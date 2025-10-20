@extends('layouts.headerexternal')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

<link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    .alphabet-filter,
    .year-filter {
        margin-bottom: 10px;
    }

    .alphabet-filter a,
    .year-filter a {
        margin: 0 4px;
        text-decoration: none;
        color: #007bff;
    }

    .alphabet-filter a.active,
    .year-filter a.active {
        font-weight: bold;
        color: #000;
    }
</style>
<script>
    $(document).ready(function() {
        var table = $('#example').DataTable({
            "order": [
                [0, "asc"]
            ] // Sort by the first column (Issuer Name) in ascending order
        });

        // Create year filter
        var years = [...new Set($('#example tbody tr td:nth-child(4)').map(function() {
            return $(this).text();
        }).get())].sort();
        var yearHtml = '<div class="year-filter">';
        yearHtml += '<a href="#" class="active" data-year="all">All</a>';
        years.forEach(function(year) {
            yearHtml += '<a href="#" data-year="' + year + '">' + year + '</a>';
        });
        yearHtml += '</div>';
        $('#example_wrapper').prepend(yearHtml);

        // Add event listener for year filter
        $('.year-filter a').on('click', function(e) {
            e.preventDefault();
            var year = $(this).data('year');
            $('.year-filter a').removeClass('active');
            $(this).addClass('active');

            if (year === 'all') {
                table.column(3).search('').draw();
            } else {
                table.column(3).search(year).draw();
            }
        });


        // Create alphabet filter
        var alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
        var alphabetHtml = '<div class="alphabet-filter">';
        alphabetHtml += '<a href="#" class="active" data-letter="all">All</a>';
        alphabet.forEach(function(letter) {
            alphabetHtml += '<a href="#" data-letter="' + letter + '">' + letter + '</a>';
        });
        alphabetHtml += '</div>';
        $('#example_wrapper').prepend(alphabetHtml);

        // Add event listener for alphabet filter
        $('.alphabet-filter a').on('click', function(e) {
            e.preventDefault();
            var letter = $(this).data('letter');
            $('.alphabet-filter a').removeClass('active');
            $(this).addClass('active');

            if (letter === 'all') {
                table.column(0).search('').draw();
            } else {
                table.column(0).search('^' + letter, true, false, true).draw();
            }
        });

    });
</script>
<div class="info">

    <div class="title">Search </div>


</div>
</div>

</section>
<section style="background: #e8eaf0 !important;" class="gd-main-container">
    <div class="hd-container">
        <div class="gl-flex">
            <div class="tabs">




            </div>


        </div>
    </div>




    <div class="gda-cards-container">
        <div class="glass-container">


            @if ($isSubscribed)
            <div class="tab-content">
                <div class="">
                    <ul class="nav nav-tabs">
                        <li class="">
                            <a href="#home-1" data-toggle="tab">Basic Search</a>
                        </li>
                        <li class="active"><a href="#profile-1" data-toggle="tab">Advanced Search</a>
                        </li>

                    </ul>
                    <div class="tab-content p-3 text-muted">
                        <div class="tab-pane " id="home-1" role="tabpanel">
                            <nav aria-label="Page navigation example">
                                <form method="GET" action="{{ route('searchPost') }}">
                                    <div class="search-filters" style="padding-right: 0 !important">
                                        <br>
                                        <div class="sf-title">Select category</div>
                                        <div class="spc-btw">

                                            <div class="cb-gap">
                                                @foreach ($categories as $category)
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" style="font-size:5px"
                                                        id="category_{{ $category->id }}" name="category_id"
                                                        value="{{ $category->id }}"
                                                        class="custom-control-input">
                                                    <label class="custom-control-label"
                                                        for="category_{{ $category->id }}"
                                                        style="margin-bottom: 0px; color: #1d326d !important; font-size:12px">{{ $category->name }}</label>
                                                </div>
                                                @endforeach



                                            </div>
                                        </div>
                                        <div class="search-input">
                                            <div class="w-85">
                                                <div class="si-title">Search For</div>
                                                <input class="si-input-box" type="text" name="Key_Words"
                                                    placeholder="Enter words" required />
                                            </div>
                                            <div class="w-50" style="display: none">
                                                <div class="si-title">Search In</div>
                                                <select class="si-input-box-s" style="margin-top: 4px;"
                                                    name="searchBy">

                                                    <option value="title">Title</option>
                                                    <option value="tags">All Content Keywords</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="search-input">
                                            <div>
                                                <div class="si-title">Limit Search to</div>
                                                <div class="si-date">
                                                    <select class="si-input-box-s" style="margin-top: 8px;"
                                                        name="year">
                                                        <option></option>
                                                        @foreach ($years as $year)
                                                        <option value="{{ $year->id }}">
                                                            {{ $year->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>
                                            <div>
                                                <div class="si-title">And/or</div>
                                                <input class="si-input-box" type="text" name="number"
                                                    placeholder="Number" />
                                            </div>
                                            <!-- <div class="b-ao">and/or</div> -->

                                            <div>
                                                <div class="si-title" style="margin-bottom:3px;">Point in Time
                                                </div>
                                                <input class="si-input-box" type="date" name="date_posted" />
                                            </div>
                                        </div>
                                        <div class="btn-flex">
                                            <div class="gradient-buttons">
                                                <div class="gradient-button-content-white">
                                                    <div>Clear Fields</div>
                                                    <img src="{{ asset('public/users/assets/Close.svg') }}"
                                                        alt="Clear Fields" />
                                                </div>
                                            </div>
                                            <div class="gradient-buttons">
                                                <button type="submit">
                                                    <div class="gradient-button-content">
                                                        <div>Search</div>
                                                        <img src="{{ asset('public/users/assets/Arrow - Right.svg') }}"
                                                            alt="Search" />
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </nav>
                        </div>
                        <div class="tab-pane active" id="profile-1" role="tabpanel">
                            <div class="search-filters">
                                <br>
                                <form method="GET" action="{{ route('searchPostAdvance') }}">
                                    <div class="sf-title">Select one or more options</div>
                                    <div class="spc-btw">
                                        <div>
                                            <div class="cb-gap">
                                                @foreach ($categories as $category)
                                                <div class="catgory">
                                                    <input type="checkbox" name="categories[]"
                                                        id="category_{{ $category->id }}"
                                                        value=" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}" />
                                                    <label
                                                        style="margin-bottom: 0px;
                                                        for=" category_{{ $category->id }}">{{ $category->name }}</label>
                                                </div>
                                                @endforeach
                                            </div>


                                        </div>

                                    </div>
                                    <div class="search-input">
                                        <div class="w-33">
                                            <div class="si-title">Search For</div>
                                            <input class="si-input-box" type="text" name="search_Words"
                                                id="" placeholder="Enter words"
                                                value="{{ $title ?? '' }}" />

                                        </div>
                                        <div class="w-33">
                                            <div class="si-title" style="margin-top: 4px;">Search In</div>
                                            <select class="si-input-box-s" name="searchBy">
                                                <option></option>
                                                <option value="title">Title</option>

                                                <option value="tags">All Content Keywords</option>
                                                {{-- <option value="title"
                                                            @if (($searchBy ?? 'title') === 'title') selected @endif>
                                                            Title</option>
                                                        <option value="tags"
                                                            @if (($searchBy ?? 'tags') === 'tags') selected @endif>
                                                            All Content Keywords</option> --}}
                                            </select>

                                        </div>
                                        <div class="w-33">
                                            <div class="si-title" style="margin-top: 4px;">Search Using</div>
                                            <select class="si-input-box-s" name="searchusing" id="">
                                                <option></option>
                                                <option value="allwords"
                                                    @if (($searchUsing ?? 'allwords' )==='allwords' ) selected @endif>
                                                    All of The Words</option>
                                                <option value="anywords"
                                                    @if (($searchUsing ?? 'allwords' )==='anywords' ) selected @endif>
                                                    Any of The Words</option>
                                                <option value="exactwords"
                                                    @if (($searchUsing ?? 'allwords' )==='exactwords' ) selected @endif>The Exact
                                                    Phrase
                                                </option>
                                                <option value="woutwords"
                                                    @if (($searchUsing ?? 'allwords' )==='woutwords' ) selected @endif>Without The
                                                    Words
                                                </option>
                                            </select>



                                        </div>
                                    </div>


                                    <div class="search-input">
                                        <div class="w-33">
                                            <div class="si-title">Issue Date</div>
                                            <input value="{{ $issueDate ?? '' }}" class="si-input-box"
                                                type="date" name="issue_date" />

                                        </div>
                                        <div class="w-33">
                                            <div class="si-title" style="margin-top: 0px;"> Effective Date</div>
                                            <input value="{{ $effectiveDate ?? '' }}" class="si-input-box"
                                                type="date" name="effective_date" />


                                        </div>
                                        <div class="w-33">
                                            <div class="si-title" style="margin-top: 0px;">Version number</div>
                                            <input class="si-input-box" style="margin-top: 3px;" type="number"
                                                name="document_version" value="{{ $versionNumber ?? '' }}" />



                                        </div>


                                    </div>





                                    <div class="search-input">
                                        <div class="w-33">
                                            <div class="si-title" style="margin-top: 4px;">Limit Search to</div>
                                            <select class="si-input-box-s" style="margin-top: 3.5px"
                                                name="year">
                                                <option></option>
                                                @foreach ($years as $yearOption)
                                                <option value="{{ $yearOption->id }}"
                                                    @if (($year_id ?? '' )==$yearOption->id) selected @endif>
                                                    {{ $yearOption->name }}
                                                </option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="w-33">
                                            <div class="si-title">Document Limit</div>
                                            <input class="si-input-box" type="number" name="number"
                                                placeholder="Number" value="{{ $number ?? '' }}" />

                                        </div>


                                        <div class="w-33">
                                            <div class="si-title" style="margin-top: 4px;">Entity</div>
                                            <select class="si-input-box-s" style="margin-top: 3.5px"
                                                name="entity_id" id="">
                                                <option></option>

                                                @foreach ($entities as $entity)
                                                <option value="{{ $entity->id }}"
                                                    @if (($entity_id ?? '' )==$entity->id) selected @endif>
                                                    {{ $entity->name }}
                                                </option>
                                                @endforeach



                                            </select>
                                        </div>


                                        <div class="w-33">
                                            <div class="si-title" style="margin-top: 4px;">Ceased/Repealed </div>
                                            <select class="si-input-box-s" style="margin-top: 3.5px"
                                                name="entity_id" id="">
                                                <option></option>
                                                <option value="Ceased">Ceased</option>
                                                <option value="Repealed">Repealed</option>


                                            </select>
                                        </div>
                                    </div>
                                    <div class="btn-flex">
                                        <div class="gradient-buttons">
                                            <button type="reset">
                                                <div class="gradient-button-content-white">
                                                    <div>Clear Fields</div>
                                                    <img src="{{ asset('public/users/assets/Close.svg') }}"
                                                        alt="Right Arrow" />
                                                </div>
                                            </button>
                                        </div>
                                        <div class="gradient-buttons">
                                            <button type="submit">
                                                <div class="gradient-button-content">
                                                    <div>Search</div>
                                                    <img src="{{ asset('public/users/assets/Arrow - Right.svg') }}"
                                                        alt="Search" />
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                            </div>

                            </form>

                        </div>


                    </div>
                </div>
            </div>
            @else
            @if (Auth::check())
            <div class="tab-content">
                <div class="">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#home-1" data-toggle="tab">Basic Search</a>
                        </li>
                        <li><a href="{{ route('subscribe') }}">Advanced Search</a>
                        </li>

                    </ul>
                    <div class="tab-content p-3 text-muted">
                        <div class="tab-pane active" id="home-1" role="tabpanel">
                            <nav aria-label="Page navigation example">
                                <form method="GET" action="{{ route('searchPost') }}">
                                    <div class="search-filters" style="padding-right: 0 !important">
                                        <br>
                                        <div class="sf-title">Select category</div>
                                        <div class="spc-btw">

                                            <div class="cb-gap">
                                                @foreach ($categories as $category)
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" style="font-size:5px"
                                                        id="category_{{ $category->id }}"
                                                        name="category_id" value="{{ $category->id }}"
                                                        class="custom-control-input">
                                                    <label class="custom-control-label"
                                                        for="category_{{ $category->id }}"
                                                        style="margin-bottom: 0px; color: #1d326d !important; font-size:12px">{{ $category->name }}</label>
                                                </div>
                                                @endforeach



                                            </div>
                                        </div>
                                        <div class="search-input">
                                            <div class="w-85">
                                                <div class="si-title">Search For</div>
                                                <input class="si-input-box" type="text" name="Key_Words"
                                                    placeholder="Enter words" required />
                                            </div>
                                            <div class="w-50" style="display: none">
                                                <div class="si-title">Search In</div>
                                                <select class="si-input-box-s" style="margin-top: 4px;"
                                                    name="searchBy">

                                                    <option value="title">Title</option>
                                                    <option value="tags">All Content Keywords</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="search-input">
                                            <div>
                                                <div class="si-title">Limit Search to</div>
                                                <div class="si-date">
                                                    <select class="si-input-box-s" style="margin-top: 8px;"
                                                        name="year">
                                                        <option></option>
                                                        @foreach ($years as $year)
                                                        <option value="{{ $year->id }}">
                                                            {{ $year->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>
                                            <div>
                                                <div class="si-title">And/or</div>
                                                <input class="si-input-box" type="text" name="number"
                                                    placeholder="Number" />
                                            </div>
                                            <!-- <div class="b-ao">and/or</div> -->

                                            <div>
                                                <div class="si-title" style="margin-bottom:3px;">Point in Time
                                                </div>
                                                <input class="si-input-box" type="date"
                                                    name="date_posted" />
                                            </div>
                                        </div>
                                        <div class="btn-flex">
                                            <div class="gradient-buttons">
                                                <div class="gradient-button-content-white">
                                                    <div>Clear Fields</div>
                                                    <img src="{{ asset('public/users/assets/Close.svg') }}"
                                                        alt="Clear Fields" />
                                                </div>
                                            </div>
                                            <div class="gradient-buttons">
                                                <button type="submit">
                                                    <div class="gradient-button-content">
                                                        <div>Search</div>
                                                        <img src="{{ asset('public/users/assets/Arrow - Right.svg') }}"
                                                            alt="Search" />
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </nav>
                        </div>


                    </div>
                </div>
            </div>
            @else
            <div class="tab-content">
                <div class="mt-5">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#home-1" data-toggle="tab">Basic Search</a>
                        </li>
                        <li><a href="{{ route('login') }}">Advanced Search</a>
                        </li>

                    </ul>
                    <div class="tab-content p-3 text-muted">
                        <div class="tab-pane active" id="home-1" role="tabpanel">
                            <nav aria-label="Page navigation example">
                                <form method="GET" action="{{ route('searchPost') }}">
                                    <div class="search-filters" style="padding-right: 0 !important">
                                        <br>
                                        <div class="sf-title">Select category</div>
                                        <div class="spc-btw">

                                            <div class="cb-gap">
                                                @foreach ($categories as $category)
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" style="font-size:5px"
                                                        id="category_{{ $category->id }}"
                                                        name="category_id" value="{{ $category->id }}"
                                                        class="custom-control-input">
                                                    <label class="custom-control-label"
                                                        for="category_{{ $category->id }}"
                                                        style="margin-bottom: 0px; color: #1d326d !important; font-size:12px">{{ $category->name }}</label>
                                                </div>
                                                @endforeach



                                            </div>
                                        </div>
                                        <div class="search-input">
                                            <div class="w-85">
                                                <div class="si-title">Search For</div>
                                                <input class="si-input-box" type="text" name="Key_Words"
                                                    placeholder="Enter words" required />
                                            </div>
                                            <div class="w-50" style="display: none">
                                                <div class="si-title">Search In</div>
                                                <select class="si-input-box-s" style="margin-top: 4px;"
                                                    name="searchBy">

                                                    <option value="title">Title</option>
                                                    <option value="tags">All Content Keywords</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="search-input">
                                            <div>
                                                <div class="si-title">Limit Search to</div>
                                                <div class="si-date">
                                                    <select class="si-input-box-s" style="margin-top: 8px;"
                                                        name="year">
                                                        <option></option>
                                                        @foreach ($years as $year)
                                                        <option value="{{ $year->id }}">
                                                            {{ $year->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>
                                            <div>
                                                <div class="si-title">And/or</div>
                                                <input class="si-input-box" type="text" name="number"
                                                    placeholder="Number" />
                                            </div>
                                            <!-- <div class="b-ao">and/or</div> -->

                                            <div>
                                                <div class="si-title" style="margin-bottom:3px;">Point in Time
                                                </div>
                                                <input class="si-input-box" type="date"
                                                    name="date_posted" />
                                            </div>
                                        </div>
                                        <div class="btn-flex">
                                            <div class="gradient-buttons">
                                                <div class="gradient-button-content-white">
                                                    <div>Clear Fields</div>
                                                    <img src="{{ asset('public/users/assets/Close.svg') }}"
                                                        alt="Clear Fields" />
                                                </div>
                                            </div>
                                            <div class="gradient-buttons">
                                                <button type="submit">
                                                    <div class="gradient-button-content">
                                                        <div>Search</div>
                                                        <img src="{{ asset('public/users/assets/Arrow - Right.svg') }}"
                                                            alt="Search" />
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </nav>
                        </div>


                    </div>
                </div>
            </div>
            @endif
            @endif
        </div>




        <div class="">
            @if (count($results) == 0)
            <img src="{{ asset('public/users/assets/illustration-search.svg') }}"
                alt="No document purchased illustration" height="250px" />
            <div class="no-doc"></div>
            <div class="get-in">
                There is no search for the word <span>“{{ $title }}”</span>, refine
                your search by trying another keyword
            </div>
            @else
            <div style="background-color: #fff; padding: 20px; width: 100%">
                <div class="row" style="width: 100%">
                    <div class="col-md-12">
                        <table id="example" class="datatable-init nowrap table table-striped"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Ceased/Repealed </th>
                                    <th>NO.</th>
                                    <th>Year</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($results as $result)
                                <tr>
                                    <td>{{ $result->title }}</td>
                                    <td>
                                        @if ($result->ceased == 1)
                                        Ceased/Repealed/Amended
                                        @endif
                                    </td>
                                    <td>{{ $result->document_version }}</td>

                                    <td>{{ $result->year->name }}</td>
                                    <td class="tb-odr-action">
                                        <div class="tb-odr-btns d-none d-sm-inline">
                                            @if ($isSubscribed)
                                            <a href="{{ route('download', $result->id) }}"
                                                target="_blank"
                                                class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                    class="icon ni ni-download"></em></a>
                                            @else
                                            @if (Auth::check())
                                            <a href="{{ route('subscribe') }}" target="_blank"
                                                class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                    class="icon ni ni-download"></em></a>
                                            @else
                                            <a href="{{ route('login') }}" target="_blank"
                                                class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                    class="icon ni ni-download"></em></a>
                                            @endif
                                            @endif
                                            {{-- <a href="html/hotel/invoice-print.html" target="_blank"
                                            class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                class="icon ni ni-download"></em></a> --}}



                                            <a href="#" id="submit"
                                                onclick="document.getElementById('save-{{ $result->id }}').submit();"
                                                class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                    class="icon ni ni-save"></em></a>




                                            <form id="save-{{ $result->id }}"
                                                action="{{ route('save-document', $result->id) }}"
                                                method="POST" class="d-none" style="display: none">
                                                @csrf

                                            </form>




                                        </div>

                                    </td>
                                </tr>
                                @endforeach

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>


    </div>
</section>

</div>
<script src="{{ asset('public/admin/js/bundle.js') }}"></script>
@endsection
</div>
</body>

</html>