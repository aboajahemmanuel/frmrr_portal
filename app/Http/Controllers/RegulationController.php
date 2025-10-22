<?php
namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\Category;
use App\Models\DocumentApproval;
use App\Models\Entity;
use App\Models\Regulation;
use App\Models\Subcategory;
use App\Models\User;
use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class RegulationController extends Controller
{
    /**
     * create a new instance of the class
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:regulation-list|regulation-create|regulation-edit|regulation-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:regulation-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:regulation-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:regulation-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $user = Auth::user();
        $role = 'Content_Owner_Authoriser';

        $authoriser = User::where('group_id', $user->group_id)
            ->role($role)
            ->get();

        $data = Regulation::orderBy('created_at', 'desc')->where('group_id', $user->group_id)->paginate(20);

        // $data = Regulation::orderBy('created_at', 'desc')->get();

        $statuses          = DB::table('doc_type')->pluck('name')->toArray();
        $formattedStatuses = implode('/', $statuses);

        $categories = Category::where('status', 1)->get();
        return view('regulations.index', compact('data', 'categories', 'authoriser', 'formattedStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $entities   = Entity::where('status', 1)->get();
        $categories = Category::where('status', 1)->get();
        $alpha      = DB::table('alpha')->get();

        return view('regulations.add_regulation', compact('entities', 'categories', 'alpha'));
    }

    public function getCategory(Request $request)
    {

        $category_id = DB::table('subcategories')
            ->where('category_id', $request->category_id)
            ->get();

        if (count($category_id) > 0) {
            return response()->json($category_id);
        }
    }

    public function store(Request $request)
    {
        //return $request;

        $user_id = Auth::user()->id;
        $user    = User::find($user_id);

        // return $request;
        $inputter_time = Carbon::now();
        $userId        = Auth::user()->id;

        $regulation_doc = $request->file('pdf_file');

        //return  $request;
        $validator = Validator::make($request->all(), [
            'pdf_file' => 'required|file|mimes:pdf',
            // 'csv_file' => 'required|file|mimes:csv,txt',

        ]);

        if ($validator->fails()) {
            // Validation failed, handle the errors
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $slug                             = Str::slug($request->title);
        $new_regulation                   = new regulation();
        $new_regulation->title            = $request['title'];
        $new_regulation->effective_date   = $request['effective_date'];
        $new_regulation->issue_date       = $request['issue_date'];
        $new_regulation->document_version = $request['document_version'];
        $new_regulation->year_id          = $request['year_id'];
        $new_regulation->month_id         = $request['month_id'];
        $new_regulation->entity_id        = $request['entity_id'];
        $new_regulation->category_id      = $request['category_id'];
        $new_regulation->subcategory_id   = $request['subcategory_id'];
        $new_regulation->alpha_id         = $request['alpha_id'];
        $new_regulation->document_tag     = $request['document_tag'];
        $new_regulation->ceased_date      = $request['ceased_date'];
        $new_regulation->ceased           = $request['ceased'];
        $new_regulation->doc_preview      = $request['doc_preview'];
        $new_regulation->doc_preview_count = $request['doc_preview_count'];
        
        // Save related documents if provided
        if ($request->has('related_docs') && is_array($request->related_docs)) {
            $new_regulation->related_docs = implode(',', $request->related_docs);
        }

       
        $new_regulation->slug     = $slug;
        $new_regulation->group_id = $user->group_id;

        $new_regulation->save();
        $id = $new_regulation->id;

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($regulation_doc) {
            $new_regulation_doc = regulation::find($id);
            $regulation_doc     = $regulation_doc;
            $name               = Str::slug($request->title) . '.' . $regulation_doc->getClientOriginalExtension();
            $destinationPath    = public_path('pdf_documents');
            $regulation_doc->move($destinationPath, time() . '-' . $name);
            $db_file = time() . '-' . $name;

            $new_regulation_doc->regulation_doc = $db_file;
            $new_regulation_doc->save();
        }

        $inputter_time = Carbon::now();
        $userId        = Auth::user()->id;

        $regulation_approval                = new DocumentApproval();
        $regulation_approval->regulation_id = $id;
        $regulation_approval->inputter_id   = $userId;
        $regulation_approval->status        = 0;        // Initial admin_status for approval process
        $regulation_approval->action_type   = 'Insert'; // Initial admin_status for approval process

        $regulation_approval->title            = $request['title'];
        $regulation_approval->effective_date   = $request['effective_date'];
        $regulation_approval->issue_date       = $request['issue_date'];
        $regulation_approval->document_version = $request['document_version'];
        $regulation_approval->year_id          = $request['year_id'];
        $regulation_approval->month_id         = $request['month_id'];
        $regulation_approval->entity_id        = $request['entity_id'];
        $regulation_approval->category_id      = $request['category_id'];
        $regulation_approval->subcategory_id   = $request['subcategory_id'];
        $regulation_approval->alpha_id         = $request['alpha_id'];
        $regulation_approval->document_tag     = $request['document_tag'];
        $regulation_approval->ceased_date      = $request['ceased_date'];
        $regulation_approval->ceased           = $request['ceased'];
        $regulation_approval->doc_preview      = $request['doc_preview'];
        $regulation_approval->regulation_doc   = $db_file;
        // Add related_docs to the approval record
        if ($request->has('related_docs') && is_array($request->related_docs)) {
            $regulation_approval->related_docs = implode(',', $request->related_docs);
        }
        $regulation_approval->slug             = $slug;
        $regulation_approval->group_id         = $user->group_id;

        $regulation_approval->authoriser_time = $inputter_time;
        $regulation_approval->save();

        $action = $request['title'];
        $title  = 'Please be advised that a new Document (' . $action . ') has been uploaded and is awaiting your review and approval.';

        LogActivity::addToLog(' Document  (' . $request['title'] . ') created  by ' . Auth::user()->name);

        $authorise_email = User::where('id', $request->authorizer_id)->first();

        $authorise_email = $authorise_email->email;

        // Notify users after the application is created
        $this->InsertnotifyUsers($action, $title, $authorise_email);

        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that Document  (' . $action . ') has been created.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

        return Redirect::to('regulations')->with('success', 'Document uploaded successfully.');
    }

    public function update_doc(Request $request, $id)
    {

        $request;
        $validator = Validator::make($request->all(), [
            // 'pdf_file' => 'required|file|mimes:pdf',
            // 'csv_file' => 'required|file|mimes:csv,txt',

        ]);

        if ($validator->fails()) {
            // Validation failed, handle the errors
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user_id = Auth::user()->id;
        $user    = User::find($user_id);

        $regulation_doc = $request->file('pdf_file');

        $admin_status      = 0;
        $regulation_update = Regulation::find($id);
        $slug              = Str::slug($request->title);

        $regulation_update->admin_status = $admin_status;
        // $regulation_update->group_id = $user->group_id;

        $regulation_update->save();
        $id = $regulation_update->id;

        //$new_document_tag = regulation::find($id);
        $year = Year::find($request['year_id'])->first();

        $inputter_time = Carbon::now();
        $userId        = Auth::user()->id;

        $regulation_approval                = new DocumentApproval();
        $regulation_approval->regulation_id = $id;
        $regulation_approval->inputter_id   = $userId;
        $regulation_approval->status        = 0;      // Initial admin_status for approval process
        $regulation_approval->action_type   = 'Edit'; // Initial admin_status for approval process

        $regulation_approval->title            = $request['title'];
        $regulation_approval->effective_date   = $request['effective_date'];
        $regulation_approval->issue_date       = $request['issue_date'];
        $regulation_approval->document_version = $request['document_version'];
        $regulation_approval->year_id          = $request['year_id'];
        $regulation_approval->month_id         = $request['month_id'];
        $regulation_approval->entity_id        = $request['entity_id'];
        $regulation_approval->category_id      = $request['category_id'];
        $regulation_approval->subcategory_id   = $request['subcategory_id'];
        $regulation_approval->alpha_id         = $request['alpha_id'];

        $regulation_approval->document_tag   = $request['document_tag'];
        $regulation_approval->doc_preview    = $request['doc_preview'];
        $regulation_approval->doc_preview_count = $request['doc_preview_count'];
        // Add related_docs to the approval record
        if ($request->has('related_docs') && is_array($request->related_docs)) {
            $regulation_approval->related_docs = implode(',', $request->related_docs);
        }
        $regulation_approval->ceased_date    = $request['ceased_date'];
        $regulation_approval->ceased         = $request['ceased'];
        $regulation_approval->slug           = $slug;
        $regulation_approval->group_id       = $user->group_id;
        $regulation_approval->regulation_doc = $regulation_update->regulation_doc;

        $regulation_approval->inputter_time = $inputter_time;
        $regulation_approval->save();

        if ($regulation_doc) {
            $new_regulation_doc     = regulation::find($id);
            $pending_regulation_doc = DocumentApproval::where('regulation_id', $id)->latest()->first();
            $regulation_doc         = $regulation_doc;
            $name                   = Str::slug($request->title) . '.' . $regulation_doc->getClientOriginalExtension();
            $destinationPath        = public_path('pdf_documents');
            $regulation_doc->move($destinationPath, time() . '-' . $name);
            $db_file                                = time() . '-' . $name;
            $new_regulation_doc->regulation_doc     = $db_file;
            $pending_regulation_doc->regulation_doc = $db_file;
            $new_regulation_doc->save();
            $pending_regulation_doc->save();
        }

        $action = $request['title'];
        $title  = 'Please be advised that a new Document (' . $action . ') has been updated and is awaiting your review and approval.';

        LogActivity::addToLog(' Document  (' . $request['title'] . ') updated  by ' . Auth::user()->name);

        $authorise_email = User::where('id', $request->authorizer_id)->first();

        $authorise_email = $authorise_email->email;

        // Notify users after the application is created
        $this->InsertnotifyUsers($action, $title, $authorise_email);

        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that Document  (' . $action . ') has been updated.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

        //return   $new_regulation;
        return Redirect::to('regulations')->with('success', 'Document updated successfully.');
    }

    public function show($id)
    {
        $regulation = Regulation::find($id);

        return view('categories.show', compact('regulation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_doc($id)
    {
        $regulation = Regulation::where('id', $id)->first();
        $entities   = Entity::where('status', 1)->get();
        $categories = Category::where('status', 1)->get();
        $alpha      = DB::table('alpha')->get();
        $years      = DB::table('years')->get();
        $months     = DB::table('months')->get();

        $user = Auth::user();
        $role = 'Content_Owner_Authoriser';

        $authoriser = User::where('group_id', $user->group_id)
            ->role($role)
            ->get();
        $statuses = DB::table('doc_type')->get();

        $statua            = DB::table('doc_type')->pluck('name')->toArray();
        $formattedStatuses = implode('/', $statua);

        // Get related documents for the same category
        $relatedDocuments = Regulation::where('category_id', $regulation->category_id)
            ->where('admin_status', 1) // Only approved documents
            ->where('id', '!=', $regulation->id) // Exclude current document
            ->orderBy('title')
            ->get();

        return view('regulations.edit_document', compact('regulation', 'entities', 'categories', 'alpha', 'years', 'months', 'authoriser', 'statuses', 'formattedStatuses', 'relatedDocuments'));
    }

    public function view_doc($id)
    {
        $regulation = Regulation::where('id', $id)->first();
        $entities   = Entity::all();
        $categories = Category::all();
        $alpha      = DB::table('alpha')->get();
        $years      = DB::table('years')->get();
        $months     = DB::table('months')->get();

        $user  = Auth::user();
        $roles = ['Super_Administrator_Authoriser', 'Content_Owner_Authoriser'];

        $authoriser = User::where('group_id', $user->group_id)->where('status', 1)
            ->whereHas('roles', function ($query) use ($roles) {
                $query->whereIn('name', $roles);
            })
            ->get();

        // Get related documents for display
        $relatedDocuments = $regulation->relatedDocuments;

        return view('regulations.view_document', compact('regulation', 'entities', 'categories', 'alpha', 'years', 'months', 'authoriser', 'relatedDocuments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',

        ]);

        $regulation = Regulation::find($id);
        
        // Update the regulation with the request data
        $regulation->title = $request->title;
        $regulation->effective_date = $request->effective_date;
        $regulation->issue_date = $request->issue_date;
        $regulation->document_version = $request->document_version;
        $regulation->year_id = $request->year_id;
        $regulation->month_id = $request->month_id;
        $regulation->entity_id = $request->entity_id;
        $regulation->category_id = $request->category_id;
        $regulation->subcategory_id = $request->subcategory_id;
        $regulation->alpha_id = $request->alpha_id;
        $regulation->document_tag = $request->document_tag;
        $regulation->ceased_date = $request->ceased_date;
        $regulation->ceased = $request->ceased;
        $regulation->doc_preview = $request->doc_preview;
        $regulation->doc_preview_count = $request->doc_preview_count;
        
        // Update related documents if provided
        if ($request->has('related_docs') && is_array($request->related_docs)) {
            $regulation->related_docs = implode(',', $request->related_docs);
        } else {
            $regulation->related_docs = null;
        }
        
        $regulation->slug = Str::slug($request->title);
        
        $regulation->save();

        return redirect()->back()->with('success', 'Document updated successfully.');
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
        $user    = User::find($user_id);

        $regulation = Regulation::find($id);

        $regulation->admin_status = 3;

        $regulation->save();

        $inputter_time = Carbon::now();
        $userId        = Auth::user()->id;

        $regulation_approval                = new DocumentApproval();
        $regulation_approval->regulation_id = $id;
        $regulation_approval->inputter_id   = $userId;
        $regulation_approval->status        = 0;        // Initial admin_status for approval process
        $regulation_approval->action_type   = 'Delete'; // Initial admin_status for approval process

        $regulation_approval->authoriser_time = $inputter_time;
        $regulation_approval->save();

        $action = $regulation->title;
        $title  = 'Please be advised that a  Document (' . $action . ') has been deleted and is awaiting your review and approval.';

        LogActivity::addToLog(' Document  (' . $request['title'] . ') deleted by ' . Auth::user()->name);

        $authorise_email = User::where('id', $request->authorizer_id)->first();

        $authorise_email = $authorise_email->email;

        // Notify users after the application is created
        $this->InsertnotifyUsers($action, $title, $authorise_email);

        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that Document  (' . $action . ') has been deleted.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

        return Redirect::back()->with('success', 'Document successfully deleted and pending approval.');
    }

    public function redirectToUrl($selectedValue)
    {

       
        $user = Auth::user();
        $role = 'Content_Owner_Authoriser';

        $authoriser = User::where('group_id', $user->group_id)
            ->role($role)
            ->get();

        $entities   = Entity::where('status', 1)->get();
        $categories = Category::where('status', 1)->get();
        $cate = Category::where('slug', $selectedValue)->first();
        $alpha      = DB::table('alpha')->get();
        $years      = DB::table('years')->get();
        $months     = DB::table('months')->get();

        $statuses = DB::table('doc_type')->get();

        // Get related documents based on selected category
        //  $relatedDocuments = collect();
        // if ($selectedValue) {
        //     $category = Category::where('slug', $selectedValue)->first();
        //     if ($category) {
              
        //     }
        // }

         $relatedDocuments = Regulation::where('category_id', $cate->id)
                    ->where('admin_status', 1) // Only approved documents
                    ->orderBy('title')
                    ->get();

         $statua            = DB::table('doc_type')->pluck('name')->toArray();
        $formattedStatuses = implode('/', $statua);


        return view('regulations.add_new', [
            'selectedValue' => $selectedValue,
            'entities'      => $entities,
            'categories'    => $categories,
            'alpha'         => $alpha,
            'years'         => $years,
            'months'        => $months,
            'authoriser'    => $authoriser,
            'statuses'      => $statuses,
            'formattedStatuses' => $formattedStatuses,
            'relatedDocuments' => $relatedDocuments
        ]);
    }

    public function
    regstatus(Request $request, $id) {

        // return $request;

        $user_id = Auth::user()->id;
        $user    = User::find($user_id);

        $authoriser_time     = Carbon::now();
        $userId              = Auth::user()->id;
        $update_admin_status = regulation::find($id);

        $regulation_approval = DocumentApproval::where('regulation_id', $id)->where(
            'authoriser_id',
            null
        )->orderBy('created_at', 'desc')->first();

        $update_admin_status->admin_status = $request['admin_status'];
        $update_admin_status->note         = $request['note'];

        if ($regulation_approval->action_type == 'Insert' && $request->status == 1) {

            $regulation_approval->status          = $request->status;
            $update_admin_status->status          = $request->status;
            $update_admin_status->admin_status    = $request->status;
            $regulation_approval->authoriser_id   = Auth::id(); // Assuming the user is authenticated
            $regulation_approval->authoriser_time = $authoriser_time;

            $update_admin_status->save();
            $regulation_approval->save();

            $action = $update_admin_status->title;

            $this->ApprovenotifyUsersnew($action);
            LogActivity::addToLog('Document  (' . $update_admin_status->title . ') Insert request approved by ' . Auth::user()->name);

            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that Document (' . $action . ') Insert request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

            return Redirect::to('regulations')->with('success', 'Request approved successfully.');
        }

        if ($regulation_approval->action_type == 'Edit' && $request->status == 1) {

            $update_admin_status->admin_status = $request->status;
            // $update_admin_status->admin_status = $request->status;
            $update_admin_status->title            = $regulation_approval->title;
            $update_admin_status->effective_date   = $regulation_approval->effective_date;
            $update_admin_status->issue_date       = $regulation_approval->issue_date;
            $update_admin_status->document_version = $regulation_approval->document_version;
            $update_admin_status->year_id          = $regulation_approval->year_id;
            $update_admin_status->month_id         = $regulation_approval->month_id;
            $update_admin_status->entity_id        = $regulation_approval->entity_id;
            $update_admin_status->category_id      = $regulation_approval->category_id;
            $update_admin_status->subcategory_id   = $regulation_approval->subcategory_id;
            $update_admin_status->alpha_id         = $regulation_approval->alpha_id;
            $update_admin_status->document_tag     = $regulation_approval->document_tag;
            $update_admin_status->ceased_date      = $regulation_approval->ceased_date;
            $update_admin_status->ceased           = $regulation_approval->ceased;
            $update_admin_status->slug             = $regulation_approval->slug;
            $update_admin_status->group_id         = $regulation_approval->group_id;
            $update_admin_status->regulation_doc   = $regulation_approval->regulation_doc;
            $update_admin_status->doc_preview      = $regulation_approval->doc_preview;
            $update_admin_status->doc_preview_count = $regulation_approval->doc_preview_count;

            $regulation_approval->status          = $request->status;
            $regulation_approval->authoriser_id   = Auth::id(); // Assuming the user is authenticated
            $regulation_approval->authoriser_time = $authoriser_time;

            $update_admin_status->save();
            $regulation_approval->save();

            $action = $update_admin_status->title;

            $this->ApprovenotifyUsersnew($action);
            LogActivity::addToLog(' Document   (' . $update_admin_status->title . ') Update request approved by ' . Auth::user()->name);

            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that Document (' . $action . ') Update request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

            return Redirect::to('regulations')->with('success', 'Request approved successfully.');
        }

        if ($regulation_approval->action_type == 'Delete' && $request->status == 1) {

            $update_admin_status->admin_status = $request->status;

            $regulation_approval->status          = $request->status;
            $regulation_approval->authoriser_id   = Auth::id(); // Assuming the user is authenticated
            $regulation_approval->authoriser_time = $authoriser_time;

            $regulation_approval->save();

            $action = $update_admin_status->title;

            $this->ApprovenotifyUsersnew($action);

            LogActivity::addToLog(' Document  (' . $update_admin_status->title . ') Delete request approved by ' . Auth::user()->name);

            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that Document (' . $action . ') Delete request approved.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

            Regulation::find($id)->delete();

            return Redirect::to('regulations')->with('success', 'Request approved successfully.');
        }

        if ($regulation_approval->action_type == 'Edit' && $request->status == 2) {

            // return $request->note;
            $update_admin_status->admin_status    = 1;
            $regulation_approval->admin_status    = $request->status;
            $regulation_approval->note            = $request->note;
            $update_admin_status->note            = $request->note;
            $regulation_approval->authoriser_time = $authoriser_time;
            $regulation_approval->authoriser_id   = Auth::user()->id;

            $update_admin_status->save();
            $regulation_approval->save();

            $action = $update_admin_status->title;
            $note   = $request->note;

            $this->ApprovenotifyReject($action, $note);

            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that Document (' . $action . ') Request rejected.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

            LogActivity::addToLog(' Document (' . $update_admin_status->title . ') Request rejected by ' . Auth::user()->name);

            // $this->notifyUsersOfRejection($update_admin_status->name, $request->note);
            return Redirect::to('regulations')->with('success', 'Request rejected.');
        }

        if ($regulation_approval->action_type == 'Insert' && $request->status == 2) {

            //  return $request->note;

            $update_admin_status->admin_status    = $request->status;
            $regulation_approval->admin_status    = $request->status;
            $regulation_approval->note            = $request->note;
            $update_admin_status->note            = $request->note;
            $regulation_approval->authoriser_time = $authoriser_time;
            $regulation_approval->authoriser_id   = Auth::user()->id;

            $update_admin_status->save();
            $regulation_approval->save();

            $action = $update_admin_status->title;
            $note   = $request->note;

            $this->ApprovenotifyReject($action, $note);

            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that Document (' . $action . ') Request rejected.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

            LogActivity::addToLog(' Document (' . $update_admin_status->title . ') Request rejected by ' . Auth::user()->name);

            // $this->notifyUsersOfRejection($update_admin_status->name, $request->note);
            return Redirect::to('regulations')->with('success', 'Request rejected.');
        }

        if ($regulation_approval->action_type == 'Delete' && $request->status == 2) {

            // return $request->note;

            $update_admin_status->admin_status    = 1;
            $regulation_approval->status          = $request->status;
            $regulation_approval->note            = $request->note;
            $update_admin_status->note            = $request->note;
            $regulation_approval->authoriser_time = $authoriser_time;
            $regulation_approval->authoriser_id   = Auth::user()->id;

            $update_admin_status->save();
            $regulation_approval->save();

            $action = $update_admin_status->title;
            $note   = $request->note;

            $this->ApprovenotifyReject($action, $note);

            $inputter_email = Auth::user()->email;
            $inputter_title = 'Please be advised that Document (' . $action . ') Request rejected.';
            $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

            LogActivity::addToLog(' Document (' . $update_admin_status->title . ') Request rejected by ' . Auth::user()->name);

            // $this->notifyUsersOfRejection($update_admin_status->name, $request->note);
            return Redirect::to('regulations')->with('success', 'Request rejected.');
        }
    }

    public function admin_statusceased(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'ceased' => 'required',

        ]);

        $ceased_update = Regulation::find($id);

        // Proceed with updating the Category
        $ceased_update->ceased = $request['ceased'];
        $ceased_update->save();

        return redirect()->back()->with('success', 'Document admin_status successfully updated.');
    }

    public function getSubcategoriesByCategory($categoryId)
    {
        $subcategories = Subcategory::where('category_id', $categoryId)
            ->where('status', 1)
            ->get();
            
        return response()->json($subcategories);
    }
    
    public function getRelatedDocumentsByCategory($categoryId)
    {
        $documents = Regulation::where('category_id', $categoryId)
            ->where('admin_status', 1) // Only approved documents
            ->orderBy('title')
            ->get();
            
        return response()->json($documents);
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
            $role = 'Content_Owner_Inputter';

            $inputter = User::where('group_id', $user->group_id)
                ->role($role)
                ->get();

            $title = 'Please be informed  the Document  (' . $action . ') has been approved.';

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
            $role        = 'Content_Owner_Inputter';

            // Retrieve all users in the same group with the specified role
            $inputters = User::where('group_id', $currentUser->group_id)
                ->role($role)
                ->get();

            // Prepare the email content
            $title = 'Please be advised that the Document (' . e($action) . ') has been rejected and requires your attention.';

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
