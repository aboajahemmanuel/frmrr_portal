<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="">
    <meta charset="utf-8">

    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('public/admin/images/fmdq_favicon.png') }}">
    <!-- Page Title  -->
    <title>Financial Markets Regulations & Rules Repository Portal</title>
    <!-- StyleSheets  -->
    <link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/admin/css/theme.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/admin/css/libs/fontawesome-icons.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/admin/css/libs/themify-icons.css') }}" rel="stylesheet" type="text/css" />
</head>

<body class="nk-body bg-white npc-general pg-auth">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap nk-wrap-nosidebar">
                <!-- content @s -->
                @yield('content')



                <div class="nk-footer nk-auth-footer-full">
                    <div class="container wide-lg">
                        <div class="row g-3">

                            <div class="col-lg-12">
                                <div class="nk-block-content text-center text-lg-left">
                                    <center>
                                        <p>Powered by iQx Consult Limited &copy; </p>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- wrap @e -->
        </div>
        <!-- content @e -->
    </div>
    <!-- main @e -->
    </div>



    <script>
        function loading() {

            $(".btn .fa-spinner").show();
            $(".btn .btn-text").html("");

            /* var button = document.getElementById("submit");
             button.innerHTML = "Loading...";
             var span = document.getElementById("button_span");
             span.classList.add("spinner-grow");
             span.classList.add("spinner-grow-sm");*/
        }
    </script>

    <!-- app-root @e -->
    <!-- JavaScript -->
    <script src="{{ asset('public/admin/js/bundle.js') }}"></script>
    <script src="{{ asset('public/admin/js/scripts.js') }}"></script>
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
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>  --}}


</html>
