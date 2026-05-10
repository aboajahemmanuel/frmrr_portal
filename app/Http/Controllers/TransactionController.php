<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use App\Models\SubscriptionPlansPending;
use App\Helpers\LogActivity;

class TransactionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:subscription-list|subscription-create|subscription-edit|subscription-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:subscription-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:subscription-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:subscription-delete', ['only' => ['destroy']]);
    }



    public function index(Request $request)
    {


        $data = Transaction::orderBy('created_at', 'desc')->get();
        return view('transactions.index', compact('data'));
    }



    public function subscribers(Request $request)
    {

          if (!Auth::user()->hasPermissionTo('subscription-list')) {
            abort(403, 'Unauthorized action.');
        }


        $query = Subscription::with(['user', 'subscriptionPlan'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        $data = $query->paginate(10);
        
        return view('transactions.subscribers', compact('data'));
    }




    public function subcription_plan(Request $request)
    {

        if (!Auth::user()->hasPermissionTo('subscription-list')) {
            abort(403, 'Unauthorized action.');
        }

            $user = Auth::user();
        $permission = 'subscription-approve';
        $authoriser = User::where('group_id', $user->group_id)->where('status', 1)
            ->permission($permission)
            ->get();


             // Check if the user has the 'view-all-categories' permission
        $canViewAllMarketTag = $user->hasPermissionTo('view-all-subscriptions');

        // Fetch categories based on group_id or include all if the user has the required permission
        $query = SubscriptionPlan::where(function ($query) use ($user, $canViewAllMarketTag) {
            // Condition to filter categories by the user's group
            $query->where('group_id', $user->group_id);

            // If the user has permission to view all categories, include them
            if ($canViewAllMarketTag) {
                $query->orWhereNotNull('id'); // This will include all categories
            }
        });

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $data = $query->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('transactions.subscriptionPlan', compact('data', 'authoriser'));
    }



    public function addSubcription(Request $request)
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);

        // Create subscription record
        $subscription = new SubscriptionPlan();
        $subscription->name = $request->name;
        $subscription->price = $request->price;
        $subscription->duration = $request->duration;
        $subscription->description = $request->description;
        $subscription->group_id = $user->group_id;
        $subscription->notification_days = $request->notification_days ?? 0;


        $subscription->save();


        $subscription_pending = new SubscriptionPlansPending();
        $subscription_pending->subscription_plans_id =  $subscription->id;

        $subscription_pending->name =  $subscription->name;
        $subscription_pending->duration =  $subscription->duration;
        $subscription_pending->price =  $subscription->price;
        $subscription_pending->description =  $subscription->description;
        $subscription_pending->notification_days =  $subscription->notification_days;

        $subscription_pending->inputer_id = Auth::user()->id;
        $subscription_pending->status = 0;
        $subscription_pending->action_type = 'Insert';

        $subscription_pending->save();


        $action =  $request['name'];
        $title = 'Please be advised that a new Subscription Plan (' . $action . ') has been created and is awaiting your review and approval.';
        LogActivity::addToLog(' Subscription Plan (' . $request['name'] . ') Subscription Plan creation Request submitted by ' . Auth::user()->name);



        $authorise_email =  User::where('id', $request->authorizer_id)->first();


        $authorise_email =  $authorise_email->email;

        // Notify users after the application is created
        $this->InsertnotifyUsers($action, $title, $authorise_email);


        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised  subscription (' . $action . ') has been created.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

        return redirect()->back()->with('success', 'Subscription plan created successfully.');
    }


    public function updateSubcription(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $subscription = SubscriptionPlan::findOrFail($id);
                $subscription->admin_status = 0;
                $subscription->save();

                $pending = new SubscriptionPlansPending();
                $pending->subscription_plans_id = $subscription->id;
                $pending->name = $request->name;
                $pending->duration = $request->duration;
                $pending->price = $request->price;
                $pending->description = $request->description;
                $pending->notification_days = $request->notification_days ?? 0;
                $pending->inputer_id = Auth::id();
                $pending->status = 0;
                $pending->action_type = 'Edit';
                $pending->save();

                $action = $request->name;
                $title = "Please be advised that a new Subscription Plan ($action) has been updated and is awaiting your review and approval.";
                LogActivity::addToLog(" Subscription Plan ($action) Subscription Plan Update Request by " . Auth::user()->name);

                $authoriser = User::findOrFail($request->authorizer_id);
                $this->InsertnotifyUsers($action, $title, $authoriser->email);
                $this->insertNotifyInputter($action, "Please be advised subscription ($action) has been updated.", Auth::user()->email);

                return redirect()->back()->with('success', 'Subscription plan updated successfully.');
            });
        } catch (\Exception $e) {
            Log::error('Subscription update request failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function deleteSubcription(Request $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                $subscription = SubscriptionPlan::findOrFail($id);
                $subscription->admin_status = 3;
                $subscription->save();

                $pending = new SubscriptionPlansPending();
                $pending->subscription_plans_id = $id;
                $pending->inputer_id = Auth::id();
                $pending->status = 0;
                $pending->action_type = 'Delete';
                $pending->save();

                $action = $subscription->name;
                $title = "Please be advised that the Subscription plan ($action) has been deleted and is awaiting your review and approval.";
                LogActivity::addToLog(" Subscription Plan ($action) Subscription Plan Deletion Request by " . Auth::user()->name);

                $authoriser = User::findOrFail($request->authorizer_id);
                $this->InsertnotifyUsers($action, $title, $authoriser->email);
                $this->insertNotifyInputter($action, "Please be advised subscription ($action) has been deleted.", Auth::user()->email);

                return redirect()->back()->with('success', 'Subscription plan deleted successfully.');
            });
        } catch (\Exception $e) {
            Log::error('Subscription deletion request failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function Subcriptionstatus(Request $request, $id)
    {
        $subscription = SubscriptionPlan::find($id);
        $pending = SubscriptionPlansPending::where('subscription_plans_id', $id)
            ->where('status', 0)
            ->whereNull('authorizer_id')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$pending) {
            return redirect()->back()->with('error', 'No pending request found for this subscription plan.');
        }

        try {
            return DB::transaction(function () use ($request, $subscription, $pending) {
                if ($request->status == 1) {
                    $this->processSubscriptionApproval($subscription, $pending);
                    $msg = 'Request approved successfully.';
                } else {
                    $this->processSubscriptionRejection($request, $subscription, $pending);
                    $msg = 'Request rejected.';
                }

                $this->logAndNotifySubscriptionSuccess($subscription, $pending, $request->status);

                return redirect()->back()->with('success', $msg);
            });
        } catch (\Exception $e) {
            Log::error('Subscription status update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    private function processSubscriptionApproval($subscription, $pending)
    {
        $pending->status = 1;
        $pending->authorizer_id = Auth::id();
        $pending->save();

        switch ($pending->action_type) {
            case 'Delete':
                $subscription->delete();
                break;
            case 'Edit':
                $subscription->name = $pending->name;
                $subscription->duration = $pending->duration;
                $subscription->price = $pending->price;
                $subscription->description = $pending->description;
                $subscription->notification_days = $pending->notification_days;
                $subscription->status = 1;
                $subscription->admin_status = 1;
                $subscription->save();
                break;
            case 'Insert':
                $subscription->status = 1;
                $subscription->admin_status = 1;
                $subscription->save();
                break;
        }
    }

    private function processSubscriptionRejection($request, $subscription, $pending)
    {
        $pending->status = $request->status;
        $pending->note = $request->note;
        $pending->authorizer_id = Auth::id();
        $pending->save();

        $subscription->note = $request->note;
        $subscription->admin_status = ($pending->action_type == 'Insert') ? $request->status : 1;
        if ($pending->action_type == 'Edit') {
            $subscription->status = 1;
        } elseif ($pending->action_type == 'Insert') {
            $subscription->status = $request->status;
        }
        $subscription->save();
    }

    private function logAndNotifySubscriptionSuccess($subscription, $pending, $decision)
    {
        $action = $subscription->name;
        $inputter_email = Auth::user()->email;
        $isApprove = ($decision == 1);

        if ($isApprove) {
            $type = ($pending->action_type == 'Insert') ? 'Creation' : $pending->action_type;
            $title = "Subscription Plan ($action) $type request approved.";
            LogActivity::addToLog(" Subscription Plan ($action) Subscription Plan $type Request approved by " . Auth::user()->name);

            if ($pending->action_type == 'Delete') {
                $this->ApprovenotifyDeletion($action);
            } else {
                $this->ApprovenotifyUsersnew($action);
            }
        } else {
            $this->ApprovenotifyReject($action, $pending->note);
            $type = ($pending->action_type == 'Insert') ? 'Creation' : $pending->action_type;
            $title = "Subscription Plan ($action) $type request rejected.";
            LogActivity::addToLog(" Subscription Plan ($action) Subscription Plan $type Request rejected by " . Auth::user()->name);
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
            // }
        } catch (\Exception $e) {
            Log::error('Failed to queue emails for authorisers', ['error' => $e->getMessage()]);
        }
    }




    private function ApprovenotifyUsersnew($action)
    {
        try {
            $user = Auth::user();
            $role = 'Super_Administrator_Inputter';

            $inputter = User::where('group_id', $user->group_id)
                ->role($role)
                ->get();



            $title = 'Please be informed  the group (' . $action . ') has been approved.';

            foreach ($inputter as $user) {
                $email_data = [
                    'email' => $user->email,
                    'title' => $title,
                    'action' => $action,
                ];

                Mail::to($user->email)->queue(new \App\Mail\NotifyUser($email_data));
            }
        } catch (\Exception $e) {
            //Log::error('Failed to queue emails for Inputter', ['error' => $e->getMessage()]);
        }
    }






    private function ApprovenotifyReject($action, $note)
    {
        try {
            $currentUser = Auth::user();
            $role = 'Super_Administrator_Inputter';

            // Retrieve all users in the same group with the specified role
            $inputters = User::where('group_id', $currentUser->group_id)
                ->role($role)
                ->get();

            // Prepare the email content
            $title = 'Please be advised that the group (' . e($action) . ') has been rejected and requires your attention.';

            // Loop through the users and queue the email for each
            foreach ($inputters as $inputter) {
                $emailData = [
                    'email' => $inputter->email,
                    'title' => $title,
                    'action' => $action,
                    'note' => $note,
                ];

                Mail::to($inputter->email)->queue(new \App\Mail\NotifyUserApplicationReject($emailData));
            }
        } catch (\Exception $e) {
            //Log::error('Failed to queue emails for Inputter', ['error' => $e->getMessage()]);
        }
    }






    private function ApprovenotifyDeletion($action)
    {
        try {
            $user = Auth::user();
            $role = 'Super_Administrator_Inputter';

            $inputter = User::where('group_id', $user->group_id)
                ->role($role)
                ->get();



            $title = 'Please be informed  the group (' . $action . ') has been deleted.';

            foreach ($inputter as $user) {
                $email_data = [
                    'email' => $user->email,
                    'title' => $title,
                    'action' => $action,
                ];

                Mail::to($user->email)->queue(new \App\Mail\NotifyUser($email_data));
            }
        } catch (\Exception $e) {
            //Log::error('Failed to queue emails for Inputter', ['error' => $e->getMessage()]);
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
            // }
        } catch (\Exception $e) {
            Log::error('Failed to queue emails for authorisers', ['error' => $e->getMessage()]);
        }
    }
}
