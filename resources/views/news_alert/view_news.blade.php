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
                                    <h3 class="nk-block-title page-title">{{ $news->title }}</h3>
                                    <div class="nk-block-des text-soft">

                                    </div>
                                </div><!-- .nk-block-head-content -->
                                <div class="nk-block-head-content">
                                    <div class="toggle-wrap nk-block-tools-toggle">
                                        <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                            data-target="more-options"><em class="icon ni ni-more-v"></em></a>
                                        <div class="toggle-expand-content" data-content="more-options">

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

                                    <div class="modal-body modal-body-md">


                                        <div class="tab-content">
                                            <div class="tab-pane active" id="infomation">
                                                <div class="row gy-4">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label" for="lead-name">Title</label>
                                                            <div class="form-control-wrap">
                                                                <input name="title" type="text" value="{{ $news->title }}"
                                                                    disabled class="form-control" id="lead-name">
                                                            </div>
                                                        </div>



                                                    </div>



                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label" for="lead-name">News Content</label>
                                                            <div class="form-control-wrap">
                                                                {!! $news->news_content !!}
                                                                {{-- <textarea name="news_content" type="readonly" class="summernote-minimal"> </textarea> --}}
                                                            </div>
                                                        </div>



                                                    </div>



                                                    @if ($news->status == 0 || $news->status == 3)
                                                        <div class="col-12">
                                                            <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                                                <li class="d-inline-flex">
                                                                    @can('news-approve')
                                                                        <button
                                                                            onclick="document.getElementById('approve-{{ $news->id }}').submit();"
                                                                            class="btn btn-lg btn-primary custom-space"
                                                                            id="deleteSubmitBtn-{{ $news->id }}" type="submit">
                                                                            <i class="fas fa-spinner fa-spin"
                                                                                style="display:none;"></i>
                                                                            <span class="btn-text">Approve</span>
                                                                        </button>
                                                                    @endcan


                                                                    @can('news-reject')
                                                                        <button class="btn btn-lg btn-primary" data-toggle="modal"
                                                                            data-target="#reject-{{ $news->id }}">

                                                                            Reject
                                                                        </button>
                                                                    @endcan
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    @endif

                                                    <style>
                                                        .custom-space {
                                                            margin-right: 10px;
                                                            /* Adjust this value as needed */
                                                        }
                                                    </style>




                                                </div>
                                            </div><!-- .tab-pane -->

                                        </div><!-- .tab-content -->


                                        <form id="approve-{{ $news->id }}" action="{{ route('news_status', $news->id) }}"
                                            method="POST" class="d-none" style="display: none">
                                            @csrf
                                            <input name="status" value="1">
                                        </form>


                                    </div><!-- .modal-body -->
                                </div>
                            </div><!-- .card-preview -->
                        </div><!-- .nk-block -->
                    </div>
                </div>
            </div>
        </div>
        <!-- content @e -->
        <!-- @@ Group Add Modal @e -->


        <script>
            document.getElementById('deleteSubmitBtn-{{ $news->id }}').addEventListener('click', function() {
                loading('deleteSubmitBtn-{{ $news->id }}');
                setTimeout(() => {
                    document.getElementById('deleteSubmitBtn-{{ $news->id }}').disabled = true;
                }, 50);
            });
        </script>


        <div class="modal fade" role="dialog" id="reject-{{ $news->id }}">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                    <div class="modal-body modal-body-md">
                        <h5 class="title">{{ $news->name }}</h5>
                        <form method="POST" action="{{ route('news_status', $news->id) }}"
                            id="rejectForm-{{ $news->id }}">
                            @csrf
                            <div class="tab-content">
                                <div class="tab-pane active" id="infomation">
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
                                                    <button class="btn btn-lg btn-primary btn-block"
                                                        id="rejectSubmitBtn-{{ $news->id }}" type="submit">
                                                        <i class="fas fa-spinner fa-spin" style="display:none;"></i>
                                                        <span class="btn-text">Submit</span>
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









    @endsection
