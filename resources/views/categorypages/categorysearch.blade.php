{{-- @if (is_null($search))
    <h3>No results found for {{$title}}</h3>
@else
   Good
@endif --}}





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

                    <div class="col-lg-9">
                        <div class="me-lg-5">
                            <div class="job-list-header">
                                <form method="GET" action="{{ route('search', $title) }}">


                                    <div class="row g-2">
                                        <div class="col-lg-8 col-md-6">
                                            <div class="filler-job-form">
                                                <i class="uil uil-book-alt"></i>
                                                <input hidden name="category_slug" value="{{ $data->slug }}">
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


                                                <form method="POST" action="{{ route('categorysearch', $category->id) }}">
                                                    @csrf
                                                    <input hidden name="title" value="{{ $title }}">
                                                    <button type="submit"
                                                        class="btn btn-outline-primary">{{ $category->name }}</button>
                                                </form>
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
                                @if (is_null($search))
                                    <h3>No results found for "{{ $title }}"</h3>
                                @else
                                    @foreach ($search as $result)
                                        <div class="job-box bookmark-post card mt-5">
                                            <div class="p-4">
                                                <div class="row">
                                                    <div class="col-lg-1">
                                                        <a href="#"><img
                                                                src="{{ asset('public/users/assets/images/pdfimg.png') }}"
                                                                alt="" class="img-fluid rounded-3"></a>
                                                    </div><!--end col-->
                                                    <div class="col-lg-10">
                                                        <div class="mt-3 mt-lg-0">
                                                            <h5 class="fs-17 mb-1"><a href="#"
                                                                    class="text-dark">{{ $result->title }}
</a> </h5>

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


    </div>


@endsection
