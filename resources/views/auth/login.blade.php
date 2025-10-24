@extends('layouts.auth')

@section('content')
    <div class="onb-bg">
        <div class="login-card">
            <div class="logo-container">
                <img src="{{ asset('public/users/assets/FMDQ-Logo.png') }}" alt="FMDQlogo" />
            </div>
            <div class="app-name">
                <p>FINANCIAL MARKETS RULES REPOSITOR<p>go lang
            </div>
            <div class="tabs">
                <div class="current">
                    <a href="{{ route('login') }}">
                        <p class="current-active">Log in</p>
                    </a>
                    <a href="{{ route('register') }}">
                        <p class="current-inactive">Sign up</p>
                    </a>
                </div>
                <div class="active-line">
                    <div class="line-active"></div>
                    <div class="line-inactive"></div>
                </div>
            </div>
            <form method="POST" id="groupForm" action="{{ route('login.post') }}">
                @csrf
                <div class="form__group field">
                    <input type="email" class="form__field" placeholder="Email Address" name="email" id="email"
                        required />
                    <label for="name" class="form__label">Email Address <span class="starrr" style="color: red">*</span></label>
                </div>
                <div class="form__group field">
                    <input type="password" class="form__field" placeholder="Password" name="password" id="password"
                        required />
                    <label for="name" class="form__label">Password <span class="starrr" style="color: red">*</span></label>
                </div>
                <div class="full">
                    <a href="{{ route('password.request') }}">
                        <div class="link-txt">Forgot Password?</div>
                    </a>
                </div>

                <div class="auth-buttons-container" style="">
                    <button class="btn btn-lg btn-primary btn-block auth-buttons" id="addSubmitBtn" type="submit"
                        style="">
                        <i class="fas fa-spinner fa-spin" style="display:none;"></i>
                        <span class="btn-text">Log in</span>
                    </button>
                </div>

            </form>
        </div>
    @endsection
