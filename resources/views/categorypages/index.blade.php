@extends('layouts.externalcategory')

@section('content')
  <link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js"></script>
    <script src="{{ asset('public/assets/js/centralized-table-filter.js') . '?v=' . time() }}"></script>
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
        /* Gradient button styles for ceased documents */
        .button-container-sb { display: inline-block; }
        .gradient-buttons { display: inline-block; }
        .gradient-button-content { display: flex; align-items: center; }
    </style>
    <script>
        $(document).ready(function() {
            console.log('Document ready, initializing table filter');
            
            // Small delay to ensure DOM is fully loaded
            setTimeout(function() {
                // Check if table exists
                var tableElement = document.getElementById('example');
                console.log('Table element found:', tableElement);
                
                var years = @json($years); 
                console.log('Years data:', years);
                
                // Initialize centralized table filter with pagination disabled
                console.log('Calling initCentralizedTableFilter');
                window.tableFilter = initCentralizedTableFilter('example', {
                    years: years
                });
                console.log('Table filter initialized:', window.tableFilter);
            }, 100);
            
            // Add ceased button to filter container (after Clear Filters button)
            @if ($regulations_ceased > 0)
            setTimeout(function() {
                var clearButton = $('#clear-filters-example');
                if (clearButton.length) {
                    var ceasedButton = $(`
                        <div class="filter-group" style="margin-left: 10px;">
                            <label>&nbsp;</label>
                            <a href="{{ route('ceasedDoc', $category->slug) }}" style="text-decoration: none; display: block;">
                                <div class="button-container-sb" style="display: block;">
                                    <div class="gradient-buttons" style="margin: 0 !important;">
                                        <div class="gradient-button-content" style="padding: 8px 12px; font-size: 14px; display: flex; align-items: center; justify-content: center; height: 38px; box-sizing: border-box;">
                                            <div style="white-space: nowrap;">Ceased/Repealed/Amended/Superseded</div>
                                            <img src="{{ asset('public/users/assets/Arrow - Right.svg') }}" alt="Arrow" style="width: 16px; height: 16px; margin-left: 5px;" />
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    `);
                    clearButton.parent().after(ceasedButton);
                }
            }, 100);
            @endif
        });
    </script>

    <section class="gd-main-container">
        <div class="hd-container">

        </div>
        <div class="gl-flex">
            <div class="tabs">
                <div class="current">
                   
                        <p class="current-active" style="font-size: 24px;">A-Z {{ $category->name }}</p>
                  

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

                    {{-- Pagination Info --}}
                  @if($reg->hasPages())
                        <div class="mt-4 d-flex justify-content-center">
                            <nav aria-label="Regulations pagination">
                                {{ $reg->onEachSide(1)->links('vendor.pagination.bootstrap-4') }}
                            </nav>
                        </div>
                        @endif
                </div>
            </div>
        </div>






        <div class="gda-cards-container">



          
        </div>
    </section>
    </div>

    <script src="{{ asset('public/admin/js/bundle.js') }}"></script>
@endsection
</div>
</body>

</html>
