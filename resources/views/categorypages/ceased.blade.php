@extends('layouts.externalcategory')

@section('content')
    <link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha384-R334r6kryDNB/GWs2kfB6blAOyWPCxjdHSww/mo7fel+o5TM/AOobJ0QpGRXSDh4" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js"></script>
    <script src="{{ asset('public/assets/js/centralized-table-filter.js') . '?v=' . time() }}"></script>
    @php
        // Extract unique years from the regulations
        $uniqueYears = $reg->pluck('year.name')->unique()->sort();
    @endphp

    <style>
        .filter-container { display: flex; gap: 15px; margin-bottom: 20px; align-items: flex-end; flex-wrap: wrap; clear: both; width: 100%; }
        .form-control-select .custom-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='4' height='5' viewBox='0 0 4 5'%3e%3cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: left 0.75rem center !important;
            background-size: 8px 10px !important;
            background-color: #fff !important;
            padding-left: 1.75rem !important;
            padding-right: 0.75rem !important;
        }
        .form-control-select::after {
            display: none !important;
        }
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
        
        .break-text { max-width: 200px; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; }
        /* Gradient button styles */
        .button-container-sb { display: inline-block; }
        .gradient-buttons { display: inline-block; }
        .gradient-button-content { display: flex; align-items: center; }
    </style>

    <script>
    $(document).ready(function() {
        console.log('Document ready, initializing table filter');
        
        setTimeout(function() {
            var tableElement = document.getElementById('example');
            console.log('Table element found for Ceased:', tableElement);
            
            var years = @json($uniqueYears);
            
            console.log('Calling initCentralizedTableFilter');
            window.tableFilter = initCentralizedTableFilter('example', {
                years: years
            });
            console.log('Table filter initialized:', window.tableFilter);
            
            // Add back button to filter container (after Clear Filters button)
            var clearButton = $('#clear-filters-example');
            if (clearButton.length) {
                var backButton = $(`
                    <div class="filter-group" style="margin-left: 10px;">
                        <label>&nbsp;</label>
                        <a href="{{ route('categorypages', $category->slug) }}" style="text-decoration: none; display: block;">
                            <div class="button-container-sb" style="display: block;">
                                <div class="gradient-buttons" style="background: #1d326d; border-radius: 5px; margin: 0 !important;">
                                    <div class="gradient-button-content" style="padding: 8px 12px; font-size: 14px; color: white; display: flex; align-items: center; justify-content: center; height: 38px; box-sizing: border-box;">
                                        <div style="white-space: nowrap;">< Go back</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                `);
                // Insert it immediately after the Clear Filters button wrapper div or inline
                clearButton.parent().after(backButton);
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
                    
                        <p class="current-active" style="font-size: 24px;">A-Z {{ $category->name }}
                            (Ceased/Repealed/Amended)
                        </p>
                   

                </div>
                <div class="active-line">
                    <div class="line-active"></div>
                    <div class="line-inactive"></div>
                </div>
            </div>
        </div>
      
        
             
                    
                    @include('components.regulations.ceasedtable', [
                        'records' => $reg, 
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
                            'years' => $years
                        ]
                    ])
        </div>
    </section>
    <script src="{{ asset('public/admin/js/bundle.js') }}"></script>
@endsection