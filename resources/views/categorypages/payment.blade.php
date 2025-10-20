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
                            <h3 class="mb-4">{{$document_payment->title}}</h3>
                            <div class="page-next">
                                <nav class="d-inline-block" aria-label="breadcrumb text-center">
                                    <ol class="breadcrumb justify-content-center">
                                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                        <li class="breadcrumb-item"><a href="index.html">{{$document_payment->title}}</a></li>
                                        
                                        <li class="breadcrumb-item active"><a href="javascript:void(0)">A-Z 3</a></li>
                                       
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
                        d="M0,192L120,202.7C240,213,480,235,720,234.7C960,235,1200,213,1320,202.7L1440,192L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z"></path>
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
                                    <div class="row">
                                        <div class="col-md-12">
                                            
                                            
                                            
                                        </div><!--end col-->
                                        
                                    </div><!--end row-->    
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="candidate-list">
                                            <div class="candidate-list-box card mt-4">
                                                <div class="card-body p-4">
                                                    <div class="row align-items-center">
                                                        <div class="col-auto">
                                                            <div class="candidate-list-images">
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
                                                                    <div class="flex-container">
                                             @if (!empty($document_payment->regulation_doc) && !empty($document_payment->regulation_doc2))
                                                       <div class="flex-item">
                                                                                      <!-- Content of Element 1 -->
                                               <img style="height: 100px;"  src="{{ asset('public/users/assets/images/pdfimg.png')}}" alt="" class="img-fluid rounded-3">
                                            </div>

                                            <!-- Element 2 -->
                                            <div class="flex-item">
                                                <!-- Content of Element 2 -->
                                               <img style="height: 100px;" src="{{ asset('public/users/assets/images/docx_icon.png')}}" alt="" class="img-fluid rounded-3">
                                            </div>
                                                    @elseif (!empty($document_payment->regulation_doc))
                                                     <div class="flex-item">
                                                                                      <!-- Content of Element 1 -->
                                               <img style="height: 100px;"  src="{{ asset('public/users/assets/images/pdfimg.png')}}" alt="" class="img-fluid rounded-3">
                                            </div>
                                                    @elseif (!empty($document_payment->regulation_doc2))
                                                        <!-- Only data2 is not empty -->
                                                         <div class="flex-item">
                                                <!-- Content of Element 2 -->
                                               <img style="height: 100px;" src="{{ asset('public/users/assets/images/docx_icon.png')}}" alt="" class="img-fluid rounded-3">
                                            </div>
                                                    @else
                                                        <!-- Both data1 and data2 are empty -->
                                                        <p>No data available.</p>
                                                    @endif
                                                             

                                            <!-- Element 1 -->
                                            
                                        </div>
                                                               
                                                            </div>
                                                           <h4>{{$document_payment->title}}</h4>
                                                        </div><!--end col-->
                                                        
                                                        <div class="mt-4">
                                                            <div class="row g-2">
                                                                <div class="col-lg-3">
                                                                    <div class="border rounded-start p-3">
                                                                        <p class="text-muted mb-0 fs-13" style="font-weight: bolder">Price</p>
                                                                        <p class="fw-medium fs-15 mb-0" style="font-weight: bolder">
                                                                            ₦@php echo number_format($document_payment->price) @endphp</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <div class="border rounded-start p-3">
                                                                        <p class="text-muted mb-0 fs-13" style="font-weight: bolder">Month</p>
                                                                        <p class="fw-medium fs-15 mb-0" style="font-weight: bolder">{{$document_payment->month->name}}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <div class="border rounded-start p-3">
                                                                        <p class="text-muted mb-0 fs-13" style="font-weight: bolder">Year</p>
                                                                        <p class="fw-medium fs-15 mb-0" style="font-weight: bolder">{{$document_payment->year->name}}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <div class="border rounded-start p-3">
                                                                        <p class="text-muted mb-0 fs-13" style="font-weight: bolder">Entity</p>
                                                                        <p class="fw-medium fs-15 mb-0" style="font-weight: bolder">{{optional($document_payment->entity)->name}}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <div class="border p-3">
                                                                        <p class="text-muted fs-13 mb-0" style="font-weight: bolder">Category</p>
                                                                        <p class="fw-medium mb-0" style="font-weight: bolder">{{$document_payment->category->name}}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    <div class="border p-3">
                                                                        <p class="text-muted fs-13 mb-0" style="font-weight: bolder">Sub Category</p>
                                                                        <p class="fw-medium mb-0" style="font-weight: bolder">{{optional($document_payment->subcategory)->name}}</p>
                                                                    </div>
                                                                </div>
                                                             
                                                            </div>
                                                        </div><!--end Experience-->
    
                                                       
                                                    </div><!--end row-->
                                                   
                                                </div>
                                               
                                            </div> <!--end card-->
                                            <div class="col-lg-12">
                                                
                                               
                                                <div class="text-center mt-5">


                                                 

                                                   
    
               

                                            <form name="payForm" id="payForm" method="POST" action="{{route('paystore')}}">
                                                @csrf
                                                <input hidden type="text" name="email" value="{{Auth::user()->email}}">
                                                <input hidden type="text" name="name" value="{{ Auth::user()->name}}">
                                                <input hidden type="text" name="phone" value="{{Auth::user()->phone}}">
                                                <input hidden type="text" name="price" value="{{$document_payment->price}}">
                                                <input hidden type="text" name="user_id" value="{{Auth::user()->id}}">
                                                <input hidden type="text" name="regulation_id" value="{{$document_payment->id}}">
                                            </form>
        
                                                                
                                                                
                                                    <a  onclick="event.preventDefault();
                                                    document.getElementById('payForm').submit();" class="btn btn-primary btn-hover mt-2">Make Payment To Download</a>
                                                    
                                                    {{-- <a href="{{ route('pay')}}"  onclick="event.preventDefault();
                                                    document.getElementById('flutterwave-form').submit();" class="btn btn-warning btn-hover mt-2 ms-md-2">Flutterwave</a> --}}

                                                    {{-- <a href="{{ route('pay')}}"  onclick="event.preventDefault();
                                                    document.getElementById('flutterwave-form').submit();" class="btn btn- btn-hover mt-2 ms-md-2"><img src="{{ asset('public/assets/images/flutterwave.png')}}" height="50" alt="" class="logo-dark" /></a> --}}
                                                </div>
                                            </div>
                                            


                                            
                                            
                                        </div><!--end candidate-list-->
                                    </div><!--end col-->
                                </div><!--end row-->

                                

                                

                               
                            </div><!--end card-body-->
                        </div><!--end job-detail-->

                       


                    

                    </div><!--end col-->

                    
                </div><!--end row-->
            </div><!--end container-->
        </section>
        <!-- START JOB-DEATILS -->

        <!-- START APPLY MODAL -->
      

    </div>
    <!-- End Page-content -->

 



    <!--start back-to-top-->
    <button onclick="topFunction()" id="back-to-top">
        <i class="mdi mdi-arrow-up"></i>
    </button>
    <!--end back-to-top-->
</div>



@if(Auth::check())
<div class="" style="display: none">
    <div class="">
        <form method="post" id="flutterwave-form" action="{{route('pay')}}">
            @csrf
       
        
                <button type="submit" class="btn btn-primary w-100">Pay with Paystack</button>
           
                
                    <input type="hidden" name="regulation_id" value="{{$document_payment->id}}"> 
                    <input type="hidden" name="user_id" value="{{ Auth::user()->id}}"> 
                    <input type="hidden" name="email" value="{{ Auth::user()->email}}"> 
                    <input type="hidden" name="amount" value="{{$document_payment->price}}"> 
                    
                   
                </form>
            </div>
        </div>
    
    </div>
</div><!-- END APPLY MODAL -->
@endif





@if(Auth::check())
<div class="" style="display: none">
    <div class="">
        <form method="post" id="paystack-form" action="{{route('docpayment')}}">
            @csrf
       
        <div class="modal-content">
            <div class="modal-body p-5">
                <div class="text-center mb-4">
                    <h5 class="modal-title" id="staticBackdropLabel">{{$document_payment->document_tag}}</h5>
                </div>
                <div class="position-absolute end-0 top-0 p-3">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="mb-3">
                    <label for="nameControlInput" class="form-label">Name</label>
                    <input type="text" name="name" value="{{ Auth::user()->name}}" readonly class="form-control" id="nameControlInput" placeholder="Enter your name">
                </div>
                <div class="mb-3">
                    <label for="emailControlInput2" class="form-label">Email Address</label>
                    <input type="email" name="email" readonly value="{{ Auth::user()->email}}" class="form-control" id="emailControlInput2" placeholder="Enter your email">
                </div>
                <div class="mb-3">
                    <label for="emailControlInput2" class="form-label">Price</label>
                    <input type="text" name="amount" value="{{$document_payment->price}}" readonly class="form-control" id="emailControlInput2" placeholder="Enter your email">
                </div>
                <input type="hidden" name="reference" value="{{ Paystack::genTranxRef() }}"> 
                <input type="hidden" name="regulation_id" value="{{$document_payment->id}}"> 
                <input type="hidden" name="user_id" value="{{ Auth::user()->id}}"> 
                <button type="submit" class="btn btn-primary w-100">Pay with Paystack</button>
           
            </div>
        </div>
    
    </div>
</div><!-- END APPLY MODAL -->
@endif

@endsection