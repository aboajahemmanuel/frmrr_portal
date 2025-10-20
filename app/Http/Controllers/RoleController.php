<?php

namespace App\Http\Controllers;

use DB;
use App\Models\User;
use App\Models\PendingRole;
use Illuminate\Http\Request;
// use Spatie\Permission\Models\Role;
use App\Models\Role; // Use the custom Role model
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\update_status_pending;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Permission;
use App\Helpers\LogActivity;

class RoleController extends Controller
{
    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $user = Auth::user();
        $role = 'Super_Administrator_Authoriser';

        $authoriser = User::where('group_id', $user->group_id)
            ->role($role)
            ->get();






        $permission = Permission::where('status', 1)->orderBy('name', 'ASC')->get();
        $data = Role::orderBy('id', 'DESC')->get();

        return view('roles.index', compact('data', 'permission', 'role', 'authoriser'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();

        return view('roles.create', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     $this->validate($request, [
    //         'name' => 'required|unique:roles,name',
    //         'permission' => 'required',
    //     ]);

    //     $role = Role::create(['name' => $request->input('name')]);
    //     $role->syncPermissions($request->input('permission'));

    //     return redirect()->back()->with('success', 'Role created successfully.');
    // }



    public function store(Request $request)
    {
        // Validate the request
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required|array',
            'authorizer_id' => 'required|exists:users,id', // Validate the authorizer_id as an existing user
        ]);

        // Create a new role with the user's group_id
        $role = Role::create([
            'name' => $request->input('name'),
            'group_id' => Auth::user()->group_id, // Get the group_id from the authenticated user
        ]);

        // Sync permissions with the role
        $role->syncPermissions($request->input('permission'));

        // Store the data in the pending_roles table for approval
        PendingRole::create([
            'name' => $request->input('name'),
            'role_id' => $role->id,
            'inputer_id' => Auth::user()->id,
            'permissions' => json_encode($request->input('permission')), // Store permissions as JSON if it's an array
            'status' => '0', // Initial status is pending
            'action_type' => 'Insert', // Action type is insert for new role creation
        ]);

        // Log the role creation activity
        LogActivity::addToLog('Role (' . $request->input('name') . ') role created by ' . Auth::user()->name);

        // Prepare the action and title for notification
        $action = $request->input('name');
        $title = 'Please be advised that a new Role (' . $action . ') has been created and is awaiting your review and approval.';

        // Get the email of the authorizer
        $authorizer = User::findOrFail($request->authorizer_id);
        $authorizer_email = $authorizer->email;

        // Notify the authorizer
        $this->InsertnotifyUsers($action, $title, $authorizer_email);


        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that Role (' . $action . ') has been created.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Role submitted for approval successfully.');
    }









    public function reject($id)
    {
        $update_status_pending = PendingRole::findOrFail($id);
        $update_status_pending->update(['status' => 'rejected']);

        return redirect()->route('pending-roles.index')->with('success', 'Role rejected successfully.');
    }







    public function update(Request $request, $id)
    {
        // return $request;
        // Validate the request data
        $this->validate($request, [
            'name' => 'required|unique:roles,name,' . $id,
            'permission' => 'required|array',
        ]);

        // Find the existing role
        $role = Role::findOrFail($id);

        // Check if there's already a pending role with the same name
        $existingupdate_status_pending = PendingRole::where('name', $request->input('name'))
            ->where('action_type', 'Edit')
            ->where('role_id', $id)
            ->first();

        // if ($existingupdate_status_pending) {
        //     return redirect()->back()->withErrors('A pending update for this role already exists.');
        // }

        // Convert permissions array to JSON string without slashes
        $permissionsJson = json_encode($request->input('permission'));

        // Store the update in the pending_roles table for approval
        PendingRole::create([
            'name' => $request->input('name'),
            'role_id' => $role->id,
            'inputer_id' => Auth::user()->id,
            'permissions' => $permissionsJson, // Store permissions as JSON without slashes
            'status' => '0', // Initial status is pending
            'action_type' => 'Edit', // Indicate that this is an update
        ]);


        $role->admin_status = 0;
        $role->save();

        // Prepare the notification details
        $action = $request->input('name');
        // $title = 'Please be advised that the Role (' . $action . ') has been updated and is awaiting your review and approval.'


        $action =  $request['name'];
        $title = 'Please be advised that the Role (' . $action . ') has been updated and is awaiting your review and approval.';
        LogActivity::addToLog(' Role (' . $request['name'] . ')  update  by ' . Auth::user()->name);


        $authorise_email =  User::where(
            'id',
            $request->authorizer_id
        )->first();


        $authorise_email =  $authorise_email->email;

        // Notify users after the application is created
        $this->insertNotifyUsers($action, $title, $authorise_email);

        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that Role (' . $action . ') has been updated.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

        return redirect()->back()->with('success', 'Role update submitted for approval successfully.');
    }





    public function destroy(Request $request, $id)
    {

        $user_id = Auth::user()->id;
        $user = User::find($user_id);


        $role = Role::find($id);

        $role->admin_status = 3;

        $role->save();





        $role_pending = new PendingRole();
        $role_pending->role_id =  $id;
        $role_pending->inputer_id = Auth::user()->id;
        $role_pending->status = 0;
        $role_pending->action_type = 'Delete';

        $role_pending->save();


        $action =  $role->name;
        $title = 'Please be advised that the Role (' . $action . ') has been deleted and is awaiting your review and approval.';
        LogActivity::addToLog(' Role (' . $request['name'] . ') delelted   by ' . Auth::user()->name);



        $authorise_email =  User::where('id', $request->authorizer_id)->first();


        $authorise_email =  $authorise_email->email;

        // Notify users after the application is created
        $this->InsertnotifyUsers($action, $title, $authorise_email);


        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that Role (' . $action . ') has been deleted.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

        return redirect()->back()->with('success', 'Role delete submitted for approval successfully.');
    }






    public function rolestatus(Request $request, $id)
    {

        //return $request;

        $update_status = Role::find($id);
        $update_status_pending = PendingRole::where('status', 0)->where(
            'authorizer_id',
            null
        )->where('role_id', $id)->orderBy('created_at', 'desc')->first();



        if ($update_status_pending->action_type == 'Delete' &&  $request->status == 1) {
            //  $update_status_pending->status = 1;
            //return "here";
            $update_status_pending->authorizer_id = Auth::user()->id;
            $update_status_pending->save();


            LogActivity::addToLog(' Role (' . $update_status->name . ') Delete request approved by ' . Auth::user()->name);

            Role::find($id)->delete();



            $action = $update_status->name;

            $this->ApprovenotifyUsersnewDEL($action);




            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that  Role (' . $action . ') Delete request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);


            return Redirect::to('roles')->with('success', 'Request approved.');
            // return redirect()->back()->with('success', 'Request approved.');
        }









        if ($update_status_pending->action_type == 'Edit' && $request->status == 1) {




            // Check if the role already exists
            $role = Role::find($id);

            // Decode permissions from JSON if necessary
            $permissions = json_decode($update_status_pending->permissions, true);

            $role->name = $update_status_pending->name;
            $role->admin_status = 1;
            $role->syncPermissions($permissions); // Sync permissions
            $role->save();




            $update_status_pending->authorizer_id = Auth::id();
            $update_status_pending->status = 1;
            $update_status_pending->save();






            $action = $update_status->name;

            $this->ApprovenotifyUsersnew($action);
            LogActivity::addToLog(' Role (' . $update_status->name . ') Update request approved by ' . Auth::user()->name);



            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that  Role (' . $action . ') Update request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

            return Redirect::to('roles')->with('success', 'Request approved.');
        }



        if ($update_status_pending->action_type == 'Insert' &&  $request->status == 1) {

            $update_status->status = $request->status;
            $update_status->admin_status = $request->status;


            $update_status_pending->status = $request->status;
            $update_status_pending->authorizer_id = Auth::id(); // Assuming the user is authenticated

            $update_status->save();
            $update_status_pending->save();

            $action = $update_status->name;

            $this->ApprovenotifyUsersnew($action);
            LogActivity::addToLog(' Role (' . $update_status->name . ') Insert request approved by ' . Auth::user()->name);



            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that  Role (' . $action . ') Insert request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

            return Redirect::to('roles')->with('success', 'Request approved.');
        }





        if ($update_status_pending->action_type == 'Delete' && $request->status == 2) {

            // return $request->note;

            $update_status->admin_status = 1;
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
            $inputter_title = 'Please be advised that  Role (' . $action . ') Request rejected.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

            // $this->notifyUsersOfRejection($update_status->name, $request->note);
            return Redirect::to('roles')->with('success', 'Request rejected.');

            //return redirect()->back()->with('success', 'Request rejected.');
        }



        if ($update_status_pending->action_type == 'Edit' && $request->status == 2) {

            // return $request->note;

            $update_status->admin_status = 1;
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
            $inputter_title = 'Please be advised that  Role (' . $action . ') Request rejected.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

            // $this->notifyUsersOfRejection($update_status->name, $request->note);
            return Redirect::to('roles')->with('success', 'Request rejected.');

            //return redirect()->back()->with('success', 'Request rejected.');
        }


        if ($update_status_pending->action_type == 'Insert' && $request->status == 2) {

            // return $request->note;

            $update_status->status = $request->status;
            $update_status->admin_status = $request->status;
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
            $inputter_title = 'Please be advised that  Role (' . $action . ') Request rejected.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

            // $this->notifyUsersOfRejection($update_status->name, $request->note);
            return Redirect::to('roles')->with('success', 'Request rejected.');

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



            $title = 'Please be informed the Role (' . $action . ') has been approved.';

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
            $title = 'Please be advised that the Role (' . e($action) . ') has been rejected and requires your attention.';

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




    private function ApprovenotifyUsersnewDEL($action)
    {
        try {
            $user = Auth::user();
            $role = 'Super_Administrator_Inputter';

            $inputter = User::where('group_id', $user->group_id)
                ->role($role)
                ->get();



            $title = 'Please be informed the Role (' . $action . ') has been deleted.';

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
