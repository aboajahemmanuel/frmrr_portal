@extends('layouts.master')

@section('content')




    <!-- main header @e -->
    <!-- content @s
                                -->
        <div class="nk-content ">
            <div class="container-fluid">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-block-head-sm">
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title page-title">External Users</h3>
                                    <div class="nk-block-des text-soft">

                                    </div>
                                </div><!-- .nk-block-head-content -->
                                <div class="nk-block-head-content">
                                    <div class="toggle-wrap nk-block-tools-toggle">
                                        <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                            data-target="more-options"><em class="icon ni ni-more-v"></em></a>
                                        <div class="toggle-expand-content" data-content="more-options">
                                            <ul class="nk-block-tools g-3">



                                                {{-- <li><a href="#" data-toggle="modal" data-target="#addLead"><span>Add Orgranigation</span></a></li>
                                                        <li><a href="#"><span>Import Lead</span></a></li> --}}
                                            </ul>
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
                                    <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                        <thead>
                                            <tr class="nk-tb-item nk-tb-head">
                                                <th>#</th>
                                                <th class="nk-tb-col"><span class="sub-text">User</span></th>
                                                <th class="nk-tb-col tb-col-lg"><span class="sub-text">Company</span></th>
                                                <th class="nk-tb-col tb-col-mb"><span class="sub-text">Email</span></th>
                                                <th class="nk-tb-col tb-col-md"><span class="sub-text">Phone</span></th>

                                                <th class="nk-tb-col tb-col-lg"><span class="sub-text">Created At</span></th>
                                                <th class="nk-tb-col tb-col-lg"><span class="sub-text">Verified</span></th>
                                                <th class="nk-tb-col tb-col-lg">Subscription Status</th>
                                                <th class="nk-tb-col tb-col-lg">Subscription End Date</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $key => $user)
                                                <tr class="nk-tb-item">
                                                    <td class="nk-tb-col nk-tb-col-check">
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td class="nk-tb-col">
                                                        <div class="user-card">

                                                            <div class="user-info">
                                                                <span class="tb-lead">{{ $user->name }} <span
                                                                        class="dot dot-success d-md-none ml-1"></span></span>

                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="nk-tb-col tb-col-lg"
                                                        data-order="Email Verified - Kyc Unverified">
                                                        {{ $user->company_name }}

                                                    </td>
                                                    <td class="nk-tb-col tb-col-mb" data-order="35040.34">
                                                        <span class="tb-amount">{{ $user->email }}<span
                                                                class="currency"></span></span>
                                                    </td>
                                                    <td class="nk-tb-col tb-col-md">
                                                        {{ $user->phone }}

                                                    </td>

                                                    <td class="nk-tb-col tb-col-lg">
                                                        <span>
                                                            @php
                                                                $postdate = date_format($user->created_at, 'F d,Y');

                                                            @endphp

                                                            <?php
                                                            
                                                            $timestamp = strtotime($postdate);
                                                            $newDateFormat = date('M. d, Y', $timestamp);
                                                            echo $newDateFormat;
                                                            
                                                            ?></span>
                                                    </td>

                                                    <td class="nk-tb-col tb-col-lg">
                                                        @if ($user->email_verified_at == null)
                                                            <span class="badge fmdq_Blue">Pending<span>
                                                                @else
                                                                    <span class="badge badge-primary">Active</span>
                                                        @endif


                                                    </td>

                                                    <td class="nk-tb-col tb-col-lg">
                                                        @php
                                                            $activeSubscription = $user->subscriptions
                                                                ->filter(function ($subscription) {
                                                                    return $subscription->status == 1 &&
                                                                        $subscription->end_date >= now();
                                                                })
                                                                ->first();
                                                        @endphp


                                                        @if ($activeSubscription)
                                                            <span class="badge badge-success">Active</span>
                                                        @else
                                                            <span class="badge badge-danger">Inactive<span>
                                                        @endif
                                                    </td>
                                                    <td class="nk-tb-col tb-col-lg">
                                                        @if ($activeSubscription)
                                                            @php
                                                                $postdate = date_format(
                                                                    $activeSubscription->end_date,
                                                                    'F d,Y',
                                                                );

                                                            @endphp

                                                            <?php
                                                            
                                                            $timestamp = strtotime($postdate);
                                                            $newDateFormat = date('M. d, Y', $timestamp);
                                                            echo $newDateFormat;
                                                            
                                                            ?>
                                                            {{-- @if ($activeSubscription)
                                                            @php
                                                                $DateCreated = \Carbon\Carbon::parse(
                                                                    $activeSubscription->end_date,
                                                                )->format('Y-m-d');
                                                            @endphp
                                                            {{ $DateCreated }} --}}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>


                                                </tr><!-- .nk-tb-item  -->
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!-- .nk-block -->
                    </div>
                </div>
            </div>
        </div>
        <!-- content @e -->
        <!-- @@ Lead Add Modal @e -->
    @endsection
