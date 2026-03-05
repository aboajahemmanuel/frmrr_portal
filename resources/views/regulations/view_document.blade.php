@extends('layouts.master')

@section('content')


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

                                        <form method="POST" action="{{ route('update_doc', $regulation->id) }}"
                                            enctype="multipart/form-data" id="editForm-{{ $regulation->id }}">
                                            @csrf
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="infomation">
                                                    <div class="row gy-4">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name">Title</label>
                                                                <div class="form-control-wrap">
                                                                    <input disabled name="title"  value="{{ $regulation->title }}"
                                                                        type="text" class="form-control" id="lead-name">
                                                                </div>
                                                            </div>



                                                        </div>



                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name">Effective Date</label>
                                                                <div class="form-control-wrap">
                                                                    <input disabled name="effective_date"
                                                                        value="{{ $regulation->effective_date }}" required
                                                                        type="date" class="form-control">
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name">Issue Date</label>
                                                                <div class="form-control-wrap">
                                                                    <input disabled name="issue_date"
                                                                        value="{{ $regulation->issue_date }}" required
                                                                        type="date" class="form-control">
                                                                </div>
                                                            </div>

                                                        </div>


                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name"> Document
                                                                    Version</label>
                                                                <div class="form-control-wrap">
                                                                    <input disabled name="document_version"
                                                                        value="{{ $regulation->document_version }}"
                                                                        type="number" class="form-control">
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="lead-name">Year</label>
                                                                <div class="form-control-wrap">
                                                                    <select disabled class="form-select" name="year_id" required>
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
                                                                    <select disabled class="form-select" name="month_id" required>
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
                                                                        <select disabled class="form-select" name="entity_id" required>




                                                                            @foreach ($entities as $entity)
                                                                                <option value="{{ $entity->id ?? '' }}"
                                                                                    @if ($entity->id == $regulation->entity_id) selected @endif>
                                                                                    {{ $entity->name ?? 'Unnamed Entity' }}
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
                                                                        <select disabled required name="category_id"
                                                                            id="Category-dropdown" class="form-control"
                                                                            required>

                                                                            @foreach ($categories as $category)
                                                                                <option value="{{ $category->id }}"
                                                                                    @if ($category->id == $regulation->category_id) selected @endif>
                                                                                    {{ $category->name }}
                                                                                </option>
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
                                                                        <select disabled class="form-control" name="subcategory_id"
                                                                            id="CategoryName-dropdown" required>
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

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="market-product-tags-edit">Market Product Tags</label>
                                                                <div class="form-control-wrap">
                                                                    <select disabled class="form-control" name="market_product_tags[]" id="market-product-tags-edit" multiple>
                                                                        @foreach ($marketProductTags as $tag)
                                                                            <option value="{{ $tag->id }}" 
                                                                                @if($regulation->marketProductTags->contains($tag->id)) selected @endif>
                                                                                {{ $tag->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <small class="form-text text-muted">Hold Ctrl (Cmd on Mac) to select multiple tags</small>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        @if ($regulation->category->slug == 'market-notices' || $regulation->category->slug == 'market-bulletins')
                                                        <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="lead-name">Entity  </label>
                                                                    <div class="form-control-wrap">
                                                                        <select disabled class="form-select" name="entity_id" required>

                                                       <option value="" @if (empty($regulation->entity_id)) selected @endif>-- Select an Entity --</option>
                                        @foreach ($entities as $entity)
                                            <option value="{{ $entity->id ?? '' }}"
                                                @if (!empty($regulation->entity_id) && $entity->id == $regulation->entity_id) selected @endif>
                                                {{ $entity->name ?? 'Unnamed Entity' }}
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
                                                                        <select disabled required name="category_id"
                                                                            id="Category-dropdown" class="form-control"
                                                                            required>

                                                                            @foreach ($categories as $category)
                                                                                <option value="{{ $category->id }}"
                                                                                    @if ($category->id == $regulation->category_id) selected @endif>
                                                                                    {{ $category->name }}
                                                                                </option>
                                                                            @endforeach


                                                                        </select>


                                                                    </div>
                                                                </div>

                                                            </div>

                                                             <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="lead-name">Sub
                                                                        Category</label>
                                                                    <div class="form-control-wrap">
                                                                        <select disabled class="form-control" name="subcategory_id"
                                                                            id="CategoryName-dropdown" required>
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


                                                        @if ($regulation->category->slug == 'market-circulars')
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="lead-name">Entity</label>
                                                                    <div class="form-control-wrap">
                                                                        <select disabled class="form-select" name="entity_id" required>




                                                                            @foreach ($entities as $entity)
                                                                                <option value="{{ $entity->id ?? '' }}"
                                                                                    @if ($entity->id == $regulation->entity_id) selected @endif>
                                                                                    {{ $entity->name ?? 'Unnamed Entity' }}
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
                                                                        <select disabled required name="category_id"
                                                                            id="Category-dropdown" class="form-control"
                                                                            required>

                                                                            @foreach ($categories as $category)
                                                                                <option value="{{ $category->id }}"
                                                                                    @if ($category->id == $regulation->category_id) selected @endif>
                                                                                    {{ $category->name }}
                                                                                </option>
                                                                            @endforeach


                                                                        </select>


                                                                    </div>
                                                                </div>

                                                            </div>
                                                             <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="lead-name">Sub
                                                                        Category</label>
                                                                    <div class="form-control-wrap">
                                                                        <select disabled class="form-control" name="subcategory_id"
                                                                            id="CategoryName-dropdown" required>
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
                                                                        <select disabled class="form-select" name="entity_id" required>

                                                                            @foreach ($entities as $entity)
                                                                                <option value="{{ $entity->id ?? '' }}"
                                                                                    @if ($entity->id == $regulation->entity_id) selected @endif>
                                                                                    {{ $entity->name ?? 'Unnamed Entity' }}
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
                                                                        <select disabled required name="category_id"
                                                                            id="Category-dropdown" class="form-control"
                                                                            required>

                                                                            @foreach ($categories as $category)
                                                                                <option value="{{ $category->id }}"
                                                                                    @if ($category->id == $regulation->category_id) selected @endif>
                                                                                    {{ $category->name }}
                                                                                </option>
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
                                                                        <select disabled class="form-control" name="category_name"
                                                                            id="CategoryName-dropdown" required>
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
                                                                                <input disabled class="form-check-input" required
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
                                                                <label class="form-label"
                                                                    for="event-title">{{$formattedStatuses}}
                                                                </label>
                                                                <div class="form-control-wrap">
                                                                    @php
                                                                        // Prepare existing values (support comma-separated string stored in DB)
                                                                        $existingCeased = $regulation->ceased ?? '';
                                                                        $existingCeasedArr = [];
                                                                        if (is_string($existingCeased) && strlen($existingCeased) > 0) {
                                                                            $existingCeasedArr = array_map('trim', explode(',', $existingCeased));
                                                                        }
                                                                    @endphp
                                                                    <select disabled class="form-control select2" name="ceased[]" id="ceased-select" multiple="multiple" data-placeholder="Select status(es)">
                                                                        <option value="NULL">N/A</option>
                                                                        @foreach ($statuses as $status)
                                                                            @if (trim($status->name) === 'Active')
                                                                                <option value="Active" {{ in_array('Active', $existingCeasedArr) ? 'selected' : '' }}>
                                                                                    {{ trim($status->name) }}</option>
                                                                            @else
                                                                                <option value="{{ trim($status->name) }}" {{ in_array(trim($status->name), $existingCeasedArr) ? 'selected' : '' }}>
                                                                                    {{ trim($status->name) }}
                                                                                </option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>

                                                                </div>
                                                            </div>
                                                        </div>



                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label class="form-label"
                                                                    for="event-title">{{$formattedStatuses}}
                                                                    Date</label>
                                                                <div class="form-control-wrap">
                                                                    <input disabled class="form-control"
                                                                        value="{{ $regulation->ceased_date }}" id="ceased-date" type="date"
                                                                        name="ceased_date">


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

                                                        // For Select2 or native multiple select
                                                        function getSelectedValues(selectEl) {
                                                            if (!selectEl) return [];
                                                            // If Select2 is used, jQuery val() returns array; try that first
                                                            if (window.jQuery && jQuery(selectEl).data('select2')) {
                                                                const val = jQuery(selectEl).val();
                                                                return val ? (Array.isArray(val) ? val : [val]) : [];
                                                            }
                                                            return Array.from(selectEl.selectedOptions || []).map(o => o.value);
                                                        }

                                                        function handleChange() {
                                                            const selectedValues = getSelectedValues(ceasedSelect) || [];

                                                            // Check if any non-NULL/non-Active status is selected
                                                            const hasOtherValidStatus = selectedValues.some(v => {
                                                                return v && v !== 'NULL' && v !== 'Active' && validStatuses.includes(v.trim().toLowerCase());
                                                            });

                                                            // If there's at least one other valid status selected, enable the date
                                                            if (hasOtherValidStatus) {
                                                                ceasedDateInput.disabled = false;
                                                                ceasedDateInput.required = true;
                                                            } else {
                                                                // Otherwise (only Active/NULL or nothing selected) keep it disabled
                                                                ceasedDateInput.disabled = true;
                                                                ceasedDateInput.required = false;
                                                                ceasedDateInput.value = ''; // Clear date input when disabled
                                                            }
                                                        }

                                                        // Initialize Select2 if available
                                                        if (window.jQuery && jQuery.fn && jQuery.fn.select2) {
                                                            jQuery(ceasedSelect).select2({
                                                                placeholder: jQuery(ceasedSelect).data('placeholder') || 'Select statuses',
                                                                allowClear: true,
                                                                width: '100%'
                                                            });
                                                            // Bind change event to Select2
                                                            jQuery(ceasedSelect).on('change', handleChange);
                                                        } else {
                                                            // Fallback for native select
                                                            ceasedSelect.addEventListener('change', handleChange);
                                                        }

                                                        // Trigger initial state check on page load
                                                        handleChange();
                                                    });
                                                </script>


                                                        <div class="col-md-6">
                                                            <br>

                                                            <div class="form-group">
                                                                
                                                                <div class="form-control-wrap">
                                                                    <div class="custom-file">
                                                                        
                                                                        @if (!empty($regulation->regulation_doc))
                                                                            <a href="../public/pdf_documents/{{ $regulation->regulation_doc }}"
                                                                                target="_blank">
                                                                                <h5>
                                                                                    <br>
                                                                                    <center>Click to download document</center>
                                                                            </a></h5>
                                                                        @endif
                                                                     
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
                                                                        <textarea name="document_tag" required type="text" class="summernote-minimal"> {{ $regulation->document_tag }} </textarea>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>



                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="add-account">Select Authoriser
                                                                    <span style="color: red;">*</span></label>
                                                                <div class="form-control-wrap">

                                                                    <select disabled required name="authorizer_id"
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

                                                                <select disabled  required id="doc-preview" name="doc_preview"
                                                                    class="form-select form-control"
                                                                    data-placeholder="Select an option">
                                                                    <option value="">---</option>
                                                                    <option value="1"
                                                                        {{ $regulation->doc_preview == '1' ? 'selected' : '' }}>
                                                                        Active</option>
                                                                    <option value="0"
                                                                        {{ $regulation->doc_preview == '0' ? 'selected' : '' }}>
                                                                        Inactive</option>

                                                                </select>




                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="doc_preview_count">Preview Page Count</label>
                                                            <div class="form-control-wrap">
                                                                <input disabled type="number" name="doc_preview_count" id="doc_preview_count"
                                                                    class="form-control" min="0" max="10" 
                                                                    value="{{ $regulation->doc_preview_count ?? 2 }}"
                                                                    placeholder="Enter number of pages to preview (0 for default behavior)">
                                                                <div class="form-note">
                                                                    Number of pages users can preview (0 = use default logic)
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Related Documents Section --}}
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="related-documents-select">
                                                                Select Related Documents
                                                            </label>
                                                            <div class="form-control-wrap">
                                                                <select disabled 
                                                                    name="related_docs[]" 
                                                                    id="related-documents-select"
                                                                    class="form-select form-control select2"
                                                                    multiple="multiple"
                                                                    data-placeholder="Select one or more documents">
                                                                    
                                                                    @foreach ($relatedDocuments as $doc)
                                                                        <option value="{{ $doc->id }}" 
                                                                            {{ $regulation->relatedDocuments->contains('id', $doc->id) ? 'selected' : '' }}>
                                                                            {{ $doc->title }} ({{ $doc->month->name }} - {{ $doc->year->name }})
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <script>
                                                        $(document).ready(function() {
                                                            $('#related-documents-select').select2();
                                                        });
                                                    </script>
                                                    {{-- End Related Documents Section --}}

                                                  
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
            function loading(buttonId) {
                $("#" + buttonId + " .fa-spinner").show();
                $("#" + buttonId + " .btn-text").html("Processing...");
            }

            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('editForm-{{ $regulation->id }}').addEventListener('submit', function(event) {
                    if (this.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    } else {
                        loading('editSubmitBtn-{{ $regulation->id }}');
                        document.getElementById('editSubmitBtn-{{ $regulation->id }}').disabled = true;
                    }
                    this.classList.add('was-validated');
                }, false);
            });
        </script>


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            $(document).ready(function() {








                /*------------------------------------------
                --------------------------------------------
                Category Dropdown Change Event
                --------------------------------------------
                --------------------------------------------*/
                $('#Category-dropdown').on('change', function() {
                    var idCategory = this.value;
                    $("#CategoryName-dropdown").html('');
                    $.ajax({
                        url: "{{ url('fetch-category') }}",
                        type: "POST",
                        data: {
                            category_id: idCategory,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(result) {
                            $('#CategoryName-dropdown').html();
                            $.each(result, function(key, value) {
                                $("#CategoryName-dropdown").append('<option value="' + value
                                    .name + '">' + value.name + '</option>');
                            });

                        }
                    });
                });

                /*------------------------------------------
                --------------------------------------------
                State Dropdown Change Event
                --------------------------------------------
                --------------------------------------------*/
                $('#Category-dropdown').on('change', function() {
                    var idColor = this.value;
                    $("#CategoryColor-dropdown").html('');
                    $.ajax({
                        url: "{{ url('fetch-sub') }}",
                        type: "POST",
                        data: {
                            category_id: idColor,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(res) {
                            $('#CategoryColor-dropdown').html(
                                '');
                            $.each(res, function(key, value) {
                                $("#CategoryColor-dropdown").append('<option value="' +
                                    value
                                    .color + '">' + value.color + '</option>');
                            });
                        }
                    });
                });










            });
        </script>
        <!-- content @e -->
        <!-- @@ Group Add Modal @e -->
    @endsection
