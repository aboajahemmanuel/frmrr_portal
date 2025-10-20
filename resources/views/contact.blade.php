@extends('layouts.externalex')

@section('content')
    <section class="hd-main-container">
        <div class="hd-container">
            <div class="profile-left-side">
                <div class="profile-left-side-container">
                    <p class="profile-left-side-header">Contact Information</p>
                    <div class="profile-horizontal-line"></div>
                    <div class="profile-profile-details">
                        <div class="profile-title">Email</div>
                        <div class="profile-info">mbg@fmdqgroup.com</div>
                    </div>
                    <div class="profile-horizontal-line"></div>
                    <div class="profile-profile-details">
                        <div class="profile-title">Phone</div>
                        <div class="profile-info">+234-919-291-9219</div>
                    </div>
                    <div class="profile-horizontal-line"></div>
                    <div class="profile-profile-details">
                        <div class="profile-title">Address</div>
                        <div class="profile-info">
                           Exchange Place, 35 Idowu Taylor Street, Victoria Island, Lagos, Nigeria
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-70">
                <div class="git">Get in Touch</div>
                <div class="git-desc">
                    Please use the form below to report an issue, make a suggestion
                    or ask a question
                </div>
                <div class="hd-form">
                    <form method="POST" action="{{ route('contactpost') }}">
                        @csrf
                        <div class="hd-input-flex">
                            <div class="form__group field">
                                <input type="text" class="form__field  @error('name') is-invalid @enderror"
                                    placeholder="Email Address" name="fname" id="fname" required
                                    value="{{ old('fname') }}" />
                                <label for="fname" class="form__label">First Name</label>
                            </div>
                            <div class="form__group field">
                                <input type="text" class="form__field @error('name') is-invalid @enderror"
                                    placeholder="Email Address" name="lname" id="lname" required
                                    value="{{ old('lname') }}" />
                                <label for="lname" class="form__label">Last Name</label>
                            </div>
                        </div>
                        <div class="hd-input-flex">
                            <div class="form__group field">
                                <input type="email" class="form__field @error('name') is-invalid @enderror"
                                    placeholder="Email Address" name="email" id="email" required
                                    value="{{ old('email') }}" />
                                <label for="fname" class="form__label">Email</label>
                            </div>
                            <div class="form__group field">
                                <input type="text" class="form__field  @error('name') is-invalid @enderror"
                                    placeholder="Email Address" name="subject" id="subject" required
                                    value="{{ old('subject') }}" />
                                <label for="subject" class="form__label">Subject</label>
                            </div>
                        </div>
                        <div class="form__group field">
                            <input type="text" class="form__field  @error('message') is-invalid @enderror"
                                placeholder="Email Address" name="feedback" id="message" required
                                value="{{ old('message') }}" />
                            <label for="message" class="form__label">Message</label>
                        </div>
                        <div class="w-full">

                            <button type="submit">
                                <div class="button-container">
                                    <div class="gradient-buttons">
                                        <div class="gradient-button-content">
                                            <div>Submit</div>
                                            <img src="{{ asset('public/users/assets/Arrow - Right.svg') }}"
                                                alt="Right Arrow" />
                                        </div>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    </div>
@endsection
</div>
</body>

</html>
