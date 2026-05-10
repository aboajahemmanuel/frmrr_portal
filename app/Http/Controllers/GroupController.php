<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\GroupPending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:group-list|group-create|group-edit|group-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:group-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:group-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:group-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('group-list')) {
            abort(403, 'Unauthorized action.');
        }

        $user = Auth::user();
        $permission = 'group-approve';
        $authoriser = User::where('group_id', $user->group_id)->where('status', 1)
            ->permission($permission)
            ->get();


             // Check if the user has the 'view-all-groups' permission
        $canViewAllGroups = $user->hasPermissionTo('view-all-groups');

        // Fetch groups based on group_id or include all if the user has the required permission
        $query = Group::where(function ($query) use ($user, $canViewAllGroups) {
            // Condition to filter groups by the user's group
            $query->where('group_id', $user->group_id);

            // If the user has permission to view all groups, include them
            if ($canViewAllGroups) {
                $query->orWhereNotNull('id'); // This will include all groups
            }
        });

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $data = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('groups.index', compact('data', 'authoriser'));
    }


    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);


        $this->validate($request, [

            'name' => 'required|unique:groups,name'
        ]);



        $new_group = new Group();
        $new_group->name = $request['name'];
        $new_group->group_id = $user->group_id;
        $new_group->status = 0;

        $new_group->save();



        $group_pending = new GroupPending();

        $group_pending->inputer_id = Auth::user()->id;
        $group_pending->status = 0;
        $group_pending->name = $request['name'];
        $group_pending->group_id = $new_group->id;;
        $group_pending->action_type = 'Insert';

        $group_pending->save();



        $action =  $request['name'];
        $title = 'Please be advised that a new Group (' . $action . ') has been created and is awaiting your review and approval.';

        LogActivity::addToLog('Group (' . $request['name'] . ') Group creation request by ' . Auth::user()->name);






        $authorise_email =  User::where('id', $request->authorizer_id)->first();


        $authorise_email =  $authorise_email->email;

        // Notify users after the application is created
        $this->InsertnotifyUsers($action, $title, $authorise_email);


        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that a new Group (' . $action . ') has been created.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);



        return Redirect::back()->with('success', 'Group submitted for approval successfully.');
    }







    public function edit(Request $request, $id)
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);

        $this->validate($request, [
            'name' => 'required|string|max:255', // Ensuring 'name' is a string and not too long
        ]);

        $group = Group::find($id);
        if (!$group) {
            return redirect()->back()->with('error', 'Group not found.');
        }

        // Check if another group with the same name already exists, excluding the current one
        $existingGroup = Group::where('name', $request->input('name'))
            ->where('id', '!=', $id) // Exclude the current group from the check
            ->first();

        if ($existingGroup) {
            // Group with the same name exists, return with error
            return redirect()->back()->with('error', 'A group with the given name already exists.');
        }

        // If the group doesn't exist, proceed with the update



        $group->status = $request->input('status');


        $group->save();


        $group_pending = new groupPending();
        $group_pending->group_id =  $id;
        $group_pending->name =   $request->input('name');
        $group_pending->inputer_id = Auth::user()->id;
        $group_pending->status = 0;
        $group_pending->action_type = 'Edit';

        $group_pending->save();




        $action =  $request['name'];
        $title = 'Please be informed  the group (' . $action . ') has been updated and is awaiting your review and approval.';
        LogActivity::addToLog(' Group (' . $request['name'] . ')  Group update request by ' . Auth::user()->name);



        $authorise_email =  User::where('id', $request->authorizer_id)->first();


        $authorise_email =  $authorise_email->email;

        // Notify users after the application is created
        $this->InsertnotifyUsers($action, $title, $authorise_email);


        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that Group (' . $action . ') has been updated.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

        return redirect()->back()->with('success', 'Group update submitted for approval successfully.');
    }




    public function delete(Request $request, $id)
    {

        $user_id = Auth::user()->id;
        $user = User::find($user_id);


        $group = Group::find($id);

        $group->status = 3;

        $group->save();





        $group_pending = new GroupPending();
        $group_pending->group_id =  $id;
        $group_pending->inputer_id = Auth::user()->id;
        $group_pending->status = 0;
        $group_pending->action_type = 'Delete';

        $group_pending->save();


        $action =  $group->name;
        $title = 'Please be advised that the group(' . $action . ') has been deleted and is awaiting your review and approval.';
        LogActivity::addToLog(' Group (' . $group->name . ') Group deletion request by ' . Auth::user()->name);






        $authorise_email =  User::where('id', $request->authorizer_id)->first();


        $authorise_email =  $authorise_email->email;

        // Notify users after the application is created
        $this->InsertnotifyUsers($action, $title, $authorise_email);

        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that Group (' . $action . ') has been deleted.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

        return Redirect::back()->with('success', 'Group deleted successfully.');
    }










    public function groupstatus(Request $request, $id)
    {
        $update_status = Group::find($id);
        $update_status_pending = GroupPending::where('status', 0)
            ->whereNull('authorizer_id')
            ->where('group_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$update_status_pending) {
            return redirect()->back()->with('error', 'No pending request found for this group.');
        }

        try {
            return DB::transaction(function () use ($request, $update_status, $update_status_pending) {
                if ($request->status == 1) {
                    $this->processGroupApproval($update_status, $update_status_pending);
                    $msg = 'Request approved.';
                } else {
                    $this->processGroupRejection($request, $update_status, $update_status_pending);
                    $msg = 'Request rejected.';
                }

                $this->logAndNotifyGroupSuccess($update_status, $update_status_pending, $request->status);

                return Redirect::to('groups')->with('success', $msg);
            });
        } catch (\Exception $e) {
            Log::error('Group status update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    private function processGroupApproval($group, $pending)
    {
        $pending->status = 1;
        $pending->authorizer_id = Auth::user()->id;
        $pending->save();

        switch ($pending->action_type) {
            case 'Delete':
                $group->delete();
                break;
            case 'Edit':
                $group->name = $pending->name;
                $group->status = 1;
                $group->save();
                break;
            case 'Insert':
                $group->status = 1;
                $group->save();
                break;
        }
    }

    private function processGroupRejection($request, $group, $pending)
    {
        $pending->status = $request->status;
        $pending->note = $request->note;
        $pending->authorizer_id = Auth::user()->id;
        $pending->save();

        $group->note = $request->note;

        switch ($pending->action_type) {
            case 'Delete':
            case 'Edit':
                $group->status = 1;
                break;
            case 'Insert':
                $group->status = 2;
                break;
        }
        $group->save();
    }

    private function logAndNotifyGroupSuccess($group, $pending, $decision)
    {
        $action = $group->name;
        $inputter_email = Auth::user()->email;
        $isApprove = ($decision == 1);

        if ($isApprove) {
            switch ($pending->action_type) {
                case 'Delete':
                    $this->ApprovenotifyDeletion($action);
                    $title = "Group ($action) Delete request approved.";
                    LogActivity::addToLog(" Group ($action) Group deletion request approved by " . Auth::user()->name);
                    break;
                case 'Edit':
                    $this->ApprovenotifyUsersnew($action);
                    $title = "Group ($action) Update request approved.";
                    LogActivity::addToLog(" Group ($action) Group update request approved by " . Auth::user()->name);
                    break;
                case 'Insert':
                    $this->ApprovenotifyUsersnew($action);
                    $title = "Group ($action) Group creation request approved.";
                    LogActivity::addToLog("Group ($action) Group creation request approved by " . Auth::user()->name);
                    break;
            }
        } else {
            $this->ApprovenotifyReject($action, $pending->note);
            $type = ($pending->action_type == 'Insert') ? 'creation' : strtolower($pending->action_type);
            $title = "Group ($action) Group $type request rejected.";
            LogActivity::addToLog("Group ($action) Group $type request rejected by " . Auth::user()->name);
        }

        $this->insertNotifyInputter($action, "Please be advised that $title", $inputter_email);
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
            // Log::error('Failed to queue emails for Inputter', ['error' => $e->getMessage()]);
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
            // Log::error('Failed to queue emails for Inputter', ['error' => $e->getMessage()]);
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
            // Log::error('Failed to queue emails for Inputter', ['error' => $e->getMessage()]);
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
