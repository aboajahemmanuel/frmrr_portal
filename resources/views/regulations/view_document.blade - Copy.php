@extends('layouts.master')

@section('content')





    <!-- main header @e -->
    <!-- content @s
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                -->
        <div class="nk-content ">
            <div class="container-fluid">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-block-head-sm">
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title page-title">{{ $regulation->title }}</h3>
                                    <div class="nk-block-des text-soft">

                                    </div>
                                </div><!-- .nk-block-head-content -->
                                <div class="nk-block-head-content">
                                    <div class="toggle-wrap nk-block-tools-toggle">
                                        <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                            data-target="more-options"><em class="icon ni ni-more-v"></em></a>
                                        <div class="toggle-expand-content" data-content="more-options">
                                            <ul class="nk-block-tools g-3">





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

                                    <div class="modal-body modal-body-md">


                                        <div class="tab-content">
                                            <div class="tab-pane active" id="infomation">
                                                <div class="row gy-4">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label" for="lead-name">Title</label>
                                                            <div class="form-control-wrap">
                                                                <input name="title" value="{{ $regulation->title }}"
                                                                    type="text" class="form-control" id="lead-name">
                                                            </div>
                                                        </div>



                                                    </div>



                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label" for="lead-name">Effective Date</label>
                                                            <div class="form-control-wrap">
                                                                <input name="effective_date"
                                                                    value="{{ $regulation->effective_date }}" disabled
                                                                    type="date" class="form-control">
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label" for="lead-name">Issue Date</label>
                                                            <div class="form-control-wrap">
                                                                <input name="issue_date" value="{{ $regulation->issue_date }}"
                                                                    disabled type="date" class="form-control">
                                                            </div>
                                                        </div>

                                                    </div>


                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label" for="lead-name"> Documents
                                                                Version</label>
                                                            <div class="form-control-wrap">
                                                                <input name="document_version"
                                                                    value="{{ $regulation->document_version }}" disabled
                                                                    type="number" class="form-control">
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label" for="lead-name">Year</label>
                                                            <div class="form-control-wrap">
                                                                <select class="form-select" name="year_id" disabled>
                                                                    <option selected disabled value="">Choose...
                                                                    </option>



                                                                    @foreach ($years as $year)
                                                                        <option value="{{ $regulation->year_id }}"
                                                                            @if ($year->id == $regulation->year_id) selected @endif>
                                                                            {{ $year->name }}
                                                                        </option>
                                                                    @endforeach

                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label" for="lead-name">Month</label>
                                                            <div class="form-control-wrap">
                                                                <select class="form-select" name="month_id" disabled>
                                                                    <option selected disabled value="">Choose...
                                                                    </option>


                                                                    @foreach ($months as $month)
                                                                        <option value="{{ $regulation->month_id }}"
                                                                            @if ($month->id == $regulation->month_id) selected @endif>
                                                                            {{ $month->name }}
                                                                        </option>
                                                                    @endforeach


                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    @php
                                                        $categoryslug = $regulation->category->slug;
                                                    @endphp

                                                    @if ($regulation->category->slug == 'rules-regulations' || $regulation->category->slug == 'guidelines')
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name">Entity</label>
                                                                <div class="form-control-wrap">
                                                                    <select class="form-select" name="entity_id" disabled>




                                                                        @foreach ($entities as $entity)
                                                                            <option value="{{ $regulation->entity_id }}"
                                                                                @if ($entity->id == $regulation->entity_id) selected @endif>
                                                                                {{ $entity->name }}
                                                                            </option>
                                                                        @endforeach



                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </div>


                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name">Category</label>
                                                                <div class="form-control-wrap">
                                                                    <select class="form-select" name="category_id" disabled>

                                                                        @php
                                                                            $categorieslist = \App\Models\Category::where(
                                                                                'slug',
                                                                                '=',
                                                                                $categoryslug,
                                                                            )->get();

                                                                        @endphp

                                                                        @foreach ($categorieslist as $category)
                                                                            <option selected readonly
                                                                                value="{{ $category->id }}">
                                                                                {{ $category->slug }}</option>
                                                                        @endforeach
                                                                    </select>


                                                                </div>
                                                            </div>

                                                        </div>




                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name">Sub
                                                                    Category</label>
                                                                <div class="form-control-wrap">
                                                                    <select name="subcategory_id" class="form-select"
                                                                        disabled>
                                                                        @php
                                                                            $categorieslist = \App\Models\Category::where(
                                                                                'slug',
                                                                                '=',
                                                                                $categoryslug,
                                                                            )->first();
                                                                            $subcategorieslist = \App\Models\Subcategory::where(
                                                                                'category_id',
                                                                                '=',
                                                                                $categorieslist->id,
                                                                            )->get();

                                                                        @endphp
                                                                        <option selected
                                                                            value="{{ $regulation->subcategory_id }}">
                                                                            {{ optional($regulation->subcategory)->name }}
                                                                        </option>
                                                                        @foreach ($subcategorieslist as $subcate)
                                                                            <option readonly value="{{ $subcate->id }}">
                                                                                {{ $subcate->name }}</option>
                                                                        @endforeach
                                                                    </select>


                                                                </div>
                                                            </div>

                                                        </div>
                                                    @endif


                                                    @if ($regulation->category->slug == 'market-notices' || $regulation->category->slug == 'market-bulletins')
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name">Category</label>
                                                                <div class="form-control-wrap">
                                                                    <select class="form-select" name="category_id" disabled>

                                                                        @php
                                                                            $categorieslist = \App\Models\Category::where(
                                                                                'slug',
                                                                                '=',
                                                                                $categoryslug,
                                                                            )->get();

                                                                        @endphp

                                                                        @foreach ($categorieslist as $category)
                                                                            <option selected readonly
                                                                                value="{{ $category->id }}">
                                                                                {{ $category->slug }}</option>
                                                                        @endforeach
                                                                    </select>

                                                                </div>
                                                            </div>

                                                        </div>
                                                    @endif


                                                    @if ($regulation->category->slug == 'market-circulars')
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name">Entity</label>
                                                                <div class="form-control-wrap">
                                                                    <select class="form-select" name="entity_id" disabled>




                                                                        @foreach ($entities as $entity)
                                                                            <option value="{{ $regulation->entity_id }}"
                                                                                @if ($entity->id == $regulation->entity_id) selected @endif>
                                                                                {{ $entity->name }}
                                                                            </option>
                                                                        @endforeach



                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </div>


                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name">Category</label>
                                                                <div class="form-control-wrap">
                                                                    <select class="form-select" name="category_id" disabled>

                                                                        @php
                                                                            $categorieslist = \App\Models\Category::where(
                                                                                'slug',
                                                                                '=',
                                                                                $categoryslug,
                                                                            )->get();

                                                                        @endphp

                                                                        @foreach ($categorieslist as $category)
                                                                            <option selected readonly
                                                                                value="{{ $category->id }}">
                                                                                {{ $category->slug }}</option>
                                                                        @endforeach
                                                                    </select>

                                                                </div>
                                                            </div>

                                                        </div>
                                                    @endif

                                                    @if (
                                                        !in_array($regulation->category->slug, [
                                                            'market-circulars',
                                                            'market-notices',
                                                            'market-bulletins',
                                                            'rules-regulations',
                                                            'guidelines',
                                                        ]))
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name">Entity</label>
                                                                <div class="form-control-wrap">
                                                                    <select class="form-select" name="entity_id" disabled>




                                                                        @foreach ($entities as $entity)
                                                                            <option value="{{ $regulation->entity_id }}"
                                                                                @if ($entity->id == $regulation->entity_id) selected @endif>
                                                                                {{ $entity->name }}
                                                                            </option>
                                                                        @endforeach



                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </div>


                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name">Category</label>
                                                                <div class="form-control-wrap">
                                                                    <select class="form-select" name="category_id" disabled>

                                                                        @php
                                                                            $categorieslist = \App\Models\Category::where(
                                                                                'slug',
                                                                                '=',
                                                                                $categoryslug,
                                                                            )->get();

                                                                        @endphp

                                                                        @foreach ($categorieslist as $category)
                                                                            <option selected readonly
                                                                                value="{{ $category->id }}">
                                                                                {{ $category->slug }}</option>
                                                                        @endforeach
                                                                    </select>


                                                                </div>
                                                            </div>

                                                        </div>




                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name">Sub
                                                                    Category</label>
                                                                <div class="form-control-wrap">
                                                                    <select name="subcategory_id" class="form-select"
                                                                        disabled>
                                                                        @php
                                                                            $categorieslist = \App\Models\Category::where(
                                                                                'slug',
                                                                                '=',
                                                                                $categoryslug,
                                                                            )->first();
                                                                            $subcategorieslist = \App\Models\Subcategory::where(
                                                                                'category_id',
                                                                                '=',
                                                                                $categorieslist->id,
                                                                            )->get();

                                                                        @endphp
                                                                        <option selected
                                                                            value="{{ $regulation->subcategory_id }}">
                                                                            {{ optional($regulation->subcategory)->name }}
                                                                        </option>
                                                                        @foreach ($subcategorieslist as $subcate)
                                                                            <option readonly value="{{ $subcate->id }}">
                                                                                {{ $subcate->name }}</option>
                                                                        @endforeach
                                                                    </select>


                                                                </div>
                                                            </div>

                                                        </div>
                                                    @endif







                                                    <div class="col-md-12">
                                                        <br>
                                                        <div class="form-group">
                                                            <label class="form-label" for="lead-name">Alphabet
                                                                Indexing</label>
                                                            <div class="form-control-wrap">
                                                                <div class="form-group">


                                                                    @foreach ($alpha as $val)
                                                                        <div class="form-check form-check-inline"
                                                                            style="margin-right: 10px;">
                                                                            <input class="form-check-input" disabled
                                                                                type="radio" name="alpha_id"
                                                                                value="{{ $val->id }}"
                                                                                style="margin-right: 10px;"
                                                                                <?php if ($regulation->alpha_id == $val->id) {
                                                                                    echo 'checked="checked"';
                                                                                } ?>>

                                                                            <label
                                                                                class="btn btn-success waves-effect waves-light"
                                                                                style="margin-right: 10px;">{{ $val->name }}</label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>






                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="event-title">Ceased/Repealed
                                                            </label>
                                                            <div class="form-control-wrap">
                                                                <select disabled class="form-control" name="ceased">

                                                                    <option value="{{ $regulation->ceased }}">
                                                                        {{ $regulation->ceased }}</option>
                                                                    <option value="Ceased">Ceased</option>
                                                                    <option value="Repealed">Repealed</option>
                                                                    <option value="Amended">Amended</option>


                                                                </select>


                                                            </div>
                                                        </div>
                                                    </div>



                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="event-title">Ceased/Repealed
                                                                Date</label>
                                                            <div class="form-control-wrap">
                                                                <input class="form-control"
                                                                    value="{{ $regulation->ceased_date }}" type="date"
                                                                    name="ceased_date">


                                                            </div>
                                                        </div>
                                                    </div>




                                                    <div class="col-md-6">
                                                        <br>

                                                        <div class="form-group">
                                                            <center>
                                                                <label class="form-label" for="customFileLabel">PDF
                                                                    Document
                                                                    Upload</label>
                                                            </center>
                                                            <div class="form-control-wrap">
                                                                <div class="custom-file">
                                                                    <input name="pdf_file" type="file"
                                                                        class="custom-file-input" />
                                                                    @if (!empty($regulation->regulation_doc))
                                                                        <a href="../public/pdf_documents/{{ $regulation->regulation_doc }}"
                                                                            download="{{ $regulation->regulation_doc }}">
                                                                            <h5>
                                                                                <br>
                                                                                <center>Click to download document</center>
                                                                        </a></h5>
                                                                    @endif
                                                                    {{-- <input type="file" name="pdf_file"
                                                                            class="custom-file-input"> --}}

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-6">
                                                        <br>
                                                        <div class="form-group">
                                                            <label class="form-label" for="customFileLabel">Document
                                                                Index</label>
                                                            <small style="color: brown">Seperate with comas</small>
                                                            <div class="form-control-wrap">
                                                                <div class="custom-file">
                                                                    <textarea disabled name="document_tag" class="form-control">
                                                                            {{ $regulation->document_tag }}
                                                                            </textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>





                                                    @if ($regulation->status == 0 || $regulation->status == 3)
                                                        <div class="col-12">
                                                            <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                                                <li class="d-inline-flex">
                                                                    @can('regulation-approve')
                                                                        <button
                                                                            onclick="document.getElementById('approve-{{ $regulation->id }}').submit();"
                                                                            class="btn btn-lg btn-primary custom-space"
                                                                            id="deleteSubmitBtn-{{ $regulation->id }}"
                                                                            type="submit">
                                                                            <i class="fas fa-spinner fa-spin"
                                                                                style="display:none;"></i>
                                                                            <span class="btn-text">Approve</span>
                                                                        </button>
                                                                    @endcan


                                                                    @can('regulation-reject')
                                                                        <button class="btn btn-lg btn-primary" data-toggle="modal"
                                                                            data-target="#rejectdocument-{{ $regulation->id }}">

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

                                                    <form id="approve-{{ $regulation->id }}"
                                                        action="{{ route('RegStatus', $regulation->id) }}" method="POST"
                                                        class="d-none">
                                                        @csrf
                                                        <input name="status" value="1">
                                                    </form>
                                                </div>
                                            </div><!-- .tab-pane -->

                                        </div><!-- .tab-content -->

                                    </div><!-- .modal-body -->
                                </div>
                            </div><!-- .card-preview -->
                        </div><!-- .nk-block -->
                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade" role="dialog" id="rejectdocument-{{ $regulation->id }}">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                    <div class="modal-body modal-body-md">
                        <h5 class="title">{{ $regulation->title }}</h5>
                        <form method="POST" action="{{ route('RegStatus', $regulation->id) }}"
                            id="rejectForm-{{ $regulation->id }}">
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
                                                    <br>

                                                    <button class="btn btn-lg btn-primary btn-block"
                                                        id="rejectSubmitBtn-{{ $regulation->id }}" type="submit">
                                                        <i class="fas fa-spinner fa-spin" style="display:none;"></i>
                                                        Submit
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

        <script>
            document.getElementById('deleteSubmitBtn-{{ $regulation->id }}').addEventListener('click', function() {
                loading('deleteSubmitBtn-{{ $regulation->id }}');
                setTimeout(() => {
                    document.getElementById('deleteSubmitBtn-{{ $regulation->id }}').disabled = true;
                }, 50);
            });
        </script>
    @endsection
