@extends('layouts.app')

@section('content')

    <div class="page-content">

        <!-- Start home -->
        <section class="page-title-box">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="text-center text-white">
                            <h3 class="mb-4">{{ $title }}</h3>
                            <div class="page-next">
                                <nav class="d-inline-block" aria-label="breadcrumb text-center">
                                    <ol class="breadcrumb justify-content-center">
                                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                                        <li class="breadcrumb-item active" aria-current="page"> Search {{ $title }}
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->
            </div>
            <!--end container-->
        </section>
        <!-- end home -->

        <!-- START SHAPE -->
        <div class="position-relative" style="z-index: 1">
            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 250">
                    <path fill="#FFFFFF" fill-opacity="1"
                        d="M0,192L120,202.7C240,213,480,235,720,234.7C960,235,1200,213,1320,202.7L1440,192L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z">
                    </path>
                </svg>
            </div>
        </div>
        <!-- END SHAPE -->


        <!-- START JOB-LIST -->
        <section class="section">
            <div class="container">
                <div class="row">

                    <div class="col-lg-12">
                        <div class="me-lg-5">
                            <div class="job-list-header">
                                <form method="GET" action="{{ route('search', $title) }}">


                                    <div class="row g-2">
                                        <div class="col-lg-8 col-md-6">
                                            <div class="filler-job-form">
                                                <i class="uil uil-book-alt"></i>
                                                <input type="search" required name="title"
                                                    class="form-control filter-job-input-box" id="exampleFormControlInput1"
                                                    placeholder="E-Bond, Disciplinary...">
                                            </div>
                                        </div><!--end col-->

                                        <div class="col-lg-4 col-md-6">
                                            <button type="submit" class="btn btn-primary w-100"><i
                                                    class="uil uil-search me-1"></i> Change Search</button>
                                        </div><!--end col-->
                                    </div><!--end row-->
                                </form>
                            </div><!--end job-list-header-->
                            <div class="wedget-popular-title mt-4">
                                <h6>Categories</h6>
                                <ul class="list-inline">
                                    <p> Total Results: {{ $total }}</p>
                                    @php
                                        $categories = \App\Models\Category::all();

                                        // $search = Category::where('title', 'like', '%'.$title.'%')->get();
                                        // $total = $search->count();

                                    @endphp
                                    @foreach ($categories as $category)
                                        <li class="list-inline-item">
                                            <div class="popular-box d-flex align-items-center">



                                                <a
                                                    href="{{ route('categorysearch', ['title' => $title, 'category_slug' => $category->slug]) }}">
                                                    <button type="submit"
                                                        class="btn btn-outline-primary">{{ $category->name }}</button></a>

                                                {{-- <h6 class="fs-14 mb-0">{{$category->name}}</h6> --}}
                                            </div>
                                        </li>
                                    @endforeach

                                    {{-- @foreach ($categories as $category)
                                {{ $category->name }}
                                @endforeach --}}

                                </ul>
                            </div><!--end wedget-popular-title-->

                            <!-- Job-list -->
                            <div>
                                <style>
                                    /* Create a flex container */
                                    .flex-container {
                                        display: flex;
                                    }

                                    /* Style the child elements */
                                    .flex-item {
                                        margin: 10px;
                                        padding: 10px;
                                        border: 1px solid #ccc;
                                    }
                                </style>
                                @if (is_null($search))
                                    <h3>No results found for "{{ $title }}"</h3>
                                @else
                                    @foreach ($search as $result)
                                        <div class="job-box bookmark-post card mt-5">
                                            <div class="p-4">
                                                <div class="row">
                                                    <div class="flex-container">
                                                        @if (!empty($result->regulation_doc) && !empty($result->regulation_doc2))
                                                            <div class="flex-item">
                                                                <!-- Content of Element 1 -->
                                                                <img style="height: 100px;"
                                                                    src="{{ asset('public/users/assets/images/pdfimg.png') }}"
                                                                    alt="" class="img-fluid rounded-3">
                                                            </div>

                                                            <!-- Element 2 -->
                                                            <div class="flex-item">
                                                                <!-- Content of Element 2 -->
                                                                <img style="height: 100px;"
                                                                    src="{{ asset('public/users/assets/images/docx_icon.png') }}"
                                                                    alt="" class="img-fluid rounded-3">
                                                            </div>
                                                        @elseif (!empty($result->regulation_doc))
                                                            <div class="flex-item">
                                                                <!-- Content of Element 1 -->
                                                                <img style="height: 100px;"
                                                                    src="{{ asset('public/users/assets/images/pdfimg.png') }}"
                                                                    alt="" class="img-fluid rounded-3">
                                                            </div>
                                                        @elseif (!empty($result->regulation_doc2))
                                                            <!-- Only data2 is not empty -->
                                                            <div class="flex-item">
                                                                <!-- Content of Element 2 -->
                                                                <img style="height: 100px;"
                                                                    src="{{ asset('public/users/assets/images/docx_icon.png') }}"
                                                                    alt="" class="img-fluid rounded-3">
                                                            </div>
                                                        @else
                                                            <!-- Both data1 and data2 are empty -->
                                                            <p>No data available.</p>
                                                        @endif


                                                        <!-- Element 1 -->

                                                    </div>
                                                    <div class="col-lg-10">
                                                        <div class="mt-3 mt-lg-0">
                                                            <h5 class="fs-17 mb-1"><a href="#"
                                                                    class="text-dark">{{ $result->title }}</a> </h5>

                                                            <div class="mt-2">
                                                                <span
                                                                    class="badge bg-soft-success mt-1">{{ $result->entity->name }}</span>
                                                                <span
                                                                    class="badge bg-soft-success mt-1">{{ $result->category->name }}</span>
                                                                <span
                                                                    class="badge bg-soft-success mt-1">{{ $result->subcategory->name }}</span>
                                                            </div>
                                                        </div>
                                                    </div><!--end col-->
                                                </div><!--end row-->

                                            </div>
                                            <div class="p-3 bg-light">
                                                <div class="row justify-content-between">
                                                    <div class="col-md-8">

                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-md-3">
                                                        <div class="text-md-end">
                                                            <a href="#applyNow" data-bs-toggle="modal"
                                                                class="primary-link">Read more <i
                                                                    class="mdi mdi-chevron-double-right"></i></a>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                </div>
                                                <!--end row-->
                                            </div>
                                        </div>
                                    @endforeach
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-12">
                                    <center>
                                        <nav aria-label="Page navigation example">

                                            {{ $search->links('pagination::bootstrap-4', ['pagination job-pagination mb-0 justify-content-center' => 'pagination justify-content-center']) }}
                                            {{-- <ul class="pagination job-pagination mb-0 justify-content-center">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="javascript:void(0)" tabindex="-1">
                                                    <i class="mdi mdi-chevron-double-left fs-15"></i>
                                                </a>
                                            </li>
                                            <li class="page-item active"><a class="page-link" href="javascript:void(0)">1</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0)">2</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0)">3</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0)">4</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="javascript:void(0)">
                                                    <i class="mdi mdi-chevron-double-right fs-15"></i>
                                                </a>
                                            </li>
                                        </ul> --}}
                                        </nav>
                                    </center>
                                </div><!--end col-->
                                @endif
                            </div>


                            <!--end job-box-->




                            <!-- End Job-list -->


                        </div>

                    </div>
                    <!-- START SIDE-BAR -->

                    <!-- END SIDE-BAR -->
                </div>
            </div>
        </section>
        <!-- END JOB-LIST -->

        <!-- START APPLY MODAL -->
        <div class="modal fade" id="applyNow" tabindex="-1" aria-labelledby="applyNow" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body p-5">
                        <div class="text-center mb-4">
                            <h5 class="modal-title" id="staticBackdropLabel">Apply For This Job</h5>
                        </div>
                        <div class="position-absolute end-0 top-0 p-3">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="mb-3">
                            <label for="nameControlInput" class="form-label">Name</label>
                            <input type="text" class="form-control" id="nameControlInput"
                                placeholder="Enter your name">
                        </div>
                        <div class="mb-3">
                            <label for="emailControlInput2" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="emailControlInput2"
                                placeholder="Enter your email">
                        </div>
                        <div class="mb-3">
                            <label for="messageControlTextarea" class="form-label">Message</label>
                            <textarea class="form-control" id="messageControlTextarea" rows="4" placeholder="Enter your message"></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="inputGroupFile01">Resume Upload</label>
                            <input type="file" class="form-control" id="inputGroupFile01">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Send Application</button>
                    </div>
                </div>
            </div>
        </div><!-- END APPLY MODAL -->

    </div>
    <style>
        @media only screen and (max-width: 600px) {
            .my-div {
                font-size: 0.8em;
                padding: 0;
                display: flex;
                flex-wrap: wrap;

            }

            li {
                padding: 0.5em;
            }
        }
    </style>

@endsection
