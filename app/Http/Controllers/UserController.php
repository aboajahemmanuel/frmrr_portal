<?php
namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\Group;
use App\Models\User;
use App\Models\UsersPending;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * create a new instance of the class
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

           if (!Auth::user()->hasPermissionTo('user-list')) {
            abort(403, 'Unauthorized action.');
        }
      

        
        //$data = User::all();
        $query = User::orderBy('created_at', 'desc');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = $query->paginate(10);



        

        $groups = Group::all();
        $roles  = Role::pluck('name', 'name')->all();

        return view('users.index', compact('data', 'roles', 'groups'));
    }

    public function Adminusers(Request $request)
    {

        if (!Auth::user()->hasPermissionTo('user-list')) {
            abort(403, 'Unauthorized action.');
        }
      
        $user = Auth::user();
        $permission = 'user-approve';

        $authoriser = User::where('group_id', $user->group_id)->where('status', 1)
            ->permission($permission)
            ->get();




        // Check if the user has the 'view-all-categories' permission
        $canViewAllUsers = $user->hasPermissionTo('view-all-users');

        // Fetch categories based on group_id or include all if the user has the required permission
        $query = User::where(function ($query) use ($user, $canViewAllUsers) {
            // Condition to filter categories by the user's group
            if (!$canViewAllUsers) {
                $query->where('group_id', $user->group_id);
            }
            $query->where('usertype', '=', 'internal');
            $query->where('status', '!=', 4);
        });

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = $query->orderBy('created_at', 'desc')
            ->paginate(10);

        

        $groups = Group::where('status', 1)->get();
        $roles  = Role::where('status', 1)->pluck('name', 'name');

        return view('users.index', compact('data', 'roles', 'groups', 'authoriser'));
    }

    public function Deactivated(Request $request)
    {

        
        if (!Auth::user()->hasPermissionTo('user-list')) {
            abort(403, 'Unauthorized action.');
        }
      
        $user = Auth::user();
        $permission = 'user-approve';

        $authoriser = User::where('group_id', $user->group_id)->where('status', 1)
            ->permission($permission)
            ->get();




        // Check if the user has the 'view-all-categories' permission
        $canViewAllUsers = $user->hasPermissionTo('view-all-users');

        // Fetch categories based on group_id or include all if the user has the required permission
        $query = User::where(function ($query) use ($user, $canViewAllUsers) {
            // Condition to filter categories by the user's group
            if (!$canViewAllUsers) {
                $query->where('group_id', $user->group_id);
            }
            $query->where('usertype', '=', 'internal');
            $query->whereIn('status', [4, 5]);

            
        });

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = $query->orderBy('created_at', 'desc')
            ->paginate(10);

        

        $groups = Group::where('status', 1)->get();
        $roles  = Role::where('status', 1)->pluck('name', 'name');

        return view('users.deactivated', compact('data', 'roles', 'groups', 'authoriser'));
    }

    public function ExternalUsers(Request $request)
    {
        $query = User::with('subscriptions')->where('usertype', '=', 'external');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('company_name')) {
            $query->where('company_name', 'like', '%' . $request->company_name . '%');
        }

        $data = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('users.external', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        $user    = User::find($user_id);

        //  return $request;
        $password = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 6);
        $this->validate($request, [
            // 'name' => 'required',
            'email'    => 'required|email|unique:users,email',
            //'password' => 'required|confirmed',
            'roles'    => 'required',
            'group_id' => 'required',
            'usertype' => 'required',
        ]);

        $name = $request->fname . ' ' . $request->lname;

        $input         = $request->all();
        $input['name'] = $name;

        $input['password']    = Hash::make($password);
        $input['admin_group'] = $user->group_id;

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        $user_pending              = new UsersPending();
        $user_pending->user_id     = $user->id;
        $user_pending->name        = $user->name;
        $user_pending->email       = $user->email;
        $user_pending->password    = $password;
        $user_pending->inputer_id  = Auth::user()->id;
        $user_pending->status      = 0;
        $user_pending->action_type = 'Insert';

        $user_pending->save();

        $action = $request['name'];
        $title  = 'Please be advised that a new User (' . $action . ') has been created and is awaiting your review and approval.';
        LogActivity::addToLog(' User (' . $name . ') Profile creation request by ' . Auth::user()->name);

        $authorise_email = User::where('id', $request->authorizer_id)->first();

        $authorise_email = $authorise_email->email;

        // Notify users after the application is created
        $this->InsertnotifyUsers($action, $title, $authorise_email);

        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that User (' . $action . ') has been created.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

        return redirect()->back()->with('success', 'User created successfully.');
    }

    public function update(Request $request, $id)
    {
       
        $this->validate($request, [
            // 'name'  => 'required',
            // 'email' => 'required|email|unique:users,email,' . $id,
            // 'password' => 'confirmed',
            // 'roles' => 'required',
        ]);

         $name = $request->fname . ' ' . $request->lname;
        $user = User::find($id);

        $user_pending              = new UsersPending();
        $user_pending->user_id     = $user->id;
        $user_pending->name        = $name;
        $user_pending->email       = $request->email;
        $user_pending->group_id    = $request->group_id;
        $user_pending->roles       = json_encode($request->input('roles'));
        $user_pending->inputer_id  = Auth::user()->id;
        $user_pending->status      = 0;
        $user_pending->action_type = 'Edit';

        $user_pending->save();

        $user->status = 0;
        $user->save();

        // Prepare the notification details
        $action = $request->input('name');
        $title  = 'Please be advised that the User (' . $action . ') has been updated and is awaiting your review and approval.';
        LogActivity::addToLog(' User (' . $name . ') Profile update request created by ' . Auth::user()->name);

        // Get the authorizer's email (assuming you have a way to identify the authorizer)
        $authorise_email = User::where('id', $request->authorizer_id)->value('email');

        // Notify the authorizer after the user update is submitted for approval
        $this->InsertnotifyUsers($action, $title, $authorise_email);

        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that User (' . $action . ') has been update.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

        return redirect()->back()->with('success', 'User update submitted for approval successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $user_id     = Auth::user()->id;
        $user_addmin = User::find($user_id);

        $user = User::find($id);

        $user->status = 3;

        $user->save();

        $user_pending              = new Userspending();
        $user_pending->user_id     = $id;
        $user_pending->inputer_id  = Auth::user()->id;
        $user_pending->status      = 0;
        $user_pending->action_type = 'Delete';

        $user_pending->save();

        $authoriserUsers = User::where('admin_group', $user_addmin->admin_group)->where('status', 1)->get();

        // $authoriserUsers = User::role('Super_Administrator_Authoriser')->get();

        $title = 'user';

        foreach ($authoriserUsers as $userAdmin) {
            $email_data = [
                'email'  => $userAdmin->email,
                'action' => $user->name,
                'title'  => $title,
            ];

            Mail::send('emails.ActiondeletePending', $email_data, function ($message) use ($email_data) {
                $message->to($email_data['email'])
                    ->subject('Awaiting Approval')
                ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));

            });
        }

        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    public function statususer(Request $request, $id)
    {
        // return $request;
        $this->validate($request, [
            'status' => 'required',

        ]);

        $user = User::find($id);

        if ($request->status == 1) {

            $user_pending              = new Userspending();
            $user_pending->user_id     = $id;
            $user_pending->inputer_id  = Auth::user()->id;
            $user_pending->status      = 0;
            $user_pending->action_type = 'Enable';

            $user_pending->save();
            LogActivity::addToLog(' User (' . $user->name . ') Profile enable request by ' . Auth::user()->name);

            $user->status = 5;
            $user->save();
        }
        if ($request->status == 4) {

            $user_pending              = new Userspending();
            $user_pending->user_id     = $id;
            $user_pending->inputer_id  = Auth::user()->id;
            $user_pending->status      = 0;
            $user_pending->action_type = 'Disabled';

            $user_pending->name     = $user->name;
            $user_pending->email    = $user->email;
            $user_pending->group_id = $user->group_id;
            $user_pending->roles    = json_encode($request->input('roles'));
            //$user_pending->roles = json_encode($user->roles);

            LogActivity::addToLog(' User (' . $user->name . ') Profile disable Request by ' . Auth::user()->name);

            $user_pending->save();

            $user->status = 0;
            $user->save();
        }

        $action = $user->name;
        $title  = 'Please be advised that the User (' . $action . ') status has been updated and is awaiting your review and approval.';

        // Get the authorizer's email (assuming you have a way to identify the authorizer)
        $authorise_email = User::where('id', $request->authorizer_id)->value('email');

        // Notify the authorizer after the user update is submitted for approval
        $this->InsertnotifyUsers($action, $title, $authorise_email);

        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that User (' . $action . ') status updated.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

        return redirect()->back()->with('success', 'User status successfully updated.');
    }

    public function userstatus(Request $request, $id)
    {
        $update_status = User::find($id);
        $update_status_pending = Userspending::where('status', 0)
            ->whereNull('authorizer_id')
            ->where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$update_status_pending) {
            return redirect()->back()->with('error', 'No pending request found for this user.');
        }

        try {
            return DB::transaction(function () use ($request, $update_status, $update_status_pending) {
                if ($request->status == 1) {
                    $this->processApproval($update_status, $update_status_pending);
                    $msg = 'Request approved.';
                } else {
                    $this->processRejection($request, $update_status, $update_status_pending);
                    $msg = 'Request rejected.';
                }

                $this->logAndNotifySuccess($update_status, $update_status_pending, $request->status);

                $redirect = ($update_status_pending->action_type == 'Enable' && $request->status == 2) 
                    ? 'deactivated' 
                    : 'admin_users';

                return Redirect::to($redirect)->with('success', $msg);
            });
        } catch (\Exception $e) {
            Log::error('User status update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    private function processApproval($user, $pending)
    {
        $pending->status = 1;
        $pending->authorizer_id = Auth::id();
        $pending->save();

        switch ($pending->action_type) {
            case 'Delete':
                Role::find($user->id)->delete();
                break;

            case 'Edit':
                $user->name = $pending->name;
                $user->group_id = $pending->group_id;
                $user->status = 1;
                $user->save();

                DB::table('model_has_roles')->where('model_id', $user->id)->delete();
                $user->assignRole(json_decode($pending->roles, true));
                break;

            case 'Insert':
                $user->status = 1;
                $user->save();
                break;

            case 'Disabled':
                $user->status = 4;
                $pending->status = 4;
                $user->save();
                $pending->save();
                break;

            case 'Enable':
                $user->status = 1;
                $user->save();
                break;
        }
    }

    private function processRejection($request, $user, $pending)
    {
        $pending->status = $request->status;
        $pending->note = $request->note;
        $pending->authorizer_id = Auth::id();
        $pending->save();

        $user->note = $request->note;

        switch ($pending->action_type) {
            case 'Insert':
                $user->status = 2;
                break;
            case 'Disabled':
            case 'Edit':
                $user->status = 1;
                break;
            case 'Enable':
                $user->status = 4;
                break;
        }
        $user->save();
    }

    private function logAndNotifySuccess($user, $pending, $decision)
    {
        $action = $user->name;
        $inputter_email = Auth::user()->email;
        $isApprove = ($decision == 1);

        if ($isApprove) {
            switch ($pending->action_type) {
                case 'Delete':
                    $this->ApprovenotifyDeletion($action);
                    $title = "User ($action) Delete request approved.";
                    break;
                case 'Edit':
                case 'Insert':
                    $this->ApprovenotifyUsersnew($action);
                    $title = "User ($action) Profile " . ($pending->action_type == 'Edit' ? 'update' : 'creation') . " request approved.";
                    if ($pending->action_type == 'Insert') {
                        $email_data = [
                            'title'      => 'New User Notification',
                            'name'       => $pending->name,
                            'email'      => $pending->email,
                            'password'   => $pending->password,
                            'created_at' => $pending->created_at,
                        ];
                        Mail::to($email_data['email'])->queue(new \App\Mail\NotifyNewUser($email_data));
                    }
                    break;
                case 'Disabled':
                    $this->ApprovenotifyUsersDisable($action);
                    $title = "User ($action) Profile disabled request approved.";
                    break;
                case 'Enable':
                    $this->ApprovenotifyUsersEnable($action);
                    $title = "User ($action) Profile enable request approved.";
                    break;
            }
            LogActivity::addToLog(" User ($action) Profile {$pending->action_type} request approved by " . Auth::user()->name);
        } else {
            $this->ApprovenotifyReject($action, $pending->note);
            $title = "User ($action) Profile {$pending->action_type} request rejected.";
            LogActivity::addToLog(" User ($action) Profile {$pending->action_type} request rejected by " . Auth::user()->name);
        }

        $this->insertNotifyInputter($action, "Please be advised that $title", $inputter_email);
    }

    private function ApproveNotifyNewUser($userName, $userEmail, $userPassword, $dateCreated)
    {
        try {
            $email_data = [
                'title'        => 'New User',
                'email'        => $userEmail,
                'action'       => $userName,
                'userPassword' => $userPassword,

            ];

            Mail::to($userEmail)->queue(new \App\Mail\NotifyNewUser($email_data));

            // Log successful email queueing
            Log::info('Queued email to notify new user', ['email' => $userEmail]);
        } catch (\Exception $e) {
            // Improved error logging with more descriptive message
            Log::error('Failed to queue email for new user notification', [
                'email' => $userEmail,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function insertNotifyUsers($action, $title, $authorise_email)
    {
        try {

            $email_data = [
                'email'  => $authorise_email,
                'action' => $action,
                'title'  => $title,
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

            $title = 'Please be informed the User (' . $action . ') has been approved.';

            foreach ($inputter as $user) {
                $email_data = [
                    'email'  => $user->email,
                    'title'  => $title,
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
            $role        = 'Super_Administrator_Inputter';

            // Retrieve all users in the same group with the specified role
            $inputters = User::where('group_id', $currentUser->group_id)
                ->role($role)
                ->get();

            // Prepare the email content
            $title = 'Please be advised that the Role (' . e($action) . ') has been rejected and requires your attention.';

            // Loop through the users and queue the email for each
            foreach ($inputters as $inputter) {
                $emailData = [
                    'email'  => $inputter->email,
                    'title'  => $title,
                    'action' => $action,
                    'note'   => $note,
                ];

                Mail::to($inputter->email)->queue(new \App\Mail\NotifyUserApplicationReject($emailData));
            }
        } catch (\Exception $e) {
            Log::error('Failed to queue emails for Inputter', ['error' => $e->getMessage()]);
        }
    }

    private function ApprovenotifyUsersEnable($action)
    {
        try {
            $user = Auth::user();
            $role = 'Super_Administrator_Inputter';

            $inputter = User::where('group_id', $user->group_id)
                ->role($role)
                ->get();

            $title = 'Please be informed the User (' . $action . ') status has been updated';

            foreach ($inputter as $user) {
                $email_data = [
                    'email'  => $user->email,
                    'title'  => $title,
                    'action' => $action,
                ];

                Mail::to($user->email)->queue(new \App\Mail\NotifyUser($email_data));
            }
        } catch (\Exception $e) {
            Log::error('Failed to queue emails for Inputter', ['error' => $e->getMessage()]);
        }
    }

    private function ApprovenotifyUsersDisable($action)
    {
        try {
            $user = Auth::user();
            $role = 'Super_Administrator_Inputter';

            $inputter = User::where('group_id', $user->group_id)
                ->role($role)
                ->get();

            $title = 'Please be informed the User (' . $action . ') status has been updated';

            foreach ($inputter as $user) {
                $email_data = [
                    'email'  => $user->email,
                    'title'  => $title,
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
                'email'  => $inputter_email,
                'action' => $action,
                'title'  => $inputter_title,
            ];

            Mail::to($inputter_email)->queue(new \App\Mail\NotifyUser($email_data));
            // }
        } catch (\Exception $e) {
            Log::error('Failed to queue emails for authorisers', ['error' => $e->getMessage()]);
        }
    }
}
