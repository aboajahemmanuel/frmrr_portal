@extends('layouts.externalcategory')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

    <link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @php
        // Extract unique years from the regulations
        $uniqueYears = $reg->pluck('year.name')->unique()->sort();
    @endphp

    <style>
        .break-text {
            max-width: 200px;
            /* Adjust the width as needed */
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
            /* Ensure the text wraps */
        }

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
   
     var years = @json($years);



    $(document).ready(function() {
        var table = $('#example').DataTable({
            "order": [
                [0, "asc"]
            ] // Sort by the first column (Issuer Name) in ascending order
        });

        // Use years from PHP instead of extracting from table
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
    <section class="gd-main-container">
        <div class="hd-container">

        </div>
        <div class="gl-flex">
            <div class="tabs">
                <div class="current">
                    
                        <p class="current-active" style="font-size: 24px;">A-Z {{ $subcategory->name }}
                            (Ceased/Repealed/Amended)
                        </p>
                   

                </div>
                <div class="active-line">
                    <div class="line-active"></div>
                    <div class="line-inactive"></div>
                </div>
            </div>
            <a href="{{ route('subCategory', $subcategory->slug) }}">
                <div class="button-container-sb">
                    <div class="gradient-buttons">
                        <div class="gradient-button-content">
                            <div>
                                < Go back</div>
                                    {{-- <img src="{{ asset('public/users/assets/Arrow - Left.svg') }}" alt="FMDQ Logo" /> --}}
                            </div>
                        </div>
                    </div>
            </a>
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
                                         <th style="text-align: center;">{{$formattedStatuses}}</th>
                                        <th style="text-align: center;">{{$formattedStatuses}} Date</th>

                                        <th style="text-align: center;">Entity</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reg as $result)
                                        <tr>
                                            <td class="" style="text-align:justify">
                                                <a href="#" data-toggle="modal"
                                                    data-target="#pdfModal-{{ $result->id }}">
                                                    {{ $result->title }} <em class="icon ni ni-zoom-in"></em>

                                                </a>
                                            </td>
                                            <td style="text-align: center">{{ $result->document_version }}</td>
                                            <td style="text-align: center">
                                                {{ \Carbon\Carbon::parse($result->issue_date)->format('M. j, Y') }}
                                            </td>
                                            <td style="text-align: center">{{ $result->year->name }}</td>
                                            <td style="text-align: center">
                                                {{ \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y') }}
                                            </td>
                                             <td style="text-align: center">
                                                <span class="badge fmdq_Blue">{{$result->ceased}}</span>
                                            </td>

                                             <td style="text-align: center">
                                                {{ \Carbon\Carbon::parse($result->ceased_date)->format('M. d, Y') }}
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
                        @endif
                    @endif

                    @if (Auth::check())
                        @if (!$isSubscribed && Auth::user()->usertype != 'internal')
                            <table id="example" class="datatable-init responsive table table-striped"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Title</th>
                                        <th style="text-align: center;">Effective Date</th>
                                        <th style="text-align: center;">Entity</th>
                                         <th style="text-align: center;">{{$formattedStatuses}}</th>
                                        <th style="text-align: center;">{{$formattedStatuses}} Date</th>
                                        <th style="text-align: center;"><span style=" display:none">Entity</span></th>
                                        <th style="text-align: center;"><span
                                                style="margin: 0px -190px 0px 0px ">Action</span></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reg as $result)
                                        <tr>
                                             <td class="" style="text-align: justify;">
                                                <a href="#" data-toggle="modal"
                                                    data-target="#pdfModal-{{ $result->id }}">
                                                    {{ $result->title }} <em class="icon ni ni-zoom-in"></em>

                                                </a>
                                            </td>


                                            <td style="text-align: center">
                                                {{ \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y') }}
                                            </td>
                                            <td style="text-align: center">{{ optional($result->entity)->name }}</td>
                                            <td style="text-align: center">
                                                <span class="badge fmdq_Blue">{{$result->ceased}}</span>
                                            </td>

                                             <td style="text-align: center">
                                                {{ \Carbon\Carbon::parse($result->ceased_date)->format('M. d, Y') }}
                                            </td>
                                            <td style="text-align: center;"><span
                                                    style=" display:none">{{ $result->year->name }}</span></td>
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
                    @else
                        <table id="example" class="datatable-init responsive table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Title</th>
                                    <th style="text-align: center;">Effective Date</th>
                                    <th style="text-align: center;">Entity</th>
                                    <th style="text-align: center;"><span style=" display:none">Entity</span></th>
                                    <th style="text-align: center;"><span
                                            >Action</span></th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reg as $result)
                                    <tr>
                                         <td class="" style="text-align: justify;">
                                            <a href="#" data-toggle="modal"
                                                data-target="#pdfModal-{{ $result->id }}">
                                                {{ $result->title }} <em class="icon ni ni-zoom-in"></em>

                                            </a>
                                        </td>


                                        <td style="text-align: center">
                                            {{ \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y') }}
                                        </td>
                                        <td style="text-align: center">{{ optional($result->entity)->name }}</td>
                                        <td style="text-align: center;"><span
                                                style=" display:none">{{ $result->year->name }}</span></td>
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
                                if (viewer) {
                                    viewer.innerHTML = '';
                                }
                                
                                // Check if doc_preview_count is set and use it to determine how many pages to show
                                if (previewCount > 0) {
                                    // Show pages based on doc_preview_count
                                    var pagesToShow = Math.min(previewCount, pageCount, 5); // Limit to 5 pages max for performance
                                    
                                    // Create canvas elements dynamically
                                    for (var i = 1; i <= pagesToShow; i++) {
                                        var canvas = document.createElement('canvas');
                                        canvas.id = 'canvas-page' + i + '-' + id;
                                        canvas.className = 'pdf-page';
                                        if (viewer) {
                                            viewer.appendChild(canvas);
                                        }
                                    }
                                    
                                    // Render pages
                                    for (var i = 1; i <= pagesToShow; i++) {
                                        renderPage(i, 'canvas-page' + i + '-' + id);
                                    }
                                    
                                    // Blur additional pages if document has more pages than preview count
                                    if (pageCount > previewCount) {
                                        for (var i = previewCount + 1; i <= Math.min(pageCount, 5); i++) {
                                            var canvas = document.getElementById('canvas-page' + i + '-' + id);
                                            if (canvas) {
                                                canvas.className += ' blurred';
                                            }
                                        }
                                    }
                                } else {
                                    // Fallback to original logic - show first 2 pages
                                    function renderPage(pageNum, canvasId) {
                                        pdfDoc.getPage(pageNum).then(function(page) {
                                            var viewport = page.getViewport({
                                                scale: 1.5
                                            });
                                            var canvas = document.getElementById(canvasId);
                                            if (canvas) {
                                                var context = canvas.getContext('2d');
                                                canvas.height = viewport.height;
                                                canvas.width = viewport.width;

                                                var renderContext = {
                                                    canvasContext: context,
                                                    viewport: viewport
                                                };
                                                page.render(renderContext);
                                            }
                                        });
                                    }

                                    renderPage(1, 'canvas-page1-' + id);
                                    renderPage(2, 'canvas-page2-' + id);
                                }
                            }).catch(function(error) {
                                console.error('Error loading PDF:', error);
                            });
                            
                            // Function to render a page
                            function renderPage(pageNum, canvasId) {
                                pdfDoc.getPage(pageNum).then(function(page) {
                                    var viewport = page.getViewport({
                                        scale: 1.5
                                    });
                                    var canvas = document.getElementById(canvasId);
                                    if (canvas) {
                                        var context = canvas.getContext('2d');
                                        canvas.height = viewport.height;
                                        canvas.width = viewport.width;

                                        var renderContext = {
                                            canvasContext: context,
                                            viewport: viewport
                                        };
                                        page.render(renderContext);
                                    }
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
