@extends('layouts.externalcategory')

@section('content')
    <link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js"></script>
    <script src="{{ asset('public/assets/js/custom-table-filter.js') }}"></script>
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
        }
    </style>

    <script>
    $(document).ready(function() {
        var years = @json($uniqueYears);
        
        // Initialize custom table filter
        initCustomTableFilter('example', {
            years: years,
            showAlphabetFilter: true,
            showEntityFilter: false
        });
        
        // Add back button to filter container (before Clear Filters button)
        setTimeout(function() {
            var clearButton = $('#clear-filters-example');
            if (clearButton.length) {
                var backButton = $(`
                    <div style="display: flex; flex-direction: column; gap: 5px; margin-top: 50px;">
                        <a href="{{ route('subCategory', $subcategory->slug) }}" style="text-decoration: none;">
                            <div class="button-container-sb" style="display: inline-block;">
                                <div class="gradient-buttons">
                                    <div class="gradient-button-content" style="padding: 8px 12px; font-size: 14px;">
                                        <div style="white-space: nowrap;">< Go back</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                `);
                backButton.insertBefore(clearButton);
            }
        }, 100);
        
    
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
                                            <td class="" style="text-align:justify">
                                                <a href="#" data-toggle="modal"
                                                    data-target="#pdfModal-{{ $result->id }}">
                                                    {{ $result->title }} <em class="icon ni ni-zoom-in"></em>

                                                </a>
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
                                                {{ \Carbon\Carbon::parse($result->ceased_date)->format('M. d, Y') }}
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
                                            <div class="modal-dialog modal-xl" role="document">
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
                                        
                                        <!-- Modal for Related Documents -->
                                        <div class="modal fade" id="relatedDocsModal-{{ $result->id }}" tabindex="-1" role="dialog" aria-labelledby="relatedDocsModalLabel-{{ $result->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-xl" role="document">
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
                                                            <div class="card w-100 border-0 shadow-sm">
                                                                <div class="card-header bg-light">
                                                                    <h5 class="mb-0 fw-bold">Related Documents</h5>
                                                                </div>
                                
                                                                <div class="card-body p-0">
                                                                    <!-- Header Row -->
                                                                    <div class="d-flex bg-light border-bottom p-3 fw-bold text-center" style="font-size: 14px;">
                                                                        <div style="width: 8%; min-width: 50px;">S/N</div>
                                                                        <div style="width: 30%; min-width: 200px;">Title</div>
                                                                        <div style="width: 10%; min-width: 80px;">Year</div>
                                                                        <div style="width: 15%; min-width: 120px;">Effective Date</div>
                                                                        <div style="width: 15%; min-width: 120px;">Issued Date</div>
                                                                        <div style="width: 12%; min-width: 100px;">Status</div>
                                                                        <div style="width: 10%; min-width: 120px;">Action</div>
                                                                    </div>
                                                                    
                                                                    <div class="list-group w-100">
                                                                        @if ($isSubscribed || Auth::user()->usertype == 'internal')
                                                                            @foreach($relatedDocuments as $index => $relatedDoc)
                                                                                <div class="list-group-item border-0 border-bottom">
                                                                                    <div class="d-flex align-items-center p-2" style="font-size: 14px;">
                                                                                        <div style="width: 8%; min-width: 50px; text-align: center;">
                                                                                            <strong>{{ $index + 1 }}</strong>
                                                                                        </div>
                                                                                        <div style="width: 30%; min-width: 200px;" class="text-truncate">
                                                                                            {{ $relatedDoc->title }}
                                                                                        </div>
                                                                                        <div style="width: 10%; min-width: 80px; text-align: center;">
                                                                                            {{ optional($relatedDoc->year)->name ?? 'N/A' }}
                                                                                        </div>
                                                                                        <div style="width: 15%; min-width: 120px; text-align: center;">
                                                                                            {{ $relatedDoc->effective_date ? \Carbon\Carbon::parse($relatedDoc->effective_date)->format('M. j, Y') : 'N/A' }}
                                                                                        </div>
                                                                                        <div style="width: 15%; min-width: 120px; text-align: center;">
                                                                                            {{ $relatedDoc->issue_date ? \Carbon\Carbon::parse($relatedDoc->issue_date)->format('M. j, Y') : 'N/A' }}
                                                                                        </div>
                                                                                        <div style="width: 12%; min-width: 100px; text-align: center;">
                                                                                            <span class="badge badge-success">{{ $relatedDoc->ceased ?? 'Active' }}</span>
                                                                                        </div>
                                                                                        <div style="width: 10%; min-width: 120px; text-align: center;">
                                                                                            <div class="d-flex gap-1 justify-content-center">
                                                                                                <a href="{{ asset('public/pdf_documents/' . $relatedDoc->regulation_doc) }}" target="_blank" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                                                    <em class="icon ni ni-book-read"></em>
                                                                                                </a>
                                                                                                <a href="{{ route('download', $relatedDoc->id) }}" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                                                    <em class="icon ni ni-download"></em>
                                                                                                </a>
                                                                                                <a href="#" onclick="document.getElementById('save-{{ $relatedDoc->id }}').submit();" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                                                    <em class="icon ni ni-save"></em>
                                                                                                </a>
                                                                                                <form id="save-{{ $relatedDoc->id }}" action="{{ route('save-document', $relatedDoc->id) }}" method="POST" class="d-none">
                                                                                                    @csrf
                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    {{-- Check if this related document has its own related documents --}}
                                                                                    @if($relatedDoc->related_docs && $relatedDoc->nested_related_documents->count() > 0)
                                                                                        @include('partials.nested-related-documents', [
                                                                                            'nestedDocuments' => $relatedDoc->nested_related_documents,
                                                                                            'parentIndex' => $index + 1,
                                                                                            'level' => 1,
                                                                                            'isSubscribed' => $isSubscribed || Auth::user()->usertype == 'internal'
                                                                                        ])
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                        @else
                                                                            @foreach($relatedDocuments as $index => $relatedDoc)
                                                                                <div class="list-group-item border-0 border-bottom">
                                                                                    <div class="d-flex align-items-center p-2" style="font-size: 14px;">
                                                                                        <div style="width: 8%; min-width: 50px; text-align: center;">
                                                                                            <strong>{{ $index + 1 }}</strong>
                                                                                        </div>
                                                                                        <div style="width: 72%; min-width: 400px;" class="text-muted">
                                                                                            Restricted - Upgrade to view document details
                                                                                        </div>
                                                                                        <div style="width: 20%; min-width: 120px; text-align: center;">
                                                                                            <a href="{{ route('subscribe') }}" target="_blank" class="btn btn-sm btn-warning">
                                                                                                Upgrade to Access
                                                                                            </a>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    {{-- Check if this related document has its own related documents --}}
                                                                                    @if($relatedDoc->related_docs && $relatedDoc->nested_related_documents->count() > 0)
                                                                                        @include('partials.nested-related-documents', [
                                                                                            'nestedDocuments' => $relatedDoc->nested_related_documents,
                                                                                            'parentIndex' => $index + 1,
                                                                                            'level' => 1,
                                                                                            'isSubscribed' => false
                                                                                        ])
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                        @endif
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

                
                    @else
                        <table id="example" class="datatable-init responsive table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Title</th>
                                    <th style="text-align: center;">Category</th>
                                        <th style="text-align: center;">Subcategory</th>
                                    <th style="text-align: center;">Effective Date</th>
                                    <th style="text-align: center;">Entity</th>
                                    <th style="text-align: center;">Related Docs</th>
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

                                                                                    <td style="text-align: center">{{ optional($result->category)->name }}</td>
                                            <td style="text-align: center">{{ optional($result->subcategory)->name }}</td>

                                        <td style="text-align: center">
                                            {{ \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y') }}
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
                                        <div class="modal-dialog modal-xl" role="document">
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
                                        
                                        <!-- Modal for Related Documents -->
                                        <div class="modal fade" id="relatedDocsModal-{{ $result->id }}" tabindex="-1" role="dialog" aria-labelledby="relatedDocsModalLabel-{{ $result->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-xl" role="document">
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
                                                            <div class="card w-100 border-0 shadow-sm">
                                                                <div class="card-header bg-light">
                                                                    <h5 class="mb-0 fw-bold">Related Documents</h5>
                                                                </div>
                                
                                                                <div class="card-body p-0">
                                                                    <!-- Header Row -->
                                                                    <div class="d-flex bg-light border-bottom p-3 fw-bold text-center" style="font-size: 14px;">
                                                                        <div style="width: 8%; min-width: 50px;">S/N</div>
                                                                        <div style="width: 30%; min-width: 200px;">Title</div>
                                                                        <div style="width: 10%; min-width: 80px;">Year</div>
                                                                        <div style="width: 15%; min-width: 120px;">Effective Date</div>
                                                                        <div style="width: 15%; min-width: 120px;">Issued Date</div>
                                                                        <div style="width: 12%; min-width: 100px;">Status</div>
                                                                        <div style="width: 10%; min-width: 120px;">Action</div>
                                                                    </div>
                                                                    
                                                                    <div class="list-group w-100">
                                                                        @if ($isSubscribed || Auth::user()->usertype == 'internal')
                                                                            @foreach($relatedDocuments as $index => $relatedDoc)
                                                                                <div class="list-group-item border-0 border-bottom">
                                                                                    <div class="d-flex align-items-center p-2" style="font-size: 14px;">
                                                                                        <div style="width: 8%; min-width: 50px; text-align: center;">
                                                                                            <strong>{{ $index + 1 }}</strong>
                                                                                        </div>
                                                                                        <div style="width: 30%; min-width: 200px;" class="text-truncate">
                                                                                            {{ $relatedDoc->title }}
                                                                                        </div>
                                                                                        <div style="width: 10%; min-width: 80px; text-align: center;">
                                                                                            {{ optional($relatedDoc->year)->name ?? 'N/A' }}
                                                                                        </div>
                                                                                        <div style="width: 15%; min-width: 120px; text-align: center;">
                                                                                            {{ $relatedDoc->effective_date ? \Carbon\Carbon::parse($relatedDoc->effective_date)->format('M. j, Y') : 'N/A' }}
                                                                                        </div>
                                                                                        <div style="width: 15%; min-width: 120px; text-align: center;">
                                                                                            {{ $relatedDoc->issue_date ? \Carbon\Carbon::parse($relatedDoc->issue_date)->format('M. j, Y') : 'N/A' }}
                                                                                        </div>
                                                                                        <div style="width: 12%; min-width: 100px; text-align: center;">
                                                                                            <span class="badge badge-success">{{ $relatedDoc->ceased ?? 'Active' }}</span>
                                                                                        </div>
                                                                                        <div style="width: 10%; min-width: 120px; text-align: center;">
                                                                                            <div class="d-flex gap-1 justify-content-center">
                                                                                                <a href="{{ asset('public/pdf_documents/' . $relatedDoc->regulation_doc) }}" target="_blank" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                                                    <em class="icon ni ni-book-read"></em>
                                                                                                </a>
                                                                                                <a href="{{ route('download', $relatedDoc->id) }}" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                                                    <em class="icon ni ni-download"></em>
                                                                                                </a>
                                                                                                <a href="#" onclick="document.getElementById('save-{{ $relatedDoc->id }}').submit();" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                                                    <em class="icon ni ni-save"></em>
                                                                                                </a>
                                                                                                <form id="save-{{ $relatedDoc->id }}" action="{{ route('save-document', $relatedDoc->id) }}" method="POST" class="d-none">
                                                                                                    @csrf
                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    {{-- Check if this related document has its own related documents --}}
                                                                                    @if($relatedDoc->related_docs && $relatedDoc->nested_related_documents->count() > 0)
                                                                                        @include('partials.nested-related-documents', [
                                                                                            'nestedDocuments' => $relatedDoc->nested_related_documents,
                                                                                            'parentIndex' => $index + 1,
                                                                                            'level' => 1,
                                                                                            'isSubscribed' => $isSubscribed || Auth::user()->usertype == 'internal'
                                                                                        ])
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                        @else
                                                                            @foreach($relatedDocuments as $index => $relatedDoc)
                                                                                <div class="list-group-item border-0 border-bottom">
                                                                                    <div class="d-flex align-items-center p-2" style="font-size: 14px;">
                                                                                        <div style="width: 8%; min-width: 50px; text-align: center;">
                                                                                            <strong>{{ $index + 1 }}</strong>
                                                                                        </div>
                                                                                        <div style="width: 72%; min-width: 400px;" class="text-muted">
                                                                                            Restricted - Upgrade to view document details
                                                                                        </div>
                                                                                        <div style="width: 20%; min-width: 120px; text-align: center;">
                                                                                            <a href="{{ route('subscribe') }}" target="_blank" class="btn btn-sm btn-warning">
                                                                                                Upgrade to Access
                                                                                            </a>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    {{-- Check if this related document has its own related documents --}}
                                                                                    @if($relatedDoc->related_docs && $relatedDoc->nested_related_documents->count() > 0)
                                                                                        @include('partials.nested-related-documents', [
                                                                                            'nestedDocuments' => $relatedDoc->nested_related_documents,
                                                                                            'parentIndex' => $index + 1,
                                                                                            'level' => 1,
                                                                                            'isSubscribed' => false
                                                                                        ])
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                        @endif
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