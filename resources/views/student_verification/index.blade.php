@extends('layouts.master')

@section('content')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Student Research Applications</h3>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block nk-block-lg">

                        <div class="example-alert">
                            @if (\Session::has('success'))
                                <div class="alert alert-success alert-icon alert-dismissible">
                                    <em class="icon ni ni-check-circle"></em> <strong> {{ \Session::get('success') }}<button
                                            class="close" data-dismiss="alert"></button>
                                </div>
                            @endif

                            @if (\Session::has('error'))
                                <div class="alert alert-danger alert-icon alert-dismissible">
                                    <em class="icon ni ni-check-circle"></em> <strong> {{ \Session::get('error') }}<button
                                            class="close" data-dismiss="alert"></button>
                                </div>
                            @endif
                        </div>

                        <div class="card card-bordered mb-4">
                            <div class="card-inner">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Status</label>
                                                <div class="form-control-wrap">
                                                    <select name="status" class="form-select form-control">
                                                        <option value="0" @if (request('status', '0') == '0') selected @endif>Pending</option>
                                                        <option value="1" @if (request('status') == '1') selected @endif>Approved</option>
                                                        <option value="2" @if (request('status') == '2') selected @endif>Rejected</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8 text-right align-self-end">
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card card-preview">
                            <div class="card-inner">
                                <table class="nk-tb-list nk-tb-ulist" data-auto-responsive="false">
                                    <thead>
                                        <tr class="nk-tb-item nk-tb-head">
                                            <th>#</th>
                                            <th class="nk-tb-col"><span class="sub-text">Applicant</span></th>
                                            <th class="nk-tb-col"><span class="sub-text">Institution</span></th>
                                            <th class="nk-tb-col"><span class="sub-text">Student ID</span></th>
                                            <th class="nk-tb-col tb-col-lg"><span class="sub-text">Submitted</span></th>
                                            <th class="nk-tb-col"><span class="sub-text">Status</span></th>
                                            <th class="nk-tb-col nk-tb-col-tools text-right"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $request)
                                            <tr class="nk-tb-item">
                                                <td class="nk-tb-col nk-tb-col-check">
                                                    {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                                </td>
                                                <td class="nk-tb-col">
                                                    <span class="tb-lead">{{ optional($request->applicant)->name }}</span><br>
                                                    <span class="tb-sub">{{ optional($request->applicant)->email }}</span>
                                                </td>
                                                <td class="nk-tb-col">{{ $request->institution_name }}</td>
                                                <td class="nk-tb-col">{{ $request->student_id_number ?? '-' }}</td>
                                                <td class="nk-tb-col tb-col-lg">{{ $request->created_at->format('M. d, Y') }}</td>
                                                <td class="nk-tb-col">
                                                    @if ($request->status == 0)
                                                        <span class="badge fmdq_Blue">Pending</span>
                                                    @elseif ($request->status == 1)
                                                        <span class="badge badge-primary">Approved</span>
                                                    @else
                                                        <span class="badge badge-danger">Rejected</span>
                                                    @endif
                                                    @if ($request->status == 2 && $request->note)
                                                        <div class="text-soft" style="font-size: 12px;">{{ $request->note }}</div>
                                                    @endif
                                                </td>
                                                <td class="nk-tb-col nk-tb-col-tools text-right">
                                                    <a href="{{ route('studentVerification.proof', $request->id) }}" class="btn btn-sm btn-outline-primary">View Proof</a>
                                                    @if ($request->status == 0)
                                                        @can('student-verification-approve')
                                                            <a href="#" class="btn btn-sm btn-success"
                                                                onclick="event.preventDefault(); document.getElementById('approve-{{ $request->id }}').submit();">Approve</a>
                                                        @endcan
                                                        @can('student-verification-reject')
                                                            <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#reject-{{ $request->id }}">Reject</a>
                                                        @endcan
                                                    @endif
                                                </td>
                                            </tr>
                                            <form id="approve-{{ $request->id }}" action="{{ route('studentVerification.approve', $request->id) }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                            <div class="modal fade" id="reject-{{ $request->id }}">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                                                        <div class="modal-body modal-body-md">
                                                            <h5 class="title">Reject application from {{ optional($request->applicant)->name }}</h5>
                                                            <form method="POST" action="{{ route('studentVerification.reject', $request->id) }}">
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label>Reason for rejection</label>
                                                                    <textarea required class="form-control" name="note"></textarea>
                                                                </div>
                                                                <button type="submit" class="btn btn-lg btn-danger btn-block mt-3">Reject Application</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-inner">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }} entries
                                    </div>
                                    @if ($data->hasPages())
                                        <div>
                                            {{ $data->appends(request()->input())->links('vendor.pagination.bootstrap-4') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
