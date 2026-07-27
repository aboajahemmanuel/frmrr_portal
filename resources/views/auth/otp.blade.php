@extends('layouts.auth')

@section('content')
    <div class="onb-bg">
        <div class="login-card">
            <div class="logo-container">
                <img src="{{ asset('public/users/assets/FMDQ-Logo.png') }}" alt="FMDQlogo" />
            </div>
            <div class="app-name">
                              <p>Financial Markets Rules & Regulations Portal <p>

            </div>
            <div class="tabs">
                <div class="current">
                    <p class="current-active">Two-Factor Authentication</p>
                </div>
                <div class="active-line">
                    <div class="line-active"></div>
                    <div class="line-inactive"></div>
                </div>
            </div>
            
            <div style="margin-bottom: 20px; text-align: center; color: #555;">
                <p>Please enter the OTP sent to your email address to complete the login process.</p>
            </div>

            @if (session('success'))
                <div style="color: green; text-align: center; margin-bottom: 15px; font-weight: bold;">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div style="color: red; text-align: center; margin-bottom: 15px; font-weight: bold;">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" id="groupForm" action="{{ route('otp.verify.submit') }}">
                @csrf
                <div class="form__group field">
                    <input type="text" class="form__field" placeholder="Enter OTP" name="otp" id="otp"
                        required autofocus />
                    <label for="otp" class="form__label">OTP <span class="starrr" style="color: red">*</span></label>
                </div>

                <div class="full" style="text-align: right; margin-top: 10px;">
                    <a href="{{ route('otp.resend') }}">
                        <div class="link-txt" style="color: #007bff; text-decoration: none;">Resend OTP?</div>
                    </a>
                </div>

                <div class="auth-buttons-container" style="margin-top: 30px;">
                    <button class="btn btn-lg btn-primary btn-block auth-buttons" id="addSubmitBtn" type="submit">
                        <i class="fas fa-spinner fa-spin" style="display:none;"></i>
                        <span class="btn-text">Verify and Login</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
