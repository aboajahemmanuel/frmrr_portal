<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Session timeout configuration -->
    <meta name="session-timeout-minutes" content="{{ \App\Models\SessionSetting::getCurrentTimeout() }}">
    <link rel="stylesheet" href="{{ asset('public/users/style.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js" integrity="sha384-JUMjoW8OzDJw4oFpWIB2Bu/c6768ObEthBMVSiIx4ruBIEdyNSUQAjJNFqT5pnJ6" crossorigin="anonymous"></script>

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha384-R334r6kryDNB/GWs2kfB6blAOyWPCxjdHSww/mo7fel+o5TM/AOobJ0QpGRXSDh4" crossorigin="anonymous">







    <style>
        .loader-mask {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #fff;
            z-index: 99999;
        }

        /* .loader {
            position: absolute;
            left: 50%;
            top: 50%;
            width: 50px;
            height: 50px;
            font-size: 0;
            color: #1d326d;
            display: inline-block;
            margin: -25px 0 0 -25px;
            text-indent: -9999em;
            -webkit-transform: translateZ(0);
            -ms-transform: translateZ(0);
            transform: translateZ(0);
        } */

        .loader {
            position: absolute;
            left: 50%;
            top: 50%;
            /* width: 50px;
            height: 50px; */
            font-size: 0;
            color: #1d326d;
            display: inline-block;
            margin: -25px 0 0 -25px;
            text-indent: -9999em;
            -webkit-transform: translateZ(0);
            -ms-transform: translateZ(0);
            transform: translateZ(0);
            width: 40px;
            height: 40px;
            --c: no-repeat linear-gradient(#1d326d 0 0);
            background: var(--c), var(--c), var(--c), var(--c);
            background-size: 21px 21px;
            animation: l5 1.5s infinite cubic-bezier(0.3, 1, 0, 1);
        }

        @keyframes l5 {
            0% {
                background-position: 0 0, 100% 0, 100% 100%, 0 100%
            }

            33% {
                background-position: 0 0, 100% 0, 100% 100%, 0 100%;
                width: 60px;
                height: 60px
            }

            66% {
                background-position: 100% 0, 100% 100%, 0 100%, 0 0;
                width: 60px;
                height: 60px
            }

            100% {
                background-position: 100% 0, 100% 100%, 0 100%, 0 0
            }
        }

        /* .lead {
            font-size: 13px;
        }

        .loader div {
            background-color: #1d326d;
            display: inline-block;
            float: none;
            position: absolute;
            top: 0;
            left: 0;
            width: 50px;
            height: 50px;
            opacity: .5;
            border-radius: 50%;
            -webkit-animation: ballPulseDouble 2s ease-in-out infinite;
            animation: ballPulseDouble 2s ease-in-out infinite;
        }

        .loader div:last-child {
            -webkit-animation-delay: -1s;
            animation-delay: -1s;
        } */
        /*
        @-webkit-keyframes ballPulseDouble {

            0%,
            100% {
                -webkit-transform: scale(0);
                transform: scale(0);
            }

            50% {
                -webkit-transform: scale(1);
                transform: scale(1);
            }
        }

        @keyframes ballPulseDouble {

            0%,
            100% {
                -webkit-transform: scale(0);
                transform: scale(0);
            }

            50% {
                -webkit-transform: scale(1);
                transform: scale(1);
            }
        } */
    </style>











    <title>FMRR Login</title>
</head>

<body>
    {{-- <div class="loader-mask">
        <div class="loader">
            <div></div>
            <div></div>
        </div>
    </div> --}}



    @yield('content')



    <p class="iqx">Powered by iQx Consult Limited. <br>
Copyright © FMDQ Group PLC. All rights reserved.
</p>
    </div>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha384-Si3HKTyQYGU+NC4aAF3ThcOSvK+ZQiyEKlYyfjiIFKMqsnCmfHjGa1VK1kYP9UdS" crossorigin="anonymous"></script>
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

    <script>
        function loading() {
            $(".btn .fa-spinner").show();
            $(".btn .btn-text").html("Processing...");
        }


        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('groupForm').addEventListener('submit', function(event) {
                if (this.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                } else {
                    loading('addSubmitBtn');
                    document.getElementById('addSubmitBtn').disabled = true;
                }
                this.classList.add('was-validated');
            }, false);
        });
    </script>







    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

   
</body>

</html>