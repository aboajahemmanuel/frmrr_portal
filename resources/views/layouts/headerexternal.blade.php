<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="session-timeout-minutes" content="{{ \App\Models\SessionSetting::getCurrentTimeout() }}">
    <link rel="stylesheet" href="{{ asset('public/users/style.css') }}" />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js" integrity="sha384-JUMjoW8OzDJw4oFpWIB2Bu/c6768ObEthBMVSiIx4ruBIEdyNSUQAjJNFqT5pnJ6" crossorigin="anonymous"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                            <script>
                                $(window).on('load', function() {
                                    // Preloader
                                    $('.loader').fadeOut();
                                    $('.loader-mask').delay(250).fadeOut('slow');
                                });


                                $(document).ready(function() {
                                    @if ($errors->any())
                                        @php
                                            $errorMessages = implode('<br>', $errors->all());
                                        @endphp
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error!',
                                            html: '{!! $errorMessages !!}',
                                            confirmButtonColor: '#3c4d62'
                                        });
                                    @endif

                                    @if (session('success'))
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success!',
                                            text: "{{ session('success') }}",
                                            timer: 3000,
                                            showConfirmButton: false
                                        });
                                    @endif

                                    @if (session('error'))
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error!',
                                            text: "{{ session('error') }}",
                                            confirmButtonColor: '#3c4d62'
                                        });
                                    @endif
                                });
                            </script>
                    </footer>
