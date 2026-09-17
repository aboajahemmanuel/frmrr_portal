@extends('layouts.externalprofile')

@section('content')
    <link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha384-R334r6kryDNB/GWs2kfB6blAOyWPCxjdHSww/mo7fel+o5TM/AOobJ0QpGRXSDh4" crossorigin="anonymous">

    <style>
        /* Scoped Modern Profile Styling */
        .profile-main {
            display: flex;
            justify-content: center;
            padding: 30px 20px 80px;
            background-color: #f4f6fa;
            min-height: calc(100vh - 320px);
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .profile-content {
            display: flex;
            gap: 28px;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        @media (max-width: 991px) {
            .profile-content {
                flex-direction: column;
            }
            .profile-left-side, .profile-right-side {
                width: 100% !important;
            }
        }

        .profile-left-side {
            width: 350px;
            flex-shrink: 0;
        }

        .profile-right-side {
            flex: 1;
            min-width: 0;
        }

        /* Profile Left User Card */
        .profile-user-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 30px -5px rgba(12, 43, 112, 0.07), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .profile-user-header {
            background: linear-gradient(135deg, #0c2b70 0%, #1a3a8f 60%, #17337f 100%);
            padding: 32px 24px 28px;
            text-align: center;
            position: relative;
            color: #ffffff;
        }

        .profile-user-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(199, 157, 81, 0.5), transparent);
        }

        .profile-avatar-circle {
            width: 78px;
            height: 78px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ffffff 0%, #edf2f7 100%);
            color: #0c2b70;
            font-size: 26px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.28);
            border: 3px solid rgba(199, 157, 81, 0.85);
            margin-bottom: 14px;
            letter-spacing: 0.5px;
        }

        .profile-user-name {
            font-size: 21px;
            font-weight: 700;
            color: #ffffff;
            margin: 0 0 4px;
            letter-spacing: -0.2px;
            text-transform: capitalize;
        }

        .profile-user-email-sub {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.75);
            margin: 0 0 14px;
            word-break: break-all;
        }

        /* Status Badge */
        .profile-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        .badge-status-active {
            background: rgba(16, 185, 129, 0.18);
            color: #34d399;
            border: 1px solid rgba(52, 211, 153, 0.45);
        }

        .badge-status-expired {
            background: rgba(245, 158, 11, 0.2);
            color: #fbbf24;
            border: 1px solid rgba(251, 191, 36, 0.45);
        }

        .badge-status-free {
            background: rgba(255, 255, 255, 0.15);
            color: #f1f5f9;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            display: inline-block;
        }

        .status-dot-active { background-color: #10b981; box-shadow: 0 0 8px #10b981; }
        .status-dot-expired { background-color: #f59e0b; }
        .status-dot-free { background-color: #94a3b8; }

        /* User Card Body */
        .profile-user-body {
            padding: 24px;
        }

        /* Subscription Box in User Card */
        .profile-plan-banner {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 22px;
        }

        .profile-plan-banner-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .profile-plan-banner-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #64748b;
            font-weight: 700;
        }

        .profile-plan-banner-name {
            font-size: 15px;
            font-weight: 700;
            color: #0c2b70;
            margin-bottom: 4px;
        }

        .profile-plan-banner-date {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 12px;
        }

        .btn-sub-cta {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            padding: 9px 14px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 10px;
            text-decoration: none !important;
            transition: all 0.25s ease;
        }

        .btn-sub-cta-gold {
            background: linear-gradient(135deg, #c79d51 0%, #e5c378 100%);
            color: #0c2b70 !important;
            box-shadow: 0 4px 12px rgba(199, 157, 81, 0.28);
            border: none;
        }

        .btn-sub-cta-gold:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(199, 157, 81, 0.4);
            color: #0c2b70 !important;
        }

        .btn-sub-cta-outline {
            background: #ffffff;
            color: #0c2b70 !important;
            border: 1px solid #cbd5e1;
        }

        .btn-sub-cta-outline:hover {
            background: #e2e8f0;
            border-color: #0c2b70;
        }

        /* Detail List */
        .profile-detail-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-bottom: 24px;
        }

        .profile-detail-row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        .profile-detail-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .profile-detail-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #eff6ff;
            color: #1a3a8f;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .profile-detail-text {
            flex: 1;
            min-width: 0;
        }

        .profile-detail-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            margin-bottom: 2px;
        }

        .profile-detail-value {
            font-size: 13.5px;
            font-weight: 600;
            color: #1e293b;
            word-break: break-word;
        }

        /* Left Actions */
        .profile-actions-area {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn-profile-edit {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 11px 18px;
            background: #0c2b70;
            color: #ffffff !important;
            font-size: 14px;
            font-weight: 600;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(12, 43, 112, 0.2);
            text-decoration: none !important;
        }

        .btn-profile-edit:hover {
            background: #1a3a8f;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(12, 43, 112, 0.3);
            color: #ffffff !important;
        }

        .btn-profile-logout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 10px 18px;
            background: #ffffff;
            color: #ef4444 !important;
            font-size: 13.5px;
            font-weight: 600;
            border-radius: 12px;
            border: 1px solid #fee2e2;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none !important;
        }

        .btn-profile-logout:hover {
            background: #fef2f2;
            border-color: #fca5a5;
            color: #dc2626 !important;
        }

        /* Stat Metric Cards */
        .profile-stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        @media (max-width: 600px) {
            .profile-stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .profile-stat-card {
            background: #ffffff;
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            padding: 22px 24px;
            display: flex;
            align-items: center;
            gap: 18px;
            box-shadow: 0 8px 24px -4px rgba(12, 43, 112, 0.05);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            text-decoration: none !important;
        }

        .profile-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px -4px rgba(12, 43, 112, 0.12);
            border-color: #cbd5e1;
        }

        .stat-icon-wrapper {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            flex-shrink: 0;
        }

        .stat-saved .stat-icon-wrapper {
            background: #eff6ff;
            color: #1a3a8f;
            border: 1px solid #dbeafe;
        }

        .stat-downloaded .stat-icon-wrapper {
            background: #fef9ed;
            color: #c79d51;
            border: 1px solid #fef3c7;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 800;
            line-height: 1.1;
            color: #0c2b70;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .stat-sublabel {
            font-size: 12px;
            color: #64748b;
        }

        /* Activity Table Card */
        .profile-table-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 30px -5px rgba(12, 43, 112, 0.06);
            overflow: hidden;
        }

        .profile-table-header {
            border-bottom: 1px solid #e2e8f0;
            padding: 0 24px;
            background: #ffffff;
        }

        .profile-tabs.nav-tabs {
            border-bottom: none;
            gap: 24px;
        }

        .profile-tabs .nav-link {
            border: none;
            background: transparent;
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
            padding: 18px 4px;
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            text-decoration: none !important;
        }

        .profile-tabs .nav-link:hover {
            color: #0c2b70;
        }

        .profile-tabs .nav-link.active {
            color: #0c2b70 !important;
            background: transparent !important;
            font-weight: 700;
        }

        .profile-tabs .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #0c2b70 0%, #c79d51 100%);
            border-radius: 3px 3px 0 0;
        }

        .tab-badge {
            background: #f1f5f9;
            color: #475569;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .profile-tabs .nav-link.active .tab-badge {
            background: #eff6ff;
            color: #1a3a8f;
        }

        .profile-table-body {
            padding: 24px;
        }

        /* Empty State */
        .profile-empty-state {
            text-align: center;
            padding: 50px 24px;
        }

        .empty-icon-circle {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            color: #94a3b8;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin-bottom: 16px;
        }

        .empty-title {
            font-size: 17px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .empty-text {
            font-size: 13.5px;
            color: #64748b;
            max-width: 440px;
            margin: 0 auto 20px;
            line-height: 1.5;
        }

        /* DataTables Controls & Styling */
        .dataTables_wrapper {
            padding: 0 !important;
        }

        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 16px;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            padding: 7px 14px !important;
            font-size: 13px !important;
            outline: none !important;
            box-shadow: none !important;
            transition: all 0.2s ease;
            background-color: #ffffff;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #0c2b70 !important;
            box-shadow: 0 0 0 3px rgba(12, 43, 112, 0.1) !important;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            padding: 5px 10px !important;
            font-size: 13px !important;
        }

        .table.nk-tb-list {
            margin-bottom: 16px !important;
            border: 1px solid #edf2f7;
            border-radius: 10px;
            overflow: hidden;
        }

        .nk-tb-head th {
            background-color: #f8fafc !important;
            color: #475569 !important;
            font-size: 11.5px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            padding: 13px 18px !important;
            border-top: none !important;
            border-bottom: 1px solid #e2e8f0 !important;
        }

        .nk-tb-item td {
            padding: 14px 18px !important;
            vertical-align: middle !important;
            font-size: 13.5px !important;
            color: #334155 !important;
            border-bottom: 1px solid #f1f5f9 !important;
        }

        .nk-tb-item:hover td {
            background-color: #f8fafc !important;
        }

        .btn-download-action {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #eff6ff;
            color: #1a3a8f;
            border: 1px solid #dbeafe;
            font-size: 16px;
            transition: all 0.2s ease;
            text-decoration: none !important;
        }

        .btn-download-action:hover {
            background: #1a3a8f;
            color: #ffffff !important;
            border-color: #1a3a8f;
            transform: scale(1.05);
        }

        /* Modal Improvements */
        #modalForm .modal-dialog {
            max-width: 520px;
        }

        #modalForm .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        #modalForm .modal-header {
            background: linear-gradient(135deg, #0c2b70 0%, #1a3a8f 100%);
            color: #ffffff;
            padding: 20px 24px;
            border-bottom: none;
        }

        #modalForm .modal-title {
            color: #ffffff;
            font-weight: 700;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #modalForm .close {
            color: #ffffff;
            opacity: 0.85;
            text-shadow: none;
            font-size: 20px;
        }

        #modalForm .close:hover {
            opacity: 1;
        }

        #modalForm .modal-body {
            padding: 24px;
            background: #ffffff;
        }

        .form-section-divider {
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #64748b;
            margin: 22px 0 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-section-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .form-control-wrap input.form-control {
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .form-control-wrap input.form-control:focus {
            border-color: #0c2b70;
            box-shadow: 0 0 0 3px rgba(12, 43, 112, 0.12);
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        #modalForm button {
            width: auto;
        }

        #modalForm .close {
            width: auto !important;
            height: auto !important;
            padding: 0 !important;
            background: transparent !important;
            border: none !important;
            color: #ffffff !important;
            opacity: 0.85 !important;
            font-size: 22px !important;
            cursor: pointer !important;
            line-height: 1 !important;
        }

        #modalForm .close:hover {
            opacity: 1 !important;
        }

        .modal-footer-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 12px;
            margin-top: 24px;
            padding-top: 14px;
            border-top: 1px solid #f1f5f9;
        }

        .btn-modal-cancel {
            width: auto !important;
            background: #f1f5f9 !important;
            color: #475569 !important;
            font-weight: 600 !important;
            font-size: 14px !important;
            padding: 10px 20px !important;
            border-radius: 10px !important;
            border: 1px solid #cbd5e1 !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.2s ease !important;
        }

        .btn-modal-cancel:hover {
            background: #e2e8f0 !important;
            color: #1e293b !important;
        }

        .btn-modal-save {
            width: auto !important;
            background: #0c2b70 !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            font-size: 14px !important;
            padding: 10px 24px !important;
            border-radius: 10px !important;
            border: none !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            box-shadow: 0 4px 12px rgba(12, 43, 112, 0.2) !important;
            flex-shrink: 0 !important;
        }

        .btn-modal-save:hover {
            background: #1a3a8f !important;
            color: #ffffff !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 16px rgba(12, 43, 112, 0.28) !important;
        }

        @media (max-width: 480px) {
            .modal-footer-actions {
                flex-direction: column-reverse;
            }
            .modal-footer-actions button {
                width: 100% !important;
            }
        }
    </style>

    @php
        $userName = Auth::user()->name ?? 'User';
        $nameParts = array_filter(explode(' ', trim($userName)));
        if (count($nameParts) >= 2) {
            $initials = strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
        } else {
            $initials = strtoupper(substr($userName, 0, 2));
        }
    @endphp

    <section class="profile-main">
        <div class="profile-content">
            <!-- Left Side: User Identity & Account Details -->
            <div class="profile-left-side">
                <div class="profile-user-card">
                    <!-- User Header with Avatar -->
                    <div class="profile-user-header">
                        <div class="profile-avatar-circle">
                            {{ $initials }}
                        </div>
                        <h3 class="profile-user-name">{{ $userName }}</h3>
                        <p class="profile-user-email-sub">{{ Auth::user()->email }}</p>

                        <div>
                            @if ($isSubscribed)
                                <span class="profile-status-badge badge-status-active">
                                    <span class="status-dot status-dot-active"></span> Active Subscriber
                                </span>
                            @elseif ($userPlan)
                                <span class="profile-status-badge badge-status-expired">
                                    <span class="status-dot status-dot-expired"></span> Plan Expired
                                </span>
                            @else
                                <span class="profile-status-badge badge-status-free">
                                    <span class="status-dot status-dot-free"></span> Free Tier
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- User Card Body -->
                    <div class="profile-user-body">
                        <!-- Subscription Status Banner -->
                        <div class="profile-plan-banner">
                            <div class="profile-plan-banner-header">
                                <span class="profile-plan-banner-title">Membership Status</span>
                                @if ($isSubscribed)
                                    <span class="badge badge-sm badge-success">Active</span>
                                @elseif ($userPlan)
                                    <span class="badge badge-sm badge-warning">Expired</span>
                                @else
                                    <span class="badge badge-sm badge-light">Free</span>
                                @endif
                            </div>

                            <div class="profile-plan-banner-name">
                                {{ optional($userPlan?->subscriptionPlan)->name ?? ($isSubscribed ? 'Active Plan' : 'No Active Plan') }}
                            </div>

                            @if ($isSubscribed && $userPlan?->end_date)
                                <div class="profile-plan-banner-date">
                                    Expires: {{ \Carbon\Carbon::parse($userPlan->end_date)->format('M d, Y') }}
                                </div>
                            @elseif ($userPlan && $userPlan->end_date)
                                <div class="profile-plan-banner-date text-warning">
                                    Expired on: {{ \Carbon\Carbon::parse($userPlan->end_date)->format('M d, Y') }}
                                </div>
                            @else
                                <div class="profile-plan-banner-date">
                                    Upgrade to access premium rules and downloads.
                                </div>
                            @endif

                            @if ($isSubscribed)
                                <a href="{{ url('subscribe') }}" class="btn-sub-cta btn-sub-cta-outline">
                                    <em class="icon ni ni-swap-alt"></em> Manage / Change Plan
                                </a>
                            @elseif ($userPlan)
                                <a href="{{ url('subscribe') }}" class="btn-sub-cta btn-sub-cta-gold">
                                    <em class="icon ni ni-reload"></em> Renew Subscription
                                </a>
                            @else
                                <a href="{{ url('subscribe') }}" class="btn-sub-cta btn-sub-cta-gold">
                                    <em class="icon ni ni-spark"></em> Upgrade to Premium
                                </a>
                            @endif
                        </div>

                        <!-- Profile Info Rows -->
                        <div class="profile-detail-list">
                            <div class="profile-detail-row">
                                <div class="profile-detail-icon">
                                    <em class="icon ni ni-mail"></em>
                                </div>
                                <div class="profile-detail-text">
                                    <div class="profile-detail-label">Email Address</div>
                                    <div class="profile-detail-value">{{ Auth::user()->email }}</div>
                                </div>
                            </div>

                            <div class="profile-detail-row">
                                <div class="profile-detail-icon">
                                    <em class="icon ni ni-call"></em>
                                </div>
                                <div class="profile-detail-text">
                                    <div class="profile-detail-label">Phone Number</div>
                                    <div class="profile-detail-value">{{ Auth::user()->phone ?: 'Not provided' }}</div>
                                </div>
                            </div>

                            <div class="profile-detail-row">
                                <div class="profile-detail-icon">
                                    <em class="icon ni ni-lock"></em>
                                </div>
                                <div class="profile-detail-text">
                                    <div class="profile-detail-label">Password</div>
                                    <div class="profile-detail-value d-flex justify-content-between align-items-center">
                                        <span>••••••••••••</span>
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#modalForm" data-bs-toggle="modal" data-bs-target="#modalForm" class="text-primary font-weight-bold" style="font-size: 12px;">Change</a>
                                    </div>
                                </div>
                            </div>

                            @if (Auth::user()->created_at)
                                <div class="profile-detail-row">
                                    <div class="profile-detail-icon">
                                        <em class="icon ni ni-calendar"></em>
                                    </div>
                                    <div class="profile-detail-text">
                                        <div class="profile-detail-label">Member Since</div>
                                        <div class="profile-detail-value">{{ Auth::user()->created_at->format('M Y') }}</div>
                                    </div>
                                </div>
                            @endif

                            @if ($canManageTeam ?? false)
                                <div class="profile-detail-row">
                                    <div class="profile-detail-icon">
                                        <em class="icon ni ni-users"></em>
                                    </div>
                                    <div class="profile-detail-text">
                                        <div class="profile-detail-label">Team Members</div>
                                        <div class="profile-detail-value">
                                            <a href="{{ route('institutionalMember.index') }}" class="text-primary font-weight-bold">
                                                Manage Team &rarr;
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="profile-actions-area">
                            <button type="button" class="btn-profile-edit" data-toggle="modal" data-target="#modalForm" data-bs-toggle="modal" data-bs-target="#modalForm">
                                <em class="icon ni ni-edit-alt"></em> Edit Profile
                            </button>

                            <a href="{{ route('logout') }}" class="btn-profile-logout"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <em class="icon ni ni-signout"></em> Sign Out
                            </a>
                        </div>
                    </div>
                </div>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>

            <!-- Right Side: Stats & Activity Tables -->
            <div class="profile-right-side">
                <!-- Quick Metric Counters -->
                <div class="profile-stats-grid">
                    <div class="profile-stat-card stat-saved" onclick="$('a[href=\'#tabItem1\']').tab('show');">
                        <div class="stat-icon-wrapper">
                            <em class="icon ni ni-bookmark"></em>
                        </div>
                        <div>
                            <div class="stat-number">{{ $docSaved }}</div>
                            <div class="stat-label">Documents Saved</div>
                            <div class="stat-sublabel">Your bookmarked regulations</div>
                        </div>
                    </div>

                    <div class="profile-stat-card stat-downloaded" onclick="$('a[href=\'#tabItem2\']').tab('show');">
                        <div class="stat-icon-wrapper">
                            <em class="icon ni ni-download-cloud"></em>
                        </div>
                        <div>
                            <div class="stat-number">{{ $docDownloaded }}</div>
                            <div class="stat-label">Documents Downloaded</div>
                            <div class="stat-sublabel">Downloaded for offline use</div>
                        </div>
                    </div>
                </div>

                <!-- Document Activity Tab Card -->
                <div class="profile-table-card">
                    <div class="profile-table-header">
                        <ul class="nav nav-tabs profile-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" data-bs-toggle="tab" href="#tabItem1">
                                    <em class="icon ni ni-bookmark"></em> Documents Saved
                                    <span class="tab-badge">{{ $docSaved }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" data-bs-toggle="tab" href="#tabItem2">
                                    <em class="icon ni ni-download"></em> Documents Downloaded
                                    <span class="tab-badge">{{ $docDownloaded }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="profile-table-body">
                        <div class="tab-content">
                            <!-- Tab 1: Saved Documents -->
                            <div class="tab-pane active" id="tabItem1">
                                @if ($savedDocuments->count() > 0)
                                    <table class="datatable-init table nk-tb-list" data-auto-responsive="false">
                                        <thead>
                                            <tr class="nk-tb-head">
                                                <th class="nk-tb-col" style="width: 60px;">S/N</th>
                                                <th class="nk-tb-col">Document Title</th>
                                                <th class="nk-tb-col" style="width: 140px;">Saved On</th>
                                                <th class="nk-tb-col text-right" style="width: 90px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($savedDocuments as $save)
                                                <tr class="nk-tb-item">
                                                    <td class="nk-tb-col font-weight-bold text-muted">{{ $loop->iteration }}</td>
                                                    <td class="nk-tb-col">
                                                        <span class="font-weight-bold text-dark">
                                                            {{ optional($save->regulation)->title ?? 'Regulation Document' }}
                                                        </span>
                                                    </td>
                                                    <td class="nk-tb-col text-muted">
                                                        {{ str_replace('May.', 'May', \Carbon\Carbon::parse($save->created_at)->format('M. j, Y')) }}
                                                    </td>
                                                    <td class="nk-tb-col text-right">
                                                        @if ($save->regulation)
                                                            @if ($isSubscribed || (Auth::check() && Auth::user()->usertype == 'internal'))
                                                                <a href="{{ route('download', $save->regulation->id) }}"
                                                                    class="btn-download-action" title="Download Document">
                                                                    <em class="icon ni ni-download"></em>
                                                                </a>
                                                            @else
                                                                <a href="{{ route('subscribe') }}"
                                                                    class="btn-download-action" title="Subscribe to Download">
                                                                    <em class="icon ni ni-download"></em>
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="profile-empty-state">
                                        <div class="empty-icon-circle">
                                            <em class="icon ni ni-bookmark"></em>
                                        </div>
                                        <h4 class="empty-title">No Saved Documents Yet</h4>
                                        <p class="empty-text">
                                            You haven't bookmarked any regulations yet. Search or explore regulations and bookmark them for instant access.
                                        </p>
                                        <a href="{{ url('/') }}" class="btn btn-primary" style="background: #0c2b70; border: none; border-radius: 10px; padding: 10px 22px; font-weight: 600;">
                                            <em class="icon ni ni-search mr-1"></em> Explore Regulations
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Tab 2: Downloaded Documents -->
                            <div class="tab-pane" id="tabItem2">
                                @if ($downloadedDocuments->count() > 0)
                                    <table class="datatable-init table nk-tb-list" data-auto-responsive="false">
                                        <thead>
                                            <tr class="nk-tb-head">
                                                <th class="nk-tb-col" style="width: 60px;">S/N</th>
                                                <th class="nk-tb-col">Document Title</th>
                                                <th class="nk-tb-col" style="width: 140px;">Downloaded On</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($downloadedDocuments as $download)
                                                <tr class="nk-tb-item">
                                                    <td class="nk-tb-col font-weight-bold text-muted">{{ $loop->iteration }}</td>
                                                    <td class="nk-tb-col">
                                                        <span class="font-weight-bold text-dark">
                                                            {{ optional($download->regulation)->title ?? 'Regulation Document' }}
                                                        </span>
                                                    </td>
                                                    <td class="nk-tb-col text-muted">
                                                        {{ str_replace('May.', 'May', \Carbon\Carbon::parse($download->created_at)->format('M. j, Y')) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="profile-empty-state">
                                        <div class="empty-icon-circle">
                                            <em class="icon ni ni-download-cloud"></em>
                                        </div>
                                        <h4 class="empty-title">No Downloaded Documents Yet</h4>
                                        <p class="empty-text">
                                            Your download history will appear here once you download regulatory documents from the portal.
                                        </p>
                                        <a href="{{ url('/') }}" class="btn btn-primary" style="background: #0c2b70; border: none; border-radius: 10px; padding: 10px 22px; font-weight: 600;">
                                            <em class="icon ni ni-search mr-1"></em> Explore Regulations
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Update Profile Modal -->
    <div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <em class="icon ni ni-user-edit mr-1"></em> Edit Profile Information
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('profile.update') }}" method="POST" class="form-validate is-alter">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="form-label" for="full-name">Full Name <span class="text-danger">*</span></label>
                            <div class="form-control-wrap">
                                <input value="{{ Auth::user()->name }}" type="text" name="name"
                                    class="form-control" id="full-name" required placeholder="Enter full name">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="email-address">Email Address <span class="text-danger">*</span></label>
                            <div class="form-control-wrap">
                                <input type="email" value="{{ Auth::user()->email }}" name="email"
                                    class="form-control" id="email-address" required placeholder="name@example.com">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="phone-no">Phone Number</label>
                            <div class="form-control-wrap">
                                <input type="text" value="{{ Auth::user()->phone }}" name="phone"
                                    class="form-control" id="phone-no" placeholder="e.g. 08012345678">
                            </div>
                        </div>

                        <div class="form-section-divider">
                            <em class="icon ni ni-lock"></em> Change Password (Optional)
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="password">New Password</label>
                            <div class="form-control-wrap">
                                <input type="password" name="password" class="form-control" id="password" placeholder="Leave blank to keep current password">
                            </div>
                            <small class="form-text text-muted" style="font-size: 11.5px;">Must be at least 8 characters and include uppercase, lowercase, numbers, and special characters.</small>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label" for="password_confirmation">Confirm New Password</label>
                            <div class="form-control-wrap">
                                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Re-type new password">
                            </div>
                        </div>

                        <div class="modal-footer-actions">
                            <button type="button" class="btn-modal-cancel" data-dismiss="modal" data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="submit" class="btn-modal-save">
                                <em class="icon ni ni-check"></em> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('public/admin/js/bundle.js') }}"></script>
    <script src="{{ asset('public/admin/js/scripts.js') }}"></script>
    <script src="{{ asset('public/admin/js/libs/datatable-btns.js') }}"></script>

    <script>
        $(document).ready(function() {
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error('{{ $error }}', '');
                @endforeach
            @endif

            @if (session('success'))
                toastr.success('{{ session('success') }}', '');
            @endif

            @if (session('error'))
                toastr.error('{{ session('error') }}', '');
            @endif
        });
    </script>
@endsection
