<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Session timeout configuration -->
    <meta name="session-timeout-minutes" content="{{ \App\Models\SessionSetting::getCurrentTimeout() }}">
    <link rel="stylesheet" href="{{ asset('public/users/style.css') }}" />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <title>FMRR Home</title>

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

    </style>
</head>

<body>
    <div class="full-page">
        <div class="content">
            @include('layouts.externalheader')




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
                        Powered by iQx Consult Limited. <br>
Copyright © FMDQ Group PLC. All rights reserved.

                        </div>
                    </div>
                </div>
            </footer>
        </div>

    </div>

    @include('layouts.sessioncheck')

</body>

</html>