<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MarketProductTag;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MarketProductTagPending;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\LogActivity;

class MarketProductTagController extends Controller
{
    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:Market-Product-Tag-list|Market-Product-Tag-create|Market-Product-Tag-edit|Market-Product-Tag-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:Market-Product-Tag-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:Market-Product-Tag-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:Market-Product-Tag-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
               $user = Auth::user();
        $permission = 'Market-Product-Tag-approve';
        $authoriser = User::where('group_id', $user->group_id)->where('status', 1)
            ->permission($permission)
            ->get();


             // Check if the user has the 'view-all-categories' permission
        $canViewAllMarketTag = $user->hasPermissionTo('View-All-Market-Product-Tag');

        // Fetch categories based on group_id or include all if the user has the required permission
        $query = MarketProductTag::where(function ($query) use ($user, $canViewAllMarketTag) {
            // Condition to filter categories by the user's group
            $query->where('group_id', $user->group_id);

            // If the user has permission to view all categories, include them
            if ($canViewAllMarketTag) {
                $query->orWhereNotNull('id'); // This will include all categories
            }
        });

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $data = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('market_product_tags.index', compact('data', 'authoriser'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);

        $this->validate($request, [
            'name' => 'required|unique:market_product_tags,name',
        ]);

        $slug = Str::slug($request->name);

        $new_tag = new MarketProductTag();
        $new_tag->name = $request['name'];
        $new_tag->group_id = $user->group_id;
        $new_tag->description = $request['description'];
        $new_tag->slug = $slug;

        $new_tag->save();

        $tag_pending = new MarketProductTagPending();
        $tag_pending->name = $request['name'];
        $tag_pending->description = $request['description'];
        $tag_pending->market_product_tag_id = $new_tag->id;
        $tag_pending->inputer_id = Auth::user()->id;
        $tag_pending->status = 0;
        $tag_pending->action_type = 'Insert';

        $tag_pending->save();

        $action = $request['name'];
        $title = 'Please be advised that a new Market Product Tag (' . $action . ') has been created and is awaiting your review and approval.';
        $inputter_title = 'Please be advised that a new Market Product Tag (' . $action . ') has been created.';
        LogActivity::addToLog('Market Product Tag (' . $request['name'] . ')Market Product Tag creation request by ' . Auth::user()->name);

        $authorise_email = User::where('id', $request->authorizer_id)->first();
        $authorise_email = $authorise_email->email;
        $inputter_email = Auth::user()->email;

        // Notify users after the tag is created
        $this->InsertnotifyUsers($action, $title, $authorise_email);
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

        return redirect()->back()->with('success', 'Market Product Tag successfully created and pending approval.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tag = MarketProductTag::find($id);
        return view('market_product_tags.show', compact('tag'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tag = MarketProductTag::find($id);
        return view('market_product_tags.edit', compact('tag'));
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
        $user_id = Auth::user()->id;
        $user = User::find($user_id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tag = MarketProductTag::find($id);
        if (!$tag) {
            return redirect()->back()->with('error', 'Market Product Tag not found.');
        }

        $existingTag = MarketProductTag::where('name', $request->input('name'))
            ->where('id', '!=', $id)
            ->first();

        if ($existingTag) {
            return redirect()->back()->with('error', 'A Market Product Tag with the given name already exists.');
        }

        $tag->admin_status = 0;
        $tag->save();

        $tagPending = new MarketProductTagPending();
        $tagPending->market_product_tag_id = $id;
        $tagPending->name = $request->input('name');
        $tagPending->description = $request->input('description');
        $tagPending->inputer_id = Auth::user()->id;
        $tagPending->status = 0;
        $tagPending->action_type = 'Edit';
        $tagPending->save();

        $action = $request['name'];
        $title = 'Please be informed the Market Product Tag (' . $action . ') has been updated and is awaiting your review and approval.';

        LogActivity::addToLog('Market Product Tag (' . $request['name'] . ') Market Product Tag update request by ' . Auth::user()->name);

        $authorise_email = User::where('id', $request->authorizer_id)->first();
        $authorise_email = $authorise_email->email;

        // Notify users after the tag is updated
        $this->InsertnotifyUsers($action, $title, $authorise_email);

        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that Market Product Tag (' . $action . ') has been updated.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);
        
        return redirect()->back()->with('success', 'Market Product Tag updated successfully and pending approval.');
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

        $tag = MarketProductTag::find($id);
        $tag->admin_status = 3;
        $tag->save();

        $tag_pending = new MarketProductTagPending();
        $tag_pending->market_product_tag_id = $id;
        $tag_pending->name = $tag->name;
        $tag_pending->description = $tag->description;
        $tag_pending->inputer_id = Auth::user()->id;
        $tag_pending->status = 0;
        $tag_pending->action_type = 'Delete';
        $tag_pending->save();

        $action = $tag->name;
        $title = 'Please be advised that the Market Product Tag (' . $action . ') has been deleted and is awaiting your review and approval.';

        LogActivity::addToLog('Market Product Tag (' . $tag->name . ') Market Product Tag deletion request by ' . Auth::user()->name);

        $authorise_email = User::where('id', $request->authorizer_id)->first();
        $authorise_email = $authorise_email->email;

        // Notify users after the tag is deleted
        $this->InsertnotifyUsers($action, $title, $authorise_email);

        $inputter_email = Auth::user()->email;
        $inputter_title = 'Please be advised that Market Product Tag (' . $action . ') has been deleted.';
        $this->insertNotifyInputter($action, $inputter_title, $inputter_email);

        return redirect()->back()->with('success', 'Market Product Tag deleted successfully and pending approval.');
    }

    /**
     * Update status of the tag (approve/reject)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function tagstatus(Request $request, $id)
    {
        $update_status = MarketProductTag::find($id);
        $update_status_pending = MarketProductTagPending::where('status', 0)
            ->whereNull('authorizer_id')
            ->where('market_product_tag_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$update_status_pending) {
            return redirect()->back()->with('error', 'No pending request found for this tag.');
        }

        try {
            return DB::transaction(function () use ($request, $update_status, $update_status_pending) {
                if ($request->status == 1) {
                    $this->processTagApproval($update_status, $update_status_pending);
                    $msg = 'Request approved.';
                } else {
                    $this->processTagRejection($request, $update_status, $update_status_pending);
                    $msg = 'Request rejected.';
                }

                $this->logAndNotifyTagSuccess($update_status, $update_status_pending, $request->status);

                return Redirect::to('market-product-tags')->with('success', $msg);
            });
        } catch (\Exception $e) {
            Log::error('Tag status update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    private function processTagApproval($tag, $pending)
    {
        $pending->status = 1;
        $pending->authorizer_id = Auth::id();
        $pending->save();

        switch ($pending->action_type) {
            case 'Delete':
                $tag->delete();
                break;
            case 'Edit':
                $tag->name = $pending->name;
                $tag->description = $pending->description;
                $tag->slug = Str::slug($pending->name);
                $tag->status = 1;
                $tag->admin_status = 1;
                $tag->save();
                break;
            case 'Insert':
                $tag->status = 1;
                $tag->admin_status = 1;
                $tag->save();
                break;
        }
    }

    private function processTagRejection($request, $tag, $pending)
    {
        $pending->status = $request->status;
        $pending->note = $request->note;
        $pending->authorizer_id = Auth::id();
        $pending->save();

        $tag->note = $request->note;
        $tag->admin_status = $request->status;

        switch ($pending->action_type) {
            case 'Insert':
                $tag->status = $request->status;
                break;
            case 'Delete':
                $tag->admin_status = 1;
                break;
        }
        $tag->save();
    }

    private function logAndNotifyTagSuccess($tag, $pending, $decision)
    {
        $action = $tag->name;
        $inputter_email = Auth::user()->email;
        $isApprove = ($decision == 1);

        if ($isApprove) {
            switch ($pending->action_type) {
                case 'Delete':
                    $this->ApprovenotifyDeletion($action);
                    $title = "Market Product Tag ($action) Market Product Tag Delete request approved.";
                    LogActivity::addToLog("Market Product Tag ($action) Market Product Tag deletion request approved by " . Auth::user()->name);
                    break;
                case 'Edit':
                    $this->ApprovenotifyUsersnew($action);
                    $title = "Market Product Tag ($action) Market Product Tag update request approved.";
                    LogActivity::addToLog("Market Product Tag ($action) Market Product Tag update request approved by " . Auth::user()->name);
                    break;
                case 'Insert':
                    $this->ApprovenotifyUsersnew($action);
                    $title = "Market Product Tag ($action) Market Product Tag creation request approved.";
                    LogActivity::addToLog("Market Product Tag ($action) Market Product Tag creation request approved by " . Auth::user()->name);
                    break;
            }
        } else {
            $this->ApprovenotifyReject($action, $pending->note);
            $type = ($pending->action_type == 'Insert') ? 'creation' : strtolower($pending->action_type);
            $title = "Market Product Tag ($action) Market Product Tag $type request rejected.";
            LogActivity::addToLog("Market Product Tag ($action) Market Product Tag $type request rejected by " . Auth::user()->name);
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
        } catch (\Exception $e) {
            Log::error('Failed to queue emails for authorisers', ['error' => $e->getMessage()]);
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
        } catch (\Exception $e) {
            Log::error('Failed to queue emails for inputters', ['error' => $e->getMessage()]);
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

            $title = 'Please be informed the Market Product Tag (' . $action . ') has been approved.';

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

            $inputters = User::where('group_id', $currentUser->group_id)
                ->role($role)
                ->get();

            $title = 'Please be advised that the Market Product Tag (' . e($action) . ') has been rejected and requires your attention.';

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

            $title = 'Please be informed the Market Product Tag (' . $action . ') has been deleted.';

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
}
