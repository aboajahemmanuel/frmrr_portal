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
                        <h6 class="page-title">Add Regulation</h6>
                        <ol class="breadcrumb m-0">
                           
                            <li class="breadcrumb-item"><a href="{{url('regulations')}}">Regulations</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Regulation</li>
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

                              
                              @if($selectedValue  == 'rules-regulations')
                               <div class="row">
                                <center><h3>Rules Regulations</h3></center>
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            
                                                <form class="row g-3 needs-validation" novalidate method="POST" action="{{ route('regulations.store')}}" enctype="multipart/form-data">
                                                    @csrf
                                            
                                                <div class="col-md-12">
                                                    <label for="validationCustom01" class="form-label">Title</label>
                                                    <input type="text" name="title" class="form-control" id="validationCustom01" placeholder="Investment" required>
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>
                                                <!-- end col -->

                                                <div class="col-md-4">
                                                    <label for="validationCustom02" class="form-label">Price</label>
                                                    <input  type="number" name="price"  class="form-control" id="validationCustom02 year" placeholder="2000" required>
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>



                                                <div class="col-md-4">
                                                    <label for="validationCustom04" class="form-label">Year</label>
                                                    <select class="form-select" name="year_id"  required>
                                                        <option selected disabled value="">Choose...</option>
                                                        @foreach ($years as $year)
                                                        <option value="{{ $year->id }}">{{ $year->name }}</option>
                                                        @endforeach
                                                      
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Please select Year.
                                                    </div>
                                                </div>



                                                <div class="col-md-4">
                                                    <label for="validationCustom04" class="form-label">Month</label>
                                                    <select class="form-select" name="month_id"  required>
                                                        <option selected disabled value="">Choose...</option>
                                                        @foreach ($months as $month)
                                                        <option value="{{ $month->id }}">{{ $month->name }}</option>
                                                        @endforeach
                                                      
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Please select Month.
                                                    </div>
                                                </div>


                                                {{-- <div class="col-md-6">
                                                    <label for="validationCustom02" class="form-label">Year</label>
                                                    <input  type="date"  class="form-control" id="validationCustom02 year" placeholder="Otto" required>
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div> --}}

                                                {{-- <div class="col-md-6">
                                                    <label for="validationCustom02" class="form-label">Month</label>
                                                    <input type="text" name="month" class="form-control" id="validationCustom02" placeholder="Otto" required>
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div> --}}
                                               
                                               
                                               
                                                <div class="col-md-4">
                                                    <label for="validationCustom04" class="form-label">Entity</label>
                                                    <select class="form-select" name="entity_id"  required>
                                                        <option selected disabled value="">Choose...</option>
                                                        @foreach ($entities as $entity)
                                                        <option value="{{ $entity->id }}">{{ $entity->name }}</option>
                                                        @endforeach
                                                      
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Please select an entity.
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="validationCustom04" class="form-label">Category</label>
                                                   
                                                        <select class="form-select" name="category_id" required >
                                                           
                                                    @php
                                                     $categorieslist =  \App\Models\Category::where('slug','=',$selectedValue)->get();
                                                     
                                                    @endphp
                                                   
                                                    @foreach ($categorieslist as $category)
                                                    <option selected readonly value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                                
                                                    <div class="invalid-feedback">
                                                        Please select a category.
                                                    </div>
                                                </div>


                                                <div class="col-md-4">
                                                    <label for="validationCustom04" class="form-label">Sub Category</label>
                                                    <select name="subcategory_id" class="form-select" required>
                                                        @php
                                                        $categorieslist =  \App\Models\Category::where('slug','=',$selectedValue)->first();
                                                        $subcategorieslist =  \App\Models\Subcategory::where('category_id','=',$categorieslist->id)->get();
                                                        
                                                       @endphp
                                                         <option selected disabled value="">Choose...</option>
                                                       @foreach ($subcategorieslist as $subcate)
                                                       <option  readonly value="{{ $subcate->id }}">{{ $subcate->name }}</option>
                                                       @endforeach
                                                    </select>
                                                
                                                    <div class="invalid-feedback">
                                                        Please select a Sub category.
                                                    </div>
                                                </div>

                                                <br>

                                                <div class="form-group">
                                                    <label>Alphabet Indexing</label>
                                                    <br>
                                                    @foreach ($alpha as $val)
                                                        <div class="form-check form-check-inline" style="margin-right: 10px;">
                                                            <input class="form-check-input" required type="radio" name="alpha_id" value="{{ $val->id }}" style="margin-right: 10px;">

                                                            <label class="btn btn-success waves-effect waves-light" style="margin-right: 10px;">{{ $val->name }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>


                                                
                                                    <div class="mb-3">
                                                        <label class="form-label">Default file input</label>
                                                        <input type="file" name="regulation_doc" class="filestyle" data-buttonname="btn-secondary">
                                                    </div>
            
                                 
                                           
                                               
                                                <!-- end col -->
                                                <div class="col-12">
                                                    <button class="btn btn-primary" type="submit">Submit form</button>
                                                </div>
                                                <!-- end col -->
                                            </form><!-- end form -->
                                        </div><!-- end cardbody -->
                                    </div><!-- end card -->
                                </div>
                                <!-- end col -->
                               
                                <!-- end col -->
                            </div>
                            @endif


                            @if($selectedValue  == 'guidelines')
                            <div class="row">
                                <center><h3>Guidelines</h3></center>
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            
                                                <form class="row g-3 needs-validation" novalidate method="POST" action="{{ route('regulations.store')}}" enctype="multipart/form-data">
                                                    @csrf
                                            
                                                <div class="col-md-12">
                                                    <label for="validationCustom01" class="form-label">Title</label>
                                                    <input type="text" name="title" class="form-control" id="validationCustom01" placeholder="Investment" required>
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="validationCustom02" class="form-label">Price</label>
                                                    <input  type="number" name="price"  class="form-control" id="validationCustom02 year" placeholder="2000" required>
                                                    <div class="valid-feedback">
                                                        Looks good!
                                                    </div>
                                                </div>

                                                <!-- end col -->
                                                <div class="col-md-4">
                                                    <label for="validationCustom04" class="form-label">Month</label>
                                                    <select class="form-select" name="month_id"  required>
                                                        <option selected disabled value="">Choose...</option>
                                                        @foreach ($months as $month)
                                                        <option value="{{ $month->id }}">{{ $month->name }}</option>
                                                        @endforeach
                                                      
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Please select Month.
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="validationCustom04" class="form-label">Year</label>
                                                    <select class="form-select" name="year_id"  required>
                                                        <option selected disabled value="">Choose...</option>
                                                        @foreach ($years as $year)
                                                        <option value="{{ $year->id }}">{{ $year->name }}</option>
                                                        @endforeach
                                                      
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Please select Year.
                                                    </div>
                                                </div>
                                               
                                               
                                               
                                                <div class="col-md-4">
                                                    <label for="validationCustom04" class="form-label">Entity</label>
                                                    <select class="form-select" name="entity_id"  required>
                                                        <option selected disabled value="">Choose...</option>
                                                        @foreach ($entities as $entity)
                                                        <option value="{{ $entity->id }}">{{ $entity->name }}</option>
                                                        @endforeach
                                                      
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Please select an entity.
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="validationCustom04" class="form-label">Category</label>
                                                   
                                                        <select class="form-select" name="category_id" required >
                                                           
                                                    @php
                                                     $categorieslist =  \App\Models\Category::where('slug','=',$selectedValue)->get();
                                                     
                                                    @endphp
                                                   
                                                    @foreach ($categorieslist as $category)
                                                    <option selected readonly value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                                
                                                    <div class="invalid-feedback">
                                                        Please select a category.
                                                    </div>
                                                </div>


                                                <div class="col-md-4">
                                                    <label for="validationCustom04" class="form-label">Sub Category</label>
                                                    <select name="subcategory_id" class="form-select" required>
                                                        @php
                                                        $categorieslist =  \App\Models\Category::where('slug','=',$selectedValue)->first();
                                                        $subcategorieslist =  \App\Models\Subcategory::where('category_id','=',$categorieslist->id)->get();
                                                        
                                                       @endphp
                                                         <option selected disabled value="">Choose...</option>
                                                       @foreach ($subcategorieslist as $subcate)
                                                       <option  readonly value="{{ $subcate->id }}">{{ $subcate->name }}</option>
                                                       @endforeach
                                                    </select>
                                                
                                                    <div class="invalid-feedback">
                                                        Please select a Sub category.
                                                    </div>
                                                </div>
                                                <br>

                                                <div class="form-group">
                                                    <label>Alphabet Indexing</label>
                                                    <br>
                                                    @foreach ($alpha as $val)
                                                        <div class="form-check form-check-inline" style="margin-right: 10px;">
                                                            <input class="form-check-input" required type="radio" name="alpha_id" value="{{ $val->id }}" style="margin-right: 10px;">

                                                            <label class="btn btn-success waves-effect waves-light" style="margin-right: 10px;">{{ $val->name }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>


                                                
                                                    <div class="mb-3">
                                                        <label class="form-label">Default file input</label>
                                                        <input type="file" name="regulation_doc" class="filestyle" data-buttonname="btn-secondary">
                                                    </div>
            
                                                    

                                           
                                               
                                                <!-- end col -->
                                                <div class="col-12">
                                                    <button class="btn btn-primary" type="submit">Submit form</button>
                                                </div>
                                                <!-- end col -->
                                            </form><!-- end form -->
                                        </div><!-- end cardbody -->
                                    </div><!-- end card -->
                                </div>
                                <!-- end col -->
                               
                                <!-- end col -->
                            </div>
                            @endif

                              {{-- <div id="rules-regulations" style="display:none">
                            
                               
                            </div> --}}
                             


                            
                                @if($selectedValue  == 'market-notices')
                                <div class="row">
                                    <center><h3>Market Notices</h3></center>
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                
                                                    <form class="row g-3 needs-validation" novalidate method="POST" action="{{ route('regulations.store')}}" enctype="multipart/form-data">
                                                        @csrf
                                                
                                                    <div class="col-md-12">
                                                        <label for="validationCustom01" class="form-label">Title</label>
                                                        <input type="text" name="title" class="form-control" id="validationCustom01" placeholder="Mark" required>
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                    <!-- end col -->
                                                    <div class="col-md-4">
                                                        <label for="validationCustom02" class="form-label">Price</label>
                                                        <input  type="number" name="price"  class="form-control" id="validationCustom02 year" placeholder="2000" required>
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
    
                                                    <!-- end col -->
                                                    <div class="col-md-4">
                                                        <label for="validationCustom04" class="form-label">Month</label>
                                                        <select class="form-select" name="month_id"  required>
                                                            <option selected disabled value="">Choose...</option>
                                                            @foreach ($months as $month)
                                                            <option value="{{ $month->id }}">{{ $month->name }}</option>
                                                            @endforeach
                                                          
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Please select Month.
                                                        </div>
                                                    </div>
    
                                                    <div class="col-md-4">
                                                        <label for="validationCustom04" class="form-label">Year</label>
                                                        <select class="form-select" name="year_id"  required>
                                                            <option selected disabled value="">Choose...</option>
                                                            @foreach ($years as $year)
                                                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                                                            @endforeach
                                                          
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Please select Year.
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="validationCustom04" class="form-label">Category</label>
                                                       
                                                            <select class="form-select" name="category_id" required >
                                                               
                                                        @php
                                                         $categorieslist =  \App\Models\Category::where('slug','=',$selectedValue)->get();
                                                         
                                                        @endphp
                                                       
                                                        @foreach ($categorieslist as $category)
                                                        <option selected readonly value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    
                                                        <div class="invalid-feedback">
                                                            Please select a category.
                                                        </div>
                                                    </div>
                                                   
                                                   
                                                   <br>
    
                                                    <div class="form-group">
                                                        <label>Alphabet Indexing</label>
                                                        <br>
                                                        @foreach ($alpha as $val)
                                                            <div class="form-check form-check-inline" style="margin-right: 10px;">
                                                                <input class="form-check-input" required type="radio" name="alpha_id" value="{{ $val->id }}" style="margin-right: 10px;">
    
                                                                <label class="btn btn-success waves-effect waves-light" style="margin-right: 10px;">{{ $val->name }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
    
    
                                                    
                                                        <div class="mb-3">
                                                            <label class="form-label">Default file input</label>
                                                            <input type="file" name="regulation_doc" class="filestyle" data-buttonname="btn-secondary">
                                                        </div>
                
                                                     
                                                   
                                                    <!-- end col -->
                                                    <div class="col-12">
                                                        <button class="btn btn-primary" type="submit">Submit form</button>
                                                    </div>
                                                    <!-- end col -->
                                                </form><!-- end form -->
                                            </div><!-- end cardbody -->
                                        </div><!-- end card -->
                                    </div>
                                    <!-- end col -->
                                   
                                    <!-- end col -->
                                </div>
                                @endif




                                @if($selectedValue  == 'market-bulletins')
                                <div class="row">
                                    <center><h3>Market Bulletins</h3></center>
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                
                                                    <form class="row g-3 needs-validation" novalidate method="POST" action="{{ route('regulations.store')}}" enctype="multipart/form-data">
                                                        @csrf
                                                
                                                    <div class="col-md-12">
                                                        <label for="validationCustom01" class="form-label">Title</label>
                                                        <input type="text" name="title" class="form-control" id="validationCustom01" placeholder="Mark" required>
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                    <!-- end col -->
                                                    <div class="col-md-4">
                                                        <label for="validationCustom02" class="form-label">Price</label>
                                                        <input  type="number" name="price"  class="form-control" id="validationCustom02 year" placeholder="2000" required>
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
    
                                                    <!-- end col -->
                                                    <div class="col-md-4">
                                                        <label for="validationCustom04" class="form-label">Month</label>
                                                        <select class="form-select" name="month_id"  required>
                                                            <option selected disabled value="">Choose...</option>
                                                            @foreach ($months as $month)
                                                            <option value="{{ $month->id }}">{{ $month->name }}</option>
                                                            @endforeach
                                                          
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Please select Month.
                                                        </div>
                                                    </div>
    
                                                    <div class="col-md-4">
                                                        <label for="validationCustom04" class="form-label">Year</label>
                                                        <select class="form-select" name="year_id"  required>
                                                            <option selected disabled value="">Choose...</option>
                                                            @foreach ($years as $year)
                                                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                                                            @endforeach
                                                          
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Please select Year.
                                                        </div>
                                                    </div>


                                                    <div class="col-md-4">
                                                        <label for="validationCustom04" class="form-label">Category</label>
                                                       
                                                            <select class="form-select" name="category_id" required >
                                                               
                                                        @php
                                                         $categorieslist =  \App\Models\Category::where('slug','=',$selectedValue)->get();
                                                         
                                                        @endphp
                                                       
                                                        @foreach ($categorieslist as $category)
                                                        <option selected readonly value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    
                                                        <div class="invalid-feedback">
                                                            Please select a category.
                                                        </div>
                                                    </div>
                                                   
                                                   
                                                   
                                                   
    
                                                    <br>
    
                                                    <div class="form-group">
                                                        <label>Alphabet Indexing</label>
                                                        <br>
                                                        @foreach ($alpha as $val)
                                                            <div class="form-check form-check-inline" style="margin-right: 10px;">
                                                                <input class="form-check-input" required type="radio" name="alpha_id" value="{{ $val->id }}" style="margin-right: 10px;">
    
                                                                <label class="btn btn-success waves-effect waves-light" style="margin-right: 10px;">{{ $val->name }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
    
    
                                                    
                                                        <div class="mb-3">
                                                            <label class="form-label">Default file input</label>
                                                            <input type="file" name="regulation_doc" class="filestyle" data-buttonname="btn-secondary">
                                                        </div>
                
                                                        
    
    
                                                    
    
                                                    
                                                          
                                                        
                                                          
                                               
    
    
    
                                               
                                                   
                                                    <!-- end col -->
                                                    <div class="col-12">
                                                        <button class="btn btn-primary" type="submit">Submit form</button>
                                                    </div>
                                                    <!-- end col -->
                                                </form><!-- end form -->
                                            </div><!-- end cardbody -->
                                        </div><!-- end card -->
                                    </div>
                                    <!-- end col -->
                                   
                                    <!-- end col -->
                                </div>
                                @endif





                            
                            



                                @if($selectedValue  == 'market-circulars')
                                <div class="row">
                                    <center><h3>Market Circulars</h3></center>
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                
                                                    <form class="row g-3 needs-validation" novalidate method="POST" action="{{ route('regulations.store')}}" enctype="multipart/form-data">
                                                        @csrf
                                                
                                                    <div class="col-md-12">
                                                        <label for="validationCustom01" class="form-label">Title</label>
                                                        <input type="text" name="title" class="form-control" id="validationCustom01" placeholder="Mark" required>
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                    <!-- end col -->
                                                    <div class="col-md-4">
                                                        <label for="validationCustom02" class="form-label">Price</label>
                                                        <input  type="number" name="price"  class="form-control" id="validationCustom02 year" placeholder="2000" required>
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
    
                                                    <!-- end col -->
                                                    <div class="col-md-4">
                                                        <label for="validationCustom04" class="form-label">Month</label>
                                                        <select class="form-select" name="month_id"  required>
                                                            <option selected disabled value="">Choose...</option>
                                                            @foreach ($months as $month)
                                                            <option value="{{ $month->id }}">{{ $month->name }}</option>
                                                            @endforeach
                                                          
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Please select Month.
                                                        </div>
                                                    </div>
    
                                                    <div class="col-md-4">
                                                        <label for="validationCustom04" class="form-label">Year</label>
                                                        <select class="form-select" name="year_id"  required>
                                                            <option selected disabled value="">Choose...</option>
                                                            @foreach ($years as $year)
                                                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                                                            @endforeach
                                                          
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Please select Year.
                                                        </div>
                                                    </div>
                                                   

                                                   
                                                    <div class="col-md-6">
                                                        <label for="validationCustom04" class="form-label">Entity</label>
                                                        <select class="form-select" name="entity_id"  required>
                                                            <option selected disabled value="">Choose...</option>
                                                            @foreach ($entities as $entity)
                                                            <option value="{{ $entity->id }}">{{ $entity->name }}</option>
                                                            @endforeach
                                                          
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Please select an entity.
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="validationCustom04" class="form-label">Category</label>
                                                       
                                                            <select class="form-select" name="category_id" required >
                                                               
                                                        @php
                                                         $categorieslist =  \App\Models\Category::where('slug','=',$selectedValue)->get();
                                                         
                                                        @endphp
                                                       
                                                        @foreach ($categorieslist as $category)
                                                        <option selected readonly value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    
                                                        <div class="invalid-feedback">
                                                            Please select a category.
                                                        </div>
                                                    </div>
    
                                                   
    
    
    
                                                    <br>
    
                                                    <div class="form-group">
                                                        <label>Alphabet Indexing</label>
                                                        <br>
                                                        @foreach ($alpha as $val)
                                                            <div class="form-check form-check-inline" style="margin-right: 10px;">
                                                                <input class="form-check-input" required type="radio" name="alpha_id" value="{{ $val->id }}" style="margin-right: 10px;">
    
                                                                <label class="btn btn-success waves-effect waves-light" style="margin-right: 10px;">{{ $val->name }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
    
    
                                                    
                                                        <div class="mb-3">
                                                            <label class="form-label">Default file input</label>
                                                            <input type="file" name="regulation_doc" class="filestyle" data-buttonname="btn-secondary">
                                                        </div>
                
                                                        
    
    
                                                    
    
                                                   
                                                    <!-- end col -->
                                                    <div class="col-12">
                                                        <button class="btn btn-primary" type="submit">Submit form</button>
                                                    </div>
                                                    <!-- end col -->
                                                </form><!-- end form -->
                                            </div><!-- end cardbody -->
                                        </div><!-- end card -->
                                    </div>
                                    <!-- end col -->
                                   
                                    <!-- end col -->
                                </div>
                                @endif
                          
                           
                             
                                
                                
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