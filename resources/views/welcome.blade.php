@extends('layouts.external')

@section('content')
<link href="{{ asset('public/admin/css/dashlite.css') }}" rel="stylesheet" type="text/css" />
<style>
    .upgrade-card {
        position: relative;
        width: 100%;
        height: fit-content;
        max-width: 420px;
        min-height: 520px;
        background: linear-gradient(135deg, #f8f9fd 0%, #eef1f8 100%);
        border-radius: 20px;
        padding: 40px 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
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
</style>

<section class="main-container-home">
    <div class="cards-container-info">
        @foreach ($data as $category)
        
        <a href="{{ route('categorypages', $category->slug) }}">
            <div class="card-info js-card">
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
      
        <div>
            <div class="card-header">
                <div class="header-text">
                    <h1>Upgrade to <span>Pro Membership</span></h1>
                </div>
                <div class="medal-icon">
                    <div class="medal-ribbon"></div>
                    <div class="medal-circle">
                        <div class="medal-crown"></div>
                        <div class="medal-shine"></div>
                    </div>
                </div>
            </div>

            <p class="value-prop">Unlock premium features and exclusive content</p>
            <!-- <p class="social-proof">Join 10,000+ members enjoying advanced benefits</p> -->

            <div class="features">
                <div class="feature-item">
                    <div class="feature-icon">✓</div>
                    <div class="feature-text">Advanced Search</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">✓</div>
                    <div class="feature-text">Priority Support</div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">✓</div>
                    <div class="feature-text">Exclusive Content</div>
                </div>
            </div>
        </div>
        <a href="{{ route('subscribe') }}">
            <div class="button-container-sb" style="margin-top: 50px !important;">
                <div class="gradient-buttons">
                    <div class="gradient-button-content">
                        <div>View Subscription Types</div>
                        <img src="{{ asset('public/users/assets/Arrow - Right.svg') }}" alt="FMDQ Logo" />
                    </div>
                </div>
            </div>
        </a>
        
    </div>
    </a>
    
    </div> 
    @endif
    <!-- <div class=""> -->
    @if ($userSubscription)
    @else
   
    @endif


</section>
</div>


{{-- <div class="modal fade" style="font-size: small !important;" tabindex="-1" id="disclaimerModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-body modal-xl " style="text-align: justify !important;">
                <div class="nk-modal">
                   
                    <h4 class="nk-modal-title">Legal Disclaimer </h4>
                    <div class="nk-modal-text tandt">
                        <li>
                            The content provided on this Portal is for general informational purposes only and does not constitute legal, financial, or professional advice
                        </li>
                        <div>
                            <br>
                            <li>
                                While FMDQ Securities Exchange Limited (“<b>FMDQ Exchange</b>”) endeavours to ensure the accuracy, completeness, and timeliness of the information presented, <span style="margin-left: 20px;"> FMDQ Exchange </span> <br>
                                <ul style="padding-left: 20px;">
                                    
                                    <li style="list-style-type: '- ' !important; margin-left: 20px !important;">does not warrant or guarantee accuracy, completeness, or reliability of the information</li>
                                    <li style="list-style-type: '- ' !important; margin-left: 20px !important;">shall not be held liable for any errors, omissions, or reliance placed on the information/materials available on this Portal</li>
                                    <li style="list-style-type: '- ' !important; margin-left: 20px !important;">reserves the right to modify, update, or remove any content on this Portal at its discretion and without prior notice</li>
                                    <li style="list-style-type: '- ' !important; margin-left: 20px !important;">disclaims all liability for any losses or damages, whether direct, indirect, or consequential, arising from the use of, or inability to use, the Portal or its content</li>
                                </ul>

                            </li>
                            
                            
                        </div><br>
                        <li>
                                By accessing and using this Portal, you agree to these terms and assume full responsibility for your use of the information provided herein
                            </li>
                        <br>
                        <li>
                            By clicking “Accept” below, you acknowledge that you have read, understood, and agree to be bound by the above terms and conditions
                        </li>
                    </div>

                </div>
            </div>
            <div class="modal-footer bg-lighter" style="padding: 0px 10px !important;">
                <div class="nk-modal-action" style="margin: 0px !important;">
                    <form method="POST" action="{{ route('disclaimer.accept') }}" id="disclaimerForm">
                        @csrf
                        <button type="submit" class=""
                            style="border: none !important; outline: none !important;">
                            <div class="button-container-sb">
                                <div class="gradient-buttons">
                                    <div class="gradient-button-content"
                                        style="padding: 10px 16px !important; gap: 7px;">
                                        <div>Accept</div>
                                       
                                        <img src="{{ asset('public/users/assets/Arrow - Right.svg') }}"
                                            alt="FMDQ Logo" />
                                    </div>
                                </div>
                            </div>
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}

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
    });
</script>
@endsection
{{-- <script>
    $(window).load('load', function() {
        // Preloader
        $('.loader').fadeOut();
        $('.loader-mask').delay(250).fadeOut('slow');
    });
</script>
<script>
    window.onload = function() {
        // https://getbootstrap.com/docs/5.0/components/modal/#via-javascript
        let myModal = new bootstrap.Modal(
            document.getElementById("myModal"), {}
        );
        // https://getbootstrap.com/docs/5.0/components/modal/#show
        myModal.show();
    };
</script> --}}


</body>

</html>