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
                                    <h3 class="nk-block-title page-title">Dashboard</h3>
                                </div><!-- .nk-block-head-content -->
                                <div class="nk-block-head-content">
                                    <div class="toggle-wrap nk-block-tools-toggle">
                                        <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                            data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>

                                    </div>
                                </div><!-- .nk-block-head-content -->
                            </div><!-- .nk-block-between -->
                        </div><!-- .nk-block-head -->
                        <div class="nk-block">
                            @if (Auth::user()->hasRole('MBG_User'))
                                <div class="row g-gs">

                                    <div class="col-md-6">
                                        <div class="card card-bordered card-full">
                                            <div class="card-inner">
                                                <div class="card-title-group align-start mb-0">
                                                    <div class="card-title">
                                                        <h6 class="title">External Users</h6>
                                                    </div>
                                                    <div class="card-tools">
                                                        <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip"
                                                            data-placement="left"></em>
                                                    </div>
                                                </div>
                                                <div class="card-amount">
                                                    <span class="amount"> {{ $count_external_users }} </span>

                                                </div>
                                                <div class="invest-data">
                                                    <div class="invest-data-amount g-2">
                                                        <div class="invest-data-history">
                                                            <div class="title">Verified/Active</div>
                                                            <div class="amount">{{ $count_verified_users }}</div>
                                                        </div>
                                                        <div class="invest-data-history">
                                                            <div class="title">Unverified/Inactive</div>
                                                            <div class="amount">{{ $count_unverified_users }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="invest-data-ck">
                                                        <canvas class="iv-data-chart" id="totalBooking"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- .card -->
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card card-bordered card-full">
                                            <div class="card-inner">
                                                <div class="card-title-group align-start mb-0">
                                                    <div class="card-title">
                                                        <h6 class="title">
                                                            Subscribers
                                                        </h6>
                                                    </div>
                                                    <div class="card-tools">
                                                        <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip"
                                                            data-placement="left"></em>
                                                    </div>
                                                </div>
                                                {{-- 'all_sub_ctaegories', 'active_sub_categories', 'inactive_sub_categories' --}}
                                                <div class="card-amount">
                                                    <span class="amount"> {{ $usersWithSubscriptions }} </span>

                                                </div>
                                                <div class="invest-data">
                                                    <div class="invest-data-amount g-2">
                                                        <div class="invest-data-history">
                                                            <div class="title">Active</div>
                                                            <div class="amount">{{ $activeSubscribers }}</div>
                                                        </div>
                                                        <div class="invest-data-history">
                                                            <div class="title">Inactive</div>
                                                            <div class="amount">{{ $inactiveSubscribers }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="invest-data-ck">
                                                        <canvas class="iv-data-chart" id="totalBooking"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- .card -->
                                    </div>




                                </div><!-- .row -->
                                <br>
                                <br>



                                <div class="card card-preview">

                                    <div class="card card-preview">
                                        <div class="card-inner">
                                            <div class="card-title">
                                                <h6 class="title text-center">External Users</h6>
                                                <p class="text-center"></p>
                                            </div>
                                            <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                                <thead>
                                                    <tr class="nk-tb-item nk-tb-head">
                                                        <th>#</th>
                                                        <th class="nk-tb-col"><span class="sub-text">User</span></th>
                                                        <th class="nk-tb-col tb-col-lg"><span class="sub-text">Company</span>
                                                        </th>
                                                        <th class="nk-tb-col tb-col-mb"><span class="sub-text">Email</span></th>
                                                        <th class="nk-tb-col tb-col-md"><span class="sub-text">Phone</span></th>

                                                        <th class="nk-tb-col tb-col-lg"><span class="sub-text">Created At</span>
                                                        </th>
                                                        <th class="nk-tb-col tb-col-lg"><span class="sub-text">Verified</span>
                                                        </th>
                                                        <th class="nk-tb-col tb-col-lg">Subscription Status</th>
                                                        <th class="nk-tb-col tb-col-lg">Subscription End Date</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($external_users as $external)
                                                        <tr class="nk-tb-item">
                                                            <td class="nk-tb-col nk-tb-col-check">
                                                                {{ $loop->iteration }}
                                                            </td>
                                                            <td class="nk-tb-col">
                                                                <div class="user-card">

                                                                    <div class="user-info">
                                                                        <span class="tb-lead">{{ $external->name }} <span
                                                                                class="dot dot-success d-md-none ml-1"></span></span>

                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="nk-tb-col tb-col-lg"
                                                                data-order="Email Verified - Kyc Unverified">
                                                                {{ $external->company_name }}

                                                            </td>
                                                            <td class="nk-tb-col tb-col-mb" data-order="35040.34">
                                                                <span class="tb-amount">{{ $external->email }}<span
                                                                        class="currency"></span></span>
                                                            </td>
                                                            <td class="nk-tb-col tb-col-md">
                                                                {{ $external->phone }}

                                                            </td>

                                                            <td class="nk-tb-col tb-col-lg">
                                                                <span>
                                                                    @php
                                                                        $postdate = date_format(
                                                                            $external->created_at,
                                                                            'F d,Y',
                                                                        );

                                                                    @endphp

                                                                    <?php
                                                                    
                                                                    $timestamp = strtotime($postdate);
                                                                    $newDateFormat = date('M. d, Y', $timestamp);
                                                                    echo $newDateFormat;
                                                                    
                                                                    ?></span>
                                                            </td>

                                                            <td class="nk-tb-col tb-col-lg">
                                                                @if ($external->email_verified_at == null)
                                                                    <span class="badge fmdq_Blue">Pending<span>
                                                                        @else
                                                                            <span class="badge badge-primary">Active</span>
                                                                @endif


                                                            </td>

                                                            <td class="nk-tb-col tb-col-lg">
                                                                @php
                                                                    $activeSubscription = $external->subscriptions
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
                                </div>
                            @endif


                            @if (!Auth::user()->hasRole('MBG_User'))
                                <div class="row g-gs">

                                    <div class="col-md-4">
                                        <div class="card card-bordered card-full">
                                            <div class="card-inner">
                                                <div class="card-title-group align-start mb-0">
                                                    <div class="card-title">
                                                        <h6 class="title">Category Count</h6>
                                                    </div>
                                                    <div class="card-tools">
                                                        <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip"
                                                            data-placement="left"></em>
                                                    </div>
                                                </div>
                                                <div class="card-amount">
                                                    <span class="amount"> {{ $all_categories }} </span>

                                                </div>
                                                <div class="invest-data">
                                                    <div class="invest-data-amount g-2">
                                                        <div class="invest-data-history">
                                                            <div class="title">Active</div>
                                                            <div class="amount">{{ $active_categories }}</div>
                                                        </div>
                                                        <div class="invest-data-history">
                                                            <div class="title">Inactive</div>
                                                            <div class="amount">{{ $inactive_categories }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="invest-data-ck">
                                                        <canvas class="iv-data-chart" id="totalBooking"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- .card -->
                                    </div>

                                    <div class="col-md-4">
                                        <div class="card card-bordered card-full">
                                            <div class="card-inner">
                                                <div class="card-title-group align-start mb-0">
                                                    <div class="card-title">
                                                        <h6 class="title">Sub-category
                                                            Count</h6>
                                                    </div>
                                                    <div class="card-tools">
                                                        <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip"
                                                            data-placement="left"></em>
                                                    </div>
                                                </div>
                                                {{-- 'all_sub_ctaegories', 'active_sub_categories', 'inactive_sub_categories' --}}
                                                <div class="card-amount">
                                                    <span class="amount"> {{ $all_sub_categories }} </span>

                                                </div>
                                                <div class="invest-data">
                                                    <div class="invest-data-amount g-2">
                                                        <div class="invest-data-history">
                                                            <div class="title">Active</div>
                                                            <div class="amount">{{ $active_sub_categories }}</div>
                                                        </div>
                                                        <div class="invest-data-history">
                                                            <div class="title">Inactive</div>
                                                            <div class="amount">{{ $inactive_sub_categories }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="invest-data-ck">
                                                        <canvas class="iv-data-chart" id="totalBooking"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- .card -->
                                    </div>



                                    <div class="col-md-4">
                                        <div class="card card-bordered card-full">
                                            <div class="card-inner">
                                                <div class="card-title-group align-start mb-0">
                                                    <div class="card-title">
                                                        <h6 class="title">Documents
                                                            Count</h6>
                                                    </div>
                                                    <div class="card-tools">
                                                        <em class="card-hint icon ni ni-help-fill" data-toggle="tooltip"
                                                            data-placement="left"></em>
                                                    </div>
                                                </div>
                                                <div class="card-amount">
                                                    <span class="amount">{{ $all_documents }}</span>

                                                </div>
                                                <div class="invest-data">
                                                    <div class="invest-data-amount g-2">
                                                        <div class="invest-data-history">
                                                            <div class="title">Active</div>
                                                            <div class="amount">{{ $active_documents }}</div>
                                                        </div>
                                                        <div class="invest-data-history">
                                                            <div class="title">Inactive</div>
                                                            <div class="amount">{{ $inactive_documents }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="invest-data-ck">
                                                        <canvas class="iv-data-chart" id="totalBooking"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- .card -->
                                    </div>







                                </div><!-- .row -->
                                <div class="row g-gs">
                                    <div class="col-md-5">
                                        <div class="card card-preview">
                                            <div class="card-inner">
                                                <div class="card-head text-center">
                                                    <h6 class="title">Categories</h6>
                                                </div>
                                                <canvas id="categoryChart"></canvas>
                                            </div>
                                        </div><!-- .card-preview -->
                                    </div>
                                    <div class="col-md-7">
                                        <div class="col-xxl-4">
                                            <div class="card card-bordered card-full">
                                                <div class="card-inner d-flex flex-column h-100">
                                                    <div class="card-title-group mb-3">
                                                        <div class="card-title">
                                                            <h6 class="title">Top Document Downloaded</h6>
                                                            <p>In last 7 days top selected ride.</p>
                                                        </div>


                                                    </div>
                                                    @foreach ($DownloadStats as $stat)
                                                        <div class="progress-list gy-3">
                                                            <div class="progress-wrap">
                                                                <div class="progress-text">
                                                                    <div class="progress-label"> {{ $stat->title }}
                                                                    </div>
                                                                    <div class="progress-amount"> {{ $stat->total }}</div>
                                                                </div>
                                                                <div class="progress progress-md">
                                                                    <div class="progress-bar"
                                                                        data-progress=" {{ $stat->total }}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    @endforeach

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            @endif
                        </div><!-- .nk-block -->
                    </div>
                    <br>
                    <br>
                    @if ($regulations->isNotEmpty())
                        <div class="card card-preview">

                            <div class="card-inner">

                                <div class="card-title">
                                    <h6 class="title text-center">Recent Document Uploaded</h6>
                                    <p class="text-center"></p>
                                </div>
                                <div class="card card-preview">

                                    <div class="card-inner">

                                        <table class="datatable-init nk-tb-list nk-tb-ulist table-striped"
                                            data-auto-responsive="false">
                                            <thead>
                                                <tr class="nk-tb-item nk-tb-head">
                                                    <th class="nk-tb-col">S/N</th>
                                                    <th class="nk-tb-col">Name</th>

                                                    <th class="nk-tb-col">Category</th>
                                                    <th class="nk-tb-col">Status</th>
                                                    <th class="nk-tb-col">Ceased</th>
                                                    <th class="nk-tb-col">Date Created</th>
                                                    <th class="nk-tb-col"></th>
                                                    <th class="nk-tb-col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($regulations as $regulation)
                                                    <tr class="nk-tb-item">
                                                        <td class="nk-tb-col"> {{ $loop->iteration }}</td>
                                                        <td class="nk-tb-col"> {{ $regulation->title }}</td>

                                                        <td class="nk-tb-col">{{ optional($regulation->category)->name }}</td>
                                                        <td class="nk-tb-col">
                                                            @if ($regulation->status == 0)
                                                                <span class="badge fmdq_Blue">Awaiting Approval<span>
                                                            @endif
                                                            @if ($regulation->status == 1)
                                                                <span class="badge badge-primary">Approved</span>
                                                            @endif
                                                            @if ($regulation->status == 2)
                                                                <span class="badge badge-danger">Rejected</span>
                                                            @endif


                                                            @if ($regulation->status == 3)
                                                                <span class="badge badge-warning">Awaiting approval for
                                                                    delete</span>
                                                            @endif

                                                        </td>


                                                        <td class="nk-tb-col">
                                                            @if ($regulation->ceased == 'Ceased')
                                                                <span class="badge fmdq_Blue">Ceased</span>
                                                            @endif
                                                            @if ($regulation->ceased == 'Repealed')
                                                                <span class="badge badge-warning">Repealed</span>
                                                            @endif


                                                        </td>

                                                        <td class="nk-tb-col">
                                                            @php
                                                                $postdate = date_format(
                                                                    $regulation->created_at,
                                                                    'F d,Y',
                                                                );

                                                            @endphp

                                                            <?php
                                                            
                                                            $timestamp = strtotime($postdate);
                                                            $newDateFormat = date('M. d, Y', $timestamp);
                                                            echo $newDateFormat;
                                                            
                                                            ?>



                                                        </td>

                                                        <td class="nk-tb-col">
                                                            @if ($regulation->status == 2)
                                                                {{ $regulation->note }}
                                                            @endif
                                                        </td>

                                                        <td class="nk-tb-col nk-tb-col-tools">



                                                            <ul class="nk-tb-actions gx-1">

                                                                <li>
                                                                    <div class="drodown">
                                                                        <a href="#"
                                                                            class="dropdown-toggle btn btn-icon btn-trigger"
                                                                            data-toggle="dropdown"><em
                                                                                class="icon ni ni-more-h"></em></a>
                                                                        <div class="dropdown-menu dropdown-menu-right">

                                                                            <ul class="link-list-opt no-bdr">
                                                                                <li><a
                                                                                        href="{{ route('view_doc', $regulation->id) }}"><em
                                                                                            class="icon ni ni-edit"></em><span>View</span></a>
                                                                                </li>
                                                                                @if ($regulation->status != 3)
                                                                                    @if ($regulation->status != 0)
                                                                                        @can('regulation-edit')
                                                                                            <li>
                                                                                                <a
                                                                                                    href="{{ route('edit_doc', $regulation->id) }}">
                                                                                                    <em
                                                                                                        class="icon ni ni-edit"></em><span>Edit</span>
                                                                                                </a>
                                                                                            </li>
                                                                                        @endcan




                                                                                        @can('regulation-delete')
                                                                                            <li><a href="#"
                                                                                                    data-toggle="modal"
                                                                                                    data-target="#deleteReg-{{ $regulation->id }}"><em
                                                                                                        class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                                            </li>
                                                                                        @endcan
                                                                                    @endif
                                                                                @endif



                                                                                @if ($regulation->status == 0)
                                                                                    @can('regulation-approve')
                                                                                        <li><a href="#" id="submit"
                                                                                                onclick="document.getElementById('approve-{{ $regulation->id }}').submit();"><em
                                                                                                    class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                                        </li>
                                                                                    @endcan


                                                                                    @can('regulation-reject')
                                                                                        <li><a href="#" data-toggle="modal"
                                                                                                data-target="#rejectdocument-{{ $regulation->id }}"><em
                                                                                                    class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                                        </li>
                                                                                    @endcan
                                                                                @endif



                                                                                @if ($regulation->status == 3)
                                                                                    @can('regulation-approve')
                                                                                        <li><a href="#" id="submit"
                                                                                                onclick="document.getElementById('approve-{{ $regulation->id }}').submit();"><em
                                                                                                    class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                                        </li>
                                                                                    @endcan

                                                                                    @can('regulation-reject')
                                                                                        <li><a href="#" data-toggle="modal"
                                                                                                data-target="#rejectdocument-{{ $regulation->id }}"><em
                                                                                                    class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                                        </li>
                                                                                    @endcan
                                                                                    <form
                                                                                        id="delete_request-{{ $regulation->id }}"
                                                                                        action="{{ route('RegStatus', $regulation->id) }}"
                                                                                        method="POST" class="d-none"
                                                                                        style="display: none">
                                                                                        @csrf
                                                                                        <input name="status"
                                                                                            value="{{ $regulation->status }}">
                                                                                    </form>
                                                                                @endif


                                                                            </ul>

                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>

                                                            <form id="approve-{{ $regulation->id }}"
                                                                action="{{ route('RegStatus', $regulation->id) }}"
                                                                method="POST" class="d-none">
                                                                @csrf
                                                                <input name="status" value="1">
                                                            </form>





                                                        </td>
                                                    </tr>
                                                    <div class="modal fade" role="dialog"
                                                        id="rejectdocument-{{ $regulation->id }}">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg"
                                                            role="document">
                                                            <div class="modal-content">
                                                                <a href="#" class="close" data-dismiss="modal"><em
                                                                        class="icon ni ni-cross-sm"></em></a>
                                                                <div class="modal-body modal-body-md">
                                                                    <h5 class="title">{{ $regulation->title }}</h5>
                                                                    <form method="POST"
                                                                        action="{{ route('RegStatus', $regulation->id) }}"
                                                                        id="rejectForm-{{ $regulation->id }}">
                                                                        @csrf
                                                                        <div class="tab-content">
                                                                            <div class="tab-pane active" id="infomation">
                                                                                <div class="row gy-4">
                                                                                    <div class="col-md-12">
                                                                                        <div class="form-group">

                                                                                            <label>Rejection Note</label>
                                                                                            <input hidden name="status"
                                                                                                value="2">
                                                                                            <textarea required class="form-control" name="note"></textarea>


                                                                                        </div>
                                                                                    </div>



                                                                                    <div class="col-12">
                                                                                        <ul
                                                                                            class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                                                                            <li>
                                                                                                <button
                                                                                                    class="btn btn-lg btn-primary btn-block"
                                                                                                    id="rejectSubmitBtn-{{ $regulation->id }}"
                                                                                                    type="submit">
                                                                                                    <i class="fas fa-spinner fa-spin"
                                                                                                        style="display:none;"></i>
                                                                                                    <span
                                                                                                        class="btn-text">Submit</span>
                                                                                                </button>


                                                                                                <script>
                                                                                                    function loading(buttonId) {
                                                                                                        $("#" + buttonId + " .fa-spinner").show();
                                                                                                        $("#" + buttonId + " .btn-text").html("Processing...");
                                                                                                    }

                                                                                                    document.addEventListener('DOMContentLoaded', function() {
                                                                                                        document.getElementById('rejectForm-{{ $regulation->id }}').addEventListener('submit', function(
                                                                                                            event) {
                                                                                                            if (this.checkValidity() === false) {
                                                                                                                event.preventDefault();
                                                                                                                event.stopPropagation();
                                                                                                            } else {
                                                                                                                loading('rejectSubmitBtn-{{ $regulation->id }}');
                                                                                                                document.getElementById('rejectSubmitBtn-{{ $regulation->id }}').disabled = true;
                                                                                                            }
                                                                                                            this.classList.add('was-validated');
                                                                                                        }, false);
                                                                                                    });
                                                                                                </script>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div><!-- .tab-pane -->

                                                                        </div><!-- .tab-content -->
                                                                    </form>
                                                                </div><!-- .modal-body -->
                                                            </div><!-- .modal-content -->
                                                        </div><!-- .modal-dialog -->
                                                    </div><!-- .modal -->




                                    </div>
                                    <script>
                                        document.getElementById('deleteSubmitBtn-{{ $regulation->id }}').addEventListener('click', function() {
                                            loading('deleteSubmitBtn-{{ $regulation->id }}');
                                            setTimeout(() => {
                                                document.getElementById('deleteSubmitBtn-{{ $regulation->id }}').disabled = true;
                                            }, 50);
                                        });
                                    </script>
                                </div>
                    @endforeach

                    </tbody>
                    </table>
                </div>
            </div><!-- .card-preview -->
            @endif




            @if ($categories->isNotEmpty())
                <div class="card card-preview">

                    <div class="card-inner">

                        <div class="card-title">
                            <h6 class="title text-center">Recent Categories </h6>
                            <p class="text-center"></p>
                        </div>
                        <div class="card card-preview">

                            <div class="card-inner">

                                <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                    <thead>
                                        <tr class="nk-tb-item nk-tb-head">
                                            <th>#</th>
                                            <th class="nk-tb-col"><span class="sub-text">Name</span></th>
                                            <th class="nk-tb-col tb-col-lg"><span class="sub-text">Created At</span></th>

                                            <th class="nk-tb-col nk-tb-col-tools ">
                                                Status
                                            </th>
                                            <th class="nk-tb-col nk-tb-col-tools ">
                                            </th>

                                            <th class="nk-tb-col nk-tb-col-tools ">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $category)
                                            <tr class="nk-tb-item">
                                                <td class="nk-tb-col nk-tb-col-check">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td class="nk-tb-col">
                                                    <div class="user-card">

                                                        <div class="user-info">
                                                            <span class="tb-lead">{{ $category->name }} <span
                                                                    class="dot dot-success d-md-none ml-1"></span></span>

                                                        </div>
                                                    </div>
                                                </td>




                                                <td class="nk-tb-col tb-col-lg">
                                                    <span>
                                                        @php
                                                            $postdate = date_format($category->created_at, 'F d,Y');

                                                        @endphp

                                                        <?php
                                                        
                                                        $timestamp = strtotime($postdate);
                                                        $newDateFormat = date('M. d, Y', $timestamp);
                                                        echo $newDateFormat;
                                                        
                                                        ?>

                                                    </span>
                                                </td>

                                                <td class="nk-tb-col tb-col-lg">
                                                    @if ($category->status == 0)
                                                        <span class="badge fmdq_Blue">Awaiting Approval<span>
                                                    @endif
                                                    @if ($category->status == 1)
                                                        <span class="badge badge-primary">Approved</span>
                                                    @endif
                                                    @if ($category->status == 2)
                                                        <span class="badge badge-danger">Rejected</span>
                                                    @endif


                                                    @if ($category->status == 3)
                                                        <span class="badge badge-warning">Awaiting approval for
                                                            delete</span>
                                                    @endif

                                                </td>


                                                <td class="nk-tb-col tb-col-lg">

                                                    @if ($category->status == 2)
                                                        {{ $category->note }}
                                                    @endif



                                                </td>
                                                <td class="nk-tb-col nk-tb-col-tools">
                                                    <ul class="nk-tb-actions gx-1">

                                                        <li>
                                                            <div class="drodown">
                                                                <a href="#"
                                                                    class="dropdown-toggle btn btn-icon btn-trigger"
                                                                    data-toggle="dropdown"><em
                                                                        class="icon ni ni-more-h"></em></a>
                                                                <div class="dropdown-menu dropdown-menu-right">

                                                                    <ul class="link-list-opt no-bdr">
                                                                        @if ($category->status != 3)
                                                                            @if ($category->status != 0)
                                                                                @can('category-edit')
                                                                                    <li>
                                                                                        <a href="#" data-toggle="modal"
                                                                                            data-target="#editGroup-{{ $category->id }}">
                                                                                            <em
                                                                                                class="icon ni ni-edit"></em><span>Edit</span>
                                                                                        </a>
                                                                                    </li>
                                                                                @endcan




                                                                                @can('category-delete')
                                                                                    <li><a href="#" data-toggle="modal"
                                                                                            data-target="#deleteGroup-{{ $category->id }}"><em
                                                                                                class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                                    </li>
                                                                                @endcan
                                                                            @endif
                                                                        @endif


                                                                        @php
                                                                            $user = Auth::user()->group_id;

                                                                        @endphp
                                                                        @if ($category->status == 0)
                                                                            @if ($category->group_id == $user)
                                                                                @can('category-approve')
                                                                                    <li><a href="#" id="submit"
                                                                                            onclick="document.getElementById('approve-{{ $category->id }}').submit();"><em
                                                                                                class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                                    </li>
                                                                                @endcan


                                                                                @can('category-reject')
                                                                                    <li><a href="#" data-toggle="modal"
                                                                                            data-target="#reject-{{ $category->id }}"><em
                                                                                                class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                                    </li>
                                                                                @endcan
                                                                            @endif
                                                                        @endif



                                                                        @if ($category->status == 3)
                                                                            @if ($category->group_id == $user)
                                                                                @can('category-approve')
                                                                                    <li><a href="#" id="submit"
                                                                                            onclick="document.getElementById('approve-{{ $category->id }}').submit();"><em
                                                                                                class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                                    </li>
                                                                                @endcan

                                                                                @can('category-reject')
                                                                                    <li><a href="#" data-toggle="modal"
                                                                                            data-target="#reject-{{ $category->id }}"><em
                                                                                                class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                                    </li>
                                                                                @endcan
                                                                                <form id="delete_request-{{ $category->id }}"
                                                                                    action="{{ route('CatStatus', $category->id) }}"
                                                                                    method="POST" class="d-none"
                                                                                    style="display: none">
                                                                                    @csrf
                                                                                    <input name="status"
                                                                                        value="{{ $category->status }}">
                                                                                </form>
                                                                            @endif
                                                                        @endif

                                                                    </ul>

                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    <form id="approve-{{ $category->id }}"
                                                        action="{{ route('CatStatus', $category->id) }}" method="POST"
                                                        class="d-none" style="display: none">
                                                        @csrf
                                                        <input name="status" value="1">
                                                    </form>


                                                    <div class="modal fade" role="dialog" id="reject-{{ $category->id }}">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg"
                                                            role="document">
                                                            <div class="modal-content">
                                                                <a href="#" class="close" data-dismiss="modal"><em
                                                                        class="icon ni ni-cross-sm"></em></a>
                                                                <div class="modal-body modal-body-md">
                                                                    <h5 class="title">{{ $category->name }}</h5>
                                                                    <form method="POST"
                                                                        action="{{ route('CatStatus', $category->id) }}"
                                                                        id="rejectForm-{{ $category->id }}">
                                                                        @csrf
                                                                        <div class="tab-content">
                                                                            <div class="tab-pane active" id="infomation">
                                                                                <div class="row gy-4">
                                                                                    <div class="col-md-12">
                                                                                        <div class="form-group">

                                                                                            <label>Rejection Note</label>
                                                                                            <input hidden name="status"
                                                                                                value="2">
                                                                                            <textarea required class="form-control" name="note"></textarea>


                                                                                        </div>
                                                                                    </div>



                                                                                    <div class="col-12">
                                                                                        <ul
                                                                                            class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                                                                            <li>

                                                                                                <button
                                                                                                    class="btn btn-lg btn-primary btn-block"
                                                                                                    id="rejectSubmitBtn-{{ $category->id }}"
                                                                                                    type="submit">
                                                                                                    <i class="fas fa-spinner fa-spin"
                                                                                                        style="display:none;"></i>
                                                                                                    <span
                                                                                                        class="btn-text">Submit</span>
                                                                                                </button>


                                                                                                <script>
                                                                                                    function loading(buttonId) {
                                                                                                        $("#" + buttonId + " .fa-spinner").show();
                                                                                                        $("#" + buttonId + " .btn-text").html("Processing...");
                                                                                                    }

                                                                                                    document.addEventListener('DOMContentLoaded', function() {
                                                                                                        document.getElementById('rejectForm-{{ $category->id }}').addEventListener('submit', function(event) {
                                                                                                            if (this.checkValidity() === false) {
                                                                                                                event.preventDefault();
                                                                                                                event.stopPropagation();
                                                                                                            } else {
                                                                                                                loading('rejectSubmitBtn-{{ $category->id }}');
                                                                                                                document.getElementById('rejectSubmitBtn-{{ $category->id }}').disabled = true;
                                                                                                            }
                                                                                                            this.classList.add('was-validated');
                                                                                                        }, false);
                                                                                                    });
                                                                                                </script>


                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div><!-- .tab-pane -->

                                                                        </div><!-- .tab-content -->
                                                                    </form>
                                                                </div><!-- .modal-body -->
                                                            </div><!-- .modal-content -->
                                                        </div><!-- .modal-dialog -->
                                                    </div><!-- .modal -->
                                                </td>
                                            </tr><!-- .nk-tb-item  -->
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div><!-- .card-preview -->


                        </tbody>
                        </table>
                    </div>
                </div><!-- .card-preview -->
            @endif





            @if ($subcategories->isNotEmpty())
                <div class="card card-preview">
                    <div class="card-title">
                        <br>
                        <br>
                        <h6 class="title text-center">Recent Subcategories </h6>
                        <p class="text-center"></p>
                    </div>

                    <div class="card-inner">


                        <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                            <thead>
                                <tr class="nk-tb-item nk-tb-head">
                                    <th>#</th>
                                    <th class="nk-tb-col"><span class="sub-text">Sub Category</span></th>
                                    <th class="nk-tb-col"><span class="sub-text">Category</span></th>
                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text">Created At</span></th>

                                    <th class="nk-tb-col nk-tb-col-tools ">
                                        Status
                                    </th>

                                    <th class="nk-tb-col nk-tb-col-tools text-right">
                                    </th>

                                    <th class="nk-tb-col nk-tb-col-tools text-right">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subcategories as $subcategory)
                                    <tr class="nk-tb-item">
                                        <td class="nk-tb-col nk-tb-col-check">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="nk-tb-col">
                                            <div class="user-card">

                                                <div class="user-info">
                                                    <span class="tb-lead">{{ $subcategory->name }} <span
                                                            class="dot dot-success d-md-none ml-1"></span></span>

                                                </div>
                                            </div>
                                        </td>

                                        <td class="nk-tb-col">
                                            <div class="user-card">

                                                <div class="user-info">
                                                    <span class="tb-lead"> {{ optional($subcategory->category)->name }}

                                                        <span class="dot dot-success d-md-none ml-1"></span></span>

                                                </div>
                                            </div>
                                        </td>




                                        <td class="nk-tb-col tb-col-lg">
                                            <span> @php
                                                $postdate = date_format($subcategory->created_at, 'F d,Y');

                                            @endphp

                                                <?php
                                                
                                                $timestamp = strtotime($postdate);
                                                $newDateFormat = date('M. d, Y', $timestamp);
                                                echo $newDateFormat;
                                                
                                                ?></span>
                                        </td>

                                        <td class="nk-tb-col tb-col-lg">
                                            @if ($subcategory->status == 0)
                                                <span class="badge fmdq_Blue">Awaiting Approval<span>
                                            @endif
                                            @if ($subcategory->status == 1)
                                                <span class="badge badge-primary">Approved</span>
                                            @endif
                                            @if ($subcategory->status == 2)
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif


                                            @if ($subcategory->status == 3)
                                                <span class="badge badge-warning">Awaiting approval for
                                                    delete</span>
                                            @endif

                                        </td>


                                        <td class="nk-tb-col tb-col-lg">

                                            @if ($subcategory->status == 2)
                                                {{ $subcategory->note }}
                                            @endif



                                        </td>
                                        <td class="nk-tb-col nk-tb-col-tools">
                                            <ul class="nk-tb-actions gx-1">

                                                <li>
                                                    <div class="drodown">
                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"
                                                            data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                        <div class="dropdown-menu dropdown-menu-right">

                                                            <ul class="link-list-opt no-bdr">
                                                                @if ($subcategory->status != 3)
                                                                    @if ($subcategory->status != 0)
                                                                        @can('category-edit')
                                                                            <li>
                                                                                <a href="#" data-toggle="modal"
                                                                                    data-target="#editGroup-{{ $subcategory->id }}">
                                                                                    <em
                                                                                        class="icon ni ni-edit"></em><span>Edit</span>
                                                                                </a>
                                                                            </li>
                                                                        @endcan




                                                                        @can('category-delete')
                                                                            <li><a href="#" data-toggle="modal"
                                                                                    data-target="#deleteGroup-{{ $subcategory->id }}"><em
                                                                                        class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                            </li>
                                                                        @endcan
                                                                    @endif
                                                                @endif


                                                                @php
                                                                    $user = Auth::user()->group_id;

                                                                @endphp
                                                                @if ($subcategory->status == 0)
                                                                    @can('category-approve')
                                                                        <li><a href="#" id="submit"
                                                                                onclick="document.getElementById('approve-{{ $subcategory->id }}').submit();"><em
                                                                                    class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                        </li>
                                                                    @endcan


                                                                    @can('category-reject')
                                                                        <li><a href="#" data-toggle="modal"
                                                                                data-target="#reject-{{ $subcategory->id }}"><em
                                                                                    class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                        </li>
                                                                    @endcan
                                                                @endif




                                                                @if ($subcategory->status == 3)
                                                                    @can('category-approve')
                                                                        <li><a href="#" id="submit"
                                                                                onclick="document.getElementById('approve-{{ $category->id }}').submit();"><em
                                                                                    class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                        </li>
                                                                    @endcan

                                                                    @can('category-reject')
                                                                        <li><a href="#" data-toggle="modal"
                                                                                data-target="#reject-{{ $category->id }}"><em
                                                                                    class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                        </li>
                                                                    @endcan
                                                                    <form id="delete_request-{{ $category->id }}"
                                                                        action="{{ route('subCatestatus', $category->id) }}"
                                                                        method="POST" class="d-none" style="display: none">
                                                                        @csrf
                                                                        <input name="status"
                                                                            value="{{ $category->status }}">
                                                                    </form>
                                                                @endif


                                                            </ul>

                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <form id="approve-{{ $subcategory->id }}"
                                                action="{{ route('subCatestatus', $subcategory->id) }}" method="POST"
                                                class="d-none" style="display: none">
                                                @csrf
                                                <input name="status" value="1">
                                            </form>


                                            <div class="modal fade" role="dialog" id="reject-{{ $subcategory->id }}">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <a href="#" class="close" data-dismiss="modal"><em
                                                                class="icon ni ni-cross-sm"></em></a>
                                                        <div class="modal-body modal-body-md">
                                                            <h5 class="title">{{ $subcategory->name }}</h5>
                                                            <form method="POST"
                                                                action="{{ route('subCatestatus', $subcategory->id) }}"
                                                                id="rejectForm-{{ $subcategory->id }}">
                                                                @csrf
                                                                <div class="tab-content">
                                                                    <div class="tab-pane active" id="infomation">
                                                                        <div class="row gy-4">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">

                                                                                    <label>Rejection Note</label>
                                                                                    <input hidden name="status"
                                                                                        value="2">
                                                                                    <textarea required class="form-control" name="note"></textarea>


                                                                                </div>
                                                                            </div>



                                                                            <div class="col-12">
                                                                                <ul
                                                                                    class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                                                                    <li>
                                                                                        <button
                                                                                            class="btn btn-lg btn-primary btn-block"
                                                                                            id="rejectSubmitBtn-{{ $subcategory->id }}"
                                                                                            type="submit">
                                                                                            <i class="fas fa-spinner fa-spin"
                                                                                                style="display:none;"></i>
                                                                                            <span
                                                                                                class="btn-text">Submit</span>
                                                                                        </button>


                                                                                        <script>
                                                                                            function loading(buttonId) {
                                                                                                $("#" + buttonId + " .fa-spinner").show();
                                                                                                $("#" + buttonId + " .btn-text").html("Processing...");
                                                                                            }

                                                                                            document.addEventListener('DOMContentLoaded', function() {
                                                                                                document.getElementById('rejectForm-{{ $subcategory->id }}').addEventListener('submit', function(
                                                                                                    event) {
                                                                                                    if (this.checkValidity() === false) {
                                                                                                        event.preventDefault();
                                                                                                        event.stopPropagation();
                                                                                                    } else {
                                                                                                        loading('rejectSubmitBtn-{{ $subcategory->id }}');
                                                                                                        document.getElementById('rejectSubmitBtn-{{ $subcategory->id }}').disabled = true;
                                                                                                    }
                                                                                                    this.classList.add('was-validated');
                                                                                                }, false);
                                                                                            });
                                                                                        </script>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div><!-- .tab-pane -->

                                                                </div><!-- .tab-content -->
                                                            </form>
                                                        </div><!-- .modal-body -->
                                                    </div><!-- .modal-content -->
                                                </div><!-- .modal-dialog -->
                                            </div><!-- .modal -->
                                        </td>
                                    </tr><!-- .nk-tb-item  -->
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            @endif




            @if ($entities->isNotEmpty())
                <div class="card card-preview">


                    <div class="card-inner">
                        <div class="card-title">
                            <h6 class="title text-center">Recent Entities </h6>
                            <p class="text-center"></p>
                        </div>
                        <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                            <thead>
                                <tr class="nk-tb-item nk-tb-head">
                                    <th>#</th>
                                    <th class="nk-tb-col"><span class="sub-text">Name</span></th>
                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text">Created At</span></th>
                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text">Status</span></th>

                                    <th class="nk-tb-col nk-tb-col-tools text-right">
                                    </th>

                                    <th class="nk-tb-col nk-tb-col-tools text-right">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($entities as $entity)
                                    <tr class="nk-tb-item">
                                        <td class="nk-tb-col nk-tb-col-check">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="nk-tb-col">
                                            <div class="user-card">

                                                <div class="user-info">
                                                    <span class="tb-lead">{{ $entity->name }} <span
                                                            class="dot dot-success d-md-none ml-1"></span></span>

                                                </div>
                                            </div>
                                        </td>




                                        <td class="nk-tb-col tb-col-lg">
                                            <span>
                                                @php
                                                    $postdate = date_format($entity->created_at, 'F d,Y');

                                                @endphp

                                                <?php
                                                
                                                $timestamp = strtotime($postdate);
                                                $newDateFormat = date('M. d, Y', $timestamp);
                                                echo $newDateFormat;
                                                
                                                ?>

                                            </span>
                                        </td>

                                        <td class="nk-tb-col tb-col-lg">
                                            @if ($entity->status == 0)
                                                <span class="badge fmdq_Blue">Awaiting Approval<span>
                                            @endif
                                            @if ($entity->status == 1)
                                                <span class="badge badge-primary">Approved</span>
                                            @endif
                                            @if ($entity->status == 2)
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif


                                            @if ($entity->status == 3)
                                                <span class="badge badge-warning">Awaiting approval for
                                                    delete</span>
                                            @endif

                                        </td>



                                        <td class="nk-tb-col tb-col-lg">

                                            @if ($entity->status == 2)
                                                {{ $entity->note }}
                                            @endif




                                        </td>
                                        <td class="nk-tb-col nk-tb-col-tools">
                                            <ul class="nk-tb-actions gx-1">

                                                <li>
                                                    <div class="drodown">
                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"
                                                            data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                        <div class="dropdown-menu dropdown-menu-right">

                                                            <ul class="link-list-opt no-bdr">
                                                                @if ($entity->status == 1 || $entity->status == 2)
                                                                    @can('entity-edit')
                                                                        <li>
                                                                            <a href="#" data-toggle="modal"
                                                                                data-target="#editGroup-{{ $entity->id }}">
                                                                                <em class="icon ni ni-edit"></em><span>Edit</span>
                                                                            </a>
                                                                        </li>
                                                                    @endcan




                                                                    @can('entity-delete')
                                                                        <li><a href="#" data-toggle="modal"
                                                                                data-target="#deleteGroup-{{ $entity->id }}"><em
                                                                                    class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                        </li>
                                                                    @endcan
                                                                @endif



                                                                @if ($entity->status == 0)
                                                                    @can('entity-approve')
                                                                        <li><a href="#" id="submit"
                                                                                onclick="document.getElementById('approve-{{ $entity->id }}').submit();"><em
                                                                                    class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                        </li>
                                                                    @endcan


                                                                    @can('entity-reject')
                                                                        <li><a href="#" data-toggle="modal"
                                                                                data-target="#reject-{{ $entity->id }}"><em
                                                                                    class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                        </li>
                                                                    @endcan
                                                                @endif



                                                                @if ($entity->status == 3)
                                                                    @can('entity-approve')
                                                                        <li><a href="#" id="submit"
                                                                                onclick="document.getElementById('approve-{{ $entity->id }}').submit();"><em
                                                                                    class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                        </li>
                                                                    @endcan

                                                                    @can('entity-reject')
                                                                        <li><a href="#" data-toggle="modal"
                                                                                data-target="#reject-{{ $entity->id }}"><em
                                                                                    class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                        </li>
                                                                    @endcan
                                                                    <form id="delete_request-{{ $entity->id }}"
                                                                        action="{{ route('EntityStatus', $entity->id) }}"
                                                                        method="POST" class="d-none" style="display: none">
                                                                        @csrf
                                                                        <input name="status" value="{{ $entity->status }}">
                                                                    </form>
                                                                @endif

                                                            </ul>

                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <form id="approve-{{ $entity->id }}"
                                                action="{{ route('EntityStatus', $entity->id) }}" method="POST"
                                                class="d-none" style="display: none">
                                                @csrf
                                                <input name="status" value="1">
                                            </form>


                                            <div class="modal fade" role="dialog" id="reject-{{ $entity->id }}">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <a href="#" class="close" data-dismiss="modal"><em
                                                                class="icon ni ni-cross-sm"></em></a>
                                                        <div class="modal-body modal-body-md">
                                                            <h5 class="title">{{ $entity->name }}</h5>
                                                            <form method="POST"
                                                                action="{{ route('EntityStatus', $entity->id) }}"
                                                                id="rejectForm-{{ $entity->id }}">
                                                                @csrf
                                                                <div class="tab-content">
                                                                    <div class="tab-pane active" id="infomation">
                                                                        <div class="row gy-4">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">

                                                                                    <label>Rejection Note</label>
                                                                                    <input hidden name="status"
                                                                                        value="2">
                                                                                    <textarea required class="form-control" name="note"></textarea>


                                                                                </div>
                                                                            </div>



                                                                            <div class="col-12">
                                                                                <ul
                                                                                    class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                                                                    <li>
                                                                                        <button
                                                                                            class="btn btn-lg btn-primary btn-block"
                                                                                            id="rejectSubmitBtn-{{ $entity->id }}"
                                                                                            type="submit">
                                                                                            <i class="fas fa-spinner fa-spin"
                                                                                                style="display:none;"></i>
                                                                                            <span
                                                                                                class="btn-text">Submit</span>
                                                                                        </button>


                                                                                        <script>
                                                                                            function loading(buttonId) {
                                                                                                $("#" + buttonId + " .fa-spinner").show();
                                                                                                $("#" + buttonId + " .btn-text").html("Processing...");
                                                                                            }

                                                                                            document.addEventListener('DOMContentLoaded', function() {
                                                                                                document.getElementById('rejectForm-{{ $entity->id }}').addEventListener('submit', function(event) {
                                                                                                    if (this.checkValidity() === false) {
                                                                                                        event.preventDefault();
                                                                                                        event.stopPropagation();
                                                                                                    } else {
                                                                                                        loading('rejectSubmitBtn-{{ $entity->id }}');
                                                                                                        document.getElementById('rejectSubmitBtn-{{ $entity->id }}').disabled = true;
                                                                                                    }
                                                                                                    this.classList.add('was-validated');
                                                                                                }, false);
                                                                                            });
                                                                                        </script>

                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div><!-- .tab-pane -->

                                                                </div><!-- .tab-content -->
                                                            </form>
                                                        </div><!-- .modal-body -->
                                                    </div><!-- .modal-content -->
                                                </div><!-- .modal-dialog -->
                                            </div><!-- .modal -->
                                        </td>
                                    </tr><!-- .nk-tb-item  -->
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div><!-- .card-preview -->
            @endif




            @if ($groups->isNotEmpty())
                <div class="card card-preview">

                    <div class="card-inner">
                        <div class="card-title">
                            <h6 class="title text-center">Recent Groups </h6>
                            <p class="text-center"></p>
                        </div>

                        <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                            <thead>
                                <tr class="nk-tb-item nk-tb-head">
                                    <th>#</th>
                                    <th class="nk-tb-col"><span class="sub-text">Name</span></th>

                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text">Created At</span></th>
                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text">Status</span></th>
                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text"></span></th>

                                    <th class="nk-tb-col nk-tb-col-tools text-right">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($groups as $group)
                                    <tr class="nk-tb-item">
                                        <td class="nk-tb-col nk-tb-col-check">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="nk-tb-col">
                                            <div class="user-card">

                                                <div class="user-info">
                                                    <span class="tb-lead">{{ $group->name }} <span
                                                            class="dot dot-success d-md-none ml-1"></span></span>

                                                </div>
                                            </div>
                                        </td>




                                        <td class="nk-tb-col tb-col-lg">
                                            <span>
                                                @php
                                                    $postdate = date_format($group->created_at, 'F d,Y');

                                                @endphp

                                                <?php
                                                
                                                $timestamp = strtotime($postdate);
                                                $newDateFormat = date('M. d, Y', $timestamp);
                                                echo $newDateFormat;
                                                
                                                ?>


                                            </span>
                                        </td>

                                        <td class="nk-tb-col tb-col-lg">
                                            @if ($group->status == 0)
                                                <span class="badge fmdq_Blue">Awaiting Approval<span>
                                            @endif
                                            @if ($group->status == 1)
                                                <span class="badge badge-primary">Approved</span>
                                            @endif
                                            @if ($group->status == 2)
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif


                                            @if ($group->status == 3)
                                                <span class="badge badge-warning">Awaiting approval for
                                                    delete</span>
                                            @endif

                                        </td>



                                        <td class="nk-tb-col tb-col-lg">

                                            @if ($group->status == 2)
                                                {{ $group->note }}
                                            @endif




                                        </td>

                                        <td class="nk-tb-col nk-tb-col-tools">
                                            <ul class="nk-tb-actions gx-1">

                                                <li>
                                                    <div class="drodown">
                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"
                                                            data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                        <div class="dropdown-menu dropdown-menu-right">

                                                            <ul class="link-list-opt no-bdr">
                                                                @if ($group->status == 1 || $group->status == 2)
                                                                    @can('group-edit')
                                                                        <li>
                                                                            <a href="#" data-toggle="modal"
                                                                                data-target="#editGroup-{{ $group->id }}">
                                                                                <em class="icon ni ni-edit"></em><span>Edit</span>
                                                                            </a>
                                                                        </li>
                                                                    @endcan




                                                                    @can('group-delete')
                                                                        <li><a href="#" data-toggle="modal"
                                                                                data-target="#deleteGroup-{{ $group->id }}"><em
                                                                                    class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                        </li>
                                                                    @endcan
                                                                @endif



                                                                @if ($group->status == 0)
                                                                    @can('group-approve')
                                                                        <li><a href="#" id="submit"
                                                                                onclick="document.getElementById('approve-{{ $group->id }}').submit();"><em
                                                                                    class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                        </li>
                                                                    @endcan


                                                                    @can('group-reject')
                                                                        <li><a href="#" data-toggle="modal"
                                                                                data-target="#reject-{{ $group->id }}"><em
                                                                                    class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                        </li>
                                                                    @endcan
                                                                @endif



                                                                @if ($group->status == 3)
                                                                    @can('group-approve')
                                                                        <li><a href="#" id="submit"
                                                                                onclick="document.getElementById('approve-{{ $group->id }}').submit();"><em
                                                                                    class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                        </li>
                                                                    @endcan

                                                                    @can('group-reject')
                                                                        <li><a href="#" data-toggle="modal"
                                                                                data-target="#reject-{{ $group->id }}"><em
                                                                                    class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                        </li>
                                                                    @endcan
                                                                    <form id="delete_request-{{ $group->id }}"
                                                                        action="{{ route('groupStatus', $group->id) }}"
                                                                        method="POST" class="d-none" style="display: none">
                                                                        @csrf
                                                                        <input name="status" value="{{ $group->status }}">
                                                                    </form>
                                                                @endif

                                                            </ul>

                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <form id="approve-{{ $group->id }}"
                                                action="{{ route('groupStatus', $group->id) }}" method="POST"
                                                class="d-none" style="display: none">
                                                @csrf
                                                <input name="status" value="1">
                                            </form>


                                            <div class="modal fade" role="dialog" id="reject-{{ $group->id }}">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <a href="#" class="close" data-dismiss="modal"><em
                                                                class="icon ni ni-cross-sm"></em></a>
                                                        <div class="modal-body modal-body-md">
                                                            <h5 class="title">{{ $group->name }}</h5>
                                                            <form method="POST"
                                                                action="{{ route('groupStatus', $group->id) }}">
                                                                @csrf
                                                                <div class="tab-content">
                                                                    <div class="tab-pane active" id="infomation">
                                                                        <div class="row gy-4">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">

                                                                                    <label>Rejection Note</label>
                                                                                    <input hidden name="status"
                                                                                        value="2">
                                                                                    <textarea required class="form-control" name="note"></textarea>


                                                                                </div>
                                                                            </div>



                                                                            <div class="col-12">
                                                                                <ul
                                                                                    class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                                                                    <li>
                                                                                        <button type="submit"
                                                                                            class="btn btn-primary">Submit</button>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div><!-- .tab-pane -->

                                                                </div><!-- .tab-content -->
                                                            </form>
                                                        </div><!-- .modal-body -->
                                                    </div><!-- .modal-content -->
                                                </div><!-- .modal-dialog -->
                                            </div><!-- .modal -->
                                        </td>
                                    </tr><!-- .nk-tb-item  -->
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div><!-- .card-preview -->
            @endif


            @if ($news_alert->isNotEmpty())
                <div class="card card-preview">

                    <div class="card-inner">
                        <div class="card-title">
                            <h6 class="title text-center">Recent News Alert </h6>
                            <p class="text-center"></p>
                        </div>

                        <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                            <thead>
                                <tr class="nk-tb-item nk-tb-head">
                                    <th>#</th>
                                    <th class="nk-tb-col"><span class="sub-text">Title</span></th>
                                    {{-- <th class="nk-tb-col"><span class="sub-text">news</span></th> --}}
                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text">Created At</span></th>

                                    <th class="nk-tb-col nk-tb-col-tools text-right">
                                    </th>
                                    <th class="nk-tb-col nk-tb-col-tools text-right">
                                    </th>
                                    <th class="nk-tb-col nk-tb-col-tools text-right">
                                    </th>
                                </tr>
                            </thead>


                            <tbody>
                                @foreach ($news_alert as $news)
                                    <tr class="nk-tb-item">
                                        <td class="nk-tb-col nk-tb-col-check">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="nk-tb-col">
                                            <div class="user-card">

                                                <div class="user-info">
                                                    <span class="tb-lead">{{ $news->title }} <span
                                                            class="dot dot-success d-md-none ml-1"></span></span>

                                                </div>
                                            </div>
                                        </td>


                                        <td class="nk-tb-col tb-col-lg">
                                            <span>
                                                @php
                                                    $postdate = date_format($news->created_at, 'F d,Y');

                                                @endphp

                                                <?php
                                                
                                                $timestamp = strtotime($postdate);
                                                $newDateFormat = date('M. d, Y', $timestamp);
                                                echo $newDateFormat;
                                                
                                                ?>


                                            </span>
                                        </td>

                                        <td class="nk-tb-col tb-col-lg">
                                            @if ($news->status == 0)
                                                <span class="badge fmdq_Blue">Awaiting Approval<span>
                                            @endif
                                            @if ($news->status == 1)
                                                <span class="badge badge-primary">Approved</span>
                                            @endif
                                            @if ($news->status == 2)
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif


                                            @if ($news->status == 3)
                                                <span class="badge badge-warning">Awaiting approval for
                                                    delete</span>
                                            @endif

                                        </td>


                                        <td class="nk-tb-col tb-col-lg">

                                            @if ($news->status == 2)
                                                {{ $news->note }}
                                            @endif



                                        </td>

                                        <td class="nk-tb-col nk-tb-col-tools">
                                            <ul class="nk-tb-actions gx-1">

                                                <li>
                                                    <div class="drodown">
                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"
                                                            data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                        <div class="dropdown-menu dropdown-menu-right">

                                                            <ul class="link-list-opt no-bdr">
                                                                <li>
                                                                    <a href="{{ route('view_news', $news->id) }}">
                                                                        <em class="icon ni ni-edit"></em><span>view</span>
                                                                    </a>
                                                                </li>
                                                                @if ($news->status != 3)
                                                                    @if ($news->status != 0)
                                                                        @can('news-edit')
                                                                            <li>
                                                                                <a href="{{ route('edit_news', $news->id) }}">
                                                                                    <em
                                                                                        class="icon ni ni-edit"></em><span>Edit</span>
                                                                                </a>
                                                                            </li>
                                                                        @endcan




                                                                        @can('news-delete')
                                                                            <li><a href="#" data-toggle="modal"
                                                                                    data-target="#deleteNews-{{ $news->id }}"><em
                                                                                        class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                            </li>
                                                                        @endcan
                                                                    @endif
                                                                @endif



                                                                @if ($news->status == 0)
                                                                    @can('news-approve')
                                                                        <li><a href="#" id="submit"
                                                                                onclick="document.getElementById('approve-{{ $news->id }}').submit();"><em
                                                                                    class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                        </li>
                                                                    @endcan


                                                                    @can('news-reject')
                                                                        <li><a href="#" data-toggle="modal"
                                                                                data-target="#reject-{{ $news->id }}"><em
                                                                                    class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                        </li>
                                                                    @endcan
                                                                @endif



                                                                @if ($news->status == 3)
                                                                    @can('news-approve')
                                                                        <li><a href="#" id="submit"
                                                                                onclick="document.getElementById('approve-{{ $news->id }}').submit();"><em
                                                                                    class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                        </li>
                                                                    @endcan

                                                                    @can('news-reject')
                                                                        <li><a href="#" data-toggle="modal"
                                                                                data-target="#reject-{{ $news->id }}"><em
                                                                                    class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                        </li>
                                                                    @endcan
                                                                    <form id="delete_request-{{ $news->id }}"
                                                                        action="{{ route('news_status', $news->id) }}"
                                                                        method="POST" class="d-none" style="display: none">
                                                                        @csrf
                                                                        <input name="status" value="{{ $news->status }}">
                                                                    </form>
                                                                @endif

                                                            </ul>

                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <form id="approve-{{ $news->id }}"
                                                action="{{ route('news_status', $news->id) }}" method="POST"
                                                class="d-none" style="display: none">
                                                @csrf
                                                <input name="status" value="1">
                                            </form>


                                            <div class="modal fade" role="dialog" id="reject-{{ $news->id }}">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <a href="#" class="close" data-dismiss="modal"><em
                                                                class="icon ni ni-cross-sm"></em></a>
                                                        <div class="modal-body modal-body-md">
                                                            <h5 class="title">{{ $news->name }}</h5>
                                                            <form method="POST"
                                                                action="{{ route('news_status', $news->id) }}"
                                                                id="rejectForm-{{ $news->id }}">
                                                                @csrf
                                                                <div class="tab-content">
                                                                    <div class="tab-pane active" id="infomation">
                                                                        <div class="row gy-4">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">

                                                                                    <label>Rejection Note</label>
                                                                                    <input hidden name="status"
                                                                                        value="2">
                                                                                    <textarea required class="form-control" name="note"></textarea>


                                                                                </div>
                                                                            </div>



                                                                            <div class="col-12">
                                                                                <ul
                                                                                    class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                                                                    <li>
                                                                                        <button
                                                                                            class="btn btn-lg btn-primary btn-block"
                                                                                            id="rejectSubmitBtn-{{ $news->id }}"
                                                                                            type="submit">
                                                                                            <i class="fas fa-spinner fa-spin"
                                                                                                style="display:none;"></i>
                                                                                            <span
                                                                                                class="btn-text">Submit</span>
                                                                                        </button>


                                                                                        <script>
                                                                                            function loading(buttonId) {
                                                                                                $("#" + buttonId + " .fa-spinner").show();
                                                                                                $("#" + buttonId + " .btn-text").html("Processing...");
                                                                                            }

                                                                                            document.addEventListener('DOMContentLoaded', function() {
                                                                                                document.getElementById('rejectForm-{{ $news->id }}').addEventListener('submit', function(event) {
                                                                                                    if (this.checkValidity() === false) {
                                                                                                        event.preventDefault();
                                                                                                        event.stopPropagation();
                                                                                                    } else {
                                                                                                        loading('rejectSubmitBtn-{{ $news->id }}');
                                                                                                        document.getElementById('rejectSubmitBtn-{{ $news->id }}').disabled = true;
                                                                                                    }
                                                                                                    this.classList.add('was-validated');
                                                                                                }, false);
                                                                                            });
                                                                                        </script>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div><!-- .tab-pane -->

                                                                </div><!-- .tab-content -->
                                                            </form>
                                                        </div><!-- .modal-body -->
                                                    </div><!-- .modal-content -->
                                                </div><!-- .modal-dialog -->
                                            </div>
                                        </td>
                                    </tr><!-- .nk-tb-item  -->
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            @endif


            @if ($users->isNotEmpty())
                <div class="card card-preview">

                    <div class="card-inner">
                        <div class="card-title">
                            <h6 class="title text-center">Recent Users</h6>
                            <p class="text-center"></p>
                        </div>
                        <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                            <thead>
                                <tr class="nk-tb-item nk-tb-head">
                                    <th>#</th>
                                    <th class="nk-tb-col"><span class="sub-text">User</span></th>
                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text">Group</span></th>
                                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">Email</span></th>
                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Role</span></th>

                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text">Created At</span></th>
                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text">Status</span></th>
                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text"></span></th>

                                    <th class="nk-tb-col nk-tb-col-tools text-right">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
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
                                        <td class="nk-tb-col tb-col-lg" data-order="Email Verified - Kyc Unverified">
                                            {{ optional($user->group)->name }}

                                        </td>
                                        <td class="nk-tb-col tb-col-mb" data-order="35040.34">
                                            <span class="tb-amount">{{ $user->email }}<span class="currency"></span></span>
                                        </td>
                                        <td class="nk-tb-col tb-col-md">
                                            @if (!empty($user->getRoleNames()))
                                                @foreach ($user->getRoleNames() as $val)
                                                    <span class="badge badge-primary">{{ $val }}</span>
                                                @endforeach
                                            @endif
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
                                            @if ($user->status == 0)
                                                <span class="badge fmdq_Blue">Awaiting Approval<span>
                                            @endif
                                            @if ($user->status == 1)
                                                <span class="badge badge-primary">Approved</span>
                                            @endif
                                            @if ($user->status == 2)
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif

                                            @if ($user->status == 4)
                                                <span class="badge badge-danger">Disabled</span>
                                            @endif


                                            @if ($user->status == 3)
                                                <span class="badge badge-warning">Awaiting approval for
                                                    delete</span>
                                            @endif

                                        </td>


                                        <td class="nk-tb-col tb-col-lg">

                                            @if ($user->status == 2)
                                                {{ $user->note }}
                                            @endif



                                        </td>

                                        <td class="nk-tb-col nk-tb-col-tools">
                                            <ul class="nk-tb-actions gx-1">

                                                <li>
                                                    <div class="drodown">
                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"
                                                            data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                        <div class="dropdown-menu dropdown-menu-right">

                                                            <ul class="link-list-opt no-bdr">
                                                                @if ($user->status == 1 || $user->status == 2)
                                                                    @can('user-edit')
                                                                        <li>
                                                                            <a href="#" data-toggle="modal"
                                                                                data-target="#editUser-{{ $user->id }}">
                                                                                <em class="icon ni ni-edit"></em><span>Edit</span>
                                                                            </a>
                                                                        </li>


                                                                        <li><a href="#" data-toggle="modal"
                                                                                data-target="#changeStatus-{{ $user->id }}"><em
                                                                                    class="icon ni ni-trash"></em><span>Change
                                                                                    Status</span></a>
                                                                        </li>
                                                                    @endcan
                                                                @endif




                                                                @if ($user->status == 0)
                                                                    @can('user-approve')
                                                                        <li><a href="#" id="submit"
                                                                                onclick="document.getElementById('approve-{{ $user->id }}').submit();"><em
                                                                                    class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                        </li>
                                                                    @endcan


                                                                    @can('user-reject')
                                                                        <li><a href="#" data-toggle="modal"
                                                                                data-target="#reject-{{ $user->id }}"><em
                                                                                    class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                        </li>
                                                                    @endcan
                                                                @endif



                                                                @if ($user->status == 3)
                                                                    @can('user-approve')
                                                                        <li><a href="#" id="submit"
                                                                                onclick="document.getElementById('approve-{{ $user->id }}').submit();"><em
                                                                                    class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                        </li>
                                                                    @endcan

                                                                    @can('user-reject')
                                                                        <li><a href="#" data-toggle="modal"
                                                                                data-target="#reject-{{ $user->id }}"><em
                                                                                    class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                        </li>
                                                                    @endcan
                                                                    <form id="delete_request-{{ $user->id }}"
                                                                        action="{{ route('userStatus', $user->id) }}"
                                                                        method="POST" class="d-none" style="display: none">
                                                                        @csrf
                                                                        <input name="status" value="{{ $user->status }}">
                                                                    </form>
                                                                @endif



                                                            </ul>

                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <form id="approve-{{ $user->id }}"
                                                action="{{ route('userStatus', $user->id) }}" method="POST" class="d-none"
                                                style="display: none">
                                                @csrf
                                                <input name="status" value="1">
                                            </form>


                                            <div class="modal fade" role="dialog" id="reject-{{ $user->id }}">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <a href="#" class="close" data-dismiss="modal"><em
                                                                class="icon ni ni-cross-sm"></em></a>
                                                        <div class="modal-body modal-body-md">
                                                            <h5 class="title">{{ $user->name }}</h5>
                                                            <form method="POST" id="modifyForm-{{ $user->id }}"
                                                                action="{{ route('userStatus', $user->id) }}"
                                                                id="rejectForm-{{ $user->id }}">
                                                                @csrf
                                                                <div class="tab-content">
                                                                    <div class="tab-pane active" id="infomation">
                                                                        <div class="row gy-4">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">

                                                                                    <label>Rejection Note</label>
                                                                                    <input hidden name="status"
                                                                                        value="2">
                                                                                    <textarea required class="form-control" name="note"></textarea>


                                                                                </div>
                                                                            </div>



                                                                            <div class="col-12">
                                                                                <ul
                                                                                    class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                                                                    <li>
                                                                                        <button
                                                                                            class="btn btn-lg btn-primary btn-block"
                                                                                            id="rejectSubmitBtn-{{ $user->id }}"
                                                                                            type="submit">
                                                                                            <i class="fas fa-spinner fa-spin"
                                                                                                style="display:none;"></i>
                                                                                            <span
                                                                                                class="btn-text">Submit</span>
                                                                                        </button>


                                                                                        <script>
                                                                                            function loading(buttonId) {
                                                                                                $("#" + buttonId + " .fa-spinner").show();
                                                                                                $("#" + buttonId + " .btn-text").html("Processing...");
                                                                                            }

                                                                                            document.addEventListener('DOMContentLoaded', function() {
                                                                                                document.getElementById('rejectForm-{{ $user->id }}').addEventListener('submit', function(event) {
                                                                                                    if (this.checkValidity() === false) {
                                                                                                        event.preventDefault();
                                                                                                        event.stopPropagation();
                                                                                                    } else {
                                                                                                        loading('rejectSubmitBtn-{{ $user->id }}');
                                                                                                        document.getElementById('rejectSubmitBtn-{{ $user->id }}').disabled = true;
                                                                                                    }
                                                                                                    this.classList.add('was-validated');
                                                                                                }, false);
                                                                                            });
                                                                                        </script>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div><!-- .tab-pane -->

                                                                </div><!-- .tab-content -->
                                                            </form>
                                                        </div><!-- .modal-body -->
                                                    </div><!-- .modal-content -->
                                                </div><!-- .modal-dialog -->
                                            </div><!-- .modal -->
                                        </td>
                                    </tr><!-- .nk-tb-item  -->


                                    <div class="modal fade" role="dialog" id="changeStatus-{{ $user->id }}">
                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Change Status</h5>
                                                    <a href="#" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <em class="icon ni ni-cross"></em>
                                                    </a>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" action="{{ route('statusUser', $user->id) }}"
                                                        class="form-validate is-alter" enctype="multipart/form-data">
                                                        @csrf

                                                        <div class="row gx-4 gy-3">
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="event-title">Select
                                                                        Status</label>
                                                                    <div class="form-control-wrap">
                                                                        <select class="form-control" name="status">
                                                                            {{-- <option value="1"
                                                                                            {{ $user->status == 1 ? 'selected' : '' }}>
                                                                                            Active</option> --}}
                                                                            <option value="4"
                                                                                {{ $user->status == 4 ? 'selected' : '' }}>
                                                                                Inactive</option>
                                                                        </select>


                                                                    </div>
                                                                </div>
                                                            </div>




                                                            <div class="col-12">
                                                                <ul class="d-flex justify-content-between gx-4 mt-1">
                                                                    <li>

                                                                        <button id="deleteSubmitBtn-{{ $user->id }}"
                                                                            type="submit" class="btn fmdq_Gold">
                                                                            <i class="fas fa-spinner fa-spin"
                                                                                style="display:none;"></i>
                                                                            <span class="btn-text">Submit</span></button>


                                                                        <script>
                                                                            document.getElementById('deleteSubmitBtn-{{ $user->id }}').addEventListener('click', function() {
                                                                                loading('deleteSubmitBtn-{{ $user->id }}');
                                                                                setTimeout(() => {
                                                                                    document.getElementById('deleteSubmitBtn-{{ $user->id }}').disabled = true;
                                                                                }, 50);
                                                                            });
                                                                        </script>
                                                                    </li>

                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div><!-- .modal-dialog -->

                                    </div>

                                    <script>
                                        function loading(buttonId) {
                                            $("#" + buttonId + " .fa-spinner").show();
                                            $("#" + buttonId + " .btn-text").html("Processing...");
                                        }

                                        document.addEventListener('DOMContentLoaded', function() {
                                            document.getElementById('modifyForm-{{ $user->id }}').addEventListener('submit', function(event) {
                                                if (this.checkValidity() === false) {
                                                    event.preventDefault();
                                                    event.stopPropagation();
                                                } else {
                                                    loading('ModifySubmitBtn-{{ $user->id }}');
                                                    document.getElementById('ModifySubmitBtn-{{ $user->id }}').disabled = true;
                                                }
                                                this.classList.add('was-validated');
                                            }, false);
                                        });
                                    </script>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            @endif







            @if ($roles->isNotEmpty())
                <div class="card card-preview">

                    <div class="card-inner">

                        <div class="card-title">
                            <h6 class="title text-center">Recent Roles</h6>
                            <p class="text-center"></p>
                        </div>
                        <table class="datatable-init nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                            <thead>
                                <tr class="nk-tb-item nk-tb-head">
                                    <th>#</th>
                                    <th class="nk-tb-col"><span class="sub-text">Name</span></th>
                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text">Created At</span></th>
                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text">Status</span></th>
                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text"></span></th>

                                    <th class="nk-tb-col nk-tb-col-tools text-right">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr class="nk-tb-item">
                                        <td class="nk-tb-col nk-tb-col-check">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="nk-tb-col">
                                            <div class="user-card">

                                                <div class="user-info">
                                                    <span class="tb-lead">{{ $role->name }} <span
                                                            class="dot dot-success d-md-none ml-1"></span></span>

                                                </div>
                                            </div>
                                        </td>

                                        <td class="nk-tb-col tb-col-lg">
                                            <span>
                                                @php
                                                    $postdate = date_format($role->created_at, 'F d,Y');

                                                @endphp

                                                <?php
                                                
                                                $timestamp = strtotime($postdate);
                                                $newDateFormat = date('M. d, Y', $timestamp);
                                                echo $newDateFormat;
                                                
                                                ?>

                                            </span>
                                        </td>

                                        <td class="nk-tb-col tb-col-lg">
                                            @if ($role->status == 0)
                                                <span class="badge fmdq_Blue">Awaiting Approval<span>
                                            @endif
                                            @if ($role->status == 1)
                                                <span class="badge badge-primary">Approved</span>
                                            @endif
                                            @if ($role->status == 2)
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif


                                            @if ($role->status == 3)
                                                <span class="badge badge-warning">Awaiting approval for
                                                    delete</span>
                                            @endif

                                        </td>


                                        <td class="nk-tb-col tb-col-lg">

                                            @if ($role->status == 2)
                                                {{ $role->note }}
                                            @endif




                                        </td>


                                        <td class="nk-tb-col nk-tb-col-tools">
                                            <ul class="nk-tb-actions gx-1">

                                                <li>
                                                    <div class="drodown">
                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"
                                                            data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                        <div class="dropdown-menu dropdown-menu-right">

                                                            <ul class="link-list-opt no-bdr">
                                                                @if ($role->status == 1 || $role->status == 2)
                                                                    @can('role-edit')
                                                                        <li>
                                                                            <a href="#" data-toggle="modal"
                                                                                data-target="#editGroup-{{ $role->id }}">
                                                                                <em class="icon ni ni-edit"></em><span>Edit</span>
                                                                            </a>
                                                                        </li>
                                                                    @endcan




                                                                    @can('role-delete')
                                                                        <li><a href="#" data-toggle="modal"
                                                                                data-target="#deleteGroup-{{ $role->id }}"><em
                                                                                    class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                        </li>
                                                                    @endcan
                                                                @endif



                                                                @if ($role->status == 0)
                                                                    @can('role-approve')
                                                                        <li><a href="#" id="submit"
                                                                                onclick="document.getElementById('approve-{{ $role->id }}').submit();"><em
                                                                                    class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                        </li>
                                                                    @endcan


                                                                    @can('role-reject')
                                                                        <li><a href="#" data-toggle="modal"
                                                                                data-target="#reject-{{ $role->id }}"><em
                                                                                    class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                        </li>
                                                                    @endcan
                                                                @endif



                                                                @if ($role->status == 3)
                                                                    @can('role-approve')
                                                                        <li><a href="#" id="submit"
                                                                                onclick="document.getElementById('approve-{{ $role->id }}').submit();"><em
                                                                                    class="icon ni ni-check-round-fill"></em><span>Approve</span></a>
                                                                        </li>
                                                                    @endcan

                                                                    @can('role-reject')
                                                                        <li><a href="#" data-toggle="modal"
                                                                                data-target="#reject-{{ $role->id }}"><em
                                                                                    class="icon ni ni-cross-circle-fill"></em><span>Reject</span></a>
                                                                        </li>
                                                                    @endcan
                                                                    <form id="delete_request-{{ $role->id }}"
                                                                        action="{{ route('rolestatus', $role->id) }}"
                                                                        method="POST" class="d-none"
                                                                        style="display: none">
                                                                        @csrf
                                                                        <input name="status" value="{{ $role->status }}">
                                                                    </form>
                                                                @endif

                                                            </ul>

                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <form id="approve-{{ $role->id }}"
                                                action="{{ route('rolestatus', $role->id) }}" method="POST"
                                                class="d-none" style="display: none">
                                                @csrf
                                                <input name="status" value="1">
                                            </form>


                                            <div class="modal fade" role="dialog" id="reject-{{ $role->id }}">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <a href="#" class="close" data-dismiss="modal"><em
                                                                class="icon ni ni-cross-sm"></em></a>
                                                        <div class="modal-body modal-body-md">
                                                            <h5 class="title">{{ $role->name }}</h5>
                                                            <form method="POST"
                                                                action="{{ route('rolestatus', $role->id) }}"
                                                                id="rejectForm-{{ $role->id }}">
                                                                @csrf
                                                                <div class="tab-content">
                                                                    <div class="tab-pane active" id="infomation">
                                                                        <div class="row gy-4">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">

                                                                                    <label>Rejection Note</label>
                                                                                    <input hidden name="status"
                                                                                        value="2">
                                                                                    <textarea required class="form-control" name="note"></textarea>


                                                                                </div>
                                                                            </div>



                                                                            <div class="col-12">
                                                                                <ul
                                                                                    class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                                                                    <li>
                                                                                        <button
                                                                                            class="btn btn-lg btn-primary btn-block"
                                                                                            id="rejectSubmitBtn-{{ $role->id }}"
                                                                                            type="submit">
                                                                                            <i class="fas fa-spinner fa-spin"
                                                                                                style="display:none;"></i>
                                                                                            <span
                                                                                                class="btn-text">Submit</span>
                                                                                        </button>


                                                                                        <script>
                                                                                            function loading(buttonId) {
                                                                                                $("#" + buttonId + " .fa-spinner").show();
                                                                                                $("#" + buttonId + " .btn-text").html("Processing...");
                                                                                            }

                                                                                            document.addEventListener('DOMContentLoaded', function() {
                                                                                                document.getElementById('rejectForm-{{ $role->id }}').addEventListener('submit', function(event) {
                                                                                                    if (this.checkValidity() === false) {
                                                                                                        event.preventDefault();
                                                                                                        event.stopPropagation();
                                                                                                    } else {
                                                                                                        loading('rejectSubmitBtn-{{ $role->id }}');
                                                                                                        document.getElementById('rejectSubmitBtn-{{ $role->id }}').disabled = true;
                                                                                                    }
                                                                                                    this.classList.add('was-validated');
                                                                                                }, false);
                                                                                            });
                                                                                        </script>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div><!-- .tab-pane -->

                                                                </div><!-- .tab-content -->
                                                            </form>
                                                        </div><!-- .modal-body -->
                                                    </div><!-- .modal-content -->
                                                </div><!-- .modal-dialog -->
                                            </div><!-- .modal -->
                                        </td>
                                    </tr><!-- .nk-tb-item  -->
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div><!-- .card-preview -->
            @endif



            @if (Auth::user()->hasRole('Super_Administrator_Authoriser') || Auth::user()->hasRole('Super_Administrator_Inputter'))
                <div class="card card-preview">

                    <div class="card-inner">

                        <div class="card-title">
                            <h6 class="title text-center">Top Document Uploaded</h6>
                            <p class="text-center"></p>
                        </div>
                        <table class="datatable-init nk-tb-list nk-tb-ulist table-striped" data-auto-responsive="false">
                            <thead>
                                <tr class="nk-tb-item nk-tb-head">
                                    <th class="nk-tb-col">S/N</th>
                                    <th class="nk-tb-col">Tille</th>
                                    <th class="nk-tb-col">Inputter</th>
                                    <th class="nk-tb-col">Date Uploaded</th>
                                    <th class="nk-tb-col">Authoriser</th>
                                    <th class="nk-tb-col">Approve/Reject Date</th>
                                    <th class="nk-tb-col">Status</th>

                                    {{-- <th class="nk-tb-col">Date Created</th>
                                        <th class="nk-tb-col">Date Update</th>
                                        <th class="nk-tb-col">Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $regulation)
                                    <tr class="nk-tb-item">
                                        <td class="nk-tb-col"> {{ $loop->iteration }}</td>
                                        <td class="nk-tb-col"> {{ optional($regulation->regulation)->title }}</td>
                                        <td class="nk-tb-col">{{ optional($regulation->inputter)->name }}</td>
                                        <td class="nk-tb-col">{{ $regulation->created_at }}</td>
                                        <td class="nk-tb-col"> {{ optional($regulation->authoriser)->name }}
                                        </td>
                                        <td class="nk-tb-col"> {{ $regulation->authoriser_time }}
                                        </td>
                                        <td class="nk-tb-col">
                                            @if ($regulation->status == 0)
                                                <span class="badge fmdq_Blue">Pending</span>
                                            @endif
                                            @if ($regulation->status == 1)
                                                <span class="badge badge-primary">Approved</span>
                                            @endif
                                            @if ($regulation->status == 2)
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif

                                        </td>


                                        {{-- <td class="nk-tb-col">
                                                @php
                                                    $DateCreated = \Carbon\Carbon::parse(
                                                        $regulation->created_at,
                                                    )->format('Y-m-d');
                                                @endphp
                                                {{ $DateCreated }}



                                            </td>


                                            <td class="nk-tb-col">
                                                @php
                                                    $DateCreated = \Carbon\Carbon::parse(
                                                        $regulation->updated_at,
                                                    )->format('Y-m-d');
                                                @endphp
                                                {{ $DateCreated }}



                                            </td>

                                            <td class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1">

                                                    <li>
                                                        <div class="drodown">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"
                                                                data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <ul class="link-list-opt no-bdr">
                                                                    @can('regulation-edit')
                                                                        <li><a href="{{ route('edit_doc', $regulation->id) }}"><em
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
                                                                                    class="icon ni ni-shield-star-fill"></em><span>Ceased</span></a>
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







                                            </td> --}}
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div><!-- .card-preview -->
            @endif






        </div>
        </div>
        </div>

        </div>


        </div>








        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var ctx = document.getElementById('categoryChart').getContext('2d');
                var chartData = @json($chartData);

                var pieChartData = {
                    labels: chartData.labels,
                    dataUnit: 'Documents',
                    legend: false,
                    datasets: [{
                        borderColor: "#fff",
                        backgroundColor: chartData.colors,
                        data: chartData.data
                    }]
                };

                var categoryChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: pieChartData.labels,
                        datasets: [{
                            data: pieChartData.datasets[0].data,
                            backgroundColor: pieChartData.datasets[0].backgroundColor,
                            borderColor: pieChartData.datasets[0].borderColor,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: pieChartData.legend,
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': ' + context.raw + ' ' + pieChartData
                                            .dataUnit;
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endsection
