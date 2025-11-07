@extends('layouts.externalcategory')

@section('content')

<link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js"></script>




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
    var years = @json($years);

    $(document).ready(function () {
        // Custom table management without DataTables
        const table = document.getElementById('example');
        if (!table) return;
        
        const tbody = table.querySelector('tbody');
        const thead = table.querySelector('thead');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const totalRows = rows.length;
        
        let currentFilters = {
            alphabet: '',
            year: '',
            search: ''
        };
        
        let sortColumn = 0;
        let sortDirection = 'asc';

        // Detect table structure and get column indices
        const headers = [];
        thead.querySelectorAll('th').forEach((th, index) => {
            headers.push(th.textContent.trim());
        });
        
        const titleColIndex = 0; // Title is always first
        const yearColIndex = headers.indexOf('Year');
        const entityColIndex = headers.indexOf('Entity');

        // Create enhanced filter container
        let filterHtml = '<div class="filter-container">';
        
        // Search input
        filterHtml += '<div class="filter-group">';
        filterHtml += '<label for="table-search">Search documents:</label>';
        filterHtml += '<input type="text" id="table-search" class="filter-select" placeholder="Search..." style="min-width: 200px;">';
        filterHtml += '</div>';
        
        // Alphabet filter dropdown
        filterHtml += '<div class="filter-group">';
        filterHtml += '<label for="alphabet-filter">Filter by First Letter:</label>';
        filterHtml += '<select id="alphabet-filter" class="filter-select">';
        filterHtml += '<option value="">All Letters</option>';
        const alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
        alphabet.forEach(letter => {
            filterHtml += `<option value="${letter}">${letter}</option>`;
        });
        filterHtml += '</select>';
        filterHtml += '</div>';

        // Year filter dropdown
        filterHtml += '<div class="filter-group">';
        filterHtml += '<label for="year-filter">Filter by Year:</label>';
        filterHtml += '<select id="year-filter" class="filter-select">';
        filterHtml += '<option value="">All Years</option>';
        years.forEach(year => {
            filterHtml += `<option value="${year}">${year}</option>`;
        });
        filterHtml += '</select>';
        filterHtml += '</div>';

        // Clear filters button
        filterHtml += '<button class="clear-filters-btn" id="clear-filters">Clear All Filters</button>';
        filterHtml += '</div>';

        // Add search info container
        filterHtml += '<div id="search-info" class="search-info" style="display: none;"></div>';

        // Insert filter container before table
        $(table).before(filterHtml);

        // Filter and display rows
        function filterAndDisplayRows() {
            let visibleCount = 0;
            
            rows.forEach(row => {
                let visible = true;
                const cells = row.querySelectorAll('td');
                
                // Get text content from cells
                const titleText = cells[titleColIndex]?.textContent.trim() || '';
                const yearText = cells[yearColIndex >= 0 ? yearColIndex : 2]?.textContent.trim() || '';
                
                // Search filter (searches in all text content)
                if (currentFilters.search) {
                    const searchTerm = currentFilters.search.toLowerCase();
                    const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
                    visible = visible && rowText.includes(searchTerm);
                }
                
                // Alphabet filter
                if (visible && currentFilters.alphabet) {
                    const firstLetter = titleText.charAt(0).toUpperCase();
                    visible = visible && (firstLetter === currentFilters.alphabet);
                }
                
                // Year filter
                if (visible && currentFilters.year) {
                    visible = visible && yearText.includes(currentFilters.year);
                }
                
                // Show/hide row
                row.style.display = visible ? '' : 'none';
                if (visible) visibleCount++;
            });
            
            updateSearchInfo(visibleCount);
        }

        // Search functionality
        document.getElementById('table-search').addEventListener('keyup', function(e) {
            currentFilters.search = e.target.value;
            filterAndDisplayRows();
        });

        // Alphabet filter functionality
        document.getElementById('alphabet-filter').addEventListener('change', function(e) {
            currentFilters.alphabet = e.target.value;
            filterAndDisplayRows();
        });

        // Year filter functionality
        document.getElementById('year-filter').addEventListener('change', function(e) {
            currentFilters.year = e.target.value;
            filterAndDisplayRows();
        });

        // Clear all filters
        document.getElementById('clear-filters').addEventListener('click', function() {
            document.getElementById('table-search').value = '';
            document.getElementById('alphabet-filter').value = '';
            document.getElementById('year-filter').value = '';
            
            currentFilters = {
                alphabet: '',
                year: '',
                search: ''
            };
            
            filterAndDisplayRows();
        });

        // Update search info
        function updateSearchInfo(visibleCount) {
            const activeFilters = [];
            
            if (currentFilters.search) {
                activeFilters.push('Search: ' + currentFilters.search);
            }
            if (currentFilters.alphabet) {
                activeFilters.push('Letter: ' + currentFilters.alphabet);
            }
            if (currentFilters.year) {
                activeFilters.push('Year: ' + currentFilters.year);
            }
            
            const searchInfo = document.getElementById('search-info');
            if (activeFilters.length > 0) {
                const infoText = 'Active filters: ' + activeFilters.join(', ') + 
                              ' | Showing ' + visibleCount + ' of ' + totalRows + ' documents';
                searchInfo.textContent = infoText;
                searchInfo.style.display = 'block';
            } else {
                searchInfo.style.display = 'none';
            }
        }
        
        // Add sort functionality to table headers
        thead.querySelectorAll('th').forEach((th, index) => {
            th.style.cursor = 'pointer';
            th.style.userSelect = 'none';
            
            // Add sort indicator
            const sortIndicator = document.createElement('span');
            sortIndicator.innerHTML = ' ↕';
            sortIndicator.style.opacity = '0.3';
            th.appendChild(sortIndicator);
            
            th.addEventListener('click', function() {
                // Toggle sort direction
                if (sortColumn === index) {
                    sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    sortColumn = index;
                    sortDirection = 'asc';
                }
                
                // Update all sort indicators
                thead.querySelectorAll('th span').forEach((span, i) => {
                    if (i === index) {
                        span.innerHTML = sortDirection === 'asc' ? ' ▲' : ' ▼';
                        span.style.opacity = '1';
                    } else {
                        span.innerHTML = ' ↕';
                        span.style.opacity = '0.3';
                    }
                });
                
                // Sort rows
                sortTable(index);
            });
        });
        
        // Sort table function
        function sortTable(columnIndex) {
            const sortedRows = rows.slice().sort((a, b) => {
                const aText = a.querySelectorAll('td')[columnIndex]?.textContent.trim() || '';
                const bText = b.querySelectorAll('td')[columnIndex]?.textContent.trim() || '';
                
                // Try numeric comparison first
                const aNum = parseFloat(aText);
                const bNum = parseFloat(bText);
                
                if (!isNaN(aNum) && !isNaN(bNum)) {
                    return sortDirection === 'asc' ? aNum - bNum : bNum - aNum;
                }
                
                // String comparison
                if (sortDirection === 'asc') {
                    return aText.localeCompare(bText);
                } else {
                    return bText.localeCompare(aText);
                }
            });
            
            // Re-append rows in sorted order
            sortedRows.forEach(row => tbody.appendChild(row));
            
            // Re-apply filters after sorting
            filterAndDisplayRows();
        }

        // Initialize display
        filterAndDisplayRows();
        console.log('Table filters initialized successfully');

        // Handle restricted docs click for unpaid users
        $(document).on('click', '.restricted-docs', function(e) {
            e.preventDefault();
            var redirectUrl = $(this).data('redirect');
            if (redirectUrl) {
                window.open(redirectUrl, '_blank');
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
                <a href="rules.html">
                    <p class="current-active" style="font-size: 24px;">A-Z {{ $category->name }}</p>
                </a>

            </div>
            <div class="active-line">
                <div class="line-active"></div>
                <div class="line-inactive"></div>
            </div>
        </div>

        @if ($regulations_ceased->count() > 0)
        <a href="{{ route('ceasedDoc', $category->slug) }}">
            <div class="button-container-sb">
                <div class="gradient-buttons">
                    <div class="gradient-button-content">
                        <div>Show {{ $formattedStatuses }}</div>
                        <img src="{{ asset('public/users/assets/Arrow - Right.svg') }}" alt="FMDQ Logo" />
                    </div>
                </div>
            </div>
        </a>
        @endif


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
                                            <th style="text-align: center;">Entity</th>
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
                                                
                                                {{-- Related Documents Column --}}
                                                <td style="text-align: center">
                                                    @if($result->related_docs)
                                                        @php
                                                            // Only count related docs, don't load them (prevents recursive queries)
                                                            $relatedCount = count(explode(',', $result->related_docs));
                                                        @endphp
                                                        @if ($isSubscribed || Auth::user()->usertype == 'internal')
                                                            <span class="badge badge-primary" style="cursor: pointer;" data-toggle="modal" data-target="#relatedDocsModal-{{ $result->id }}">{{ $relatedCount }} related</span>
                                                        @else
                                                            <span class="badge badge-primary" style="cursor: pointer;" data-toggle="modal" data-target="#relatedDocsModal-{{ $result->id }}">{{ $relatedCount }} related</span>
                                                        @endif
                                                    @else
                                                        <span class="badge badge-secondary">None</span>
                                                    @endif
                                                </td>
                                                {{-- End Related Documents Column --}}
                                                
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
                                                <div class="modal-dialog modal-xl" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="" id="relatedDocsModalLabel-{{ $result->id }}">{{ $result->title }}</h5>
                                                           
                                                        </div>
                                                        <div class="modal-body">
                                                            @php
                                                                // Optimized: Load related docs without recursive accessor
                                                                if ($result->related_docs) {
                                                                    $relatedIds = explode(',', $result->related_docs);
                                                                    $relatedDocuments = \App\Models\Regulation::whereIn('id', $relatedIds)
                                                                        ->select('id', 'title', 'document_version', 'year_id', 'entity_id', 'regulation_doc')
                                                                        ->with(['year:id,name', 'entity:id,name'])
                                                                        ->get();
                                                                } else {
                                                                    $relatedDocuments = collect();
                                                                }
                                                            @endphp

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
                                                                                            'level' => 1
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
                                                                                        <div style="width: 30%; min-width: 200px;" class="text-truncate">
                                                                                            <span class="text-muted">Restricted - Upgrade to view</span>
                                                                                        </div>
                                                                                        <div style="width: 10%; min-width: 80px; text-align: center;">
                                                                                            <span class="text-muted">-</span>
                                                                                        </div>
                                                                                        <div style="width: 15%; min-width: 120px; text-align: center;">
                                                                                            <span class="text-muted">-</span>
                                                                                        </div>
                                                                                        <div style="width: 15%; min-width: 120px; text-align: center;">
                                                                                            <span class="text-muted">-</span>
                                                                                        </div>
                                                                                        <div style="width: 12%; min-width: 100px; text-align: center;">
                                                                                            <span class="text-muted">-</span>
                                                                                        </div>
                                                                                        <div style="width: 10%; min-width: 120px; text-align: center;">
                                                                                            <a href="{{ route('subscribe') }}" target="_blank" class="btn btn-sm btn-warning">
                                                                                                Upgrade
                                                                                            </a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                      
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal for Related Documents -->
                                            <!-- End Modal for Related Documents -->
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                <!-- Pagination Links -->
                                @if($reg->hasPages())
                                <div class="mt-4 d-flex justify-content-center">
                                    <nav aria-label="Regulations pagination">
                                        {{ $reg->onEachSide(1)->links('vendor.pagination.bootstrap-4') }}
                                    </nav>
                                </div>
                                @endif
                                
                            @else
                               <table id="example" class="datatable-init responsive table table-striped"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Title</th>
                                        <th style="text-align: center;">Effective Date</th>
                                        <th style="text-align: center;">Year</th>
                                        <th style="text-align: center;">Entity</th>
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



                                            <td style="text-align: center">
                                               
                                                {{ \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y') }}
                                            </td>
                                            <td style="text-align: center">{{ $result->year->name }}</td>

                                            <td style="text-align: center">{{ optional($result->entity)->name }}</td>
                                          
                                            {{-- Related Documents Column --}}
                                            <td style="text-align: center">
                                                @if($result->related_docs)
                                                    @php
                                                        // Only count related docs, don't load them (prevents recursive queries)
                                                        $relatedCount = count(explode(',', $result->related_docs));
                                                    @endphp
                                                    @if ($isSubscribed || Auth::user()->usertype == 'internal')
                                                        <span class="badge badge-primary" style="cursor: pointer;" data-toggle="modal" data-target="#relatedDocsModal-{{ $result->id }}">{{ $relatedCount }} related</span>
                                                    @else
                                                        <span class="badge badge-primary" style="cursor: pointer;" data-toggle="modal" data-target="#relatedDocsModal-{{ $result->id }}">{{ $relatedCount }} related</span>
                                                    @endif
                                                @else
                                                    <span class="badge badge-secondary">None</span>
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
                                                <div class="modal-dialog modal-xl" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="" id="relatedDocsModalLabel-{{ $result->id }}">{{ $result->title }}</h5>
                                                           
                                                        </div>
                                                        <div class="modal-body">
                                                            @php
                                                                // Optimized: Load related docs without recursive accessor
                                                                if ($result->related_docs) {
                                                                    $relatedIds = explode(',', $result->related_docs);
                                                                    $relatedDocuments = \App\Models\Regulation::whereIn('id', $relatedIds)
                                                                        ->select('id', 'title', 'document_version', 'year_id', 'entity_id', 'regulation_doc')
                                                                        ->with(['year:id,name', 'entity:id,name'])
                                                                        ->get();
                                                                } else {
                                                                    $relatedDocuments = collect();
                                                                }
                                                            @endphp

                                                            <div class="card w-100 border-0 shadow-sm">
                                                                <div class="card-header bg-light">
                                                                    <h5 class="mb-0 fw-bold">Related Documents</h5>
                                                                </div>

                                                                <div class="card-body p-0">
                                                                    <div class="list-group w-100">
                                                                        @if ($isSubscribed || Auth::user()->usertype == 'internal')
                                                                            @foreach($relatedDocuments as $index => $relatedDoc)
                                                                                <div class="list-group-item">
                                                                                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                                                                                        <div class="flex-grow-1">
                                                                                            <strong>{{ $index + 1 }}.</strong>
                                                                                            <span>{{ $relatedDoc->title }}</span>
                                                                                            <span>{{ $relatedDoc->title }}</span>
                                                                                        </div>
                                                                                        <div class="d-flex gap-2">
                                                                                        <a href="{{ asset('public/pdf_documents/' . $relatedDoc->regulation_doc) }}" target="_blank" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                                            <em class="icon ni ni-book-read"></em>
                                                                                        </a>
                                                                                        <a href="{{ route('download', $relatedDoc->id) }}" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                                            <em class="icon ni ni-download"></em>
                                                                                        </a>
                                                                                        <a href="#" id="submit" onclick="document.getElementById('save-{{ $relatedDoc->id }}').submit();" class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                                            <em class="icon ni ni-save"></em>
                                                                                        </a>
                                                                                        <form id="save-{{ $relatedDoc->id }}" action="{{ route('save-document', $relatedDoc->id) }}" method="POST" class="d-none">
                                                                                            @csrf
                                                                                        </form>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    {{-- Check if this related document has its own related documents --}}
                                                                                    @if($relatedDoc->related_docs && $relatedDoc->nested_related_documents->count() > 0)
                                                                                        @include('partials.nested-related-documents', [
                                                                                            'nestedDocuments' => $relatedDoc->nested_related_documents,
                                                                                            'parentIndex' => $index + 1,
                                                                                            'level' => 1
                                                                                        ])
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                        @else
                                                                            @foreach($relatedDocuments as $index => $relatedDoc)
                                                                                <div class="list-group-item">
                                                                                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                                                                                        <div class="flex-grow-1">
                                                                                            <strong>{{ $index + 1 }}.</strong>
                                                                                            <span class="text-muted">Restricted - Upgrade to view</span>
                                                                                        </div>
                                                                                        <div class="d-flex gap-2">
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
                                                                                            'level' => 1
                                                                                        ])
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>


                                                               
                                                               
                                                        </div>
                                                      
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- End Modal for Related Documents -->
                                    @endforeach
                                </tbody>
                            </table>
                            
                            <!-- Pagination Links -->
                            @if($reg->hasPages())
                            <div class="mt-4 d-flex justify-content-center">
                                <nav aria-label="Regulations pagination">
                                    {{ $reg->onEachSide(1)->links('vendor.pagination.bootstrap-4') }}
                                </nav>
                            </div>
                            @endif
                            
                            @endif
                        @else
                            <!-- Content for non-authenticated users -->
                            <div class="alert alert-info">
                                <p>Please <a href="{{ route('login') }}">login</a> to view the regulations.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <br>


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
            
            <script>
                // Initialize modals for DataTables - only for elements with data-target attribute
                $(document).on('click', '[data-toggle="modal"][data-target]', function(e) {
                    var target = $(this).data('target');
                    console.log('Modal target:', target);
                    if ($(target).length > 0) {
                        $(target).modal('show');
                    } else {
                        console.log('Modal not found:', target);
                    }
                });
                
                // Additional modal initialization for DataTables
                $('#example').on('draw.dt', function() {
                    $('[data-toggle="modal"][data-target]').off('click').on('click', function(e) {
                        var target = $(this).data('target');
                        console.log('Modal target (DataTable):', target);
                        if ($(target).length > 0) {
                            $(target).modal('show');
                        } else {
                            console.log('Modal not found (DataTable):', target);
                        }
                    });
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