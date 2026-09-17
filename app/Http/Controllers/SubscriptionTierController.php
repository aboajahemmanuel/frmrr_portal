<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SubscriptionTier;
use App\Models\SubscriptionTierPending;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Helpers\LogActivity;

class SubscriptionTierController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:subscription-tier-list|subscription-tier-create|subscription-tier-edit|subscription-tier-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:subscription-tier-create', ['only' => ['store']]);
        $this->middleware('permission:subscription-tier-edit', ['only' => ['update']]);
        $this->middleware('permission:subscription-tier-delete', ['only' => ['destroy']]);
        $this->middleware('permission:subscription-tier-approve|subscription-tier-reject', ['only' => ['tierStatus']]);
    }

    public function index(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('subscription-tier-list')) {
            abort(403, 'Unauthorized action.');
        }

        $user = Auth::user();
        $permission = 'subscription-tier-approve';

        $authoriser = User::where('group_id', $user->group_id)->where('status', 1)
            ->permission($permission)
            ->get();

        $canViewAll = $user->hasPermissionTo('view-all-subscriptions');

        $query = SubscriptionTier::where(function ($query) use ($user, $canViewAll) {
            $query->where('group_id', $user->group_id);

            if ($canViewAll) {
                $query->orWhereNotNull('id');
            }
        });

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $data = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('subscriptiontiers.index', compact('data', 'authoriser'));
    }

    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);

        $this->validate($request, [
            'name' => 'required|unique:subscription_tiers,name',
            'description' => 'nullable|string',
        ]);

        $slug = Str::slug($request->name);

        $tier = new SubscriptionTier();
        $tier->name = $request->name;
        $tier->group_id = $user->group_id;
        $tier->description = $request->description;
        $tier->slug = $slug;
        $tier->save();

        $tier_pending = new SubscriptionTierPending();
        $tier_pending->name = $request->name;
        $tier_pending->slug = $slug;
        $tier_pending->description = $request->description;
        $tier_pending->subscription_tier_id = $tier->id;
        $tier_pending->group_id = $user->group_id;
        $tier_pending->inputer_id = Auth::user()->id;
        $tier_pending->status = 0;
        $tier_pending->action_type = 'Insert';
        $tier_pending->save();

        $action = $request->name;
        $title = 'Please be advised that a new Subscription Tier (' . $action . ') has been created and is awaiting your review and approval.';
        LogActivity::addToLog(' Subscription Tier (' . $request->name . ') Subscription Tier creation request submitted by ' . Auth::user()->name);

        $authorise_email = User::where('id', $request->authorizer_id)->first();
        $authorise_email = $authorise_email->email;

        $this->insertNotifyUsers($action, $title, $authorise_email);

        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that Subscription Tier (' . $action . ') has been created.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

        return redirect()->back()->with('success', 'Subscription tier successfully created and pending approval.');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $tier = SubscriptionTier::find($id);
        if (!$tier) {
            return redirect()->back()->with('error', 'Subscription tier not found.');
        }

        $existing = SubscriptionTier::where('name', $request->name)
            ->where('id', '!=', $id)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'A subscription tier with the given name already exists.');
        }

        $tier->admin_status = 0;
        $tier->save();

        $slug = Str::slug($request->name);

        $tier_pending = new SubscriptionTierPending();
        $tier_pending->subscription_tier_id = $id;
        $tier_pending->name = $request->name;
        $tier_pending->slug = $slug;
        $tier_pending->description = $request->description;
        $tier_pending->inputer_id = Auth::user()->id;
        $tier_pending->status = 0;
        $tier_pending->action_type = 'Edit';
        $tier_pending->save();

        $action = $request->name;
        $title = 'Please be informed the Subscription Tier (' . $action . ') has been updated and is awaiting your review and approval.';
        LogActivity::addToLog(' Subscription Tier (' . $request->name . ') Subscription Tier update request by ' . Auth::user()->name);

        $authorise_email = User::where('id', $request->authorizer_id)->first();
        $authorise_email = $authorise_email->email;

        $this->insertNotifyUsers($action, $title, $authorise_email);

        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that Subscription Tier (' . $action . ') has been updated.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

        return redirect()->back()->with('success', 'Subscription tier updated successfully and pending approval.');
    }

    public function destroy(Request $request, $id)
    {
        $tier = SubscriptionTier::find($id);
        if (!$tier) {
            return redirect()->back()->with('error', 'Subscription tier not found.');
        }

        $tier->admin_status = 3;
        $tier->save();

        $tier_pending = new SubscriptionTierPending();
        $tier_pending->subscription_tier_id = $id;
        $tier_pending->inputer_id = Auth::user()->id;
        $tier_pending->status = 0;
        $tier_pending->action_type = 'Delete';
        $tier_pending->save();

        $action = $tier->name;
        $title = 'Please be advised that the Subscription Tier (' . $action . ') has been deleted and is awaiting your review and approval.';
        LogActivity::addToLog(' Subscription Tier (' . $action . ') Subscription Tier delete request by ' . Auth::user()->name);

        $authorise_email = User::where('id', $request->authorizer_id)->first();
        $authorise_email = $authorise_email->email;

        $this->insertNotifyUsers($action, $title, $authorise_email);

        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that Subscription Tier (' . $action . ') has been deleted.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

        return redirect()->back()->with('success', 'Subscription tier deleted successfully and pending approval.');
    }

    public function duplicate(Request $request, $id)
    {
        $tier = SubscriptionTier::find($id);
        if (!$tier) {
            return redirect()->back()->with('error', 'Subscription tier not found.');
        }

        $user = Auth::user();
        $name = $tier->name . ' (Copy)';
        $slug = Str::slug($name) . '-' . time();

        $copy = new SubscriptionTier();
        $copy->name = $name;
        $copy->slug = $slug;
        $copy->description = $tier->description;
        $copy->group_id = $user->group_id;
        $copy->save();

        $tier_pending = new SubscriptionTierPending();
        $tier_pending->subscription_tier_id = $copy->id;
        $tier_pending->name = $name;
        $tier_pending->slug = $slug;
        $tier_pending->description = $tier->description;
        $tier_pending->group_id = $user->group_id;
        $tier_pending->inputer_id = $user->id;
        $tier_pending->status = 0;
        $tier_pending->action_type = 'Insert';
        $tier_pending->save();

        $title = 'Please be advised that a new Subscription Tier (' . $name . ') has been created (duplicated from ' . $tier->name . ') and is awaiting your review and approval.';
        LogActivity::addToLog(' Subscription Tier (' . $name . ') duplicated from (' . $tier->name . ') by ' . $user->name);

        $authorise_email = User::where('id', $request->authorizer_id)->first();
        if ($authorise_email) {
            $this->insertNotifyUsers($name, $title, $authorise_email->email);
        }

        return redirect()->back()->with('success', 'Subscription tier duplicated successfully and pending approval.');
    }

    public function tierStatus(Request $request, $id)
    {
        $tier = SubscriptionTier::find($id);
        $pending = SubscriptionTierPending::where('status', 0)
            ->whereNull('authorizer_id')
            ->where('subscription_tier_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$pending) {
            return redirect()->back()->with('error', 'No pending request found for this subscription tier.');
        }

        try {
            return DB::transaction(function () use ($request, $tier, $pending) {
                if ($request->status == 1) {
                    $this->processTierApproval($tier, $pending);
                    $msg = 'Request approved.';
                } else {
                    $this->processTierRejection($request, $tier, $pending);
                    $msg = 'Request rejected.';
                }

                $this->logAndNotifyTierSuccess($tier, $pending, $request->status);

                return redirect()->back()->with('success', $msg);
            });
        } catch (\Exception $e) {
            Log::error('Subscription tier status update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    private function processTierApproval($tier, $pending)
    {
        $pending->status = 1;
        $pending->authorizer_id = Auth::id();
        $pending->save();

        switch ($pending->action_type) {
            case 'Delete':
                $tier->delete();
                break;
            case 'Edit':
                $tier->name = $pending->name;
                $tier->slug = $pending->slug;
                $tier->description = $pending->description;
                $tier->status = 1;
                $tier->admin_status = 1;
                $tier->save();
                break;
            case 'Insert':
                $tier->status = 1;
                $tier->admin_status = 1;
                $tier->save();
                break;
        }
    }

    private function processTierRejection($request, $tier, $pending)
    {
        $pending->status = $request->status;
        $pending->note = $request->note;
        $pending->authorizer_id = Auth::id();
        $pending->save();

        $tier->note = $request->note;
        $tier->admin_status = ($pending->action_type == 'Insert') ? $request->status : 1;

        if ($pending->action_type == 'Delete') {
            $tier->admin_status = 1;
        }

        $tier->save();
    }

    private function logAndNotifyTierSuccess($tier, $pending, $decision)
    {
        $action = $tier->name;
        $inputter_email = Auth::user()->email;
        $isApprove = ($decision == 1);

        if ($isApprove) {
            $type = ($pending->action_type == 'Insert') ? 'creation' : strtolower($pending->action_type);
            $title = "Subscription Tier ($action) $type request approved.";
            LogActivity::addToLog(" Subscription Tier ($action) $type request approved by " . Auth::user()->name);

            if ($pending->action_type == 'Delete') {
                $this->approveNotifyDeletion($action);
            } else {
                $this->approveNotifyUsers($action);
            }
        } else {
            $this->approveNotifyReject($action, $pending->note);
            $type = ($pending->action_type == 'Insert') ? 'creation' : strtolower($pending->action_type);
            $title = "Subscription Tier ($action) $type request rejected.";
            LogActivity::addToLog(" Subscription Tier ($action) $type request rejected by " . Auth::user()->name);
        }

        $this->insertNotifyInputter($action, "Please be advised $title", $inputter_email);
    }

    private function insertNotifyUsers($action, $title, $authorise_email)
    {
        try {
            $email_data = [
                'email' => $authorise_email,
                'action' => $action,
                'title' => $title,
            ];

            Mail::to($authorise_email)->queue(new \App\Mail\NotifyUser($email_data));
        } catch (\Exception $e) {
            Log::error('Failed to queue emails for authorisers', ['error' => $e->getMessage()]);
        }
    }

    private function insertNotifyInputter($action, $inputter_title, $inputter_email)
    {
        try {
            $email_data = [
                'email' => $inputter_email,
                'action' => $action,
                'title' => $inputter_title,
            ];

            Mail::to($inputter_email)->queue(new \App\Mail\NotifyUser($email_data));
        } catch (\Exception $e) {
            Log::error('Failed to queue emails for authorisers', ['error' => $e->getMessage()]);
        }
    }

    private function approveNotifyUsers($action)
    {
        try {
            $user = Auth::user();
            $role = 'Super_Administrator_Inputter';

            $inputter = User::where('group_id', $user->group_id)->role($role)->get();
            $title = 'Please be informed the Subscription Tier (' . $action . ') has been approved.';

            foreach ($inputter as $recipient) {
                Mail::to($recipient->email)->queue(new \App\Mail\NotifyUser([
                    'email' => $recipient->email,
                    'title' => $title,
                    'action' => $action,
                ]));
            }
        } catch (\Exception $e) {
            Log::error('Failed to queue emails for Inputter', ['error' => $e->getMessage()]);
        }
    }

    private function approveNotifyReject($action, $note)
    {
        try {
            $currentUser = Auth::user();
            $role = 'Super_Administrator_Inputter';

            $inputters = User::where('group_id', $currentUser->group_id)->role($role)->get();
            $title = 'Please be advised that the Subscription Tier (' . e($action) . ') has been rejected and requires your attention.';

            foreach ($inputters as $inputter) {
                Mail::to($inputter->email)->queue(new \App\Mail\NotifyUserApplicationReject([
                    'email' => $inputter->email,
                    'title' => $title,
                    'action' => $action,
                    'note' => $note,
                ]));
            }
        } catch (\Exception $e) {
            Log::error('Failed to queue emails for Inputter', ['error' => $e->getMessage()]);
        }
    }

    private function approveNotifyDeletion($action)
    {
        try {
            $user = Auth::user();
            $role = 'Super_Administrator_Inputter';

            $inputter = User::where('group_id', $user->group_id)->role($role)->get();
            $title = 'Please be informed the Subscription Tier (' . $action . ') has been deleted.';

            foreach ($inputter as $recipient) {
                Mail::to($recipient->email)->queue(new \App\Mail\NotifyUser([
                    'email' => $recipient->email,
                    'title' => $title,
                    'action' => $action,
                ]));
            }
        } catch (\Exception $e) {
            Log::error('Failed to queue emails for Inputter', ['error' => $e->getMessage()]);
        }
    }
}
