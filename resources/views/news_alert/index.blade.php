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
                                    <h3 class="nk-block-title page-title">News Alert</h3>
                                    <div class="nk-block-des text-soft">

                                    </div>
                                </div><!-- .nk-block-head-content -->
                                <div class="nk-block-head-content">
                                    <div class="toggle-wrap nk-block-tools-toggle">
                                        <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                            data-target="more-options"><em class="icon ni ni-more-v"></em></a>
                                        <div class="toggle-expand-content" data-content="more-options">
                                            <ul class="nk-block-tools g-3">

                                                @can('post-create')
                                                    <li class="nk-block-tools-opt">
                                                        <a href="#" class="btn btn-icon btn-primary d-md-none"><em
                                                                class="icon ni ni-plus"></em></a>
                                                        <a href="{{ route('add_news') }}"
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

                                    <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                        <thead>
                                            <tr class="nk-tb-item nk-tb-head">
                                                <th>#</th>
                                                <th class="nk-tb-col"><span class="sub-text">Title</span></th>
                                                {{-- <th class="nk-tb-col"><span class="sub-text">news</span></th> --}}
                                                <th class="nk-tb-col tb-col-lg"><span class="sub-text">Created At</span></th>

                                                <th class="nk-tb-col nk-tb-col-tools text-right">
                                                </th>
                                                <th class="nk-tb-col nk-tb-col-tools text-right">
                                                </th>
                                                <th class="nk-tb-col nk-tb-col-tools text-right">
                                                </th>
                                            </tr>
                                        </thead>


                                        <tbody>
                                            @foreach ($data as $news)
                                                <tr class="nk-tb-item">
                                                    <td class="nk-tb-col nk-tb-col-check">
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td class="nk-tb-col">
                                                        <div class="user-card">

                                                            <div class="user-info">
                                                                <span class="tb-lead">{{ $news->title }} <span
                                                                        class="dot dot-success d-md-none ml-1"></span></span>

                                                            </div>
                                                        </div>
                                                    </td>


                                                    <td class="nk-tb-col tb-col-lg">
                                                        <span>
                                                            @php
                                                                $postdate = date_format($news->created_at, 'F d,Y');

                                                            @endphp

                                                            <?php
                                                            
                                                            $timestamp = strtotime($postdate);
                                                            $newDateFormat = date('M. d, Y', $timestamp);
                                                            echo $newDateFormat;
                                                            
                                                            ?>


                                                        </span>
                                                    </td>

                                                    <td class="nk-tb-col tb-col-lg">
                                                        @if ($news->admin_status == 0)
                                                            <span class="badge fmdq_Blue">Awaiting Approval<span>
                                                        @endif
                                                        @if ($news->admin_status == 1)
                                                            <span class="badge badge-primary">Approved</span>
                                                        @endif
                                                        @if ($news->admin_status == 2)
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @endif


                                                        @if ($news->admin_status == 3)
                                                            <span class="badge badge-warning">Awaiting approval for
                                                                delete</span>
                                                        @endif

                                                    </td>


                                                    <td class="nk-tb-col tb-col-lg">

                                                        @if ($news->admin_status == 2)
                                                            {{ $news->note }}
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
                                                                            <li>
                                                                                <a href="{{ route('view_news', $news->id) }}">
                                                                                    <em
                                                                                        class="icon ni ni-edit"></em><span>view</span>
                                                                                </a>
                                                                            </li>
                                                                            @if ($news->admin_status != 3)
                                                                                @if ($news->admin_status != 0)
                                                                                    @can('news-edit')
                                                                                        <li>
                                                                                            <a
                                                                                                href="{{ route('edit_news', $news->id) }}">
                                                                                                <em
                                                                                                    class="icon ni ni-edit"></em><span>Edit</span>
                                                                                            </a>
                                                                                        </li>
                                                                                    @endcan




                                                                                    @can('news-delete')
                                                                                        <li><a href="#" data-toggle="modal"
                                                                                                data-target="#deleteNews-{{ $news->id }}"><em
                                                                                                    class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                                        </li>
                                                                                    @endcan
                                                                                @endif
                                                                            @endif



                                                                            @if ($news->admin_status == 0)
                                                                                @can('news-approve')
                                                                                    <li><a href="#" id="submit"
                                                                                            onclick="document.getElementById('approve-{{ $news->id }}').submit();"><em
                                                                                                class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                                    </li>
                                                                                @endcan


                                                                                @can('news-reject')
                                                                                    <li><a href="#" data-toggle="modal"
                                                                                            data-target="#reject-{{ $news->id }}"><em
                                                                                                class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                                    </li>
                                                                                @endcan


                                                                                <li><a href="#" data-toggle="modal"
                                                                                        data-target="#viewUser-{{ $news->id }}"><em
                                                                                            class="icon ni ni-cross-circle-fill"></em><span>View
                                                                                            Changed</span></a>
                                                                                </li>
                                                                            @endif



                                                                            @if ($news->admin_status == 3)
                                                                                @can('news-approve')
                                                                                    <li><a href="#" id="submit"
                                                                                            onclick="document.getElementById('approve-{{ $news->id }}').submit();"><em
                                                                                                class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                                    </li>
                                                                                @endcan

                                                                                @can('news-reject')
                                                                                    <li><a href="#" data-toggle="modal"
                                                                                            data-target="#reject-{{ $news->id }}"><em
                                                                                                class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                                    </li>
                                                                                @endcan
                                                                                <form id="delete_request-{{ $news->id }}"
                                                                                    action="{{ route('news_status', $news->id) }}"
                                                                                    method="POST" class="d-none"
                                                                                    style="display: none">
                                                                                    @csrf
                                                                                    <input name="status"
                                                                                        value="{{ $news->admin_status }}">
                                                                                </form>
                                                                            @endif

                                                                        </ul>

                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <form id="approve-{{ $news->id }}"
                                                            action="{{ route('news_status', $news->id) }}" method="POST"
                                                            class="d-none" style="display: none">
                                                            @csrf
                                                            <input name="status" value="1">
                                                        </form>


                                                        <div class="modal fade" role="dialog"
                                                            id="reject-{{ $news->id }}">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg"
                                                                role="document">
                                                                <div class="modal-content">
                                                                    <a href="#" class="close" data-dismiss="modal"><em
                                                                            class="icon ni ni-cross-sm"></em></a>
                                                                    <div class="modal-body modal-body-md">
                                                                        <h5 class="title">{{ $news->name }}</h5>
                                                                        <form method="POST"
                                                                            action="{{ route('news_status', $news->id) }}"
                                                                            id="rejectForm-{{ $news->id }}">
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
                                                                                                        id="rejectSubmitBtn-{{ $news->id }}"
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
                                                                                                            document.getElementById('rejectForm-{{ $news->id }}').addEventListener('submit', function(event) {
                                                                                                                if (this.checkValidity() === false) {
                                                                                                                    event.preventDefault();
                                                                                                                    event.stopPropagation();
                                                                                                                } else {
                                                                                                                    loading('rejectSubmitBtn-{{ $news->id }}');
                                                                                                                    document.getElementById('rejectSubmitBtn-{{ $news->id }}').disabled = true;
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
                                                        </div>
                                                    </td>
                                                </tr><!-- .nk-tb-item  -->
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- .card-preview -->
                        </div><!-- .nk-block -->
                    </div>
                </div>
            </div>
        </div>
        <!-- content @e -->
        <!-- @@ Group Add Modal @e -->









        <!-- @@  Delete Modal @e -->
        @foreach ($data as $news)
            <div class="modal fade" id="deleteNews-{{ $news->id }}">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross"></em></a>
                        <div class="modal-body modal-body-sm text-center">
                            <form method="POST" action="{{ route('deleteNews', $news->id) }}">
                                @csrf
                                <div class="nk-modal py-4">
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

                                            <button id="deleteSubmitBtn-{{ $news->id }}" type="submit" id="deleteOrg"
                                                class="btn btn-success">Yes, Delete
                                                it</button>
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
                document.getElementById('deleteSubmitBtn-{{ $news->id }}').addEventListener('click', function() {
                    loading('deleteSubmitBtn-{{ $news->id }}');
                    setTimeout(() => {
                        document.getElementById('deleteSubmitBtn-{{ $news->id }}').disabled = true;
                    }, 50);
                });
            </script>
        @endforeach
        <!-- .modal -->







        @foreach ($data as $news)
            @php
                $news_pending = \App\Models\NewsPending::where('news_id', $news->id)
                    // ->where('action_type', '=', 'Edit')
                    ->latest()
                    ->first();
            @endphp

            <div class="modal fade" id="viewUser-{{ $news->id }}" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <a href="#" class="close" data-dismiss="modal">
                            <em class="icon ni ni-cross-sm"></em>
                        </a>
                        <div class="modal-body modal-body-md">
                            <h5 class="title">View Changes</h5>

                            <form id="editForm-{{ $news->id }}" method="POST"
                                action="{{ route('userUpdate', $news->id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="tab-content">
                                    <div class="tab-pane active" id="infomation">
                                        <div class="row gy-4">
                                            <!-- Name Field -->
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="lead-name" class="form-label">title</label>
                                                    <input disabled type="text" class="form-control" id="lead-name"
                                                        name="name" value="{{ optional($news_pending)->title ?? 'N/A' }}"
                                                        required />
                                                </div>
                                            </div>




                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="lead-name" class="form-label">News Content</label>
                                                    <textarea disabled type="text" class="form-control">
                                                        {{ optional($news_pending)->news_content ?? 'N/A' }}
                                                    </textarea>
                                                </div>
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




    @endsection
\