<style>
    .header ul li.active a, 
    .header ul li.active {
        color: #C79D51 !important;
        font-weight: 600;
    }
</style>
<header class="header-container">
    <div class="header">
        <a href="{{ url('/') }}"><img src="{{ asset('public/users/assets/FMDQ-logo.png') }}" alt="FMDQ Logo" /></a>
        <ul>
    <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
        <a href="{{ url('/') }}">Home</a>
    </li>

    @php
        $navbar = \App\Models\Category::where('status', 1)->where('display_on_menu', 1)->get();
        $currentSlug = request()->route('slug');
    @endphp

    @foreach ($navbar as $menu)
        @php
            $isActive = request()->is('category/' . $menu->slug . '*') || 
                        (request()->is('subCategory/*') && $menu->subcategories->contains('slug', $currentSlug)) ||
                        (request()->is('subCategory/ceased/*') && $menu->subcategories->contains('slug', $currentSlug)) ||
                        (request()->is('category/ceased/' . $menu->slug . '*'));
        @endphp
        <li class="nav-flex {{ $isActive ? 'active' : '' }}">
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

    <li class="{{ request()->routeIs('news') || request()->routeIs('newsalert') ? 'active' : '' }}">
        <a href="{{ route('newsalert') }}">News</a>
    </li>
  
    <li class="{{ request()->routeIs('feedback') ? 'active' : '' }}">
        <a href="{{ route('feedback') }}">Feedback</a>
    </li>

           
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
