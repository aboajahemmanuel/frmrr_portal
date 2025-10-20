@extends('layouts.admin')

@section('content')


<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h6 class="page-title">Transactions</h6>
                        {{-- <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="#">Veltrix</a></li>
                            <li class="breadcrumb-item"><a href="#">Tables</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data tables</li>
                        </ol> --}}
                    </div>
                   
            </div>
            <!-- end page title -->



          
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if (\Session::has('success'))
                           
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                               </div>
                               @endif

                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>S/N</th>
                                        <th>Customer Name</th>
                                        <th>Document</th>
                                        <th>Amount</th>
                                        <th>Transaction Ref</th>
                                        <th>Transaction Date</th>
                                        <th>Status</th>
                                        {{-- <th>Action</th> --}}
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $transaction)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{optional($transaction->user)->name}}</td>
                                            <td>{{optional($transaction->regulation)->title}}</td>
                                            <td>{{$transaction->amount}}</td>
                                            <td>{{$transaction->reference}}</td>
                                            <td>{{$transaction->created_at}}</td>
                                            <td>
                                                @if($transaction->status == 'success')
                                                <span class="badge fmdq_Blue">Success</span>
                                                @endif
                                                @if($transaction->status == 'successful')
                                                <span class="badge fmdq_Blue">Success</span>
                                                @endif
                                                @if($transaction->status =='')
                                                <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>

                                            {{-- <td>
                                                <a class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#view">View Reciept</a>
                                            </td> --}}
                                                
                                            
                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                           

                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->

        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

  


</div>















<script>
    function loading() {
  
  $(".btn .fa-spinner").show();
  $(".btn .btn-text").html("");
  
  }
  </script>
  

@endsection