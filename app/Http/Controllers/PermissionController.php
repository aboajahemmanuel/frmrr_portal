<?php

namespace App\Http\Controllers;

use DB;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PendingPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:permission-list|permission-create|permission-edit|permission-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:permission-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:permission-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
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

        //$data = Permission::all();
        $data = Permission::orderBy('id', 'DESC')->get();

        return view('permissions.index', compact('data', 'authoriser'));
    }






    public function store(Request $request)
    {
        // Get the group ID of the logged-in user
        $group_id = Auth::user()->group_id;

        // Validate the request data
        $this->validate($request, [
            'name' => 'required|unique:permissions,name',
        ]);



        $permission = Permission::create([
            'name' => $request->input('name'),
            'group_id' => $group_id,
        ]);

        $pen_permission =  $permission->id;

        // Create the permission and store in the pending_permissions table
        $pendingPermission = PendingPermission::create([
            'name' => $request->input('name'),
            'permission_id' => $pen_permission,
            //  'group_id' => $group_id,
            'inputter_id' => Auth::user()->id,
            'status' => 0, // Initial status is pending
            'action_type' => 'Insert', // Indicate this is a new permission creation
        ]);

        // Prepare notification details
        $action = $request->input('name');
        $title = 'Please be advised that a new Permission (' . $action . ') has been created and is awaiting your review and approval.';

        // Get the authorizer's email (assumes you have a way to identify who the authorizer is)
        $authorise_email = User::where('id', $request->authorizer_id)->value('email');

        // Notify the authorizer after the permission is submitted for approval

        $this->InsertnotifyUsers($action, $title, $authorise_email);



        return redirect()->back()->with('success', 'Permission submitted for approval successfully.');
    }






    public function update(Request $request, $id)
    {
        // Get the group ID of the logged-in user
        $group_id = Auth::user()->group_id;

        // Validate the request data
        $this->validate($request, [
            'name' => 'required|unique:permissions,name,' . $id,
        ]);

        // Find the existing permission
        $permission = Permission::findOrFail($id);

        // Store the current permission ID for later use
        $pen_permission = $permission->id;

        // Update the permission's name and group_id (but don't save yet)
        $permission->status = 0;
        $permission->save();


        // Create an entry in the pending_permissions table for approval
        $pendingPermission = PendingPermission::create([
            'name' => $request->input('name'),
            'permission_id' => $pen_permission,
            'inputter_id' => Auth::user()->id,
            'status' => 0, // Initial status is pending
            'action_type' => 'Edit', // Indicate that this is an update
        ]);

        // Prepare notification details
        $action = $request->input('name');
        $title = 'Please be advised that the Permission (' . $action . ') has been updated and is awaiting your review and approval.';

        // Get the authorizer's email (assumes you have a way to identify who the authorizer is)
        $authorise_email = User::where('id', $request->authorizer_id)->value('email');

        // Notify the authorizer after the permission update is submitted for approval
        $this->InsertnotifyUsers($action, $title, $authorise_email);

        return redirect()->back()->with('success', 'Permission update submitted for approval successfully.');
    }



    // public function update(Request $request, $id)
    // {
    //     $this->validate($request, [
    //         'name' => 'required|string|max:255', // Ensuring 'name' is a string and not too long
    //     ]);

    //     $permission = Permission::find($id);
    //     if (!$permission) {
    //         return redirect()->back()->with('error', 'Permission not found.');
    //     }

    //     // Check if another permission with the same name already exists, excluding the current one
    //     $existingPermission = Permission::where('name', $request->input('name'))
    //         ->where('id', '!=', $id) // Exclude the current permission from the check
    //         ->first();

    //     if ($existingPermission) {
    //         // Permission with the same name exists, return with error
    //         return redirect()->back()->with('error', 'A permission with the given name already exists.');
    //     }

    //     // If the permission doesn't exist, proceed with the update
    //     $permission->name = $request->input('name');
    //     $permission->save();

    //     return redirect()->back()->with('success', 'Permission updated successfully.');
    // }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     Permission::find($id)->delete();

    //     return redirect()->back()->with('success', 'Permission deleted successfully.');
    // }




    public function destroy(Request $request, $id)
    {

        $user_id = Auth::user()->id;
        $user = User::find($user_id);


        $permission = Permission::find($id);

        $permission->status = 3;

        $permission->save();





        $pen_permission = new PendingPermission();
        $pen_permission->permission_id =  $id;
        $pen_permission->inputter_id = Auth::user()->id;
        $pen_permission->status = 0;
        $pen_permission->action_type = 'Delete';

        $pen_permission->save();


        $action =  $permission->name;
        $title = 'Please be advised that the Permission (' . $action . ') has been deleted and is awaiting your review and approval.';



        $authorise_email =  User::where('id', $request->authorizer_id)->first();


        $authorise_email =  $authorise_email->email;

        // Notify users after the application is created
        $this->InsertnotifyUsers($action, $title, $authorise_email);

        return redirect()->back()->with('success', 'Permission deleted submitted for approval successfully.');
    }






    public function permissionstatus(Request $request, $id)
    {
        // return  $request;


        //return   $request->status;

        $update_status = Permission::find($id);
        $update_status_pending = PendingPermission::where('status', 0)->where(
            'authorizer_id',
            null
        )->where('permission_id', $id)->orderBy('created_at', 'desc')->first();



        if ($update_status_pending->action_type == 'Delete' &&  $request->status == 1) {
            //return  $request;
            $update_status_pending->status = 1;
            $update_status_pending->authorizer_id = Auth::user()->id;
            $update_status_pending->save();


            Permission::find($id)->delete();



            $action = $update_status->name;

            $this->ApprovenotifyUsersnewDEL($action);



            return redirect()->back()->with('success', 'Request approved.');
        }









        if ($update_status_pending->action_type == 'Edit' && $request->status == 1) {



            // Check if the role already exists
            $role = Permission::find($id);

            // Decode permissions from JSON if necessary
            $permissions = json_decode($update_status_pending->permissions, true);

            $role->name = $update_status_pending->name;
            $role->status = 1;
            $role->syncPermissions($permissions); // Sync permissions
            $role->save();




            $update_status_pending->authorizer_id = Auth::id();
            $update_status_pending->status = 1;
            $update_status_pending->save();






            $action = $update_status->name;

            $this->ApprovenotifyUsersnew($action);

            return redirect()->back()->with('success', 'Request approved successfully.');
        }



        if ($update_status_pending->action_type == 'Insert' &&  $request->status == 1) {





            $update_status->status = $request->status;

            $update_status_pending->status = $request->status;
            $update_status_pending->authorizer_id = Auth::id(); // Assuming the user is authenticated

            $update_status->save();
            $update_status_pending->save();

            $action = $update_status->name;

            $this->ApprovenotifyUsersnew($action);

            return redirect()->back()->with('success', 'Request approved successfully.');
        }





        if ($request->status == 2) {



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


            // $this->notifyUsersOfRejection($update_status->name, $request->note);
            return redirect()->back()->with('success', 'Request rejected.');
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



            $title = 'Please be informed the Permission (' . $action . ') has been approved.';

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





    private function ApprovenotifyUsersnewDEL($action)
    {
        try {
            $user = Auth::user();
            $role = 'Super_Administrator_Inputter';

            $inputter = User::where('group_id', $user->group_id)
                ->role($role)
                ->get();



            $title = 'Please be informed the Permission (' . $action . ') has been deleted.';

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
            $title = 'Please be advised that the Permission (' . e($action) . ') has been rejected and requires your attention.';

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
            $role = 'Content_Owner_Inputter';

            $inputter = User::where('group_id', $user->group_id)
                ->role($role)
                ->get();



            $title = 'Please be informed  the Permission (' . $action . ') has been deleted.';

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
}
