@extends('layouts.master')

@section('content')

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">




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
                                    <!-- Filter Section -->
                                    <div class="filter-container mb-4">
                                        <div class="row g-3 align-items-end">
                                            <!-- Search Box -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Search Documents</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="globalSearch" 
                                                               placeholder="Search by title, category...">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Category Filter -->
                                            <div class="col-md-2" style="display: none;">
                                                <div class="form-group">
                                                    <label class="form-label">Category</label>
                                                    <select class="form-select form-control" id="categoryFilter">
                                                        <option value="">All Categories</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Status Filter -->
                                            <div class="col-md-2" style="display: none;">
                                                <div class="form-group">
                                                    <label class="form-label">Approval Status</label>
                                                    <select class="form-select form-control" id="statusFilter">
                                                        <option value="">All Status</option>
                                                        <option value="0">Awaiting Approval</option>
                                                        <option value="1">Approved</option>
                                                        <option value="2">Rejected</option>
                                                        <option value="3">Awaiting Delete</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Ceased Status Filter -->
                                            <div class="col-md-2" style="display: none;">
                                                <div class="form-group">
                                                    <label class="form-label">Document Status</label>
                                                    <select class="form-select form-control" id="ceasedFilter">
                                                        <option value="">All Documents</option>
                                                        <option value="Ceased">Ceased</option>
                                                        <option value="Repealed">Repealed</option>
                                                        <option value="Amended">Amended</option>
                                                        <option value="Active">Active</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Year Filter -->
                                            <div class="col-md-2" style="display: none;">
                                                <div class="form-group">
                                                    <label class="form-label">Year</label>
                                                    <select class="form-select form-control" id="yearFilter">
                                                        <option value="">All Years</option>
                                                        @for($year = date('Y'); $year >= 2000; $year--)
                                                            <option value="{{ $year }}">{{ $year }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="col-md-1" style="display: none;">
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-dim btn-outline-primary" id="clearFilters" 
                                                            title="Clear all filters">
                                                        <em class="icon ni ni-reload"></em>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Active Filters Display -->
                                        <div class="row mt-2" id="activeFiltersRow" style="display: none;">
                                            <div class="col-12">
                                                <div class="d-flex align-items-center flex-wrap gap-2">
                                                    <span class="text-muted">Active Filters:</span>
                                                    <div id="activeFilters" class="d-flex flex-wrap gap-2"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Export and Info Section -->
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="dataTables_info" id="resultsInfo">
                                                    Showing {{ $data->firstItem() ?? 0 }} to {{ $data->lastItem() ?? 0 }} of {{ $data->total() }} entries
                                                    <span id="filteredInfo" style="display: none;"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 text-end" style="display: none;">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" id="exportCSV">
                                                        <em class="icon ni ni-file-text"></em> Export CSV
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-success" id="exportExcel">
                                                        <em class="icon ni ni-file-xls"></em> Export Excel
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Filter Section -->

                                    <table class="nk-tb-list nk-tb-ulist table-striped" id="regulationsTable"
                                        data-auto-responsive="false" data-paging="false" data-info="false">
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
                                                    <td class="nk-tb-col"> {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
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
                            
                            <!-- Pagination Links -->
                            @if($data->hasPages())
                            <div class="mt-4 d-flex justify-content-center">
                                <nav aria-label="Deployment pagination">
                                    {{ $data->onEachSide(1)->links('vendor.pagination.bootstrap-4') }}
                                </nav>
                            </div>
                            @endif
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



















        <style>
            /* Modern Filter Styling */
            .filter-container {
                background: #f8f9fa;
                padding: 20px;
                border-radius: 8px;
                border: 1px solid #e5e9f2;
            }

            .filter-container .form-label {
                font-weight: 600;
                font-size: 0.875rem;
                color: #364a63;
                margin-bottom: 0.5rem;
            }

            .filter-container .form-control,
            .filter-container .form-select {
                border: 1px solid #dbdfea;
                border-radius: 4px;
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
                transition: all 0.3s ease;
            }

            .filter-container .form-control:focus,
            .filter-container .form-select:focus {
                border-color: #6576ff;
                box-shadow: 0 0 0 3px rgba(101, 118, 255, 0.1);
            }

            #clearFilters {
                width: 100%;
                padding: 0.5rem;
            }

            /* Active Filter Chips */
            .filter-chip {
                display: inline-flex;
                align-items: center;
                background: #6576ff;
                color: white;
                padding: 4px 12px;
                border-radius: 16px;
                font-size: 0.75rem;
                margin-right: 8px;
                margin-bottom: 8px;
            }

            .filter-chip .remove-filter {
                margin-left: 8px;
                cursor: pointer;
                font-weight: bold;
                opacity: 0.8;
            }

            .filter-chip .remove-filter:hover {
                opacity: 1;
            }

            /* Table Row Highlight on Hover */
            .nk-tb-item:hover {
                background-color: #f5f6fa !important;
                transition: background-color 0.2s ease;
            }

            /* Export Buttons */
            .btn-group .btn {
                font-size: 0.875rem;
            }

            /* Results Info */
            .dataTables_info {
                font-size: 0.875rem;
                color: #526484;
                padding: 0.5rem 0;
            }

            #filteredInfo {
                color: #6576ff;
                font-weight: 600;
                margin-left: 8px;
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                .filter-container .col-md-3,
                .filter-container .col-md-2,
                .filter-container .col-md-1 {
                    width: 100%;
                    margin-bottom: 1rem;
                }

                .btn-group {
                    width: 100%;
                }

                .btn-group .btn {
                    width: 50%;
                }
            }
        </style>

        <script>
            console.log('Filter script loaded');
            
            // Use window.onload to ensure everything is ready
            window.addEventListener('load', function() {
                console.log('Window loaded, initializing filters');
                
                // Get all table rows (excluding header)
                const table = document.getElementById('regulationsTable');
                if (!table) {
                    console.error('Table not found!');
                    return;
                }
                console.log('Table found:', table);
                
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr.nk-tb-item'));
                const totalRows = rows.length;
                console.log('Total rows found:', totalRows);
                
                // Check if all filter elements exist
                const searchInput = document.getElementById('globalSearch');
                const categoryFilter = document.getElementById('categoryFilter');
                const statusFilter = document.getElementById('statusFilter');
                const ceasedFilter = document.getElementById('ceasedFilter');
                const yearFilter = document.getElementById('yearFilter');
                const clearBtn = document.getElementById('clearFilters');
                
                console.log('Filter elements:', {
                    searchInput: !!searchInput,
                    categoryFilter: !!categoryFilter,
                    statusFilter: !!statusFilter,
                    ceasedFilter: !!ceasedFilter,
                    yearFilter: !!yearFilter,
                    clearBtn: !!clearBtn
                });
                
                if (!searchInput || !categoryFilter || !statusFilter || !ceasedFilter || !yearFilter || !clearBtn) {
                    console.error('Some filter elements are missing!');
                    return;
                }
                
                let currentFilters = {
                    search: '',
                    category: '',
                    status: '',
                    ceased: '',
                    year: ''
                };

                // Filter rows based on current filters
                function filterRows() {
                    let visibleCount = 0;
                    
                    rows.forEach(row => {
                        let visible = true;
                        const cells = row.querySelectorAll('td');
                        
                        // Global search filter (search in title and category)
                        if (currentFilters.search) {
                            const title = cells[1]?.textContent.toLowerCase() || '';
                            const category = cells[2]?.textContent.toLowerCase() || '';
                            const searchTerm = currentFilters.search.toLowerCase();
                            visible = visible && (title.includes(searchTerm) || category.includes(searchTerm));
                        }
                        
                        // Category filter
                        if (currentFilters.category) {
                            const categoryName = document.querySelector(`#categoryFilter option[value="${currentFilters.category}"]`)?.textContent;
                            const cellCategory = cells[2]?.textContent.trim() || '';
                            visible = visible && (cellCategory === categoryName);
                        }
                        
                        // Status filter
                        if (currentFilters.status) {
                            const statusCell = cells[3]?.textContent.trim() || '';
                            let statusMatch = false;
                            if (currentFilters.status === '0') statusMatch = statusCell.includes('Awaiting Approval');
                            else if (currentFilters.status === '1') statusMatch = statusCell.includes('Approved') && !statusCell.includes('Awaiting');
                            else if (currentFilters.status === '2') statusMatch = statusCell.includes('Rejected');
                            else if (currentFilters.status === '3') statusMatch = statusCell.includes('Awaiting approval for delete');
                            visible = visible && statusMatch;
                        }
                        
                        // Ceased status filter
                        if (currentFilters.ceased) {
                            const ceasedCell = cells[4]?.textContent.trim() || '';
                            visible = visible && ceasedCell.includes(currentFilters.ceased);
                        }
                        
                        // Year filter
                        if (currentFilters.year) {
                            const dateCell = cells[5]?.textContent.trim() || '';
                            visible = visible && dateCell.includes(currentFilters.year);
                        }
                        
                        // Show/hide row
                        row.style.display = visible ? '' : 'none';
                        if (visible) visibleCount++;
                    });
                    
                    updateResultsInfo(visibleCount);
                }

                // Global search functionality
                searchInput.addEventListener('keyup', function(e) {
                    console.log('Search triggered:', e.target.value);
                    currentFilters.search = e.target.value;
                    filterRows();
                    updateFilterDisplay();
                });
                console.log('Search filter attached');

                // Category filter
                document.getElementById('categoryFilter').addEventListener('change', function(e) {
                    currentFilters.category = e.target.value;
                    filterRows();
                    updateFilterDisplay();
                });

                // Status filter
                document.getElementById('statusFilter').addEventListener('change', function(e) {
                    currentFilters.status = e.target.value;
                    filterRows();
                    updateFilterDisplay();
                });

                // Ceased status filter
                document.getElementById('ceasedFilter').addEventListener('change', function(e) {
                    currentFilters.ceased = e.target.value;
                    filterRows();
                    updateFilterDisplay();
                });

                // Year filter
                document.getElementById('yearFilter').addEventListener('change', function(e) {
                    currentFilters.year = e.target.value;
                    filterRows();
                    updateFilterDisplay();
                });

                // Clear all filters
                document.getElementById('clearFilters').addEventListener('click', function() {
                    document.getElementById('globalSearch').value = '';
                    document.getElementById('categoryFilter').value = '';
                    document.getElementById('statusFilter').value = '';
                    document.getElementById('ceasedFilter').value = '';
                    document.getElementById('yearFilter').value = '';
                    
                    currentFilters = {
                        search: '',
                        category: '',
                        status: '',
                        ceased: '',
                        year: ''
                    };
                    
                    filterRows();
                    updateFilterDisplay();
                });

                // Update active filter display
                function updateFilterDisplay() {
                    const activeFiltersArray = [];
                    let filtersHtml = '';

                    // Check each filter
                    if (currentFilters.search) {
                        activeFiltersArray.push({type: 'search', label: 'Search: ' + currentFilters.search});
                    }

                    if (currentFilters.category) {
                        const categoryText = document.querySelector(`#categoryFilter option[value="${currentFilters.category}"]`)?.textContent;
                        activeFiltersArray.push({type: 'category', label: 'Category: ' + categoryText});
                    }

                    if (currentFilters.status) {
                        const statusText = document.querySelector(`#statusFilter option[value="${currentFilters.status}"]`)?.textContent;
                        activeFiltersArray.push({type: 'status', label: 'Status: ' + statusText});
                    }

                    if (currentFilters.ceased) {
                        activeFiltersArray.push({type: 'ceased', label: 'Document Status: ' + currentFilters.ceased});
                    }

                    if (currentFilters.year) {
                        activeFiltersArray.push({type: 'year', label: 'Year: ' + currentFilters.year});
                    }

                    // Display active filters
                    if (activeFiltersArray.length > 0) {
                        activeFiltersArray.forEach(function(filter) {
                            filtersHtml += '<span class="filter-chip">' + filter.label + 
                                          ' <span class="remove-filter" data-type="' + filter.type + '">×</span></span>';
                        });
                        document.getElementById('activeFilters').innerHTML = filtersHtml;
                        document.getElementById('activeFiltersRow').style.display = 'block';
                    } else {
                        document.getElementById('activeFiltersRow').style.display = 'none';
                    }
                }

                // Remove individual filter
                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-filter')) {
                        const filterType = e.target.getAttribute('data-type');
                        
                        if (filterType === 'search') {
                            document.getElementById('globalSearch').value = '';
                            currentFilters.search = '';
                        } else if (filterType === 'category') {
                            document.getElementById('categoryFilter').value = '';
                            currentFilters.category = '';
                        } else if (filterType === 'status') {
                            document.getElementById('statusFilter').value = '';
                            currentFilters.status = '';
                        } else if (filterType === 'ceased') {
                            document.getElementById('ceasedFilter').value = '';
                            currentFilters.ceased = '';
                        } else if (filterType === 'year') {
                            document.getElementById('yearFilter').value = '';
                            currentFilters.year = '';
                        }
                        
                        filterRows();
                        updateFilterDisplay();
                    }
                });

                // Update results info
                function updateResultsInfo(visibleCount) {
                    const filteredInfo = document.getElementById('filteredInfo');
                    
                    if (visibleCount < totalRows) {
                        filteredInfo.innerHTML = '(filtered from ' + totalRows + ' total entries)';
                        filteredInfo.style.display = 'inline';
                    } else {
                        filteredInfo.style.display = 'none';
                    }
                }

                // Export to CSV
                document.getElementById('exportCSV').addEventListener('click', function() {
                    exportTableToCSV('regulations_export.csv');
                });

                // Export to Excel (CSV format with .xls extension)
                document.getElementById('exportExcel').addEventListener('click', function() {
                    exportTableToCSV('regulations_export.xls');
                });

                // Export function
                function exportTableToCSV(filename) {
                    const csv = [];
                    const visibleRows = rows.filter(row => row.style.display !== 'none');
                    
                    // Headers
                    const headers = [];
                    const headerCells = table.querySelectorAll('thead th');
                    headerCells.forEach((cell, index) => {
                        if (index < 7) { // Exclude action columns
                            headers.push(cell.textContent.trim());
                        }
                    });
                    csv.push(headers.join(','));
                    
                    // Data rows
                    visibleRows.forEach(row => {
                        const rowData = [];
                        const cells = row.querySelectorAll('td');
                        cells.forEach((cell, index) => {
                            if (index < 7) { // Exclude action columns
                                const cellText = cell.textContent.trim().replace(/\n/g, ' ').replace(/,/g, ';');
                                rowData.push('"' + cellText + '"');
                            }
                        });
                        csv.push(rowData.join(','));
                    });
                    
                    // Download
                    const csvContent = csv.join('\n');
                    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                    const link = document.createElement('a');
                    if (link.download !== undefined) {
                        const url = URL.createObjectURL(blob);
                        link.setAttribute('href', url);
                        link.setAttribute('download', filename);
                        link.style.visibility = 'hidden';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    }
                }

                // Initialize filter display
                updateFilterDisplay();
                filterRows();
            });
        </script>

        @endsection
