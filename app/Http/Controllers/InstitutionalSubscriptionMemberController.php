<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\InstitutionalSubscriptionMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Helpers\LogActivity;

class InstitutionalSubscriptionMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function ownerSubscription($user)
    {
        return Subscription::with('subscriptionPlan')
            ->where('user_id', $user->id)
            ->where('status', 1)
            ->where('end_date', '>=', Carbon::now())
            ->whereHas('subscriptionPlan', function ($query) {
                $query->where('seat_limit', '>', 1);
            })
            ->latest('end_date')
            ->first();
    }

    public function index()
    {
        $user = Auth::user();
        $subscription = $this->ownerSubscription($user);

        if (!$subscription) {
            return redirect()->route('profile')->with('error', 'You do not have an active team-eligible subscription.');
        }

        $members = InstitutionalSubscriptionMember::with('member')
            ->where('owner_subscription_id', $subscription->id)
            ->where('status', InstitutionalSubscriptionMember::STATUS_ACTIVE)
            ->orderBy('created_at', 'desc')
            ->get();

        $seatLimit = $subscription->subscriptionPlan->seat_limit;
        $seatsUsed = $members->count() + 1; // +1 for the owner's own seat

        return view('institutional_members.index', compact('subscription', 'members', 'seatLimit', 'seatsUsed'));
    }

    public function invite(Request $request)
    {
        $user = Auth::user();
        $subscription = $this->ownerSubscription($user);

        if (!$subscription) {
            return redirect()->route('profile')->with('error', 'You do not have an active team-eligible subscription.');
        }

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'No registered user was found with this email. They must create an FMRR Portal account first.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $seatLimit = $subscription->subscriptionPlan->seat_limit;
        $activeCount = InstitutionalSubscriptionMember::where('owner_subscription_id', $subscription->id)
            ->where('status', InstitutionalSubscriptionMember::STATUS_ACTIVE)
            ->count();

        if ($activeCount + 1 >= $seatLimit) {
            return redirect()->back()->with('error', 'All seats on this subscription have been used.');
        }

        $newMember = User::where('email', $request->email)->first();

        if ($newMember->id == $user->id) {
            return redirect()->back()->with('error', 'You are already the owner of this subscription.');
        }

        $exists = InstitutionalSubscriptionMember::where('owner_subscription_id', $subscription->id)
            ->where('member_user_id', $newMember->id)
            ->where('status', InstitutionalSubscriptionMember::STATUS_ACTIVE)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'This user has already been added to your team.');
        }

        try {
            return DB::transaction(function () use ($user, $subscription, $newMember) {
                $seat = new Subscription();
                $seat->user_id = $newMember->id;
                $seat->subscription_plan_id = $subscription->subscription_plan_id;
                $seat->start_date = Carbon::now();
                $seat->end_date = $subscription->end_date;
                $seat->status = 1;
                $seat->save();

                $member = new InstitutionalSubscriptionMember();
                $member->owner_subscription_id = $subscription->id;
                $member->owner_user_id = $user->id;
                $member->email = $newMember->email;
                $member->member_user_id = $newMember->id;
                $member->member_subscription_id = $seat->id;
                $member->status = InstitutionalSubscriptionMember::STATUS_ACTIVE;
                $member->save();

                $this->notifyGrantedAccess($newMember->email, $user->name, $subscription->subscriptionPlan->name);

                LogActivity::addToLog('Team member (' . $newMember->email . ') added by ' . $user->name);

                return redirect()->back()->with('success', $newMember->email . ' has been added to your team.');
            });
        } catch (\Exception $e) {
            Log::error('Institutional member add failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function remove(Request $request, $id)
    {
        $user = Auth::user();
        $subscription = $this->ownerSubscription($user);

        if (!$subscription) {
            return redirect()->route('profile')->with('error', 'You do not have an active team-eligible subscription.');
        }

        $member = InstitutionalSubscriptionMember::where('owner_subscription_id', $subscription->id)->findOrFail($id);

        try {
            return DB::transaction(function () use ($member, $user) {
                $member->status = InstitutionalSubscriptionMember::STATUS_REMOVED;
                $member->save();

                if ($member->member_subscription_id) {
                    Subscription::where('id', $member->member_subscription_id)->update(['status' => 0]);
                }

                LogActivity::addToLog('Team member (' . $member->email . ') removed by ' . $user->name);

                return redirect()->back()->with('success', 'Member removed.');
            });
        } catch (\Exception $e) {
            Log::error('Institutional member removal failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    private function notifyGrantedAccess($email, $ownerName, $planName)
    {
        try {
            Mail::to($email)->queue(new \App\Mail\NotifyUser([
                'email' => $email,
                'title' => $ownerName . ' has granted you access to the FMRR Portal via their ' . $planName . ' subscription.',
                'action' => 'Institutional Subscription Access',
            ]));
        } catch (\Exception $e) {
            Log::error('Failed to queue institutional member access email', ['error' => $e->getMessage()]);
        }
    }
}
