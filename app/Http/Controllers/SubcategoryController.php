<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SubCategoryPending;
use App\Models\SubCategorycategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\LogActivity;

class SubcategoryController extends Controller
{
    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:category-list|category-create|category-edit|category-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:category-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:category-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $user = Auth::user();

        $superAdminRole = 'Super_Administrator_Authoriser';




        // Check if the user has the 'Super_Administrator_Authoriser' role
        $hasSuperAdminRole = $user->hasRole($superAdminRole);

        // Fetch categories based on group_id or include all if the user has the Super Admin role
        $categories = Category::where('status', 1)->where(function ($query) use ($user, $hasSuperAdminRole) {
            // Condition to filter categories by the user's group
            $query->where('group_id', $user->group_id);

            // If the user has the Super Admin role, include all categories
            if ($hasSuperAdminRole) {
                $query->orWhereNotNull('id'); // This will include all categories
            }
        })
            ->orderBy('created_at', 'desc')
            ->get();

        // $categories = Category::where('status', 1)->get();





        $roles = ['Super_Administrator_Authoriser', 'Super_Administrator_Inputter', 'Content_Owner_Authoriser'];

        $authoriser = User::where('group_id', $user->group_id)->where('status', 1)
            ->whereHas('roles', function ($query) use ($roles) {
                $query->whereIn('name', $roles);
            })
            ->get();



        $superAdminRole = ['Super_Administrator_Authoriser', 'Super_Administrator_Inputter'];



        // Check if the user has the 'Super_Administrator_Authoriser' role
        $hasSuperAdminRole = $user->hasRole($superAdminRole);

        // Fetch categories based on group_id or include all if the user has the Super Admin role
        $data = Subcategory::where(function ($query) use ($user, $hasSuperAdminRole) {
            // Condition to filter categories by the user's group
            $query->where('group_id', $user->group_id);

            // If the user has the Super Admin role, include all categories
            if ($hasSuperAdminRole) {
                $query->orWhereNotNull('id'); // This will include all categories
            }
        })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('categories.subcategories', compact('data', 'categories', 'authoriser'));
    }


    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);


        $this->validate($request, [
            'name' => 'required',

        ]);


        $exists = Subcategory::where('name', $request->name)
            ->where('category_id', $request->category_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['name' => 'The subcategory name already exists in this category.']);
        }


        $slug = Str::slug($request->name);

        $new_category = new Subcategory();
        $new_category->name = $request['name'];
        $new_category->category_id = $request['category_id'];
        $new_category->group_id = $user->group_id;
        $new_category->slug = $slug;
        $new_category->status = 0;

        $new_category->save();



        $category_category = new SubCategoryPending();
        $category_category->name = $request['name'];
        $category_category->slug = $slug;
        $category_category->category_id = $request['category_id'];
        $category_category->subcategory_id =  $new_category->id;
        $category_category->inputer_id = Auth::user()->id;
        $category_category->status = 0;
        $category_category->action_type = 'Insert';

        $category_category->save();


        // $authoriserUsers = User::role('Authoriser')->get();

        $action =  $request['name'];
        $title = 'Please be advised that a new Sub Category (' . $action . ') has been created and is awaiting your review and approval.';
        LogActivity::addToLog(' Subcategory (' . $request['name'] . ') created  by ' . Auth::user()->name);






        $authorise_email =  User::where('id', $request->authorizer_id)->first();


        $authorise_email =  $authorise_email->email;

        // Notify users after the application is created
        $this->InsertnotifyUsers($action, $title, $authorise_email);





        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that a new Subcategory (' . $action . ') has been created.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);


        return redirect()->back()->with('success', 'Subcategory  successfully created and pending approval..');
    }




    public function update(Request $request, $id)
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);


        $this->validate($request, [
            'name' => 'required|string|max:255', // Ensuring 'name' is a string and not too long
        ]);

        $category = Subcategory::find($id);
        if (!$category) {
            return redirect()->back()->with('error', 'Category not found.');
        }

        // Check if another category with the same name already exists, excluding the current one
        $existingCategory = Subcategory::where('name', $request->input('name'))
            ->where('id', '!=', $id) // Exclude the current category from the check
            ->first();

        if ($existingCategory) {
            // Category with the same name exists, return with error
            return redirect()->back()->with('error', 'A category with the given name already exists.');
        }

        $category->admin_status = $request->input('status');
        $category->group_id = $user->group_id;



        $category->save();




        $slug = Str::slug($request->input('name'));

        $category_pending = new SubCategoryPending();
        $category_pending->subcategory_id =  $id;

        $category_pending->name =   $request->input('name');
        $category_pending->slug =   $slug;
        $category_pending->category_id = $request['category_id'];
        $category_pending->inputer_id = Auth::user()->id;
        $category_pending->status = 0;
        $category_pending->action_type = 'Edit';

        $category_pending->save();



        //$authoriserUsers = User::role('Authoriser')->get();
        $action =  $request['name'];
        $title = 'Please be informed  the Category (' . $action . ') has been updated and is awaiting your review and approval.';

        LogActivity::addToLog(' Subcategory (' . $request['name'] . ') updated  by ' . Auth::user()->name);



        $authorise_email =  User::where('id', $request->authorizer_id)->first();


        $authorise_email =  $authorise_email->email;

        // Notify users after the application is created
        $this->InsertnotifyUsers($action, $title, $authorise_email);

        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that  Subcategory (' . $action . ') has been updated.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

        return redirect()->back()->with('success', 'Subcategory updated successfully and pending approval.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

        $user_id = Auth::user()->id;
        $user = User::find($user_id);



        $category = Subcategory::find($id);

        $category->admin_status = 3;

        $category->save();





        $category_pending = new SubCategoryPending();
        $category_pending->subcategory_id =  $id;
        $category_pending->category_id =  $category->category_id;
        $category_pending->inputer_id = Auth::user()->id;
        $category_pending->status = 0;
        $category_pending->action_type = 'Delete';

        $category_pending->save();


        $action =  $category->name;
        $title = 'Please be advised that the Category (' . $action . ') has been deleted and is awaiting your review and approval.';

        LogActivity::addToLog(' Subcategory (' . $request['name'] . ') deleted  by ' . Auth::user()->name);






        $authorise_email =  User::where('id', $request->authorizer_id)->first();


        $authorise_email =  $authorise_email->email;

        // Notify users after the application is created
        $this->InsertnotifyUsers($action, $title, $authorise_email);


        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that Subcategory (' . $action . ') has been deleted.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

        return redirect()->back()->with('success', 'Subcategory deleted  successfully  and pending approval..');
    }




    public function subcategorystatus(Request $request, $id)
    {


        $update_status = Subcategory::find($id);
        $update_status_pending = SubCategoryPending::where('status', 0)->where(
            'authorizer_id',
            null
        )->where('subcategory_id', $id)->orderBy('created_at', 'desc')->first();



        if ($update_status_pending->action_type == 'Delete' &&  $request->status == 1) {
            $update_status_pending->status = 1;
            $update_status_pending->authorizer_id = Auth::user()->id;
            $update_status_pending->save();


            Subcategory::find($id)->delete();



            $action = $update_status->name;

            $this->ApprovenotifyDeletion($action);


            LogActivity::addToLog(' Subcategory (' . $update_status->name . ') Delete request approved by ' . Auth::user()->name);

            return Redirect::to('subcategories')->with('success', 'Request approved.');


            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that  Subcategory (' . $action . ') Delete request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);
            //return redirect()->back()->with('success', 'Request approved.');
        }







        if ($update_status_pending->action_type == 'Edit' && $request->status == 1) {
            $update_status->name = $update_status_pending->name;
            $update_status->slug = $update_status_pending->slug;
            $update_status->category_id = $update_status_pending->category_id;
            $update_status->admin_status = $request->status;
            $update_status->status = $request->status;
            $update_status_pending->status = $request->status;
            $update_status_pending->authorizer_id = Auth::id(); // Assuming the user is authenticated

            $update_status->save();
            $update_status_pending->save();

            $action = $update_status->name;

            $this->ApprovenotifyUsersnew($action);


            LogActivity::addToLog(' Subcategory (' . $update_status->name . ') Update request approved by ' . Auth::user()->name);


            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that  Subcategory (' . $action . ') Update request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

            return Redirect::to('subcategories')->with('success', 'Request approved.');


            //return redirect()->back()->with('success', 'Request approved successfully.');
        }



        if (
            $update_status_pending->action_type == 'Insert' &&  $request->status == 1
        ) {
            $update_status->name = $update_status_pending->name;
            $update_status->slug = $update_status_pending->slug;
            $update_status->category_id = $update_status_pending->category_id;


            $update_status->status = $request->status;
            $update_status->admin_status = $request->status;

            $update_status_pending->status = $request->status;


            $update_status_pending->authorizer_id = Auth::id();
            $update_status->save();
            $update_status_pending->save();

            $action = $update_status->name;

            $this->ApprovenotifyUsersnew($action);


            LogActivity::addToLog(' Subcategory (' . $update_status->name . ') Insert request approved by ' . Auth::user()->name);


            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that  Subcategory (' . $action . ') Insert request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);




            return Redirect::to('subcategories')->with('success', 'Request approved.');


            //return redirect()->back()->with('success', 'Request approved successfully.');
        }





        if ($update_status_pending->action_type == 'Insert' &&  $request->status == 2) {

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


            LogActivity::addToLog(' Subcategory (' . $update_status->name . ') Request rejected by ' . Auth::user()->name);

            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that  Subcategory (' . $action . ')  Request rejected .';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);


            return Redirect::to('subcategories')->with('success', 'Request rejected.');
        }


        if ($update_status_pending->action_type == 'Delete' &&  $request->status == 2) {

            // return $request->note;

            $update_status->admin_status = 1;
            $update_status_pending->status = 1;
            $update_status_pending->note = $request->note;
            $update_status->note = $request->note;
            $update_status_pending->authorizer_id = Auth::user()->id;


            $update_status->save();
            $update_status_pending->save();

            $action = $update_status->name;
            $note = $request->note;


            $this->ApprovenotifyReject($action, $note);


            LogActivity::addToLog(' Subcategory (' . $update_status->name . ') Request rejected by ' . Auth::user()->name);

            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that  Subcategory (' . $action . ')  Request rejected .';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);


            return Redirect::to('subcategories')->with('success', 'Request rejected.');
        }


        if ($update_status_pending->action_type == 'Edit' &&  $request->status == 2) {

            // return $request->note;

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


            LogActivity::addToLog(' Subcategory (' . $update_status->name . ') Request rejected by ' . Auth::user()->name);

            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that  Subcategory (' . $action . ')  Request rejected .';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);


            return Redirect::to('subcategories')->with('success', 'Request rejected.');
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



            $title = 'Please be informed  the Sub Category (' . $action . ') has been approved.';

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
            $title = 'Please be advised that the Sub Category (' . e($action) . ') has been rejected and requires your attention.';

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



            $title = 'Please be informed  the Sub Category (' . $action . ') has been deleted.';

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
