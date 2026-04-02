@extends('layouts.headerexternal')

@section('content')
    <link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js"></script>
    <script src="{{ asset('public/assets/js/centralized-table-filter.js') . '?v=' . time() }}"></script>
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

            setTimeout(function() {
                var years = @json($years);
                window.tableFilter = initCentralizedTableFilter('example', {
                    years: years
                });
            }, 100);
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
                     

                        <div class="row" style="width: 100%">
                            <div class="col-md-12">
                                  @include('components.regulations.searchAdtable', [
                        'records' => $results, 
                        'isSubscribed' => $isSubscribed,
                        'showFilters' => true,
                        'tableId' => 'example',
                        'filterOptions' => [
                            'showAlphabetFilter' => true, 
                            'showYearFilter' => true,
                            'showEntityFilter' => true,
                            'showEffectiveDateFilter' => false,
                            'showVersionFilter' => false,
                            'showSearchBar' => true,
                            'showStatusFilter' => true,
                            'showMarketProductFilter' => true,
                            'years' => $years
                        ]
                    ]) 

                    {{-- Pagination Info --}}
                          {{-- @if($results->hasPages())
                        <div class="mt-4 d-flex justify-content-center">
                            <nav aria-label="Regulations pagination">
                                {{ $results->onEachSide(1)->links('vendor.pagination.bootstrap-4') }}
                            </nav>
                        </div>
                        @endif --}}
                          
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