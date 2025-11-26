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
        <table id="{{ $tableId }}" class="datatable-init responsive table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th style="text-align: center;">Title</th>
                    <th style="text-align: center;">Category</th>
                    <th style="text-align: center;">Subcategory</th>
                    <th style="text-align: center;">Version Number</th>
                    <th style="text-align: center;">Issue Date</th>
                    <th style="text-align: center;">Year</th>
                    <th style="text-align: center;">Effective Date</th>
                    <th style="text-align: center;">Entity</th>
                    <th style="text-align: center;">Market Product</th>
                    <th style="text-align: center;">Related Docs</th>
                    <th style="text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $result)
                    <tr>
                        <td>
                            @if ($result->doc_preview == 1)
                                <a href="#" data-toggle="modal" data-target="#pdfModal-{{ $result->id }}">
                                    {{ $result->formatted_title ?? $result->title }} <em class="icon ni ni-zoom-in"></em>
                                </a>
                            @else
                                {{ $result->formatted_title ?? $result->title }}
                            @endif
                        </td>
                        <td style="text-align: center">{{ optional($result->category)->name }}</td>
                        <td style="text-align: center">{{ optional($result->subcategory)->name }}</td>
                        <td style="text-align: center">{{ $result->document_version }}</td>
                        <td style="text-align: center">{{ \Carbon\Carbon::parse($result->issue_date)->format('M. j, Y') }}</td>
                        <td style="text-align: center">{{ optional($result->year)->name }}</td>
                        <td style="text-align: center">{{ \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y') }}</td>
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
                        <td style="text-align: center">
                            @if($result->related_docs)
                                @php
                                    $relatedDocuments = $result->related_documents;
                                    // Sort related documents by issue_date in descending order
                                    if ($relatedDocuments instanceof \Illuminate\Support\Collection) {
                                        $relatedDocuments = $relatedDocuments->sortByDesc(function($doc) {
                                            return \Carbon\Carbon::parse($doc->issue_date);
                                        });
                                    }
                                    $relatedCount = $relatedDocuments->count();
                                @endphp
                                <span class="badge badge-primary" title="View related documents and lineage" style="cursor: pointer;" data-toggle="modal" data-target="#relatedDocsModal-{{ $result->id }}">{{ $relatedCount }} related</span>
                            @else
                                <span class="badge badge-secondary">None</span>
                            @endif
                        </td>
                        <td class="tb-odr-action" style="display: flex !important; align-items: center; justify-content: center">
                            <div style="display: flex !important; align-items: center; justify-content: center" class="tb-odr-btns d-none d-sm-inline">
                                <a href="{{ asset('public/pdf_documents/' . $result->regulation_doc) }}" target="_blank" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                    <em class="icon ni ni-book-read"></em>
                                </a>
                                <a href="{{ route('download', $result->id) }}" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                    <em class="icon ni ni-download"></em>
                                </a>
                                <a href="#" id="submit" onclick="document.getElementById('save-{{ $result->id }}').submit();" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                    <em class="icon ni ni-save"></em>
                                </a>
                                <form id="save-{{ $result->id }}" action="{{ route('save-document', $result->id) }}" method="POST" class="d-none" style="display: none">
                                    @csrf
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- PDF Preview Modal -->
                    <div class="modal fade" id="pdfModal-{{ $result->id }}" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel-{{ $result->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="pdfModalLabel-{{ $result->id }}">Document Preview</h5>
                                </div>
                                <div class="modal-body">
                                    <div id="pdf-viewer-{{ $result->id }}"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Related Documents Modal -->
                    <div class="modal fade related-docs-modal" id="relatedDocsModal-{{ $result->id }}" tabindex="-1" role="dialog" aria-labelledby="relatedDocsModalLabel-{{ $result->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="relatedDocsModalLabel-{{ $result->id }}">{{ $result->title }}  </h5>
                                    
                                    <span class="badge badge-primary" style="cursor: pointer;" data-toggle="modal" data-target="#activeRelatedDocsModal-{{ $result->id }}"> Jump to Active Version</span>
                                </div>
                                <div class="modal-body">
                                    @php 
                                        $relatedDocs = $result->relatedDocuments;
                                        // Sort related documents by issue_date in descending order
                                        if ($relatedDocs instanceof \Illuminate\Support\Collection) {
                                            $relatedDocs = $relatedDocs->sortByDesc(function($doc) {
                                                return \Carbon\Carbon::parse($doc->issue_date);
                                            });
                                        }
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
                                                    @if($relatedDoc->ceased)
                                                        <span class="badge badge-danger">{{ $relatedDoc->ceased }}</span>
                                                    @else
                                                        <span class="badge badge-primary">Active</span>
                                                    @endif
                                                    @if($relatedDoc->document_version)
                                                        <span><strong>Version:</strong> {{ $relatedDoc->document_version }}</span>
                                                    @endif
                                                    @if($relatedDoc->effective_date)
                                                        <span><strong>Effective Date:</strong> {{ \Carbon\Carbon::parse($relatedDoc->effective_date)->format('M. j, Y') }}</span>
                                                    @endif
                                                    @if($relatedDoc->entity)
                                                        <span><strong>Entity:</strong> {{ $relatedDoc->entity->name }}</span>
                                                    @endif
                                                    
                                                    <span><strong>Issue Date:</strong> {{ \Carbon\Carbon::parse($relatedDoc->issue_date)->format('M. j, Y') }}</span>
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
                                                    <div class="nested-related-docs">
                                                        <small><strong>Related Documents:</strong></small>
                                                        @foreach($relatedDoc->nested_related_documents as $nestedDoc)
                                                            <div class="nested-doc-item">
                                                                <div class="nested-doc-title">
                                                                    <em class="icon ni ni-chevron-right"></em> {{ $nestedDoc->title }}
                                                                </div>
                                                                <div class="related-doc-meta">
                                                                    @if($nestedDoc->ceased)
                                                                        <span class="badge badge-danger">{{ $nestedDoc->ceased }}</span>
                                                                    @else
                                                                        <span class="badge badge-primary">Active</span>
                                                                    @endif
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

                    <!-- Active Related Documents Modal -->
                    <div class="modal fade related-docs-modal" id="activeRelatedDocsModal-{{ $result->id }}" tabindex="-1" role="dialog" aria-labelledby="activeRelatedDocsModalLabel-{{ $result->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="activeRelatedDocsModalLabel-{{ $result->id }}">Active Related Documents - {{ $result->title }}</h5>
                                </div>
                                <div class="modal-body">
                                    @php
                                        $allRelatedDocs = $result->relatedDocuments;
                                        // Sort related documents by issue_date in descending order
                                        if ($allRelatedDocs instanceof \Illuminate\Support\Collection) {
                                            $allRelatedDocs = $allRelatedDocs->sortByDesc(function($doc) {
                                                return \Carbon\Carbon::parse($doc->issue_date);
                                            });
                                        }
                                        $activeRelatedDocs = ($allRelatedDocs instanceof \Illuminate\Support\Collection)
                                            ? $allRelatedDocs->filter(function($doc){ return is_null($doc->ceased); })
                                            : collect();
                                    @endphp
                                    @if($activeRelatedDocs->count() > 0)
                                        @foreach($activeRelatedDocs as $relatedDoc)
                                            <div class="related-doc-item">
                                                <div class="related-doc-title">
                                                    {{ $relatedDoc->title }}
                                                    @if(isset($relatedDoc->nested_related_documents) && $relatedDoc->nested_related_documents->count() > 0)
                                                        @php
                                                            $activeNestedCount = $relatedDoc->nested_related_documents->filter(function($d){ return is_null($d->ceased); })->count();
                                                        @endphp
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
                                                    @php $activeNestedDocs = $relatedDoc->nested_related_documents->filter(function($d){ return is_null($d->ceased); }); @endphp
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
                @endforeach
            </tbody>
        </table>
    @else
        <table id="{{ $tableId }}" class="datatable-init responsive table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th style="text-align: center;">Title</th>
                    <th style="text-align: center;">Category</th>
                    <th style="text-align: center;">Subcategory</th>
                    <th style="text-align: center;">Version Number</th>
                    <th style="text-align: center;">Issue Date</th>
                    <th style="text-align: center;">Year</th>
                    <th style="text-align: center;">Effective Date</th>
                    <th style="text-align: center;">Entity</th>
                    <th style="text-align: center;">Market Product</th>
                    <th style="text-align: center;">Related Docs</th>
                    <th style="text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $result)
                    <tr>
                        <td>
                            @if ($result->doc_preview == 1)
                                <a href="#" data-toggle="modal" data-target="#pdfModal-{{ $result->id }}">
                                    {{ $result->formatted_title ?? $result->title }} <em class="icon ni ni-zoom-in"></em>
                                </a>
                            @else
                                {{ $result->formatted_title ?? $result->title }}
                            @endif
                        </td>
                         <td style="text-align: center">
                            {{ optional($result->category)->name }}
                        </td>
                        <td style="text-align: center">
                            {{ optional($result->subcategory)->name }}
                        </td>
                        <td style="text-align: center">{{ $result->document_version }}</td>
                        <td style="text-align: center">{{ \Carbon\Carbon::parse($result->issue_date)->format('M. j, Y') }}</td>
                        <td style="text-align: center">{{ optional($result->year)->name }}</td>
                        <td style="text-align: center">{{ \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y') }}</td>
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
                        <td style="text-align: center">
                            @if($result->related_docs)
                                @php
                                    $relatedDocuments = $result->related_documents;
                                    // Sort related documents by issue_date in descending order
                                    if ($relatedDocuments instanceof \Illuminate\Support\Collection) {
                                        $relatedDocuments = $relatedDocuments->sortByDesc('issue_date');
                                    }
                                    $relatedCount = $relatedDocuments->count();
                                @endphp
                                <span class="badge badge-primary" title="View related documents and lineage" style="cursor: pointer;" data-toggle="modal" data-target="#relatedDocsModal-{{ $result->id }}">{{ $relatedCount }} related</span>
                            @else
                                <span class="badge badge-secondary">None</span>
                            @endif
                        </td>
                        <td class="tb-odr-action" style="display: flex !important; align-items: center; justify-content: center">
                            <div style="display: flex !important; align-items: center; justify-content: center" class="tb-odr-btns d-none d-sm-inline">
                                @if ($isSubscribed)
                                    <a href="{{ asset('public/pdf_documents/' . $result->regulation_doc) }}" target="_blank" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                        <em class="icon ni ni-book-read"></em>
                                    </a>
                                    <a href="{{ route('download', $result->id) }}" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                        <em class="icon ni ni-download"></em>
                                    </a>
                                @else
                                    @if (Auth::check())
                                        <a href="{{ route('subscribe') }}" target="_blank" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                            <em class="icon ni ni-book-read"></em>
                                        </a>
                                        <a href="{{ route('subscribe') }}" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                            <em class="icon ni ni-download"></em>
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" target="_blank" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                            <em class="icon ni ni-book-read"></em>
                                        </a>
                                        <a href="{{ route('login') }}" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                            <em class="icon ni ni-download"></em>
                                        </a>
                                    @endif
                                @endif
                                <a href="#" id="submit" onclick="document.getElementById('save-{{ $result->id }}').submit();" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                    <em class="icon ni ni-save"></em>
                                </a>
                                <form id="save-{{ $result->id }}" action="{{ route('save-document', $result->id) }}" method="POST" class="d-none" style="display: none">
                                    @csrf
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- PDF Preview Modal -->
                    <div class="modal fade" id="pdfModal-{{ $result->id }}" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel-{{ $result->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="pdfModalLabel-{{ $result->id }}">PDF Preview</h5>
                                </div>
                                <div class="modal-body">
                                    <div id="pdf-viewer-{{ $result->id }}">
                                        <canvas id="canvas-page1-{{ $result->id }}" class="pdf-page"></canvas>
                                        <canvas id="canvas-page2-{{ $result->id }}" class="pdf-page"></canvas>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Related Documents Modal -->
                    <div class="modal fade related-docs-modal" id="relatedDocsModal-{{ $result->id }}" tabindex="-1" role="dialog" aria-labelledby="relatedDocsModalLabel-{{ $result->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="relatedDocsModalLabel-{{ $result->id }}">{{ $result->title }} -  </h5>
                                    <span class="badge badge-primary" style="cursor: pointer;" data-toggle="modal" data-target="#activeRelatedDocsModal-{{ $result->id }}"> Jump to Active Version</span>
                                </div>
                                <div class="modal-body">
                                    @php 
                                    $relatedDocs = $result->relatedDocuments;
                                    // Sort related documents by issue_date in descending order
                                    if ($relatedDocs instanceof \Illuminate\Support\Collection) {
                                        $relatedDocs = $relatedDocs->sortByDesc(function($doc) {
                                            return \Carbon\Carbon::parse($doc->issue_date);
                                        });
                                    }
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
                                                    @if($relatedDoc->ceased)
                                                        <span class="badge badge-danger">{{ $relatedDoc->ceased }}</span>
                                                    @else
                                                        <span class="badge badge-primary">Active</span>
                                                    @endif
                                                    @if($relatedDoc->document_version)
                                                        <span><strong>Version:</strong> {{ $relatedDoc->document_version }}</span>
                                                    @endif
                                                    @if($relatedDoc->effective_date)
                                                        <span><strong>Effective Date:</strong> {{ \Carbon\Carbon::parse($relatedDoc->effective_date)->format('M. j, Y') }}</span>
                                                    @endif
                                                    @if($relatedDoc->entity)
                                                        <span><strong>Entity:</strong> {{ $relatedDoc->entity->name }}</span>
                                                    @endif

                                                    @if($relatedDoc->issue_date)
                                                        <span><strong>Issue Date:</strong> {{ \Carbon\Carbon::parse($relatedDoc->issue_date)->format('M. j, Y') }}</span>
                                                    @endif
                                                </div>
                                                <div style="margin-top: 8px;">
                                                    @if ($isSubscribed)
                                                        <a href="{{ asset('public/pdf_documents/' . $relatedDoc->regulation_doc) }}" target="_blank" class="btn btn-sm btn-primary"><em class="icon ni ni-book-read"></em> View</a>
                                                        <a href="{{ route('download', $relatedDoc->id) }}" class="btn btn-sm btn-primary"><em class="icon ni ni-download"></em> Download</a>
                                                    @else
                                                        <a href="{{ route('subscribe') }}" class="btn btn-sm btn-outline-primary"><em class="icon ni ni-lock"></em> Restricted, subscribe to access</a>
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
                                                                    @if($nestedDoc->ceased)
                                                                        <span class="badge badge-danger">{{ $nestedDoc->ceased }}</span>
                                                                    @else
                                                                        <span class="badge badge-primary">Active</span>
                                                                    @endif
                                                                    @if($nestedDoc->document_version)
                                                                        <span><strong>Version:</strong> {{ $nestedDoc->document_version }}</span>
                                                                    @endif
                                                                    @if($nestedDoc->effective_date)
                                                                        <span><strong>Effective Date:</strong> {{ \Carbon\Carbon::parse($nestedDoc->effective_date)->format('M. j, Y') }}</span>
                                                                    @endif
                                                                </div>
                                                                <div style="margin-top: 5px;">
                                                                    @if($isSubscribed)
                                                                        <a href="{{ asset('public/pdf_documents/' . $nestedDoc->regulation_doc) }}" target="_blank" class="btn btn-xs btn-outline-primary"><em class="icon ni ni-book-read"></em> View</a>
                                                                        <a href="{{ route('download', $nestedDoc->id) }}" class="btn btn-xs btn-outline-primary"><em class="icon ni ni-download"></em> Download</a>
                                                                    @else
                                                                        <a href="{{ route('subscribe') }}" class="btn btn-sm btn-outline-primary"><em class="icon ni ni-lock"></em> Restricted, subscribe to access</a>
                                                                    @endif
                                                                </div>
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

                    <!-- Active Related Documents Modal -->
                    <div class="modal fade related-docs-modal" id="activeRelatedDocsModal-{{ $result->id }}" tabindex="-1" role="dialog" aria-labelledby="activeRelatedDocsModalLabel-{{ $result->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="activeRelatedDocsModalLabel-{{ $result->id }}">Active Related Documents - {{ $result->title }}</h5>
                                </div>
                                <div class="modal-body">
                                    @php
                                        $allRelatedDocs = $result->relatedDocuments;
                                        // Sort related documents by issue_date in descending order
                                        if ($allRelatedDocs instanceof \Illuminate\Support\Collection) {
                                            $allRelatedDocs = $allRelatedDocs->sortByDesc(function($doc) {
                                                return \Carbon\Carbon::parse($doc->issue_date);
                                            });
                                        }
                                        $activeRelatedDocs = ($allRelatedDocs instanceof \Illuminate\Support\Collection) ? $allRelatedDocs->filter(function($doc){ return is_null($doc->ceased); }) : collect();
                                    @endphp
                                    @if($activeRelatedDocs->count() > 0)
                                        @foreach($activeRelatedDocs as $relatedDoc)
                                            <div class="related-doc-item">
                                                <div class="related-doc-title">{{ $relatedDoc->title }}
                                                    @if(isset($relatedDoc->nested_related_documents) && $relatedDoc->nested_related_documents->count() > 0)
                                                        @php $activeNestedCount = $relatedDoc->nested_related_documents->filter(function($d){ return is_null($d->ceased); })->count(); @endphp
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
                                                    @if ($isSubscribed)
                                                        <a href="{{ asset('public/pdf_documents/' . $relatedDoc->regulation_doc) }}" target="_blank" class="btn btn-sm btn-primary"><em class="icon ni ni-book-read"></em> View</a>
                                                        <a href="{{ route('download', $relatedDoc->id) }}" class="btn btn-sm btn-primary"><em class="icon ni ni-download"></em> Download</a>
                                                    @else
                                                        <a href="{{ route('subscribe') }}" class="btn btn-sm btn-outline-primary"><em class="icon ni ni-lock"></em> Restricted, subscribe to access</a>
                                                    @endif
                                                </div>

                                                @php $activeNestedDocs = $relatedDoc->nested_related_documents->filter(function($d){ return is_null($d->ceased); }); @endphp
                                                @if(isset($relatedDoc->nested_related_documents) && $activeNestedDocs->count() > 0)
                                                    <div class="nested-related-docs">
                                                        <small><strong>Active Related Documents:</strong></small>
                                                        @foreach($activeNestedDocs as $nestedDoc)
                                                            <div class="nested-doc-item">
                                                                <div class="nested-doc-title"><em class="icon ni ni-chevron-right"></em> {{ $nestedDoc->title }}</div>
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
                                                                    @if($isSubscribed)
                                                                        <a href="{{ asset('public/pdf_documents/' . $nestedDoc->regulation_doc) }}" target="_blank" class="btn btn-xs btn-outline-primary"><em class="icon ni ni-book-read"></em> View</a>
                                                                        <a href="{{ route('download', $nestedDoc->id) }}" class="btn btn-xs btn-outline-primary"><em class="icon ni ni-download"></em> Download</a>
                                                                    @else
                                                                        <a href="{{ route('subscribe') }}" class="btn btn-sm btn-outline-primary"><em class="icon ni ni-lock"></em> Restricted, subscribe to access</a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
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
                @endforeach
            </tbody>
        </table>
    @endif
@else
    {{-- Guest view (no Auth user) --}}
    <table id="{{ $tableId }}" class="datatable-init responsive table table-striped" style="width:100%">
        <thead>
            <tr>
                <th style="text-align: center;">Title</th>
                <th style="text-align: center;">Version Number</th>
                <th style="text-align: center;">Issue Date</th>
                <th style="text-align: center;">Year</th>
                <th style="text-align: center;">Effective Date</th>
                <th style="text-align: center;">Entity</th>
                <th style="text-align: center;">Market Product</th>
                <th style="text-align: center;">Related Docs</th>
                <th style="text-align: center;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $result)
                <tr>
                    <td>
                        @if ($result->doc_preview == 1)
                            <a href="#" data-toggle="modal" data-target="#pdfModal-{{ $result->id }}">
                                {{ $result->formatted_title ?? $result->title }} <em class="icon ni ni-zoom-in"></em>
                            </a>
                        @else
                            {{ $result->formatted_title ?? $result->title }}
                        @endif
                    </td>
                    <td style="text-align: center">{{ $result->document_version }}</td>
                    <td style="text-align: center">{{ \Carbon\Carbon::parse($result->issue_date)->format('M. j, Y') }}</td>
                    <td style="text-align: center">{{ optional($result->year)->name }}</td>
                    <td style="text-align: center">{{ \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y') }}</td>
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
                    <td style="text-align: center">
@php
    $relatedDocuments = $result->related_documents;
    // Sort related documents by issue_date in descending order
    if ($relatedDocuments instanceof \Illuminate\Support\Collection) {
        $relatedDocuments = $relatedDocuments->sortByDesc(function($doc) {
            return \Carbon\Carbon::parse($doc->issue_date);
        });
    }
    $relatedCount = $relatedDocuments->count();
@endphp
@if($relatedCount > 0)
    <a href="#" data-toggle="modal" data-target="#relatedDocsModal-{{ $result->id }}" class="related-docs-badge">
        <em class="icon ni ni-link-alt"></em> {{ $relatedCount }}
    </a>
@else
    <span class="related-docs-badge no-docs">0</span>
@endif
</td>
                    <td class="tb-odr-action" style="display: flex !important; align-items: center; justify-content: center">
                        <div style="display: flex !important; align-items: center; justify-content: center" class="tb-odr-btns d-none d-sm-inline">
                            <a href="{{ route('login') }}" target="_blank" class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em class="icon ni ni-book-read"></em></a>
                            <a href="{{ route('login') }}" class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em class="icon ni ni-download"></em></a>
                            <a href="#" id="submit" onclick="document.getElementById('save-{{ $result->id }}').submit();" class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em class="icon ni ni-save"></em></a>
                            <form id="save-{{ $result->id }}" action="{{ route('save-document', $result->id) }}" method="POST" class="d-none" style="display: none">@csrf</form>
                        </div>
                    </td>
                </tr>

                <!-- Minimal Modals for guest -->
                <div class="modal fade" id="pdfModal-{{ $result->id }}" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel-{{ $result->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header"><h5 class="modal-title" id="pdfModalLabel-{{ $result->id }}">PDF Preview</h5></div>
                            <div class="modal-body"><div id="pdf-viewer-{{ $result->id }}"><canvas id="canvas-page1-{{ $result->id }}" class="pdf-page"></canvas><canvas id="canvas-page2-{{ $result->id }}" class="pdf-page"></canvas></div></div>
                            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button></div>
                        </div>
                    </div>
                </div>

                <div class="modal fade related-docs-modal" id="relatedDocsModal-{{ $result->id }}" tabindex="-1" role="dialog" aria-labelledby="relatedDocsModalLabel-{{ $result->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header"><h5 class="modal-title" id="relatedDocsModalLabel-{{ $result->id }}">{{ $result->title }}</h5></div>
                            <div class="modal-body">
                                @php 
                                    $relatedDocs = $result->relatedDocuments;
                                    // Sort related documents by issue_date in descending order
                                    if ($relatedDocs instanceof \Illuminate\Support\Collection) {
                                        $relatedDocs = $relatedDocs->sortByDesc(function($doc) {
                                            return \Carbon\Carbon::parse($doc->issue_date);
                                        });
                                    }
                                @endphp
                                @if($relatedDocs->count() > 0)
                                    @foreach($relatedDocs as $relatedDoc)
                                        <div class="related-doc-item">
                                            <div class="related-doc-title">{{ $relatedDoc->title }}</div>
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
                                            <div style="margin-top: 8px;"><a href="{{ route('subscribe') }}" class="btn btn-sm btn-outline-primary"><em class="icon ni ni-lock"></em> Restricted, subscribe to access</a></div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-center text-muted">No related documents found.</p>
                                @endif
                            </div>
                            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>
@endif