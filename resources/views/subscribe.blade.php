@extends('layouts.headerexternal')

@section('content')
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
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr;
        flex-wrap: wrap;
        justify-content: center;
        gap: 25px;
        max-width: 1200px;
        width: 100%;
    }

    /* Plan Card */
    .card {
        flex: 1;
        min-width: 250px;
        max-width: 300px;
        max-height: ;
        background: linear-gradient(135deg, #1a3a8f 0%, #0c2b70 100%);
        border-radius: 15px;
        padding: 30px;
        color: white;
        box-shadow: 0 10px 25px rgba(12, 43, 112, 0.2);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
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
        font-size: 18px;
        font-weight: 600;
        display: block;
        margin-bottom: 15px;
    }

    .card-price {
        font-size: 32px;
        font-weight: 800;
        /* margin: 10px 0; */
        position: relative;
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
    @media (max-width: 900px) {
        .plans-container {
            flex-direction: column;
            align-items: center;
        }
        
        .card {
            max-width: 100%;
            width: 100%;
        }
        
        .card.highlighted {
            order: -1;
            margin-bottom: 10px;
        }
    }
</style>
    <div class="info">

        <div class="title">Subscribe </div>


    </div>
    </div>

    </section>
    <section class="hd-main-container pricing-section">
        <div class="pricing">

            <div class="pricing-body" >
            <!-- style="margin: -150px 0px 00px 0px " -->
                <br>
                <br>
                <br>
                <div class="pricing-container">
    <div class="pricing-body-header">
        <h2>Choose a Plan</h2>
    </div>
    <div class="pricing-body-plans">
        <div class="active" id="pricing__monthly__plan">
            <div class="plans-container">
                @foreach ($plans as $package)
                    <div class="card {{ $package->name === 'Weekly Access' ? 'highlighted' : '' }}">
                        @if($package->name === 'Weekly Access')
                            <div class="popular-badge">POPULAR</div>
                        @endif
                        <div class="card-header">
                            <span class="card-title">{{ $package->name }}</span>
                            <h2 class="card-price">₦{{ number_format($package->price, 2) }}</h2>
                        </div>
                        <div class="card-body">
                            <p>{{ $package->description }}</p>
                        </div>
                        <div class="card-footer">
                            <form method="post" action="{{ route('subscribe_payment') }}">
                                @csrf
                                <input name="plan_id" type="hidden" value="{{ $package->id }}" name="">
                                <button type="submit" class="choose-button">Choose Plan</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
            </div>
        </div>
    </section>

    </div>
@endsection
</div>
</body>

</html>
