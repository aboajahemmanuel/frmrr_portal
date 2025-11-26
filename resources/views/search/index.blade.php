@extends('layouts.headerexternal')

@section('content')
 <link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js"></script>
    <script src="{{ asset('public/assets/js/centralized-table-filter.js') }}"></script>
    <style>
        .filter-container { display: flex; gap: 15px; margin-bottom: 20px; align-items: center; flex-wrap: wrap; clear: both; width: 100%; }
        .dataTables_wrapper .dataTables_filter { float: none !important; text-align: left; margin-bottom: 15px; }
        .dataTables_wrapper .dataTables_length { float: none !important; margin-bottom: 10px; }
        .filter-group { display: flex; flex-direction: column; gap: 5px; }
        .filter-group label { font-weight: 600; color: #333; font-size: 14px; }
        .filter-select { padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; background-color: #fff; font-size: 14px; min-width: 120px; cursor: pointer; }
        .pdf-page { border: 1px solid #ddd; margin-bottom: 10px; width: 100%; }
        .nested-related-docs { margin-left: 25px; padding-left: 15px; border-left: 2px solid #007bff; margin-top: 10px; }
        .nested-doc-item { padding: 10px; background-color: #f8f9fa; border-radius: 4px; margin-bottom: 8px; }
        .nested-doc-title { font-weight: 500; color: #555; font-size: 14px; margin-bottom: 4px; }
        .nested-badge { display: inline-block; background-color: #17a2b8; color: white; padding: 2px 8px; border-radius: 8px; font-size: 11px; margin-left: 8px; }
        .pdf-page.blurred { filter: blur(8px); opacity: 0.5; }
        .pdf-page.partial-page { position: relative; }
        .clear-filters-btn { padding: 8px 16px; background-color: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; align-self: flex-end; }
        .clear-filters-btn:hover { background-color: #5a6268; }
        .search-info { margin-top: 10px; padding: 10px; background-color: #f8f9fa; border-radius: 4px; font-size: 14px; color: #495057; }
        /* Gradient button styles for ceased documents */
        .button-container-sb { display: inline-block; }
        .gradient-buttons { display: inline-block; }
        .gradient-button-content { display: flex; align-items: center; }
    </style>
    <script>
        $(document).ready(function() {
            var years = @json($years);
            
            // Initialize centralized table filter with pagination disabled
            window.tableFilter = initCentralizedTableFilter('example', {
                years: years
            });
            
       
        });
    </script>
    <div class="info">
        <div class="title">Search Results </div>
        <br>

        <form method="GET" action="{{ route('search_result') }}">
            <div class="search">
                <div class="search-box">
                    <img src="{{ asset('public/users/assets/Search.svg') }}" alt="search icon" />
                    <input required name="title" value="{{ $title }}" type="search" style="color: #000;" />
                </div>
                <a href="#" style="height: 100%;">
                    <button style="height: 100%;" type="submit">
                        <div class="search-full">Search</div>
                    </button>
                </a>
            </div>
        </form>
    </div>
    </div>
    </section>

    <div class="gl-flex">
        <div class="tabs">
        </div>
    </div>

    <section class="gd-main-container">
        @if (is_null($reg))
            <img src="{{ asset('public/users/assets/illustration-search.svg') }}" height="250px" alt="No document purchased illustration" />
            <div class="no-doc">Search Not Found</div>
            <div class="get-in">
                There is no search for the word <span>“{{ $title }}”</span>, refine
                your search by trying another keyword 
            </div>
        @else
            <div style="background-color: #fff; padding: 20px; width: 100%">
                <div class="row" style="width: 100%">
                    <div class="col-md-12">
                        @include('components.regulations.table', [
                            'records' => $reg, 
                            'isSubscribed' => $isSubscribed,
                            'showFilters' => true,
                            'filterOptions' => [
                                'showAlphabetFilter' => true,
                                'showYearFilter' => true,
                                'showEntityFilter' => true,
                                'showEffectiveDateFilter' => true,
                                'showVersionFilter' => true,
                                'years' => $years
                            ]
                        ])

                        @if($reg->hasPages())
                        <div class="mt-4 d-flex justify-content-center">
                            <nav aria-label="Regulations pagination">
                                {{ $reg->links('vendor.pagination.bootstrap-4') }}
                            </nav>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <br>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var isPrivileged = {!! ($isSubscribed || (Auth::check() && Auth::user()->usertype == 'internal')) ? 'true' : 'false' !!};
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
                                        if (i === pagesToShow && pageCount > previewCount && !isPrivileged) {
                                            // For non-privileged users only: last page shows partial with blur
                                            renderPartialPage(pdfDoc, i, viewer, 0.5);
                                        } else {
                                            // Show full page
                                            renderFullPage(pdfDoc, i, viewer, false);
                                        }
                                    }
                                    
                                    // Blur additional pages if document has more pages than preview count (non-privileged only)
                                    if (!isPrivileged && pageCount > previewCount) {
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
                                        for (var i = 3; i <= Math.min(pageCount, 5); i++) {
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
                                            ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';
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

        <div class="gda-cards-container"></div>
    </section>
    </div>

    <script src="{{ asset('public/admin/js/bundle.js') }}"></script>
@endsection
</div>
</body>
</html>