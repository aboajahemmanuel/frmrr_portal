@extends('layouts.externalex')

@section('content')
<style>
/* Modernized Feedback Form Styling */
.hd-form {
    border-radius: 16px !important;
    background-color: #f9f5ed !important;
    padding: 36px 36px 32px 36px !important;
    margin: 16px 0 !important;
    border: 1px solid rgba(29, 50, 109, 0.08);
    box-shadow: 0 4px 24px rgba(29, 50, 109, 0.04);
}

.git {
    font-family: straightBold, sans-serif !important;
    font-size: 26px !important;
    color: #1d326d !important;
    margin-bottom: 6px !important;
}

.git-desc {
    font-family: customRegular, sans-serif !important;
    font-size: 14px !important;
    color: #5a6a85 !important;
    line-height: 1.5 !important;
    margin-bottom: 12px !important;
}

.fb-form {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.fb-row {
    display: flex;
    gap: 24px;
    width: 100%;
}

.fb-col-50 {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
}

.fb-col-100 {
    width: 100%;
    display: flex;
    flex-direction: column;
}

.fb-label {
    display: flex;
    align-items: center;
    font-family: straightBold, sans-serif;
    font-size: 13px;
    color: #1d326d;
    margin-bottom: 6px;
    letter-spacing: 0.1px;
}

.fb-req {
    color: #e53e3e;
    margin-left: 3px;
    font-weight: bold;
}

.fb-opt {
    font-family: customRegular, sans-serif;
    font-size: 11.5px;
    color: #718096;
    margin-left: 6px;
    font-weight: normal;
}

.fb-input,
.fb-select,
.fb-textarea {
    width: 100%;
    background: transparent;
    border: 0;
    border-bottom: 2px solid #cbd5e1;
    outline: none;
    font-family: customRegular, sans-serif;
    font-size: 13.5px;
    color: #0f172a;
    padding: 8px 0;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    box-sizing: border-box;
}

.fb-input::placeholder,
.fb-textarea::placeholder {
    color: #94a3b8;
    font-family: customRegular, sans-serif;
    font-size: 13px;
}

.fb-input:hover,
.fb-select:hover,
.fb-textarea:hover {
    border-bottom-color: #94a3b8;
}

.fb-input:focus,
.fb-select:focus,
.fb-textarea:focus {
    border-bottom-color: #1d326d;
    box-shadow: 0 1px 0 0 #1d326d;
}

.fb-input.is-invalid,
.fb-select.is-invalid,
.fb-textarea.is-invalid {
    border-bottom-color: #e53e3e !important;
}

.fb-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%231d326d' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 4px center;
    background-size: 14px;
    padding-right: 24px;
    cursor: pointer;
}

.fb-select option {
    background-color: #ffffff;
    color: #0f172a;
    font-family: customRegular, sans-serif;
    padding: 10px;
}

.fb-textarea {
    resize: vertical;
    min-height: 85px;
    line-height: 1.55;
    padding-top: 6px;
}

.fb-error-msg {
    color: #dc2626;
    font-family: customRegular, sans-serif;
    font-size: 11.5px;
    margin-top: 4px;
    display: block;
}

.fb-submit-container {
    margin-top: 6px;
    display: flex;
    justify-content: flex-start;
}

.fb-submit-btn {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    outline: none;
    display: inline-block;
}

.fb-submit-btn .gradient-buttons {
    margin: 0 !important;
    padding: 1px;
    display: inline-block;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.fb-submit-btn:hover .gradient-buttons {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(199, 157, 81, 0.25);
}

.fb-submit-btn .gradient-button-content {
    padding: 12px 32px !important;
    display: flex;
    align-items: center;
    gap: 12px;
}

.fb-submit-btn .gradient-button-content div {
    font-family: customBold, sans-serif;
    font-size: 14px;
    letter-spacing: 0.3px;
}

@media (max-width: 768px) {
    .fb-row {
        flex-direction: column;
        gap: 20px;
    }
    .hd-form {
        padding: 24px 20px !important;
    }
}
</style>

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
                        <div class="profile-info">+2349113977152</div>
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

            @php
                $user = Auth::user();
                $nameParts = $user ? explode(' ', $user->name, 2) : [];
                $defaultFname = $user->fname ?? ($nameParts[0] ?? '');
                $defaultLname = $user->lname ?? ($nameParts[1] ?? '');
                $defaultEmail = $user->email ?? '';
                $defaultInstitution = $user->company_name ?? ($user->institution ?? '');
            @endphp

            <div class="w-70">
                <div class="git">Feedback</div>
                <div class="git-desc">
                    Please use the form below to report an issue, make a suggestion or ask a question.
                </div>
                <div class="hd-form">
                    <form method="POST" action="{{ route('contactpost') }}" class="fb-form" id="feedbackForm">
                        @csrf

                        {{-- Row 1: First Name & Last Name --}}
                        <div class="fb-row">
                            <div class="fb-col-50">
                                <label for="fname" class="fb-label">
                                    First Name <span class="fb-req">*</span>
                                </label>
                                <input type="text"
                                    class="fb-input @error('fname') is-invalid @enderror"
                                    placeholder="Enter your first name"
                                    name="fname"
                                    id="fname"
                                    required
                                    value="{{ old('fname', $defaultFname) }}" />
                                @error('fname')
                                    <span class="fb-error-msg">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="fb-col-50">
                                <label for="lname" class="fb-label">
                                    Last Name <span class="fb-req">*</span>
                                </label>
                                <input type="text"
                                    class="fb-input @error('lname') is-invalid @enderror"
                                    placeholder="Enter your last name"
                                    name="lname"
                                    id="lname"
                                    required
                                    value="{{ old('lname', $defaultLname) }}" />
                                @error('lname')
                                    <span class="fb-error-msg">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Row 2: Institution --}}
                        <div class="fb-col-100">
                            <label for="institution" class="fb-label">
                                Institution <span class="fb-req">*</span>
                            </label>
                            <input type="text"
                                class="fb-input @error('institution') is-invalid @enderror"
                                placeholder="Enter your institution or company name"
                                name="institution"
                                id="institution"
                                required
                                value="{{ old('institution', $defaultInstitution) }}" />
                            @error('institution')
                                <span class="fb-error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Row 3: Member Status --}}
                        <div class="fb-col-100">
                            <label for="member_status" class="fb-label">
                                Member or Non-Member of FMDQ Securities Exchange Limited <span class="fb-opt">(Optional)</span>
                            </label>
                            <select class="fb-select @error('member_status') is-invalid @enderror"
                                name="member_status"
                                id="member_status">
                                <option value="" {{ old('member_status') ? '' : 'selected' }}>-- Select Status (Optional) --</option>
                                <option value="Member" {{ old('member_status') == 'Member' ? 'selected' : '' }}>Member</option>
                                <option value="Non-Member" {{ old('member_status') == 'Non-Member' ? 'selected' : '' }}>Non-Member</option>
                            </select>
                            @error('member_status')
                                <span class="fb-error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Row 4: Email Address & Subject --}}
                        <div class="fb-row">
                            <div class="fb-col-50">
                                <label for="email" class="fb-label">
                                    Email Address <span class="fb-req">*</span>
                                </label>
                                <input type="email"
                                    class="fb-input @error('email') is-invalid @enderror"
                                    placeholder="e.g. name@company.com"
                                    name="email"
                                    id="email"
                                    required
                                    value="{{ old('email', $defaultEmail) }}" />
                                @error('email')
                                    <span class="fb-error-msg">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="fb-col-50">
                                <label for="subject" class="fb-label">
                                    Subject <span class="fb-req">*</span>
                                </label>
                                <input type="text"
                                    class="fb-input @error('subject') is-invalid @enderror"
                                    placeholder="Brief topic of your feedback"
                                    name="subject"
                                    id="subject"
                                    required
                                    value="{{ old('subject') }}" />
                                @error('subject')
                                    <span class="fb-error-msg">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Row 5: Message --}}
                        <div class="fb-col-100">
                            <label for="message" class="fb-label">
                                Message <span class="fb-req">*</span>
                            </label>
                            <textarea
                                class="fb-textarea @error('feedback') is-invalid @enderror"
                                placeholder="Please describe your issue, suggestion or question in detail..."
                                name="feedback"
                                id="message"
                                rows="4"
                                required>{{ old('feedback') }}</textarea>
                            @error('feedback')
                                <span class="fb-error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Row 6: Submit Button --}}
                        <div class="fb-submit-container">
                            <button type="submit" class="fb-submit-btn" id="fbSubmitBtn">
                                <div class="gradient-buttons">
                                    <div class="gradient-button-content">
                                        <div>Submit</div>
                                        <img src="{{ asset('public/users/assets/Arrow - Right.svg') }}" alt="Right Arrow" />
                                    </div>
                                </div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
