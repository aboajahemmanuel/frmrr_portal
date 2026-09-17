@extends('layouts.master')

@section('content')

    <!-- content @s -->
        <div class="nk-content ">
            <div class="container-fluid">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-block-head-sm">
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title page-title">Subscription Tiers</h3>
                                    <div class="nk-block-des text-soft">

                                    </div>
                                </div><!-- .nk-block-head-content -->
                                <div class="nk-block-head-content">
                                    <div class="toggle-wrap nk-block-tools-toggle">
                                        <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                            data-target="more-options"><em class="icon ni ni-more-v"></em></a>
                                        <div class="toggle-expand-content" data-content="more-options">
                                            <ul class="nk-block-tools g-3">
                                                @can('subscription-tier-create')
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
                                    </div>
                                @endif
                            </div>

                            <!-- Filter Section -->
                            <div class="card card-bordered mb-4">
                                <div class="card-inner">
                                    <form action="{{ url()->current() }}" method="GET">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">Name</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" name="name" class="form-control" value="{{ request('name') }}" placeholder="Search by name">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8 text-right align-self-end">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">Filter</button>
                                                    <a href="{{ url()->current() }}" class="btn btn-light">Reset</a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card card-preview">
                                <div class="card-inner">
                                    <table class="nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                        <thead>
                                            <tr class="nk-tb-item nk-tb-head">
                                                <th>#</th>
                                                <th class="nk-tb-col"><span class="sub-text">Name</span></th>
                                                <th class="nk-tb-col"><span class="sub-text">Description</span></th>
                                                <th class="nk-tb-col tb-col-lg"><span class="sub-text">Created At</span></th>
                                                <th class="nk-tb-col nk-tb-col-tools ">
                                                    Status
                                                </th>
                                                <th class="nk-tb-col nk-tb-col-tools "></th>
                                                <th class="nk-tb-col nk-tb-col-tools "></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $tier)
                                                <tr class="nk-tb-item">
                                                    <td class="nk-tb-col nk-tb-col-check">
                                                        {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                                    </td>
                                                    <td class="nk-tb-col">
                                                        <div class="user-card">
                                                            <div class="user-info">
                                                                <span class="tb-lead">{{ $tier->name }} <span
                                                                        class="dot dot-success d-md-none ml-1"></span></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="nk-tb-col">
                                                        <span class="tb-lead">{{ $tier->description }}</span>
                                                    </td>
                                                    <td class="nk-tb-col tb-col-lg">
                                                        <span>
                                                            @php
                                                                $postdate = date_format($tier->created_at, 'F d,Y');
                                                            @endphp
                                                            <?php
                                                            $timestamp = strtotime($postdate);
                                                            $newDateFormat = date('M. d, Y', $timestamp);
                                                            echo $newDateFormat;
                                                            ?>
                                                        </span>
                                                    </td>
                                                    <td class="nk-tb-col tb-col-lg">
                                                        @if ($tier->admin_status == 0)
                                                            <span class="badge fmdq_Blue">Awaiting Approval</span>
                                                        @endif
                                                        @if ($tier->admin_status == 1)
                                                            <span class="badge badge-primary">Approved</span>
                                                        @endif
                                                        @if ($tier->admin_status == 2)
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @endif
                                                        @if ($tier->admin_status == 3)
                                                            <span class="badge badge-warning">Awaiting approval for
                                                                delete</span>
                                                        @endif
                                                    </td>
                                                    <td class="nk-tb-col tb-col-lg">
                                                        @if ($tier->admin_status == 2)
                                                            {{ $tier->note }}
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
                                                                            @if ($tier->admin_status != 3)
                                                                                @if ($tier->admin_status != 0)
                                                                                    @can('subscription-tier-edit')
                                                                                        <li>
                                                                                            <a href="#" data-toggle="modal"
                                                                                                data-target="#editGroup-{{ $tier->id }}">
                                                                                                <em
                                                                                                    class="icon ni ni-edit"></em><span>Edit</span>
                                                                                            </a>
                                                                                        </li>
                                                                                    @endcan
                                                                                    @can('subscription-tier-create')
                                                                                        <li>
                                                                                            <a href="#" data-toggle="modal"
                                                                                                data-target="#duplicate-{{ $tier->id }}">
                                                                                                <em
                                                                                                    class="icon ni ni-copy"></em><span>Duplicate</span>
                                                                                            </a>
                                                                                        </li>
                                                                                    @endcan
                                                                                    @can('subscription-tier-delete')
                                                                                        <li><a href="#" data-toggle="modal"
                                                                                                data-target="#deleteGroup-{{ $tier->id }}"><em
                                                                                                    class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                                        </li>
                                                                                    @endcan
                                                                                @endif
                                                                            @endif
                                                                            @if ($tier->admin_status == 0)
                                                                                @can('subscription-tier-approve')
                                                                                    <li><a href="#" id="submit"
                                                                                            onclick="document.getElementById('approve-{{ $tier->id }}').submit();"><em
                                                                                                class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                                    </li>
                                                                                @endcan
                                                                                @can('subscription-tier-reject')
                                                                                    <li><a href="#" data-toggle="modal"
                                                                                            data-target="#reject-{{ $tier->id }}"><em
                                                                                                class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                                    </li>
                                                                                @endcan
                                                                            @endif
                                                                            @if ($tier->admin_status == 3)
                                                                                @can('subscription-tier-approve')
                                                                                    <li><a href="#" id="submit"
                                                                                            onclick="document.getElementById('approve-{{ $tier->id }}').submit();"><em
                                                                                                class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                                    </li>
                                                                                @endcan
                                                                                @can('subscription-tier-reject')
                                                                                    <li><a href="#" data-toggle="modal"
                                                                                            data-target="#reject-{{ $tier->id }}"><em
                                                                                                class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                                    </li>
                                                                                @endcan
                                                                            @endif
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <form id="approve-{{ $tier->id }}"
                                                            action="{{ route('subscriptionTierStatus', $tier->id) }}" method="POST"
                                                            class="d-none" style="display: none">
                                                            @csrf
                                                            <input name="status" value="1">
                                                        </form>
                                                        <div class="modal fade" role="dialog"
                                                            id="reject-{{ $tier->id }}">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg"
                                                                role="document">
                                                                <div class="modal-content">
                                                                    <a href="#" class="close" data-dismiss="modal"><em
                                                                            class="icon ni ni-cross-sm"></em></a>
                                                                    <div class="modal-body modal-body-md">
                                                                        <h5 class="title">{{ $tier->name }}</h5>
                                                                        <form method="POST"
                                                                            action="{{ route('subscriptionTierStatus', $tier->id) }}"
                                                                            id="rejectForm-{{ $tier->id }}">
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
                                                                                                        id="rejectSubmitBtn-{{ $tier->id }}"
                                                                                                        type="submit">
                                                                                                        <i class="fas fa-spinner fa-spin"
                                                                                                            style="display:none;"></i>
                                                                                                        <span
                                                                                                            class="btn-text">Submit</span>
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
                                                        <div class="modal fade" id="duplicate-{{ $tier->id }}">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross"></em></a>
                                                                    <div class="modal-body modal-body-sm text-center">
                                                                        <form method="POST" action="{{ route('duplicateSubscriptionTier', $tier->id) }}">
                                                                            @csrf
                                                                            <div class="nk-modal py-4">
                                                                                <h4 class="nk-modal-title">Duplicate "{{ $tier->name }}"?</h4>
                                                                                <div class="nk-modal-text mt-n2">
                                                                                    <p class="text-soft">A copy will be created as a new pending tier awaiting approval.</p>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label class="form-label">Select Authoriser <span style="color: red;">*</span></label>
                                                                                        <div class="form-control-wrap">
                                                                                            <select required name="authorizer_id" class="form-select form-control">
                                                                                                <option value="">---</option>
                                                                                                @foreach ($authoriser as $auth)
                                                                                                    <option value="{{ $auth->id }}">{{ $auth->name }}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <ul class="d-flex justify-content-center gx-4 mt-4">
                                                                                    <li>
                                                                                        <button type="submit" class="btn btn-success">Yes, Duplicate</button>
                                                                                    </li>
                                                                                    <li>
                                                                                        <button data-dismiss="modal" class="btn btn-danger btn-dim">Cancel</button>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr><!-- .nk-tb-item  -->
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-inner">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-muted small">
                                            Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }} entries
                                        </div>
                                        @if ($data->hasPages())
                                            <div>
                                                {{ $data->appends(request()->input())->links('vendor.pagination.bootstrap-4') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div><!-- .card-preview -->
                        </div><!-- .nk-block -->
                    </div>
                </div>
            </div>
        </div>
        <!-- content @e -->
        <!-- @@ Add Modal @e -->
        <div class="modal fade" role="dialog" id="addUser">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                    <div class="modal-body modal-body-md">
                        <h5 class="title">Add Subscription Tier</h5>
                        <form id="tierForm" method="POST" action="{{ route('subscription-tiers.store') }}">
                            @csrf
                            <div class="tab-content">
                                <div class="tab-pane active" id="infomation">
                                    <div class="row gy-4">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label" for="lead-name">Name <span style="color: red">*</span></label>
                                                <div class="form-control-wrap">
                                                    <input required name="name" type="text" class="form-control" id="lead-name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label" for="lead-desc">Description</label>
                                                <div class="form-control-wrap">
                                                    <textarea class="form-control" name="description" id="lead-desc"></textarea>
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

        @foreach ($data as $tier)
            <div class="modal fade" role="dialog" id="editGroup-{{ $tier->id }}">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                        <div class="modal-body modal-body-md">
                            <h5 class="title">{{ $tier->name }}</h5>
                            <form id="editForm-{{ $tier->id }}" method="POST"
                                action="{{ route('subscriptionTierUpdate', $tier->id) }}">
                                @csrf
                                <div class="tab-content">
                                    <div class="tab-pane active" id="infomation">
                                        <div class="row gy-4">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Name</label>
                                                    <div class="form-control-wrap">
                                                        <input required value="{{ $tier->name }}" name="name" type="text"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Description</label>
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" name="description">{{ $tier->description }}</textarea>
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
                                                            id="editSubmitBtn-{{ $tier->id }}" type="submit">
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
        @endforeach

        <!-- @@  Delete Modal @e -->
        @foreach ($data as $tier)
            <div class="modal fade" id="deleteGroup-{{ $tier->id }}">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross"></em></a>
                        <div class="modal-body modal-body-sm text-center">
                            <form method="POST" action="{{ route('deleteSubscriptionTier', $tier->id) }}">
                                @csrf
                                <div class="nk-modal py-4">
                                    <em class="nk-modal-icon icon icon-circle icon-circle-xxl ni ni-cross bg-danger"></em>
                                    <h4 class="nk-modal-title">Are You Sure ?</h4>
                                    <div class="nk-modal-text mt-n2">
                                        <p class="text-soft">This will delete the subscription tier permanently, pending approval.</p>
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
                                            <button type="submit" class="btn btn-success">Yes, Delete it</button>
                                        </li>
                                        <li>
                                            <button data-dismiss="modal" class="btn btn-danger btn-dim">Cancel</button>
                                        </li>
                                    </ul>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <script>
            function loading(buttonId) {
                $("#" + buttonId + " .fa-spinner").show();
                $("#" + buttonId + " .btn-text").html("Processing...");
            }

            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('tierForm').addEventListener('submit', function(event) {
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
