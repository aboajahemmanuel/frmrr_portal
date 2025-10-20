@extends('layouts.externalprofile')

@section('content')
    <link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <section class="profile-main">

        <div class="profile-content">
            <div class="profile-left-side">
                <div class="profile-left-side-container">
                    <p class="profile-left-side-header">{{ Auth::user()->name }}</p>
                    @if ($isSubscribed)
                        <div class="profile-sub-status">
                            <p>Subscription</p>

                            <div class="">

                                @if ($isSubscribed)
                                    <div class="profile-gold-border-details">Active</div>
                                @else
                                    <div class="" style="display: flex; gap: 30px;">

                                        <div class="profile-gold-border" style="color: white; text-align: center">Expired
                                        </div>
                                        <div class="profile-gold-border" style="color: white; text-align: center"><a
                                                href="{{ url('subscribe') }}">Renew</a>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endif
                    <div class="profile-horizontal-line"></div>
                    <div class="profile-profile-details">
                        <div class="profile-title">Email</div>
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
                                <div class="profile-info">{{ $userPlan->subscriptionPlan->name }}</div>
                            </div>
                        @endif
                        <div data-toggle="modal" data-target="#modalForm">

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
                                    <a class="nav-link active" data-toggle="tab" href="#tabItem1">Saved Documents</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabItem2">Downloaded Documents</a>
                                </li>

                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tabItem1">
                                    <table class="datatable-init nk-tb-list nk-tb-ulist table-striped"
                                        data-auto-responsive="false">
                                        <thead>
                                            <tr class="nk-tb-item nk-tb-head">
                                                <th class="nk-tb-col">S/N</th>
                                                <th class="nk-tb-col">Name of the Document</th>
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
                                                        @php
                                                            $saveDate = date_format($save->created_at, 'F d,Y');

                                                        @endphp

                                                        <?php
                                                        
                                                        $timestamp = strtotime($saveDate);
                                                        $saveDate = date('M. d, Y', $timestamp);
                                                        echo $saveDate;
                                                        
                                                        ?>

                                                    </td>

                                                    <td class="tb-odr-action">
                                                        <div class="tb-odr-btns d-none d-sm-inline">
                                                            @if ($isSubscribed)
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
                                                <th class="nk-tb-col">Name of the Document</th>
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
                                                        @php
                                                            $downloadDate = date_format($download->created_at, 'F d,Y');

                                                        @endphp

                                                        <?php
                                                        
                                                        $timestamp = strtotime($downloadDate);
                                                        $downloadDate = date('M. d, Y', $timestamp);
                                                        echo $downloadDate;
                                                        
                                                        ?>

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
                            <label class="form-label" for="email-address">Email address</label>
                            <div class="form-control-wrap">
                                <input type="email" value="{{ Auth::user()->email }}" name="email"
                                    class="form-control" id="email-address" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="phone-no">Phone No</label>
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
                            <button type="submit" class="btn btn-lg btn-primary">Save Informations</button>
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
