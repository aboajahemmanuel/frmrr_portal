<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <title>Financial Markets Regulations & Rules Repository Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Financial Markets Regulations & Rules Repository Portal" name="description">
    <meta content="Financial Markets Regulations & Rules Repository Portal" name="author">

    <!-- Session timeout configuration -->
    <meta name="session-timeout-minutes" content="{{ \App\Models\SessionSetting::getCurrentTimeout() }}">


    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('public/assets/images/ifmdq_logo.png') }}">
    <!-- DataTables -->
    <link href="{{ asset('public/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('public/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css">
    <!-- Responsive datatable examples -->
    <link href="{{ asset('public/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css">
    <link href="{{ asset('public/assets/libs/chartist/chartist.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Css -->
    <link href="{{ asset('public/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet"
        type="text/css">
    <!-- Icons Css -->
    <link href="{{ asset('public/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <!-- App Css-->
    <link href="{{ asset('public/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/assets/css/style.css') }}" id="app-style" rel="stylesheet" type="text/css">


    <link rel="stylesheet" type="text/css" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">



</head>

<body data-sidebar="dark">

    <!-- Begin page -->
    <div id="layout-wrapper">


        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box">
                        <a href="index.html" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="{{ asset('public/assets/images/login-logo.jpg') }}" alt=""
                                    height="45">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset('public/assets/images/login-logo.jpg') }}" alt=""
                                    height="45">
                            </span>
                        </a>

                        <a href="index.html" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="{{ asset('public/assets/images/ifmdq_logo.png') }}" alt=""
                                    height="40">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset('public/assets/images/login-logo.jpg') }}" alt=""
                                    height="45">
                            </span>
                        </a>
                    </div>

                    <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect"
                        id="vertical-menu-btn">
                        <i class="mdi mdi-menu"></i>
                    </button>


                </div>

                <div class="d-flex">
                    <!-- App Search-->










                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle header-profile-user"
                                src="{{ asset('public/users/assets/images/profile.jpg') }}" alt="Header Avatar">
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            {{-- <a class="dropdown-item" href="#">{{Auth::user()->email}}</a> --}}

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                              document.getElementById('logout-form').submit();"><i
                                    class="bx bx-power-off font-size-17 align-middle me-1 text-danger"></i> <i
                                    class="mdi mdi-lock-open-outline font-size-17 align-middle me-1"></i> Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>



                </div>
            </div>
        </header>


        @include('layouts.adminsidebar')
        @yield('content')

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        ©
                        <script>
                            document.write(new Date().getFullYear())
                        </script> FMDQ Group<span class="d-none d-sm-inline-block"> </span>
                    </div>
                </div>
            </div>
        </footer>

    </div>
    <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->



    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('public/assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('public/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('public/assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('public/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('public/assets/libs/node-waves/waves.min.js') }}"></script>


    <!-- Peity chart-->
    <script src="{{ asset('public/assets/libs/peity/jquery.peity.min.js') }}"></script>

    <!-- Plugin Js-->
    <script src="{{ asset('public/assets/libs/chartist/chartist.min.js') }}"></script>
    <script src="{{ asset('public/assets/libs/chartist-plugin-tooltips/chartist-plugin-tooltip.min.js') }}"></script>

    <script src="{{ asset('public/assets/js/pages/dashboard.init.js') }}"></script>



    <!-- Required datatable js -->
    <script src="{{ asset('public/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('public/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('public/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('public/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('public/assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('public/assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('public/assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('public/assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('public/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('public/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('public/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Datatable init js -->
    <script src="{{ asset('public/assets/js/pages/datatables.init.js') }}"></script>

    <script src="{{ asset('public/assets/libs/parsleyjs/parsley.min.js') }}"></script>

    <script src="{{ asset('public/assets/js/pages/form-validation.init.js') }}"></script>

    <script src="{{ asset('public/assets/js/pages/form-advanced.init.js') }}"></script>

    <script src="{{ asset('public/assets/js/app.js') }}"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    
@include('layouts.sessioncheck')
</body>




</html>