<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Session timeout configuration -->
    <meta name="session-timeout-minutes" content="{{ \App\Models\SessionSetting::getCurrentTimeout() }}">
    <link rel="stylesheet" href="{{ asset('public/users/style.css') }}" />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js" integrity="sha384-JUMjoW8OzDJw4oFpWIB2Bu/c6768ObEthBMVSiIx4ruBIEdyNSUQAjJNFqT5pnJ6" crossorigin="anonymous"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>FMRR - {{ $marketTag->name }}</title>

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

        .loader {
            position: absolute;
            left: 50%;
            top: 50%;
            font-size: 0;
            color: #1d326d;
            display: inline-block;
            margin: -25px 0 0 -25px;
            width: 80px;
            height: 80px;
        }

        .loader img {
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body>
    <div class="loader-mask" id="loader-mask">
        <div class="loader">
            <img src="{{ asset('public/users/assets/FMDQ-loader.gif') }}" alt="Loading..." />
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            document.getElementById('loader-mask').style.display = 'none';
        });
    </script>

    <div class="full-page">
        <div class="content">
            @include('layouts.externalheadertag')



            @yield('content')


            @include('layouts.footer')
        </div>
    </div>
</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sessionTimeoutMinutes = parseInt(document.querySelector('meta[name="session-timeout-minutes"]')?.content || 30);
        const sessionTimeoutMs = sessionTimeoutMinutes * 60 * 1000;
        const warningTimeMs = 2 * 60 * 1000; // 2 minutes before expiry
        
        let timeoutWarning;
        let timeoutExpiry;
        
        function resetSessionTimer() {
            clearTimeout(timeoutWarning);
            clearTimeout(timeoutExpiry);
            
            // Set warning timer
            timeoutWarning = setTimeout(() => {
                Swal.fire({
                    icon: 'warning',
                    title: 'Session Warning',
                    text: 'Your session will expire in 2 minutes due to inactivity.',
                    confirmButtonColor: '#3c4d62'
                });
            }, sessionTimeoutMs - warningTimeMs);
            
            // Set expiry timer
            timeoutExpiry = setTimeout(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Session Expired',
                    text: 'Your session has expired. Please log in again.',
                    confirmButtonColor: '#3c4d62'
                }).then(() => {
                    window.location.href = '/login';
                });
            }, sessionTimeoutMs);
            
            // Keep session alive on server
            fetch('/keep-alive', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
        }
        
        // Track user activity
        const activityEvents = ['mousedown', 'keydown', 'scroll', 'touchstart'];
        activityEvents.forEach(event => {
            document.addEventListener(event, resetSessionTimer, true);
        });
        
        // Initialize timer
        resetSessionTimer();
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

</html>
