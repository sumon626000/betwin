{{-- Footer / home provider logo strip (BET44-style partners grid) --}}
@php
    $footerProviders = collect(config('rapidverse_providers', []))
        ->filter(fn ($p) => !empty($p['logo']) && ($p['has_games'] ?? false))
        ->unique('vendor')
        ->take(24)
        ->values();
@endphp

@if ($footerProviders->isNotEmpty())
<section class="partner-logos" aria-label="@lang('Game Providers')">
    <div class="partner-logos__head">
        <span class="partner-logos__title"><i class="fas fa-building"></i> @lang('PROVIDERS')</span>
        <span class="partner-logos__sub">@lang('Trusted game studios')</span>
    </div>
    <div class="partner-logos__grid">
        @foreach ($footerProviders as $p)
            @php
                $slug = $p['slug'];
                $name = $p['name'];
                $logo = $p['logo'];
                $initials = strtoupper(mb_substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 2) ?: 'P');
            @endphp
            <button type="button"
                    class="partner-logos__item"
                    title="{{ $name }}"
                    onclick="typeof selectProvider==='function' && selectProvider('{{ $slug }}')">
                <img src="{{ $logo }}" alt="{{ $name }}" loading="lazy" referrerpolicy="no-referrer"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <span class="partner-logos__fallback" style="display:none;">{{ $initials }}</span>
            </button>
        @endforeach
    </div>
</section>
@endif
