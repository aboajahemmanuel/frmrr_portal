@extends('layouts.externalcategory')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

    <link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .break-text {
            max-width: 200px;
            /* Adjust the width as needed */
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
            /* Ensure the text wraps */
        }

        .filter-container {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            align-items: center;
            flex-wrap: wrap;
            clear: both;
            width: 100%;
        }
        
        .dataTables_wrapper .dataTables_filter {
            float: none !important;
            text-align: left;
            margin-bottom: 15px;
        }
        
        .dataTables_wrapper .dataTables_length {
            float: none !important;
            margin-bottom: 10px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .filter-group label {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .filter-select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fff;
            font-size: 14px;
            min-width: 120px;
            cursor: pointer;
        }

        .filter-select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
        }

        .clear-filters-btn {
            padding: 8px 16px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            align-self: flex-end;
        }

        .clear-filters-btn:hover {
            background-color: #5a6268;
        }

        .search-info {
            margin-top: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
            font-size: 14px;
            color: #495057;
        }
        
        /* Custom styles for PDF preview */
        .pdf-page {
            border: 1px solid #ddd;
            margin-bottom: 10px;
            width: 100%;
        }
        
        .blurred {
            filter: blur(5px);
            opacity: 0.7;
        }
        
        .partial-page {
            position: relative;
            overflow: hidden;
        }
        
        .partial-page .content-mask {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50%;
            background: linear-gradient(transparent 0%, white 100%);
        }
    </style>
    <script>
   
     var years = @json($years);



    $(document).ready(function() {
        var table = $('#example').DataTable({
            columnDefs: [
                {
                    targets: 0, // Title column
                    render: function (data, type, row) {
                        if (type === 'filter' || type === 'sort') {
                            return $('<div>').html(data).text(); // Strips HTML for filtering/sorting
                        }
                        return data; // Keep HTML for display
                    }
                }
            ],
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[0, 'asc']], // Default sort by title
            responsive: true,
            language: {
                search: "Search documents:",
                lengthMenu: "Show _MENU_ documents per page",
                info: "Showing _START_ to _END_ of _TOTAL_ documents",
                infoFiltered: "(filtered from _MAX_ total documents)"
            }
        });

        // Detect table structure and get column indices
        var headers = [];
        $('#example thead th').each(function(index) {
            headers.push($(this).text().trim());
        });
        
        var titleColIndex = 0; // Title is always first
        var yearColIndex = headers.indexOf('Year');

        // Create enhanced filter container
        var filterHtml = '<div class="filter-container">';
        
        // Alphabet filter dropdown
        filterHtml += '<div class="filter-group">';
        filterHtml += '<label for="alphabet-filter">Filter by First Letter:</label>';
        filterHtml += '<select id="alphabet-filter" class="filter-select">';
        filterHtml += '<option value="">All Letters</option>';
        var alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
        alphabet.forEach(function (letter) {
            filterHtml += '<option value="' + letter + '">' + letter + '</option>';
        });
        filterHtml += '</select>';
        filterHtml += '</div>';

        // Year filter dropdown
        filterHtml += '<div class="filter-group">';
        filterHtml += '<label for="year-filter">Filter by Year:</label>';
        filterHtml += '<select id="year-filter" class="filter-select">';
        filterHtml += '<option value="">All Years</option>';
        years.forEach(function (year) {
            filterHtml += '<option value="' + year + '">' + year + '</option>';
        });
        filterHtml += '</select>';
        filterHtml += '</div>';

        // Clear filters button
        filterHtml += '<button class="clear-filters-btn" id="clear-filters">Clear All Filters</button>';
        filterHtml += '</div>';

        // Add search info container
        filterHtml += '<div id="search-info" class="search-info" style="display: none;"></div>';

        $('#example_wrapper').prepend(filterHtml);

        // Custom search function for first letter and year filtering
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var selectedLetter = $('#alphabet-filter').val();
                var selectedYear = $('#year-filter').val();
                
                // If no filters are selected, show all rows
                if (!selectedLetter && !selectedYear) {
                    return true;
                }
                
                var match = true;
                
                // Check alphabet filter
                if (selectedLetter) {
                    var titleText = $('<div>').html(data[titleColIndex]).text().trim();
                    var firstLetter = titleText.charAt(0).toUpperCase();
                    if (firstLetter !== selectedLetter.toUpperCase()) {
                        match = false;
                    }
                }
                
                // Check year filter - find year column dynamically
                if (match && selectedYear) {
                    var currentYearColIndex = yearColIndex;
                    // For tables without explicit year column, try index 3
                    if (currentYearColIndex === -1) {
                        currentYearColIndex = 3;
                    }
                    
                    var yearText = $('<div>').html(data[currentYearColIndex]).text().trim();
                    if (yearText !== selectedYear) {
                        match = false;
                    }
                }
                
                return match;
            }
        );

        // Alphabet filter functionality
        $('#alphabet-filter').on('change', function () {
            table.draw();
            updateSearchInfo();
        });

        // Year filter functionality
        $('#year-filter').on('change', function () {
            table.draw();
            updateSearchInfo();
        });

        // Clear all filters
        $('#clear-filters').on('click', function () {
            $('#alphabet-filter').val('');
            $('#year-filter').val('');
            table.draw();
            $('#search-info').hide();
        });

        // Update search info
        function updateSearchInfo() {
            var info = table.page.info();
            var activeFilters = [];
            
            if ($('#alphabet-filter').val()) {
                activeFilters.push('Letter: ' + $('#alphabet-filter').val());
            }
            if ($('#year-filter').val()) {
                activeFilters.push('Year: ' + $('#year-filter').val());
            }
            
            if (activeFilters.length > 0) {
                var infoText = 'Active filters: ' + activeFilters.join(', ') + 
                              ' | Showing ' + info.recordsDisplay + ' of ' + info.recordsTotal + ' documents';
                $('#search-info').text(infoText).show();
            } else {
                $('#search-info').hide();
            }
        }

        // Update info on table draw
        table.on('draw', function () {
            updateSearchInfo();
        });

        // Enhanced search functionality
        $('.dataTables_filter input').attr('placeholder', 'Search by title, entity, or any field...');
    });
</script>
    <section class="gd-main-container">
        <div class="hd-container">

        </div>
        <div class="gl-flex">
            <div class="tabs">
                <div class="current">
                   
                        <p class="current-active" style="font-size: 24px;">A-Z {{ $subcategory->name }}</p>
                  

                </div>
                <div class="active-line">
                    <div class="line-active"></div>
                    <div class="line-inactive"></div>
                </div>
            </div>

            @if ($subcat_ceased->count() > 0)
                <a href="{{ route('subCatceasedDoc', $subcategory->slug) }}">
                    <div class="button-container-sb">
                        <div class="gradient-buttons">
                            <div class="gradient-button-content">
                                <div>Show {{$formattedStatuses}}</div>
                                <img src="{{ asset('public/users/assets/Arrow - Right.svg') }}" alt="FMDQ Logo" />
                            </div>
                        </div>
                    </div>
                </a>
            @endif


        </div>
        <div style="background-color: #fff; padding: 20px; width: 100%">
            <div class="row" style="width: 100%">
                <div class="col-md-12">
                    @if (Auth::check())
                        @if ($isSubscribed || Auth::user()->usertype == 'internal')
                            <table id="example" class="datatable-init responsive table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Title</th>
                                        <th style="text-align: center;">Version Number</th>
                                        <th style="text-align: center;">Issue Date</th>
                                        <th style="text-align: center;">Year</th>
                                        <th style="text-align: center;">Effective Date</th>
                                        <th style="text-align: center;">Entity</th>
                                        <th style="text-align: center;">Related Docs</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reg as $result)
                                        <tr>
                                               <td>
                                
                                    @if ($result->doc_preview == 1)
                                     <a href="#" data-toggle="modal"
                                                            data-target="#pdfModal-{{ $result->id }}">
                                                            {{ $result->formatted_title }} <em class="icon ni ni-zoom-in"></em>

                                                        </a>
                                    @else
                                        {{ $result->formatted_title }}
                                    @endif
                                
                            </td>
                                            <td style="text-align: center">{{ $result->document_version }}</td>
                                            <td style="text-align: center">
                                                {{ \Carbon\Carbon::parse($result->issue_date)->format('M. j, Y') }}
                                            </td>
                                            <td style="text-align: center">{{ $result->year->name }}</td>
                                            <td style="text-align: center">
                                                {{ \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y') }}
                                            </td>
                                            <td style="text-align: center">{{ optional($result->entity)->name }}</td>
                                            
                                            {{-- Related Documents Column --}}
                                            <td style="text-align: center">
                                                @if($result->related_docs)
                                                    @php
                                                        $relatedDocuments = $result->related_documents;
                                                        $relatedCount = $relatedDocuments->count();
                                                    @endphp
                                                    @if ($isSubscribed || Auth::user()->usertype == 'internal')
                                                        <span class="badge badge-primary" style="cursor: pointer;" data-toggle="modal" data-target="#relatedDocsModal-{{ $result->id }}">{{ $relatedCount }} related</span>
                                                    @else
                                                        <span class="badge badge-warning" style="cursor: pointer;" data-toggle="modal" data-target="#relatedDocsModal-{{ $result->id }}">{{ $relatedCount }} related</span>
                                                    @endif
                                                @else
                                                    <span class="badge badge-secondary">None</span>
                                                @endif
                                            </td>
                                            {{-- End Related Documents Column --}}
                                          
                                            <td class="tb-odr-action"
                                                style="display: flex !important; align-items: center; justify-content: center">
                                                <div style="display: flex !important; align-items: center; justify-content: center" class="tb-odr-btns d-none d-sm-inline">



                                                    @if ($isSubscribed || Auth::user()->usertype == 'internal')
                                                        <a href="{{ asset('public/pdf_documents/' . $result->regulation_doc) }}"
                                                            target="_blank"
                                                            class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                            <em class="icon ni ni-book-read"></em>
                                                        </a>

                                                        <a href="{{ route('download', $result->id) }}"
                                                            class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                                class="icon ni ni-download"></em></a>
                                                    @else
                                                        @if (Auth::check())
                                                            <a href="{{ route('subscribe') }}" target="_blank"
                                                                class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                <em class="icon ni ni-book-read"></em>
                                                            </a>
                                                            <a href="{{ route('subscribe') }}"
                                                                class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                                    class="icon ni ni-download"></em></a>
                                                        @else
                                                            <a href="{{ route('login') }}" target="_blank"
                                                                class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                <em class="icon ni ni-book-read"></em>
                                                            </a>
                                                            <a href="{{ route('login') }}"
                                                                class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                                    class="icon ni ni-download"></em></a>
                                                        @endif
                                                    @endif




                                                    <a href="#" id="submit"
                                                        onclick="document.getElementById('save-{{ $result->id }}').submit();"
                                                        class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                            class="icon ni ni-save"></em></a>






                                                    <form id="save-{{ $result->id }}"
                                                        action="{{ route('save-document', $result->id) }}" method="POST"
                                                        class="d-none" style="display: none">
                                                        @csrf

                                                    </form>




                                                </div>

                                            </td>
                                        </tr>

                                        <!-- Modal for PDF Preview -->
                                        <div class="modal fade" id="pdfModal-{{ $result->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="pdfModalLabel-{{ $result->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <div id="pdf-viewer-{{ $result->id }}">
                                                            <!-- Canvas elements will be dynamically added based on page count -->
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Modal for Related Documents -->
                                        <div class="modal fade" id="relatedDocsModal-{{ $result->id }}" tabindex="-1" role="dialog" aria-labelledby="relatedDocsModalLabel-{{ $result->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="relatedDocsModalLabel-{{ $result->id }}">Related Documents</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @php
                                                            $relatedDocuments = $result->related_documents;
                                                        @endphp
                            
                                                        @if($relatedDocuments && $relatedDocuments->count() > 0)
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    <h5 class="mb-0 text-center">Related Documents</h5>
                                                                </div>
                                
                                                                <div class="card-body p-0">
                                                                    <div class="list-group">
                                                                        @foreach($relatedDocuments as $index => $relatedDoc)
                                                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                                <span>{{ $index + 1 }}. {{ $relatedDoc->title }}</span>
                                                                                <div>
                                                                                    <a href="{{ asset('public/pdf_documents/' . $relatedDoc->regulation_doc) }}" target="_blank" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                                        <em class="icon ni ni-book-read"></em>
                                                                                    </a>
                                                                                    <a href="{{ route('download', $relatedDoc->id) }}" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                                        <em class="icon ni ni-download"></em>
                                                                                    </a>
                                                                                    <a href="#" id="submit" onclick="document.getElementById('save-{{ $relatedDoc->id }}').submit();" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                                        <em class="icon ni ni-save"></em>
                                                                                    </a>
                                                                                    <form id="save-{{ $relatedDoc->id }}" action="{{ route('save-document', $relatedDoc->id) }}" method="POST" class="d-none">
                                                                                        @csrf
                                                                                    </form>
                                                                                    {{-- <a href="{{ route('view_doc', $relatedDoc->id) }}" target="_blank" class="btn btn-sm btn-primary">
                                                                                        View
                                                                                    </a> --}}
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <p>No related documents found.</p>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal for Related Documents -->
                                    @endforeach
                                </tbody>
                            </table>
                       
                          @else
                          <table id="example" class="datatable-init responsive table table-striped"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Title</th>
                                        <th style="text-align: center;">Effective Date</th>
                                        <th style="text-align: center;">Year</th>
                                        <th style="text-align: center;">Entity</th>
                                        <th style="text-align: center;">Related Docs</th>
                                        <th style="text-align: center;"><span
                                                style="">Action</span></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reg as $result)
                                        <tr>
                                               <td>
                                
                                    @if ($result->doc_preview == 1)
                                     <a href="#" data-toggle="modal"
                                                            data-target="#pdfModal-{{ $result->id }}">
                                                            {{ $result->formatted_title }} <em class="icon ni ni-zoom-in"></em>

                                                        </a>
                                    @else
                                        {{ $result->formatted_title }}
                                    @endif
                                
                            </td>


                                            <td style="text-align: center">
                                                {{ \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y') }}
                                            </td>
                                            <td style="text-align: center">{{ $result->year->name }}</td>
                                            <td style="text-align: center">{{ optional($result->entity)->name }}</td>
                                          
                                            <td style="text-align: center">
                                                @if($result->related_docs)
                                                    @php
                                                        $relatedDocuments = $result->related_documents;
                                                        $relatedCount = $relatedDocuments->count();
                                                    @endphp
                                                    @if ($isSubscribed || Auth::user()->usertype == 'internal')
                                                        <span class="badge badge-primary" style="cursor: pointer;" data-toggle="modal" data-target="#relatedDocsModal-{{ $result->id }}">{{ $relatedCount }} related</span>
                                                    @else
                                                        <span class="badge badge-warning" style="cursor: pointer;" data-toggle="modal" data-target="#relatedDocsModal-{{ $result->id }}">{{ $relatedCount }} related</span>
                                                    @endif
                                                @else
                                                    <span class="badge badge-secondary">None</span>
                                                @endif
                                            </td>
                                            <td class="tb-odr-action"
                                                style="display: flex !important; align-items: center; justify-content: center">
                                                <div style="display: flex !important; align-items: center; justify-content: center" class="tb-odr-btns d-none d-sm-inline">



                                                    @if ($isSubscribed)
                                                        <a href="{{ asset('public/pdf_documents/' . $result->regulation_doc) }}"
                                                            target="_blank"
                                                            class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                            <em class="icon ni ni-book-read"></em>
                                                        </a>

                                                        <a href="{{ route('download', $result->id) }}"
                                                            class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                                class="icon ni ni-download"></em></a>
                                                    @else
                                                        @if (Auth::check())
                                                            <a href="{{ route('subscribe') }}" target="_blank"
                                                                class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                <em class="icon ni ni-book-read"></em>
                                                            </a>
                                                            <a href="{{ route('subscribe') }}"
                                                                class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                                    class="icon ni ni-download"></em></a>
                                                        @else
                                                            <a href="{{ route('login') }}" target="_blank"
                                                                class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                <em class="icon ni ni-book-read"></em>
                                                            </a>
                                                            <a href="{{ route('login') }}"
                                                                class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                                    class="icon ni ni-download"></em></a>
                                                        @endif
                                                    @endif




                                                    <a href="#" id="submit"
                                                        onclick="document.getElementById('save-{{ $result->id }}').submit();"
                                                        class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                            class="icon ni ni-save"></em></a>






                                                    <form id="save-{{ $result->id }}"
                                                        action="{{ route('save-document', $result->id) }}" method="POST"
                                                        class="d-none" style="display: none">
                                                        @csrf

                                                    </form>




                                                </div>

                                            </td>
                                        </tr>


                                        <div class="modal fade" id="pdfModal-{{ $result->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="pdfModalLabel-{{ $result->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <div id="pdf-viewer-{{ $result->id }}">
                                                            <!-- Canvas elements will be dynamically added based on page count -->
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Modal for Related Documents -->
                                        <div class="modal fade" id="relatedDocsModal-{{ $result->id }}" tabindex="-1" role="dialog" aria-labelledby="relatedDocsModalLabel-{{ $result->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="relatedDocsModalLabel-{{ $result->id }}">Related Documents</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @php
                                                            $relatedDocuments = $result->related_documents;
                                                        @endphp
                            
                                                        @if($relatedDocuments && $relatedDocuments->count() > 0)
                                                            <div class="card">
                                                                <div class="card-header">
                                                                    <h5 class="mb-0 text-center">Related Documents</h5>
                                                                </div>
                                
                                                                <div class="card-body p-0">
                                                                    <div class="list-group">
                                                                        @foreach($relatedDocuments as $index => $relatedDoc)
                                                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                                <span>{{ $index + 1 }}. {{ $relatedDoc->title }}</span>
                                                                                <div>
                                                                                    <a href="{{ asset('public/pdf_documents/' . $relatedDoc->regulation_doc) }}" target="_blank" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                                        <em class="icon ni ni-book-read"></em>
                                                                                    </a>
                                                                                    <a href="{{ route('download', $relatedDoc->id) }}" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                                        <em class="icon ni ni-download"></em>
                                                                                    </a>
                                                                                    <a href="#" id="submit" onclick="document.getElementById('save-{{ $relatedDoc->id }}').submit();" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                                        <em class="icon ni ni-save"></em>
                                                                                    </a>
                                                                                    <form id="save-{{ $relatedDoc->id }}" action="{{ route('save-document', $relatedDoc->id) }}" method="POST" class="d-none">
                                                                                        @csrf
                                                                                    </form>
                                                                                    {{-- <a href="{{ route('view_doc', $relatedDoc->id) }}" target="_blank" class="btn btn-sm btn-primary">
                                                                                        View
                                                                                    </a> --}}
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <p>No related documents found.</p>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal for Related Documents -->
                                    @endforeach
                                </tbody>
                            </table>
                    @endif
                      
                    @endif
                </div>
            </div>
        </div>






        <div class="gda-cards-container">



            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    @foreach ($reg as $result)
                        (function(id) {
                            var url = '{{ asset("public/pdf_documents/$result->regulation_doc") }}';
                            var pageCount = {{ $result->page_count }};
                            var previewCount = {{ $result->doc_preview_count ?? 2 }}; // Default to 2 pages if not set
                            var pdfjsLib = window['pdfjs-dist/build/pdf'];
                            pdfjsLib.GlobalWorkerOptions.workerSrc =
                                'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js';

                            pdfjsLib.getDocument(url).promise.then(function(pdfDoc) {
                                // Clear existing content
                                var viewer = document.getElementById('pdf-viewer-' + id);
                                viewer.innerHTML = '';
                                
                                // Check if doc_preview_count is set and use it to determine how many pages to show
                                if (previewCount > 0) {
                                    // Show pages based on doc_preview_count
                                    var pagesToShow = Math.min(previewCount, pageCount, 5); // Limit to 5 pages max for performance
                                    
                                    for (var i = 1; i <= pagesToShow; i++) {
                                        if (i === pagesToShow && pageCount > previewCount) {
                                            // For the last page when we're limiting preview, show partial with blur
                                            renderPartialPage(pdfDoc, i, viewer, 0.5);
                                        } else {
                                            // Show full page
                                            renderFullPage(pdfDoc, i, viewer, false);
                                        }
                                    }
                                    
                                    // Blur additional pages if document has more pages than preview count
                                    if (pageCount > previewCount) {
                                        for (var i = previewCount + 1; i <= Math.min(pageCount, 5); i++) {
                                            renderFullPage(pdfDoc, i, viewer, true);
                                        }
                                    }
                                } else {
                                    // Fallback to original logic if doc_preview_count is 0 or not set
                                    if (pageCount === 1) {
                                        // 1-page docs: Show first 3 lines or 50% with blur
                                        renderPartialPage(pdfDoc, 1, viewer, 0.5); // 50% of page with blur
                                    } else if (pageCount === 2) {
                                        // 2-page docs: Show 1 full page with second page blurred
                                        renderFullPage(pdfDoc, 1, viewer, false); // First page full
                                        renderFullPage(pdfDoc, 2, viewer, true);  // Second page blurred
                                    } else if (pageCount >= 3) {
                                        // 3+ page docs: Show first 1.5 pages with remainder blurred
                                        renderFullPage(pdfDoc, 1, viewer, false);    // First page full
                                        renderPartialPage(pdfDoc, 2, viewer, 0.5);   // 50% of second page with blur
                                        // Blur the rest of the pages
                                        for (var i = 3; i <= Math.min(pageCount, 5); i++) { // Limit to first 5 pages for performance
                                            renderFullPage(pdfDoc, i, viewer, true);
                                        }
                                    } else {
                                        // Fallback: show first 2 pages if page count is unknown
                                        renderFullPage(pdfDoc, 1, viewer, false);
                                        renderFullPage(pdfDoc, 2, viewer, false);
                                    }
                                }
                            }).catch(function(error) {
                                console.error('Error loading PDF:', error);
                                // Fallback: show error message
                                var viewer = document.getElementById('pdf-viewer-' + id);
                                viewer.innerHTML = '<p>Error loading PDF preview. Please try again later.</p>';
                            });
                            
                            // Function to render a full page
                            function renderFullPage(pdfDoc, pageNum, viewer, blurred) {
                                if (pageNum > pdfDoc.numPages) return;
                                
                                pdfDoc.getPage(pageNum).then(function(page) {
                                    var viewport = page.getViewport({ scale: 1.5 });
                                    var canvas = document.createElement('canvas');
                                    canvas.className = 'pdf-page';
                                    if (blurred) {
                                        canvas.className += ' blurred';
                                    }
                                    canvas.id = 'canvas-page' + pageNum + '-' + id;
                                    var context = canvas.getContext('2d');
                                    canvas.height = viewport.height;
                                    canvas.width = viewport.width;

                                    var renderContext = {
                                        canvasContext: context,
                                        viewport: viewport
                                    };
                                    
                                    viewer.appendChild(canvas);
                                    page.render(renderContext);
                                });
                            }
                            
                            // Function to render a partial page (with blur effect)
                            function renderPartialPage(pdfDoc, pageNum, viewer, visibleRatio) {
                                if (pageNum > pdfDoc.numPages) return;
                                
                                pdfDoc.getPage(pageNum).then(function(page) {
                                    var viewport = page.getViewport({ scale: 1.5 });
                                    var canvas = document.createElement('canvas');
                                    canvas.className = 'pdf-page partial-page';
                                    canvas.id = 'canvas-page' + pageNum + '-' + id;
                                    var context = canvas.getContext('2d');
                                    canvas.height = viewport.height;
                                    canvas.width = viewport.width;

                                    var renderContext = {
                                        canvasContext: context,
                                        viewport: viewport
                                    };
                                    
                                    viewer.appendChild(canvas);
                                    page.render(renderContext).promise.then(function() {
                                        // Apply blur effect to the hidden portion
                                        if (visibleRatio < 1) {
                                            var ctx = canvas.getContext('2d');
                                            var height = canvas.height;
                                            var hiddenStart = height * visibleRatio;
                                            
                                            // Apply blur to the hidden portion
                                            ctx.filter = 'blur(5px)';
                                            ctx.globalAlpha = 0.7;
                                            ctx.fillRect(0, hiddenStart, canvas.width, height - hiddenStart);
                                            
                                            // Add gradient mask for smoother transition
                                            var gradient = ctx.createLinearGradient(0, hiddenStart - 50, 0, hiddenStart);
                                            gradient.addColorStop(0, 'rgba(255, 255, 255, 0)');
                                            gradient.addColorStop(1, 'rgba(255, 255, 255, 1)');
                                            ctx.filter = 'none';
                                            ctx.globalAlpha = 1;
                                            ctx.fillStyle = gradient;
                                            ctx.fillRect(0, hiddenStart - 50, canvas.width, 50);
                                        }
                                    });
                                });
                            }
                        })({{ $result->id }});
                    @endforeach
                });
            </script>
            {{-- <div class="search-filters">
                <div class="sf-title-gd">Showing guidelines current and historical versions (including ceased
                    versions) with index information:</div>
                <div class="b-a">
                    By Alphabet:
                </div>
                <div class="alphabet-container">
                    @foreach ($alpha as $alphas)
                        <a href="{{ route('alphaname', ['slug' => $category->slug, 'name' => $alphas->name]) }}">
                            <div class="alphabet-card">{{ $alphas->name }}
                            </div>
                        </a>
                    @endforeach

                </div>
                <div class="b-a">
                    By Year:
                </div>
                <div class="alphabet-container" style="font-size: 20px;">
                    @foreach ($years as $year)
                        <a href="{{ route('yearname', ['slug' => $category->slug, 'yname' => $year->name]) }}">
                            <div class="alphabet-card">{{ $year->name }}</div>
                        </a>
                    @endforeach

                </div>
            </div>
            <div class="adv-search-empty">
                <img src="{{ asset('public/users/assets/illustration-search.svg') }}" alt="search illustration">
                <div class="no-doc">No Results to Show</div>
                <div class="get-in">
                    Enter a query to see results here
                </div>
            </div> --}}
        </div>
    </section>
    </div>

    <script src="{{ asset('public/admin/js/bundle.js') }}"></script>
@endsection
</div>
</body>

</html>