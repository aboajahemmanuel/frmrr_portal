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
                                    <h3 class="nk-block-title page-title">Add New Document</h3>
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

                                        <form method="POST" action="{{ route('regulations.store') }}" id="groupForm"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="infomation">
                                                    <div class="row gy-4">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name">Title <span
                                                                        style="color: red">*</span></label>
                                                                <div class="form-control-wrap">
                                                                    <input {{ old('title') }} required name="title"
                                                                        type="text" class="form-control" id="lead-name">
                                                                </div>
                                                            </div>



                                                        </div>



                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name">Effective Date<span
                                                                        style="color: red">*</span></label>
                                                                <div class="form-control-wrap">
                                                                    <input {{ old('effective_date') }} name="effective_date"
                                                                        required type="date" class="form-control">
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name">Issue Date<span
                                                                        style="color: red">*</span></label>
                                                                <div class="form-control-wrap">
                                                                    <input {{ old('issue_date') }} name="issue_date" required
                                                                        type="date" class="form-control">
                                                                </div>
                                                            </div>

                                                        </div>


                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name"> Document
                                                                    Version</label>
                                                                <div class="form-control-wrap">
                                                                    <input {{ old('document_version') }} name="document_version"
                                                                        type="number" class="form-control">
                                                                </div>
                                                            </div>

                                                        </div>


                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name">Year<span
                                                                        style="color: red">*</span></label>
                                                                <div class="form-control-wrap">
                                                                    <select required class="form-control" name="year_id"
                                                                        required>
                                                                        <option selected disabled value="">Select Year
                                                                        </option>

                                                                        @foreach ($years as $year)
                                                                            <option value="{{ $year->id }}">
                                                                                {{ $year->name }}
                                                                            </option>
                                                                        @endforeach

                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name">Month<span
                                                                        style="color: red">*</span></label>
                                                                <div class="form-control-wrap">
                                                                    <select required class="form-control" name="month_id"
                                                                        required>
                                                                        <option selected disabled value="">Select Month
                                                                        </option>
                                                                        @foreach ($months as $month)
                                                                            <option value="{{ $month->id }}">
                                                                                {{ $month->name }}
                                                                            </option>
                                                                        @endforeach

                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </div>


                                                        @php
                                                            $categorieslist = \App\Models\Category::where(
                                                                'slug',
                                                                '=',
                                                                $selectedValue,
                                                            )->get();

                                                        @endphp



                                                        @if ($selectedValue == 'rules-regulations' || $selectedValue == 'guidelines')
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="lead-name">Entity</label>
                                                                    <div class="form-control-wrap">
                                                                        <select class="form-control" name="entity_id" required>
                                                                            <option selected disabled value="">Select
                                                                                Entity
                                                                            </option>
                                                                            @foreach ($entities as $entity)
                                                                                <option value="{{ $entity->id }}">
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
                                                                        <select class="form-select" name="category_id"
                                                                            required>


                                                                            @foreach ($categorieslist as $category)
                                                                                <option selected readonly
                                                                                    value="{{ $category->id }}">
                                                                                    {{ $category->name }}</option>
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
                                                                        <select name="subcategory_id" class="form-control"
                                                                            required>
                                                                            @php
                                                                                $categorieslist = \App\Models\Category::where(
                                                                                    'slug',
                                                                                    '=',
                                                                                    $selectedValue,
                                                                                )->first();
                                                                                $subcategorieslist = \App\Models\Subcategory::where(
                                                                                    'category_id',
                                                                                    '=',
                                                                                    $categorieslist->id,
                                                                                )
                                                                                    ->where('status', 1)
                                                                                    ->get();

                                                                            @endphp
                                                                            {{-- <option selected disabled value="">Choose...
                                                                            </option> --}}
                                                                            <option selected disabled value="">Select Sub
                                                                                Category
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


                                                        @if ($selectedValue == 'market-notices' || $selectedValue == 'market-bulletins')
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="lead-name">Category</label>
                                                                    <div class="form-control-wrap">
                                                                        <select class="form-select" name="category_id"
                                                                            required>


                                                                            @foreach ($categorieslist as $category)
                                                                                <option selected readonly
                                                                                    value="{{ $category->id }}">
                                                                                    {{ $category->name }}</option>
                                                                            @endforeach
                                                                        </select>

                                                                    </div>
                                                                </div>

                                                            </div>
                                                        @endif


                                                        @if ($selectedValue == 'market-circulars')
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="lead-name">Entity</label>
                                                                    <div class="form-control-wrap">
                                                                        <select class="form-select" name="entity_id" required>
                                                                            <option selected disabled value="">
                                                                                Choose...
                                                                            </option>
                                                                            @foreach ($entities as $entity)
                                                                                <option value="{{ $entity->id }}">
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
                                                                        <select class="form-select" name="category_id"
                                                                            required>


                                                                            @foreach ($categorieslist as $category)
                                                                                <option selected readonly
                                                                                    value="{{ $category->id }}">
                                                                                    {{ $category->name }}</option>
                                                                            @endforeach
                                                                        </select>

                                                                    </div>
                                                                </div>

                                                            </div>
                                                        @endif

                                                        @if (
                                                            !in_array($selectedValue, [
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
                                                                        <select class="form-select" name="entity_id" required>
                                                                            <option selected disabled value="">Choose...
                                                                            </option>
                                                                            @foreach ($entities as $entity)
                                                                                <option value="{{ $entity->id }}">
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
                                                                        <select class="form-select" name="category_id"
                                                                            required>


                                                                            @foreach ($categorieslist as $category)
                                                                                <option selected readonly
                                                                                    value="{{ $category->id }}">
                                                                                    {{ $category->name }}</option>
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
                                                                            required>
                                                                            @php
                                                                                $categorieslist = \App\Models\Category::where(
                                                                                    'slug',
                                                                                    '=',
                                                                                    $selectedValue,
                                                                                )->first();
                                                                                $subcategorieslist = \App\Models\Subcategory::where(
                                                                                    'category_id',
                                                                                    '=',
                                                                                    $categorieslist->id,
                                                                                )
                                                                                    ->where('status', 1)
                                                                                    ->get();

                                                                            @endphp
                                                                            <option selected disabled value="">Choose...
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




                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label class="form-label"
                                                                    for="event-title">Ceased/Repealed/Amended
                                                                </label>
                                                                <div class="form-control-wrap">
                                                                    <select class="form-control" name="ceased">
                                                                        <option value="">Active</option>
                                                                        <option value="Ceased">Ceased</option>
                                                                        <option value="Repealed">Repealed</option>
                                                                        <option value="Amended">Amended</option>


                                                                    </select>


                                                                </div>
                                                            </div>
                                                        </div>



                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label class="form-label"
                                                                    for="event-title">Ceased/Repealed/Amended
                                                                    Date</label>
                                                                <div class="form-control-wrap">
                                                                    <input {{ old('ceased_date') }} class="form-control"
                                                                        type="date" name="ceased_date">


                                                                </div>
                                                            </div>
                                                        </div>



                                                        <div class="col-md-12">
                                                            <br>
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name">Alphabet
                                                                    Indexing<span style="color: red">*</span></label>
                                                                <div class="form-control-wrap">
                                                                    <div class="form-group">


                                                                        @foreach ($alpha as $val)
                                                                            <div class="form-check form-check-inline"
                                                                                style="margin-right: 10px;">
                                                                                <input class="form-check-input" required
                                                                                    type="radio" name="alpha_id"
                                                                                    value="{{ $val->id }}"
                                                                                    style="margin-right: 10px;">

                                                                                <label class="btn btn-success"
                                                                                    style="margin-right: 10px;">{{ $val->name }}</label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>








                                                        <div class="col-md-6">
                                                            <br>
                                                            <div class="form-group">
                                                                <label class="form-label" for="customFileLabel">PDF
                                                                    Document
                                                                    Upload<span style="color: red">*</span></label>
                                                                <div class="form-control-wrap">
                                                                    <div class="custom-file">
                                                                        <input required type="file" name="pdf_file"
                                                                            class="custom-file-input">
                                                                        <label class="custom-file-label"
                                                                            for="customFile">Choose file</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="col-md-6">
                                                            <br>
                                                            <div class="form-group">
                                                                <label class="form-label" for="customFileLabel">Document
                                                                    Index<span style="color: red">*</span></label>
                                                                <small style="color: brown">Seperate with comas</small>
                                                                <div class="form-control-wrap">
                                                                    <div class="custom-file">
                                                                        <textarea name="document_tag" required type="text" class="summernote-minimal"> </textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
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


                                                        <div class="col-6">
                                                            <div class="form-group">

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


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('groupForm').addEventListener('submit', function(event) {
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
        <!-- content @e -->
        <!-- @@ Group Add Modal @e -->
    @endsection
