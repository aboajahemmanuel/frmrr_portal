@extends('layouts.admin')

@section('content')



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>


<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h6 class="page-title">Add News</h6>
                        <ol class="breadcrumb m-0">
                           
                            <li class="breadcrumb-item"><a href="{{url('news')}}">News</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add News</li>
                        </ol>
                    </div>
                   
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

                              
                             
                               <div class="row">
                                
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            
                                                <form class="row g-3 needs-validation" novalidate method="POST" action="{{ route('newsStore')}}" enctype="multipart/form-data">
                                                    @csrf
                                            
                                                <div class="col-md-12">
                                                    <label for="validationCustom01" class="form-label">Title</label>
                                                    <input type="text" name="title" class="form-control" id="validationCustom01" placeholder="Investment" required>
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                                <!-- end col -->

                                               

                                                <div class="col-md-6">
                                                    <label for="validationCustom04" class="form-label">Category</label>
                                                   
                                                        <select class="form-select" name="category" required >
                                                           
                                                    @php
                                                     $categorieslist =  \App\Models\Category::all();
                                                     
                                                    @endphp
                                                   
                                                    @foreach ($categorieslist as $category)
                                                    <option selected readonly value="{{ $category->name }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                                
                                                    <div class="invalid-feedback">
                                                        Please select a category.
                                                    </div>
                                                </div>

                                           


                                                 <div class="col-md-12">
                                                    <label for="validationCustom04" class="form-label">News Content</label>
                                                   
                                                   <textarea  required class="form-control" name="news_content" id="" cols="30" rows="10"></textarea>
                                                </div>


                                               
                                           
                                               
                                                <!-- end col -->
                                                <div class="col-12">
                                                    <button class="btn btn-primary" type="submit">Submit</button>
                                                </div>
                                                <!-- end col -->
                                            </form><!-- end form -->
                                        </div><!-- end cardbody -->
                                    </div><!-- end card -->
                                </div>
                                <!-- end col -->
                               
                                <!-- end col -->
                            </div>
                           




                          
                           
                             
                                
                                
                                </div>

                               
                           

                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->

        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

  


    <script type="text/javascript">
        $(document).ready(function () {
            $('#country').on('change', function () {
                // alert("Hello! I am an alert box!");
                var countryId = this.value;
                $('#state').html('');
                $.ajax({
                    url: '{{ route('getCategory') }}?category_id='+countryId,
                    type: 'get',
                    success: function (res) {
                        $('#state').html('<option selected disabled value="">Choose...</option>');
                        $.each(res, function (key, value) {
                            $('#state').append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                        });
                        $('#city').html('<option value="">Select City</option>');
                    }
                });
            });
           
        });


        document.getElementById("selectOption").addEventListener("change", function() {
  let selectedOption = this.value;
  let options = ["rules-regulations", "guidelines", "market-notices", "market-bulletins", "market-circulars"];
  
  options.forEach(function(option) {
    if (option === selectedOption) {
      document.getElementById(option).style.display = "block";
    } else {
      document.getElementById(option).style.display = "none";
    }
  });
});

    </script>

   
  
</div>







































@endsection