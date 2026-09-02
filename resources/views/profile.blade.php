@extends('layouts.externalprofile')

@section('content')
    <link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha384-R334r6kryDNB/GWs2kfB6blAOyWPCxjdHSww/mo7fel+o5TM/AOobJ0QpGRXSDh4" crossorigin="anonymous">
    <style>
        .form-control-select .custom-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='4' height='5' viewBox='0 0 4 5'%3e%3cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: left 0.75rem center !important;
            background-size: 8px 10px !important;
            background-color: #fff !important;
            padding-left: 1.75rem !important;
            padding-right: 0.75rem !important;
        }
        .form-control-select::after {
            display: none !important;
        }
    </style>
    <section class="profile-main">

        <div class="profile-content">
            <div class="profile-left-side">
                <div class="profile-left-side-container">
                    <p class="profile-left-side-header">{{ Auth::user()->name }}</p>
                    <div class="profile-sub-status" style="display: flex; flex-direction: row; align-items: center;">
                        <p style="margin: 0; margin-right: 5px;">Subscription</p>

                        <div class="">
                            @if ($isSubscribed)
                                <div class="profile-gold-border-details">Active</div>
                            @elseif ($userPlan)
                                <div class="" style="display: flex; gap: 30px;">
                                    <div class="profile-gold-border" style="color: white; text-align: center">Expired
                                    </div>
                                    <div class="profile-gold-border" style="color: white; text-align: center"><a
                                            href="{{ url('subscribe') }}">Renew</a>
                                    </div>
                                </div>
                            @else
                                <div class="" style="display: flex; gap: 30px;">
                                    <div class="profile-gold-border" style="color: white; text-align: center"><a
                                            href="{{ url('subscribe') }}">Subscribe</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="profile-horizontal-line"></div>
                    <div class="profile-profile-details">
                        <div class="profile-title">Email Address</div>
                        <div class="profile-info">{{ Auth::user()->email }}</div>
                    </div>

                    <div class="profile-horizontal-line"></div>
                    <div class="profile-profile-details">
                        <div class="profile-title">Phone Number</div>
                        <div class="profile-info">{{ Auth::user()->phone }}</div>
                    </div>
                    <div class="profile-horizontal-line"></div>
                    <div class="profile-profile-details edit">
                        <div>
                            <div class="profile-title">Password</div>
                            <div class="profile-info">*************</div>
                        </div>
                    </div>


                    {{-- <div class="profile-profile-details">
                        <div class="profile-title">Billing Address</div>
                        <div class="profile-info">23 Norman Williams Street, Ikoyi, Lagos </div>
                    </div> --}}
                    <div class="profile-horizontal-line"></div>
                    <div class="profile-profile-details edit">
                        @if ($isSubscribed)
                            <div class="profile-profile-details">
                                <div class="profile-title">Subscription Type</div>
                                <div class="profile-info">{{ optional($userPlan?->subscriptionPlan)->name ?? 'Active Plan' }}</div>
                            </div>
                        @elseif ($userPlan && $userPlan->subscriptionPlan)
                            <div class="profile-profile-details">
                                <div class="profile-title">Subscription Type</div>
                                <div class="profile-info">{{ $userPlan->subscriptionPlan->name }} (Expired)</div>
                            </div>
                        @endif
                        <div data-toggle="modal" data-target="#modalForm" style="cursor: pointer;">

                            <img src="{{ asset('public/users/assets/edit-btn.svg') }}" alt="edit">
                        </div>
                    </div>
                </div>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                   document.getElementById('logout-form').submit();">
                    <div class="profile-logout">Log out</div>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
            <div class="profile-right-side">
                <div class="profile-right-side-top">
                    <div class="profile-usage-info usage-one">
                        <p class="profile-fig">{{ $docSaved }} <span class="profile-ui-img"><img
                                    src="{{ asset('public/users/assets/book.svg') }}" alt="read"></span></p>
                        <p class="profile-doc-action">Documents Saved</p>
                    </div>
                    <div class="profile-usage-info">
                        <div>
                            <p class="profile-fig">{{ $docDownloaded }} <span class="profile-ui-img"><img
                                        src="{{ asset('public/users/assets/download.svg') }}" alt="download"></span></p>
                            <p class="profile-doc-action">Documents Downloaded</p>
                        </div>
                    </div>
                </div>
                <div class="profile-right-side-mid">
                    <div class="profile-right-side-mid-container">

                        <div class="profile-rsmc-mid">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tabItem1">Documents Saved</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabItem2">Documents Downloaded</a>
                                </li>

                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tabItem1">
                                    <table class="datatable-init nk-tb-list nk-tb-ulist table-striped"
                                        data-auto-responsive="false">
                                        <thead>
                                            <tr class="nk-tb-item nk-tb-head">
                                                <th class="nk-tb-col">S/N</th>
                                                <th class="nk-tb-col">Title</th>
                                                <th class="nk-tb-col">Date</th>
                                                <th class="nk-tb-col">Action</th>



                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($savedDocuments as $save)
                                                <tr class="nk-tb-item">
                                                    <td class="nk-tb-col">{{ $loop->iteration }}</td>
                                                    <td class="nk-tb-col"> {{ optional($save->regulation)->title }}</td>
                                                    <td class="nk-tb-col">
                                                        {{ str_replace('May.', 'May', \Carbon\Carbon::parse($save->created_at)->format('M. j, Y')) }}
                                                    </td>

                                                    <td class="tb-odr-action">
                                                        <div class="tb-odr-btns d-none d-sm-inline">
                                                            @if ($save->regulation)
                                                                @if ($isSubscribed || (Auth::check() && Auth::user()->usertype == 'internal'))
                                                                    <a href="{{ route('download', $save->regulation->id) }}"
                                                                        class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                                            class="icon ni ni-download"></em></a>
                                                                @else
                                                                    @if (Auth::check())
                                                                        <a href="{{ route('subscribe') }}"
                                                                            class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                                                class="icon ni ni-download"></em></a>
                                                                    @else
                                                                        <a href="{{ route('login') }}"
                                                                            class="btn btn-icon btn-white btn-dim btn-sm btn-primary"><em
                                                                                class="icon ni ni-download"></em></a>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        </div>

                                                    </td>


                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="tabItem2">
                                    <table class="datatable-init nk-tb-list nk-tb-ulist table-striped"
                                        data-auto-responsive="false">
                                        <thead>
                                            <tr class="nk-tb-item nk-tb-head">
                                                <th class="nk-tb-col">S/N</th>
                                                <th class="nk-tb-col">Title</th>
                                                <th class="nk-tb-col">Date</th>




                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($downloadedDocuments as $download)
                                                <tr class="nk-tb-item">
                                                    <td class="nk-tb-col">{{ $loop->iteration }}</td>
                                                    <td class="nk-tb-col">{{ optional($download->regulation)->title }}
                                                    </td>
                                                    <td class="nk-tb-col">
                                                        {{ str_replace('May.', 'May', \Carbon\Carbon::parse($download->created_at)->format('M. j, Y')) }}
                                                    </td>




                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="tabItem3">

                                </div>

                            </div>






                        </div>
                        <div class="profile-colored-sth"></div>
                    </div>
                </div>

            </div>
        </div>

        <div>

        </div>
    </section>
    </div>






    <div class="modal fade" id="modalForm">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Profile</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="{{ route('profile.update') }}" method="POST" class="form-validate is-alter">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="full-name">Full Name</label>
                            <div class="form-control-wrap">
                                <input value="{{ Auth::user()->name }}" type="text" name="name"
                                    class="form-control" id="full-name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="email-address">Email Address</label>
                            <div class="form-control-wrap">
                                <input type="email" value="{{ Auth::user()->email }}" name="email"
                                    class="form-control" id="email-address" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="phone-no">Phone Number</label>
                            <div class="form-control-wrap">
                                <input type="number" value="{{ Auth::user()->phone }}" name="phone"
                                    class="form-control" id="phone-no">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="password">Password</label>
                            <div class="form-control-wrap">
                                <input type="password" name="password" class="form-control" id="password">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password">Confirm Password</label>
                            <div class="form-control-wrap">
                                <input type="password" name="password_confirmation" class="form-control" id="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-primary">Save Information</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>





    <script>
        $(window).load('load', function() {
            // Preloader
            $('.loader').fadeOut();
            $('.loader-mask').delay(250).fadeOut('slow');
        });


        // $(window).load(function() {
        //     // Preloader
        //     $('.loader').fadeOut();
        //     $('.loader-mask').delay(150).fadeOut('slow');
        // });


        $(document).ready(function() {
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error('{{ $error }}', '');
                @endforeach
            @endif

            @if (session('success'))
                toastr.success('{{ session('success') }}', '');
            @endif

            @if (session('error'))
                toastr.error('{{ session('error') }}', '');
            @endif
        });
    </script>


    <script src="{{ asset('public/admin/js/bundle.js') }}"></script>
    <script src="{{ asset('public/admin/js/scripts.js') }}"></script>
    <script src="{{ asset('public/admin/js/charts/chart-ecommerce.js') }}"></script>
    <script src="{{ asset('public/admin/js/libs/datatable-btns.js') }}"></script>
@endsection
</div>
</body>

</html>
