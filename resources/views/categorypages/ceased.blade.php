@extends('layouts.externalcategory')

@section('content')
    <link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js"></script>
    <script src="{{ asset('public/assets/js/centralized-table-filter.js') }}"></script>
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
    $(document).ready(function() {
        var years = @json($uniqueYears);
        
        // Initialize centralized table filter
        window.tableFilter = initCentralizedTableFilter('example', {
            years: years
        });
    });
</script>
    <section class="gd-main-container">
        <div class="hd-container">

        </div>
        <div class="gl-flex">
            <div class="tabs">
                <div class="current">
                  
                        <p class="current-active" style="font-size: 24px;">A-Z {{ $category->name }} ({{$formattedStatuses}})</p>
                  

                </div>
                <div class="active-line">
                    <div class="line-active"></div>
                    <div class="line-inactive"></div>
                </div>
            </div>
            <a href="{{ route('categorypages', $category->slug) }}">
                <div class="button-container-sb">
                    <div class="gradient-buttons">
                        <div class="gradient-button-content">
                            <div>
                                < Go back </div>
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
                                        <th style="text-align: center;">Category</th>
                                        <th style="text-align: center;">Subcategory</th>
                                        <th style="text-align: center;">Version Number</th>
                                        <th style="text-align: center;">Issue Date</th>
                                        <th style="text-align: center;">Year</th>
                                        <th style="text-align: center;">Effective Date</th>
                                        <th style="text-align: center;">{{$formattedStatuses}}</th>
                                        <th style="text-align: center;">{{$formattedStatuses}} Date</th>
                                        <th style="text-align: center;">Entity</th>
                                        <th style="text-align: center;">Market Product</th>
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
                                                                        <td style="text-align: center">{{ optional($result->category)->name }}</td>
                                            <td style="text-align: center">{{ optional($result->subcategory)->name }}</td>
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
                                                 {{ \Carbon\Carbon::parse($result->ceased_date)->format('M. j, Y') }}
                                               
                                            </td>
                                            <td style="text-align: center">{{ optional($result->entity)->name }}</td>
                                            <td style="text-align: center">
                                                @php
                                                    $tags = $result->marketProductTags ?? collect();
                                                    if (($tags instanceof \Illuminate\Support\Collection ? $tags->isEmpty() : empty($tags)) && !empty($result->market_product_tag)) {
                                                        $ids = array_filter(explode(',', $result->market_product_tag));
                                                        if (!empty($ids)) {
                                                            $tags = \App\Models\MarketProductTag::whereIn('id', $ids)->get();
                                                        }
                                                    }
                                                @endphp
                                                @if($tags && $tags->count())
                                                    @foreach($tags as $tag)
                                                        <span class="badge badge-info" style="margin: 0 2px;">{{ $tag->name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="badge badge-secondary">None</span>
                                                @endif
                                            </td>
                                            
                                            {{-- Related Documents Column --}}
                                            <td style="text-align: center">
                                                @if($result->related_docs)
                                                    @php
                                                        $relatedDocuments = $result->related_documents;
                                                        $relatedCount = $relatedDocuments->count();
                                                    @endphp
                                                    <span class="badge badge-primary" style="cursor: pointer;" data-toggle="modal" data-target="#relatedDocsModal-{{ $result->id }}">{{ $relatedCount }} related</span>
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
                                                            $nestedRelatedDocuments = $result->nested_related_documents;
                                                        @endphp
                            
                                                        @if($nestedRelatedDocuments && $nestedRelatedDocuments->count() > 0)
                                                            <div class="nested-documents-container">
                                                                @include('partials.nested-related-documents', [
                                                                    'documents' => $nestedRelatedDocuments,
                                                                    'level' => 1,
                                                                    'parentNumber' => '',
                                                                    'isSubscribed' => $isSubscribed || Auth::user()->usertype == 'internal'
                                                                ])
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
                           
                         @else
                          <table id="example" class="datatable-init responsive table table-striped"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Title</th>
                                        <th style="text-align: center;">Category</th>
                                        <th style="text-align: center;">Subcategory</th>
                                        <th style="text-align: center;">Effective Date</th>
                                        <th style="text-align: center;">Year</th>
                                        <th style="text-align: center !important;">Entity</th>
                                          <th style="text-align: center;">{{$formattedStatuses}}</th>
                                        <th style="text-align: center;">{{$formattedStatuses}} Date</th>
                                        <th style="text-align: center;">Market Product</th>
                                        <th style="text-align: center;">Related Docs</th>
                                        <th style="text-align: center;"><span
                                                >Action</span></th>

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
                                                                        <td style="text-align: center">{{ optional($result->category)->name }}</td>
                                            <td style="text-align: center">{{ optional($result->subcategory)->name }}</td>


                                            <td style="text-align: center">
                                                {{ \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y') }}
                                            </td>
                                            <td style="text-align: center">{{ $result->year->name }}</td>
                                            <td style="text-align: center">{{ optional($result->entity)->name }}</td>

                                              <td style="text-align: center">
                                                <span class="badge fmdq_Blue">{{$result->ceased}}</span>
                                            </td>

                                             <td style="text-align: center">
                                                 {{ \Carbon\Carbon::parse($result->ceased_date)->format('M. j, Y') }}
                                               
                                            </td>
                                            <td style="text-align: center">
                                                @php
                                                    $tags = $result->marketProductTags ?? collect();
                                                    if (($tags instanceof \Illuminate\Support\Collection ? $tags->isEmpty() : empty($tags)) && !empty($result->market_product_tag)) {
                                                        $ids = array_filter(explode(',', $result->market_product_tag));
                                                        if (!empty($ids)) {
                                                            $tags = \App\Models\MarketProductTag::whereIn('id', $ids)->get();
                                                        }
                                                    }
                                                @endphp
                                                @if($tags && $tags->count())
                                                    @foreach($tags as $tag)
                                                        <span class="badge badge-info" style="margin: 0 2px;">{{ $tag->name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="badge badge-secondary">None</span>
                                                @endif
                                            </td>
                                            
                                            {{-- Related Documents Column --}}
                                            <td style="text-align: center">
                                                @if($result->related_docs)
                                                    @php
                                                        $relatedDocuments = $result->related_documents;
                                                        $relatedCount = $relatedDocuments->count();
                                                    @endphp
                                                    <span class="badge badge-primary" style="cursor: pointer;" data-toggle="modal" data-target="#relatedDocsModal-{{ $result->id }}">{{ $relatedCount }} related</span>
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
                                                            $nestedRelatedDocuments = $result->nested_related_documents;
                                                        @endphp
                            
                                                        @if($nestedRelatedDocuments && $nestedRelatedDocuments->count() > 0)
                                                            <div class="nested-documents-container">
                                                                @include('partials.nested-related-documents', [
                                                                    'documents' => $nestedRelatedDocuments,
                                                                    'level' => 1,
                                                                    'parentNumber' => '',
                                                                    'isSubscribed' => false
                                                                ])
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
                            @include('components.regulations.table', [
                                'records' => $reg, 
                                'isSubscribed' => $isSubscribed,
                                'showFilters' => true,
                                'filterOptions' => [
                                    'showAlphabetFilter' => true,
                                    'showYearFilter' => true,
                                    'showEntityFilter' => false,
                                    'showEffectiveDateFilter' => true,
                                    'showVersionFilter' => true,
                                    'years' => $uniqueYears
                                ]
                            ])
                  
                       
                   @endif
                      
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
                        var pageCount = {{ $result->page_count ?? 0 }};
                        var pdfjsLib = window['pdfjs-dist/build/pdf'];
                        pdfjsLib.GlobalWorkerOptions.workerSrc =
                            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js';

                        pdfjsLib.getDocument(url).promise.then(function(pdfDoc) {
                            // Clear existing content
                            var viewer = document.getElementById('pdf-viewer-' + id);
                            if (!viewer) return;
                            viewer.innerHTML = '';
                            
                            // Use actual page count from PDF if backend didn't provide it
                            var actualPageCount = pageCount > 0 ? pageCount : pdfDoc.numPages;
                            
                            // Implement page-based blur logic (only for non-privileged users)
                            if (actualPageCount === 1) {
                                if (!isPrivileged) {
                                    renderPartialPage(pdfDoc, 1, viewer, 0.5);
                                } else {
                                    renderFullPage(pdfDoc, 1, viewer, false);
                                }
                            } else if (actualPageCount === 2) {
                                renderFullPage(pdfDoc, 1, viewer, false);
                                if (!isPrivileged) {
                                    renderFullPage(pdfDoc, 2, viewer, true);
                                } else {
                                    renderFullPage(pdfDoc, 2, viewer, false);
                                }
                            } else if (actualPageCount >= 3) {
                                renderFullPage(pdfDoc, 1, viewer, false);
                                if (!isPrivileged) {
                                    renderPartialPage(pdfDoc, 2, viewer, 0.5);
                                    for (var i = 3; i <= Math.min(actualPageCount, 5); i++) {
                                        renderFullPage(pdfDoc, i, viewer, true);
                                    }
                                } else {
                                    if (pdfDoc.numPages >= 2) {
                                        renderFullPage(pdfDoc, 2, viewer, false);
                                    }
                                    for (var i = 3; i <= Math.min(actualPageCount, 5); i++) {
                                        renderFullPage(pdfDoc, i, viewer, false);
                                    }
                                }
                            } else {
                                // Fallback: show first 2 pages
                                renderFullPage(pdfDoc, 1, viewer, false);
                                if (pdfDoc.numPages >= 2) {
                                    renderFullPage(pdfDoc, 2, viewer, false);
                                }
                            }
                        }).catch(function(error) {
                            console.error('Error loading PDF:', error);
                            var viewer = document.getElementById('pdf-viewer-' + id);
                            if (viewer) {
                                viewer.innerHTML = '<p class="text-center text-muted">Error loading PDF preview.</p>';
                            }
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
                        
                        // Function to render a partial page with blur effect
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

        <div class="gda-cards-container">




        </div>
    </section>
    </div>

    <script src="{{ asset('public/admin/js/bundle.js') }}"></script>
@endsection
</div>
</body>

</html>