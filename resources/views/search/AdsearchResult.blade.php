@extends('layouts.headerexternal')

@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

    <link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

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
                table.column(3).search(year).draw(); // Year is now column 3 (0-indexed)
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
                <div style="background-color: #fff; padding: 20px; width: 100%">
                    <!-- Filter Container -->
                        <div class="filter-container">
                            <div class="filter-group">
                                <label for="search-input">Search:</label>
                                <input type="text" id="search-input" class="filter-input" placeholder="Search...">
                            </div>
                            <div class="filter-group">
                                <label for="letter-filter">First Letter:</label>
                                <select id="letter-filter" class="filter-select">
                                    <option value="">All Letters</option>
                                    @foreach(range('A', 'Z') as $letter)
                                        <option value="{{ $letter }}">{{ $letter }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-group">
                                <label for="year-filter">Year:</label>
                                <select id="year-filter" class="filter-select">
                                    <option value="">All Years</option>
                                    @php
                                        $years = $results->pluck('year.name')->unique()->sort()->values();
                                    @endphp
                                    @foreach($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-group" style="flex: 0 0 100%;">
                                <button id="clear-filters-example" class="clear-filters-btn">Clear Filters</button>
                            </div>
                        </div>

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
                                                    <th style="text-align: center;">Category</th>
                                                    <th style="text-align: center;">Year</th>
                                                    <th style="text-align: center;">Effective Date</th>
                                                    <th style="text-align: center;">Entity</th>
                                                    <th style="text-align: center;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                        @foreach ($results as $result)
                                            <tr>
                                                <td>
                                                    @if ($result->doc_preview == 1)
                                                        <a href="#" data-toggle="modal"
                                                            data-target="#pdfModal-{{ $result->id }}">
                                                            {{ $result->title }} <em class="icon ni ni-zoom-in"></em>
                                                        </a>
                                                    @else
                                                        {{ $result->title }}
                                                    @endif
                                                </td>
                                                <td style="text-align: center">{{ $result->document_version }}</td>
                                                <td style="text-align: center">
                                                    {{ \Carbon\Carbon::parse($result->issue_date)->format('M. j, Y') }}
                                                </td>
                                                <td style="text-align: center">{{ $result->category->name }}</td>
                                                <td style="text-align: center">{{ $result->year->name }}</td>
                                                <td style="text-align: center">
                                                    {{ \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y') }}
                                                </td>
                                                <td style="text-align: center">{{ optional($result->entity)->name }}</td>
                                                <td class="tb-odr-action"
                                                    style="display: flex !important; align-items: center; justify-content: center">
                                                    <div style="display: flex !important; align-items: center; justify-content: center" class="tb-odr-btns d-none d-sm-inline">





                                                        @if ($isSubscribed || Auth::user()->usertype == 'internal')
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


                                            <div class="modal fade" id="pdfModal-{{ $result->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="pdfModalLabel-{{ $result->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
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
                                        @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                @else
                                    <table id="example" class="datatable-init responsive table table-striped"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center;">Title</th>
                                                <th style="text-align: center;">Effective Date</th>
                                                <th style="text-align: center;">Entity</th>
                                                <th style="text-align: center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($results as $result)
                                                <tr>
                                                    <td>
                                                        @if ($result->doc_preview == 1)
                                                            <a href="#" data-toggle="modal"
                                                                data-target="#pdfModal-{{ $result->id }}">
                                                                {{ $result->title }} <em class="icon ni ni-zoom-in"></em>
                                                            </a>
                                                        @else
                                                            {{ $result->title }}
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center">
                                                        {{ \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y') }}
                                                    </td>
                                                    <td style="text-align: center">{{ optional($result->entity)->name }}</td>
                                                    <td class="tb-odr-action"
                                                        style="display: flex !important; align-items: center; justify-content: center">
                                                        <div style="display: flex !important; align-items: center; justify-content: center" class="tb-odr-btns d-none d-sm-inline">
                                                            <a href="{{ route('login') }}" target="_blank"
                                                                class="btn btn-icon btn-white btn-dim btn-sm btn-primary">
                                                                <em class="icon ni ni-book-read"></em>
                                                            </a>
                                                            <a href="{{ route('login') }}"
                                                                class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                                    class="icon ni ni-download"></em></a>
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
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                    <br>
                @endif
            </div>


        </div>
    </section>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($results as $result)
                (function(id) {
                    var url = '{{ asset("public/pdf_documents/$result->regulation_doc") }}';
                    var pdfjsLib = window['pdfjs-dist/build/pdf'];
                    pdfjsLib.GlobalWorkerOptions.workerSrc =
                        'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js';

                    pdfjsLib.getDocument(url).promise.then(function(pdfDoc) {
                        function renderPage(pageNum, canvasId) {
                            pdfDoc.getPage(pageNum).then(function(page) {
                                var viewport = page.getViewport({
                                    scale: 1.5
                                });
                                var canvas = document.getElementById(canvasId);
                                var context = canvas.getContext('2d');
                                canvas.height = viewport.height;
                                canvas.width = viewport.width;

                                var renderContext = {
                                    canvasContext: context,
                                    viewport: viewport
                                };
                                page.render(renderContext);
                            });
                        }

                        renderPage(1, 'canvas-page1-' + id);
                        renderPage(2, 'canvas-page2-' + id);
                    }).catch(function(error) {
                        console.error('Error loading PDF:', error);
                    });
                })({{ $result->id }});
            @endforeach
        });
    </script>
    <script src="{{ asset('public/admin/js/bundle.js') }}"></script>
@endsection
</div>
</body>

</html>
