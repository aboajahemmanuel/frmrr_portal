@extends('layouts.master')

@section('content')




    <!-- main header @e -->
    <!-- content @s -->
        <div class="nk-content ">
            <div class="container-fluid">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-block-head-sm">
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title page-title">Documents</h3>
                                    <div class="nk-block-des text-soft">

                                    </div>
                                </div><!-- .nk-block-head-content -->
                                <div class="nk-block-head-content">
                                    <div class="toggle-wrap nk-block-tools-toggle">
                                        <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                            data-target="more-options"><em class="icon ni ni-more-v"></em></a>
                                        <div class="toggle-expand-content" data-content="more-options">
                                            <ul class="nk-block-tools g-3">



                                                @can('regulation-create')
                                                    <li class="nk-block-tools-opt">
                                                        <a href="#" class="btn btn-icon btn-primary d-md-none"><em
                                                                class="icon ni ni-plus"></em></a>
                                                        <a href="#" data-toggle="modal" data-target="#addUser"
                                                            class="btn btn-primary d-none d-md-inline-flex"><em
                                                                class="icon ni ni-plus"></em><span>Add</span></a>
                                                    </li>
                                                @endcan


                                            </ul>
                                        </div>
                                    </div>
                                </div><!-- .nk-block-head-content -->
                            </div><!-- .nk-block-between -->
                        </div><!-- .nk-block-head -->
                        <div class="nk-block nk-block-lg">

                            <div class="example-alert">
                                @if (\Session::has('success'))
                                    <div class="alert alert-success alert-icon alert-dismissible">
                                        <em class="icon ni ni-check-circle"></em> <strong> {{ \Session::get('success') }}<button
                                                class="close" data-dismiss="alert"></button>
                                    </div>
                                @endif


                                @if (count($errors) > 0)
                                    <div>
                                        <div class="alert alert-danger alert-icon alert-dismissible">
                                            <strong>Opps!</strong> Something went wrong, please check below errors.<br><br>
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                            <button class="close" data-dismiss="alert"></button>
                                        </div>
                                @endif



                            </div>
                            <div class="card card-preview">

                                <div class="card-inner">

                                    <table class="datatable-init nk-tb-list nk-tb-ulist table-striped"
                                        data-auto-responsive="false">
                                        <thead>
                                            <tr class="nk-tb-item nk-tb-head">
                                                <th class="nk-tb-col">S/N</th>
                                                <th class="nk-tb-col">Name</th>

                                                <th class="nk-tb-col">Category</th>
                                                <th class="nk-tb-col">Status</th>
                                                <th class="nk-tb-col">{{$formattedStatuses}}</th>
                                                <th class="nk-tb-col">Date Created</th>
                                                <th class="nk-tb-col">Related Docs</th>
                                                <th class="nk-tb-col"></th>
                                                <th class="nk-tb-col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $regulation)
                                                <tr class="nk-tb-item">
                                                    <td class="nk-tb-col"> {{ $loop->iteration }}</td>
                                                    <td class="nk-tb-col"> {{ $regulation->title }}</td>

                                                    <td class="nk-tb-col">{{ optional($regulation->category)->name }}</td>
                                                    <td class="nk-tb-col">
                                                        @if ($regulation->admin_status == 0)
                                                            <span class="badge fmdq_Blue">Awaiting Approval<span>
                                                        @endif
                                                        @if ($regulation->admin_status == 1)
                                                            <span class="badge badge-primary">Approved</span>
                                                        @endif
                                                        @if ($regulation->admin_status == 2)
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @endif


                                                        @if ($regulation->admin_status == 3)
                                                            <span class="badge badge-warning">Awaiting approval for
                                                                delete</span>
                                                        @endif

                                                    </td> 


                                                    <td class="nk-tb-col">
                                                        {{-- @if ($regulation->ceased == 'Ceased') --}}
                                                            <span class="badge fmdq_Blue">{{$regulation->ceased}}</span>
                                                        {{-- @endif --}}
                                                        {{-- @if ($regulation->ceased == 'Repealed')
                                                            <span class="badge fmdq_Blue">Repealed</span>
                                                        @endif

                                                        @if ($regulation->ceased == 'Amended')
                                                            <span class="badge fmdq_Blue">Amended</span>
                                                        @endif --}}


                                                    </td>

                                                    <td class="nk-tb-col">
                                                        @php
                                                            $postdate = date_format($regulation->created_at, 'F d,Y');

                                                        @endphp

                                                        <?php
                                                        
                                                        $timestamp = strtotime($postdate);
                                                        $newDateFormat = date('M. j, Y', $timestamp);
                                                        echo $newDateFormat;
                                                        
                                                        ?>



                                                    </td>

                                                    {{-- Related Documents Column --}}
                                                    <td class="nk-tb-col">
                                                        @if($regulation->related_docs)
                                                            @php
                                                                $relatedCount = count(explode(',', $regulation->related_docs));
                                                            @endphp
                                                            <span class="badge badge-primary">{{ $relatedCount }} related</span>
                                                        @else
                                                            <span class="badge badge-secondary">None</span>
                                                        @endif
                                                    </td>
                                                    {{-- End Related Documents Column --}}

                                                    <td class="nk-tb-col">
                                                        @if ($regulation->admin_status == 2)
                                                            {{ $regulation->note }}
                                                        @endif
                                                    </td>

                                                    <td class="nk-tb-col nk-tb-col-tools">
                                                        


                                                        <ul class="nk-tb-actions gx-1">

                                                            <li>
                                                                <div class="drodown">
                                                                    <a href="#"
                                                                        class="dropdown-toggle btn btn-icon btn-trigger"
                                                                        data-toggle="dropdown"><em
                                                                            class="icon ni ni-more-h"></em></a>
                                                                    <div class="dropdown-menu dropdown-menu-right">

                                                                        <ul class="link-list-opt no-bdr">
                                                                            <li><a
                                                                                    href="{{ route('view_doc', $regulation->id) }}"><em
                                                                                        class="icon ni ni-edit"></em><span>View</span></a>
                                                                            </li>
                                                                            @if ($regulation->admin_status != 3)
                                                                                @if ($regulation->admin_status != 0)
                                                                                    @can('regulation-edit')
                                                                                        <li>
                                                                                            <a
                                                                                                href="{{ route('edit_doc', $regulation->id) }}">
                                                                                                <em
                                                                                                    class="icon ni ni-edit"></em><span>Edit</span>
                                                                                            </a>
                                                                                        </li>
                                                                                    @endcan




                                                                                    @can('regulation-delete')
                                                                                        <li><a href="#" data-toggle="modal"
                                                                                                data-target="#deleteReg-{{ $regulation->id }}"><em
                                                                                                    class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                                        </li>
                                                                                    @endcan
                                                                                @endif
                                                                            @endif



                                                                            @if ($regulation->admin_status == 0)
                                                                                @can('regulation-approve')
                                                                                    <li><a href="#" id="submit"
                                                                                            onclick="document.getElementById('approve-{{ $regulation->id }}').submit();"><em
                                                                                                class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                                    </li>
                                                                                @endcan


                                                                                @can('regulation-reject')
                                                                                    <li><a href="#" data-toggle="modal"
                                                                                            data-target="#rejectdocument-{{ $regulation->id }}"><em
                                                                                                class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                                    </li>
                                                                                @endcan

                                                                                <li><a href="#" data-toggle="modal"
                                                                                        data-target="#viewUser-{{ $regulation->id }}"><em
                                                                                            class="icon ni ni-cross-circle-fill"></em><span>Views
                                                                                            Changes</span></a>
                                                                                </li>
                                                                            @endif



                                                                            @if ($regulation->admin_status == 3)
                                                                                @can('regulation-approve')
                                                                                    <li><a href="#" id="submit"
                                                                                            onclick="document.getElementById('approve-{{ $regulation->id }}').submit();"><em
                                                                                                class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                                    </li>
                                                                                @endcan

                                                                                @can('regulation-reject')
                                                                                    <li><a href="#" data-toggle="modal"
                                                                                            data-target="#rejectdocument-{{ $regulation->id }}"><em
                                                                                                class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                                    </li>
                                                                                @endcan
                                                                                <form
                                                                                    id="delete_request-{{ $regulation->id }}"
                                                                                    action="{{ route('RegStatus', $regulation->id) }}"
                                                                                    method="POST" class="d-none"
                                                                                    style="display: none">
                                                                                    @csrf
                                                                                    <input name="status"
                                                                                        value="{{ $regulation->admin_status }}">
                                                                                </form>
                                                                            @endif


                                                                        </ul>

                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>

                                                        <form id="approve-{{ $regulation->id }}"
                                                            action="{{ route('RegStatus', $regulation->id) }}" method="POST"
                                                            class="d-none">
                                                            @csrf
                                                            <input name="status" value="1">
                                                        </form>





                                                    </td>
                                                </tr>
                                                <div class="modal fade" role="dialog"
                                                    id="rejectdocument-{{ $regulation->id }}">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <a href="#" class="close" data-dismiss="modal"><em
                                                                    class="icon ni ni-cross-sm"></em></a>
                                                            <div class="modal-body modal-body-md">
                                                                <h5 class="title">{{ $regulation->title }}</h5>
                                                                <form method="POST"
                                                                    action="{{ route('RegStatus', $regulation->id) }}"
                                                                    id="rejectForm-{{ $regulation->id }}">
                                                                    @csrf
                                                                    <div class="tab-content">
                                                                        <div class="tab-pane active" id="infomation">
                                                                            <div class="row gy-4">
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">

                                                                                        <label>Rejection Note</label>
                                                                                        <input hidden name="status"
                                                                                            value="2">
                                                                                        <textarea required class="form-control" name="note"></textarea>


                                                                                    </div>
                                                                                </div>



                                                                                <div class="col-12">
                                                                                    <ul
                                                                                        class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                                                                        <li>
                                                                                            <button
                                                                                                class="btn btn-lg btn-primary btn-block"
                                                                                                id="rejectSubmitBtn-{{ $regulation->id }}"
                                                                                                type="submit">
                                                                                                <i class="fas fa-spinner fa-spin"
                                                                                                    style="display:none;"></i>
                                                                                                <span
                                                                                                    class="btn-text">Submit</span>
                                                                                            </button>


                                                                                            <script>
                                                                                                function loading(buttonId) {
                                                                                                    $("#" + buttonId + " .fa-spinner").show();
                                                                                                    $("#" + buttonId + " .btn-text").html("Processing...");
                                                                                                }

                                                                                                document.addEventListener('DOMContentLoaded', function() {
                                                                                                    document.getElementById('rejectForm-{{ $regulation->id }}').addEventListener('submit', function(
                                                                                                        event) {
                                                                                                        if (this.checkValidity() === false) {
                                                                                                            event.preventDefault();
                                                                                                            event.stopPropagation();
                                                                                                        } else {
                                                                                                            loading('rejectSubmitBtn-{{ $regulation->id }}');
                                                                                                            document.getElementById('rejectSubmitBtn-{{ $regulation->id }}').disabled = true;
                                                                                                        }
                                                                                                        this.classList.add('was-validated');
                                                                                                    }, false);
                                                                                                });
                                                                                            </script>
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div><!-- .tab-pane -->

                                                                    </div><!-- .tab-content -->
                                                                </form>
                                                            </div><!-- .modal-body -->
                                                        </div><!-- .modal-content -->
                                                    </div><!-- .modal-dialog -->
                                                </div><!-- .modal -->



                                                <div class="modal fade" id="deleteReg-{{ $regulation->id }}">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <a href="#" class="close" data-dismiss="modal"><em
                                                                    class="icon ni ni-cross"></em></a>
                                                            <form method="POST"
                                                                action="{{ route('deleteRegulations', $regulation->id) }}">
                                                                @csrf
                                                                <div class="modal-body modal-body-sm text-center">
                                                                    <div class="nk-modal py-4">
                                                                        <em
                                                                            class="nk-modal-icon icon icon-circle icon-circle-xxl ni ni-cross bg-danger"></em>
                                                                        <h4 class="nk-modal-title">Are You Sure ?</h4>
                                                                        <div class="nk-modal-text mt-n2">
                                                                            <p class="text-soft">This data will be delete
                                                                                permanently.</p>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label class="form-label"
                                                                                    for="add-account">Select Authoriser
                                                                                    <span style="color: red;">*</span></label>
                                                                                <div class="form-control-wrap">

                                                                                    <select required name="authorizer_id"
                                                                                        class="form-select form-control"
                                                                                        data-placeholder="Select one">
                                                                                        <option value="">---</option>
                                                                                        @foreach ($authoriser as $auth)
                                                                                            <option
                                                                                                value="{{ $auth->id }}">
                                                                                                {{ $auth->name }}</option>
                                                                                        @endforeach


                                                                                    </select>


                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <ul class="d-flex justify-content-center gx-4 mt-4">
                                                                            <li>

                                                                                <button
                                                                                    id="deleteSubmitBtn-{{ $regulation->id }}"
                                                                                    type="submit" id="deleteOrg"
                                                                                    class="btn btn-success">Yes, Delete
                                                                                    it</button>
                                                            </form>


                                                            </li>
                                                            <li>
                                                                <button data-dismiss="modal" data-toggle="modal"
                                                                    data-target="#editEventPopup"
                                                                    class="btn btn-danger btn-dim">Cancel</button>
                                                            </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                </div>
                                <script>
                                    document.getElementById('deleteSubmitBtn-{{ $regulation->id }}').addEventListener('click', function() {
                                        loading('deleteSubmitBtn-{{ $regulation->id }}');
                                        setTimeout(() => {
                                            document.getElementById('deleteSubmitBtn-{{ $regulation->id }}').disabled = true;
                                        }, 50);
                                    });
                                </script>
                            </div>


                            <div class="modal fade" role="dialog" id="ceased-{{ $regulation->id }}">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Ceased/Repealed</h5>
                                            <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                                                <em class="icon ni ni-cross"></em>
                                            </a>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="{{ route('statusCeased', $regulation->id) }}"
                                                class="form-validate is-alter" enctype="multipart/form-data">
                                                @csrf

                                                <div class="row gx-4 gy-3">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label class="form-label" for="event-title">Select
                                                                an option</label>
                                                            <div class="form-control-wrap">
                                                                <select class="form-control" name="ceased">
                                                                    {{-- <option value="Ceased">Ceased</option>
                                                                                        <option value="Repealed">Repealed</option>
                                                                                       Ceased/Repealed/Amended  --}}
                                                                    <option value="Ceased"
                                                                        {{ $regulation->ceased == 'Ceased' ? 'selected' : '' }}>
                                                                        Ceased</option>
                                                                    <option value="Repealed"
                                                                        {{ $regulation->ceased == 'Repealed' ? 'selected' : '' }}>
                                                                        Repealed</option>
                                                                </select>


                                                            </div>
                                                        </div>
                                                    </div>



                                                    <div class="col-12">
                                                        <ul class="d-flex justify-content-between gx-4 mt-1">
                                                            <li>
                                                                <button type="submit" class="btn fmdq_Gold">
                                                                    Submit</button>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div><!-- .modal-dialog -->

                            </div><!-- .modal -->





                            <div class="modal fade" role="dialog" id="index-{{ $regulation->id }}">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Index</h5>
                                            <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                                                <em class="icon ni ni-cross"></em>
                                            </a>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="{{ route('update_index', $regulation->id) }}"
                                                class="form-validate is-alter" enctype="multipart/form-data">
                                                @csrf

                                                <div class="row gx-4 gy-3">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label class="form-label" for="customFileLabel">CSV
                                                                Index
                                                                Upload</label>
                                                            <div class="form-control-wrap">
                                                                <div class="custom-file">
                                                                    <input type="file" name="csv_file"
                                                                        class="custom-file-input">
                                                                    <label class="custom-file-label" for="customFile">Choose
                                                                        file</label>
                                                                </div>
                                                            </div>
                                                            <a href="public/index/index.csv"
                                                                download="public/index/index.csv">
                                                                <h5>
                                                                    <br>
                                                                    <center>Click to download document
                                                                        format
                                                                    </center>
                                                            </a></h5>
                                                        </div>
                                                    </div>



                                                    <div class="col-12">
                                                        <ul class="d-flex justify-content-between gx-4 mt-1">
                                                            <li>
                                                                <button type="submit" class="btn fmdq_Gold">
                                                                    Submit</button>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div><!-- .modal-dialog -->

                            </div><!-- .modal -->


                            @php
                                $regulation_pending = \App\Models\DocumentApproval::where(
                                    'regulation_id',
                                    $regulation->id,
                                )
                                    // ->where('action_type', '=', 'Edit')
                                    ->latest()
                                    ->first();
                            @endphp

                            <div class="modal fade" id="viewUser-{{ $regulation->id }}" role="dialog">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <a href="#" class="close" data-dismiss="modal">
                                            <em class="icon ni ni-cross-sm"></em>
                                        </a>
                                        <div class="modal-body modal-body-md">
                                            <h5 class="title">View Changes</h5>

                                            <form id="editForm-" method="POST" action=""
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="tab-content">
                                                    <div class="tab-pane active" id="infomation">
                                                        <div class="row gy-4">
                                                            <!-- Name Field -->
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="lead-name" class="form-label">Title</label>
                                                                    <input disabled type="text" class="form-control"
                                                                        id="lead-name" name="name"
                                                                        value="{{ optional($regulation_pending)->title ?? 'N/A' }}"
                                                                        required />
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="lead-name" class="form-label">Category</label>
                                                                    <input disabled type="text" class="form-control"
                                                                        id="lead-name" name="name"
                                                                        value="{{ optional($regulation_pending->category)->name ?? 'N/A' }}"
                                                                        required />
                                                                </div>
                                                            </div>



                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="lead-name"
                                                                        class="form-label">Subcategory</label>
                                                                    <input disabled type="text" class="form-control"
                                                                        id="lead-name" name="name"
                                                                        value="{{ optional($regulation_pending->subcategory)->name ?? 'N/A' }}"
                                                                        required />
                                                                </div>
                                                            </div>



                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="lead-name" class="form-label">Effective
                                                                        Date</label>
                                                                    <input disabled type="text" class="form-control"
                                                                        id="lead-name" name="name"
                                                                        value="{{ optional($regulation_pending)->effective_date ?? 'N/A' }}"
                                                                        required />
                                                                </div>
                                                            </div>



                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="lead-name" class="form-label">Issue
                                                                        Date</label>
                                                                    <input disabled type="text" class="form-control"
                                                                        id="lead-name" name="name"
                                                                        value="{{ optional($regulation_pending)->issue_date ?? 'N/A' }}"
                                                                        required />
                                                                </div>
                                                            </div>



                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="lead-name" class="form-label">Document
                                                                        Version</label>
                                                                    <input disabled type="text" class="form-control"
                                                                        id="lead-name" name="name"
                                                                        value="{{ optional($regulation_pending)->document_version ?? 'N/A' }}"
                                                                        required />
                                                                </div>
                                                            </div>



                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="lead-name" class="form-label">Year</label>
                                                                    <input disabled type="text" class="form-control"
                                                                        id="lead-name" name="name"
                                                                        value="{{ optional($regulation_pending->year)->name ?? 'N/A' }}"
                                                                        required />
                                                                </div>
                                                            </div>


                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="lead-name" class="form-label">Month</label>
                                                                    <input disabled type="text" class="form-control"
                                                                        id="lead-name" name="name"
                                                                        value="{{ optional($regulation_pending->month)->name ?? 'N/A' }}"
                                                                        required />
                                                                </div>
                                                            </div>

                                                            {{-- Related Documents Section --}}
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Related Documents</label>
                                                                    <div class="form-control-wrap">
                                                                        @if(!empty($regulation_pending->related_docs))
                                                                            @php
                                                                                $relatedIds = explode(',', $regulation_pending->related_docs);
                                                                                $relatedDocs = \App\Models\Regulation::whereIn('id', $relatedIds)->get();
                                                                            @endphp
                                                                            @if($relatedDocs->count() > 0)
                                                                                <ul class="list-unstyled">
                                                                                    @foreach($relatedDocs as $relatedDoc)
                                                                                        <li>
                                                                                            <a href="{{ route('view_doc', $relatedDoc->id) }}" target="_blank">
                                                                                                {{ $relatedDoc->title }}
                                                                                            </a>
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            @else
                                                                                <p class="text-muted">No related documents found.</p>
                                                                            @endif
                                                                        @else
                                                                            <p class="text-muted">No related documents selected.</p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- End Related Documents Section --}}

                                                            <div class="col-md-12">

                                                                @if (!empty($regulation_pending->regulation_doc))
                                                                    <a href="public/pdf_documents/{{ $regulation_pending->regulation_doc }}"
                                                                        download="{{ $regulation_pending->regulation_doc }}">
                                                                        <h5>
                                                                            <br>
                                                                            <center>Click to download document</center>
                                                                    </a></h5>
                                                                @endif

                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            </tbody>
                            </table>
                        </div>
                    </div><!-- .card-preview -->
                </div><!-- .nk-block -->
            </div>
        </div>
        </div>
        </div>
        <!-- content @e -->
        <!-- @@ Group Add Modal @e -->
        <div class="modal fade" role="dialog" id="addUser">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                    <div class="modal-body modal-body-md">
                        <h5 class="title">Select Category</h5>

                        <div class="tab-content">
                            <div class="tab-pane active" id="infomation">
                                <div class="row gy-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="validationCustom04" class="form-label">Category</label>
                                            <form>
                                                <select class="form-select" name="selected_value" id="selected_value"
                                                    onchange="submitForm()">
                                                    <option selected disabled value="">Choose...</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->slug }}">{{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </div>
                                    </div>






                                    <div class="col-12">
                                        <script>
                                            function submitForm() {
                                                let selectedValue = document.getElementById('selected_value').value;

                                                if (selectedValue) {
                                                    window.location.href = '{{ url('') }}/regulations/create/' + selectedValue;
                                                }
                                            }
                                        </script>
                                        <br>
                                    </div>
                                </div><!-- .tab-pane -->

                            </div><!-- .tab-content -->

                        </div><!-- .modal-body -->
                    </div><!-- .modal-content -->
                </div><!-- .modal-dialog -->
            </div><!-- .modal -->



















        @endsection
