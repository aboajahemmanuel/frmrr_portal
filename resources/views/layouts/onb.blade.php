<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('public/users/style.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    {{-- <link rel="stylesheet" href="style.css" /> --}}
    <title>Private Companies’ Securities Information and Distribution Portal (PCS)</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@100..900&display=swap" rel="stylesheet">


</head>

<body>
    <div class="whole">
        <div class="main">
            @yield('content')



        </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function validatePhoneNumber(input) {
        // Remove any non-numeric characters and limit to 11 digits
        input.value = input.value.replace(/\D/g, '').slice(0, 11);
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInputs = document.querySelectorAll('.custom-file-input'); // Select all file input elements
        const maxFileSize = 5 * 1024 * 1024; // 5MB in bytes

        // Add change event listener to each file input
        fileInputs.forEach(function(fileInput) {
            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0]; // Get the selected file
                const fileType = file.type; // Get the file's MIME type

                if (file) {
                    if (file.size > maxFileSize) {
                        // Show SweetAlert if the file size exceeds 5MB
                        Swal.fire({
                            icon: 'error',
                            title: 'File Too Large',
                            text: `The file "${file.name}" exceeds the maximum allowed size of 5MB.`,
                        });
                        event.target.value = ""; // Clear the file input
                    } else if (fileType !== 'application/pdf') {
                        // Show SweetAlert if the file is not a PDF
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid File Type',
                            text: `The file "${file.name}" is not a PDF. Please upload only PDF files.`,
                        });
                        event.target.value = ""; // Clear the file input
                    }
                }
            });
        });

        // Handle the form submit event
        const form = document.getElementById('fileUploadForm');
        form.addEventListener('submit', function(event) {
            let isValid = true;

            fileInputs.forEach(function(fileInput) {
                const file = fileInput.files[0];
                const fileType = file ? file.type : '';

                if (file && (file.size > maxFileSize || fileType !== 'application/pdf')) {
                    isValid = false;
                }
            });

            if (!isValid) {
                event.preventDefault(); // Prevent form submission
                Swal.fire({
                    icon: 'error',
                    title: 'Submission Error',
                    text: 'One or more files are either too large or not in PDF format. Please select smaller PDF files.',
                });
            }
        });
    });


     function loading(buttonId) {
                $("#" + buttonId + " .fa-spinner").show();
                $("#" + buttonId + " .btn-text").html("Processing...");
            }

            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('submitForm').addEventListener('submit', function(event) {
                    if (this.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    } else {
                        loading('SubmitBtn');
                        document.getElementById('SubmitBtn').disabled = true;
                    }
                    this.classList.add('was-validated');
                }, false);
            });
</script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
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

</html>
