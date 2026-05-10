@extends('layouts.headerexternal')

@section('content')
    <link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js"></script>
    <script src="{{ asset('public/assets/js/custom-table-filter.js') }}"></script>
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
            // Initialize custom table filter (no years needed for this page)
            initCustomTableFilter('example', {
                years: [],
                showAlphabetFilter: true,
                showEntityFilter: false
            });
        });
    </script>
    <div class="info">
        {{-- <div class="go-back">
            < Go back</div> --}}
                <div class="title">Search Results </div>

                <form method="GET" action="{{ route('search_result') }}">
                    <div class="search">
                        <div class="search-box">
                            <img src="{{ asset('public/users/assets/Search.svg') }}" alt="search icon" />
                            <input required name="title" value="{{ $title }}" type="search"
                                placeholder="Type in to search" />
                        </div>
                        <a href="#" style="height: 100%;">
                            <button style="height: 100%;" type="submit">
                                <div class="search-full">Search</div>
                            </button>
                        </a>
                    </div>
                </form>
        </div>
    </div>

    </section>

    <div class="gl-flex">
        <div class="tabs">


        </div>


        <a href="{{ url()->previous() }}">
            <div class="button-container-sb">
                <div class="gradient-buttons">
                    <div class="gradient-button-content">
                        <div>
                            < Go back</div>
                                {{-- <img src="{{ asset('public/users/assets/Arrow - Left.svg') }}" alt="FMDQ Logo" /> --}}
                        </div>
                    </div>
                </div>
        </a>




    </div>

    <section class="gd-main-container">
        @if (is_null($search))
            <img src="{{ asset('public/users/assets/illustration-search.svg') }}" height="250px"
                alt="No document purchased illustration" />
            <div class="no-doc">Search Not Found</div>
            <div class="get-in">
                There is no search for the word <span>“{{ $title }}”</span>, refine
                your search by trying another keyword
            </div>
        @else
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
                                             <th style="text-align: center;">{{$formattedStatuses}}</th>
                                        <th style="text-align: center;">{{$formattedStatuses}} Date</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($search as $result)
                                            <tr>
                                                <td class="break-text">
                                                    @if ($result->doc_preview == 1)
                                                        <a href="#" data-toggle="modal"
                                                            data-target="#pdfModal-{{ $result->id }}">
                                                            {{ $result->formatted_title }}
                                                        </a>
                                                    @else
                                                        {{ $result->formatted_title }}
                                                    @endif
                                                </td>
                                                <td style="text-align: center">{{ $result->document_version }}</td>
                                                <td style="text-align: center">
                                                    {{ str_replace('May.', 'May', \Carbon\Carbon::parse($result->issue_date)->format('M. j, Y')) }}
                                                </td>
                                                <td style="text-align: center">{{ $result->year->name }}</td>
                                                <td style="text-align: center">
                                                    {{ str_replace('May.', 'May', \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y')) }}
                                                </td>
                                                <td style="text-align: center">{{ optional($result->entity)->name }}</td>
                                                <td style="text-align: center">
                                                <span class="badge fmdq_Blue">{{$result->ceased}}</span>
                                            </td>

                                             <td style="text-align: center">
                                                {{ str_replace('May.', 'May', \Carbon\Carbon::parse($result->ceased_date)->format('M. j, Y')) }}
                                            </td>
                                                <td class="tb-odr-action"
                                                    style="display: flex; align-items: center; justify-content: center">
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
                        @endif

                        @if (Auth::check())
                            @if (!$isSubscribed && Auth::user()->usertype != 'internal')
                                <table id="example" class="datatable-init responsive table table-striped"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Title</th>
                                        <th style="text-align: center;">Effective Date</th>
                                        <th style="text-align: center;">Entity</th>
                                         <th style="text-align: center;">{{$formattedStatuses}}</th>
                                        <th style="text-align: center;">{{$formattedStatuses}} Date</th>
                                      
                                        <th style="text-align: center;"><span
                                                >Action</span></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($search as $result)
                                        <tr>
                                            <td class="break-text">
                                                @if ($result->doc_preview == 1)
                                                    <a href="#" data-toggle="modal"
                                                        data-target="#pdfModal-{{ $result->id }}">
                                                        {{ $result->formatted_title }}
                                                    </a>
                                                @else
                                                    {{ $result->formatted_title }}
                                                @endif
                                            </td>


                                            <td style="text-align: center">
                                                                                                {{ str_replace('May.', 'May', \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y')) }}

                                            </td>
                                            <td style="text-align: center">{{ optional($result->entity)->name }}</td>
                                             <td style="text-align: center">
                                                <span class="badge fmdq_Blue">{{$result->ceased}}</span>
                                            </td>

                                             <td style="text-align: center">
                                                                                                {{ str_replace('May.', 'May', \Carbon\Carbon::parse($result->ceased_date)->format('M. j, Y')) }}

                                            </td>
                                           
                                            <td class="tb-odr-action"
                                                style="display: flex; align-items: center; justify-content: center">
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
                                         <th style="text-align: center;">{{$formattedStatuses}}</th>
                                        <th style="text-align: center;">{{$formattedStatuses}} Date</th>
                                      
                                        <th style="text-align: center;"><span
                                                >Action</span></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($search as $result)
                                        <tr>
                                            <td class="break-text">
                                                @if ($result->doc_preview == 1)
                                                    <a href="#" data-toggle="modal"
                                                        data-target="#pdfModal-{{ $result->id }}">
                                                        {{ $result->formatted_title }}
                                                    </a>
                                                @else
                                                    {{ $result->formatted_title }}
                                                @endif
                                            </td>


                                            <td style="text-align: center">
                                                                                                {{ str_replace('May.', 'May', \Carbon\Carbon::parse($result->effective_date)->format('M. j, Y')) }}

                                            </td>
                                            <td style="text-align: center">{{ optional($result->entity)->name }}</td>
                                             <td style="text-align: center">
                                                <span class="badge fmdq_Blue">{{$result->ceased}}</span>
                                            </td>

                                             <td style="text-align: center">
                                                                                                {{ str_replace('May.', 'May', \Carbon\Carbon::parse($result->ceased_date)->format('M. j, Y')) }}

                                            </td>
                                           
                                            <td class="tb-odr-action"
                                                style="display: flex; align-items: center; justify-content: center">
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
                    </div>
                </div>
            </div>
            <br>


            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    @foreach ($search as $result)
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





            {{-- <div style="color: red">
                <br>
                <br>
                <br>
                <br>
                <br>

                {{ $search->links('pagination::bootstrap-4') }}
    </div> --}}
        @endif
    </section>
    </div>
    <script src="{{ asset('public/admin/js/bundle.js') }}"></script>
@endsection
</div>
</body>

</html>
