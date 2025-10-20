<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\Entity;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EntityPending;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\LogActivity;

class EntityController extends Controller
{
    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:entity-list|entity-create|entity-edit|entity-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:entity-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:entity-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:entity-delete', ['only' => ['destroy']]);
    }




    public function index(Request $request)
    {







        $user = Auth::user();


        $roles = ['Super_Administrator_Authoriser', 'Content_Owner_Authoriser'];

        $authoriser = User::where('group_id', $user->group_id)->where('status', 1)
            ->whereHas('roles', function ($query) use ($roles) {
                $query->whereIn('name', $roles);
            })
            ->get();



        $superAdminRole = ['Super_Administrator_Authoriser', 'Super_Administrator_Inputter'];




        // Check if the user has the 'Super_Administrator_Authoriser' role
        $hasSuperAdminRole = $user->hasRole($superAdminRole);

        // Fetch categories based on group_id or include all if the user has the Super Admin role
        $data = Entity::where(function ($query) use ($user, $hasSuperAdminRole) {
            // Condition to filter categories by the user's group
            $query->where('group_id', $user->group_id);

            // If the user has the Super Admin role, include all categories
            if ($hasSuperAdminRole) {
                $query->orWhereNotNull('id'); // This will include all categories
            }
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('entities.index', compact('data', 'authoriser'));
    }




    public function store(Request $request)
    {

        $user_id = Auth::user()->id;
        $user = User::find($user_id);

        $this->validate($request, [
            'name' => 'required|unique:entities,name',

        ]);


        $slug = Str::slug($request->name);

        $new_entity = new Entity();
        $new_entity->name = $request['name'];
        $new_entity->slug = $slug;
        $new_entity->group_id = $user->group_id;
        $new_entity->status = 0;

        $new_entity->save();



        $entity_pending = new EntityPending();
        $entity_pending->entity_id =  $new_entity->id;
        $entity_pending->inputer_id = Auth::user()->id;
        $entity_pending->status = 0;
        $entity_pending->action_type = 'Insert';

        $entity_pending->save();


        $action =  $request['name'];
        $title = 'Please be advised that a new Entity (' . $action . ') has been created and is awaiting your review and approval.';
        LogActivity::addToLog(' Entity (' . $request['name'] . ') created  by ' . Auth::user()->name);




        $authorise_email =  User::where('id', $request->authorizer_id)->first();


        $authorise_email =  $authorise_email->email;

        // Notify users after the application is created
        $this->InsertnotifyUsers($action, $title, $authorise_email);

        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that a new Entity (' . $action . ') has been created.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);


        return redirect()->back()->with('success', 'Entity created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */





    public function update(Request $request, $id)
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);


        $this->validate($request, [
            'name' => 'required|string|max:255', // Ensuring 'name' is a string and not too long
        ]);

        $entity = Entity::find($id);
        if (!$entity) {
            return redirect()->back()->with('error', 'Entity not found.');
        }

        // Check if another entity with the same name already exists, excluding the current one
        $existingEntity = Entity::where('name', $request->input('name'))
            ->where('id', '!=', $id) // Exclude the current entity from the check
            ->first();

        if ($existingEntity) {
            // Entity with the same name exists, return with error
            return redirect()->back()->with('error', 'An entity with the given name already exists.');
        }

        // If no duplicate exists, proceed with the update
        //$entity->update($request->all());



        $entity->admin_status = $request->input('status');
        $entity->group_id = $user->group_id;




        $entity->save();




        $slug = Str::slug($request->input('name'));

        $entity_pending = new EntityPending();
        $entity_pending->entity_id =  $id;
        $entity_pending->name =   $request->input('name');
        $entity_pending->slug =   $slug;

        $entity_pending->inputer_id = Auth::user()->id;
        $entity_pending->status = 0;
        $entity_pending->action_type = 'Edit';

        $entity_pending->save();




        $action =  $request['name'];
        $title = 'Please be informed  the Entity (' . $action . ') has been updated and is awaiting your review and approval.';
        LogActivity::addToLog(' Entity (' . $request['name'] . ') updated  by ' . Auth::user()->name);



        $authorise_email =  User::where('id', $request->authorizer_id)->first();


        $authorise_email =  $authorise_email->email;

        // Notify users after the application is created
        $this->InsertnotifyUsers($action, $title, $authorise_email);


        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that  Entity (' . $action . ') has been updated.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);


        return redirect()->back()->with('success', 'Entity updated successfully.');
    }



    public function destroy(Request $request, $id)
    {

        $user_id = Auth::user()->id;
        $user = User::find($user_id);



        $entity = Entity::find($id);

        $entity->admin_status = 3;

        $entity->save();





        $entity_pending = new EntityPending();
        $entity_pending->entity_id =  $id;
        $entity_pending->inputer_id = Auth::user()->id;
        $entity_pending->status = 0;
        $entity_pending->action_type = 'Delete';

        $entity_pending->save();


        $action =  $entity->name;
        $title = 'Please be advised that the group(' . $action . ') has been deleted and is awaiting your review and approval.';
        LogActivity::addToLog(' Entity (' . $entity->name . ') deleted  by ' . Auth::user()->name);


        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that  Entity (' . $action . ') has been deleted.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);



        $authorise_email =  User::where('id', $request->authorizer_id)->first();


        $authorise_email =  $authorise_email->email;

        // Notify users after the application is created
        $this->InsertnotifyUsers($action, $title, $authorise_email);


        return redirect()->back()->with('success', 'Entity deletion request submitted for approval successfully.');
    }




    public function entitystatus(Request $request, $id)
    {
        //  return        $request;

        $update_status = Entity::find($id);
        $update_status_pending = EntityPending::where('status', 0)->where(
            'authorizer_id',
            null
        )->where('entity_id', $id)->orderBy('created_at', 'desc')->first();



        if ($update_status_pending->action_type == 'Delete' &&  $request->status == 1) {
            $update_status_pending->status = 1;
            $update_status_pending->authorizer_id = Auth::user()->id;
            $update_status_pending->save();


            Entity::find($id)->delete();



            $action = $update_status->name;

            $this->ApprovenotifyDeletion($action);

            LogActivity::addToLog(' Entity (' . $update_status->name . ') Delete request approved by ' . Auth::user()->name);

            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that  Entity (' . $action . ') Delete request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);


            return Redirect::to('entities')->with('success', 'Request approved.');

            //return redirect()->back()->with('success', 'Request approved.');
        }







        if ($update_status_pending->action_type == 'Edit' && $request->status == 1) {
            $update_status->name = $update_status_pending->name;

            $update_status->status = $request->status;
            $update_status->admin_status = $request->status;


            $update_status_pending->status = $request->status;
            $update_status_pending->authorizer_id = Auth::id(); // Assuming the user is authenticated

            $update_status->save();
            $update_status_pending->save();

            $action = $update_status->name;

            $this->ApprovenotifyUsersnew($action);

            LogActivity::addToLog(' Entity (' . $update_status->name . ') Update request approved by ' . Auth::user()->name);


            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that  Entity (' . $action . ') Update request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);
            return Redirect::to('entities')->with('success', 'Request approved.');

            // return redirect()->back()->with('success', 'Request approved successfully.');
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

            LogActivity::addToLog(' Entity (' . $update_status->name . ') Insert request approved by ' . Auth::user()->name);


            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that  Entity (' . $action . ') Insert request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

            return Redirect::to('entities')->with('success', 'Request approved.');


            // return redirect()->back()->with('success', 'Request approved successfully.');
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

            LogActivity::addToLog(' Entity (' . $update_status->name . ') Request rejected by ' . Auth::user()->name);



            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that  Entity (' . $action . ') Request rejected.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);



            return Redirect::to('entities')->with('success', 'Request rejected.');

            // $this->notifyUsersOfRejection($update_status->name, $request->note);
            // return redirect()->back()->with('success', 'Request rejected.');
        }




        if ($update_status_pending->action_type == 'Delete' && $request->status == 2) {

            // return $request->note;

            //$update_status->status = $request->status;
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

            LogActivity::addToLog(' Entity (' . $update_status->name . ') Request rejected by ' . Auth::user()->name);



            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that  Entity (' . $action . ') Request rejected.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);



            return Redirect::to('entities')->with('success', 'Request rejected.');

            // $this->notifyUsersOfRejection($update_status->name, $request->note);
            // return redirect()->back()->with('success', 'Request rejected.');
        }



        if (
            $update_status_pending->action_type == 'Edit' && $request->status == 2
        ) {

            // return $request->note;

            // $update_status->status = 1;
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

            LogActivity::addToLog(' Entity (' . $update_status->name . ') Request rejected by ' . Auth::user()->name);



            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that  Entity (' . $action . ') Request rejected.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);



            return Redirect::to('entities')->with('success', 'Request rejected.');

            // $this->notifyUsersOfRejection($update_status->name, $request->note);
            // return redirect()->back()->with('success', 'Request rejected.');
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
