<!doctype html>
<html lang="en">

<head>


    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content=" " />
    <meta name="keywords" content="" />
    <meta content="Financial Markets Regulations & Rules Repository Portal" name="author" />
    <!-- Session timeout configuration -->
    <meta name="session-timeout-minutes" content="{{ \App\Models\SessionSetting::getCurrentTimeout() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('public/assets/images/ifmdq_logo.png') }}">

    <link href="{{ asset('public/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('public/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css">
    <!-- Responsive datatable examples -->
    <link href="{{ asset('public/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css">
    <!-- Choise Css -->
    <link rel="stylesheet"
        href="{{ asset('public/users/assets/libs/choices.js/public/assets/styles/choices.min.css') }}">

    <!-- Swiper Css -->
    <link rel="stylesheet" href="{{ asset('public/users/assets/libs/swiper/swiper-bundle.min.css') }}">

    <!-- Bootstrap Css -->
    <link href="{{ asset('public/users/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet"
        type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('public/users/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('public/users/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <!--Custom Css-->
    <link href="{{ asset('public/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">

</head>

<body>


    <!-- Begin page -->
    <div>


        <!-- Navbar End -->
        @include('layouts.appnavbar')


        <!-- END SIGN-UP MODAL -->


        @yield('content')









        <!-- START FOOTER -->
        {{-- <section class="bg-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="footer-item mt-4 mt-lg-0 me-lg-5">
                                <h4 class="text-white mb-4">Jobcy</h4>
                                <p class="text-white-50">It is a long established fact that a reader will be of a page reader
                                    will be of at its layout.</p>
                                <p class="text-white mt-3">Follow Us on:</p>
                                <ul class="footer-social-menu list-inline mb-0">
                                    <li class="list-inline-item"><a href="#"><i class="uil uil-facebook-f"></i></a></li>
                                    <li class="list-inline-item"><a href="#"><i class="uil uil-linkedin-alt"></i></a></li>
                                    <li class="list-inline-item"><a href="#"><i class="uil uil-google"></i></a></li>
                                    <li class="list-inline-item"><a href="#"><i class="uil uil-twitter"></i></a></li>
                                </ul>
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-2 col-6">
                            <div class="footer-item mt-4 mt-lg-0">
                                <p class="fs-16 text-white mb-4">Company</p>
                                <ul class="list-unstyled footer-list mb-0">
                                    <li><a href="about.html"><i class="mdi mdi-chevron-right"></i> About Us</a></li>
                                    <li><a href="contact.html"><i class="mdi mdi-chevron-right"></i> Contact Us</a></li>
                                    <li><a href="services.html"><i class="mdi mdi-chevron-right"></i> Services</a></li>
                                    <li><a href="blog.html"><i class="mdi mdi-chevron-right"></i> Blog</a></li>
                                    <li><a href="team.html"><i class="mdi mdi-chevron-right"></i> Team</a></li>
                                    <li><a href="pricing.html"><i class="mdi mdi-chevron-right"></i> Pricing</a></li>
                                </ul>
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-2 col-6">
                            <div class="footer-item mt-4 mt-lg-0">
                                <p class="fs-16 text-white mb-4">For Jobs</p>
                                <ul class="list-unstyled footer-list mb-0">
                                    <li><a href="job-categories.html"><i class="mdi mdi-chevron-right"></i> Browser Categories</a></li>
                                    <li><a href="job-list.html"><i class="mdi mdi-chevron-right"></i> Browser Jobs</a></li>
                                    <li><a href="job-details.html"><i class="mdi mdi-chevron-right"></i> Job Details</a></li>
                                    <li><a href="bookmark-jobs.html"><i class="mdi mdi-chevron-right"></i> Bookmark Jobs</a></li>
                                </ul>
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-2 col-6">
                            <div class="footer-item mt-4 mt-lg-0">
                                <p class="text-white fs-16 mb-4">For Candidates</p>
                                <ul class="list-unstyled footer-list mb-0">
                                    <li><a href="candidate-list.html"><i class="mdi mdi-chevron-right"></i> Candidate List</a></li>
                                    <li><a href="candidate-grid.html"><i class="mdi mdi-chevron-right"></i> Candidate Grid</a></li>
                                    <li><a href="candidate-details.html"><i class="mdi mdi-chevron-right"></i> Candidate Details</a></li>
                                </ul>
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-2 col-6">
                            <div class="footer-item mt-4 mt-lg-0">
                                <p class="fs-16 text-white mb-4">Support</p>
                                <ul class="list-unstyled footer-list mb-0">
                                    <li><a href="contact.html"><i class="mdi mdi-chevron-right"></i> Help Center</a></li>
                                    <li><a href="faqs.html"><i class="mdi mdi-chevron-right"></i> FAQ'S</a></li>
                                    <li><a href="privacy-policy.html"><i class="mdi mdi-chevron-right"></i> Privacy Policy</a></li>
                                </ul>
                            </div>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end container-->
            </section> --}}
        <!-- END FOOTER -->

        <!-- START FOOTER-ALT -->
        <div class="footer-alt">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <p class="text-white-50 text-center mb-0">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> &copy; FMDQ Securities Exchange
                        </p>
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end container-->
        </div>
        <!-- END FOOTER -->

        <!-- Style switcher -->

        <!-- end switcher-->

        <!--start back-to-top-->
        <button onclick="topFunction" id="back-to-top">
            <i class="mdi mdi-arrow-up"></i>
        </button>
        <!--end back-to-top-->
    </div>
    <!-- end main content-->

    </div>

    <!-- END layout-wrapper -->

    <!-- JAVASCRIPT -->
    <script src="{{ asset('public/users/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://unicons.iconscout.com/release/v4.0.0/script/monochrome/bundle.js"></script>


    <!-- Choice Js -->
    <script src="{{ asset('public/users/assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>

    <!-- Swiper Js -->
    <script src="{{ asset('public/users/assets/libs/swiper/swiper-bundle.min.js') }}"></script>

    <!-- Index Js -->
    <script src="{{ asset('public/users/assets/js/pages/job-list.init.js') }}"></script>
    <script src="{{ asset('public/users/assets/js/pages/index.init.js') }}"></script>

    <!-- Switcher Js -->
    <script src="{{ asset('public/users/assets/js/pages/switcher.init.js') }}"></script>

    <!-- App Js -->
    <script src="{{ asset('public/users/assets/js/app.js') }}"></script>


    <!-- JAVASCRIPT -->
    <script src="{{ asset('public/assets/libs/jquery/jquery.min.js') }}"></script>




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


    <!-- app-root @e -->
    <!-- JavaScript -->
    <script src="{{ asset('assets/js/bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#btnFetch").click(function() {
                // disable button
                $(this).prop("disabled", true);
                // add spinner to button
                $(this).html(
                    `<center><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></center>`
                );
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>


</body>

</html>