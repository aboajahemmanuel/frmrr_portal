@extends('layouts.master')

@section('content')




    <!-- main header @e -->
    <!-- content @s -->
        <div class="nk-content ">
            <div class="container-fluid">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-block-head-sm">
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title page-title">Subscriptions</h3>
                                    <div class="nk-block-des text-soft">

                                    </div>
                                </div><!-- .nk-block-head-content -->
                                <div class="nk-block-head-content">
                                    <div class="toggle-wrap nk-block-tools-toggle">
                                        <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                            data-target="more-options"><em class="icon ni ni-more-v"></em></a>
                                        <div class="toggle-expand-content" data-content="more-options">

                                        </div>
                                    </div>
                                </div><!-- .nk-block-head-content -->
                            </div><!-- .nk-block-between -->
                        </div><!-- .nk-block-head -->
                        <div class="nk-block nk-block-lg">

                            <div class="example-alert">
                                @if (\Session::has('success'))
                                    <div class="alert alert-success alert-icon alert-dismissible">
                                        <em class="icon ni ni-check-circle"></em> <strong> {{ \Session::get('success') }}<button
                                                class="close" data-dismiss="alert"></button>
                                    </div>
                                @endif


                                @if (count($errors) > 0)
                                    <div>
                                        <div class="alert alert-danger alert-icon alert-dismissible">
                                            <strong>Opps!</strong> Something went wrong, please check below errors.<br><br>
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                            <button class="close" data-dismiss="alert"></button>
                                        </div>
                                @endif



                            </div>
                            <div class="card card-preview">

                                <div class="card-inner">

                                    <table class="datatable-init nk-tb-list nk-tb-ulist table-striped"
                                        data-auto-responsive="false">
                                        <thead>
                                            <tr class="nk-tb-item nk-tb-head">

                                                <th class="nk-tb-col">S/N</th>
                                                <th class="nk-tb-col">Customer Name</th>
                                                <th class="nk-tb-col">Document</th>
                                                <th class="nk-tb-col">Amount</th>
                                                <th class="nk-tb-col">Transaction Ref</th>
                                                <th class="nk-tb-col">Transaction Date</th>
                                                <th class="nk-tb-col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $transaction)
                                                <tr class="nk-tb-item">



                                                    <td class="nk-tb-col"> {{ $loop->iteration }}</td>
                                                    <td class="nk-tb-col"> {{ optional($transaction->user)->name }}</td>
                                                    <td class="nk-tb-col">{{ optional($transaction->regulation)->title }}</td>
                                                    <td class="nk-tb-col">{{ $transaction->amount }}</td>
                                                    <td class="nk-tb-col">{{ $transaction->reference }}</td>
                                                    <td class="nk-tb-col">
                                                        @php
                                                            $DateCreated = \Carbon\Carbon::parse(
                                                                $transaction->created_at,
                                                            )->format('Y-m-d');
                                                        @endphp
                                                        {{ $DateCreated }}

                                                    </td>
                                                    <td class="nk-tb-col">
                                                        @if ($transaction->status == 'success')
                                                            <span class="badge badge-primary">Success</span>
                                                        @endif
                                                        @if ($transaction->status == 'successful')
                                                            <span class="badge badge-primary">Success</span>
                                                        @endif
                                                        @if ($transaction->status == '')
                                                            <span class="badge badge-danger">Pending</span>
                                                        @endif

                                                    </td>




                                                    {{-- <td class="nk-tb-col">
                                                        <ul class="nk-tb-actions gx-1">

                                                            <li>
                                                                <div class="drodown">
                                                                    <a href="#"
                                                                        class="dropdown-toggle btn btn-icon btn-trigger"
                                                                        data-toggle="dropdown"><em
                                                                            class="icon ni ni-more-h"></em></a>
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        <ul class="link-list-opt no-bdr">
                                                                            @can('regulation-edit')
                                                                                <li><a
                                                                                        href="{{ route('edit_doc', $regulation->id) }}"><em
                                                                                            class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                                </li>
                                                                            @endcan

                                                                            @can('regulation-delete')
                                                                                <li>


                                                                                    <a href="#" data-toggle="modal"
                                                                                        data-target="#deleteReg-{{ $regulation->id }}"><em
                                                                                            class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                                </li>
                                                                            @endcan

                                                                            @can('document-ceased')
                                                                                <li>


                                                                                    <a href="#" data-toggle="modal"
                                                                                        data-target="#ceased-{{ $regulation->id }}"><em
                                                                                            class="icon ni ni-trash"></em><span>Ceased</span></a>
                                                                                </li>
                                                                            @endcan


                                                                            @if ($regulation->status == 0)
                                                                                @can('regulation-approve')
                                                                                    <li><a href="#" id="submit"
                                                                                            onclick="document.getElementById('test1-{{ $regulation->id }}').submit();"><em
                                                                                                class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                                    </li>
                                                                                @endcan


                                                                                @can('regulation-reject')
                                                                                    <li>


                                                                                        <a href="#" data-toggle="modal"
                                                                                            data-target="#rejectdocument-{{ $regulation->id }}"><em
                                                                                                class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                                    </li>
                                                                                @endcan
                                                                            @endif


                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>

                                                        <form id="test1-{{ $regulation->id }}"
                                                            action="{{ route('RegStatus', $regulation->id) }}" method="POST"
                                                            class="d-none">
                                                            @csrf
                                                            <input name="status" value="1">
                                                        </form>





                                                    </td> --}}
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- .card-preview -->
                        </div><!-- .nk-block -->
                    </div>
                </div>
            </div>
        </div>
        <!-- content @e -->
        <!-- @@ Group Add Modal @e -->











    @endsection
