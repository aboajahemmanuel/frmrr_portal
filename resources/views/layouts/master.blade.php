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
    <!-- Select2 CSS -->
    <link href="{{ asset('public/assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    
    <!-- Custom Select2 CSS to match existing design -->
    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
            border: 1px solid #e5e9f2 !important;
            border-radius: 4px !important;
        }
        
        .select2-container .select2-selection--single .select2-selection__rendered {
            line-height: 36px !important;
            padding-left: 12px !important;
            color: #333 !important;
        }
        
        .select2-container .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #6576ff transparent transparent transparent !important;
        }
        
        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #6576ff transparent !important;
        }
        
        .select2-dropdown {
            border: 1px solid #e5e9f2 !important;
            border-radius: 4px !important;
        }
        
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #6576ff !important;
        }
        
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #f5f6fa !important;
        }
    </style>
    
    <!-- jQuery (needed for select2) -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    
    <!-- Select2 JS -->
    <script src="{{ asset('public/assets/libs/select2/js/select2.min.js') }}"></script>
    
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
</body>

</html>