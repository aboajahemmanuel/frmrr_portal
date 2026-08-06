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
                                    <h3 class="nk-block-title page-title">Markets and Products</h3>
                                    <div class="nk-block-des text-soft">
                                        <p>Manage Markets and Products for the system</p>
                                    </div>
                                </div><!-- .nk-block-head-content -->
                                <div class="nk-block-head-content">
                                    <div class="toggle-wrap nk-block-tools-toggle">
                                        <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                            data-target="more-options"><em class="icon ni ni-more-v"></em></a>
                                        <div class="toggle-expand-content" data-content="more-options">
                                            <ul class="nk-block-tools g-3">
                                                @can('Market-Product-Tag-create')
                                                    <li class="nk-block-tools-opt">
                                                        <a href="#" class="btn btn-icon btn-primary d-md-none"><em
                                                                class="icon ni ni-plus"></em></a>
                                                        <a href="#" data-toggle="modal" data-target="#addTag"
                                                            class="btn btn-primary d-none d-md-inline-flex"><em
                                                                class="icon ni ni-plus"></em><span>Add Tag</span></a>
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
                                        <em class="icon ni ni-cross-circle"></em> <strong> {{ \Session::get('error') }}<button
                                                class="close" data-dismiss="alert"></button>
                                    </div>
                                @endif


                                @if (count($errors) > 0)
                                    <div>
                                        <div class="alert alert-danger alert-icon alert-dismissible">
                                            <strong>Oops!</strong> Something went wrong, please check below errors.<br><br>
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                            <button class="close" data-dismiss="alert"></button>
                                        </div>
                                @endif



                            </div>
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
                                                <th class="nk-tb-col tb-col-lg"><span class="sub-text">Description</span></th>
                                                <th class="nk-tb-col tb-col-lg"><span class="sub-text">Created At</span></th>

                                                <th class="nk-tb-col nk-tb-col-tools ">
                                                    Status
                                                </th>
                                                

                                                <th class="nk-tb-col nk-tb-col-tools ">
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $tag)
                                                <tr class="nk-tb-item">
                                                    <td class="nk-tb-col nk-tb-col-check">
                                                        {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                                    </td>
                                                    <td class="nk-tb-col">
                                                        <div class="user-card">
                                                            <div class="user-info">
                                                                <span class="tb-lead">{{ $tag->name }} <span
                                                                        class="dot dot-success d-md-none ml-1"></span></span>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="nk-tb-col tb-col-lg">
                                                        <span>{{ Str::limit($tag->description, 50) }}</span>
                                                    </td>

                                                    <td class="nk-tb-col tb-col-lg">
                                                        <span>
                                                            @php
                                                                $postdate = date_format($tag->created_at, 'F d,Y');
                                                                $timestamp = strtotime($postdate);
                                                                $newDateFormat = date('M. d, Y', $timestamp);
                                                                echo $newDateFormat;
                                                            @endphp
                                                        </span>
                                                    </td>

                                                    <td class="nk-tb-col tb-col-lg">
                                                        @if ($tag->admin_status == 0)
                                                            <span class="badge fmdq_Blue">Awaiting Approval</span>
                                                        @endif
                                                        @if ($tag->admin_status == 1)
                                                            <span class="badge badge-primary">Approved</span>
                                                        @endif
                                                        @if ($tag->admin_status == 2)
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @endif
                                                        @if ($tag->admin_status == 3)
                                                            <span class="badge badge-warning">Awaiting approval for delete</span>
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
                                                                            @if ($tag->admin_status != 3)
                                                                                @if ($tag->admin_status != 0)
                                                                                    @can('Market-Product-Tag-edit')
                                                                                        <li>
                                                                                            <a href="#" data-toggle="modal"
                                                                                                data-target="#editTag-{{ $tag->id }}">
                                                                                                <em class="icon ni ni-edit"></em><span>Edit</span>
                                                                                            </a>
                                                                                        </li>
                                                                                    @endcan

                                                                                    @can('Market-Product-Tag-delete')
                                                                                        <li><a href="#" data-toggle="modal"
                                                                                                data-target="#deleteTag-{{ $tag->id }}"><em
                                                                                                    class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                                        </li>
                                                                                    @endcan
                                                                                @endif
                                                                            @endif

                                                                            @if ($tag->admin_status == 0)
                                                                                @can('Market-Product-Tag-approve')
                                                                                    <li><a href="#" id="submit"
                                                                                            onclick="document.getElementById('approve-{{ $tag->id }}').submit();"><em
                                                                                                class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                                    </li>
                                                                                @endcan

                                                                                @can('Market-Product-Tag-delete')
                                                                                    <li><a href="#" data-toggle="modal"
                                                                                            data-target="#reject-{{ $tag->id }}"><em
                                                                                                class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                                    </li>
                                                                                @endcan
                                                                            @endif

                                                                            @if ($tag->admin_status == 3)
                                                                                @can('Market-Product-Tag-delete')
                                                                                    <li><a href="#" id="submit"
                                                                                            onclick="document.getElementById('approve-{{ $tag->id }}').submit();"><em
                                                                                                class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                                    </li>
                                                                                @endcan

                                                                                @can('Market-Product-Tag-delete')
                                                                                    <li><a href="#" data-toggle="modal"
                                                                                            data-target="#reject-{{ $tag->id }}"><em
                                                                                                class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                                    </li>
                                                                                @endcan
                                                                            @endif
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>

                                                        <form id="approve-{{ $tag->id }}"
                                                            action="{{ route('TagStatus', $tag->id) }}" method="POST"
                                                            class="d-none" style="display: none">
                                                            @csrf
                                                            <input name="status" value="1">
                                                        </form>

                                                        <!-- Reject Modal -->
                                                        <div class="modal fade" role="dialog" id="reject-{{ $tag->id }}">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                                <div class="modal-content">
                                                                    <a href="#" class="close" data-dismiss="modal"><em
                                                                            class="icon ni ni-cross-sm"></em></a>
                                                                    <div class="modal-body modal-body-md">
                                                                        <h5 class="title">Reject: {{ $tag->name }}</h5>
                                                                        <form method="POST"
                                                                            action="{{ route('TagStatus', $tag->id) }}">
                                                                            @csrf
                                                                            <div class="tab-content">
                                                                                <div class="tab-pane active">
                                                                                    <div class="row gy-4">
                                                                                        <div class="col-md-12">
                                                                                            <div class="form-group">
                                                                                                <label>Rejection Note</label>
                                                                                                <input hidden name="status" value="2">
                                                                                                <textarea required class="form-control" name="note"></textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-12">
                                                                                            <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                                                                                <li>
                                                                                                    <button type="submit" class="btn btn-primary">Reject</button>
                                                                                                </li>
                                                                                                <li>
                                                                                                    <a href="#" data-dismiss="modal" class="link link-light">Cancel</a>
                                                                                                </li>
                                                                                            </ul>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Edit Modal -->
                                                        <div class="modal fade" role="dialog" id="editTag-{{ $tag->id }}">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                                <div class="modal-content">
                                                                    <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                                                                    <div class="modal-body modal-body-md">
                                                                        <h5 class="title">Edit Tag</h5>
                                                                        <form method="POST" action="{{ route('tagUpdate', $tag->id) }}">
                                                                            @csrf
                                                                            <div class="tab-content">
                                                                                <div class="tab-pane active">
                                                                                    <div class="row gy-4">
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label class="form-label">Tag Name</label>
                                                                                                <input required type="text" name="name" class="form-control" value="{{ $tag->name }}">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label class="form-label" for="fw-token-address">Authoriser</label>
                                                                                                <select required name="authorizer_id" class="form-control">
                                                                                                    <option value="">Select Authoriser</option>
                                                                                                    @foreach ($authoriser as $auth)
                                                                                                        <option value="{{ $auth->id }}">{{ $auth->name }}</option>
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-12">
                                                                                            <div class="form-group">
                                                                                                <label class="form-label">Description</label>
                                                                                                <textarea class="form-control" name="description">{{ $tag->description }}</textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-12">
                                                                                            <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                                                                                <li>
                                                                                                    <button type="submit" class="btn btn-primary">Update Tag</button>
                                                                                                </li>
                                                                                                <li>
                                                                                                    <a href="#" data-dismiss="modal" class="link link-light">Cancel</a>
                                                                                                </li>
                                                                                            </ul>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Delete Modal -->
                                                        <div class="modal fade" role="dialog" id="deleteTag-{{ $tag->id }}">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                                <div class="modal-content">
                                                                    <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                                                                    <div class="modal-body modal-body-md">
                                                                        <h5 class="title">Delete Tag: {{ $tag->name }}</h5>
                                                                        <form method="POST" action="{{ route('deleteMarketProductTag', $tag->id) }}">
                                                                            @csrf
                                                                            <div class="tab-content">
                                                                                <div class="tab-pane active">
                                                                                    <div class="row gy-4">
                                                                                        <div class="col-md-12">
                                                                                            <p>Are you sure you want to delete this tag?</p>
                                                                                            <div class="form-group">
                                                                                                <label class="form-label">Authoriser</label>
                                                                                                <select required name="authorizer_id" class="form-control">
                                                                                                    <option value="">Select Authoriser</option>
                                                                                                    @foreach ($authoriser as $auth)
                                                                                                        <option value="{{ $auth->id }}">{{ $auth->name }}</option>
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-12">
                                                                                            <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                                                                                <li>
                                                                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                                                                </li>
                                                                                                <li>
                                                                                                    <a href="#" data-dismiss="modal" class="link link-light">Cancel</a>
                                                                                                </li>
                                                                                            </ul>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
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

        <!-- Add Tag Modal -->
        <div class="modal fade" role="dialog" id="addTag">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                    <div class="modal-body modal-body-md">
                        <h5 class="title">Add New Markets and Products</h5>
                        <form method="POST" action="{{ route('market-product-tags.store') }}">
                            @csrf
                            <div class="tab-content">
                                <div class="tab-pane active" id="personal">
                                    <div class="row gy-4">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="fw-first-name">Tag Name</label>
                                                <input required type="text" name="name" class="form-control" id="fw-first-name" placeholder="Enter tag name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="fw-token-address">Authoriser</label>
                                                <select required name="authorizer_id" class="form-control" id="full-name">
                                                    <option value="">Select Authoriser</option>
                                                    @foreach ($authoriser as $authorise)
                                                        <option value="{{ $authorise->id }}">{{ $authorise->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Description</label>
                                                <textarea class="form-control" name="description" placeholder="Enter tag description (optional)"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                                <li>
                                                    <button type="submit" class="btn btn-primary">Add Tag</button>
                                                </li>
                                                <li>
                                                    <a href="#" data-dismiss="modal" class="link link-light">Cancel</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection
