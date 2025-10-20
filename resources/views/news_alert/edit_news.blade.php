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

                                        <form method="POST" action="{{ route('updateNews', $news->id) }}" id="entityForm">
                                            @csrf
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="infomation">
                                                    <div class="row gy-4">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name">Title</label>
                                                                <div class="form-control-wrap">
                                                                    <input name="title" type="text"
                                                                        value="{{ $news->title }}" class="form-control"
                                                                        id="lead-name">
                                                                </div>
                                                            </div>



                                                        </div>



                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name">News Content</label>
                                                                <div class="form-control-wrap">
                                                                    <textarea name="news_content" type="text" class="summernote-minimal">{{ $news->news_content }} </textarea>
                                                                </div>
                                                            </div>



                                                        </div>


                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label" for="add-account">Select Authoriser
                                                                    <span style="color: red;">*</span></label>
                                                                <div class="form-control-wrap">

                                                                    <select required name="authorizer_id"
                                                                        class="form-select form-control"
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
                                                                        id="addSubmitBtn" type="submit">
                                                                        <i class="fas fa-spinner fa-spin"
                                                                            style="display:none;"></i>
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
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('entityForm').addEventListener('submit', function(event) {
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
