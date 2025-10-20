@extends('layouts.headerexternal')

@section('content')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />
    <div class="info">

        <div class="title">Search</div>


    </div>
    </div>

    </section>
    <section style="background: #e8eaf0 !important;" class="gd-main-container">
        <div class="hd-container">
            <div class="gl-flex">
                <div class="tabs">




                </div>


            </div>
        </div>




        <div class="gda-cards-container">
            @include('search.searchTbale')




            <div class="">

                <div class="adv-search-empty">
                    <br>
                    <br>
                    <br>
                    <img src="{{ asset('public/users/assets/illustration-search.svg') }}" alt="search illustration"
                        height="280px">
                    <div class="no-doc" style="text-align: center">No Results to Show</div>
                    <div class="get-in" style="text-align: center">
                        Enter a query to see results here
                    </div>
                </div>
            </div>


        </div>
    </section>

    </div>
@endsection
</div>
</body>

</html>
