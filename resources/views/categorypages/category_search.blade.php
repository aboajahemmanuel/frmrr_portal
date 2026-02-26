@extends('layouts.externalcategory')

@section('content')
    <link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
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
        
        /* Custom styles for PDF preview */
        .pdf-page {
            border: 1px solid #ddd;
            margin-bottom: 10px;
            width: 100%;
        }
        
        .blurred {
            filter: blur(5px);
        }
        /* Gradient button styles for ceased documents */
        .button-container-sb { display: inline-block; }
        .gradient-buttons { display: inline-block; }
        .gradient-button-content { display: flex; align-items: center; }
    </style>
    <script>
    $(document).ready(function() {
        var years = @json($years);
        
        // Initialize centralized table filter
        window.tableFilter = initCentralizedTableFilter('example', {
            years: years
        });
        
        // Add ceased button to filter container (after Clear Filters button)
        @if ($search_ceased->count() > 0)
        setTimeout(function() {
            var clearButton = $('#clear-filters-example');
            if (clearButton.length) {
                var ceasedButton = $(`
                    <div class="filter-group" style="align-self: flex-end; margin-left: 10px;">
                       <!--  <a href="{{ route('search_category_ceased', ['slug' => $category->slug, 'title' => $title]) }}" style="text-decoration: none;">
                            <div class="button-container-sb" style="display: inline-block;">
                                <div class="gradient-buttons">
                                    <div class="gradient-button-content" style="padding: 8px 12px; font-size: 14px; display: flex; align-items: center;">
                                       <div style="white-space: nowrap;">Show Ceased/Repealed/Amended/Superseded</div>
                                        <img src="{{ asset('public/users/assets/Arrow - Right.svg') }}" alt="Arrow" style="width: 16px; height: 16px; margin-left: 5px;" />
                                    </div>
                                </div>
                            </div>
                        </a>-->
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
                   
                        <p class="current-active" style="font-size: 24px;">A-Z  {{ $category->name }}</p>
                   

                </div>
                <div class="active-line">
                    <div class="line-active"></div>
                    <div class="line-inactive"></div>
                </div>
            </div>
        </div>
        @if (count($search) == 0)
            <img src="{{ asset('public/users/assets/illustration-search.svg') }}" alt="No document purchased illustration"
                height="250px" />
            <div class="no-doc"></div>
            <div class="get-in">
                There is no search for the word <span>“{{ $title }}”</span>, refine
                your search by trying another keyword
            </div>
        @else
            <div style="background-color: #fff; padding: 20px; width: 100%">
                <div class="row" style="width: 100%">
                    <div class="col-md-12">

                         @include('components.regulations.table', [
                    'records' => $search, 
                    'isSubscribed' => $isSubscribed,
                    'showFilters' => true,
                    'filterOptions' => [
                        'showAlphabetFilter' => true,
                        'showYearFilter' => true,
                        'showEntityFilter' => true,
                        'showEffectiveDateFilter' => true,
                        'showVersionFilter' => true,
                        'showSearchBar' => true,
                        'years' => $years
                    ]
                ])
               
                    </div>
                      
                        @endif
        <div class="gda-cards-container">

        </div>
    </section>
    </div>

    <script src="{{ asset('public/admin/js/bundle.js') }}"></script>
@endsection
</div>
</body>

</html>
