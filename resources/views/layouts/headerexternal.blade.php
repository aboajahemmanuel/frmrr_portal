<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('public/users/style.css') }}" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <title>FMRR Home</title>
</head>

<body>
    <div class="full-page">
        <div class="content">
            <section class="top-half-guidelines">
                <div class="w-1100">
                    @include('layouts.appnavbar')


                    @yield('content')


                    <footer class="footer">
                        <div class="w-1100">
                            <div class="full-width">
                                <!-- <div class="footer-logo">

                                    <img src="{{ asset('public/users/assets/FMDQ-Logo.png') }}" alt="FMDQ Logo" />
                                </div> -->
                            </div>
                            <div class="footer-desc">

                                <hr />
                                <div class="footer-d">
                                Powered by iQx Consult Limited.<br>
Copyright © FMDQ Group PLC. All rights reserved.
 
                                </div>
                            </div>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
                    </footer>
