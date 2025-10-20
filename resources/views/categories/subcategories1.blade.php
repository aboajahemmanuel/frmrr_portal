@extends('layouts.admin')

@section('content')


<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h6 class="page-title">Sub Categories</h6>
                        {{-- <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="#">Veltrix</a></li>
                            <li class="breadcrumb-item"><a href="#">Tables</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data tables</li>
                        </ol> --}}
                    </div>
                    <div class="col-md-4">
                        <div class="float-end d-none d-md-block">
                            <div class="dropdown">
                                <button class="btn btn-primary  dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop" aria-expanded="true">
                                    <i class="fas fa-plus-circle"></i> Add New
                                </button>
                               
                            </div>
                        </div>
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

                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>S/N</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th width="280px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $sub_category)
                                        <tr>
                                            <td> {{ $loop->iteration }}</td>
                                            <td>{{ $sub_category->name }}</td>
                                            <td>{{ $sub_category->category->name }}</td>
                                            <td>
                                               
                                                @can('role-edit')
                                                    <a class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#editcategory-{{$sub_category->id}}">Edit</a>
                                                @endcan
                                                
                                                @can('role-delete')
                                                <a class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deletecategory-{{$sub_category->id}}">Delete</a>
                                                @endcan
                                            </td>
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

<!-- ADD MODAL -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static"
data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Add Sub Category
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {!! Form::open(array('route' => 'subcategories.store','method'=>'POST')) !!}
            <div class="form-group">
                <strong>Name:</strong>
                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
            </div>

            {{-- <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Select</label>
                <div class="col-sm-10">
                    <select class="form-select" aria-label="Default select example">
                        <option selected>Open this select menu</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                        </select>
                </div>
            </div> --}}

            <br>

            <div class="form-control-wrap">
                <label>Category</label>
                <select required name="category_id" class="form-select form-control" data-placeholder="Select one">
                    <option value="">---</option>
                    @foreach($categories as $cate)
                    <option value="{{$cate->id}}">
                        {{$cate->name}}</option>
                        @endforeach

                    
                </select>
            </div>
            <br>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
                data-bs-dismiss="modal">Close</button>
            <button type="submit"
                class="btn btn-primary">Submit</button>
                {!! Form::close() !!}
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>





<!-- EDIT MODAL -->
@foreach ($data as $key => $sub_category)
<div class="modal fade" id="editcategory-{{ $sub_category->id}}"
data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">{{ $sub_category->name}}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {!! Form::model($sub_category, ['route' => ['subcategories.update', $sub_category->id],'method' => 'PATCH']) !!}
            <div class="form-group">
                <strong>Name:</strong>
                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
            </div>
           
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
                data-bs-dismiss="modal">Close</button>
            <button type="submit"
                class="btn btn-primary">Submit</button>
                {!! Form::close() !!}
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
@endforeach







<!-- DELETE MODAL -->
@foreach ($data as $key => $sub_category)
<div class="modal fade" id="deletecategory-{{ $sub_category->id}}"
data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">{{ $sub_category->name}}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body modal-body-sm text-center">
            <div class="nk-modal py-4">
                <em class="nk-modal-icon icon icon-circle icon-circle-xxl ni ni-cross bg-danger"></em>
                <h4 class="nk-modal-title">Are You Sure ?</h4>
                <div class="nk-modal-text mt-n2">
                    <p class="text-soft">This Role will be delete permanently.</p>
                </div>
                
            </div>
        </div>
        <div class="modal-footer">
            <form  method="POST" action="{{ route('deletesubcategory', $sub_category->id)}}" >
                @csrf
                <button type="submit" id="deleteOrg" class="btn btn-success">Yes, Delete it</button>
            </form>
            <button data-dismiss="modal" data-toggle="modal" data-target="#editEventPopup" class="btn btn-danger btn-dim">Cancel</button>
           
                
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
@endforeach



@endsection