<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{ asset('public/admin/images/fmdq_favicon.png') }}">
    <title>Financial Markets Regulations & Rules Repository Portal</title>
    <!-- Session timeout configuration -->
    <meta name="session-timeout-minutes" content="{{ \App\Models\SessionSetting::getCurrentTimeout() }}">
    <link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/admin/css/theme.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/admin/css/libs/fontawesome-icons.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/admin/css/libs/themify-icons.css') }}" rel="stylesheet" type="text/css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">


</head>

<body class="nk-body bg-lighter npc-default has-sidebar ">
    <div class="nk-app-root">

        <div class="nk-main ">
            @include('layouts.sidebar')
            @include('layouts.header')


            @yield('content')

            @include('layouts.footer')



            <script src="{{ asset('public/admin/js/bundle.js') }}"></script>
            <script src="{{ asset('public/admin/js/scripts.js') }}"></script>
            <script src="{{ asset('public/admin/js/charts/chart-ecommerce.js') }}"></script>
            <script src="{{ asset('public/admin/js/libs/datatable-btns.js') }}"></script>




            <link rel="stylesheet" href="{{ asset('public/admin/css/editors/summernote.css') }}">
            <script src="{{ asset('public/admin/js/libs/editors/summernote.js') }}"></script>
            <script src="{{ asset('public/admin/js/editors.js') }}"></script>

      @include('layouts.sessioncheck')

            <script>
                function loading() {
                    $(".btn .fa-spinner").show();
                    $(".btn .btn-text").html("Processing...");
                }

                document.getElementById('subcategoryForm').addEventListener('submit', function(event) {
                    if (this.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    } else {
                        loading();
                        document.getElementById('submitBtn').disabled = true;
                    }
                    this.classList.add('was-validated');
                }, false);
            </script>

            <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</body>

</html>