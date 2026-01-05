@extends('layouts.externaltag')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

<link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js"></script>
<script src="{{ asset('public/assets/js/centralized-table-filter.js') }}"></script>

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
                @include('components.regulations.table', [
                    'records' =>$search, 
                    'isSubscribed' => $isSubscribed,
                    'showFilters' => true,
                    'tableId' => 'example',
                    'filterOptions' => [
                        'showAlphabetFilter' => true,
                        'showYearFilter' => true,
                        'showEntityFilter' => true,
                        'showEffectiveDateFilter' => false,
                        'showVersionFilter' => true,
                        'years' => $years
                    ]
                ])

                {{-- @if($search->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    <nav aria-label="Regulations pagination">
                        {{$search->onEachSide(1)->links('vendor.pagination.bootstrap-4') }}
                    </nav>
                </div>
                @endif --}}
                {{-- Pagination Info --}}
               
            </div>
        </div>
    </div>
    <br>

  

    <div class="gda-cards-container">
    </div>
</section>
</div>

<script src="{{ asset('public/admin/js/bundle.js') }}"></script>
@endsection
</div>
</body>

</html>