@extends('layouts.externaltag')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

<link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

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
    
    /* Related Documents Styling */
    .related-docs-badge {
        display: inline-block;
        padding: 4px 10px;
        background-color: #007bff;
        color: white;
        border-radius: 12px;
        font-size: 12px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    
    .related-docs-badge:hover {
        background-color: #0056b3;
    }
    
    .related-docs-badge.no-docs {
        background-color: #6c757d;
        cursor: default;
    }
    
    .related-doc-item {
        padding: 12px;
        border-bottom: 1px solid #e0e0e0;
        transition: background-color 0.2s;
    }
    
    .related-doc-item:last-child {
        border-bottom: none;
    }
    
    .related-doc-item:hover {
        background-color: #f8f9fa;
    }
    
    .related-doc-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }
    
    .related-doc-meta {
        font-size: 12px;
        color: #666;
    }
    
    .related-doc-meta span {
        margin-right: 15px;
    }
    
    .related-docs-modal .modal-body {
        max-height: 500px;
        overflow-y: auto;
    }
    
    .nested-related-docs {
        margin-left: 25px;
        padding-left: 15px;
        border-left: 2px solid #007bff;
        margin-top: 10px;
    }
    
    .nested-doc-item {
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 4px;
        margin-bottom: 8px;
    }
    
    .nested-doc-title {
        font-weight: 500;
        color: #555;
        font-size: 14px;
        margin-bottom: 4px;
    }
    
    .nested-badge {
        display: inline-block;
        background-color: #17a2b8;
        color: white;
        padding: 2px 8px;
        border-radius: 8px;
        font-size: 11px;
        margin-left: 8px;
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
        var years = @json($years);
        
        // Initialize custom table filter with pagination disabled
        initCustomTableFilter('example', {
            years: years,
            showAlphabetFilter: true,
            showEntityFilter: false,
            paging: false,
            info: false
        });
    });
</script>


<section class="gd-main-container">
    <div class="hd-container">

    </div>
    <div class="gl-flex">
        <div class="tabs">
            <div class="current">
                <a href="#">
                    <p class="current-active" style="font-size: 24px;">{{ $marketTag->name }} Documents</p>
                </a>

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
                                            <th style="text-align: center;">Related Docs</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reg as $result)
                                            <tr>
                                                  <td>
                                
                                    {{ $result->title }}
                                    @if ($result->doc_preview == 1)
                                     <a href="#" data-toggle="modal"
                                                            data-target="#pdfModal-{{ $result->id }}">
                                                            {{ $result->title }} <em class="icon ni ni-zoom-in"></em>

                                                        </a>
                                        
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
                                                <td style="text-align: center">
                                                    @php
                                                        $relatedDocs = $result->relatedDocuments;
                                                        $relatedCount = $relatedDocs->count();
                                                    @endphp
                                                    @if($relatedCount > 0)
                                                        <a href="#" data-toggle="modal" data-target="#relatedDocsModal-{{ $result->id }}" class="related-docs-badge">
                                                            <em class="icon ni ni-link-alt"></em> {{ $relatedCount }}
                                                        </a>
                                                    @else
                                                        <span class="related-docs-badge no-docs">0</span>
                                                    @endif
                                                </td>
                                                <td class="tb-odr-action"
                                                    style="display: flex !important; align-items: center; justify-content: center">
                                                    <div style="display: flex !important; align-items: center; justify-content: center" class="tb-odr-btns d-none d-sm-inline">





                                                        @if ($isSubscribed || Auth::user()->usertype == 'internal' )
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
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
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

                                            <!-- Modal for Related Documents -->
                                            <div class="modal fade related-docs-modal" id="relatedDocsModal-{{ $result->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="relatedDocsModalLabel-{{ $result->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="relatedDocsModalLabel-{{ $result->id }}">
                                                                Related Documents - {{ $result->title }}
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @php
                                                                $relatedDocs = $result->relatedDocuments;
                                                            @endphp
                                                            @if($relatedDocs->count() > 0)
                                                                @foreach($relatedDocs as $relatedDoc)
                                                                    <div class="related-doc-item">
                                                                        <div class="related-doc-title">
                                                                            {{ $relatedDoc->title }}
                                                                            @if(isset($relatedDoc->nested_related_documents) && $relatedDoc->nested_related_documents->count() > 0)
                                                                                <span class="nested-badge">+{{ $relatedDoc->nested_related_documents->count() }} more</span>
                                                                            @endif
                                                                        </div>
                                                                        <div class="related-doc-meta">
                                                                            @if($relatedDoc->document_version)
                                                                                <span><strong>Version:</strong> {{ $relatedDoc->document_version }}</span>
                                                                            @endif
                                                                            @if($relatedDoc->effective_date)
                                                                                <span><strong>Effective Date:</strong> {{ \Carbon\Carbon::parse($relatedDoc->effective_date)->format('M. j, Y') }}</span>
                                                                            @endif
                                                                            @if($relatedDoc->entity)
                                                                                <span><strong>Entity:</strong> {{ $relatedDoc->entity->name }}</span>
                                                                            @endif
                                                                        </div>
                                                                        <div style="margin-top: 8px;">
                                                                            @if ($isSubscribed || Auth::user()->usertype == 'internal')
                                                                                <a href="{{ asset('public/pdf_documents/' . $relatedDoc->regulation_doc) }}" target="_blank" class="btn btn-sm btn-primary">
                                                                                    <em class="icon ni ni-book-read"></em> View
                                                                                </a>
                                                                                <a href="{{ route('download', $relatedDoc->id) }}" class="btn btn-sm btn-primary">
                                                                                    <em class="icon ni ni-download"></em> Download
                                                                                </a>
                                                                            @endif
                                                                        </div>
                                                                        
                                                                        @if(isset($relatedDoc->nested_related_documents) && $relatedDoc->nested_related_documents->count() > 0)
                                                                            <div class="nested-related-docs">
                                                                                <small><strong>Related Documents:</strong></small>
                                                                                @foreach($relatedDoc->nested_related_documents as $nestedDoc)
                                                                                    <div class="nested-doc-item">
                                                                                        <div class="nested-doc-title">
                                                                                            <em class="icon ni ni-chevron-right"></em> {{ $nestedDoc->title }}
                                                                                        </div>
                                                                                        <div class="related-doc-meta">
                                                                                            @if($nestedDoc->document_version)
                                                                                                <span><strong>Version:</strong> {{ $nestedDoc->document_version }}</span>
                                                                                            @endif
                                                                                            @if($nestedDoc->ceased)
                                                                                            <span><strong>Status:</strong> {{ $nestedDoc->ceased  }}</span>
                                                                                        @endif

                                                                                        @if($nestedDoc->effective_date)
                                                                                            <span><strong>Effective Date:</strong> {{ \Carbon\Carbon::parse($nestedDoc->effective_date)->format('M. j, Y') }}</span>
                                                                                        @endif

                                                                                        @if($nestedDoc->issue_date)
                                                                                        <span><strong>Issue Date:</strong> {{ \Carbon\Carbon::parse($nestedDoc->issue_date)->format('M. j, Y') }}</span>
                                                                                    @endif
                                                                                        </div>
                                                                                        @if ($isSubscribed || Auth::user()->usertype == 'internal')
                                                                                            <div style="margin-top: 5px;">
                                                                                                <a href="{{ asset('public/pdf_documents/' . $nestedDoc->regulation_doc) }}" target="_blank" class="btn btn-xs btn-outline-primary">
                                                                                                    <em class="icon ni ni-book-read"></em> View
                                                                                                </a>
                                                                                                <a href="{{ route('download', $nestedDoc->id) }}" class="btn btn-xs btn-outline-primary">
                                                                                                    <em class="icon ni ni-download"></em> Download
                                                                                                </a>
                                                                                            </div>
                                                                                        @endif
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <p class="text-center text-muted">No related documents found.</p>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                {{-- Pagination Info --}}
                               
                            @endif
                        @else
                            <table id="example" class="datatable-init responsive table table-striped"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Title</th>
                                        <th style="text-align: center;">Effective Date</th>
                                        <th style="text-align: center;">Entity</th>
                                        <th style="text-align: center;">Category</th>
                                        <th style="text-align: center;">Related Docs</th>
                                        <th style="text-align: center;"><span
                                                >Action</span></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reg as $result)
                                        <tr>
                                             
                                                                    
                                  <td>
                                
                                    {{ $result->title }}
                                    @if ($result->doc_preview == 1)
                                     <a href="#" data-toggle="modal"
                                                            data-target="#pdfModal-{{ $result->id }}">
                                                            {{ $result->title }} <em class="icon ni ni-zoom-in"></em>

                                                        </a>
                                        
                                    @endif
                                
                            </td>



                                            <td style="text-align: center">
                                               
                                                {{ \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y') }}
                                            </td>
                                            <td style="text-align: center">{{ optional($result->entity)->name }}</td>
                                            <td style="text-align: center">{{ $result->category->name }}</td>
                                            <td style="text-align: center">
                                                @php
                                                    $relatedDocs = $result->relatedDocuments;
                                                    $relatedCount = $relatedDocs->count();
                                                @endphp
                                                @if($relatedCount > 0)
                                                    <a href="#" data-toggle="modal" data-target="#relatedDocsModal-{{ $result->id }}" class="related-docs-badge">
                                                        <em class="icon ni ni-link-alt"></em> {{ $relatedCount }}
                                                    </a>
                                                @else
                                                    <span class="related-docs-badge no-docs">0</span>
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

                                        <!-- Modal for Related Documents -->
                                        <div class="modal fade related-docs-modal" id="relatedDocsModal-{{ $result->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="relatedDocsModalLabel-{{ $result->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="relatedDocsModalLabel-{{ $result->id }}">
                                                            Related Documents - {{ $result->title }}
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @php
                                                            $relatedDocs = $result->relatedDocuments;
                                                        @endphp
                                                        @if($relatedDocs->count() > 0)
                                                            @foreach($relatedDocs as $relatedDoc)
                                                                <div class="related-doc-item">
                                                                    <div class="related-doc-title">
                                                                        {{ $relatedDoc->title }}
                                                                        @if(isset($relatedDoc->nested_related_documents) && $relatedDoc->nested_related_documents->count() > 0)
                                                                            <span class="nested-badge">+{{ $relatedDoc->nested_related_documents->count() }} more</span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="related-doc-meta">
                                                                        @if($relatedDoc->document_version)
                                                                            <span><strong>Version:</strong> {{ $relatedDoc->document_version }}</span>
                                                                        @endif
                                                                        @if($relatedDoc->effective_date)
                                                                            <span><strong>Effective Date:</strong> {{ \Carbon\Carbon::parse($relatedDoc->effective_date)->format('M. j, Y') }}</span>
                                                                        @endif
                                                                        @if($relatedDoc->entity)
                                                                            <span><strong>Entity:</strong> {{ $relatedDoc->entity->name }}</span>
                                                                        @endif
                                                                    </div>
                                                                    @if($isSubscribed)
                                                                        <div style="margin-top: 8px;">
                                                                            <a href="{{ asset('public/pdf_documents/' . $relatedDoc->regulation_doc) }}" target="_blank" class="btn btn-sm btn-primary">
                                                                                <em class="icon ni ni-book-read"></em> View
                                                                            </a>
                                                                            <a href="{{ route('download', $relatedDoc->id) }}" class="btn btn-sm btn-primary">
                                                                                <em class="icon ni ni-download"></em> Download
                                                                            </a>
                                                                        </div>
                                                                    @else
                                                                        <div style="margin-top: 8px;">
                                                                            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">
                                                                                <em class="icon ni ni-lock"></em> Login to Access
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                    
                                                                    @if(isset($relatedDoc->nested_related_documents) && $relatedDoc->nested_related_documents->count() > 0)
                                                                        <div class="nested-related-docs">
                                                                            <small><strong>Related Documents:</strong></small>
                                                                            @foreach($relatedDoc->nested_related_documents as $nestedDoc)
                                                                                <div class="nested-doc-item">
                                                                                    <div class="nested-doc-title">
                                                                                        <em class="icon ni ni-chevron-right"></em> {{ $nestedDoc->title }}
                                                                                    </div>
                                                                                    <div class="related-doc-meta">
                                                                                        @if($nestedDoc->document_version)
                                                                                            <span><strong>Version:</strong> {{ $nestedDoc->document_version }}</span>
                                                                                        @endif
                                                                                        @if($nestedDoc->effective_date)
                                                                                            <span><strong>Date:</strong> {{ \Carbon\Carbon::parse($nestedDoc->effective_date)->format('M. j, Y') }}</span>
                                                                                        @endif

                                                                                         @if($nestedDoc->effective_date)
                                                                                            <span><strong>Date:</strong> {{ \Carbon\Carbon::parse($nestedDoc->effective_date)->format('M. j, Y') }}</span>
                                                                                        @endif
                                                                                    </div>
                                                                                    @if($isSubscribed)
                                                                                        <div style="margin-top: 5px;">
                                                                                            <a href="{{ asset('public/pdf_documents/' . $nestedDoc->regulation_doc) }}" target="_blank" class="btn btn-xs btn-outline-primary">
                                                                                                <em class="icon ni ni-book-read"></em> View
                                                                                            </a>
                                                                                            <a href="{{ route('download', $nestedDoc->id) }}" class="btn btn-xs btn-outline-primary">
                                                                                                <em class="icon ni ni-download"></em> Download
                                                                                            </a>
                                                                                        </div>
                                                                                    @else
                                                                                        <div style="margin-top: 5px;">
                                                                                            <a href="{{ route('login') }}" class="btn btn-xs btn-outline-secondary">
                                                                                                <em class="icon ni ni-lock"></em> Login
                                                                                            </a>
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <p class="text-center text-muted">No related documents found.</p>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>

                        @endif
                        @if($reg->hasPages())
                        <div class="mt-4 d-flex justify-content-center">
                            <nav aria-label="Regulations pagination">
                                {{ $reg->onEachSide(1)->links('vendor.pagination.bootstrap-4') }}
                            </nav>
                        </div>
                        @endif
                        {{-- Pagination Info --}}
                       
                    </div>
                </div>
            </div>
            <br>


            <script>
                document.addEventListener('DOMContentLoaded', function() {
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
                                
                                // Implement page-based blur logic
                                if (actualPageCount === 1) {
                                    // 1-page docs: Show first 50% with rest blurred
                                    renderPartialPage(pdfDoc, 1, viewer, 0.5);
                                } else if (actualPageCount === 2) {
                                    // 2-page docs: Show 1 full page, blur entire second page
                                    renderFullPage(pdfDoc, 1, viewer, false);
                                    renderFullPage(pdfDoc, 2, viewer, true);
                                } else if (actualPageCount >= 3) {
                                    // 3+ page docs: Show first 1.5 pages, blur remaining
                                    renderFullPage(pdfDoc, 1, viewer, false);
                                    renderPartialPage(pdfDoc, 2, viewer, 0.5);
                                    // Blur remaining pages (limit to 5 for performance)
                                    for (var i = 3; i <= Math.min(actualPageCount, 5); i++) {
                                        renderFullPage(pdfDoc, i, viewer, true);
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
