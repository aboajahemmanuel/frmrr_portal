@extends('layouts.externalprofile')

@section('page_title', 'Manage Team')

@section('content')
<link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />

<style>
    .team-page-wrapper {
        background-color: #f4f6fa;
        min-height: calc(100vh - 320px);
        padding: 30px 20px 80px;
        font-family: 'Roboto', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .team-container {
        max-width: 860px;
        margin: 0 auto;
    }

    .team-back-nav {
        margin-bottom: 20px;
    }

    .btn-back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #0c2b70;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none !important;
        padding: 8px 14px;
        border-radius: 8px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .btn-back-link:hover {
        background: #0c2b70;
        color: #ffffff !important;
        border-color: #0c2b70;
        transform: translateX(-3px);
    }

    .team-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 36px 32px;
        box-shadow: 0 10px 30px -5px rgba(12, 43, 112, 0.07), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
    }

    .team-header {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        padding-bottom: 24px;
        border-bottom: 1px solid #eef2f6;
        margin-bottom: 24px;
    }

    .team-header-icon {
        width: 58px;
        height: 58px;
        border-radius: 16px;
        background: linear-gradient(135deg, #0c2b70 0%, #1a3a8f 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        flex-shrink: 0;
        box-shadow: 0 8px 18px rgba(12, 43, 112, 0.2);
        border: 2px solid rgba(199, 157, 81, 0.4);
    }

    .team-header-content {
        flex: 1;
    }

    .team-header-content h2 {
        color: #0c2b70;
        font-size: 22px;
        font-weight: 800;
        margin: 0 0 6px;
        letter-spacing: -0.2px;
    }

    .team-plan-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fef9ed;
        color: #92400e;
        border: 1px solid #fde68a;
        font-size: 12px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        margin-bottom: 8px;
    }

    .team-header-content p.subtitle {
        color: #64748b;
        font-size: 13.5px;
        margin: 0;
        line-height: 1.5;
    }

    /* Seat Meter Card */
    .seat-meter-box {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 28px;
    }

    .seat-meter-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .seat-meter-label {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .seat-count-text {
        font-size: 14px;
        font-weight: 700;
        color: #0c2b70;
    }

    .seat-progress-track {
        background: #e2e8f0;
        height: 10px;
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 10px;
    }

    .seat-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #0c2b70 0%, #1a3a8f 60%, #c79d51 100%);
        border-radius: 20px;
        transition: width 0.5s ease;
    }

    .seat-meter-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12.5px;
        color: #64748b;
    }

    .badge-seats-available {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #ecfdf5;
        color: #059669;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 12px;
        border: 1px solid #a7f3d0;
    }

    .badge-seats-full {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #fffbeb;
        color: #d97706;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 12px;
        border: 1px solid #fde68a;
    }

    /* Team Members Table */
    .table-container {
        border: 1px solid #edf2f7;
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .team-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
        background: #ffffff;
    }

    .team-table th {
        background: #f8fafc;
        color: #475569;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 18px;
        border-bottom: 1px solid #e2e8f0;
    }

    .team-table td {
        padding: 16px 18px;
        vertical-align: middle;
        font-size: 13.5px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
    }

    .team-table tbody tr:last-child td {
        border-bottom: none;
    }

    .team-table tbody tr:hover td {
        background-color: #fafbfc;
    }

    /* Member Name & Avatar */
    .member-info-col {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .member-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #eff6ff;
        color: #1a3a8f;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        flex-shrink: 0;
        border: 1px solid #dbeafe;
    }

    .member-avatar.owner-avatar {
        background: linear-gradient(135deg, #0c2b70 0%, #1a3a8f 100%);
        color: #ffffff;
        border: 1.5px solid #c79d51;
    }

    .member-name {
        font-weight: 700;
        color: #0f172a;
    }

    .badge-you {
        background: #e2e8f0;
        color: #475569;
        font-size: 11px;
        font-weight: 600;
        padding: 2px 7px;
        border-radius: 10px;
        margin-left: 6px;
    }

    /* Status Badges */
    .badge-role {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-owner {
        background: #fef9ed;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .badge-active-member {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }

    .dot-active {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #10b981;
    }

    /* Remove Button */
    .btn-remove-member {
        width: auto !important;
        background: transparent !important;
        border: 1px solid #fecaca !important;
        color: #ef4444 !important;
        font-weight: 600 !important;
        font-size: 12px !important;
        padding: 6px 12px !important;
        border-radius: 8px !important;
        cursor: pointer !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
        transition: all 0.2s ease !important;
    }

    .btn-remove-member:hover {
        background: #ef4444 !important;
        color: #ffffff !important;
        border-color: #ef4444 !important;
    }

    /* Add Member Section */
    .add-member-section {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
    }

    .add-member-title {
        font-size: 15px;
        font-weight: 700;
        color: #0c2b70;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .add-member-desc {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 16px;
    }

    .invite-form-wrapper {
        display: flex;
        gap: 12px;
    }

    @media (max-width: 576px) {
        .invite-form-wrapper {
            flex-direction: column;
        }
        .invite-form-wrapper button {
            width: 100% !important;
        }
    }

    .input-with-icon {
        position: relative;
        flex: 1;
    }

    .input-leading-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 18px;
        pointer-events: none;
    }

    .input-with-icon input[type="email"] {
        width: 100%;
        padding: 12px 14px 12px 42px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 14px;
        outline: none;
        transition: all 0.2s ease;
        background: #ffffff;
    }

    .input-with-icon input[type="email"]:focus {
        border-color: #0c2b70;
        box-shadow: 0 0 0 3px rgba(12, 43, 112, 0.12);
    }

    .btn-add-member {
        width: auto !important;
        background: #0c2b70 !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 10px !important;
        padding: 12px 24px !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        cursor: pointer !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        white-space: nowrap !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 4px 12px rgba(12, 43, 112, 0.2) !important;
        flex-shrink: 0 !important;
    }

    .btn-add-member:hover {
        background: #1a3a8f !important;
        color: #ffffff !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 16px rgba(12, 43, 112, 0.28) !important;
    }

    /* Helper Notice */
    .helper-notice {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        background: #f0f7ff;
        border: 1px solid #d0e1fd;
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 12.5px;
        color: #1e40af;
        margin-top: 14px;
        line-height: 1.5;
    }

    .helper-notice em {
        font-size: 16px;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .seats-full-banner {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #92400e;
        font-size: 13.5px;
    }

    .seats-full-banner em {
        font-size: 20px;
        color: #d97706;
    }
</style>

@php
    $ownerName = Auth::user()->name ?? 'Owner';
    $ownerParts = array_filter(explode(' ', trim($ownerName)));
    if (count($ownerParts) >= 2) {
        $ownerInitials = strtoupper(substr($ownerParts[0], 0, 1) . substr(end($ownerParts), 0, 1));
    } else {
        $ownerInitials = strtoupper(substr($ownerName, 0, 2));
    }

    $seatPercentage = $seatLimit > 0 ? min(100, round(($seatsUsed / $seatLimit) * 100)) : 0;
    $seatsRemaining = max(0, $seatLimit - $seatsUsed);
@endphp

<div class="team-page-wrapper">
    <div class="team-container">
        <!-- Back Link Navigation -->
        <div class="team-back-nav">
            <a href="{{ route('profile') }}" class="btn-back-link">
                <em class="icon ni ni-arrow-left"></em> Back to Profile
            </a>
        </div>

        <div class="team-card">
            <!-- Header -->
            <div class="team-header">
                <div class="team-header-icon">
                    <em class="icon ni ni-users"></em>
                </div>
                <div class="team-header-content">
                    <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 8px;">
                        <h2>Manage Team</h2>
                        <span class="team-plan-tag">
                            <em class="icon ni ni-shield-check"></em> {{ $subscription->subscriptionPlan->name }}
                        </span>
                    </div>
                    <p class="subtitle">
                        Add up to {{ $seatLimit - 1 }} additional team members to share this subscription and collaborate on FMRR Portal regulations.
                    </p>
                </div>
            </div>

            <!-- Flash Alerts -->
            @if (\Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px; margin-bottom: 20px;">
                    <em class="icon ni ni-check-circle mr-1"></em> {{ \Session::get('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="width: auto !important; background: transparent; border: none; font-size: 20px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (\Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px; margin-bottom: 20px;">
                    <em class="icon ni ni-alert-circle mr-1"></em> {{ \Session::get('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="width: auto !important; background: transparent; border: none; font-size: 20px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px; margin-bottom: 20px;">
                    <div class="d-flex align-items-center mb-1">
                        <em class="icon ni ni-cross-circle mr-1"></em> <strong>Please correct the following errors:</strong>
                    </div>
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="width: auto !important; background: transparent; border: none; font-size: 20px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Visual Seat Meter -->
            <div class="seat-meter-box">
                <div class="seat-meter-top">
                    <span class="seat-meter-label">
                        <em class="icon ni ni-pie"></em> Seat Allocation
                    </span>
                    <span class="seat-count-text">
                        {{ $seatsUsed }} of {{ $seatLimit }} Seats Used
                    </span>
                </div>

                <div class="seat-progress-track">
                    <div class="seat-progress-fill" style="width: {{ $seatPercentage }}%;"></div>
                </div>

                <div class="seat-meter-footer">
                    <span>{{ $seatPercentage }}% capacity utilized</span>
                    @if ($seatsRemaining > 0)
                        <span class="badge-seats-available">
                            <em class="icon ni ni-check"></em> {{ $seatsRemaining }} {{ $seatsRemaining === 1 ? 'seat' : 'seats' }} available
                        </span>
                    @else
                        <span class="badge-seats-full">
                            <em class="icon ni ni-alert-circle"></em> All seats allocated
                        </span>
                    @endif
                </div>
            </div>

            <!-- Members Table -->
            <div class="table-container">
                <table class="team-table">
                    <thead>
                        <tr>
                            <th>Team Member</th>
                            <th>Email Address</th>
                            <th>Role & Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Subscription Owner -->
                        <tr>
                            <td>
                                <div class="member-info-col">
                                    <div class="member-avatar owner-avatar" title="Account Owner">
                                        {{ $ownerInitials }}
                                    </div>
                                    <div>
                                        <span class="member-name">{{ Auth::user()->name }}</span>
                                        <span class="badge-you">You</span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted">{{ Auth::user()->email }}</td>
                            <td>
                                <span class="badge-role badge-owner">
                                    <em class="icon ni ni-crown"></em> Owner
                                </span>
                            </td>
                            <td class="text-right text-muted" style="font-size: 12px;">
                                Primary Account
                            </td>
                        </tr>

                        <!-- Team Members -->
                        @foreach ($members as $member)
                            @php
                                $mName = optional($member->member)->name ?? 'Member';
                                $mParts = array_filter(explode(' ', trim($mName)));
                                $mInitials = count($mParts) >= 2 
                                    ? strtoupper(substr($mParts[0], 0, 1) . substr(end($mParts), 0, 1))
                                    : strtoupper(substr($mName, 0, 2));
                            @endphp
                            <tr>
                                <td>
                                    <div class="member-info-col">
                                        <div class="member-avatar" title="{{ $mName }}">
                                            {{ $mInitials }}
                                        </div>
                                        <div>
                                            <span class="member-name">{{ $mName }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $member->email }}</td>
                                <td>
                                    <span class="badge-role badge-active-member">
                                        <span class="dot-active"></span> Active
                                    </span>
                                </td>
                                <td class="text-right">
                                    <form method="POST" action="{{ route('institutionalMember.remove', $member->id) }}" onsubmit="return confirm('Remove {{ $member->email }} from your team?');">
                                        @csrf
                                        <button type="submit" class="btn-remove-member">
                                            <em class="icon ni ni-trash"></em> Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Add Member Section -->
            @if ($seatsUsed < $seatLimit)
                <div class="add-member-section">
                    <div class="add-member-title">
                        <em class="icon ni ni-user-add"></em> Add New Member
                    </div>
                    <p class="add-member-desc">
                        Invite a colleague by entering their registered email address. They will immediately receive access to this institutional subscription.
                    </p>

                    <form method="POST" action="{{ route('institutionalMember.invite') }}">
                        @csrf
                        <div class="invite-form-wrapper">
                            <div class="input-with-icon">
                                <em class="icon ni ni-mail input-leading-icon"></em>
                                <input type="email" name="email" placeholder="colleague@organization.com" required value="{{ old('email') }}">
                            </div>
                            <button type="submit" class="btn-add-member">
                                <em class="icon ni ni-plus"></em> Add Member
                            </button>
                        </div>
                    </form>

                    <div class="helper-notice">
                        <em class="icon ni ni-info"></em>
                        <div>
                            <strong>Account Requirement:</strong> The team member must already have a registered FMRR Portal account. If they don't have one yet, ask them to create a free account first, then add their registered email here.
                        </div>
                    </div>
                </div>
            @else
                <div class="seats-full-banner">
                    <em class="icon ni ni-info-fill"></em>
                    <div>
                        <strong>All seats are currently allocated.</strong> To add more team members, remove an existing member above or contact FMDQ support to upgrade your subscription capacity.
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
