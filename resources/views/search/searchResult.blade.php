@extends('layouts.headerexternal')

@section('content')
    <link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js"></script>
    <script src="{{ asset('public/assets/js/custom-table-filter.js') }}"></script>
    <style>
        .break-text {
            max-width: 200px;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
        }

        .filter-container {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            align-items: flex-end;
            flex-wrap: wrap;
            clear: both;
            width: 100%;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 4px;
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
            flex: 1;
            min-width: 150px;
        }

        .filter-group label {
            font-weight: 600;
            color: #333;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .filter-select,
        .filter-input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fff;
            font-size: 14px;
            width: 100%;
            cursor: pointer;
        }

        .filter-select:focus,
        .filter-input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
        }

        .clear-filters-btn {
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            align-self: flex-end;
            width: 100%;
            margin-top: 10px;
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
        
        /* PDF Preview Blur Effects */
        .pdf-page {
            border: 1px solid #ddd;
            margin-bottom: 10px;
            width: 100%;
        }
        
        .pdf-page.blurred {
            filter: blur(8px);
            opacity: 0.5;
        }
        
        .pdf-page.partial-page {
            position: relative;
        }
    </style>
    <script>
        $(document).ready(function() {
            // Initialize DataTable without custom filters (we have manual filters)
            var table = $('#example').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                info: true,
                dom: 'lrtip' // Remove default search box
            });

            // Manual filter functionality
            $('#search-input').on('keyup', function() {
                table.search(this.value).draw();
            });

            $('#letter-filter').on('change', function() {
                var letter = this.value;
                if (letter) {
                    table.column(0).search('^' + letter, true, false).draw();
                } else {
                    table.column(0).search('').draw();
                }
            });

            $('#year-filter').on('change', function() {
                var year = this.value;
                table.column(3).search(year).draw();
            });

            $('#clear-filters-example').on('click', function() {
                $('#search-input').val('');
                $('#letter-filter').val('');
                $('#year-filter').val('');
                table.search('').columns().search('').draw();
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




        <div class="gda-cards-container" style="display: flex; flex-direction: column;">
            @include('search.searchTbaleResult')
            
            @if (count($results) == 0)
                <div class="">
                    <img src="{{ asset('public/users/assets/illustration-search.svg') }}"
                        alt="No document purchased illustration" height="250px" />
                    <div class="no-doc"></div>
                    <div class="get-in">
                        There is no search for the word <span>"{{ $title }}"</span>, refine
                        your search by trying another keyword
                    </div>
                </div>
            @else
                    <div style="background-color: #fff; padding: 20px; width: 100%">
                        <!-- Filter Container -->
                        <div class="filter-container">
                            <div class="filter-group">
                                <label for="search-input">Search:</label>
                                <input type="text" id="search-input" class="filter-input" placeholder="Search...">
                            </div>
                            <div class="filter-group">
                                <label for="letter-filter">First Letter:</label>
                                <select id="letter-filter" class="filter-select">
                                    <option value="">All Letters</option>
                                    @foreach(range('A', 'Z') as $letter)
                                        <option value="{{ $letter }}">{{ $letter }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-group">
                                <label for="year-filter">Year:</label>
                                <select id="year-filter" class="filter-select">
                                    <option value="">All Years</option>
                                    @php
                                        $years = $results->pluck('year.name')->unique()->sort()->values();
                                    @endphp
                                    @foreach($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-group" style="flex: 0 0 100%;">
                                <button id="clear-filters-example" class="clear-filters-btn">Clear Filters</button>
                            </div>
                        </div>

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
                                                    <th style="text-align: center;">Category</th>
                                                    <th style="text-align: center;">Entity</th>
                                                    <th style="text-align: center;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($results as $result)
                                                    <tr>
                                                        <td>
                                                            @php
                                                                $abbr = optional($result->subcategory)->abbreviation ?? optional($result->category)->abbreviation;
                                                                $abbrDesc = optional($result->subcategory)->abbreviation_description ?? optional($result->category)->abbreviation_description;
                                                            @endphp
                                                            @if(!empty($abbr))
                                                                <span class="badge badge-info" style="margin-right:6px;" title="{{ $abbrDesc ?? 'Category abbreviation' }}">{{ $abbr }}</span>
                                                            @endif
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
                                                        <td style="text-align: center">{{ $result->category->name }}</td>
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
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="pdfModalLabel-{{ $result->id }}">Document Preview</h5>
                                                        </div>
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
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        @else
                            <table id="example" class="datatable-init responsive table table-striped"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Title</th>
                                        <th style="text-align: center;">Effective Date</th>
                                        <th style="text-align: center;">Entity</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($results as $result)
                                        <tr>
                                            <td>
                                                @php
                                                    $abbr = optional($result->subcategory)->abbreviation ?? optional($result->category)->abbreviation;
                                                    $abbrDesc = optional($result->subcategory)->abbreviation_description ?? optional($result->category)->abbreviation_description;
                                                @endphp
                                                @if(!empty($abbr))
                                                    <span class="badge badge-info" style="margin-right:6px;" title="{{ $abbrDesc ?? 'Category abbreviation' }}">{{ $abbr }}</span>
                                                @endif
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
                                            <td style="text-align: center">{{ optional($result->entity)->name }}</td>
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
                                                            PDF Preview</h5>
                                                    </div>
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
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
            <br>


                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            @foreach ($results as $result)
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
                @endif

        </div>
    </section>

    </div>
    <script src="{{ asset('public/admin/js/bundle.js') }}"></script>
@endsection
</div>
</body>

</html>