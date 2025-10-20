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
                                    <h3 class="nk-block-title page-title">Disclaimers</h3>
                                    <div class="nk-block-des text-soft">

                                    </div>
                                </div><!-- .nk-block-head-content -->
                                <div class="nk-block-head-content">
                                    <div class="toggle-wrap nk-block-tools-toggle">
                                        <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1"
                                            data-target="more-options"><em class="icon ni ni-more-v"></em></a>
                                        <div class="toggle-expand-content" data-content="more-options">
                                            <ul class="nk-block-tools g-3">






                                            </ul>
                                        </div>
                                    </div>
                                </div><!-- .nk-block-head-content -->
                            </div><!-- .nk-block-between -->
                        </div><!-- .nk-block-head -->
                        <div class="nk-block nk-block-lg">

                            <div class="card card-preview">
                                <div class="card-inner">
                                    <table class="datatable-init nowrap table">
                                        <thead>
                           <tr>
                                <th>S/N</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>IP Address</th>
                                <th>Accepted At</th>
                                
                            </tr>
                                        </thead>
                                        <tbody>
                              @foreach($acceptances as $acceptance)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $acceptance->user->name ?? 'N/A' }}</td>
                                <td>{{ $acceptance->user->email ?? 'N/A' }}</td>
                                <td>{{ $acceptance->ip_address ?? 'N/A' }}</td>
                                <td> {{ \Carbon\Carbon::parse($acceptance->created_at)->format('M. j, Y g:i:s A') }} </td>
                               
                            </tr>
                            @endforeach

                                        </tbody>
                                    </table>
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

@endsection