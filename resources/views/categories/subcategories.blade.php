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
                                    <h3 class="nk-block-title page-title">Sub Categories</h3>
                                    <div class="nk-block-des text-soft">

                                    </div>
                                </div><!-- .nk-block-head-content -->
                                <div class="nk-block-head-content">
                                    <div class="toggle-wrap nk-block-tools-toggle">
                                        <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                            data-target="more-options"><em class="icon ni ni-more-v"></em></a>
                                        <div class="toggle-expand-content" data-content="more-options">
                                            <ul class="nk-block-tools g-3">

                                                @can('category-create')
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
                                                <th class="nk-tb-col"><span class="sub-text">Sub Category</span></th>
                                                <th class="nk-tb-col"><span class="sub-text">Category</span></th>
                                                <th class="nk-tb-col tb-col-lg"><span class="sub-text">Created At</span></th>

                                                <th class="nk-tb-col nk-tb-col-tools ">
                                                    Status
                                                </th>

                                                <th class="nk-tb-col nk-tb-col-tools text-right">
                                                </th>

                                                <th class="nk-tb-col nk-tb-col-tools text-right">
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $category)
                                                <tr class="nk-tb-item">
                                                    <td class="nk-tb-col nk-tb-col-check">
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td class="nk-tb-col">
                                                        <div class="user-card">

                                                            <div class="user-info">
                                                                <span class="tb-lead">{{ $category->name }} <span
                                                                        class="dot dot-success d-md-none ml-1"></span></span>

                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="nk-tb-col">
                                                        <div class="user-card">

                                                            <div class="user-info">
                                                                <span class="tb-lead"> {{ optional($category->category)->name }}

                                                                    <span class="dot dot-success d-md-none ml-1"></span></span>

                                                            </div>
                                                        </div>
                                                    </td>




                                                    <td class="nk-tb-col tb-col-lg">
                                                        <span> @php
                                                            $postdate = date_format($category->created_at, 'F d,Y');

                                                        @endphp

                                                            <?php
                                                            
                                                            $timestamp = strtotime($postdate);
                                                            $newDateFormat = date('M. d, Y', $timestamp);
                                                            echo $newDateFormat;
                                                            
                                                            ?></span>
                                                    </td>

                                                    <td class="nk-tb-col tb-col-lg">
                                                        @if ($category->admin_status == 0)
                                                            <span class="badge fmdq_Blue">Awaiting Approval<span>
                                                        @endif
                                                        @if ($category->admin_status == 1)
                                                            <span class="badge badge-primary">Approved</span>
                                                        @endif
                                                        @if ($category->admin_status == 2)
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @endif


                                                        @if ($category->admin_status == 3)
                                                            <span class="badge badge-warning">Awaiting approval for
                                                                delete</span>
                                                        @endif

                                                    </td>


                                                    <td class="nk-tb-col tb-col-lg">

                                                        @if ($category->admin_status == 2)
                                                            {{ $category->note }}
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
                                                                            @if ($category->admin_status != 3)
                                                                                @if ($category->admin_status != 0)
                                                                                    @can('category-edit')
                                                                                        <li>
                                                                                            <a href="#" data-toggle="modal"
                                                                                                data-target="#editGroup-{{ $category->id }}">
                                                                                                <em
                                                                                                    class="icon ni ni-edit"></em><span>Edit</span>
                                                                                            </a>
                                                                                        </li>
                                                                                    @endcan




                                                                                    @can('category-delete')
                                                                                        <li><a href="#" data-toggle="modal"
                                                                                                data-target="#deleteGroup-{{ $category->id }}"><em
                                                                                                    class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                                        </li>
                                                                                    @endcan
                                                                                @endif
                                                                            @endif


                                                                            @php
                                                                                $user = Auth::user()->group_id;

                                                                            @endphp
                                                                            @if ($category->admin_status == 0)
                                                                                @if ($category->group_id == $user)
                                                                                    @can('category-approve')
                                                                                        <li><a href="#" id="submit"
                                                                                                onclick="document.getElementById('approve-{{ $category->id }}').submit();"><em
                                                                                                    class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                                        </li>
                                                                                    @endcan


                                                                                    @can('category-reject')
                                                                                        <li><a href="#" data-toggle="modal"
                                                                                                data-target="#reject-{{ $category->id }}"><em
                                                                                                    class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                                        </li>
                                                                                    @endcan
                                                                                @endif
                                                                            @endif



                                                                            @if ($category->admin_status == 3)
                                                                               
                                                                                    @can('category-approve')
                                                                                        <li><a href="#" id="submit"
                                                                                                onclick="document.getElementById('approve-{{ $category->id }}').submit();"><em
                                                                                                    class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                                        </li>
                                                                                    @endcan

                                                                                    @can('category-reject')
                                                                                        <li><a href="#" data-toggle="modal"
                                                                                                data-target="#reject-{{ $category->id }}"><em
                                                                                                    class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                                        </li>
                                                                                    @endcan
                                                                                    <form
                                                                                        id="delete_request-{{ $category->id }}"
                                                                                        action="{{ route('subCatestatus', $category->id) }}"
                                                                                        method="POST" class="d-none"
                                                                                        style="display: none">
                                                                                        @csrf
                                                                                        <input name="status"
                                                                                            value="{{ $category->admin_status }}">
                                                                                    </form>
                                                                                
                                                                            @endif

                                                                        </ul>

                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <form id="approve-{{ $category->id }}"
                                                            action="{{ route('subCatestatus', $category->id) }}"
                                                            method="POST" class="d-none" style="display: none">
                                                            @csrf
                                                            <input name="status" value="1">
                                                        </form>


                                                        <div class="modal fade" role="dialog"
                                                            id="reject-{{ $category->id }}">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg"
                                                                role="document">
                                                                <div class="modal-content">
                                                                    <a href="#" class="close" data-dismiss="modal"><em
                                                                            class="icon ni ni-cross-sm"></em></a>
                                                                    <div class="modal-body modal-body-md">
                                                                        <h5 class="title">{{ $category->name }}</h5>
                                                                        <form method="POST"
                                                                            action="{{ route('subCatestatus', $category->id) }}"
                                                                            id="rejectForm-{{ $category->id }}">
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
                                                                                                        id="rejectSubmitBtn-{{ $category->id }}"
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
                                                                                                            document.getElementById('rejectForm-{{ $category->id }}').addEventListener('submit', function(event) {
                                                                                                                if (this.checkValidity() === false) {
                                                                                                                    event.preventDefault();
                                                                                                                    event.stopPropagation();
                                                                                                                } else {
                                                                                                                    loading('rejectSubmitBtn-{{ $category->id }}');
                                                                                                                    document.getElementById('rejectSubmitBtn-{{ $category->id }}').disabled = true;
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

        <!-- Add Sub Category Modal -->
        <div class="modal fade" role="dialog" id="addUser">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                    <div class="modal-body modal-body-md">
                        <h5 class="title">Add Sub Category</h5>
                        <form id="subcategoryForm" method="POST" action="{{ route('subcategories.store') }}">
                            @csrf
                            <div class="tab-content">
                                <div class="tab-pane active" id="infomation">
                                    <div class="row gy-4">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label" for="lead-name">Name <span
                                                        style="color: red;">*</span></label>
                                                <div class="form-control-wrap">
                                                    <input required name="name" type="text" class="form-control"
                                                        id="lead-name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label" for="lead-category">Category <span
                                                        style="color: red;">*</span></label>
                                                <div class="form-control-wrap">
                                                    <select required name="category_id" class="form-select form-control"
                                                        data-placeholder="Select one">
                                                        <option value="">---</option>
                                                        @foreach ($categories as $cate)
                                                            <option value="{{ $cate->id }}">
                                                                {{ $cate->name }}</option>
                                                        @endforeach
                                                    </select>
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

        <!-- Edit Sub Category Modals -->
        @foreach ($data as $category)
            <div class="modal fade" role="dialog" id="editGroup-{{ $category->id }}">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                        <div class="modal-body modal-body-md">
                            <h5 class="title">Edit Sub Category</h5>

                            {!! Form::model($category, [
                                'route' => ['subcategories.update', $category->id],
                                'method' => 'PATCH',
                                'id' => 'editForm-' . $category->id,
                            ]) !!}
                            <div class="tab-content">
                                <div class="tab-pane active" id="infomation">
                                    <div class="row gy-4">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label" for="lead-name">Name <span
                                                        style="color: red;">*</span></label>
                                                <div class="form-control-wrap">
                                                    <input required value="{{ $category->name }}" name="name"
                                                        type="text" class="form-control"
                                                        id="lead-name-{{ $category->id }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="lead-category-{{ $category->id }}">Category<span
                                                        style="color: red;">*</span></label>
                                                <div class="form-control-wrap">
                                                    <select required class="form-select" name="category_id" required>
                                                        @foreach ($categories as $cate)
                                                            <option value="{{ $cate->id }}"
                                                                @if ($cate->id == $category->category_id) selected @endif>
                                                                {{ $cate->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
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
                                                        id="editSubmitBtn-{{ $category->id }}" type="submit">
                                                        <i class="fas fa-spinner fa-spin" style="display:none;"></i>
                                                        <span class="btn-text">Update</span>
                                                    </button>
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
                    document.getElementById('editForm-{{ $category->id }}').addEventListener('submit', function(event) {
                        if (this.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        } else {
                            loading('editSubmitBtn-{{ $category->id }}');
                            document.getElementById('editSubmitBtn-{{ $category->id }}').disabled = true;
                        }
                        this.classList.add('was-validated');
                    }, false);
                });
            </script>
        @endforeach

        <!-- Delete Sub Category Modals -->
        @foreach ($data as $category)
            <div class="modal fade" id="deleteGroup-{{ $category->id }}">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross"></em></a>
                        <div class="modal-body modal-body-sm text-center">
                            <div class="nk-modal py-4">
                                <em class="nk-modal-icon icon icon-circle icon-circle-xxl ni ni-cross bg-danger"></em>
                                <h4 class="nk-modal-title">Are You Sure ?</h4>
                                <div class="nk-modal-text mt-n2">
                                    <p class="text-soft">This Lead data will be delete permanently.</p>
                                </div>

                                <form method="POST" action="{{ route('deletesubcategory', $category->id) }}">
                                    @csrf
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
                                                id="deleteSubmitBtn-{{ $category->id }}" type="submit">
                                                <i class="fas fa-spinner fa-spin" style="display:none;"></i>
                                                <span class="btn-text">Yes, Delete it</span>
                                            </button>
                                </form>
                                </li>
                                <li>
                                    <button data-dismiss="modal" class="btn btn-danger btn-dim">Cancel</button>
                                </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.getElementById('deleteSubmitBtn-{{ $category->id }}').addEventListener('click', function() {
                    loading('deleteSubmitBtn-{{ $category->id }}');
                    setTimeout(() => {
                        document.getElementById('deleteSubmitBtn-{{ $category->id }}').disabled = true;
                    }, 50);
                });
            </script>
        @endforeach

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('subcategoryForm').addEventListener('submit', function(event) {
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
