<style>
     #{{ $tableId }} thead th span {
        display: none !important;
    }
    
    /* Responsive table styles */
    .table-responsive-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin-bottom: 15px;
    }
    
    .table-responsive-wrapper table {
        min-width: 100%;
        margin-bottom: 0;
    }
    
    /* Ensure table cells don't force content to break */
    .datatable-init th,
    .datatable-init td {
        word-break: break-word;
        overflow-wrap: break-word;
    }
    
    /* Stack columns on small screens */
    @media (max-width: 768px) {
        .table-responsive-wrapper table {
            font-size: 12px;
        }
        
        .datatable-init th,
        .datatable-init td {
            padding: 0.5rem !important;
            font-size: 11px;
        }
        
        .btn-sm, .btn-icon {
            padding: 0.25rem !important;
            font-size: 10px !important;
        }
    }
    
    /* Ensure badge text doesn't overflow */
    .badge {
        display: inline-block;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .modal-backdrop.show {
      opacity: 0.5;
    }
    
    .subscribe-modal .modal-content {
      border-radius: 15px;
      border: none;
      box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }
    
    .subscribe-modal .modal-header {
      border-bottom: none;
      padding: 2rem 2rem 1rem;
    }
    
    .subscribe-modal .modal-body {
      padding: 0 2rem 2rem;
    }
    
    .feature-list {
      list-style: none;
      padding: 0;
      margin: 1.5rem 0;
    }
    
    .feature-list li {
      padding: 0.5rem 0;
      display: flex;
      align-items: center;
    }
    
    .feature-list li::before {
      content: "✓";
      color: #198754;
      font-weight: bold;
      margin-right: 0.75rem;
      font-size: 1.2rem;
    }
    
        .btn-subscribe {
            padding: 0.75rem 2rem;
            font-size: 1.1rem;
            border-radius: 8px;
            background-color: #1d326d; /* requested deep blue */
            color: #ffffff !important; /* ensure white text */
            border: none;
            text-decoration: none;
        }
        .btn-subscribe:hover,
        .btn-subscribe:focus {
            background-color: #16284b; /* slightly darker on hover */
            color: #ffffff !important;
            text-decoration: none;
        }
        /* Force all text and icon elements inside the subscribe button to remain white in all states */
        .btn-subscribe,
        .btn-subscribe *,
        .btn-subscribe em,
        .btn-subscribe i,
        .btn-subscribe .icon,
        .btn-subscribe svg {
            color: #ffffff !important;
            fill: #ffffff !important;
        }
        /* Ensure anchor/link state specificity so text is white even before hover */
        a.btn-subscribe,
        a.btn-subscribe:link,
        a.btn-subscribe:visited,
        a.btn-subscribe:active {
            color: #ffffff !important;
            fill: #ffffff !important;
        }
        /* Make child elements inherit the color for safety */
        a.btn-subscribe * {
            color: inherit !important;
            fill: inherit !important;
        }
    
    .clickable-badge {
      cursor: pointer;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .clickable-badge:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    /* Accordion styles for related documents */
    .accordion-button:not(.collapsed) {
      background-color: #f8f9fa;
      color: #495057;
      font-weight: 500;
    }
    
    .accordion-button:focus {
      box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
      border-color: #86b7fe;
    }
    
    .related-doc-details p {
      margin-bottom: 0.5rem;
    }
    
    .nested-related-docs .card {
      border-left: 3px solid #0d6efd;
    }
    
    .nested-related-docs h6 {
      color: #495057;
      border-bottom: 1px solid #dee2e6;
      padding-bottom: 0.5rem;
      margin-bottom: 1rem;
    }
  </style>



@php
    // Expected variables:
    // $records: Illuminate\Pagination\LengthAwarePaginator|Collection of Regulation
    // $isSubscribed: bool
    // $years: array (optional) for filter initializer used by parent
    // $showFilters: bool (default: false) - whether to show filters
    // $filterOptions: array (optional) - options for filter configuration
    $showFilters = $showFilters ?? false;
    $filterOptions = $filterOptions ?? [];
    $tableId = $tableId ?? 'example';

  
@endphp

<div style="background-color: #fff; padding: 20px; width: 100%">
    <div class="row" style="width: 100%">
        <div class="col-md-12">
            @if($showFilters)
            <div class="filter-wrapper">
                @include('components.filters.table-filters', [
                    'records' => $records,
                    'tableId' => $tableId,
                    'options' => $filterOptions
                ])
            </div>
            @endif
                    @if (Auth::check())
                        @if ($isSubscribed || Auth::user()->usertype == 'internal')
                            <div class="table-responsive-wrapper">
                                <table id="example" class="datatable-init responsive table table-striped table-bordered table-hover" style="width:100%">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="text-center" style="min-width: 150px; white-space: nowrap;">Title</th>
                                        {{-- <th class="text-center d-none d-lg-table-cell">Category</th>--}}
                                        <th class="text-center d-none d-md-table-cell" style="min-width: 100px; white-space: nowrap;">Subcategory</th> 
                                        <!-- <th class="text-center d-none d-lg-table-cell" style="min-width: 100px; white-space: nowrap;">Version Number</th> -->
                                        <th class="text-center d-none d-md-table-cell" style="min-width: 100px; white-space: nowrap;">Issue Date</th>
                                        <th class="text-center d-none d-lg-table-cell" style="min-width: 80px; white-space: nowrap;">Year</th>
                                        <th class="text-center" style="min-width: 100px; white-space: nowrap;">Effective Date</th>
                                        <th class="text-center d-none d-lg-table-cell" style="min-width: 80px; white-space: nowrap;">Status</th>
                                        <th class="text-center d-none d-lg-table-cell" style="min-width: 100px; white-space: nowrap;">Status Date</th>
                                        <th class="text-center d-none d-lg-table-cell" style="min-width: 100px; white-space: nowrap;">Entity</th>
                                        <th class="text-center d-none d-xl-table-cell" style="min-width: 120px; white-space: nowrap;">Market Product</th>
                                        <th class="text-center d-none d-md-table-cell" style="min-width: 100px; white-space: nowrap;">Related Docs</th>
                                        <th class="text-center" style="min-width: 120px; white-space: nowrap;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($records as $result)
                                        <tr>
                                            <td class="align-middle" style="text-align:justify; max-width: 200px; word-wrap: break-word;">
                                                <a href="#" data-toggle="modal"
                                                    data-target="#pdfModal-{{ $result->id }}">
                                                    <div class="d-block d-sm-none" style="font-size: 0.9em;">
                                                        <div class="fw-bold">{{ Str::limit($result->title, 50) }}</div>
                                                    </div>
                                                    <div class="d-none d-sm-block">
                                                        {{ $result->title }} <em class="icon ni ni-zoom-in"></em>
                                                    </div>
                                                </a>
                                            </td>
                                            {{-- <td class="text-center align-middle">{{ optional($result->category)->name }}</td>--}}
                                            <td class="text-center align-middle">{{ optional($result->subcategory)->name }}</td> 
                                            
                                            <!-- <td class="text-center align-middle">{{ $result->document_version }}</td> -->
                                            <td class="text-center align-middle">
                                                {{ \Carbon\Carbon::parse($result->issue_date)->format('M. j, Y') }}
                                            </td>
                                            <td class="text-center align-middle">{{ $result->year->name }}</td>
                                            <td class="text-center align-middle">
                                                {{ \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y') }}
                                            </td>
                                             <td class="text-center align-middle">
                                                @if($result->ceased && $result->ceased !== 'Active')
                                                    <span class="badge badge-primary">{{ str_replace([',','/'], [', ', ' '], implode(', ', array_filter(explode(',', $result->ceased), function($status) { return trim($status) !== 'Active'; }))) }}</span>
                                                @endif
                                            </td>

                                             <td class="text-center align-middle">
                                                {{ \Carbon\Carbon::parse($result->ceased_date)->format('M. d, Y') }}
                                            </td>

                                            <td class="text-center align-middle">{{ optional($result->entity)->name }}</td>
                                            <td class="text-center align-middle">
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
                                                    <div class="d-none d-md-block">
                                                        @foreach($tags as $tag)
                                                            <span class="badge badge-info" style="margin: 0 2px;">{{ $tag->name }}</span>
                                                        @endforeach
                                                    </div>
                                                    <div class="d-md-none">
                                                        <span class="badge badge-info">Tags: {{ $tags->count() }}</span>
                                                    </div>
                                                @else
                                                    <span class="badge badge-secondary">None</span>
                                                @endif
                                            </td>
                                            
                                            {{-- Related Documents Column --}}
                                            <td style="text-align: center">
                            @if($result->related_docs || $result->nested_related_docs_column)
                                @php
                                    $relatedDocuments = $result->related_documents;
                                  
                                    if ($relatedDocuments instanceof \Illuminate\Support\Collection) {
                                        $relatedDocuments = $relatedDocuments->sortByDesc(function($doc) {
                                            return \Carbon\Carbon::parse($doc->effective_date);
                                        });
                                        
                                        // Remove current document ID from count
                                        $relatedDocuments = $relatedDocuments->filter(function($doc) use ($result) {
                                            return $doc->id != $result->id;
                                        });
                                    }
                                    $relatedCount = $relatedDocuments->count();
                                    
                                    
                                    $nestedRelatedCount = $result->nested_related_docs_column ? count(json_decode($result->nested_related_docs_column, true)) : 0;
                                    
                                    $totalCount = $relatedCount + $nestedRelatedCount;
                                @endphp
                                <span class="badge badge-primary" title="View related documents and lineage" style="cursor: pointer;" data-toggle="modal" data-target="#relatedDocsModal-{{ $result->id }}">{{ $totalCount }} related</span>
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
                                  <div class="modal fade related-docs-modal" id="relatedDocsModal-{{ $result->id }}" tabindex="-1" role="dialog" aria-labelledby="relatedDocsModalLabel-{{ $result->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="relatedDocsModalLabel-{{ $result->id }}">{{ $result->title }} ({{ $result->year->name }}) </h5>
                                    
                                    <span class="badge badge-primary" style="cursor: pointer;" data-toggle="modal" data-target="#activeRelatedDocsModal-{{ $result->id }}"> Jump to Active Version</span>
                                </div>
                                <div class="modal-body">
                                    @php 
                                        $relatedDocs = $result->relatedDocuments;
                                        // Sort related documents by effective_date in descending order
                                        if ($relatedDocs instanceof \Illuminate\Support\Collection) {
                                            $relatedDocs = $relatedDocs->sortByDesc(function($doc) {
                                                return \Carbon\Carbon::parse($doc->effective_date);
                                            });
                                        }
                                    @endphp
                                    
                                    @if($relatedDocs->count() > 0)
                                        <div class="accordion" id="relatedDocsAccordion-{{ $result->id }}">
                                            @foreach($relatedDocs as $index => $relatedDoc)
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="heading-{{ $result->id }}-{{ $index }}">
                                                        <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $result->id }}-{{ $index }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="collapse-{{ $result->id }}-{{ $index }}">
                                                            <div class="d-flex w-100 justify-content-between align-items-center">
                                                                <span>{{ $relatedDoc->title }} ({{ $relatedDoc->year->name }})</span>
                                                                <div>
                                                                    @if($relatedDoc->ceased)
                                                                        <span class="badge badge-danger ms-2"> {{ str_replace([',','/'], [', ', ' '], $relatedDoc->ceased) }}</span>
                                                                    @else
                                                                        <span class="badge badge-primary ms-2">Active</span>
                                                                    @endif
                                                                    @php
    // Get nested documents from the new column approach for this specific related document
    $nestedIdsFromColumn = $relatedDoc->nested_related_docs_column
        ? json_decode($relatedDoc->nested_related_docs_column, true)
        : [];

    $nestedDocsFromColumn = collect();

    foreach ($nestedIdsFromColumn as $nestedId) {
        $nestedDoc = \App\Models\Regulation::find($nestedId);

        if ($nestedDoc) {
            $nestedDoc->relationship_type = 'Nested Related';
            $nestedDocsFromColumn->push($nestedDoc);
        }
    }
    
    // Sort nested documents by effective_date in descending order
    $nestedDocsFromColumn = $nestedDocsFromColumn->sortByDesc(function($doc) {
        return \Carbon\Carbon::parse($doc->effective_date);
    });
    
    $totalNestedCount = $nestedDocsFromColumn->count();
    $oldNestedCount = isset($relatedDoc->nested_related_documents) ? $relatedDoc->nested_related_documents->count() : 0;
    $combinedNestedCount = $totalNestedCount + $oldNestedCount;
@endphp

{{-- @if($combinedNestedCount > 0)
    <span class="badge bg-secondary ms-2">+{{ $combinedNestedCount }} more</span>
@endif --}}
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse-{{ $result->id }}-{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" aria-labelledby="heading-{{ $result->id }}-{{ $index }}" data-bs-parent="#relatedDocsAccordion-{{ $result->id }}">
                                                        <div class="accordion-body">
                                                            <div class="related-doc-details">
                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        @if($relatedDoc->document_version)
                                                                            <p><strong>Version:</strong> {{ $relatedDoc->document_version }}</p>
                                                                        @endif
                                                                        @if($relatedDoc->effective_date)
                                                                            <p><strong>Effective Date:</strong> {{ \Carbon\Carbon::parse($relatedDoc->effective_date)->format('M. j, Y') }}</p>
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        @if($relatedDoc->entity)
                                                                            <p><strong>Entity:</strong> {{ $relatedDoc->entity->name }}</p>
                                                                        @endif
                                                                        <p><strong>Issue Date:</strong> {{ \Carbon\Carbon::parse($relatedDoc->issue_date)->format('M. j, Y') }}</p>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="mb-3">
                                                                    <a href="{{ asset('public/pdf_documents/' . $relatedDoc->regulation_doc) }}" target="_blank" class="btn btn-sm btn-primary me-2">
                                                                        <em class="icon ni ni-book-read"></em> View
                                                                    </a>
                                                                    <a href="{{ route('download', $relatedDoc->id) }}" class="btn btn-sm btn-outline-primary">
                                                                        <em class="icon ni ni-download"></em> Download
                                                                    </a>
                                                                </div>

                                                                @php
    // Get nested documents from the new column approach for this specific related document
    $nestedIdsFromColumn = $relatedDoc->nested_related_docs_column
        ? json_decode($relatedDoc->nested_related_docs_column, true)
        : [];

    $nestedDocsFromColumn = collect();

    foreach ($nestedIdsFromColumn as $nestedId) {
        $nestedDoc = \App\Models\Regulation::find($nestedId);

        if ($nestedDoc) {
            $nestedDoc->relationship_type = 'Nested Related';
            $nestedDocsFromColumn->push($nestedDoc);
        }
    }
    
    // Sort nested documents by effective_date in descending order
    $nestedDocsFromColumn = $nestedDocsFromColumn->sortByDesc(function($doc) {
        return \Carbon\Carbon::parse($doc->effective_date);
    });
@endphp

{{-- @if($nestedDocsFromColumn->count() > 0)
    <div class="nested-related-docs mt-4">
        <h6>Related Documents:</h6>
        @foreach($nestedDocsFromColumn as $nestedIndex => $nestedDoc)
            <div class="card mb-2">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">{{ $nestedDoc->title }}</h6>
                            <div class="small text-muted">
                                @if($nestedDoc->ceased)
                                    <span class="badge badge-danger"> {{ str_replace([',','/'], [', ', ' '], $nestedDoc->ceased) }}</span>
                                @else
                                    <span class="badge badge-primary">Active</span>
                                @endif
                                
                                @if($nestedDoc->document_version)
                                    <span class="ms-2"><strong>Version:</strong> {{ $nestedDoc->document_version }}</span>
                                @endif
                                @if($nestedDoc->effective_date)
                                    <span class="ms-2"><strong>Effective:</strong> {{ \Carbon\Carbon::parse($nestedDoc->effective_date)->format('M. j, Y') }}</span>
                                @endif
                                @if($nestedDoc->issue_date)
                                    <span class="ms-2"><strong>Issue:</strong> {{ \Carbon\Carbon::parse($nestedDoc->issue_date)->format('M. j, Y') }}</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <a href="{{ asset('public/pdf_documents/' . $nestedDoc->regulation_doc) }}" target="_blank" class="btn btn-xs btn-outline-primary me-1">
                                <em class="icon ni ni-book-read"></em>
                            </a>
                            <a href="{{ route('download', $nestedDoc->id) }}" class="btn btn-xs btn-outline-primary">
                                <em class="icon ni ni-download"></em>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
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

                    <!-- Active Related Documents Modal -->
                    <div class="modal fade related-docs-modal" id="activeRelatedDocsModal-{{ $result->id }}" tabindex="-1" role="dialog" aria-labelledby="activeRelatedDocsModalLabel-{{ $result->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="activeRelatedDocsModalLabel-{{ $result->id }}">Active Related Documents - {{ $result->title }}</h5>
                                </div>
                                <div class="modal-body">
                                    @php
                                        // Use the new recursive flattened search to find any active documents in the lineage
                                        $allRelatedDocs = $result->getFlattenedRelatedDocuments();
                                        
                                        // Sort related documents by effective_date in descending order, handling nulls
                                        if ($allRelatedDocs instanceof \Illuminate\Support\Collection) {
                                            $allRelatedDocs = $allRelatedDocs->sortByDesc(function($doc) {
                                                return $doc->effective_date ? \Carbon\Carbon::parse($doc->effective_date) : \Carbon\Carbon::now()->subYears(100);
                                            });
                                        }
                                        
                                        // Filter for active documents (where ceased is null, empty string, or 'Active')
                                        $activeRelatedDocs = ($allRelatedDocs instanceof \Illuminate\Support\Collection)
                                            ? $allRelatedDocs->filter(function($doc){ 
                                                return empty($doc->ceased) || $doc->ceased === 'Active'; 
                                            })
                                            : collect();
                                    @endphp
                                    @if($activeRelatedDocs->count() > 0)
                                        @foreach($activeRelatedDocs as $relatedDoc)
                                            <div class="related-doc-item">
                                                <div class="related-doc-title">
                                                    {{ $relatedDoc->title }}
                                                    @php
    // Get nested documents from the new column approach for this specific related document
    $nestedIdsFromColumn = $relatedDoc->nested_related_docs_column
        ? json_decode($relatedDoc->nested_related_docs_column, true)
        : [];

    $nestedDocsFromColumn = collect();

    foreach ($nestedIdsFromColumn as $nestedId) {
        $nestedDoc = \App\Models\Regulation::find($nestedId);

        if ($nestedDoc) {
            $nestedDoc->relationship_type = 'Nested Related';
            $nestedDocsFromColumn->push($nestedDoc);
        }
    }
    
    // Sort nested documents by effective_date in descending order
    $nestedDocsFromColumn = $nestedDocsFromColumn->sortByDesc(function($doc) {
        return \Carbon\Carbon::parse($doc->effective_date);
    });
    
    // Count active nested documents
    $activeNestedCount = $nestedDocsFromColumn->filter(function($d){ return empty($d->ceased) || $d->ceased === 'Active'; })->count();
@endphp

@if($nestedDocsFromColumn->count() > 0)
    @if($activeNestedCount > 0)
        <span class="nested-badge">+{{ $activeNestedCount }} active</span>
    @endif
@endif
                                                </div>
                                                <div class="related-doc-meta">
                                                    <span class="badge badge-primary">Active</span>
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
                                                    <a href="{{ asset('public/pdf_documents/' . $relatedDoc->regulation_doc) }}" target="_blank" class="btn btn-sm btn-primary">
                                                        <em class="icon ni ni-book-read"></em> View
                                                    </a>
                                                    <a href="{{ route('download', $relatedDoc->id) }}" class="btn btn-sm btn-primary">
                                                        <em class="icon ni ni-download"></em> Download
                                                    </a>
                                                </div>

                                                @if(isset($relatedDoc->nested_related_documents) && $relatedDoc->nested_related_documents->count() > 0)
                                                    @php 
                                                        $activeNestedDocs = $relatedDoc->nested_related_documents->filter(function($d){ return empty($d->ceased) || $d->ceased === 'Active'; });
                                                        // Sort active nested documents by effective_date in descending order
                                                        $activeNestedDocs = $activeNestedDocs->sortByDesc(function($doc) {
                                                            return \Carbon\Carbon::parse($doc->effective_date);
                                                        });
                                                    @endphp
                                                    @if($activeNestedDocs->count() > 0)
                                                        <div class="nested-related-docs">
                                                            <small><strong>Active Related Documents:</strong></small>
                                                            @foreach($activeNestedDocs as $nestedDoc)
                                                                <div class="nested-doc-item">
                                                                    <div class="nested-doc-title">
                                                                        <em class="icon ni ni-chevron-right"></em> {{ $nestedDoc->title }}
                                                                    </div>
                                                                    <div class="related-doc-meta">
                                                                        <span class="badge badge-primary">Active</span>
                                                                        @if($nestedDoc->document_version)
                                                                            <span><strong>Version:</strong> {{ $nestedDoc->document_version }}</span>
                                                                        @endif
                                                                        @if($nestedDoc->effective_date)
                                                                            <span><strong>Effective Date:</strong> {{ \Carbon\Carbon::parse($nestedDoc->effective_date)->format('M. j, Y') }}</span>
                                                                        @endif
                                                                        @if($nestedDoc->issue_date)
                                                                            <span><strong>Issue Date:</strong> {{ \Carbon\Carbon::parse($nestedDoc->issue_date)->format('M. j, Y') }}</span>
                                                                        @endif
                                                                    </div>
                                                                    <div style="margin-top: 5px;">
                                                                        <a href="{{ asset('public/pdf_documents/' . $nestedDoc->regulation_doc) }}" target="_blank" class="btn btn-xs btn-outline-primary">
                                                                            <em class="icon ni ni-book-read"></em> View
                                                                        </a>
                                                                        <a href="{{ route('download', $nestedDoc->id) }}" class="btn btn-xs btn-outline-primary">
                                                                            <em class="icon ni ni-download"></em> Download
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-center text-muted">No active related documents found.</p>
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
                            </div>
                        @else
                            <div class="table-responsive-wrapper">
                                <table id="example" class="datatable-init responsive table table-striped table-bordered table-hover" style="width:100%">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="text-center" style="min-width: 150px; white-space: nowrap;">Title</th>
                                        {{-- <th class="text-center d-none d-lg-table-cell">Category</th> --}}
                                        <th class="text-center d-none d-md-table-cell" style="min-width: 100px; white-space: nowrap;">Subcategory</th>
                                        <th class="text-center" style="min-width: 100px; white-space: nowrap;">Effective Date</th>
                                        <th class="text-center d-none d-lg-table-cell" style="min-width: 100px; white-space: nowrap;">Entity</th>
                                        <th class="text-center d-none d-md-table-cell" style="min-width: 100px; white-space: nowrap;">Related Docs</th>
                                        <th class="text-center" style="display: none;"><span>Entity</span></th>
                                        <th class="text-center" style="min-width: 120px; white-space: nowrap;"><span>Action</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($records as $result)
                                        <tr>
                                            <td class="align-middle" style="text-align: justify; max-width: 200px; word-wrap: break-word;">
                                                <a href="#" data-toggle="modal"
                                                    data-target="#pdfModal-{{ $result->id }}">
                                                    <div class="d-block d-sm-none" style="font-size: 0.9em;">
                                                        <div class="fw-bold">{{ Str::limit($result->title, 50) }}</div>
                                                    </div>
                                                    <div class="d-none d-sm-block">
                                                        {{ $result->title }} <em class="icon ni ni-zoom-in"></em>
                                                    </div>
                                                </a>
                                            </td>
                                            {{-- <td class="text-center align-middle">{{ optional($result->category)->name }}</td> --}}
                                            <td class="text-center align-middle">{{ optional($result->subcategory)->name }}</td>
                                            <td class="text-center align-middle">
                                                {{ \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y') }}
                                            </td>
                                            <td class="text-center align-middle">{{ optional($result->entity)->name }}</td>
                                            <td class="text-center align-middle">
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
                                                    <div class="d-none d-md-block">
                                                        @foreach($tags as $tag)
                                                            <span class="badge badge-info" style="margin: 0 2px;">{{ $tag->name }}</span>
                                                        @endforeach
                                                    </div>
                                                    <div class="d-md-none">
                                                        <span class="badge badge-info">Tags: {{ $tags->count() }}</span>
                                                    </div>
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
                                                                                   <span class="badge badge-primary" title="View related documents and lineage" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#subscribeModal">{{ $relatedCount }} related</span>

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
                                                            <a href="#"  data-bs-toggle="modal" data-bs-target="#subscribeModal"
                                                                class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                <em class="icon ni ni-book-read"></em>
                                                            </a>
                                                            <a href="#"  data-bs-toggle="modal" data-bs-target="#subscribeModal"
                                                                class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                                    class="icon ni ni-download"></em></a>

                                                                     <a href="#"  data-bs-toggle="modal" data-bs-target="#subscribeModal"
                                                        class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                            class="icon ni ni-save"></em></a>
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
                                                                                            @if($relatedDoc->ceased && $relatedDoc->ceased !== 'Active')
                                                <span class="badge badge-success">{{ str_replace([',','/'], [', ', ' '], implode(', ', array_filter(explode(',', $relatedDoc->ceased), function($status) { return trim($status) !== 'Active'; }))) }}</span>
                                                @else
                                                <span class="badge badge-secondary">Active</span>
                                                @endif
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
                                                                                        @php
                                                                                            // Sort nested documents by effective_date in descending order
                                                                                            $sortedNestedDocs = $relatedDoc->nested_related_documents->sortByDesc(function($doc) {
                                                                                                return \Carbon\Carbon::parse($doc->effective_date);
                                                                                            });
                                                                                        @endphp
                                                                                        @include('partials.nested-related-documents', [
                                                                                            'nestedDocuments' => $sortedNestedDocs,
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
                                                                                        @php
                                                                                            // Sort nested documents by effective_date in descending order
                                                                                            $sortedNestedDocs = $relatedDoc->nested_related_documents->sortByDesc(function($doc) {
                                                                                                return \Carbon\Carbon::parse($doc->effective_date);
                                                                                            });
                                                                                        @endphp
                                                                                        @include('partials.nested-related-documents', [
                                                                                            'nestedDocuments' => $sortedNestedDocs,
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
                            </div>

                            <div class="modal fade subscribe-modal" id="subscribeModal" tabindex="-1" aria-labelledby="subscribeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="subscribeModalLabel">🔒 Subscribe to Access This Document</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="text-muted mb-3">Upgrade your account to unlock premium documents and features.</p>
          
          <ul class="feature-list">
            <li>Access to all premium documents</li>
            <li>Unlimited downloads</li>
           
          </ul>
          
          <div class="d-grid gap-2">
            <a href="{{ route('subscribe') }}" class="btn btn-primary btn-subscribe" style="">Subscribe Now</a>
           
          </div>
          
          
        </div>
        <br>
        
       
      </div>
    </div>
  </div>
                        @endif
                    @else
                        <div class="table-responsive-wrapper">
                            <table id="example" class="datatable-init responsive table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" style="min-width: 150px; white-space: nowrap;">Title</th>
                                    {{-- <th class="text-center d-none d-lg-table-cell" style="min-width: 100px; white-space: nowrap;">Category</th>
                                    <th class="text-center d-none d-md-table-cell" style="min-width: 100px; white-space: nowrap;">Subcategory</th> --}}
                                    <th class="text-center" style="min-width: 100px; white-space: nowrap;">Effective Date</th>
                                    <th class="text-center d-none d-lg-table-cell" style="min-width: 100px; white-space: nowrap;">Entity</th>
                                    <th class="text-center d-none d-md-table-cell" style="min-width: 100px; white-space: nowrap;">Related Docs</th>
                                    <th style="text-align: center;"><span style=" display:none">Entity</span></th>
                                    <th class="text-center" style="min-width: 120px; white-space: nowrap;"><span>Action</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $result)
                                    <tr>
                                        <td class="align-middle" style="text-align: justify; max-width: 200px; word-wrap: break-word;">
                                            <a href="#" data-toggle="modal"
                                                data-target="#pdfModal-{{ $result->id }}">
                                                    <div class="d-block d-sm-none" style="font-size: 0.9em;">
                                                        <div class="fw-bold">{{ Str::limit($result->title, 50) }}</div>
                                                    </div>
                                                    <div class="d-none d-sm-block">
                                                        {{ $result->title }} <em class="icon ni ni-zoom-in"></em>
                                                    </div>
                                            </a>
                                        </td>
                                        {{-- <td style="text-align: center">{{ optional($result->category)->name }}</td>
                                        <td style="text-align: center">{{ optional($result->subcategory)->name }}</td> --}}
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
                                                    // Sort related documents by effective_date in descending order
                                                    if ($relatedDocuments instanceof \Illuminate\Support\Collection) {
                                                        $relatedDocuments = $relatedDocuments->sortByDesc(function($doc) {
                                                            return \Carbon\Carbon::parse($doc->effective_date);
                                                        });
                                                    }
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
                                            <!-- Desktop view buttons -->
                                            <div style="display: flex !important; align-items: center; justify-content: center" class="tb-odr-btns d-none d-sm-inline">
                                                @if ($isSubscribed)
                                                    <a href="{{ asset('public/pdf_documents/' . $result->regulation_doc) }}"
                                                        target="_blank"
                                                        class="btn btn-icon btn-white btn-dim btn-sm btn-primary" title="View">
                                                        <em class="icon ni ni-book-read"></em>
                                                    </a>
                                                    <a href="{{ route('download', $result->id) }}"
                                                        class="btn btn-icon btn-white btn-dim btn-sm btn-primary" title="Download"><em
                                                            class="icon ni ni-download"></em></a>
                                                    <a href="#" id="submit"
                                                        onclick="document.getElementById('save-{{ $result->id }}').submit();"
                                                        class="btn btn-icon btn-white btn-dim btn-sm btn-primary" title="Save"><em
                                                        class="icon ni ni-save"></em></a>
                                                @else
                                                    @if (Auth::check())
                                                        <a href="{{ route('subscribe') }}" target="_blank"
                                                            class="btn btn-icon btn-white btn-dim btn-sm btn-primary" title="Subscribe">
                                                            <em class="icon ni ni-lock"></em>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('login') }}" target="_blank"
                                                            class="btn btn-icon btn-white btn-dim btn-sm btn-primary" title="Login">
                                                            <em class="icon ni ni-lock"></em>
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>
                                            
                                            <!-- Mobile view buttons -->
                                            <div class="d-sm-none d-flex justify-content-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    @if ($isSubscribed)
                                                        <a href="{{ asset('public/pdf_documents/' . $result->regulation_doc) }}"
                                                            target="_blank"
                                                            class="btn btn-outline-primary btn-sm" title="View">
                                                            <em class="icon ni ni-book-read"></em>
                                                        </a>
                                                        <a href="{{ route('download', $result->id) }}"
                                                            class="btn btn-outline-primary btn-sm" title="Download">
                                                            <em class="icon ni ni-download"></em>
                                                        </a>
                                                        <a href="#" id="mobile-submit-{{ $result->id }}"
                                                            onclick="document.getElementById('save-{{ $result->id }}').submit();"
                                                            class="btn btn-outline-primary btn-sm" title="Save">
                                                            <em class="icon ni ni-save"></em>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('subscribe') }}" target="_blank"
                                                            class="btn btn-outline-warning btn-sm" title="Subscribe">
                                                            <em class="icon ni ni-lock"></em>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <form id="save-{{ $result->id }}"
                                                action="{{ route('save-document', $result->id) }}" method="POST"
                                                class="d-none" style="display: none">
                                                @csrf
                                            </form>
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
                                                        // Sort related documents by effective_date in descending order
                                                        if ($relatedDocuments instanceof \Illuminate\Support\Collection) {
                                                            $relatedDocuments = $relatedDocuments->sortByDesc(function($doc) {
                                                                return \Carbon\Carbon::parse($doc->effective_date);
                                                            });
                                                        }
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
                                                                                         {{ $relatedDoc->title }} ({{ optional($relatedDoc->year)->name ?? 'N/A' }})
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
                                                                                        @if($relatedDoc->ceased && $relatedDoc->ceased !== 'Active')
                                                <span class="badge badge-success">{{ str_replace([',','/'], [', ', ' '], implode(', ', array_filter(explode(',', $relatedDoc->ceased), function($status) { return trim($status) !== 'Active'; }))) }}</span>
                                                @else
                                                <span class="badge badge-secondary">Active</span>
                                                @endif
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
                                                                                    @php
                                                                                        // Sort nested documents by effective_date in descending order
                                                                                        $sortedNestedDocs = $relatedDoc->nested_related_documents->sortByDesc(function($doc) {
                                                                                            return \Carbon\Carbon::parse($doc->effective_date);
                                                                                        });
                                                                                    @endphp
                                                                                    @include('partials.nested-related-documents', [
                                                                                        'nestedDocuments' => $sortedNestedDocs,
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
                                                                                    @php
                                                                                        // Sort nested documents by effective_date in descending order
                                                                                        $sortedNestedDocs = $relatedDoc->nested_related_documents->sortByDesc(function($doc) {
                                                                                            return \Carbon\Carbon::parse($doc->effective_date);
                                                                                        });
                                                                                    @endphp
                                                                                    @include('partials.nested-related-documents', [
                                                                                        'nestedDocuments' => $sortedNestedDocs,
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
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="gda-cards-container">
                
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var isPrivileged = {!! ($isSubscribed || (Auth::check() && Auth::user()->usertype == 'internal')) ? 'true' : 'false' !!};
                    @foreach ($records as $result)
                        (function(id) {
                            var url = '{{ asset("public/pdf_documents/$result->regulation_doc") }}';
                            var pageCount = 0;
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
                                if (!isPrivileged) {
                                    // For non-privileged users, implement the preview restrictions
                                    if (actualPageCount === 1) {
                                        // 1-page document: Show first 3 lines or 50% and blur the rest
                                        renderPartialPage(pdfDoc, 1, viewer, 0.5);
                                    } else if (actualPageCount === 2) {
                                        // 2-page document: Show 1 full page and blur entire second page
                                        renderFullPage(pdfDoc, 1, viewer, false);
                                        renderFullPage(pdfDoc, 2, viewer, true);
                                    } else if (actualPageCount >= 3) {
                                        // 3+ page document: Show first 1.5 pages and blur all remaining pages
                                        renderFullPage(pdfDoc, 1, viewer, false); // Full first page
                                        renderPartialPage(pdfDoc, 2, viewer, 0.5); // 50% of second page
                                        // Blur all remaining pages (up to 5 pages max)
                                        for (var i = 3; i <= Math.min(actualPageCount, 5); i++) {
                                            renderFullPage(pdfDoc, i, viewer, true);
                                        }
                                    }
                                } else {
                                    // For privileged users, show full pages without restrictions
                                    // Show up to 5 pages maximum
                                    var maxPagesToShow = Math.min(actualPageCount, 5);
                                    for (var i = 1; i <= maxPagesToShow; i++) {
                                        renderFullPage(pdfDoc, i, viewer, false);
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
                                        // Completely hide the hidden portion
                                        if (visibleRatio < 1) {
                                            var ctx = canvas.getContext('2d');
                                            var height = canvas.height;
                                            var hiddenStart = height * visibleRatio;
                                            
                                            // Draw a solid white rectangle to completely hide the content
                                            ctx.fillStyle = 'white';
                                            ctx.globalAlpha = 1;
                                            ctx.fillRect(0, hiddenStart, canvas.width, height - hiddenStart);
                                            
                                            // Add gradient mask for smoother transition
                                            var gradient = ctx.createLinearGradient(0, hiddenStart - 20, 0, hiddenStart);
                                            gradient.addColorStop(0, 'rgba(255, 255, 255, 0)');
                                            gradient.addColorStop(1, 'rgba(255, 255, 255, 1)');
                                            ctx.fillStyle = gradient;
                                            ctx.fillRect(0, hiddenStart - 20, canvas.width, 20);
                                        }
                                    });
                                });
                            }
                        })({{ $result->id }});
                    @endforeach
                });
            </script>