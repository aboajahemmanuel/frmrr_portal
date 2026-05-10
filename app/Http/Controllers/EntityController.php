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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $permission = 'entity-approve';
        $authoriser = User::where('group_id', $user->group_id)->where('status', 1)
            ->permission($permission)
            ->get();


             // Check if the user has the 'view-all-categories' permission
        $canViewAllGroups = $user->hasPermissionTo('view-all-entities');

        // Fetch categories based on group_id or include all if the user has the required permission
        $query = Entity::where(function ($query) use ($user, $canViewAllGroups) {
            // Condition to filter categories by the user's group
            $query->where('group_id', $user->group_id);

            // If the user has permission to view all categories, include them
            if ($canViewAllGroups) {
                $query->orWhereNotNull('id'); // This will include all categories
            }
        });

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $data = $query->orderBy('created_at', 'desc')
            ->paginate(10);

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
        LogActivity::addToLog(' Entity (' . $request['name'] . ') Entity creation request created  by ' . Auth::user()->name);




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
        LogActivity::addToLog(' Entity (' . $request['name'] . ') Entity update request created  by ' . Auth::user()->name);



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
        LogActivity::addToLog(' Entity (' . $entity->name . ') Entity deletion request created  by ' . Auth::user()->name);


        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that  Entity (' . $action . ') Entity deletion request created.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);



        $authorise_email =  User::where('id', $request->authorizer_id)->first();


        $authorise_email =  $authorise_email->email;

        // Notify users after the application is created
        $this->InsertnotifyUsers($action, $title, $authorise_email);


        return redirect()->back()->with('success', 'Entity deletion request submitted for approval successfully.');
    }




    public function entitystatus(Request $request, $id)
    {
        $update_status = Entity::find($id);
        $update_status_pending = EntityPending::where('status', 0)
            ->whereNull('authorizer_id')
            ->where('entity_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$update_status_pending) {
            return redirect()->back()->with('error', 'No pending request found for this entity.');
        }

        try {
            return DB::transaction(function () use ($request, $update_status, $update_status_pending) {
                if ($request->status == 1) {
                    $this->processEntityApproval($update_status, $update_status_pending);
                    $msg = 'Request approved.';
                } else {
                    $this->processEntityRejection($request, $update_status, $update_status_pending);
                    $msg = 'Request rejected.';
                }

                $this->logAndNotifyEntitySuccess($update_status, $update_status_pending, $request->status);

                return Redirect::to('entities')->with('success', $msg);
            });
        } catch (\Exception $e) {
            Log::error('Entity status update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    private function processEntityApproval($entity, $pending)
    {
        $pending->status = 1;
        $pending->authorizer_id = Auth::id();
        $pending->save();

        switch ($pending->action_type) {
            case 'Delete':
                $entity->delete();
                break;
            case 'Edit':
                $entity->name = $pending->name;
                $entity->status = 1;
                $entity->admin_status = 1;
                $entity->save();
                break;
            case 'Insert':
                $entity->status = 1;
                $entity->admin_status = 1;
                $entity->save();
                break;
        }
    }

    private function processEntityRejection($request, $entity, $pending)
    {
        $pending->status = $request->status;
        $pending->note = $request->note;
        $pending->authorizer_id = Auth::id();
        $pending->save();

        $entity->note = $request->note;
        $entity->admin_status = $request->status;

        switch ($pending->action_type) {
            case 'Insert':
                $entity->status = $request->status;
                break;
            case 'Delete':
            case 'Edit':
                $entity->admin_status = 1;
                break;
        }
        $entity->save();
    }

    private function logAndNotifyEntitySuccess($entity, $pending, $decision)
    {
        $action = $entity->name;
        $inputter_email = Auth::user()->email;
        $isApprove = ($decision == 1);

        if ($isApprove) {
            switch ($pending->action_type) {
                case 'Delete':
                    $this->ApprovenotifyDeletion($action);
                    $title = "Entity ($action) Entity deletion request approved.";
                    LogActivity::addToLog(" Entity ($action) Entity deletion request approved by " . Auth::user()->name);
                    break;
                case 'Edit':
                    $this->ApprovenotifyUsersnew($action);
                    $title = "Entity ($action) Entity update request approved.";
                    LogActivity::addToLog(" Entity ($action) Entity update request approved by " . Auth::user()->name);
                    break;
                case 'Insert':
                    $this->ApprovenotifyUsersnew($action);
                    $title = "Entity ($action) Entity creation request approved.";
                    LogActivity::addToLog(" Entity ($action) Entity creation request approved by " . Auth::user()->name);
                    break;
            }
        } else {
            $this->ApprovenotifyReject($action, $pending->note);
            $type = ($pending->action_type == 'Insert') ? 'creation' : strtolower($pending->action_type);
            $title = "Entity ($action) Entity $type request rejected.";
            LogActivity::addToLog(" Entity ($action) Entity $type request rejected by " . Auth::user()->name);
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
