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
       


        .alphabet-filter,
        .year-filter {
            margin-bottom: 10px;
        }

        .alphabet-filter a,
        .year-filter a {
            margin: 0 4px;
            text-decoration: none;
            color: #007bff;
        }

        .alphabet-filter a.active,
        .year-filter a.active {
            font-weight: bold;
            color: #000;
        }
    </style>
    <script>
        $(document).ready(function() {
            var table = $('#example').DataTable({
                "order": [
                    [0, "asc"]
                ] // Sort by the first column (Issuer Name) in ascending order
            });

            // Create year filter
            var years = [...new Set($('#example tbody tr td:nth-child(4)').map(function() {
                return $(this).text();
            }).get())].sort();
            var yearHtml = '<div class="year-filter">';
            yearHtml += '<a href="#" class="active" data-year="all">All</a>';
            years.forEach(function(year) {
                yearHtml += '<a href="#" data-year="' + year + '">' + year + '</a>';
            });
            yearHtml += '</div>';
            $('#example_wrapper').prepend(yearHtml);

            // Add event listener for year filter
            $('.year-filter a').on('click', function(e) {
                e.preventDefault();
                var year = $(this).data('year');
                $('.year-filter a').removeClass('active');
                $(this).addClass('active');

                if (year === 'all') {
                    table.column(3).search('').draw();
                } else {
                    table.column(3).search(year).draw();
                }
            });


            // Create alphabet filter
            var alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
            var alphabetHtml = '<div class="alphabet-filter">';
            alphabetHtml += '<a href="#" class="active" data-letter="all">All</a>';
            alphabet.forEach(function(letter) {
                alphabetHtml += '<a href="#" data-letter="' + letter + '">' + letter + '</a>';
            });
            alphabetHtml += '</div>';
            $('#example_wrapper').prepend(alphabetHtml);

            // Add event listener for alphabet filter
            $('.alphabet-filter a').on('click', function(e) {
                e.preventDefault();
                var letter = $(this).data('letter');
                $('.alphabet-filter a').removeClass('active');
                $(this).addClass('active');

                if (letter === 'all') {
                    table.column(0).search('').draw();
                } else {
                    table.column(0).search('^' + letter, true, false, true).draw();
                }
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




        <div class="gda-cards-container">
            @include('search.searchTbaleResult')




            <div class="">
                @if (count($results) == 0)
                    <img src="{{ asset('public/users/assets/illustration-search.svg') }}"
                        alt="No document purchased illustration" height="250px" />
                    <div class="no-doc"></div>
                    <div class="get-in">
                        There is no search for the word <span>“{{ $title }}”</span>, refine
                        your search by trying another keyword
                    </div>
                @else
                    <div style="background-color: #fff; padding: 20px; width: 100%">
                        <div class="row" style="width: 100%">
                            <div class="col-md-12">
                                <table id="example" class="datatable-init responsive table table-striped"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">Title</th>

                                            <th style="text-align: center;">Version No.</th>
                                            <th style="text-align: center;">Year</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($results as $result)
                                            <tr>
                                                  <td>
                                
                                    {{ $result->title }}
                                    @if ($result->doc_preview == 1)
                                     <a href="#" data-toggle="modal"
                                                            data-target="#pdfModal-{{ $result->id }}">
                                                            {{ $result->title }} <em class="icon ni ni-zoom-in"></em>

                                                        </a>
                                        
                                    @endif
                                
                            </td>

                                                <td>{{ $result->document_version }}</td>

                                                <td style="text-align: center;">{{ $result->year->name }}</td>
                                                <td class="tb-odr-action"
                                                    style="display: flex; align-items: center; justify-content: center">
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
                            </div>
                        </div>
                    </div>
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
