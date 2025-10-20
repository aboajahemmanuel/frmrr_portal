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
                                    <h3 class="nk-block-title page-title">Subscriptions Plan</h3>
                                    <div class="nk-block-des text-soft">

                                    </div>
                                </div><!-- .nk-block-head-content -->
                                <div class="nk-block-head-content">
                                    <div class="toggle-wrap nk-block-tools-toggle">
                                        <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                            data-target="more-options"><em class="icon ni ni-more-v"></em></a>
                                        <div class="toggle-expand-content" data-content="more-options">
                                            <ul class="nk-block-tools g-3">

                                                @can('transaction-create')
                                                    <li class="nk-block-tools-opt">
                                                        <a href="#" class="btn btn-icon btn-primary d-md-none"><em
                                                                class="icon ni ni-plus"></em></a>
                                                        <a href="#" data-toggle="modal" data-target="#addUser"
                                                            class="btn btn-primary d-none d-md-inline-flex"><em
                                                                class="icon ni ni-plus"></em><span>Add</span></a>
                                                    </li>
                                                @endcan

                                                {{-- <li><a href="#" data-toggle="modal" data-target="#addLead"><span>Add Orgranigation</span></a></li>
                                                        <li><a href="#"><span>Import Lead</span></a></li> --}}
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


                                @if (\Session::has('error'))
                                    <div class="alert alert-danger alert-icon alert-dismissible">
                                        <em class="icon ni ni-check-circle"></em> <strong> {{ \Session::get('error') }}<button
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

                                    <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                        <thead>
                                            <tr class="nk-tb-item nk-tb-head">
                                                <th>#</th>
                                                <th class="nk-tb-col"><span class="sub-text">Name</span></th>
                                                <th class="nk-tb-col"><span class="sub-text">Price</span></th>
                                                <th class="nk-tb-col"><span class="sub-text">Duration</span></th>
                                                <th class="nk-tb-col tb-col-lg"><span class="sub-text">Created At</span></th>
                                                <th class="nk-tb-col tb-col-lg"><span class="sub-text">Status</span></th>

                                                <th class="nk-tb-col nk-tb-col-tools text-right">
                                                </th>
                                                <th class="nk-tb-col nk-tb-col-tools text-right">
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $transaction)
                                                <tr class="nk-tb-item">
                                                    <td class="nk-tb-col nk-tb-col-check">
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td class="nk-tb-col">
                                                        <div class="user-card">

                                                            <div class="user-info">
                                                                <span class="tb-lead">{{ $transaction->name }} <span
                                                                        class="dot dot-success d-md-none ml-1"></span></span>

                                                            </div>
                                                        </div>
                                                    </td>


                                                    <td class="nk-tb-col">
                                                        <div class="user-card">

                                                            <div class="user-info">
                                                                <span class="tb-lead">{{ $transaction->price }} <span
                                                                        class="dot dot-success d-md-none ml-1"></span></span>

                                                            </div>
                                                        </div>
                                                    </td>



                                                    <td class="nk-tb-col">
                                                        <div class="user-card">

                                                            <div class="user-info">
                                                                <span class="tb-lead">{{ $transaction->duration }} <span
                                                                        class="dot dot-success d-md-none ml-1"></span></span>

                                                            </div>
                                                        </div>
                                                    </td>




                                                    <td class="nk-tb-col tb-col-lg">
                                                        <span>
                                                            @php
                                                                $postdate = date_format(
                                                                    $transaction->created_at,
                                                                    'F d,Y',
                                                                );

                                                            @endphp

                                                            <?php
                                                            
                                                            $timestamp = strtotime($postdate);
                                                            $newDateFormat = date('M. d, Y', $timestamp);
                                                            echo $newDateFormat;
                                                            
                                                            ?>



                                                        </span>
                                                    </td>

                                                    <td class="nk-tb-col tb-col-lg">
                                                        @if ($transaction->admin_status == 0)
                                                            <span class="badge fmdq_Blue">Awaiting Approval<span>
                                                        @endif
                                                        @if ($transaction->admin_status == 1)
                                                            <span class="badge badge-primary">Approved</span>
                                                        @endif
                                                        @if ($transaction->admin_status == 2)
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @endif


                                                        @if ($transaction->admin_status == 3)
                                                            <span class="badge badge-warning">Awaiting approval for
                                                                delete</span>
                                                        @endif

                                                    </td>

                                                    <td class="nk-tb-col tb-col-lg">

                                                        @if ($transaction->admin_status == 2)
                                                            {{ $transaction->note }}
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
                                                                            @if ($transaction->admin_status == 1 || $transaction->admin_status == 2)
                                                                                @can('transaction-edit')
                                                                                    <li>
                                                                                        <a href="#" data-toggle="modal"
                                                                                            data-target="#editGroup-{{ $transaction->id }}">
                                                                                            <em
                                                                                                class="icon ni ni-edit"></em><span>Edit</span>
                                                                                        </a>
                                                                                    </li>
                                                                                @endcan




                                                                                @can('transaction-delete')
                                                                                    <li><a href="#" data-toggle="modal"
                                                                                            data-target="#deleteGroup-{{ $transaction->id }}"><em
                                                                                                class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                                    </li>
                                                                                @endcan
                                                                            @endif



                                                                            @if ($transaction->admin_status == 0)
                                                                                @can('transaction-approve')
                                                                                    <li><a href="#" id="submit"
                                                                                            onclick="document.getElementById('approve-{{ $transaction->id }}').submit();"><em
                                                                                                class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                                    </li>
                                                                                @endcan


                                                                                @can('transaction-reject')
                                                                                    <li><a href="#" data-toggle="modal"
                                                                                            data-target="#reject-{{ $transaction->id }}"><em
                                                                                                class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                                    </li>
                                                                                @endcan
                                                                            @endif



                                                                            @if ($transaction->admin_status == 3)
                                                                                @can('transaction-approve')
                                                                                    <li><a href="#" id="submit"
                                                                                            onclick="document.getElementById('approve-{{ $transaction->id }}').submit();"><em
                                                                                                class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                                    </li>
                                                                                @endcan

                                                                                @can('transaction-reject')
                                                                                    <li><a href="#" data-toggle="modal"
                                                                                            data-target="#reject-{{ $transaction->id }}"><em
                                                                                                class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                                    </li>
                                                                                @endcan
                                                                                <form
                                                                                    id="delete_request-{{ $transaction->id }}"
                                                                                    action="{{ route('Subcriptionstatus', $transaction->id) }}"
                                                                                    method="POST" class="d-none"
                                                                                    style="display: none">
                                                                                    @csrf
                                                                                    <input name="status"
                                                                                        value="{{ $transaction->admin_status }}">
                                                                                </form>
                                                                            @endif

                                                                        </ul>

                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <form id="approve-{{ $transaction->id }}"
                                                            action="{{ route('Subcriptionstatus', $transaction->id) }}"
                                                            method="POST" class="d-none" style="display: none">
                                                            @csrf
                                                            <input name="status" value="1">
                                                        </form>


                                                        <div class="modal fade" role="dialog"
                                                            id="reject-{{ $transaction->id }}">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg"
                                                                role="document">
                                                                <div class="modal-content">
                                                                    <a href="#" class="close" data-dismiss="modal"><em
                                                                            class="icon ni ni-cross-sm"></em></a>
                                                                    <div class="modal-body modal-body-md">
                                                                        <h5 class="title">{{ $transaction->name }}</h5>
                                                                        <form method="POST"
                                                                            action="{{ route('Subcriptionstatus', $transaction->id) }}"
                                                                            id="modifyForm-{{ $transaction->id }}">
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
                                                                                                        id="ModifySubmitBtn-{{ $transaction->id }}"
                                                                                                        type="submit">
                                                                                                        <i class="fas fa-spinner fa-spin"
                                                                                                            style="display:none;"></i>
                                                                                                        <span
                                                                                                            class="btn-text">Update</span>
                                                                                                    </button>
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
                                                    </td>
                                                </tr><!-- .nk-tb-item  -->

                                                <script>
                                                    function loading(buttonId) {
                                                        $("#" + buttonId + " .fa-spinner").show();
                                                        $("#" + buttonId + " .btn-text").html("Processing...");
                                                    }

                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        document.getElementById('modifyForm-{{ $transaction->id }}').addEventListener('submit', function(
                                                            event) {
                                                            if (this.checkValidity() === false) {
                                                                event.preventDefault();
                                                                event.stopPropagation();
                                                            } else {
                                                                loading('ModifySubmitBtn-{{ $transaction->id }}');
                                                                document.getElementById('ModifySubmitBtn-{{ $transaction->id }}').disabled = true;
                                                            }
                                                            this.classList.add('was-validated');
                                                        }, false);
                                                    });
                                                </script>
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
                        <h5 class="title">Add Plan</h5>
                        <form method="POST" id="subForm" action="{{ route('add_plan') }}">
                            @csrf
                            <div class="tab-content">
                                <div class="tab-pane active" id="infomation">
                                    <div class="row gy-4">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label" for="lead-name">Name <span
                                                        style="color: red">*</span></label>
                                                <div class="form-control-wrap">
                                                    <input name="name" required type="text" class="form-control"
                                                        id="lead-name">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label" for="lead-name">Price <span
                                                        style="color: red">*</span></label>
                                                <div class="form-control-wrap">
                                                    <input name="price" required type="number"
                                                        class="form-control formattedNumberField" id="lead-name">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label" for="lead-name">Duration (Days) <span
                                                        style="color: red">*</span> </label>
                                                <div class="form-control-wrap">
                                                    <input name="duration" required type="number" class="form-control"
                                                        id="lead-name">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label" for="lead-name">Description <span
                                                        style="color: red">*</span></label>
                                                <div class="form-control-wrap">
                                                    <textarea required name="description" class="form-control" id="lead-name"> </textarea>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label" for="add-account">Select Authoriser <span
                                                        style="color: red;">*</span></label>
                                                <div class="form-control-wrap">

                                                    <select required name="authorizer_id" class="form-select form-control"
                                                        data-placeholder="Select one">
                                                        <option value="">---</option>
                                                        @foreach ($authoriser as $auth)
                                                            <option value="{{ $auth->id }}">
                                                                {{ $auth->name }}</option>
                                                        @endforeach


                                                    </select>


                                                </div>
                                            </div>
                                        </div>





                                        <div class="col-12">
                                            <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                                <li>
                                                    <button class="btn btn-lg btn-primary btn-block" id="addSubmitBtn"
                                                        type="submit">
                                                        <i class="fas fa-spinner fa-spin" style="display:none;"></i>
                                                        <span class="btn-text">Submit</span>
                                                    </button>
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




        @foreach ($data as $transaction)
            <div class="modal fade" role="dialog" id="editGroup-{{ $transaction->id }}">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                        <div class="modal-body modal-body-md">
                            <h5 class="title">Edit Plan</h5>
                            <form method="POST" id="editForm-{{ $transaction->id }}"
                                action="{{ route('update_plan', $transaction->id) }}">
                                @csrf
                                <div class="tab-content">
                                    <div class="tab-pane active" id="infomation">
                                        <div class="row gy-4">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="lead-name">Name<span
                                                            style="color: red">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <input name="name" required value="{{ $transaction->name }}"
                                                            type="text" class="form-control" id="lead-name">
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="lead-name">Price <span
                                                            style="color: red">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <input name="price" required value="{{ $transaction->price }}"
                                                            type="number" class="form-control" id="lead-name">
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="lead-name">Duration (Days) <span
                                                            style="color: red">*</span> </label>
                                                    <div class="form-control-wrap">
                                                        <input name="duration" required value="{{ $transaction->duration }}"
                                                            type="number" class="form-control" id="lead-name">
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="lead-name">Description<span
                                                            style="color: red">*</span></label>
                                                    <div class="form-control-wrap">
                                                        <textarea name="description" required class="form-control" id="lead-name">{{ $transaction->description }}</textarea>
                                                    </div>
                                                </div>
                                            </div>




                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="add-account">Select Authoriser <span
                                                            style="color: red;">*</span></label>
                                                    <div class="form-control-wrap">

                                                        <select required name="authorizer_id" class="form-select form-control"
                                                            data-placeholder="Select one">
                                                            <option value="">---</option>
                                                            @foreach ($authoriser as $auth)
                                                                <option value="{{ $auth->id }}">
                                                                    {{ $auth->name }}</option>
                                                            @endforeach


                                                        </select>


                                                    </div>
                                                </div>
                                            </div>




                                            <div class="col-12">
                                                <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                                    <li>
                                                        <button class="btn btn-lg btn-primary btn-block"
                                                            id="editSubmitBtn-{{ $transaction->id }}" type="submit">
                                                            <i class="fas fa-spinner fa-spin" style="display:none;"></i>
                                                            <span class="btn-text">Update</span>
                                                        </button>
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

            <script>
                function loading(buttonId) {
                    $("#" + buttonId + " .fa-spinner").show();
                    $("#" + buttonId + " .btn-text").html("Processing...");
                }

                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('editForm-{{ $transaction->id }}').addEventListener('submit', function(event) {
                        if (this.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        } else {
                            loading('editSubmitBtn-{{ $transaction->id }}');
                            document.getElementById('editSubmitBtn-{{ $transaction->id }}').disabled = true;
                        }
                        this.classList.add('was-validated');
                    }, false);
                });
            </script>
        @endforeach





        <!-- @@  Delete Modal @e -->
        @foreach ($data as $transaction)
            <div class="modal fade" id="deleteGroup-{{ $transaction->id }}">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross"></em></a>
                        <div class="modal-body modal-body-sm text-center">
                            <div class="nk-modal py-4">
                                <form method="POST" action="{{ route('delete_plan', $transaction->id) }}">
                                    @csrf
                                    <em class="nk-modal-icon icon icon-circle icon-circle-xxl ni ni-cross bg-danger"></em>
                                    <h4 class="nk-modal-title">Are You Sure ?</h4>
                                    <div class="nk-modal-text mt-n2">
                                        <p class="text-soft">This Lead data will be delete permanently.</p>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="add-account">Select Authoriser <span
                                                    style="color: red;">*</span></label>
                                            <div class="form-control-wrap">

                                                <select required name="authorizer_id" class="form-select form-control"
                                                    data-placeholder="Select one">
                                                    <option value="">---</option>
                                                    @foreach ($authoriser as $auth)
                                                        <option value="{{ $auth->id }}">
                                                            {{ $auth->name }}</option>
                                                    @endforeach


                                                </select>


                                            </div>
                                        </div>
                                    </div>


                                    <ul class="d-flex justify-content-center gx-4 mt-4">
                                        <li>

                                            <button class="btn btn-lg btn-primary btn-block"
                                                id="deleteSubmitBtn-{{ $transaction->id }}" type="submit">
                                                <i class="fas fa-spinner fa-spin" style="display:none;"></i>
                                                <span class="btn-text">Yes, Delete it</span>
                                            </button>

                                </form>


                                </li>
                                <li>
                                    <button data-dismiss="modal" data-toggle="modal" data-target="#editEventPopup"
                                        class="btn btn-danger btn-dim">Cancel</button>
                                </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                document.getElementById('deleteSubmitBtn-{{ $transaction->id }}').addEventListener('click', function() {
                    loading('deleteSubmitBtn-{{ $transaction->id }}');
                    setTimeout(() => {
                        document.getElementById('deleteSubmitBtn-{{ $transaction->id }}').disabled = true;
                    }, 50);
                });
            </script>
        @endforeach
        <!-- .modal -->







        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('subForm').addEventListener('submit', function(event) {
                    if (this.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    } else {
                        loading('addSubmitBtn');
                        document.getElementById('addSubmitBtn').disabled = true;
                    }
                    this.classList.add('was-validated');
                }, false);
            });
        </script>




    @endsection
