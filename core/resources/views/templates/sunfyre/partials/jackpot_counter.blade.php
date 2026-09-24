{{-- BET44-style mega jackpot counter --}}
@php
    // Cosmetic rolling jackpot (display only). Seed so it feels live.
    $jpBase = 109212560.50;
@endphp
<section class="jp-banner home-row" id="jp-banner" aria-label="@lang('Jackpot')">
    <div class="jp-banner__glow" aria-hidden="true"></div>
    <div class="jp-banner__inner">
        <div class="jp-banner__left">
            <span class="jp-banner__badge"><i class="fas fa-crown"></i> @lang('MEGA JACKPOT')</span>
            <p class="jp-banner__hint">@lang('Play & win — live pool')</p>
        </div>
        <div class="jp-banner__amount" id="jp-amount"
             data-base="{{ $jpBase }}"
             aria-live="polite">
            <span class="jp-banner__currency">৳</span>
            <span class="jp-banner__digits" id="jp-digits">109,212,560.50</span>
        </div>
        <div class="jp-banner__shine" aria-hidden="true"></div>
    </div>
</section>
