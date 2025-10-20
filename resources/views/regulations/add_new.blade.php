@extends('layouts.master')

@section('content')

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
                                                                <label class="form-label" for="lead-name">Title</label>
                                                                <div class="form-control-wrap">
                                                                    <input {{ old('title') }} required name="title"
                                                                        type="text" class="form-control" id="lead-name">
                                                                </div>
                                                            </div>



                                                        </div>



                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name">Effective Date</label>
                                                                <div class="form-control-wrap">
                                                                    <input {{ old('effective_date') }} name="effective_date"
                                                                        required type="date" class="form-control">
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name">Issue Date</label>
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
                                                                <label class="form-label" for="lead-name">Year</label>
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
                                                                <label class="form-label" for="lead-name">Month</label>
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
                                                

                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label class="form-label"
                                                                    for="event-title">{{{$formattedStatuses}}}</label>
                                                                <div class="form-control-wrap">
                                                                    <select class="form-control" name="ceased"
                                                                        id="ceased-select">
                                                                        <option value="">Active</option>
                                                                        @foreach ($statuses as $status)
                                                                            <option value="{{ $status->name }}">
                                                                                {{ $status->name }}</option>

                                                                            
                                                                        @endforeach
                                                                       
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label class="form-label"
                                                                    for="event-title">{{{$formattedStatuses}}} Date</label>
                                                                <div class="form-control-wrap">
                                                                    <input class="form-control" type="date"
                                                                        name="ceased_date" id="ceased-date" disabled>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function () {
                                                        const ceasedSelect = document.getElementById('ceased-select');
                                                        const ceasedDateInput = document.getElementById('ceased-date');

                                                        // Convert PHP array to JSON
                                                        const statuses = @json($statuses);
                                                        const validStatuses = statuses.map(status => status.name.trim().toLowerCase());

                                                        ceasedSelect.addEventListener('change', function () {
                                                            // Normalize value: trim spaces & convert to lowercase
                                                            const selectedValue = this.value.trim().toLowerCase();

                                                            if (validStatuses.includes(selectedValue)) {
                                                                ceasedDateInput.disabled = false;
                                                                ceasedDateInput.required = true;
                                                            } else {
                                                                ceasedDateInput.disabled = true;
                                                                ceasedDateInput.required = false;
                                                                ceasedDateInput.value = ''; // Clear date input when disabled
                                                            }
                                                        });
                                                    });
                                                </script>



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
                                                                    Upload</label>
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
                                                                    Index</label>
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


                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="add-account">Preview Document
                                                                    <span style="color: red;">*</span></label>
                                                                <div class="form-control-wrap">

                                                                    <select required name="doc_preview"
                                                                        class="form-select form-control"
                                                                        data-placeholder="Select one">
                                                                        <option value="">---</option>
                                                                        <option value="1">Active</option>
                                                                        <option value="0">Inactive</option>


                                                                    </select>


                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="doc_preview_count">Preview Page Count</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="number" name="doc_preview_count" id="doc_preview_count"
                                                                        class="form-control" min="0" max="10" value="2"
                                                                        placeholder="Enter number of pages to preview (0 for default behavior)">
                                                                    <div class="form-note">
                                                                        Number of pages users can preview (0 = use default logic)
                                                                    </div>
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
