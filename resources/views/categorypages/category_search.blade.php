@extends('layouts.externalcategory')

@section('content')
    <link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js"></script>
    <script src="{{ asset('public/assets/js/custom-table-filter.js') }}"></script>
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
        }
    </style>
    <script>
    $(document).ready(function() {
        var years = @json($years);
        
        // Initialize custom table filter
        initCustomTableFilter('example', {
            years: years,
            showAlphabetFilter: true,
            showEntityFilter: false
        });
        
        /*
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
                    // For tables without explicit year column, try hidden year column
                    if (currentYearColIndex === -1) {
                        // For non-subscribed users, year is in hidden column (index 3)
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

        */
    });
</script>
    <section class="gd-main-container">
        <div class="hd-container">

        </div>
        <div class="gl-flex">
            <div class="tabs">
                <div class="current">
                   
                        <p class="current-active" style="font-size: 24px;">A-Z  {{ $category->name }}</p>
                   

                </div>
                <div class="active-line">
                    <div class="line-active"></div>
                    <div class="line-inactive"></div>
                </div>
            </div>


            @if ($search_ceased->count() > 0)
                <a href="{{ route('search_category_ceased', ['slug' => $category->slug, 'title' => $title]) }}">
                    <div class="button-container-sb">
                        <div class="gradient-buttons">
                            <div class="gradient-button-content">
                                <div>Show Ceased/Repealed/Amended</div>
                                <img src="{{ asset('public/users/assets/Arrow - Right.svg') }}" alt="FMDQ Logo" />
                            </div>
                        </div>
                    </div>
                </a>
            @endif

        </div>
        @if (count($search) == 0)
            <img src="{{ asset('public/users/assets/illustration-search.svg') }}" alt="No document purchased illustration"
                height="250px" />
            <div class="no-doc"></div>
            <div class="get-in">
                There is no search for the word <span>“{{ $title }}”</span>, refine
                your search by trying another keyword
            </div>
        @else
            <div style="background-color: #fff; padding: 20px; width: 100%">
                <div class="row" style="width: 100%">
                    <div class="col-md-12">
                        @if (Auth::check())
                            @if ($isSubscribed || Auth::user()->usertype == 'internal')
                                <table id="example" class="datatable-init responsive table table-striped"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">Title</th>
                                            <th style="text-align: center;">Version Number</th>
                                            <th style="text-align: center;">Issue Date</th>
                                            <th style="text-align: center;">Year</th>
                                            <th style="text-align: center;">Effective Date</th>
                                            <th style="text-align: center;">Entity</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($search as $result)
                                            <tr>
                                                 <td class="" style="text-align: justify;">
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
                                                    {{-- {{ \Carbon\Carbon::parse($result->issue_date)->format('M. d, Y') }} --}}
                                                      {{ \Carbon\Carbon::parse($result->issue_date)->format('M. j, Y') }}
                                                </td>
                                                <td style="text-align: center">{{ $result->year->name }}</td>
                                                <td style="text-align: center">
                                                    {{ \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y') }}
                                                </td>
                                                <td style="text-align: center">{{ optional($result->entity)->name }}</td>
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
                                                            action="{{ route('save-document', $result->id) }}"
                                                            method="POST" class="d-none" style="display: none">
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
                                                                <canvas id="canvas-page1-{{ $result->id }}"
                                                                    class="pdf-page"></canvas>
                                                                <canvas id="canvas-page2-{{ $result->id }}"
                                                                    class="pdf-page"></canvas>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
                                        <th style="text-align: center;">Entity</th>
                                        <th style="text-align: center;">Year</th>
                                        <th style="text-align: center;"><span
                                                >Action</span></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($search as $result)
                                        <tr>
                                             <td class="" style="text-align: justify;">
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
                                                {{-- {{ \Carbon\Carbon::parse($result->effective_date)->format('M. d, Y') }} --}}
                                                {{ \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y') }}
                                            </td>
                                            <td style="text-align: center">{{ optional($result->entity)->name }}</td>
                                            <td style="text-align: center">{{ $result->year->name }}</td>
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
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="pdfModalLabel-{{ $result->id }}">
                                                            PDF
                                                            Preview</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div id="pdf-viewer-{{ $result->id }}">
                                                            <canvas id="canvas-page1-{{ $result->id }}"
                                                                class="pdf-page"></canvas>
                                                            <canvas id="canvas-page2-{{ $result->id }}"
                                                                class="pdf-page"></canvas>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>

                       @endif
                      
                    @endif
                    </div>
                </div>
            </div>
            <br>


            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    @foreach ($search as $result)
                        (function(id) {
                            var url = '{{ asset("public/pdf_documents/$result->regulation_doc") }}';
                            var pdfjsLib = window['pdfjs-dist/build/pdf'];
                            pdfjsLib.GlobalWorkerOptions.workerSrc =
                                'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js';

                            pdfjsLib.getDocument(url).promise.then(function(pdfDoc) {
                                function renderPage(pageNum, canvasId) {
                                    pdfDoc.getPage(pageNum).then(function(page) {
                                        var viewport = page.getViewport({
                                            scale: 1.5
                                        });
                                        var canvas = document.getElementById(canvasId);
                                        var context = canvas.getContext('2d');
                                        canvas.height = viewport.height;
                                        canvas.width = viewport.width;

                                        var renderContext = {
                                            canvasContext: context,
                                            viewport: viewport
                                        };
                                        page.render(renderContext);
                                    });
                                }

                                renderPage(1, 'canvas-page1-' + id);
                                renderPage(2, 'canvas-page2-' + id);
                            }).catch(function(error) {
                                console.error('Error loading PDF:', error);
                            });
                        })({{ $result->id }});
                    @endforeach
                });
            </script>
        @endif

        <div class="gda-cards-container">

        </div>
    </section>
    </div>

    <script src="{{ asset('public/admin/js/bundle.js') }}"></script>
@endsection
</div>
</body>

</html>
