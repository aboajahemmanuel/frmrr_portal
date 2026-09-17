@extends('layouts.headerexternal')

@section('content')
<link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />
<style>
    /* Reset and Base Styles */
    .pricing-container * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Arial', sans-serif;
    }

    .pricing-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        padding: 40px 20px;
    }

    /* Page Title */
    .pricing-body-header h2 {
        color: #1a3a8f;
        font-size: 36px;
        font-weight: 800;
        margin-bottom: 40px;
        text-align: center;
        position: relative;
    }

    .pricing-body-header h2::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, #1a3a8f, #2e4ba0);
        border-radius: 2px;
    }

    /* Plans Container */
    .plans-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 30px;
        max-width: 1300px;
        width: 100%;
        margin: 0 auto;
    }

    /* Plan Card */
    .card {
        flex: 1;
        min-width: 280px;
        max-width: 320px;
        background: linear-gradient(145deg, #1a3a8f 0%, #0c2b70 100%);
        border-radius: 20px;
        padding: 30px 25px;
        color: white;
        box-shadow: 0 15px 35px rgba(12, 43, 112, 0.25);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
        min-height: 350px;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(12, 43, 112, 0.3);
    }

    /* Decorative Elements */
    .card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 60px;
        height: 60px;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 0 0 0 60px;
    }

    .card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 40px;
        background-color: rgba(255, 255, 255, 0.05);
        border-radius: 0 40px 0 0;
    }

    /* Card Header */
    .card-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .card-title {
        font-size: 16px;
        font-weight: 700;
        display: block;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.9;
    }

    .card-price {
        font-size: clamp(22px, 4vw, 28px);
        font-weight: 900;
        position: relative;
        word-break: break-word;
        line-height: 1.1;
        margin: 10px 0;
    }

    /* Card Body */
    .card-body {
        flex: 1;
        text-align: center;
        /* margin: 20px 0; */
    }

    .card-body p {
        font-size: 14px;
        line-height: 1.5;
        color: rgba(255, 255, 255, 0.85);
    }

    /* Card Footer */
    .card-footer {
        margin-top: auto;
    }

    /* Highlighted Card */
    .card.highlighted {
        background: linear-gradient(135deg, #1e429c 0%, #0d2d78 100%);
        transform: scale(1.03);
        box-shadow: 0 15px 35px rgba(12, 43, 112, 0.4);
        border: 2px solid rgba(255, 215, 138, 0.6);
    }

    .card.highlighted::before {
        background-color: rgba(255, 215, 138, 0.2);
    }

    .card.highlighted .card-price {
        color: #f4d078;
        text-shadow: 0 0 10px rgba(255, 215, 138, 0.4);
    }

    /* Popular Badge */
    .popular-badge {
        position: absolute;
        top: 20px;
        right: -30px;
        background: linear-gradient(90deg, #f4d078, #d5a73b);
        color: #0c2b70;
        padding: 5px 30px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
        transform: rotate(45deg);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        z-index: 2;
    }

    /* CTA Button */
    .choose-button {
        display: block;
        width: 100%;
        background-color: white;
        color: #1a3a8f;
        padding: 12px 20px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        border: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .choose-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }

    .choose-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }

    .choose-button:hover::before {
        transform: translateX(100%);
    }

    .highlighted .choose-button {
        background: linear-gradient(90deg, #f4d078, #d5a73b);
        color: #0c2b70;
        font-weight: 700;
    }

    /* Plan Tabs */
    .plan-tabs {
        display: flex;
        gap: 10px;
        background: #eef1f8;
        padding: 6px;
        border-radius: 40px;
        margin: 10px auto 40px;
    }

    .plan-tab-btn {
        border: none;
        background: transparent;
        color: #526484;
        font-weight: 700;
        font-size: 14px;
        padding: 10px 26px;
        border-radius: 30px;
        cursor: pointer;
        transition: all 0.25s ease;
        font-family: 'Roboto', sans-serif;
    }

    .plan-tab-btn.active {
        background: #1a3a8f;
        color: #fff;
        box-shadow: 0 4px 10px rgba(12, 43, 112, 0.25);
    }

    .plan-tab-panel {
        width: 100%;
        display: none;
    }

    .plan-tab-panel.active {
        display: block;
    }

    /* Sub-tier group heading, used inside the Academic tab */
    .plan-group-heading {
        text-align: center;
        max-width: 700px;
        margin: 0 auto 20px;
    }

    .plan-group-heading h3 {
        color: #1c2b46;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 6px;
        font-family: 'Roboto', sans-serif;
    }

    .plan-group-heading p {
        color: #667;
        font-size: 13.5px;
        line-height: 1.6;
        font-family: 'Roboto', sans-serif;
    }

    .plan-group + .plan-group {
        margin-top: 50px;
    }

    /* Complimentary / non-payable plan card */
    .card .complimentary-note {
        display: block;
        background: rgba(255, 255, 255, 0.12);
        border-radius: 10px;
        padding: 12px;
        font-size: 12.5px;
        line-height: 1.5;
        color: rgba(255, 255, 255, 0.85);
        text-align: center;
    }

    /* Responsive Adjustments */
    @media (max-width: 1200px) {
        .plans-container {
            max-width: 800px;
        }
        .card {
            min-width: 300px;
        }
    }

    @media (max-width: 768px) {
        .pricing-body-header h2 {
            font-size: 28px;
        }
        
        .plans-container {
            flex-direction: column;
            align-items: center;
        }

        .card {
            width: 100%;
            max-width: 400px;
            min-height: auto;
            margin-bottom: 20px;
        }
    }
</style>
    <div class="info">

        <div class="title">Subscribe </div>


    </div>
    </div>

    </section>
    <section class="hd-main-container pricing-section">
            @if ($userSubscription)
        @else
    <div class="upgrade-card">

             <section class="hd-main-container pricing-section" >
                           <div class="pricing-container">
    <div class="pricing-body-header" style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; width: 100%;">
          <h1>Upgrade Your Access</h1>
        <h2>Choose A Plan</h2>
        
        <div class="pricing-benefits-grid" style="max-width: 1200px; margin: 30px auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; font-family: 'Roboto', sans-serif;">
            <!-- Benefit 1 -->
            <div style="background: #ffffff; border: 1px solid #e5e9f2; border-radius: 8px; padding: 25px 20px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.03); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: #fef8e4; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #ad8e4f;">
                    <em class="icon ni ni-book-read" style="font-size: 24px;"></em>
                </div>
                <h4 style="font-size: 16.5px; color: #1c2b46; font-weight: 700; margin-bottom: 10px; font-family: 'Roboto', sans-serif;">Full Access</h4>
                <p style="font-size: 13.5px; color: #526484; line-height: 1.6; margin: 0; font-family: 'Roboto', sans-serif;">View and download all financial market regulations, rules, and circulars.</p>
            </div>
            
            <!-- Benefit 2 -->
            <div style="background: #ffffff; border: 1px solid #e5e9f2; border-radius: 8px; padding: 25px 20px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.03); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: #e6f8f3; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #1ee0ac;">
                    <em class="icon ni ni-eye" style="font-size: 24px;"></em>
                </div>
                <h4 style="font-size: 16.5px; color: #1c2b46; font-weight: 700; margin-bottom: 10px; font-family: 'Roboto', sans-serif;">Interactive Previews</h4>
                <p style="font-size: 13.5px; color: #526484; line-height: 1.6; margin: 0; font-family: 'Roboto', sans-serif;">Preview PDF documents directly within your browser window.</p>
            </div>
            
            <!-- Benefit 3 -->
            <div style="background: #ffffff; border: 1px solid #e5e9f2; border-radius: 8px; padding: 25px 20px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.03); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: #e5f3ff; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #09c2de;">
                    <em class="icon ni ni-network" style="font-size: 24px;"></em>
                </div>
                <h4 style="font-size: 16.5px; color: #1c2b46; font-weight: 700; margin-bottom: 10px; font-family: 'Roboto', sans-serif;">Document Linkage</h4>
                <p style="font-size: 13.5px; color: #526484; line-height: 1.6; margin: 0; font-family: 'Roboto', sans-serif;">Trace amendments, active versions, and revisions effortlessly.</p>
            </div>
            
            <!-- Benefit 4 -->
            <div style="background: #ffffff; border: 1px solid #e5e9f2; border-radius: 8px; padding: 25px 20px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.03); transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: #f3f2ff; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #816bff;">
                    <em class="icon ni ni-search" style="font-size: 24px;"></em>
                </div>
                <h4 style="font-size: 16.5px; color: #1c2b46; font-weight: 700; margin-bottom: 10px; font-family: 'Roboto', sans-serif;">Smart Tools</h4>
                <p style="font-size: 13.5px; color: #526484; line-height: 1.6; margin: 0; font-family: 'Roboto', sans-serif;">Use advanced filters and search, and get real-time notifications.</p>
            </div>
        </div>
    </div>
    <div class="pricing-body-plans">
        <div class="plan-tabs">
            <button type="button" class="plan-tab-btn active" data-tab="institutional">Institutional Subscription</button>
            <button type="button" class="plan-tab-btn" data-tab="academic">Academic Subscription</button>
        </div>

        <div class="plan-tab-panel active" id="plan-tab-institutional">
            <div class="plans-container">
                @foreach ($institutionalPlans as $package)
                    @include('partials.subscription-plan-card', ['package' => $package, 'highlight' => $package->duration >= 365])
                @endforeach
            </div>
        </div>

        <div class="plan-tab-panel" id="plan-tab-academic">
            @if ($academicTier)
                @foreach ($academicTier->subTiers as $subTier)
                    <div class="plan-group">
                        <div class="plan-group-heading">
                            <h3>{{ $subTier->name }}</h3>
                            @if ($subTier->description)
                                <p>{{ $subTier->description }}</p>
                            @endif
                        </div>
                        <div class="plans-container">
                            @foreach ($subTier->plans as $package)
                                @include('partials.subscription-plan-card', ['package' => $package, 'highlight' => $package->duration >= 365 && $package->price > 0])
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    </div>
    </section>
    </div>
    @endif
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.plan-tab-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.plan-tab-btn').forEach(function(b) { b.classList.remove('active'); });
                    document.querySelectorAll('.plan-tab-panel').forEach(function(p) { p.classList.remove('active'); });
                    btn.classList.add('active');
                    document.getElementById('plan-tab-' + btn.dataset.tab).classList.add('active');
                });
            });
        });
    </script>
@endsection
</div>
</body>

</html>