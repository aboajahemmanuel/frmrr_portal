@extends('layouts.auth')

@section('content')
    <div class="onb-bg">
        <div class="login-card">
            <div class="logo-container">
                <img src="{{ asset('public/users/assets/FMDQ-Logo.png') }}" alt="FMDQlogo" />
            </div>
            <div class="app-name-padding">
                <p>Recover your Password</p>
            </div>
            <p class="p-policy-center">
                Password must have at least 8 characters, one uppercase, one lowercase, one number and one special
                    character.
            </p>

            <form method="POST" id="groupForm" action="{{ route('password_update') }}">
                @csrf
                {{-- <input type="hidden" name="token" value="{{ $token }}"> --}}
                <input type="hidden" name="email" value="{{ $email }}">
                <div class="form__group field">
                    <input type="password" class="form__field" placeholder="New Password" name="password" id="password"
                        required />
                    <label for="name" class="form__label">New Password</label>
                </div>
                <div class="form__group field">
                    <input class="form__field" placeholder="Confirm Password" type="password" name="password_confirmation"
                        id="new-password" required />
                    <label for="name" class="form__label">Confirm Password</label>
                </div>


                <div class="auth-buttons-container">
                    <button class="btn btn-lg btn-primary btn-block auth-buttons" id="addSubmitBtn" type="submit">
                        <i class="fas fa-spinner fa-spin" style="display:none;"></i>
                        <span class="btn-text">Reset Password</span>
                    </button>
                </div>

                {{-- <button href="new-pass.html">
                    <div class="button-container m-60">
                        <div class="gradient-buttons">
                            <div class="gradient-button-content">
                                <div>Reset Password</div>
                                <img src="{{ asset('public/users/assets//Arrow - Right.svg') }}" alt="Right Arrow" />
                            </div>
                        </div>
                    </div>
                </button> --}}


        </div>
    @endsection
