@extends('layouts.externalcategory')

@section('content')
    <section class="gd-main-container">
        <div class="hd-container">
            <div class="gl-flex">
                <div class="tabs">
                    <div class="current">
                        <a href="rules.html">
                            <p class="current-active">A-Z {{ $category->name }}</p>
                        </a>
                        {{-- <a href="rules-made.html">
                            <p class="current-inactive">As Made Rules and Regulations</p>
                        </a> --}}
                    </div>
                    <div class="active-line">
                        <div class="line-active"></div>
                        <div class="line-inactive"></div>
                    </div>
                </div>
                @if ($regulations_ceased->count() > 0)
                    <div class="saved" id="toggleButton">
                        Show Ceased <img src="{{ asset('public/users/assets/Eyes.svg') }}" alt="Saved" />
                    </div>
                    {{-- <button  class="btn btn-primary">Show Ceased</button> --}}
                @endif

            </div>
        </div>
        <div class="gda-cards-container">
            <div class="search-filters">
                <div class="sf-title-gd">Showing guidelines current and historical versions (including ceased
                    versions) with index information:</div>
                <div class="b-a">
                    By Alphabet:
                </div>
                <div class="alphabet-container">
                    @foreach ($alphas as $alpha)
                        <a href="{{ route('alphaname', ['slug' => $category->slug, 'name' => $alpha->name]) }}">
                            <div class="alphabet-card">{{ $alpha->name }}</div>

                        </a>
                    @endforeach

                </div>
                <div class="b-a">
                    By Year:
                </div>
                <div class="alphabet-container">
                    @foreach ($years as $year)
                        <a href="{{ route('yearname', ['slug' => $category->slug, 'yname' => $year->name]) }}">
                            <div class="alphabet-card">{{ $year->name }}</div>
                        </a>
                    @endforeach

                </div>
            </div>



            <div id="myElement1">
                <div class="adv-search">

                    @foreach ($regulations as $result)
                        <div class="card">
                            <div class="card__wrapper">
                                <div class="card__side is-active">
                                    <div class="pdf">
                                        <img src="{{ asset('public/users/assets/pdf.svg') }}" alt="pdf" />
                                        <div>{{ $result->category->name }}</div>
                                    </div>
                                    <div class="doc-title">
                                        {{ $result->formatted_title }}
                                    </div>

                                    <div class="price">
                                        Effective Date: <span>
                                            {{ $result->effective_date }}
                                        </span>
                                    </div>


                                    <div class="price">
                                        Issue Date: <span>
                                            {{ $result->issue_date }}
                                        </span>
                                    </div>


                                    <div class="price">
                                        Document Version: <span>
                                            {{ $result->document_version }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card__side card__side--back">
                                    <div class="back-buttons-container">
                                        @if ($isSubscribed)
                                            <a href="{{ route('download', $result->id) }}" target="_blank">
                                                <div class="back-buttons">
                                                    Download
                                                    <img src="{{ asset('public/users/assets/Arrow - Bottom - Gold.svg') }}"
                                                        alt="" />
                                                </div>
                                            </a>
                                        @else
                                            @if (Auth::check())
                                                <a href="{{ route('subscribe') }}">
                                                    <div class="back-buttons">
                                                        Subscribe to download
                                                        <img src="{{ asset('public/users/assets/Arrow - Bottom - Gold.svg') }}"
                                                            alt="" />
                                                    </div>
                                                </a>
                                            @else
                                                <a href="{{ route('login') }}">
                                                    <div class="back-buttons">
                                                        Subscribe to download
                                                        <img src="{{ asset('public/users/assets/Arrow - Bottom - Gold.svg') }}"
                                                            alt="" />
                                                    </div>
                                                </a>
                                            @endif
                                        @endif
                                        <div class="back-buttons">
                                            Select <img src="{{ asset('public/users/assets/Check - Gold.svg') }}"
                                                alt="" />
                                        </div>
                                        <div class="">
                                            <form action="{{ route('save-document', $result->id) }}" method="POST">
                                                @csrf
                                                <button style="border: none; outline: none;height: 25px;" type="submit">
                                                    <div class="back-buttons">
                                                        Save <img src="{{ asset('public/users/assets/Saved - Gold.svg') }}"
                                                            alt="" />
                                                    </div>
                                                </button>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

            <div id="myElement2" class="hidden">
                <div class="adv-search">

                    @foreach ($regulations_ceased as $result_ceased)
                        <div class="card">
                            <div class="card__wrapper">
                                <div class="card__side is-active">
                                    <div class="pdf">
                                        <img src="{{ asset('public/users/assets/pdf.svg') }}" alt="pdf" />
                                        <div>{{ $result_ceased->category->name }}</div>
                                    </div>
                                    <div class="doc-title">
                                        {{ $result_ceased->formatted_title }}
                                    </div>

                                    @if ($result_ceased->ceased == 1)
                                        <span class="badge bg-soft-danger" style="color: white">Ceased</span>
                                    @endif
                                    <div class="price">
                                        Effective Date: <span>
                                            {{ $result_ceased->effective_date }}
                                        </span>
                                    </div>


                                    <div class="price">
                                        Issue Date: <span>
                                            {{ $result_ceased->issue_date }}
                                        </span>
                                    </div>


                                    <div class="price">
                                        Document Version: <span>
                                            {{ $result_ceased->document_version }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card__side card__side--back">
                                    <div class="back-buttons-container">
                                        @if ($isSubscribed)
                                            <a href="{{ route('download', $result_ceased->id) }}" target="_blank">
                                                <div class="back-buttons">
                                                    Download
                                                    <img src="{{ asset('public/users/assets/Arrow - Bottom - Gold.svg') }}"
                                                        alt="" />
                                                </div>
                                            </a>
                                        @else
                                            @if (Auth::check())
                                                 <a href="{{ route('subscribe') }}">
                                                    <div class="back-buttons">
                                                        Subscribe to download
                                                        <img src="{{ asset('public/users/assets/Arrow - Bottom - Gold.svg') }}"
                                                            alt="" />
                                                    </div>
                                                </a>
                                            @else
                                                <a href="{{ route('login') }}">
                                                    <div class="back-buttons">
                                                        Subscribe to download
                                                        <img src="{{ asset('public/users/assets/Arrow - Bottom - Gold.svg') }}"
                                                            alt="" />
                                                    </div>
                                                </a>
                                            @endif
                                        @endif
                                        <div class="back-buttons">
                                            Select <img src="{{ asset('public/users/assets/Check - Gold.svg') }}"
                                                alt="" />
                                        </div>
                                        <div class="">
                                            <form action="{{ route('save-document', $result_ceased->id) }}" method="POST">
                                                @csrf
                                                <button style="border: none; outline: none;height: 25px;" type="submit">
                                                    <div class="back-buttons">
                                                        Save <img
                                                            src="{{ asset('public/users/assets/Saved - Gold.svg') }}"
                                                            alt="" />
                                                    </div>
                                                </button>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </section>
    </div>






    <style>
        .hidden {
            display: none;
        }
    </style>



    <script>
        document.addEventListener('DOMContentLoaded', (event) => {

            const myElement1 = document.getElementById('myElement1');
            const myElement2 = document.getElementById('myElement2');
            const toggleButton = document.getElementById('toggleButton');

            toggleButton.addEventListener('click', function() {
                // Toggle the 'hidden' class for both elements
                myElement1.classList.toggle('hidden');
                myElement2.classList.toggle('hidden');

                // Update the button text based on the visibility of myElement1
                if (myElement1.classList.contains('hidden')) {
                    toggleButton.textContent = 'Hide Ceased'; // Show this if the first content is hidden
                } else {
                    toggleButton.textContent =
                        'Show Ceased'; // Show this if the second content is hidden
                }
            });
        });
    </script>
    
@endsection
</div>
</body>

</html>
