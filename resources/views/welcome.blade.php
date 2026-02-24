@extends('layouts.external')

@section('content')
<link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />
<style>
    .upgrade-card {
        position: relative;
        width: 100%;
        height: auto;
        background: linear-gradient(135deg, #f8f9fd 0%, #eef1f8 100%);
        border-radius: 20px;
        padding: 40px 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        margin: 20px 0;
    }

    /* Decorative Elements */
    .upgrade-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 60px;
        height: 80px;
        background-color: rgba(26, 58, 143, 0.1);
        clip-path: polygon(0 0, 100% 0, 0 100%);
    }

    .upgrade-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 80px;
        height: 80px;
        background-color: rgba(26, 58, 143, 0.1);
        clip-path: polygon(100% 100%, 0 100%, 100% 0);
    }

    /* Header Section */
    .card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .header-text {
        flex: 1;
    }

    h1 {
        color: #1a3a8f;
        font-size: 34px;
        font-weight: bold;
        line-height: 1.1;
        margin-bottom: 10px;
    }

    h1 span {
        display: block;
        font-size: 42px;
        font-weight: 900;
    }

    /* Medal Icon */
    .medal-icon {
        position: relative;
        width: 100px;
        height: 100px;
        margin-left: 20px;
    }

    .medal-circle {
        position: absolute;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f4d078 0%, #d5a73b 100%);
        border: 3px solid #c29730;
        box-shadow: 0 0 15px rgba(255, 215, 138, 0.6);
    }

    .medal-ribbon {
        position: absolute;
        top: 50px;
        left: 35px;
        width: 30px;
        height: 40px;
        background-color: #1a3a8f;
        clip-path: polygon(0 0, 100% 0, 50% 100%);
        z-index: -1;
    }

    .medal-crown {
        position: absolute;
        top: 35px;
        left: 30px;
        width: 40px;
        height: 20px;
        background-color: #9c7425;
        clip-path: polygon(0 100%, 25% 0, 50% 50%, 75% 0, 100% 100%);
    }

    .medal-shine {
        position: absolute;
        top: 25px;
        left: 25px;
        width: 20px;
        height: 12px;
        background-color: white;
        border-radius: 50%;
        opacity: 0.3;
        transform: rotate(-30deg);
    }

    /* Value Proposition */
    .value-prop {
        color: #555;
        font-size: 16px;
        margin-bottom: 10px;
    }

    .social-proof {
        color: #666;
        font-size: 14px;
        margin-bottom: 25px;
        opacity: 0.8;
    }

    /* Features List */
    .features {
        margin: 25px 0;
    }

    .feature-item {
        display: flex;
        align-items: center;
        margin-bottom: 16px;
    }

    .feature-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: #eaeeff;
        border: 1.5px solid #1a3a8f;
        margin-right: 15px;
        color: #1a3a8f;
        font-weight: bold;
    }

    .feature-text {
        color: #444;
        font-size: 14px;
    }

    /* CTA Button */
    .cta-section {
        margin-top: auto;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .cta-button {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 15px 20px;
        background: linear-gradient(135deg, #1a3a8f 0%, #0c2b70 100%);
        color: white;
        font-size: 16px;
        font-weight: bold;
        text-decoration: none;
        border-radius: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(26, 58, 143, 0.2);
        position: relative;
    }

    .cta-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(26, 58, 143, 0.3);
    }

    .arrow-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        background-color: #2e4ba0;
        border-radius: 50%;
        margin-left: 15px;
    }

    .arrow {
        width: 8px;
        height: 8px;
        border-style: solid;
        border-color: white;
        border-width: 2px 2px 0 0;
        transform: rotate(45deg);
        margin-left: -3px;
    }

    .guarantee {
        margin-top: 15px;
        color: #666;
        font-size: 12px;
        text-align: center;
    }

    /* Center button content */
    .gradient-button-content {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 100% !important;
    }

    .gradient-button-content div {
        flex: 1;
        text-align: center;
    }

    .gradient-button-content img {
        margin-left: auto;
    }
</style>


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
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        justify-content: center;
        gap: 25px;
        max-width: 1200px;
        width: 100%;
    }

    /* Plan Card - scoped under .pricing-container to avoid global .card conflicts */
    .pricing-container .plan-card {
        flex: 1;
        min-width: 250px;
        max-width: 300px;
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
        width: auto;
        min-height: auto;
        perspective: none;
    }

    .pricing-container .plan-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(12, 43, 112, 0.3);
    }

    /* Decorative Elements */
    .pricing-container .plan-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 60px;
        height: 60px;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 0 0 0 60px;
    }

    .pricing-container .plan-card::after {
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
    .pricing-container .plan-card .plan-card-header {
        text-align: center;
        margin-bottom: 20px;
        display: block;
        padding: 0;
        border: none;
        background: none;
    }

    .pricing-container .plan-card .plan-card-title {
        font-size: 18px;
        font-weight: 600;
        display: block;
        margin-bottom: 15px;
    }

    .pricing-container .plan-card .plan-card-price {
        font-size: 32px;
        font-weight: 800;
        position: relative;
        color: white;
    }

    .pricing-container .plan-card .plan-card-price::after {
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
    .pricing-container .plan-card .plan-card-body {
        flex: 1;
        text-align: center;
    }

    .pricing-container .plan-card .plan-card-body p {
        font-size: 14px;
        line-height: 1.5;
        color: rgba(255, 255, 255, 0.85);
    }

    /* Card Footer */
    .pricing-container .plan-card .plan-card-footer {
        margin-top: auto;
    }

    /* Highlighted Card */
    .pricing-container .plan-card.highlighted {
        background: linear-gradient(135deg, #1e429c 0%, #0d2d78 100%);
        transform: scale(1.03);
        box-shadow: 0 15px 35px rgba(12, 43, 112, 0.4);
        border: 2px solid rgba(255, 215, 138, 0.6);
    }

    .pricing-container .plan-card.highlighted::before {
        background-color: rgba(255, 215, 138, 0.2);
    }

    .pricing-container .plan-card.highlighted .plan-card-price {
        color: #f4d078;
        text-shadow: 0 0 10px rgba(255, 215, 138, 0.4);
    }

    .pricing-container .plan-card.highlighted .plan-card-price::after {
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
        
        .pricing-container .plan-card {
            max-width: 100%;
            width: 100%;
        }
        
        .pricing-container .plan-card.highlighted {
            order: -1;
            margin-bottom: 10px;
        }
    }
</style>

<section class="main-container-home" style="flex-direction: column !important;">
    <div class="cards-container-info" style="grid-template-columns: 1fr 1fr 1fr !important;">
        @foreach ($data as $category)
        
        <a href="{{ route('categorypages', $category->slug) }}">
            <div class="card-info js-card" style="width: 100% !important;">
                <div class="card__wrapper">
                    <div class="card__side-info is-active"
                        style="background-image: url('{{ asset('public/categories/' . $category->category_image . '') }}');  background-position: center;
                            height: 100%; background-repeat: no-repeat; background-size: 700px 665px;">
                        <div class="blue-overlay"></div> <!-- Blue overlay -->
                        <div class="center-text-info">
                            {{ $category->name }}
                        </div>
                    </div>
                    <div class="card__side-info nzvy card__side--back-info">

                        <p class="bc-title">{{ $category->name }}</p>
                        <p class="card-desc">{{ $category->description }}</p>
                    </div>
                </div>
            </div>
        </a>
        @endforeach

    </div>
      @if ($userSubscription)
        @else
    <div class="upgrade-card">

             <section class="hd-main-container pricing-section" >
                           <div class="pricing-container">
    <div class="pricing-body-header">
          <h1>Upgrade to Pro Membership</h1>
        <h2>Choose a Plan</h2>
    </div>
    <div class="pricing-body-plans">
        <div class="active" id="pricing__monthly__plan">
            <div class="plans-container">
                @foreach ($plans as $package)
                    <div class="plan-card">
                        <div class="plan-card-header">
                            <span class="plan-card-title">{{ $package->name }}</span>
                            <h2 class="plan-card-price">₦{{ number_format($package->price, 2) }}</h2>
                        </div>
                        <div class="plan-card-body">
                            <p>{{ $package->description }}</p>
                        </div>
                        <div class="plan-card-footer">
                            <form method="post" action="{{ route('subscribe_payment') }}">
                                @csrf
                                <input name="plan_id" type="hidden" value="{{ $package->id }}">
                                
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
    @endif
    <!-- <div class=""> -->
    @if ($userSubscription)
    @else
   
    @endif


</section>
</div>




<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        @if(!Session::has('disclaimer_accepted'))
        $('#disclaimerModal').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#disclaimerModal').modal('show');
        @endif

        // Handle SweetAlert messages
        @if(Session::has('sweetalert'))
        @php $alert = Session::get('sweetalert'); @endphp
        Swal.fire({
            icon: '{{ $alert["type"] }}',
            title: '{{ $alert["title"] }}',
            text: '{{ $alert["message"] }}',
            confirmButtonText: 'OK'
        });
        @endif
    });
</script>
@endsection



</body>

</html>