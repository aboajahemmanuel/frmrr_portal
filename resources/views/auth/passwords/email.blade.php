@extends('layouts.auth')

@section('content')
    <div class="onb-bg">
        <div class="login-card">
            <div class="logo-container">
                <img src="{{ asset('public/users/assets/FMDQ-Logo.png') }}" alt="FMDQlogo" />
            </div>
            <div class="app-name-padding">
                <p>Forgot Password?</p>
            </div>
            <p class="p-policy-center">
                Enter the email address you used to create the account and we will
                email you instructions to reset your password
            </p>
            <form method="POST" id="groupForm" action="{{ route('forgetpassword') }}">
                @csrf
                <div class="form__group field">
                    <input type="email" class="form__field" placeholder="Email Address" name="email" id="email"
                        required />
                    <label for="name" class="form__label">Enter Your Email <span style="color: red">*</span></label>
                </div>
                {{-- <button type="submit">
                    <div class="button-container m-60">
                        <div class="gradient-buttons">
                            <div class="gradient-button-content">
                                <div>Send Email</div>
                                <img src="{{ asset('public/users/assets//Arrow - Right.svg') }}" alt="Right Arrow" />
                            </div>
                        </div>
                    </div>
                </button> --}}

                  <div class="auth-buttons-container">
                    <button class="btn btn-lg btn-primary btn-block auth-buttons" id="addSubmitBtn" type="submit">
                        <i class="fas fa-spinner fa-spin" style="display:none;"></i>
                        <span class="btn-text">Send Email</span>
                    </button>
                </div>

                {{-- <button class="btn btn-lg btn-primary btn-block" id="addSubmitBtn" type="submit">
                    <i class="fas fa-spinner fa-spin" style="display:none;"></i>
                    <span class="btn-text">Send Email</span>
                </button> --}}
            </form>
            <p class="remember-pass">
                Remember Password?<a href="{{ route('login') }}"><span> Login</span></a>
            </p>
        </div>
    @endsection
