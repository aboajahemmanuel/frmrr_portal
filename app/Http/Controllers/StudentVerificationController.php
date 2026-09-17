<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\SubscriptionSubTier;
use App\Models\StudentVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Helpers\LogActivity;

class StudentVerificationController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:student-verification-list', ['only' => ['index', 'downloadProof']]);
        $this->middleware('permission:student-verification-approve', ['only' => ['approve']]);
        $this->middleware('permission:student-verification-reject', ['only' => ['reject']]);
    }

    public function apply(Request $request)
    {
        $user = Auth::user();

        $hasPending = StudentVerificationRequest::where('user_id', $user->id)->where('status', 0)->exists();
        $hasActiveGrant = Subscription::where('user_id', $user->id)
            ->where('status', 1)
            ->where('end_date', '>=', Carbon::now())
            ->whereHas('subscriptionPlan.subTier', function ($query) {
                $query->where('name', 'Student Research');
            })
            ->exists();

        if ($hasPending || $hasActiveGrant) {
            return redirect()->route('subscribe')->with('error', $hasPending
                ? 'You already have a Student Research application pending review.'
                : 'You already have active Student Research access.');
        }

        return view('student_verification.apply');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'institution_name' => 'required|string|max:255',
            'student_id_number' => 'nullable|string|max:100',
            'proof' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $user = Auth::user();

        $studentResearchSubTier = SubscriptionSubTier::where('name', 'Student Research')->first();
        $plan = $studentResearchSubTier ? $studentResearchSubTier->plans()->where('status', 1)->first() : null;

        $path = $request->file('proof')->store('student_verifications/' . $user->id, 'local');

        $verification = new StudentVerificationRequest();
        $verification->user_id = $user->id;
        $verification->institution_name = $request->institution_name;
        $verification->student_id_number = $request->student_id_number;
        $verification->proof_path = $path;
        $verification->subscription_plan_id = $plan?->id;
        $verification->status = 0;
        $verification->save();

        LogActivity::addToLog('Student Research verification request submitted by ' . $user->name);

        $this->notifyApplicant($user->email, 'Application received', 'Thank you for applying for Student Research access. Your application is under review, and you will be notified once a decision is made.');
        $this->notifyStaff($user->name);

        return redirect()->route('subscribe')->with('success', 'Your Student Research application has been submitted and is pending review.');
    }

    public function index(Request $request)
    {
        $query = StudentVerificationRequest::with(['applicant', 'reviewer'])->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 0);
        }

        $data = $query->paginate(10);

        return view('student_verification.index', compact('data'));
    }

    public function approve($id)
    {
        $verification = StudentVerificationRequest::findOrFail($id);

        if ($verification->status != 0) {
            return redirect()->back()->with('error', 'This request has already been decided.');
        }

        if (!$verification->subscription_plan_id) {
            return redirect()->back()->with('error', 'No Student Research plan is configured. Please set one up under Subscription Tiers first.');
        }

        try {
            return DB::transaction(function () use ($verification) {
                $subscription = new Subscription();
                $subscription->user_id = $verification->user_id;
                $subscription->subscription_plan_id = $verification->subscription_plan_id;
                $subscription->start_date = Carbon::now();
                $subscription->end_date = Carbon::now()->addDays(30);
                $subscription->status = 1;
                $subscription->save();

                $verification->status = 1;
                $verification->reviewer_id = Auth::id();
                $verification->subscription_id = $subscription->id;
                $verification->save();

                LogActivity::addToLog('Student Research application for ' . $verification->applicant->name . ' approved by ' . Auth::user()->name);

                $this->notifyApplicant($verification->applicant->email, 'Application approved', 'Your Student Research application has been approved. You now have 30 days of view-only access to the Portal.');

                return redirect()->back()->with('success', 'Application approved and access granted.');
            });
        } catch (\Exception $e) {
            Log::error('Student verification approval failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $this->validate($request, ['note' => 'required|string']);

        $verification = StudentVerificationRequest::findOrFail($id);

        if ($verification->status != 0) {
            return redirect()->back()->with('error', 'This request has already been decided.');
        }

        $verification->status = 2;
        $verification->reviewer_id = Auth::id();
        $verification->note = $request->note;
        $verification->save();

        LogActivity::addToLog('Student Research application for ' . $verification->applicant->name . ' rejected by ' . Auth::user()->name);

        try {
            Mail::to($verification->applicant->email)->queue(new \App\Mail\NotifyUserApplicationReject([
                'email' => $verification->applicant->email,
                'title' => 'Your Student Research application was not approved.',
                'action' => 'Student Research Application',
                'note' => $request->note,
            ]));
        } catch (\Exception $e) {
            Log::error('Failed to queue student verification rejection email', ['error' => $e->getMessage()]);
        }

        return redirect()->back()->with('success', 'Application rejected.');
    }

    public function downloadProof($id)
    {
        $verification = StudentVerificationRequest::findOrFail($id);

        if (!Storage::disk('local')->exists($verification->proof_path)) {
            return redirect()->back()->with('error', 'Proof file not found.');
        }

        return Storage::disk('local')->download($verification->proof_path);
    }

    private function notifyApplicant($email, $title, $action)
    {
        try {
            Mail::to($email)->queue(new \App\Mail\NotifyUser([
                'email' => $email,
                'title' => $title,
                'action' => $action,
            ]));
        } catch (\Exception $e) {
            Log::error('Failed to queue student verification applicant email', ['error' => $e->getMessage()]);
        }
    }

    private function notifyStaff($applicantName)
    {
        try {
            $staff = User::where('status', 1)->permission('student-verification-list')->get();
            $title = 'A new Student Research application from ' . $applicantName . ' is awaiting your review.';

            foreach ($staff as $reviewer) {
                Mail::to($reviewer->email)->queue(new \App\Mail\NotifyUser([
                    'email' => $reviewer->email,
                    'title' => $title,
                    'action' => 'Student Research Application',
                ]));
            }
        } catch (\Exception $e) {
            Log::error('Failed to queue student verification staff email', ['error' => $e->getMessage()]);
        }
    }
}
