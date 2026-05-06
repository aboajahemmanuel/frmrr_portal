@extends('layouts.externalsubcategory')

@section('content')
    <link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js"></script>
    <script src="{{ asset('public/assets/js/centralized-table-filter.js') . '?v=' . time() }}"></script>
    <style>
        .break-text { max-width: 200px; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; }
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
        .filter-select:focus { outline: none; border-color: #007bff; box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25); }
        .clear-filters-btn { padding: 8px 16px; background-color: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; align-self: flex-end; }
        .clear-filters-btn:hover { background-color: #5a6268; }
        .search-info { margin-top: 10px; padding: 10px; background-color: #f8f9fa; border-radius: 4px; font-size: 14px; color: #495057; }
        .pdf-page { border: 1px solid #ddd; margin-bottom: 10px; width: 100%; }
        .blurred { filter: blur(5px); }
    </style>
    <section class="gd-main-container">
        <div class="hd-container"></div>
        <div class="gl-flex">
            <div class="tabs">
                <div class="current">
                    <p class="current-active" style="font-size: 24px;">A-Z {{ $subcategory->name ?? 'Subcategory' }}</p>
                </div>
                <div class="active-line">
                    <div class="line-active"></div>
                    <div class="line-inactive"></div>
                </div>
            </div>
        </div>
        @if (count($search) == 0)
            <img src="{{ asset('public/users/assets/illustration-search.svg') }}" alt="No document purchased illustration" height="250px" />
            <div class="no-doc"></div>
            <div class="get-in">There is no search for the word <span>“{{ $title }}”</span>, refine your search by trying another keyword</div>
        @else
            <div style="background-color: #fff; padding: 20px; width: 100%">
                <div class="row" style="width: 100%">
                    <div class="col-md-12">
                    </div>
                        @include('components.regulations.subtable', [
                    'records' => $search, 
                    'isSubscribed' => $isSubscribed,
                    'showFilters' => true,
                    'filterOptions' => [
                        'showAlphabetFilter' => true,
                        'showYearFilter' => true,
                        'showEntityFilter' => true,
                        'showEffectiveDateFilter' => true,
                        'showVersionFilter' => true,
                        'years' => $years
                    ]
                ])
                
                 @endif
            </div>
            <br>

     

        <div class="gda-cards-container"></div>
    </section>
    </div>

    <script src="{{ asset('public/admin/js/bundle.js') }}"></script>
@endsection
</div>
</body>
</html>
