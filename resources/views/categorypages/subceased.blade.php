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
                            (Ceased/Repealed/Amended/Superseded)
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
                            'showVersionFilter' => true,
                            'years' => $years
                        ]
                    ])
                </div>
            </div>
        </div>

       
    </section>
    <script src="{{ asset('public/admin/js/bundle.js') }}"></script>
@endsection