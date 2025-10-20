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
                                    <h3 class="nk-block-title page-title">Deactivated Users</h3>
                                    <div class="nk-block-des text-soft">

                                    </div>
                                </div><!-- .nk-block-head-content -->
                                <div class="nk-block-head-content">
                                    <div class="toggle-wrap nk-block-tools-toggle">
                                        <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                            data-target="more-options"><em class="icon ni ni-more-v"></em></a>
                                        <div class="toggle-expand-content" data-content="more-options">
                                            <ul class="nk-block-tools g-3">

                                                <li class="nk-block-tools-opt">
                                                    <a href="#" class="btn btn-icon btn-primary d-md-none"></a>
                                                    <a href="{{ url('admin_users') }}"
                                                        class="btn btn-primary d-none d-md-inline-flex">
                                                        <span>Active Users</span>
                                                    </a>
                                                </li>


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
                                               <th class="nk-tb-col tb-col-lg"><span class="sub-text">First Name</span></th>
                                                <th class="nk-tb-col tb-col-lg"><span class="sub-text">Last Name</span></th>
                                                <th class="nk-tb-col tb-col-lg"><span class="sub-text">Group</span></th>
                                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">Email</span></th>
                                                <th class="nk-tb-col tb-col-md"><span class="sub-text">Role</span></th>

                                                <th class="nk-tb-col tb-col-lg"><span class="sub-text">Created At</span></th>
                                                <th class="nk-tb-col tb-col-lg"><span class="sub-text">Status</span></th>
                                                <th class="nk-tb-col tb-col-lg"><span class="sub-text"></span></th>

                                                <th class="nk-tb-col nk-tb-col-tools text-right">
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $key => $user)
                                            @php
                                                $nameParts = explode(' ', $user->name);
                                                $firstName = $nameParts[0] ?? '';
                                                $lastName = $nameParts[1] ?? '';
                                            @endphp
                                                <tr class="nk-tb-item">
                                                    <td class="nk-tb-col nk-tb-col-check">
                                                        {{ $loop->iteration }}
                                                    </td>
                                                      <td class="nk-tb-col">
                                                        <div class="user-card">

                                                            <div class="user-info">
                                                                <span class="tb-lead">{{ $firstName }} <span
                                                                        class="dot dot-success d-md-none ml-1"></span></span>

                                                            </div>
                                                        </div>
                                                    </td>

                                                     <td class="nk-tb-col">
                                                        <div class="user-card">

                                                            <div class="user-info">
                                                                <span class="tb-lead">{{ $lastName }} <span
                                                                        class="dot dot-success d-md-none ml-1"></span></span>

                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="nk-tb-col tb-col-lg"
                                                        data-order="Email Verified - Kyc Unverified">
                                                        {{ optional($user->group)->name }}

                                                    </td>
                                                    <td class="nk-tb-col tb-col-mb" data-order="35040.34">
                                                        <span class="tb-amount">{{ $user->email }}<span
                                                                class="currency"></span></span>
                                                    </td>
                                                    <td class="nk-tb-col tb-col-md">
                                                        @if (!empty($user->getRoleNames()))
                                                            @foreach ($user->getRoleNames() as $val)
                                                                <span class="badge badge-primary">{{ $val }}</span>
                                                            @endforeach
                                                        @endif
                                                    </td>

                                                    <td class="nk-tb-col tb-col-lg">
                                                        <span>
                                                            @php
                                                                $postdate = date_format($user->created_at, 'F d,Y');

                                                            @endphp

                                                            <?php
                                                            
                                                            $timestamp = strtotime($postdate);
                                                            $newDateFormat = date('M. d, Y', $timestamp);
                                                            echo $newDateFormat;
                                                            
                                                            ?></span>
                                                    </td>

                                                    <td class="nk-tb-col tb-col-lg">
                                                        @if ($user->status == 5)
                                                            <span class="badge fmdq_Blue">Awaiting Approval<span>
                                                        @endif
                                                        @if ($user->status == 1)
                                                            <span class="badge badge-primary">Approved</span>
                                                        @endif
                                                        @if ($user->status == 2)
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @endif

                                                        @if ($user->status == 4)
                                                            <span class="badge badge-danger">Disabled</span>
                                                        @endif


                                                        @if ($user->status == 3)
                                                            <span class="badge badge-warning">Awaiting approval for
                                                                delete</span>
                                                        @endif

                                                    </td>


                                                    <td class="nk-tb-col tb-col-lg">

                                                        @if ($user->status == 2)
                                                            {{ $user->note }}
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
                                                                            @if ($user->status == 4)
                                                                                @can('user-edit')
                                                                                    <li><a href="#" data-toggle="modal"
                                                                                            data-target="#changeStatus-{{ $user->id }}"><em
                                                                                                class="icon ni ni-trash"></em><span>Change
                                                                                                Status</span></a>
                                                                                    </li>
                                                                                @endcan
                                                                            @endif




                                                                            @if ($user->status == 5)
                                                                                @can('user-approve')
                                                                                    <li><a href="#" id="submit"
                                                                                            onclick="document.getElementById('approve-{{ $user->id }}').submit();"><em
                                                                                                class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                                    </li>
                                                                                @endcan


                                                                                @can('user-reject')
                                                                                    <li><a href="#" data-toggle="modal"
                                                                                            data-target="#reject-{{ $user->id }}"><em
                                                                                                class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                                    </li>
                                                                                @endcan
                                                                            @endif



                                                                            @if ($user->status == 3)
                                                                                @can('user-approve')
                                                                                    <li><a href="#" id="submit"
                                                                                            onclick="document.getElementById('approve-{{ $user->id }}').submit();"><em
                                                                                                class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                                    </li>
                                                                                @endcan

                                                                                @can('user-reject')
                                                                                    <li><a href="#" data-toggle="modal"
                                                                                            data-target="#reject-{{ $user->id }}"><em
                                                                                                class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                                    </li>
                                                                                @endcan
                                                                                <form id="delete_request-{{ $user->id }}"
                                                                                    action="{{ route('userStatus', $user->id) }}"
                                                                                    method="POST" class="d-none"
                                                                                    style="display: none">
                                                                                    @csrf
                                                                                    <input name="status"
                                                                                        value="{{ $user->status }}">
                                                                                </form>
                                                                            @endif



                                                                        </ul>

                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <form id="approve-{{ $user->id }}"
                                                            action="{{ route('userStatus', $user->id) }}" method="POST"
                                                            class="d-none" style="display: none">
                                                            @csrf
                                                            <input name="status" value="1">
                                                        </form>


                                                        <div class="modal fade" role="dialog"
                                                            id="reject-{{ $user->id }}">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg"
                                                                role="document">
                                                                <div class="modal-content">
                                                                    <a href="#" class="close" data-dismiss="modal"><em
                                                                            class="icon ni ni-cross-sm"></em></a>
                                                                    <div class="modal-body modal-body-md">
                                                                        <h5 class="title">{{ $user->name }}</h5>
                                                                        <form method="POST"
                                                                            id="modifyForm-{{ $user->id }}"
                                                                            action="{{ route('userStatus', $user->id) }}"
                                                                            id="rejectForm-{{ $user->id }}">
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
                                                                                                        id="rejectSubmitBtn-{{ $user->id }}"
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
                                                                                                            document.getElementById('rejectForm-{{ $user->id }}').addEventListener('submit', function(event) {
                                                                                                                if (this.checkValidity() === false) {
                                                                                                                    event.preventDefault();
                                                                                                                    event.stopPropagation();
                                                                                                                } else {
                                                                                                                    loading('rejectSubmitBtn-{{ $user->id }}');
                                                                                                                    document.getElementById('rejectSubmitBtn-{{ $user->id }}').disabled = true;
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
                                                    </td>
                                                </tr><!-- .nk-tb-item  -->


                                                <div class="modal fade" role="dialog"
                                                    id="changeStatus-{{ $user->id }}">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Change Status</h5>
                                                                <a href="#" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <em class="icon ni ni-cross"></em>
                                                                </a>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form method="POST"
                                                                    action="{{ route('statusUser', $user->id) }}"
                                                                    class="form-validate is-alter"
                                                                    enctype="multipart/form-data">
                                                                    @csrf

                                                                    <div class="row gx-4 gy-3">
                                                                        <div class="col-12">
                                                                            <div class="form-group">
                                                                                <label class="form-label"
                                                                                    for="event-title">Select Status</label>
                                                                                <div class="form-control-wrap">
                                                                                    <select class="form-control"
                                                                                        name="status">
                                                                                        <option value="1"
                                                                                            {{ $user->status == 1 ? 'selected' : '' }}>
                                                                                            Active</option>
                                                                                        {{-- <option value="4"
                                                                                            {{ $user->status == 4 ? 'selected' : '' }}>
                                                                                            Inactive</option> --}}
                                                                                    </select>


                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-12">
                                                                            <div class="form-group">
                                                                                <label class="form-label"
                                                                                    for="add-account">Select Authoriser <span
                                                                                        style="color: red;">*</span></label>
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


                                                                        <div class="col-12">
                                                                            <ul
                                                                                class="d-flex justify-content-between gx-4 mt-1">
                                                                                <li>

                                                                                    <button
                                                                                        id="deleteSubmitBtn-{{ $user->id }}"
                                                                                        type="submit" class="btn fmdq_Gold">
                                                                                        <i class="fas fa-spinner fa-spin"
                                                                                            style="display:none;"></i>
                                                                                        <span class="btn-text">
                                                                                            Submit
                                                                                        </span></button>


                                                                                    <script>
                                                                                        document.getElementById('deleteSubmitBtn-{{ $user->id }}').addEventListener('click', function() {
                                                                                            loading('deleteSubmitBtn-{{ $user->id }}');
                                                                                            setTimeout(() => {
                                                                                                document.getElementById('deleteSubmitBtn-{{ $user->id }}').disabled = true;
                                                                                            }, 50);
                                                                                        });
                                                                                    </script>
                                                                                </li>

                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div><!-- .modal-dialog -->

                                                </div>

                                                <script>
                                                    function loading(buttonId) {
                                                        $("#" + buttonId + " .fa-spinner").show();
                                                        $("#" + buttonId + " .btn-text").html("Processing...");
                                                    }

                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        document.getElementById('modifyForm-{{ $user->id }}').addEventListener('submit', function(event) {
                                                            if (this.checkValidity() === false) {
                                                                event.preventDefault();
                                                                event.stopPropagation();
                                                            } else {
                                                                loading('ModifySubmitBtn-{{ $user->id }}');
                                                                document.getElementById('ModifySubmitBtn-{{ $user->id }}').disabled = true;
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
        <!-- @@ Lead Add Modal @e -->
        <div class="modal fade" role="dialog" id="addUser">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                    <div class="modal-body modal-body-md">
                        <h5 class="title">Add User</h5>
                        {!! Form::open(['route' => 'users.store', 'method' => 'POST', 'id' => 'userForm']) !!}
                        <div class="tab-content">
                            <div class="tab-pane active" id="infomation">
                                <div class="row gy-4">
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label class="form-label" for="lead-name">Name</label>
                                            <div class="form-control-wrap">

                                                <input name="name" type="text" class="form-control" id="lead-name"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Group</label>
                                            <div class="form-control-wrap">
                                                <select required name="group_id" class="form-select form-control"
                                                    data-placeholder="Select one">
                                                    <option value="">Select one</option>
                                                    @foreach ($groups as $group)
                                                        <option value="{{ $group->id }}">
                                                            {{ $group->name }}</option>
                                                    @endforeach


                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="open-deal">Email</label>
                                            <input required name="email" type="text" class="form-control"
                                                id="open-deal">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="open-deal">Role</label>
                                            {!! Form::select('roles[]', $roles, [], ['class' => 'form-control', 'multiple', 'required']) !!}
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


                                    <input type="hidden" value="0" name="status">
                                    <div class="col-12">
                                        <input hidden type="text" name="usertype" value="internal">
                                        <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                            <li>
                                                <button id="addSubmitBtn" type="submit" class="btn btn-primary"><i
                                                        class="fas fa-spinner fa-spin" style="display:none;"></i>
                                                    <span class="btn-text">Submit</span></button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div><!-- .tab-pane -->

                        </div><!-- .tab-content -->
                        {!! Form::close() !!}
                    </div><!-- .modal-body -->
                </div><!-- .modal-content -->
            </div><!-- .modal-dialog -->
        </div><!-- .modal -->





        @foreach ($data as $user)
            <div class="modal fade" role="dialog" id="editUser-{{ $user->id }}">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                        <div class="modal-body modal-body-md">
                            <h5 class="title">{{ $user->name }}</h5>


                            <form id="editForm-{{ $user->id }}" method="POST"
                                action="{{ route('userUpdate', $user->id) }}" enctype="multipart/form-data">
                                @csrf

                                <div class="tab-content">
                                    <div class="tab-pane active" id="infomation">
                                        <div class="row gy-4">
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label class="form-label" for="lead-name">Name</label>
                                                    <div class="form-control-wrap">

                                                        <input required value="{{ $user->name }}" name="name"
                                                            type="text" class="form-control" id="lead-name">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="open-deal">Email</label>
                                                    <input required name="email" value="{{ $user->email }}"
                                                        type="text" class="form-control" id="open-deal">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Group</label>
                                                    <div class="form-control-wrap">
                                                        <select required name="group_id" class="form-select form-control"
                                                            data-placeholder="Select one">
                                                            @foreach ($groups as $group)
                                                                <option value="{{ $group->id }}"
                                                                    {{ $user->group_id == $group->id ? 'selected' : '' }}>
                                                                    {{ $group->name }}
                                                                </option>
                                                            @endforeach


                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="open-deal">Role</label>
                                                    <?php
                                                    $id = $user->id;
                                                    
                                                    $user = \App\Models\User::find($id);
                                                    $roles = Spatie\Permission\Models\Role::pluck('name', 'name')->all();
                                                    $userRole = $user->roles->pluck('name', 'name')->all();
                                                    ?>
                                                    {!! Form::select('roles[]', $roles, $userRole, ['class' => 'form-control', 'multiple', 'required']) !!}
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
                                                        <button type="submit" id="editSubmitBtn-{{ $user->id }}"
                                                            class="btn btn-primary"> <i class="fas fa-spinner fa-spin"
                                                                style="display:none;"></i>
                                                            <span class="btn-text">Submit</span></button>


                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div><!-- .tab-pane -->

                                </div><!-- .tab-content -->
                                {!! Form::close() !!}
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
                    document.getElementById('editForm-{{ $user->id }}').addEventListener('submit', function(event) {
                        if (this.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        } else {
                            loading('editSubmitBtn-{{ $user->id }}');
                            document.getElementById('editSubmitBtn-{{ $user->id }}').disabled = true;
                        }
                        this.classList.add('was-validated');
                    }, false);
                });
            </script>
        @endforeach





        <!-- @@ organization lead Delete Modal @e -->
        @foreach ($data as $user)
            <div class="modal fade" id="deleteUser-{{ $user->id }}">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross"></em></a>
                        <div class="modal-body modal-body-sm text-center">
                            <div class="nk-modal py-4">
                                <em class="nk-modal-icon icon icon-circle icon-circle-xxl ni ni-cross bg-danger"></em>
                                <h4 class="nk-modal-title">Are You Sure ?</h4>
                                <div class="nk-modal-text mt-n2">
                                    <p class="text-soft">This will delete user details permanently.</p>
                                </div>
                                <ul class="d-flex justify-content-center gx-4 mt-4">
                                    <li>
                                        <form action="{{ route('deleteUser', $user->id) }}" enctype="multipart/form-data"
                                            method="POST">
                                            @csrf
                                            {{-- <button id="deleteSubmitBtn-{{ $user->id }}" type="submit"
                                                class="btn btn-success"> <i class="fas fa-spinner fa-spin"
                                                    style="display:none;"></i>
                                                <span class="btn-text">Yes, Delete it</span></button> --}}
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
            </div><!-- .modal -->

            {{-- <script>
                document.getElementById('deleteSubmitBtn-{{ $user->id }}').addEventListener('click', function() {
                    loading('deleteSubmitBtn-{{ $user->id }}');
                    setTimeout(() => {
                        document.getElementById('deleteSubmitBtn-{{ $user->id }}').disabled = true;
                    }, 50);
                });
            </script> --}}
        @endforeach

        <script>
            function loading(buttonId) {
                $("#" + buttonId + " .fa-spinner").show();
                $("#" + buttonId + " .btn-text").html("Processing...");
            }



            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('userForm').addEventListener('submit', function(event) {
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
