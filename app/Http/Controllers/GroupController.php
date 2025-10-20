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
        $user = Auth::user();
        $role = 'Super_Administrator_Authoriser';

        $authoriser = User::where('group_id', $user->group_id)
            ->role($role)
            ->get();

        $group_mem = User::where('group_id', $user->group_id)->first();




        $data = Group::where('group_id', $group_mem->group_id)->orderBy('created_at', 'desc')->get();

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

        LogActivity::addToLog(' Group (' . $request['name'] . ') created  by ' . Auth::user()->name);






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
        LogActivity::addToLog(' Group (' . $request['name'] . ') updated by ' . Auth::user()->name);



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
        LogActivity::addToLog(' Group (' . $request['name'] . ') deleted by ' . Auth::user()->name);






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
        //  return        $request;

        $update_status = Group::find($id);
        $update_status_pending = GroupPending::where('status', 0)->where(
            'authorizer_id',
            null
        )->where('group_id', $id)->orderBy('created_at', 'desc')->first();



        if ($update_status_pending->action_type == 'Delete' &&  $request->status == 1) {
            $update_status_pending->status = 1;
            $update_status_pending->authorizer_id = Auth::user()->id;
            $update_status_pending->save();

            LogActivity::addToLog(' Group (' . $update_status->name . ') Delete request approved by ' . Auth::user()->name);

            $action = $update_status->name;

            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that  Group (' . $action . ') Delete request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);


            Group::find($id)->delete();





            $this->ApprovenotifyDeletion($action);




            return Redirect::to('groups')->with('success', 'Request approved.');
            //return redirect()->back()->with('success', 'Request approved.');
        }







        if ($update_status_pending->action_type == 'Edit' && $request->status == 1) {
            $update_status->name = $update_status_pending->name;
            $update_status->status = $request->status;

            $update_status_pending->status = $request->status;
            $update_status_pending->authorizer_id = Auth::id(); // Assuming the user is authenticated

            $update_status->save();
            $update_status_pending->save();

            $action = $update_status->name;

            $this->ApprovenotifyUsersnew($action);
            LogActivity::addToLog(' Role (' . $update_status->name . ') Update request approved by ' . Auth::user()->name);



            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that  Group (' . $action . ') Update request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);



            return Redirect::to('groups')->with('success', 'Request approved.');
        }



        if ($update_status_pending->action_type == 'Insert' &&  $request->status == 1) {

            $update_status->status = $request->status;

            $update_status_pending->status = $request->status;
            $update_status_pending->authorizer_id = Auth::id(); // Assuming the user is authenticated

            $update_status->save();
            $update_status_pending->save();

            $action = $update_status->name;

            $this->ApprovenotifyUsersnew($action);
            LogActivity::addToLog(' Role (' . $update_status->name . ') Insert request approved by ' . Auth::user()->name);



            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that  Group (' . $action . ') Insert request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);


            return Redirect::to('groups')->with('success', 'Request approved.');
        }





        if ($update_status_pending->action_type == 'Delete'  && $request->status == 2) {


            $update_status->status = 2;
            $update_status_pending->status = $request->status;
            $update_status_pending->note = $request->note;
            $update_status->note = $request->note;
            $update_status_pending->authorizer_id = Auth::user()->id;


            $update_status->save();
            $update_status_pending->save();

            $action = $update_status->name;
            $note = $request->note;


            $this->ApprovenotifyReject($action, $note);

            LogActivity::addToLog(' Role (' . $update_status->name . ') Request rejected by ' . Auth::user()->name);


            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that Group (' . $action . ') Request rejected.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);


            return Redirect::to('groups')->with('success', 'Request rejected.');
        }




        if ($update_status_pending->action_type == 'Edit' && $request->status == 2) {



            $update_status->status = 1;
            $update_status_pending->status = $request->status;
            $update_status_pending->note = $request->note;
            $update_status->note = $request->note;
            $update_status_pending->authorizer_id = Auth::user()->id;


            $update_status->save();
            $update_status_pending->save();

            $action = $update_status->name;
            $note = $request->note;


            $this->ApprovenotifyReject($action, $note);

            LogActivity::addToLog(' Role (' . $update_status->name . ') Request rejected by ' . Auth::user()->name);


            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that Group (' . $action . ') Request rejected.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);


            return Redirect::to('groups')->with('success', 'Request rejected.');
        }



        if ($update_status_pending->action_type == 'Insert' &&  $request->status == 2) {

            // return $request->note;

            $update_status->status = $request->status;
            $update_status_pending->status = $request->status;
            $update_status_pending->note = $request->note;
            $update_status->note = $request->note;
            $update_status_pending->authorizer_id = Auth::user()->id;


            $update_status->save();
            $update_status_pending->save();

            $action = $update_status->name;
            $note = $request->note;


            $this->ApprovenotifyReject($action, $note);

            LogActivity::addToLog(' Role (' . $update_status->name . ') Request rejected by ' . Auth::user()->name);


            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that Group (' . $action . ') Request rejected.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);


            return Redirect::to('groups')->with('success', 'Request rejected.');


            // $this->notifyUsersOfRejection($update_status->name, $request->note);
            //return redirect()->back()->with('success', 'Request rejected.');
        }
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
            Log::error('Failed to queue emails for Inputter', ['error' => $e->getMessage()]);
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
            Log::error('Failed to queue emails for Inputter', ['error' => $e->getMessage()]);
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
            Log::error('Failed to queue emails for Inputter', ['error' => $e->getMessage()]);
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
