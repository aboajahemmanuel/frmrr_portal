<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('code') — @yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #0a1628 0%, #0f2044 40%, #162d5a 70%, #1a3a8f 100%);
            overflow: hidden;
            position: relative;
        }

        /* ── Animated Background Particles ── */
        .particles {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
        }
        .particles span {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
            animation: float linear infinite;
        }
        .particles span:nth-child(1) { width: 6px; height: 6px; left: 10%; animation-duration: 18s; animation-delay: 0s; }
        .particles span:nth-child(2) { width: 4px; height: 4px; left: 25%; animation-duration: 22s; animation-delay: 3s; }
        .particles span:nth-child(3) { width: 8px; height: 8px; left: 40%; animation-duration: 16s; animation-delay: 1s; }
        .particles span:nth-child(4) { width: 5px; height: 5px; left: 55%; animation-duration: 20s; animation-delay: 5s; }
        .particles span:nth-child(5) { width: 7px; height: 7px; left: 70%; animation-duration: 24s; animation-delay: 2s; }
        .particles span:nth-child(6) { width: 3px; height: 3px; left: 85%; animation-duration: 19s; animation-delay: 4s; }
        .particles span:nth-child(7) { width: 6px; height: 6px; left: 5%;  animation-duration: 21s; animation-delay: 6s; }
        .particles span:nth-child(8) { width: 4px; height: 4px; left: 60%; animation-duration: 17s; animation-delay: 2s; }

        @keyframes float {
            0%   { transform: translateY(110vh) scale(0); opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 1; }
            100% { transform: translateY(-10vh) scale(1); opacity: 0; }
        }

        /* ── Radial Glow ── */
        body::before {
            content: '';
            position: fixed;
            width: 700px;
            height: 700px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(26,58,143,.35) 0%, transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
            z-index: 0;
        }

        /* ── Card ── */
        .error-card {
            position: relative;
            z-index: 1;
            max-width: 520px;
            width: 90%;
            background: rgba(255,255,255,.06);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 24px;
            padding: 48px 40px 44px;
            text-align: center;
            box-shadow:
                0 8px 32px rgba(0,0,0,.3),
                inset 0 1px 0 rgba(255,255,255,.08);
            animation: cardIn .6s cubic-bezier(.22,1,.36,1) both;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(30px) scale(.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* ── Logo ── */
        .error-logo {
            margin-bottom: 28px;
        }
        .error-logo img {
            height: 44px;
            filter: brightness(0) invert(1);
            opacity: .85;
        }

        /* ── Icon Area ── */
        .error-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            width: 88px;
            height: 88px;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.08);
            animation: iconPulse 3s ease-in-out infinite;
        }
        .error-icon svg {
            width: 40px;
            height: 40px;
        }

        @keyframes iconPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(255,255,255,.08); }
            50%      { box-shadow: 0 0 0 14px rgba(255,255,255,.03); }
        }

        /* ── Error Code ── */
        .error-code {
            font-size: 72px;
            font-weight: 900;
            letter-spacing: -2px;
            line-height: 1;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #fff 30%, rgba(255,255,255,.5));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ── Title ── */
        .error-title {
            font-size: 20px;
            font-weight: 600;
            color: rgba(255,255,255,.9);
            margin-bottom: 12px;
        }

        /* ── Description ── */
        .error-description {
            font-size: 15px;
            font-weight: 400;
            color: rgba(255,255,255,.55);
            line-height: 1.6;
            margin-bottom: 32px;
            max-width: 380px;
            margin-left: auto;
            margin-right: auto;
        }

        /* ── Divider ── */
        .error-divider {
            width: 48px;
            height: 3px;
            border-radius: 2px;
            background: linear-gradient(90deg, rgba(255,255,255,.15), rgba(255,255,255,.35), rgba(255,255,255,.15));
            margin: 0 auto 32px;
        }

        /* ── CTA Button ── */
        .error-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: .3px;
            text-decoration: none;
            color: #0c2b70;
            background: linear-gradient(135deg, #fff 0%, #e0e7ff 100%);
            border: none;
            border-radius: 14px;
            cursor: pointer;
            transition: all .3s cubic-bezier(.22,1,.36,1);
            box-shadow: 0 4px 16px rgba(0,0,0,.2), 0 0 0 0 rgba(255,255,255,.2);
        }
        .error-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,.3), 0 0 0 2px rgba(255,255,255,.15);
        }
        .error-btn:active {
            transform: translateY(0);
        }
        .error-btn svg {
            width: 16px;
            height: 16px;
            transition: transform .3s ease;
        }
        .error-btn:hover svg {
            transform: translateX(-3px);
        }

        /* ── Responsive ── */
        @media (max-width: 480px) {
            .error-card {
                padding: 36px 24px 32px;
                border-radius: 20px;
            }
            .error-code {
                font-size: 56px;
            }
            .error-title {
                font-size: 18px;
            }
            .error-icon {
                width: 72px;
                height: 72px;
            }
            .error-icon svg {
                width: 32px;
                height: 32px;
            }
        }
    </style>
</head>
<body>
    <!-- Floating Particles -->
    <div class="particles">
        <span></span><span></span><span></span><span></span>
        <span></span><span></span><span></span><span></span>
    </div>

    <div class="error-card">
        <!-- Logo -->
        <div class="error-logo">
            <img src="{{ asset('public/admin/images/FMDQ_Logo.png') }}" alt="FMDQ Logo">
        </div>

        <!-- Icon -->
        <div class="error-icon">
            @yield('icon')
        </div>

        <!-- Error Code -->
        <div class="error-code">@yield('code')</div>

        <!-- Title -->
        <h1 class="error-title">@yield('title')</h1>

        <!-- Divider -->
        <div class="error-divider"></div>

        <!-- Description -->
        <p class="error-description">@yield('description')</p>

        <!-- CTA -->
        <a href="{{ app('router')->has('home') ? route('home') : url('/') }}" class="error-btn">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Go Home
        </a>
    </div>
</body>
</html>
