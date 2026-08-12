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

    .card-price::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 40px;
        height: 2px;
        background-color: rgba(255, 255, 255, 0.3);
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

    .card.highlighted .card-price::after {
        background-color: rgba(255, 215, 138, 0.5);
        height: 3px;
        width: 50px;
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
        transform: rotate(45deg);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
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
        <div class="active" id="pricing__monthly__plan">
            <div class="plans-container">
                @foreach ($plans as $package)

                <!-- ll -->
                    <div class="card">
                        <div class="card-header">
                            <span class="card-title">{{ $package->name }}</span>
                            <h2 class="card-price" style="font-family: 'Roboto', sans-serif; color: #f6f7fcff; font-size: 24px; font-weight: 600; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                <span>
                                    ₦{{ number_format($package->price, 2) }}
                                    @if(isset($package->price_usd) && $package->price_usd > 0)
                                        <span style="font-size: 18px; font-weight: 300; color: #b5c2d3; margin-left: 5px;">/</span>
                                    @endif
                                </span>
                                @if(isset($package->price_usd) && $package->price_usd > 0)
                                    <span style="font-size: 26px; font-weight: normal; color: #b5c2d3; margin-top: 5px; display: block;">${{ number_format($package->price_usd, 2) }}</span>
                                @endif
                            </h2>
                        </div>
                        <div class="card-body">
                            <p>{{ $package->description }}</p>
                        </div>
                        <div class="card-footer">
                            <form method="post" action="{{ route('subscribe_payment') }}">
                                @csrf
                                <input name="plan_id" type="hidden" value="{{ $package->id }}">
                                @if(isset($package->price_usd) && $package->price_usd > 0)
                                    <button type="submit" name="currency" value="NGN" class="choose-button mb-2" style="width: 100%;">Pay in Naira (₦{{ number_format($package->price ?? 0, 2) }})</button>
                                    <button type="submit" name="currency" value="USD" class="choose-button" style="width: 100%;">Pay in USD (${{ number_format($package->price_usd, 2) }})</button>
                                @else
                                     <button type="submit" name="currency" value="NGN" class="choose-button mb-2" style="width: 100%;">Pay in Naira (₦{{ number_format($package->price ?? 0, 2) }})</button>
                                @endif
                            </form>
                            
                        </div>
                        
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    </div>
    </section>
    </div>
    @endif
    </section>
@endsection
</div>
</body>

</html>