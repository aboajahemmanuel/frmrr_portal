@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="components-preview wide-md mx-auto">
                <div class="nk-block-head nk-block-head-lg wide-sm">
                    <div class="nk-block-head-content">
                        <div class="nk-block-head-sub"><a class="back-to" href="{{ route('home') }}"><em class="icon ni ni-arrow-left"></em><span>Back to Home</span></a></div>
                        <h2 class="nk-block-title fw-normal">Disclaimer Acceptance History</h2>
                        <div class="nk-block-des">
                            <p class="lead">View your disclaimer acceptance history.</p>
                        </div>
                    </div>
                </div><!-- .nk-block-head -->
                
                <div class="nk-block nk-block-lg">
                    <div class="card card-bordered">
                        <div class="card-inner">
                            @if($acceptances->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Accepted At</th>
                                                <th>IP Address</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($acceptances as $acceptance)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $acceptance->created_at->format('M d, Y H:i:s') }}</td>
                                                <td>{{ $acceptance->ip_address ?? 'N/A' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="d-flex justify-content-center">
                                    {{ $acceptances->links() }}
                                </div>
                            @else
                                <div class="alert alert-info">
                                    You have not accepted any disclaimers yet.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection