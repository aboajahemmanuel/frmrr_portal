<header class="header-container">
    <div class="header">
        <a href="{{ url('/') }}"><img src="{{ asset('public/users/assets/FMDQ-logo.png') }}" alt="FMDQ Logo" /></a>
        <ul>
            <a href="{{ url('/') }}">
                <li class="">Home</li>
            </a>
            @php
                $navbar = \App\Models\Category::where('status', 1)->where('display_on_menu', 1)->get();

            @endphp

        


            @foreach ($navbar as $menu)
                <li class="nav-flex">
                    <a href="{{ route('categorypages', $menu->slug) }}">{{ $menu->name }} </a>

                    @if ($menu->subcategories->where('status', 1)->isNotEmpty())
                        <img src="{{ asset('public/users/assets/Arrow - Down.svg') }}" alt="arrow down" />
                        <div class="dropdown-card">
                            @foreach ($menu->subcategories->where('status', 1) as $subcategory)
                                <a href="{{ url('/subCategory/' . $subcategory->slug) }}">
                                    <div class="dc-items">
                                        <div class="dc-items-title">{{ $subcategory->name }}</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </li>
            @endforeach






            <a href="{{ route('newsalert') }}">
                <li>News</li>
            </a>
            <a href="{{ url('/search') }}">
                <li>Search</li>
            </a>
            <a href="{{ route('feedback') }}">
                <li>Feedback </li>
            </a>

            {{-- <a href="{{ url('/category/rules-regulations') }}">
                <li>Rules and Regulations</li>
            </a>
            <a href="{{ url('/search') }}">
                <li>Search</li>
            </a> --}}
        </ul>
        @if (Auth::check())
            @if (Auth::user()->usertype == 'internal')
                <a href="{{ route('dashboard') }}">
                    <div class="button-container">
                        <div class="gradient-buttons">
                            <div class="gradient-button-content">
                                <div>Dashboard</div>

                            </div>
                        </div>
                    </div>
                </a>
            @else
                <a href="{{ route('profile') }}">
                    <div class="button-container">
                        <div class="gradient-buttons">
                            <div class="gradient-button-content">
                                <div>Profile</div>
                                <img src="{{ asset('public/users/assets/Arrow - Right.svg') }}" alt="Right Arrow" />
                            </div>
                        </div>
                    </div>
                </a>
            @endif
        @else
            <a href="{{ route('login') }}">
                <div class="button-container">
                    <div class="gradient-buttons">
                        <div class="gradient-button-content">
                            <div>Log in</div>
                            <img src="{{ asset('public/users/assets/Arrow - Right.svg') }}" alt="Right Arrow" />
                        </div>
                    </div>
                </div>
            </a>
        @endif

    </div>
</header>
