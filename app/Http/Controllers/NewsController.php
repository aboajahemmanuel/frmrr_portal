<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\post;
use App\Models\User;
use App\Models\Category;
use App\Models\NewsPending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\LogActivity;

class NewsController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:post-list|post-create|post-edit|post-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:post-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:post-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:post-delete', ['only' => ['destroy']]);
    }



    public function index(Request $request)
    {



        $user = Auth::user();


        $roles = ['Super_Administrator_Authoriser', 'Super_Administrator_Inputter', 'Content_Owner_Authoriser'];

        $authoriser = User::where('group_id', $user->group_id)->where('status', 1)
            ->whereHas('roles', function ($query) use ($roles) {
                $query->whereIn('name', $roles);
            })
            ->get();



        $superAdminRole = 'Super_Administrator_Authoriser';




        // Check if the user has the 'Super_Administrator_Authoriser' role
        $hasSuperAdminRole = $user->hasRole($superAdminRole);

        // Fetch categories based on group_id or include all if the user has the Super Admin role
         $data = News::where(function ($query) use ($user, $hasSuperAdminRole) {
            // Condition to filter categories by the user's group
            $query->where('group_id', $user->group_id);

            // If the user has the Super Admin role, include all categories
            if ($hasSuperAdminRole) {
                $query->orWhereNotNull('id'); // This will include all categories
            }
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('news_alert.index', compact('data', 'authoriser'));
    }

    // public function index(Request $request)
    // {

    //     $user = Auth::user();
    //     $roles = 'Content_Owner_Authoriser';

    //     // $authoriser = User::where('group_id', $user->group_id)
    //     //     ->role($role)
    //     //     ->get();


    //     // $roles = ['Super_Administrator_Authoriser', 'Super_Administrator_Inputter', 'Content_Owner_Authoriser'];

    //     return  $authoriser = User::where('group_id', $user->group_id)->where('status', 1)
    //         ->whereHas('roles', function ($query) use ($roles) {
    //             $query->whereIn('name', $roles);
    //         })
    //         ->get();


    //     $data = News::orderBy('created_at', 'desc')->where('group_id', $user->group_id)->get();
    //     return view('news_alert.index', compact('data', 'authoriser'));
    // }


    public function add_news(Request $request)
    {


        $user = Auth::user();
        $roles = ['Super_Administrator_Authoriser', 'Content_Owner_Authoriser'];

        $authoriser = User::where('group_id', $user->group_id)->where('status', 1)
            ->whereHas('roles', function ($query) use ($roles) {
                $query->whereIn('name', $roles);
            })
            ->get();



        $data = News::all();
        return view('news_alert.add_new', compact('data', 'authoriser'));
    }


    public function edit_news(Request $request, $id)
    {
        $user = Auth::user();
        $roles = ['Super_Administrator_Authoriser', 'Content_Owner_Authoriser'];

        $authoriser = User::where('group_id', $user->group_id)->where('status', 1)
            ->whereHas('roles', function ($query) use ($roles) {
                $query->whereIn('name', $roles);
            })
            ->get();

        $news = News::where('id', $id)->first();

        // $categorieslist = Category::all();


        return view('news_alert.edit_news', compact('news', 'authoriser'));
    }






    public function view_news(Request $request, $id)
    {
        $user = Auth::user();
        $roles = ['Super_Administrator_Authoriser', 'Content_Owner_Authoriser'];

        $authoriser = User::where('group_id', $user->group_id)->where('status', 1)
            ->whereHas('roles', function ($query) use ($roles) {
                $query->whereIn('name', $roles);
            })
            ->get();
        $news = News::where('id', $id)->first();

        // $categorieslist = Category::all();


        return view('news_alert.view_news', compact('news', 'authoriser'));
    }









    public function store(Request $request)
    {

        //   return $request;
        $user_id = Auth::user()->id;
        $user = User::find($user_id);




        $new_article = new News();
        $new_article->title = $request['title'];
        $new_article->news_content = $request['news_content'];
        $new_article->group_id = $user->group_id;
        $new_article->news_content = $request['news_content'];
        $new_article->save();



        // return "here";

        $news_pending = new NewsPending();
        $news_pending->news_id =  $new_article->id;

        $news_pending->inputer_id = Auth::user()->id;
        $news_pending->status = 0;
        $news_pending->title = $request['title'];
        $news_pending->news_content = $request['news_content'];
        //$news_pending->group_id = $user->group_id;
        $news_pending->action_type = 'Insert';

        $news_pending->save();


        $action =  $request['title'];
        $title = 'Please be advised that a new News Alert (' . $action . ') has been created and is awaiting your review and approval.';
        LogActivity::addToLog(' News Alert  (' . $request['title'] . ') created  by ' . Auth::user()->name);






        $authorise_email =  User::where('id', $request->authorizer_id)->first();


        $authorise_email =  $authorise_email->email;

        // Notify users after the application is created
        $this->InsertnotifyUsers($action, $title, $authorise_email);


        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that News Alert (' . $action . ') has been created.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

        return Redirect::to('news')->with('success', 'News alert  created successfully.');

        //return Redirect::back()->with('success', 'Group submitted for approval successfully.');
    }







    public function update(Request $request, $id)
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);

        $this->validate($request, [
            'title' => 'required|string|max:255', // Ensuring 'name' is a string and not too long
        ]);

        $news = News::find($id);
        if (!$news) {
            return redirect()->back()->with('error', 'Group not found.');
        }

        // Check if another group with the same name already exists, excluding the current one
        $existingGroup = News::where('title', $request->input('title'))
            ->where('id', '!=', $id) // Exclude the current group from the check
            ->first();

        if ($existingGroup) {
            // Group with the same name exists, return with error
            return redirect()->back()->with('error', 'A news with the given title already exists.');
        }

        // If the group doesn't exist, proceed with the update



        $news->admin_status = 0;


        $news->save();


        $news_pending = new NewsPending();
        $news_pending->news_id =  $id;
        $news_pending->title = $request['title'];
        $news_pending->news_content = $request['news_content'];
        $news_pending->inputer_id = Auth::user()->id;
        $news_pending->status = 0;
        $news_pending->action_type = 'Edit';

        $news_pending->save();




        $action =  $request['name'];
        $title = 'Please be informed  the News alert (' . $action . ') has been updated and is awaiting your review and approval.';
        LogActivity::addToLog(' News Alert  (' . $request['title'] . ') updated   by ' . Auth::user()->name);



        $authorise_email =  User::where('id', $request->authorizer_id)->first();


        $authorise_email =  $authorise_email->email;

        // Notify users after the application is created
        $this->InsertnotifyUsers($action, $title, $authorise_email);


        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that News Alert (' . $action . ') has been updated.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);



        return Redirect::to('news')->with('success', 'News alert  update submitted for approval successfully.');


        //return redirect()->back()->with('success', 'Group update submitted for approval successfully.');
    }




    public function delete(Request $request, $id)
    {

        $user_id = Auth::user()->id;
        $user = User::find($user_id);


        $group = News::find($id);

        $group->admin_status = 3;

        $group->save();





        $news_pending = new NewsPending();
        $news_pending->news_id =  $id;
        $news_pending->inputer_id = Auth::user()->id;
        $news_pending->status = 0;
        $news_pending->action_type = 'Delete';

        $news_pending->save();


        $action =  $group->name;
        $title = 'Please be advised that the News alert (' . $action . ') has been deleted and is awaiting your review and approval.';
        LogActivity::addToLog(' News Alert  (' . $request['title'] . ') deleted  by ' . Auth::user()->name);






        $authorise_email =  User::where('id', $request->authorizer_id)->first();


        $authorise_email =  $authorise_email->email;

        // Notify users after the application is created
        $this->InsertnotifyUsers($action, $title, $authorise_email);

        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that News Alert (' . $action . ') has been deleted.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);




        return Redirect::back()->with('success', 'Group deleted successfully.');
    }










    public function news_status(Request $request, $id)
    {
        // return        $request;

        $update_status = News::find($id);
        $update_status_pending = NewsPending::where('status', 0)->where(
            'authorizer_id',
            null
        )->where('news_id', $id)->orderBy('created_at', 'desc')->first();



        if ($update_status_pending->action_type == 'Delete' &&  $request->status == 1) {
            $update_status_pending->status = 1;
            $update_status_pending->authorizer_id = Auth::user()->id;
            $update_status_pending->save();

            LogActivity::addToLog(' News Alert  (' . $update_status->title . ') Delete request approved by ' . Auth::user()->name);

            News::find($id)->delete();



            $action = $update_status->title;

            $this->ApprovenotifyDeletion($action);


            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised News Alert (' . $action . ') Delete request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);



            return Redirect::to('news')->with('success', 'Request approved.');
        }







        if ($update_status_pending->action_type == 'Edit' && $request->status == 1) {
            $update_status->title = $update_status_pending->title;
            $update_status->news_content = $update_status_pending->news_content;


            $update_status->status = $request->status;
            $update_status->admin_status = $request->status;



            $update_status_pending->status = $request->status;
            $update_status_pending->authorizer_id = Auth::id(); // Assuming the user is authenticated

            $update_status->save();
            $update_status_pending->save();

            $action = $update_status->title;

            $this->ApprovenotifyUsersnew($action);

            LogActivity::addToLog(' News Alert  (' . $update_status->title . ') Update request approved by ' . Auth::user()->name);



            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised News Alert (' . $action . ') Update request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);



            return Redirect::to('news')->with('success', 'Request approved.');
        }



        if ($update_status_pending->action_type == 'Insert' &&  $request->status == 1) {

            $update_status->status = $request->status;
            $update_status->admin_status = $request->status;


            $update_status_pending->status = $request->status;
            $update_status_pending->authorizer_id = Auth::id(); // Assuming the user is authenticated

            $update_status->save();
            $update_status_pending->save();

            $action = $update_status->title;

            $this->ApprovenotifyUsersnew($action);

            LogActivity::addToLog(' News Alert  (' . $update_status->title . ') Insert request approved by ' . Auth::user()->name);



            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised News Alert (' . $action . ') Insert request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);


            return Redirect::to('news')->with('success', 'Request approved.');
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

            LogActivity::addToLog(' News Alert  (' . $update_status->title . ') Request rejected by ' . Auth::user()->name);



            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised News Alert (' . $action . ') Request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);



            // $this->notifyUsersOfRejection($update_status->name, $request->note);
            return Redirect::to('news')->with('success', 'Request rejected.');

            // return redirect()->back()->with('success', 'Request rejected.');
        }




        if ($update_status_pending->action_type == 'Delete' && $request->status == 2) {

            // return $request->note;

            // $update_status->status = $request->status;
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

            LogActivity::addToLog(' News Alert  (' . $update_status->title . ') Request rejected by ' . Auth::user()->name);



            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised News Alert (' . $action . ') Request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);



            // $this->notifyUsersOfRejection($update_status->name, $request->note);
            return Redirect::to('news')->with('success', 'Request rejected.');

            // return redirect()->back()->with('success', 'Request rejected.');
        }




        if ($update_status_pending->action_type == 'Edit' &&  $request->status == 2) {

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

            LogActivity::addToLog(' News Alert  (' . $update_status->title . ') Request rejected by ' . Auth::user()->name);



            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised News Alert (' . $action . ') Request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);



            // $this->notifyUsersOfRejection($update_status->name, $request->note);
            return Redirect::to('news')->with('success', 'Request rejected.');

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
            $role = 'Content_Owner_Inputter';

            $inputter = User::where('group_id', $user->group_id)
                ->role($role)
                ->get();



            $title = 'Please be informed  the News alert (' . $action . ') has been approved.';

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
            $role = 'Content_Owner_Inputter';

            // Retrieve all users in the same group with the specified role
            $inputters = User::where('group_id', $currentUser->group_id)
                ->role($role)
                ->get();

            // Prepare the email content
            $title = 'Please be advised that the News alert (' . e($action) . ') has been rejected and requires your attention.';

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



            $title = 'Please be informed  the News alert (' . $action . ') has been deleted.';

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
