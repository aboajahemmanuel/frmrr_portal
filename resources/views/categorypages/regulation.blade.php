@extends('layouts.app')

@section('content')
    <div class="main-content">

        <div class="page-content">

            <!-- Start home -->
            <section class="page-title-box">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="text-center text-white">
                                <h3 class="mb-4">{{ $regulations->document_tag }}</h3>
                                <div class="page-next">
                                    <nav class="d-inline-block" aria-label="breadcrumb text-center">
                                        <ol class="breadcrumb justify-content-center">
                                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                                            <li class="breadcrumb-item"><a
                                                    href="#">{{ $regulations->category->name }}</a></li>

                                            <li class="breadcrumb-item active"><a href="javascript:void(0)">A-Z
                                                    {{ $regulations->category->name }}</a></li>

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


            <!-- START JOB-DEATILS -->
            <section class="section">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card job-detail overflow-hidden">
                                {{-- <div>
                                            <img src="{{ asset('public/users/assets/images/pdfimg.png')}}" style="height: 100px" alt="" class="img-fluid">
                                            <div class="job-details-compnay-profile">
                                                <img src="assets/images/featured-job/img-10.png" alt="" class="img-fluid rounded-3 rounded-3">
                                            </div>
                                        </div> --}}
                                <div class="card-body p-4">
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
                                        <div class="row">
                                            <div class="flex-container">
                                                @if (!empty($regulations->regulation_doc) && !empty($regulations->regulation_doc2))
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
                                                @elseif (!empty($regulations->regulation_doc))
                                                    <div class="flex-item">
                                                        <!-- Content of Element 1 -->
                                                        <img style="height: 100px;"
                                                            src="{{ asset('public/users/assets/images/pdfimg.png') }}"
                                                            alt="" class="img-fluid rounded-3">
                                                    </div>
                                                @elseif (!empty($regulations->regulation_doc2))
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

                                            <div class="col-md-12">

                                                <h5 class="mb-1">{{ $regulations->title }}</h5>

                                            </div><!--end col-->

                                        </div><!--end row-->
                                    </div>

                                    <div class="mt-4">
                                        <div class="row g-2">
                                            <div class="col-lg-3">
                                                <div class="border rounded-start p-3">
                                                    <p class="text-muted mb-0 fs-13" style="font-weight: bolder">Price</p>
                                                    <p class="fw-medium fs-15 mb-0" style="font-weight: bolder">
                                                        ₦@php echo number_format($regulations->price) @endphp</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="border rounded-start p-3">
                                                    <p class="text-muted mb-0 fs-13" style="font-weight: bolder">Month</p>
                                                    <p class="fw-medium fs-15 mb-0" style="font-weight: bolder">
                                                        {{ $regulations->month->name }}</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="border rounded-start p-3">
                                                    <p class="text-muted mb-0 fs-13" style="font-weight: bolder">Year</p>
                                                    <p class="fw-medium fs-15 mb-0" style="font-weight: bolder">
                                                        {{ $regulations->year->name }}</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="border rounded-start p-3">
                                                    <p class="text-muted mb-0 fs-13" style="font-weight: bolder">Entity</p>
                                                    <p class="fw-medium fs-15 mb-0" style="font-weight: bolder">
                                                        {{ optional($regulations->entity)->name }}</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="border p-3">
                                                    <p class="text-muted fs-13 mb-0" style="font-weight: bolder">Category
                                                    </p>
                                                    <p class="fw-medium mb-0" style="font-weight: bolder">
                                                        {{ $regulations->category->name }}</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="border p-3">
                                                    <p class="text-muted fs-13 mb-0" style="font-weight: bolder">Sub
                                                        Category</p>
                                                    <p class="fw-medium mb-0" style="font-weight: bolder">
                                                        {{ optional($regulations->subcategory)->name }}</p>
                                                </div>
                                            </div>

                                        </div>
                                    </div><!--end Experience-->

                                    {{-- <div class="mt-4">
                                    <h5 class="mb-3">Job Description</h5>
                                    <div class="job-detail-desc">
                                        <p class="text-muted mb-0">As a Product Designer, you will work within a Product Delivery Team fused with UX, engineering, product and data talent. You will help the team design beautiful interfaces that solve business challenges for our clients. We work with a number of Tier 1 banks on building web-based applications for AML, KYC and Sanctions List management workflows. This role is ideal if you are looking to segue your career into the FinTech or Big Data arenas.</p>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h5 class="mb-3">Responsibilities</h5>
                                    <div class="job-detail-desc mt-2">
                                        <p class="text-muted">As a Product Designer, you will work within a Product Delivery Team fused with UX, engineering, product and data talent.</p>
                                        <ul class="job-detail-list list-unstyled mb-0 text-muted">
                                            <li><i class="uil uil-circle"></i> Have sound knowledge of commercial activities.</li>
                                            <li><i class="uil uil-circle"></i> Build next-generation web applications with a focus on the client side</li> 
                                            <li><i class="uil uil-circle"></i> Work on multiple projects at once, and consistently meet draft deadlines</li> 
                                            <li><i class="uil uil-circle"></i> have already graduated or are currently in any year of study</li> 
                                            <li><i class="uil uil-circle"></i> Revise the work of previous designers to create a unified aesthetic for our brand materials</li> 
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <h5 class="mb-3">Qualification </h5>
                                    <div class="job-detail-desc mt-2">
                                        <ul class="job-detail-list list-unstyled mb-0 text-muted">
                                            <li><i class="uil uil-circle"></i> B.C.A / M.C.A under National University course complete.</li> 
                                            <li><i class="uil uil-circle"></i> 3 or more years of professional design experience</li> 
                                            <li><i class="uil uil-circle"></i> have already graduated or are currently in any year of study</li> 
                                            <li><i class="uil uil-circle"></i> Advanced degree or equivalent experience in graphic and web design</li> 
                                        </ul>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h5 class="mb-3">Skill & Experience</h5>
                                    <div class="job-details-desc">
                                        <ul class="job-detail-list list-unstyled mb-0 text-muted">
                                            <li><i class="uil uil-circle"></i> Understanding of key Design Principal</li>
                                            <li><i class="uil uil-circle"></i> Proficiency With HTML, CSS, Bootstrap</li> 
                                            <li><i class="uil uil-circle"></i> Wordpress: 1 year (Required)</li> 
                                            <li><i class="uil uil-circle"></i> Experience designing and developing responsive design websites</li>
                                            <li><i class="uil uil-circle"></i> web designing: 1 year (Preferred)</li>
                                        </ul>
                                        <div class="mt-4">
                                            <span class="badge bg-primary">PHP</span>
                                            <span class="badge bg-primary">JS</span>
                                            <span class="badge bg-primary">Marketing</span>
                                            <span class="badge bg-primary">REACT</span>
                                            <span class="badge bg-primary">PHOTOSHOP</span>
                                        </div>
                                    </div>
                                </div> --}}

                                    <div class="mt-4 pt-3">
                                        <ul class="list-inline mb-0">
                                            {{-- <li class="list-inline-item mt-1">
                                            Share this job:
                                        </li> --}}
                                            @if (Auth::check())
                                                <li class="list-inline-item mt-1">
                                                    <a href="{{ route('payment', $regulations->slug) }}"
                                                        class="btn btn-primary btn-hover">Pay To Download<i
                                                            class="uil uil-download-alt"></i></a>

                                                    {{-- <a href="javascript:void(0)" class="btn btn-primary btn-hover"><i class="uil uil-download-alt"></i> Download</a> --}}
                                                </li>
                                            @endif
                                            @guest
                                                <li class="list-inline-item mt-1">
                                                    <a href="{{ route('login') }}" target="_blank"
                                                        class="btn btn-secondary btn-hover"><i
                                                            class="uil uil-lock-alt"></i>Login to download</a>
                                                </li>
                                            @endguest


                                        </ul>
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end job-detail-->

                            {{-- <div class="mt-4">
                            <h5>Related Jobs</h5>
                            <div class="job-box card mt-4">
                                <div class="p-4">
                                    <div class="row">
                                        <div class="col-lg-1">
                                            <img src="assets/images/featured-job/img-01.png" alt="" class="img-fluid rounded-3">
                                        </div><!--end col-->
                                        <div class="col-lg-10">
                                            <div class="mt-3 mt-lg-0">
                                                <h5 class="fs-17 mb-1"><a href="job-details.html" class="text-dark">HTML Developer</a> <small class="text-muted fw-normal">(0-2 Yrs Exp.)</small></h5>
                                                <ul class="list-inline mb-0">
                                                    <li class="list-inline-item">
                                                        <p class="text-muted fs-14 mb-0">Jobcy Technology Pvt.Ltd</p>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <p class="text-muted fs-14 mb-0"><i class="mdi mdi-map-marker"></i> California</p>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <p class="text-muted fs-14 mb-0"><i class="uil uil-wallet"></i> $250 - $800 / month</p>
                                                    </li>
                                                </ul>
                                                <div class="mt-2">
                                                    <span class="badge bg-soft-success mt-1">Full Time</span>
                                                    <span class="badge bg-soft-warning mt-1">Urgent</span>
                                                    <span class="badge bg-soft-info mt-1">Private</span>
                                                </div>
                                            </div>
                                        </div><!--end col-->
                                    </div><!--end row-->
                                    <div class="favorite-icon">
                                        <a href="javascript:void(0)"><i class="uil uil-heart-alt fs-18"></i></a>
                                    </div>
                                </div>
                                <div class="p-3 bg-light">
                                    <div class="row justify-content-between">
                                        <div class="col-md-8">
                                            <div>
                                                <ul class="list-inline mb-0">
                                                    <li class="list-inline-item"><i class="uil uil-tag"></i> Keywords :</li>
                                                    <li class="list-inline-item"><a href="javascript:void(0)" class="primary-link text-muted">Ui designer</a>,</li>
                                                    <li class="list-inline-item"><a href="javascript:void(0)" class="primary-link text-muted">developer</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-md-3">
                                            <div class="text-md-end">
                                                <a href="javascript:void(0)" class="primary-link">Apply Now <i class="mdi mdi-chevron-double-right"></i></a>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </div>
                            </div>
                          

                            <div class="job-box bookmark-post card mt-4">
                                <div class="p-4">
                                    <div class="row">
                                        <div class="col-lg-1">
                                            <img src="assets/images/featured-job/img-02.png" alt="" class="img-fluid rounded-3">
                                        </div><!--end col-->
                                        <div class="col-lg-10">
                                            <div class="mt-3 mt-lg-0">
                                                <h5 class="fs-17 mb-1"><a href="job-details.html" class="text-dark">Marketing Director</a> <small class="text-muted fw-normal">(2-4 Yrs Exp.)</small></h5>
                                                <ul class="list-inline mb-0">
                                                    <li class="list-inline-item">
                                                        <p class="text-muted fs-14 mb-0">Creative Agency</p>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <p class="text-muted fs-14 mb-0"><i class="mdi mdi-map-marker"></i> New York</p>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <p class="text-muted fs-14 mb-0"><i class="uil uil-wallet"></i> $250 - $800 / month</p>
                                                    </li>
                                                </ul>
                                                <div class="mt-2">
                                                    <span class="badge bg-soft-danger mt-1">Part Time</span>
                                                    <span class="badge bg-soft-info mt-1">Private</span>
                                                </div>
                                            </div>
                                        </div><!--end col-->
                                    </div><!--end row-->
                                    <div class="favorite-icon">
                                        <a href="javascript:void(0)"><i class="uil uil-heart-alt fs-18"></i></a>
                                    </div>
                                </div>
                                <div class="p-3 bg-light">
                                    <div class="row justify-content-between">
                                        <div class="col-md-8">
                                            <div>
                                                <ul class="list-inline mb-0">
                                                    <li class="list-inline-item"><i class="uil uil-tag"></i> Keywords :</li>
                                                    <li class="list-inline-item"><a href="javascript:void(0)" class="primary-link text-muted">Marketing</a>,</li>
                                                    <li class="list-inline-item"><a href="javascript:void(0)" class="primary-link text-muted">business</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-md-3">
                                            <div class="text-md-end">
                                                <a href="javascript:void(0)" class="primary-link">Apply Now <i class="mdi mdi-chevron-double-right"></i></a>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </div>
                            </div>
                            

                            <div class="job-box card mt-4">
                                <div class="p-4">
                                    <div class="row">
                                        <div class="col-lg-1">
                                            <img src="assets/images/featured-job/img-03.png" alt="" class="img-fluid rounded-3">
                                        </div><!--end col-->
                                        <div class="col-lg-10">
                                            <div class="mt-3 mt-lg-0">
                                                <h5 class="fs-17 mb-1"><a href="job-details.html" class="text-dark">HTML Developer</a> <small class="text-muted fw-normal">(2-4 Yrs Exp.)</small></h5>
                                                <ul class="list-inline mb-0">
                                                    <li class="list-inline-item">
                                                        <p class="text-muted fs-14 mb-0">Jobcy Technology Pvt.Ltd</p>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <p class="text-muted fs-14 mb-0"><i class="mdi mdi-map-marker"></i> California</p>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <p class="text-muted fs-14 mb-0"><i class="uil uil-wallet"></i> $250 - $800 / month</p>
                                                    </li>
                                                </ul>
                                                <div class="mt-2">
                                                    <span class="badge bg-soft-purple mt-1">Freelance</span>
                                                    <span class="badge bg-soft-blue mt-1">Internship</span>
                                                </div>
                                            </div>
                                        </div><!--end col-->
                                    </div><!--end row-->
                                    <div class="favorite-icon">
                                        <a href="javascript:void(0)"><i class="uil uil-heart-alt fs-18"></i></a>
                                    </div>
                                </div>
                                <div class="p-3 bg-light">
                                    <div class="row justify-content-between">
                                        <div class="col-md-8">
                                            <div>
                                                <ul class="list-inline mb-0">
                                                    <li class="list-inline-item"><i class="uil uil-tag"></i> Keywords :</li>
                                                    <li class="list-inline-item"><a href="javascript:void(0)" class="primary-link text-muted">Ui designer</a>,</li>
                                                    <li class="list-inline-item"><a href="javascript:void(0)" class="primary-link text-muted">developer</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-md-3">
                                            <div class="text-md-end">
                                                <a href="javascript:void(0)" class="primary-link">Apply Now <i class="mdi mdi-chevron-double-right"></i></a>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </div>
                            </div>
                          

                        </div>

                        <div class="text-center mt-4">
                            <a href="job-list.html" class="primary-link form-text">View More <i class="mdi mdi-arrow-right"></i></a>
                        </div> --}}







                        </div><!--end col-->


                    </div><!--end row-->
                </div><!--end container-->
            </section>
            <!-- START JOB-DEATILS -->

            <!-- START APPLY MODAL -->
            @if (Auth::check())
                <div class="modal fade" id="applyNow" tabindex="-1" aria-labelledby="applyNow" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <form method="post" action="{{ route('docpayment') }}">
                            @csrf

                            <div class="modal-content">
                                <div class="modal-body p-5">
                                    <div class="text-center mb-4">
                                        <h5 class="modal-title" id="staticBackdropLabel">{{ $regulations->document_tag }}
                                        </h5>
                                    </div>
                                    <div class="position-absolute end-0 top-0 p-3">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nameControlInput" class="form-label">Name</label>
                                        <input type="text" name="name" value="{{ Auth::user()->name }}" readonly
                                            class="form-control" id="nameControlInput" placeholder="Enter your name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="emailControlInput2" class="form-label">Email Address</label>
                                        <input type="email" name="email" readonly value="{{ Auth::user()->email }}"
                                            class="form-control" id="emailControlInput2" placeholder="Enter your email">
                                    </div>
                                    <div class="mb-3">
                                        <label for="emailControlInput2" class="form-label">Price</label>
                                        <input type="text" name="amount" value="{{ $regulations->price }}" readonly
                                            class="form-control" id="emailControlInput2" placeholder="Enter your email">
                                    </div>


                                    <input type="hidden" name="reference" value="{{ Paystack::genTranxRef() }}">
                                    <input type="hidden" name="regulation_id" value="{{ $regulations->id }}">
                                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                                    {{-- <div class="mb-4">
                            <label class="form-label" for="inputGroupFile01">Resume Upload</label>
                            <input type="file" class="form-control" id="inputGroupFile01">
                        </div> --}}
                                    <button type="submit" class="btn btn-primary w-100">Pay with Paystack</button>
                        </form>
                        <form method="POST" action="{{ route('pay') }}" id="paymentForm">
                            @csrf

                            <input type="hidden" name="regulation_id" value="{{ $regulations->id }}">
                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                            <input type="hidden" name="amount" value="{{ $regulations->price }}">
                            <button type="submit" class="btn btn-primary w-100">Pay with Flutterwave</button>

                        </form>
                    </div>
                </div>

        </div>
    </div><!-- END APPLY MODAL -->
    @endif

    </div>
    <!-- End Page-content -->





    <!--start back-to-top-->
    <button onclick="topFunction()" id="back-to-top">
        <i class="mdi mdi-arrow-up"></i>
    </button>
    <!--end back-to-top-->
    </div>
@endsection
