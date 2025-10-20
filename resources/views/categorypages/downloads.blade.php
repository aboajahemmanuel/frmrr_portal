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
                            {{-- <h3 class="mb-4">{{ $data->name}} </h3> --}}
                            <div class="page-next">
                                <nav class="d-inline-block" aria-label="breadcrumb text-center">
                                    <ol class="breadcrumb justify-content-center">
                                        <li class="breadcrumb-item" style="font-size: 19px"><a
                                                href="{{ url('/') }}">Home</a></li>

                                        <li class="breadcrumb-item active" aria-current="page" style="font-size: 19px">
                                            Document Downloads </li>
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
                        <br>
                        @if (\Session::has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                                <strong>{{ \Session::get('success') }}</strong>
                            </div>
                        @endif

                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Opps!</strong> Something went wrong, please check below errors.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <nav aria-label="Page navigation example">
                            <h4 class="fs-30 mb-1">Document Downloads</h4>
                            <ul class="pagination job-pagination mb-0  my-div">



                                {{-- @foreach ($alphas as $alp)
                           <a href="{{route('alphaname',['slug' => $data->slug, 'name' => $alp->name])}}"> <button type="button" class="btn btn-soft-primary">{{$alp->name}} </button></a> &nbsp;
                                                    
                        @endforeach --}}


                            </ul>
                        </nav>

                        <section class="section">
                            <div class="container">
                                <div class="row align-items-center">

                                </div><!--end row-->
                                <div class="row">
                                    <div class="col-lg-12">
                                        @foreach ($data as $download)
                                            <div class="job-box card mt-4">
                                                <div class="card-body p-4">
                                                    <div class="row">
                                                        <div class="col-lg-1">
                                                            @if (!empty($download->regulation->regulation_doc) && !empty($download->regulation->regulation_doc2))
                                                                <img src="{{ asset('public/users/assets/images/pdfimg.png') }}"
                                                                    alt="" class="avatar-md ">
                                                                <img src="{{ asset('public/users/assets/images/docx_icon.png') }}"
                                                                    alt="" class="avatar-md ">
                                                            @elseif (!empty($download->regulation->regulation_doc))
                                                                <div class="flex-item">
                                                                    <!-- Content of Element 1 -->
                                                                    <img style="height: 100px;"
                                                                        src="{{ asset('public/users/assets/images/pdfimg.png') }}"
                                                                        alt="" class="img-fluid rounded-3">
                                                                </div>
                                                            @elseif (!empty($download->regulation->regulation_doc2))
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
                                                        </div>
                                                        <!--end col-->
                                                        <div class="col-lg-9">
                                                            <div class="mt-3 mt-lg-0">

                                                                <h5 class="fs-17 mb-1"><a href=""
                                                                        class="text-dark">{{ optional($download->regulation)->title }}</a>
                                                                    <small class="text-muted fw-normal"></small></h5>
                                                                <ul class="list-inline mb-0">
                                                                    <li class="list-inline-item">
                                                                        <p class="text-muted fs-14 mb-0">
                                                                            {{ optional($download->year)->name }}

                                                                        </p>
                                                                    </li>
                                                                    <li class="list-inline-item">
                                                                        <p class="text-muted fs-14 mb-0">
                                                                            {{ optional($download->month)->name }}

                                                                        </p>
                                                                    </li>
                                                                    <li class="list-inline-item">
                                                                        <p class="text-muted fs-14 mb-0"> ₦@php echo number_format(optional($download->regulation)->price) @endphp
                                                                        </p>
                                                                    </li>
                                                                </ul>
                                                                {{-- <div class="mt-2">
                                                            <span class="badge bg-soft-success mt-1">
                                                                {{$download->regulation->entity->name }}</span>
                                                            <span class="badge bg-soft-success mt-1">{{ $download->regulation->category->name }}</span>
                                                            <span class="badge bg-soft-success mt-1">{{ $download->regulation->subcategory->name }}</span>
                                                        </div> --}}
                                                            </div>
                                                        </div><!--end col-->
                                                        <div class="col-lg-2 align-self-center">
                                                            <ul class="list-inline mt-3 mb-0">
                                                                @if (!empty($download->regulation->regulation_doc))
                                                                    <li class="list-inline-item" data-bs-toggle="tooltip"
                                                                        data-bs-placement="top"
                                                                        title="Download PDF Document">
                                                                        <a href="{{ url('/') }}/public/pdf_documents/{{ $download->regulation->regulation_doc }}"
                                                                            class="avatar-sm bg-soft-success d-inline-block text-center rounded-circle fs-18">
                                                                            <i class="mdi mdi-download"></i>
                                                                        </a>
                                                                    </li>
                                                                @endif


                                                                @if (!empty($download->regulation->regulation_doc2))
                                                                    <li class="list-inline-item" data-bs-toggle="tooltip"
                                                                        data-bs-placement="top"
                                                                        title="Download Word Document">
                                                                        <a href="{{ url('/') }}/public/word_documents/{{ $download->regulation->regulation_doc2 }}"
                                                                            class="avatar-sm bg-soft-success d-inline-block text-center rounded-circle fs-18">
                                                                            <i class="mdi mdi-download"></i>
                                                                        </a>
                                                                    </li>
                                                                @endif


                                                                {{-- <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#deleteModal-{{$download->id}}"  class="avatar-sm bg-soft-danger d-inline-block text-center rounded-circle fs-18">
                                                                <i class="uil uil-trash-alt"></i>
                                                            </a>
                                                        </li> --}}
                                                            </ul>
                                                        </div>
                                                    </div><!--end row-->
                                                </div>
                                            </div><!--end job-box-->
                                        @endforeach


                                    </div><!--end col-->
                                </div><!--end row-->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <center>
                                            <nav aria-label="Page navigation example">

                                                {{ $data->links('pagination::bootstrap-4', ['pagination job-pagination mb-0 justify-content-center' => 'pagination justify-content-center']) }}

                                            </nav>
                                        </center>
                                    </div><!--end col-->


                                </div><!--end row-->
                            </div><!--end container-->
                        </section>


                    </div><!--end col-->



                </div><!--end row-->


            </div><!--end container-->
        </section>
        <!-- END JOB-LIST -->
        @foreach ($data as $download)
            <div class="modal fade" id="deleteModal-{{ $download->id }}" tabindex="-1" aria-labelledby="deleteModal"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Delete
                                {{ optional($download->regulation)->title }} ?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div>
                                <h6 class="text-danger"><i class="uil uil-exclamation-triangle"></i>Warning: Are you sure
                                    you want to delete ?</h6>
                                <p class="text-muted"> Document will be permenently removed from your list and you won't be
                                    able to see them again.</p>
                            </div>
                        </div>
                        <form action="{{ route('deletedownload', $download->id) }}" method="POST">
                            @csrf
                            <input hidden type="text" value="1" name="user_del">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn-sm"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger btn-sm">Yes, delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

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
