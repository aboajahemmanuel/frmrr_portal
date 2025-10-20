@extends('layouts.admin')

@section('content')


<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h6 class="page-title">Entities</h6>
                        {{-- <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="#">Veltrix</a></li>
                            <li class="breadcrumb-item"><a href="#">Tables</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data tables</li>
                        </ol> --}}
                    </div>
                    <div class="col-md-4">
                        <div class="float-end d-none d-md-block">
                            <div class="dropdown">
                                <button class="btn fmdq_Blue  dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="modal"
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
                                        <th width="280px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $entity)
                                        <tr>
                                            <td> {{ $loop->iteration }}</td>
                                            <td>{{ $entity->name }}</td>
                                            <td>
                                               
                                                @can('role-edit')
                                                    <a class="btn fmdq_Gold" data-bs-toggle="modal"
                                                    data-bs-target="#editcategory-{{$entity->id}}">Edit</a>
                                                @endcan
                                                
                                                @can('role-delete')
                                                <a class="btn fmdq_Grey" data-bs-toggle="modal"
                                                data-bs-target="#deletecategory-{{$entity->id}}">Delete</a>
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
            <h5 class="modal-title" id="staticBackdropLabel">Add Entities
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {!! Form::open(array('route' => 'entities.store','method'=>'POST')) !!}
            <div class="form-group">
                <strong>Name:</strong>
                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
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
@foreach ($data as $key => $entity)
<div class="modal fade" id="editcategory-{{ $entity->id}}"
data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">{{ $entity->name}}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {!! Form::model($entity, ['route' => ['entities.update', $entity->id],'method' => 'PATCH']) !!}
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
@foreach ($data as $key => $entity)
<div class="modal fade" id="deletecategory-{{ $entity->id}}"
data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">{{ $entity->name}}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body modal-body-sm text-center">
            <div class="nk-modal py-4">
                <em class="nk-modal-icon icon icon-circle icon-circle-xxl ni ni-cross bg-danger"></em>
                <h4 class="nk-modal-title">Are You Sure ?</h4>
                <div class="nk-modal-text mt-n2">
                    <p class="text-soft">This Entity will be delete permanently.</p>
                </div>
                
            </div>
        </div>
        <div class="modal-footer">
            <form  method="POST" action="{{ route('deleteEntity', $entity->id)}}" >
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