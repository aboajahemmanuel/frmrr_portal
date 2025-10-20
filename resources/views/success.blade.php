<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Payment Success</title>
</head>

<body>
    <div>
        @if (Session::has('success'))
            <div class="modal fade" tabindex="-1" id="modalAlert">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        {{-- <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross"></em></a> --}}
                        <div class="modal-body modal-body-lg text-center">
                            <div class="nk-modal">
                                <em class="nk-modal-icon icon icon-circle icon-circle-xxl ni ni-check bg-success"></em>
                                <h4 class="nk-modal-title">Your payment was successful!</h4>
                                <div class="nk-modal-text">
                                    <div class="caption-text">
                                        Thank you for subscribing to our package.
                                    </div>
                                    {{-- <span class="sub-text-sm">Learn when you reciveve bitcoin in your wallet. <a
                                            href="#"> Click here</a></span> --}}
                                </div>
                                {{-- <div class="nk-modal-action">
                                    <a href="#" class="btn btn-lg btn-mw btn-primary" data-dismiss="modal">OK</a>
                                </div> --}}
                            </div>
                        </div><!-- .modal-body -->
                        {{-- <div class="modal-footer bg-lighter">
                            <div class="text-center w-100">
                                <p>Earn upto $25 for each friend your refer! <a href="#">Invite friends</a></p>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>



            @php
                $getPreviousUrl = Session::get('previous_url');
            @endphp

            <script type="text/javascript">
                $(document).ready(function() {
                    $('#modalAlert').modal('show');
                    setTimeout(function() {
                        window.location.href = '{{ $getPreviousUrl }}'; // Redirect after 5 seconds
                    }, 5000);
                });
            </script>

            @php
                Session::forget('success');
            @endphp
        @endif
    </div>
    <script src="{{ asset('public/admin/js/bundle.js') }}"></script>
    <script src="{{ asset('public/admin/js/scripts.js') }}"></script>
    <script src="{{ asset('public/admin/js/charts/chart-ecommerce.js') }}"></script>
    <script src="{{ asset('public/admin/js/libs/datatable-btns.js') }}"></script>
</body>

</html>
