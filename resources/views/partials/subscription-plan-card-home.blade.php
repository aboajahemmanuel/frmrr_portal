@php
    $isComplimentary = (float) $package->price <= 0 && (float) ($package->price_usd ?? 0) <= 0;
@endphp
<div class="plan-card {{ ($highlight ?? false) ? 'highlighted' : '' }}">
    @if ($highlight ?? false)
        <span class="popular-badge">Best Value</span>
    @endif
    <div class="plan-card-header">
        <span class="plan-card-title">{{ $package->name }}</span>
        <h2 class="plan-card-price" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
            @if ($isComplimentary)
                <span>Complimentary</span>
            @else
                <!-- <span>
                    ₦{{ number_format($package->price, 2) }}
                </span>
                @if(isset($package->price_usd) && $package->price_usd > 0)
                    <span style="margin-top: 5px;">${{ number_format($package->price_usd, 2) }}</span>
                @endif -->
            @endif
        </h2>
    </div>
    <div class="plan-card-body">
        <p>{{ $package->description }}</p>
    </div>
    <div class="plan-card-footer">
        @if ($isComplimentary)
            <a href="{{ route('studentVerification.apply') }}" class="choose-button" style="width: 100%; display: block; text-align: center;">Apply Now</a>
            <span class="complimentary-note" style="margin-top: 10px;">Requires eligibility verification and FMDQ approval.</span>
        @else
            <form method="post" action="{{ route('subscribe_payment') }}">
                @csrf
                <input name="plan_id" type="hidden" value="{{ $package->id }}">
                @if(isset($package->price_usd) && $package->price_usd > 0)
                    <button type="submit" name="currency" value="NGN" class="choose-button mb-2" style="width: 100%; font-size: 15px;">Pay in Naira (₦{{ number_format($package->price ?? 0, 2) }})</button>
                    <button type="submit" name="currency" value="USD" class="choose-button" style="width: 100%; font-size: 15px;">Pay in USD (${{ number_format($package->price_usd, 2) }})</button>
                @else
                    <button type="submit" name="currency" value="NGN" class="choose-button mb-2" style="width: 100%; font-size: 15px;">Pay in Naira (₦{{ number_format($package->price ?? 0, 2) }})</button>
                @endif
            </form>
        @endif
    </div>
</div>
