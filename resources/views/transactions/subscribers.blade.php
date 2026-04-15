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
                                    <h3 class="nk-block-title page-title">Subscribers</h3>
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
                                    <div class="card-title-group">
                                        <div class="card-title">
                                            <h5 class="title">All Subscribers</h5>
                                        </div>
                                    </div>
                                    <form action="{{ route('subscribers') }}" method="GET" class="mb-4">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">Search by Customer Name</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" name="name" class="form-control"
                                                            placeholder="Customer Name" value="{{ request('name') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">&nbsp;</label>
                                                    <div class="form-control-wrap">
                                                        <button type="submit" class="btn btn-primary">Filter</button>
                                                        <a href="{{ route('subscribers') }}"
                                                            class="btn btn-outline-light">Reset</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="nk-tb-list nk-tb-ulist table-striped"
                                        data-auto-responsive="false">
                                        <thead>
                                            <tr class="nk-tb-item nk-tb-head">

                                                <th class="nk-tb-col">S/N</th>
                                                <th class="nk-tb-col">Customer Name</th>
                                                <th class="nk-tb-col">Plan</th>
                                                <th class="nk-tb-col">Start Date</th>
                                                <th class="nk-tb-col">End Date</th>

                                                <th class="nk-tb-col">Status</th>
                                                <th class="nk-tb-col">Date Created</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $transaction)
                                                <tr class="nk-tb-item">



                                                    <td class="nk-tb-col"> {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                                                    <td class="nk-tb-col"> {{ optional($transaction->user)->name }}</td>
                                                    <td class="nk-tb-col">{{ optional($transaction->subscriptionPlan)->name }}
                                                    </td>
                                                    <td class="nk-tb-col">


                                                    {{ \Carbon\Carbon::parse($transaction->start_date)->format('M. d, Y') }} {{ \Carbon\Carbon::parse($transaction->created_at)->format('h:i A') }}
                                                    </td>
                                                    <td class="nk-tb-col">
                                                        {{ \Carbon\Carbon::parse($transaction->end_date)->format('M. d, Y') }} {{ \Carbon\Carbon::parse($transaction->created_at)->format('h:i A') }}
                                                    </td>

                                                    <td class="nk-tb-col">
                                                        @if (\Carbon\Carbon::parse($transaction->end_date)->lt(\Carbon\Carbon::now()))
                                                            <span class="badge badge-primary">expired</span>
                                                        @else
                                                            <span class="badge badge-success">active</span>
                                                        @endif




                                                    </td>

                                                    <td class="nk-tb-col">
                                                        {{ \Carbon\Carbon::parse($transaction->created_at)->format('M. d, Y h:i A') }}


                                                    </td>


                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-inner">
                                    <div class="nk-block-between-md g-3">
                                        <div class="g">
                                            {{ $data->appends(request()->input())->links('vendor.pagination.bootstrap-4') }}
                                        </div>
                                    </div>
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
