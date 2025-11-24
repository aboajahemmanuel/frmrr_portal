@extends('layouts.headerexternal')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

    <link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js"></script>

    <style>
        .break-text {
            max-width: 200px;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
        }

        .filter-container {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            align-items: flex-end;
            flex-wrap: wrap;
            clear: both;
            width: 100%;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 4px;
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
            flex: 1;
            min-width: 150px;
        }

        .filter-group label {
            font-weight: 600;
            color: #333;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .filter-select,
        .filter-input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fff;
            font-size: 14px;
            width: 100%;
            cursor: pointer;
        }

        .filter-select:focus,
        .filter-input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
        }

        .clear-filters-btn {
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            align-self: flex-end;
            width: 100%;
            margin-top: 10px;
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
            // Initialize DataTable without custom filters (we have manual filters)
            var table = $('#example').DataTable({
                responsive: true,
                paging: true,
                searching: true,
                info: true,
                dom: 'lrtip' // Remove default search box
            });

            // Manual filter functionality
            $('#search-input').on('keyup', function() {
                table.search(this.value).draw();
            });

            $('#letter-filter').on('change', function() {
                var letter = this.value;
                if (letter) {
                    table.column(0).search('^' + letter, true, false).draw();
                } else {
                    table.column(0).search('').draw();
                }
            });

            $('#year-filter').on('change', function() {
                var year = this.value;
                table.column(3).search(year).draw();
            });

            $('#clear-filters-example').on('click', function() {
                $('#search-input').val('');
                $('#letter-filter').val('');
                $('#year-filter').val('');
                table.search('').columns().search('').draw();
            });
        });
    </script>
    <div class="info">

        <div class="title">Search </div>


    </div>
    </div>

    </section>
    <section style="background: #e8eaf0 !important;" class="gd-main-container">
        <div class="hd-container">
            <div class="gl-flex">
                <div class="tabs">




                </div>


            </div>
        </div>




        <div class="gda-cards-container" style="display: flex; flex-direction: column;">
            @include('search.searchTbaleResult')
            
            @if (count($results) == 0)
                <div style="text-align: center; padding: 50px;">
                    <img src="{{ asset('public/users/assets/illustration-search.svg') }}"
                        alt="No document purchased illustration" height="250px" />
                    <div class="no-doc"></div>
                    <div class="get-in">
                        There is no search for the word <span>"{{ $title }}"</span>, refine
                        your search by trying another keyword
                    </div>
                </div>
            @else
                     @include('components.regulations.ceasedtable', [
                        'records' => $results, 
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
                @endif
            </div>


        </div>
    </section>

    </div>
    <script src="{{ asset('public/admin/js/bundle.js') }}"></script>
@endsection
</div>
</body>

</html>
