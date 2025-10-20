@extends('layouts.auth')

@section('content')
    <div class="onb-bg">
        <div class="login-card">
            <div class="logo-container">
                <img src="{{ asset('public/users/assets/FMDQ-Logo.png') }}" alt="FMDQlogo" />
            </div>
            <div class="app-name">
                <p>FINANCIAL MARKETS RULES REPOSITORY</p>
            </div>
            <div class="tabs">
                <div class="current">
                    <a href="{{ route('login') }}">
                        <p class="current-inactive">Log in</p>
                    </a>
                    <a href="{{ route('register') }}">
                        <p class="current-active">Sign up</p>
                    </a>
                </div>
                <div class="active-line">
                    <div class="line-inactive"></div>
                    <div class="line-active"></div>
                </div>
            </div>

            <form method="POST" id="groupForm" action="{{ route('register.post') }}">
                @csrf
                <div class="form__group field">
                    <input type="text" class="form__field" placeholder="First Name" name="fname" id="name"
                        value="{{ old('name') }}" required />
                    <label for="name" class="form__label">First Name <span class="starrr" style="color: red">*</span></label>
                </div>


                  <div class="form__group field">
                    <input type="text" class="form__field" placeholder="Last Name" name="lname" id="name"
                        value="{{ old('name') }}" required />
                    <label for="name" class="form__label">Last Name <span class="starrr" style="color: red">*</span></label>
                </div>
                <div class="form__group field">
                    <input type="email" class="form__field" placeholder="Email Address" name="email" id="email"
                        value="{{ old('email') }}" required />
                    <label for="name" class="form__label">Email Address <span class="starrr" style="color: red">*</span></label>
                </div>
                <div class="form__group field">
                    <input type="number" class="form__field" placeholder="Phone Number" name="phone" id="phone"
                        value="{{ old('phone') }}" required />
                    <label for="phone" class="form__label">Phone Number <span class="starrr" style="color: red">*</span></label>
                </div>


                <div class="form__group field">
                    <input type="text" class="form__field" placeholder="Company Name" name="company_name" id="phone"
                        value="{{ old('phone') }}" />
                    <label for="company_name " class="form__label">Company Name </label>
                </div>



                <div class="form__group field">
                    <input type="password" class="form__field" placeholder="Password" name="password" id="password"
                        required />
                    <label for="name" class="form__label">Choose Password <span class="starrr" style="color: red">*</span></label>
                </div>
                <p class="p-policy">Password must have at least 8 characters, one uppercase, one lowercase, one number and
                    one special
                    character.</p>

                <div class="auth-buttons-container">
                    <button class="btn btn-lg btn-primary btn-block auth-buttons" id="addSubmitBtn" type="submit">
                        <i class="fas fa-spinner fa-spin" style="display:none;"></i>
                        <span class="btn-text">Sign Up</span>
                    </button>
                </div>

            </form>
        </div>
    @endsection
