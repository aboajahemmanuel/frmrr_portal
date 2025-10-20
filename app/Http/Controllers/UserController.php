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
        //$data = User::all();
        $data = User::orderBy('created_at', 'desc')->get();

        $groups = Group::all();
        $roles  = Role::pluck('name', 'name')->all();

        return view('users.index', compact('data', 'roles', 'groups'));
    }

    public function Adminusers(Request $request)
    {

        $user = Auth::user();
        $role = 'Super_Administrator_Authoriser';

        $authoriser = User::where('group_id', $user->group_id)
            ->role($role)
            ->get();

        $data = User::where('usertype', '=', 'internal')
            ->where('status', '!=', 4)
            ->orderBy('created_at', 'desc')
            ->get();

        $groups = Group::where('status', 1)->get();
        $roles  = Role::where('status', 1)->pluck('name', 'name');

        return view('users.index', compact('data', 'roles', 'groups', 'authoriser'));
    }

    public function Deactivated(Request $request)
    {

        $user = Auth::user();
        $role = 'Super_Administrator_Authoriser';

        $authoriser = User::where('group_id', $user->group_id)
            ->role($role)
            ->get();

        $data = User::where('usertype', 'internal')
            ->whereIn('status', [4, 5])
            ->orderBy('created_at', 'desc')
            ->get();

        $groups = Group::where('status', 1)->get();
        $roles  = Role::where('status', 1)->pluck('name', 'name');

        return view('users.deactivated', compact('data', 'roles', 'groups', 'authoriser'));
    }

    public function ExternalUsers(Request $request)
    {
        // return "2";
        //$data = User::all();
        //  $data = User::where('usertype', '=', 'external')->orderBy('created_at', 'desc')->get();

        $data = User::with('subscriptions')->where('usertype', '=', 'external')->get();

        // return   $data = User::whereHas('subscriptions', function ($query) {
        //     $query->where('status', 1);
        // })->get();

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
        LogActivity::addToLog(' User (' . $request['name'] . ')  created  by ' . Auth::user()->name);

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
        //return $request;
        $this->validate($request, [
            'name'  => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            //'password' => 'confirmed',
            'roles' => 'required',
        ]);

        $user = User::find($id);

        $user_pending              = new UsersPending();
        $user_pending->user_id     = $user->id;
        $user_pending->name        = $request->name;
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
        LogActivity::addToLog(' User (' . $request['name'] . ')  updated  by ' . Auth::user()->name);

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
                    ->from('no-reply@fmdqgroup.com', 'Financial Markets Regulations & Rules Repository Portal');
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
            LogActivity::addToLog(' User (' . $request['name'] . ')  Enabled  by ' . Auth::user()->name);

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

            LogActivity::addToLog(' User (' . $request['name'] . ')  Disabled  by ' . Auth::user()->name);

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
        $request;

        $update_status         = User::find($id);
        $update_status_pending = Userspending::where('status', 0)->where(
            'authorizer_id',
            null
        )->where('user_id', $id)->orderBy('created_at', 'desc')->first();

        if ($update_status_pending->action_type == 'Delete' && $request->status == 1) {
            $update_status_pending->status        = 1;
            $update_status_pending->authorizer_id = Auth::user()->id;
            $update_status_pending->save();

            Role::find($id)->delete();

            $action = $update_status->name;

            $this->ApprovenotifyDeletion($action);

            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that User (' . $action . ') Delete request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

            return Redirect::to('admin_users')->with('success', 'Request approved.');

            //  return redirect()->back()->with('success', 'Request approved.');
        }

        if ($update_status_pending->action_type == 'Edit' && $request->status == 1) {

            // Check if the role already exists
            $user = User::find($id);

            $user->name     = $update_status_pending->name;
            $user->email    = $update_status_pending->email;
            $user->group_id = $update_status_pending->group_id;

            $user->status = 1;
            $user->save();

            DB::table('model_has_roles')->where(
                'model_id',
                $user->id
            )->delete();
            $user->assignRole(json_decode($update_status_pending->roles, true));

            $update_status_pending->authorizer_id = Auth::id();
            $update_status_pending->status        = 1;
            $update_status_pending->save();

            $action = $update_status->name;

            $this->ApprovenotifyUsersnew($action);

            LogActivity::addToLog(' User (' . $request['name'] . ')  update request approved  by ' . Auth::user()->name);

            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that User (' . $action . ') Update request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

            return Redirect::to('admin_users')->with('success', 'Request approved.');

            // return redirect()->back()->with('success', 'Request approved successfully.');
        }

        if ($update_status_pending->action_type == 'Insert' && $request->status == 1) {

            $update_status->status = $request->status;

            $update_status_pending->status        = $request->status;
            $update_status_pending->authorizer_id = Auth::id(); // Assuming the user is authenticated

            $update_status->save();
            $update_status_pending->save();

            $action = $update_status->name;

            $this->ApprovenotifyUsersnew($action);

            $email_data = [
                'title'      => 'New User Notification', // Ensure title is added
                'name'       => $update_status_pending->name,
                'email'      => $update_status_pending->email,
                'password'   => $update_status_pending->password,
                'created_at' => $update_status_pending->created_at,
            ];

            // Queue the email
            Mail::to($email_data['email'])->queue(new \App\Mail\NotifyNewUser($email_data));

            LogActivity::addToLog(' User (' . $update_status->name . ')  insert request approved  by ' . Auth::user()->name);

            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that User (' . $action . ') Insert request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

            return Redirect::to('admin_users')->with('success', 'Request approved.');

            // return redirect()->back()->with('success', 'Request approved successfully.');
        }

        if ($update_status_pending->action_type == 'Disabled' && $request->status == 1) {

            $update_status->status = 4;

            $update_status_pending->status        = 4;
            $update_status_pending->authorizer_id = Auth::id(); // Assuming the user is authenticated

            $update_status->save();
            $update_status_pending->save();

            $action = $update_status->name;

            $this->ApprovenotifyUsersDisable($action);

            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that User (' . $action . ') Disabled request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

            LogActivity::addToLog(' User (' . $update_status->name . ')  Disabled request approved  by ' . Auth::user()->name);
            return Redirect::to('admin_users')->with('success', 'Request approved.');

            //return redirect()->back()->with('success', 'Request approved successfully.');
        }

        if ($update_status_pending->action_type == 'Enable' && $request->status == 1) {

            $update_status->status = $request->status;

            $update_status_pending->status        = $request->status;
            $update_status_pending->authorizer_id = Auth::id(); // Assuming the user is authenticated

            $update_status->save();
            $update_status_pending->save();

            $action = $update_status->name;

            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that User (' . $action . ') Enable request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

            $this->ApprovenotifyUsersEnable($action);
            LogActivity::addToLog(' User (' . $update_status->name . ')  Enable request approved  by ' . Auth::user()->name);

            return Redirect::to('admin_users')->with('success', 'Request approved.');

            // return redirect()->back()->with('success', 'Request approved successfully.');
        }

        if ($update_status_pending->action_type == 'Insert' && $request->status == 2) {

            $update_status->status                = $request->status;
            $update_status_pending->status        = $request->status;
            $update_status_pending->note          = $request->note;
            $update_status->note                  = $request->note;
            $update_status_pending->authorizer_id = Auth::user()->id;

            $update_status->save();
            $update_status_pending->save();

            $action = $update_status->name;
            $note   = $request->note;

            $this->ApprovenotifyReject($action, $note);

            LogActivity::addToLog(' User (' . $update_status->name . ')  Request rejected by ' . Auth::user()->name);

            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that User (' . $action . ') Request rejected.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

            return Redirect::to('admin_users')->with('success', 'Request rejected.');

            // return redirect()->back()->with('success', 'Request rejected.');
        }

        if ($update_status_pending->action_type == 'Disabled' && $request->status == 2) {

            $update_status->status                = 1;
            $update_status_pending->status        = $request->status;
            $update_status_pending->note          = $request->note;
            $update_status->note                  = $request->note;
            $update_status_pending->authorizer_id = Auth::user()->id;

            $update_status->save();
            $update_status_pending->save();

            $action = $update_status->name;
            $note   = $request->note;

            $this->ApprovenotifyReject($action, $note);

            LogActivity::addToLog(' User (' . $update_status->name . ')  Request rejected by ' . Auth::user()->name);

            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that User (' . $action . ') Request rejected.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

            return Redirect::to('admin_users')->with('success', 'Request rejected.');

            // return redirect()->back()->with('success', 'Request rejected.');
        }

        if ($update_status_pending->action_type == 'Enable' && $request->status == 2) {

            $update_status->status                = 4;
            $update_status_pending->status        = $request->status;
            $update_status_pending->note          = $request->note;
            $update_status->note                  = $request->note;
            $update_status_pending->authorizer_id = Auth::user()->id;

            $update_status->save();
            $update_status_pending->save();

            $action = $update_status->name;
            $note   = $request->note;

            $this->ApprovenotifyReject($action, $note);

            LogActivity::addToLog(' User (' . $update_status->name . ')  Request rejected by ' . Auth::user()->name);

            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that User (' . $action . ') Request rejected.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

            return Redirect::to('deactivated')->with('success', 'Request rejected.');

            // return redirect()->back()->with('success', 'Request rejected.');
        }

        if ($update_status_pending->action_type == 'Edit' && $request->status == 2) {

            $update_status->status                = 1;
            $update_status_pending->status        = $request->status;
            $update_status_pending->note          = $request->note;
            $update_status->note                  = $request->note;
            $update_status_pending->authorizer_id = Auth::user()->id;

            $update_status->save();
            $update_status_pending->save();

            $action = $update_status->name;
            $note   = $request->note;

            $this->ApprovenotifyReject($action, $note);

            LogActivity::addToLog(' User (' . $update_status->name . ')  Request rejected by ' . Auth::user()->name);

            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that User (' . $action . ') Request rejected.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

            return Redirect::to('admin_users')->with('success', 'Request rejected.');

            // return redirect()->back()->with('success', 'Request rejected.');
        }
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
