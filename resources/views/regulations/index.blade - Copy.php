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
                                                <th class="nk-tb-col">Ceased/Repealed/Amended</th>
                                                <th class="nk-tb-col">Date Created</th>
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
                                                        @if ($regulation->status == 0)
                                                            <span class="badge fmdq_Blue">Awaiting Approval<span>
                                                        @endif
                                                        @if ($regulation->status == 1)
                                                            <span class="badge badge-primary">Approved</span>
                                                        @endif
                                                        @if ($regulation->status == 2)
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @endif


                                                        @if ($regulation->status == 3)
                                                            <span class="badge badge-warning">Awaiting approval for
                                                                delete</span>
                                                        @endif

                                                    </td>


                                                    <td class="nk-tb-col">
                                                        @if ($regulation->ceased == 'Ceased')
                                                            <span class="badge fmdq_Blue">Ceased</span>
                                                        @endif
                                                        @if ($regulation->ceased == 'Repealed')
                                                            <span class="badge fmdq_Blue">Repealed</span>
                                                        @endif

                                                        @if ($regulation->ceased == 'Amended')
                                                            <span class="badge fmdq_Blue">Amended</span>
                                                        @endif


                                                    </td>

                                                    <td class="nk-tb-col">
                                                        @php
                                                            $postdate = date_format($regulation->created_at, 'F d,Y');

                                                        @endphp

                                                        <?php
                                                        
                                                        $timestamp = strtotime($postdate);
                                                        $newDateFormat = date('M. d, Y', $timestamp);
                                                        echo $newDateFormat;
                                                        
                                                        ?>



                                                    </td>

                                                    <td class="nk-tb-col">
                                                        @if ($regulation->status == 2)
                                                            {{ $regulation->note }}
                                                        @endif
                                                    </td>

                                                    <td class="nk-tb-col nk-tb-col-tools">
                                                        {{-- <ul class="nk-tb-actions gx-1">

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
                                                                            @can('regulation-edit')
                                                                                <li><a
                                                                                        href="{{ route('edit_doc', $regulation->id) }}"><em
                                                                                            class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                                </li>
                                                                                <li>


                                                                                    <a href="#" data-toggle="modal"
                                                                                        data-target="#index-{{ $regulation->id }}"><em
                                                                                            class="icon ni ni-shield-star-fill"></em><span>index</span></a>
                                                                                </li>
                                                                            @endcan

                                                                            @can('regulation-delete')
                                                                                <li>


                                                                                    <a href="#" data-toggle="modal"
                                                                                        data-target="#deleteReg-{{ $regulation->id }}"><em
                                                                                            class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                                </li>
                                                                            @endcan

                                                                            @can('document-ceased')
                                                                                <li>


                                                                                    <a href="#" data-toggle="modal"
                                                                                        data-target="#ceased-{{ $regulation->id }}"><em
                                                                                            class="icon ni ni-shield-star-fill"></em><span>Ceased/Repealed</span></a>
                                                                                </li>
                                                                            @endcan




                                                                            @php
                                                                                $user = Auth::user()->group_id;

                                                                            @endphp
                                                                            @if ($regulation->status == 0)
                                                                                @if ($regulation->group_id == $user)
                                                                                    @can('regulation-approve')
                                                                                        <li><a href="#" id="submit"
                                                                                                onclick="document.getElementById('test1-{{ $regulation->id }}').submit();"><em
                                                                                                    class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                                        </li>
                                                                                    @endcan


                                                                                    @can('regulation-reject')
                                                                                        <li>


                                                                                            <a href="#" data-toggle="modal"
                                                                                                data-target="#rejectdocument-{{ $regulation->id }}"><em
                                                                                                    class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                                        </li>
                                                                                    @endcan
                                                                                @endif
                                                                            @endif


                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul> --}}


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
                                                                            @if ($regulation->status != 3)
                                                                                @if ($regulation->status != 0)
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



                                                                            @if ($regulation->status == 0)
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
                                                                            @endif



                                                                            @if ($regulation->status == 3)
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
                                                                                <form id="delete_request-{{ $regulation->id }}"
                                                                                    action="{{ route('RegStatus', $regulation->id) }}"
                                                                                    method="POST" class="d-none"
                                                                                    style="display: none">
                                                                                    @csrf
                                                                                    <input name="status"
                                                                                        value="{{ $regulation->status }}">
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
                                                                    <center>Click to downlaod document
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
