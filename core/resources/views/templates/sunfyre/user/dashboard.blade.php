@extends($activeTemplate . 'layouts.master')
@section('content')

@php
    $apiControls = App\Models\GeneralSetting::first()->whereNotNull('id')->get();
    $gameStatus = Illuminate\Support\Facades\DB::table('api_game_controls')->get()->keyBy('slug');
@endphp

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="referrer" content="no-referrer">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

<style>
    :root {
        --bg-deep:    #e8f0fa;
        --bg-main:    #f5f7fa;
        --bg-card:    #ffffff;
        --bg-card2:   #ffffff;
        --teal:       #2563eb;
        --teal-light: #2563eb;
        --gold:       #f4b942;
        --gold-dark:  #d9a12a;
        --gold-text:  #123b66;
        --green-btn:  #2563eb;
        --text-main:  #172033;
        --text-muted: #6b7280;
        --border:     rgba(18,59,102,0.1);
        --glass:      rgba(255, 255, 255, 0.9);
        --header-color: #123b66;
        --accent-color: #2563eb;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        background: var(--bg-deep);
        color: var(--text-main);
        padding-bottom: 70px;
        min-height: 100vh;
        -webkit-tap-highlight-color: transparent;
        user-select: none;
        background-image: none;
    }

    .custom-home-wrapper {
        margin: 0 !important;
        padding: 0 !important;
        width: 100%;
    }

    /* â”€â”€â”€ ANNOUNCEMENT BAR â”€â”€â”€ */
    .announce-bar {
        background: rgba(255,255,255,0.04); border-bottom: 1px solid var(--border);
        padding: 7px 14px; display: flex; align-items: center; gap: 8px;
    }
    .announce-bar .ann-icon { font-size: 14px; color: var(--gold-text); flex-shrink: 0; }
    .announce-bar marquee { font-size: 12px; color: var(--text-muted); font-weight: 500; }

    /* Offer banner styles live in theme.css (.ob) */
    .slider-wrap { padding: 10px 10px 6px; }
    .mainSlider img { display: none !important; }

    /* â”€â”€â”€ DEPOSIT & WITHDRAW â”€â”€â”€ */
    .quick-actions { display: flex; gap: 10px; padding: 10px 10px 4px; }
    .qa-btn {
        flex: 1; display: flex; align-items: center; justify-content: center;
        gap: 8px; padding: 13px 10px; border-radius: 10px;
        font-size: 14px; font-weight: 800; text-decoration: none;
        transition: all 0.15s; cursor: pointer; border: none;
    }
    .qa-btn i { font-size: 16px; }
    .qa-btn.deposit {
        background: linear-gradient(180deg, #3b82f6 0%, #2563eb 60%, #1d4ed8 100%);
        color: #fff; box-shadow: 0 4px 0 #1e3a8a, 0 4px 12px rgba(37,99,235,0.4);
        border-bottom: 2px solid #60a5fa;
    }
    .qa-btn.deposit:active { transform: translateY(3px); box-shadow: 0 1px 0 #1e3a8a; }
    .qa-btn.withdraw {
        background: linear-gradient(180deg, #ffe066 0%, #f0c030 60%, #c89a10 100%);
        color: #2a1500; box-shadow: 0 4px 0 #8a6a00, 0 4px 12px rgba(240,192,48,0.3);
        border-bottom: 2px solid #ffe57a;
    }
    .qa-btn.withdraw:active { transform: translateY(3px); box-shadow: 0 1px 0 #8a6a00; }

    /* â”€â”€â”€ SECTION HEADER â”€â”€â”€ */
    .sec-header { display: flex; align-items: center; justify-content: space-between; padding: 14px 12px 8px; }
    .sec-title {
        display: flex; align-items: center; gap: 8px; font-size: 16px; font-weight: 800;
        color: var(--gold-text); text-transform: uppercase; letter-spacing: 0.5px;
    }
    .sec-title i { font-size: 18px; color: var(--gold-text); }

    .btn-see-all {
        padding: 6px 13px; border-radius: 7px; font-size: 12px; font-weight: 800;
        color: var(--gold-text);
        background: linear-gradient(180deg, rgba(255,220,70,0.18) 0%, rgba(240,192,48,0.10) 100%);
        border: 1px solid rgba(240,192,48,0.4); border-bottom: 2px solid rgba(255,220,80,0.6);
        text-decoration: none; transition: all 0.15s; box-shadow: 0 3px 0 rgba(0,0,0,0.3);
        display: inline-flex; align-items: center;
    }
    .btn-see-all:active { transform: translateY(2px); box-shadow: 0 1px 0 rgba(0,0,0,0.3); }

    /* â”€â”€â”€ GAME GRID â”€â”€â”€ */
    .games-section { padding: 0 10px; margin-bottom: 6px; }
    /* Match ZIP icon grid */
    .game-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
    @media (min-width: 600px) { .game-grid { grid-template-columns: repeat(4, 1fr); } }
    @media (min-width: 900px) {
        .game-grid { grid-template-columns: repeat(6, 1fr); }
    }
    .game-grid .swiper-slide,
    .game-grid .game-item-box {
        width: 100% !important;
        max-width: none !important;
        margin: 0 !important;
        height: auto !important;
    }

    .game-card {
        position: relative; border-radius: 10px; overflow: hidden; background: #ffffff;
        border: 1px solid #e8f0fa; transition: all 0.2s; text-decoration: none; display: block;
        box-shadow: 0 4px 14px rgba(18,59,102,0.08);
    }
    .game-card:active { transform: scale(0.96); }
    /* ZIP: square icon fills the card */
    .game-card-img { width: 100%; aspect-ratio: 1; object-fit: cover; display: block; overflow: hidden; }
    .game-card-img img {
        width: 100%; height: 100%; object-fit: cover; object-position: center;
        display: block;
    }

    .game-card-fav {
        position: absolute; top: 5px; right: 5px; width: 26px; height: 26px;
        border-radius: 50%; background: rgba(0,0,0,0.45); backdrop-filter: blur(4px);
        display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,0.6); font-size: 13px; cursor: pointer;
        z-index: 5; transition: color 0.2s, background 0.2s; border: none; flex-shrink: 0;
    }
    .game-card-fav.active { color: var(--gold-text); background: rgba(240,192,48,0.18); }

    .game-card-name {
        font-size: 10px; font-weight: 700; color: var(--text-main); padding: 5px 6px;
        text-align: center; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    /* â”€â”€â”€ STATUS TAGS â”€â”€â”€ */
    .game-card[data-status="2"]::before {
        content: 'à¦•à¦¾à¦œ à¦šà¦²à¦›à§‡';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.75);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 5;
        backdrop-filter: blur(2px);
        color: #ffffff;
        font-weight: 700;
        font-size: 13px;
        border: 1px solid rgba(211, 47, 47, 0.5);
        border-radius: 10px;
    }

    .game-card[data-status="0"]::before,
    .game-card[data-status="3"]::before {
        content: 'à¦¶à§€à¦˜à§à¦°à¦‡ à¦†à¦¸à¦›à§‡';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.75);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 5;
        backdrop-filter: blur(2px);
        color: #ff9800;
        font-weight: 700;
        font-size: 13px;
        border: 1px solid rgba(245, 127, 23, 0.5);
        border-radius: 10px;
    }

    /* â”€â”€â”€ CATEGORY NAV â”€â”€â”€ */
    .cat-nav-wrap {
        padding: 12px 12px 0; overflow-x: auto; white-space: nowrap;
        scrollbar-width: none; -ms-overflow-style: none;
    }
    .cat-nav-wrap::-webkit-scrollbar { display: none; }
    .cat-nav-inner { display: inline-flex; gap: 8px; padding-bottom: 10px; }
    .cat-pill {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 16px; border-radius: 999px; font-size: 13px; font-weight: 700;
        text-decoration: none; color: #6b7280;
        background: #ffffff; border: 1px solid #e8f0fa;
        transition: background 0.15s ease, color 0.15s ease, border-color 0.15s ease;
        white-space: nowrap; cursor: pointer; box-shadow: none;
    }
    .cat-pill i { font-size: 13px; color: #2563eb; }
    .cat-pill.active,
    .cat-pill:active {
        background: #2563eb; border-color: #2563eb; color: #ffffff;
        box-shadow: none;
    }
    .cat-pill.active i,
    .cat-pill:active i { color: #ffffff; }

    /* Category section */
    .cat-section {
        margin-bottom: 6px; background: rgba(18,59,102,0.06);
        border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); padding-bottom: 8px;
    }

    /* â”€â”€â”€ PROVIDER GRID â”€â”€â”€ */
    .provider-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        padding: 0 10px;
    }
    @media (min-width: 600px) {
        .provider-grid { grid-template-columns: repeat(4, 1fr); }
    }
    @media (min-width: 900px) {
        .provider-grid { grid-template-columns: repeat(5, 1fr); }
    }

    .provider-card {
        background: var(--bg-card);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 12px;
        padding: 16px 8px;
        aspect-ratio: 1 / 0.9;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        box-shadow: 0 4px 0 rgba(0,0,0,0.3);
    }
    .provider-card:active {
        transform: scale(0.94) translateY(3px);
        border-color: var(--teal-light);
        box-shadow: 0 1px 0 rgba(0,0,0,0.3);
    }
    .provider-card img {
        height: 32px;
        max-width: 75%;
        object-fit: contain;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.4));
    }
    .provider-card i {
        font-size: 28px;
        color: var(--text-main);
    }
    .provider-card span {
        color: var(--text-main);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        text-align: center;
        line-height: 1.2;
    }

    /* â”€â”€â”€ GAME CENTER â”€â”€â”€ */
    .game-center { padding: 8px 12px 16px; }
    .game-center-title { font-size: 18px; font-weight: 800; color: var(--gold-text); margin-bottom: 12px; }
    .game-center-pills { display: flex; flex-wrap: wrap; gap: 8px; }
    .gc-pill {
        padding: 8px 18px; border-radius: 8px; font-size: 13px; font-weight: 700;
        color: var(--teal-light); border: 1px solid rgba(37,99,235,0.4);
        border-bottom: 2px solid rgba(37,99,235,0.45);
        background: linear-gradient(180deg, rgba(37,99,235,0.12) 0%, rgba(37,99,235,0.06) 100%);
        text-decoration: none; transition: all 0.15s; box-shadow: 0 3px 0 rgba(0,0,0,0.3);
    }
    .gc-pill:active { transform: translateY(2px); box-shadow: 0 1px 0 rgba(0,0,0,0.3); }

    /* â”€â”€â”€ BOTTOM NAV â”€â”€â”€ */
    .bottom-nav-container {
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        max-width: 480px;
        z-index: 10000;
        padding: 0 10px 8px 10px;
    }

    .bottom-nav {
        width: 100%;
        height: 58px;
        background: #ffffff;
        border-radius: 999px;
        border: 1px solid #e8f0fa;
        box-shadow:
            0 0 0 2px #e8f0fa,
            inset 0 1px 0 rgba(37,99,235,0.12),
            0 8px 24px rgba(18,59,102,0.12),
            0 4px 24px rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: space-around;
        padding: 0 6px;
        position: relative;
    }

    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 3px;
        flex: 1;
        text-decoration: none !important;
        color: #6b7280;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.2px;
        padding: 6px 0;
        transition: color 0.2s;
        position: relative;
    }

    .nav-item.active { color: #f5c518; }

    .nav-item i { font-size: 20px; }

    .nav-item span { font-size: 10px; font-weight: 700; }

    .center-item {
        position: relative;
        flex: 1;
        justify-content: flex-end;
        padding-bottom: 0;
    }

    .center-icon-circle {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: linear-gradient(145deg, #2563eb, #123b66);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow:
            0 0 0 3px #e8f0fa,
            0 0 0 5px #2563eb,
            0 6px 20px rgba(37,99,235,0.45);
        font-size: 22px;
        color: #fff;
        margin-top: -18px;
        border: none;
    }

    .section-container { transition: opacity 0.3s ease; }
    .section-container.show-anim { animation: softFade 0.4s ease forwards; }

    @keyframes softFade {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .game-tag, .game-item__title, h4 { display: none !important; }
    .main-footer-section { margin-top: 25px; padding-bottom: 20px; }

    /* â”€â”€â”€ DESKTOP â”€â”€â”€ */
    @media (min-width: 900px) {
        .bottom-nav-container { max-width: 600px; }
    }
</style>

<div class="custom-home-wrapper">

<!-- ANNOUNCEMENT -->
<div class="announce-bar">
    <i class="fas fa-bullhorn ann-icon"></i>
    <marquee scrollamount="4">{{ gs('announcement_text') }}</marquee>
</div>

<!-- OFFER BANNERS -->
@include($activeTemplate . 'partials.offer_banners')

        <div class="swiper-pagination" style="bottom:10px"></div>
    </div>
</div>

<!-- DEPOSIT & WITHDRAW -->
<div class="quick-actions">
    <a href="{{ route('user.deposit.index') }}" class="qa-btn deposit">
        <i class="fas fa-plus-circle"></i> @lang('Deposit')
    </a>
    <a href="{{ route('user.withdraw') }}" class="qa-btn withdraw">
        <i class="fas fa-arrow-up-from-bracket"></i> @lang('Withdraw')
    </a>
</div>

<!-- CATEGORY PILLS -->
<nav class="cat-nav-wrap">
    <div class="cat-nav-inner">
        <a href="javascript:void(0)" class="cat-pill active" onclick="filterGames('hot', this)">
            <i class="fas fa-fire"></i> @lang('HOT')
        </a>
        <a href="javascript:void(0)" class="cat-pill" onclick="filterGames('sports', this)">
            <i class="fas fa-futbol"></i> @lang('SPORTS')
        </a>
        <a href="javascript:void(0)" class="cat-pill" onclick="filterGames('crash', this)">
            <i class="fas fa-chart-line"></i> @lang('CRASH')
        </a>
        <a href="javascript:void(0)" class="cat-pill" onclick="filterGames('slot', this)">
            <i class="fas fa-dice"></i> @lang('SLOT')
        </a>
        <a href="javascript:void(0)" class="cat-pill" onclick="filterGames('casino', this)">
            <i class="fas fa-video"></i> @lang('CASINO')
        </a>
        <a href="javascript:void(0)" class="cat-pill" onclick="filterGames('table', this)">
            <i class="fas fa-table"></i> @lang('TABLE')
        </a>
        <a href="javascript:void(0)" class="cat-pill" onclick="filterGames('fishing', this)">
            <i class="fas fa-fish"></i> @lang('FISHING')
        </a>
        <a href="javascript:void(0)" class="cat-pill" onclick="filterGames('poker', this)">
            <i class="fas fa-chess"></i> @lang('POKER')
        </a>
    </div>
</nav>

<!-- GAMES SECTIONS -->
<div id="gamesSections">
    <div class="section-container" data-provider="hot">
        <div class="sec-header">
            <div class="sec-title"><i class="fas fa-fire"></i> @lang('HOT GAMES')</div>
            <a href="javascript:void(0)" class="btn-see-all" onclick="seeAll('hot')" style="display:none;">@lang('See All')</a>
        </div>
        <div class="games-section" id="hot-wrapper" data-status="1">
            <div class="game-grid">@include($activeTemplate . 'partials.hot-games')</div>
        </div>
    </div>

    <div class="section-container" data-provider="sports" style="display:none;">
        <div class="sec-header">
            <div class="sec-title"><i class="fas fa-futbol"></i> @lang('SPORTS')</div>
            <a href="javascript:void(0)" class="btn-see-all" onclick="seeAll('sports')">@lang('See All')</a>
        </div>
        <div class="games-section" id="sports-wrapper" data-status="1">
            <div class="game-grid">@include($activeTemplate . 'partials.sports-games')</div>
        </div>
    </div>

    <div class="section-container" data-provider="crash" style="display:none;">
        <div class="sec-header">
            <div class="sec-title"><i class="fas fa-chart-line"></i> @lang('CRASH GAMES')</div>
            <a href="javascript:void(0)" class="btn-see-all" onclick="seeAll('crash')">@lang('See All')</a>
        </div>
        <div class="games-section" id="crash-wrapper" data-status="1">
            <div class="game-grid">@include($activeTemplate . 'partials.crash-games')</div>
        </div>
    </div>

    <div class="section-container" data-provider="casino" style="display:none;">
        <div class="sec-header">
            <div class="sec-title"><i class="fas fa-video"></i> @lang('CASINO')</div>
            <a href="javascript:void(0)" class="btn-see-all" onclick="seeAll('casino')">@lang('See All')</a>
        </div>
        <div class="games-section" id="casino-wrapper" data-status="{{ isset($gameStatus['evo']) ? $gameStatus['evo']->status : 1 }}">
            <div class="game-grid">@include($activeTemplate . 'partials.evo-games')</div>
        </div>
    </div>

    <div id="provider-grid-container" style="display:none;">
        <div class="sec-header">
            <div class="sec-title"><i class="fas fa-dice"></i> <span id="provider-grid-title">@lang('PROVIDERS')</span></div>
        </div>
        @include($activeTemplate . 'partials.provider-grid')
    </div>

    <div class="section-container" data-provider="__dynamic__" id="provider-dynamic-section" style="display:none;">
        <div class="sec-header">
            <a href="javascript:void(0)" class="btn-see-all" onclick="backToProviders()"><i class="fas fa-arrow-left"></i> Back</a>
            <div class="sec-title"><i class="fas fa-gamepad"></i> <span id="provider-dynamic-title">Provider</span></div>
        </div>
        <div class="games-section">
            <div class="game-grid" id="provider-dynamic-grid"></div>
        </div>
        <div id="provider-dynamic-empty" style="display:none;background:#fff;border-radius:12px;padding:28px 18px;text-align:center;color:#6b7280;">
            <p style="margin:0 0 8px;font-weight:700;color:#123b66;">@lang('Games list syncing')</p>
            <p style="margin:0;font-size:13px;">@lang('This provider is enabled from RapidVerse. Full game icons will appear after game-list import.')</p>
        </div>
    </div>

    @if(isset($gameStatus['jili']) && $gameStatus['jili']->status != 0)
    <div class="section-container" data-provider="jili" style="display:none;">
        <div class="sec-header">
            <a href="javascript:void(0)" class="btn-see-all" onclick="backToProviders()"><i class="fas fa-arrow-left"></i> Back</a>
            <div class="sec-title"><i class="fas fa-fire"></i> JILI GAMES</div>
        </div>
        <div class="games-section" id="jili-wrapper" data-status="{{ $gameStatus['jili']->status }}">
            <div class="game-grid">@include($activeTemplate . 'partials.jili-games')</div>
        </div>
    </div>
    @endif

    @if(isset($gameStatus['pg']) && $gameStatus['pg']->status != 0)
    <div class="section-container" data-provider="pg" style="display:none;">
        <div class="sec-header">
            <a href="javascript:void(0)" class="btn-see-all" onclick="backToProviders()"><i class="fas fa-arrow-left"></i> Back</a>
            <div class="sec-title"><i class="fas fa-fire"></i> PG SOFT</div>
        </div>
        <div class="games-section" id="pg-wrapper" data-status="{{ $gameStatus['pg']->status }}">
            <div class="game-grid">@include($activeTemplate . 'partials.pg-games')</div>
        </div>
    </div>
    @endif

    @if(isset($gameStatus['jdb']) && $gameStatus['jdb']->status != 0)
    <div class="section-container" data-provider="jdb" style="display:none;">
        <div class="sec-header">
            <a href="javascript:void(0)" class="btn-see-all" onclick="backToProviders()"><i class="fas fa-arrow-left"></i> Back</a>
            <div class="sec-title"><i class="fas fa-fire"></i> JDB GAMES</div>
        </div>
        <div class="games-section" id="jdb-wrapper" data-status="{{ $gameStatus['jdb']->status }}">
            <div class="game-grid">@include($activeTemplate . 'partials.jdb-games')</div>
        </div>
    </div>
    @endif

    @if(isset($gameStatus['cq9']) && $gameStatus['cq9']->status != 0)
    <div class="section-container" data-provider="cq9" style="display:none;">
        <div class="sec-header">
            <a href="javascript:void(0)" class="btn-see-all" onclick="backToProviders()"><i class="fas fa-arrow-left"></i> Back</a>
            <div class="sec-title"><i class="fas fa-fire"></i> CQ9 GAMES</div>
        </div>
        <div class="games-section" id="cq9-wrapper" data-status="{{ $gameStatus['cq9']->status }}">
            <div class="game-grid">@include($activeTemplate . 'partials.cq9-games')</div>
        </div>
    </div>
    @endif

    @if(isset($gameStatus['idg']) && $gameStatus['idg']->status != 0)
    <div class="section-container" data-provider="idg" style="display:none;">
        <div class="sec-header">
            <a href="javascript:void(0)" class="btn-see-all" onclick="backToProviders()"><i class="fas fa-arrow-left"></i> Back</a>
            <div class="sec-title"><i class="fas fa-fire"></i> IDG GAMES</div>
        </div>
        <div class="games-section" id="idg-wrapper" data-status="{{ $gameStatus['idg']->status }}">
            <div class="game-grid">@include($activeTemplate . 'partials.idg-games')</div>
        </div>
    </div>
    @endif

    @if(isset($gameStatus['km']) && $gameStatus['km']->status != 0)
    <div class="section-container" data-provider="km" style="display:none;">
        <div class="sec-header">
            <a href="javascript:void(0)" class="btn-see-all" onclick="backToProviders()"><i class="fas fa-arrow-left"></i> Back</a>
            <div class="sec-title"><i class="fas fa-fire"></i> KM GAMES</div>
        </div>
        <div class="games-section" id="km-wrapper" data-status="{{ $gameStatus['km']->status }}">
            <div class="game-grid">@include($activeTemplate . 'partials.km-games')</div>
        </div>
    </div>
    @endif

    @if(isset($gameStatus['v8']) && $gameStatus['v8']->status != 0)
    <div class="section-container" data-provider="v8" style="display:none;">
        <div class="sec-header">
            <a href="javascript:void(0)" class="btn-see-all" onclick="backToProviders()"><i class="fas fa-arrow-left"></i> Back</a>
            <div class="sec-title"><i class="fas fa-fire"></i> V8 GAMES</div>
        </div>
        <div class="games-section" id="v8-wrapper" data-status="{{ $gameStatus['v8']->status }}">
            <div class="game-grid">@include($activeTemplate . 'partials.v8-games')</div>
        </div>
    </div>
    @endif

    @if(isset($gameStatus['mg']) && $gameStatus['mg']->status != 0)
    <div class="section-container" data-provider="mg" style="display:none;">
        <div class="sec-header">
            <a href="javascript:void(0)" class="btn-see-all" onclick="backToProviders()"><i class="fas fa-arrow-left"></i> Back</a>
            <div class="sec-title"><i class="fas fa-fire"></i> MG GAMES</div>
        </div>
        <div class="games-section" id="mg-wrapper" data-status="{{ $gameStatus['mg']->status }}">
            <div class="game-grid">@include($activeTemplate . 'partials.mg-games')</div>
        </div>
    </div>
    @endif

    @if(isset($gameStatus['g9']) && $gameStatus['g9']->status != 0)
    <div class="section-container" data-provider="g9" style="display:none;">
        <div class="sec-header">
            <a href="javascript:void(0)" class="btn-see-all" onclick="backToProviders()"><i class="fas fa-arrow-left"></i> Back</a>
            <div class="sec-title"><i class="fas fa-fire"></i> G9 GAMES</div>
        </div>
        <div class="games-section" id="g9-wrapper" data-status="{{ $gameStatus['g9']->status }}">
            <div class="game-grid">@include($activeTemplate . 'partials.g9-games')</div>
        </div>
    </div>
    @endif

    @if(isset($gameStatus['card365']) && $gameStatus['card365']->status != 0)
    <div class="section-container" data-provider="card365" style="display:none;">
        <div class="sec-header">
            <a href="javascript:void(0)" class="btn-see-all" onclick="backToProviders()"><i class="fas fa-arrow-left"></i> Back</a>
            <div class="sec-title"><i class="fas fa-fire"></i> CARD365</div>
        </div>
        <div class="games-section" id="card365-wrapper" data-status="{{ $gameStatus['card365']->status }}">
            <div class="game-grid">@include($activeTemplate . 'partials.card365-games')</div>
        </div>
    </div>
    @endif

    @if(isset($gameStatus['evo']) && $gameStatus['evo']->status != 0)
    <div class="section-container" data-provider="evo" style="display:none;">
        <div class="sec-header">
            <a href="javascript:void(0)" class="btn-see-all" onclick="backToProviders()"><i class="fas fa-arrow-left"></i> Back</a>
            <div class="sec-title"><i class="fas fa-fire"></i> EVOLUTION</div>
        </div>
        <div class="games-section" id="evo-slot-wrapper" data-status="{{ $gameStatus['evo']->status }}">
            <div class="game-grid">@include($activeTemplate . 'partials.evo-games')</div>
        </div>
    </div>
    @endif
</div>

<!-- GAME CENTER -->
<div class="game-center">
    <div class="game-center-title">@lang('Game Center')</div>
    <div class="game-center-pills">
        <a href="#" class="gc-pill">@lang('Slots')</a>
        <a href="#" class="gc-pill">@lang('Live Casino')</a>
        <a href="#" class="gc-pill">@lang('Sports')</a>
        <a href="#" class="gc-pill">@lang('E-sports')</a>
        <a href="#" class="gc-pill">@lang('Poker')</a>
        <a href="#" class="gc-pill">@lang('Fish')</a>
        <a href="#" class="gc-pill">@lang('Lottery')</a>
    </div>
</div>

<div class="main-footer-section">
    @include($activeTemplate . 'partials.footer')
</div>

</div><!-- /custom-home-wrapper -->

@include($activeTemplate . 'partials.mobile_bottom_nav')

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

@endsection

@push('script')
<script>
    new Swiper('.mainSlider', {
        loop: true,
        autoplay: { delay: 3500, disableOnInteraction: false },
        pagination: { el: '.swiper-pagination', clickable: true }
    });

    const providerGridEl = document.querySelector('#provider-grid-container .provider-grid');
    const originalProviderCards = providerGridEl ? Array.from(providerGridEl.children) : [];
    const providerTitleMap = {
        slot: 'SLOT PROVIDERS',
        casino: 'LIVE CASINO',
        table: 'TABLE / MINI',
        fishing: 'FISHING',
        poker: 'CARD / POKER',
        sports: 'SPORTS PROVIDERS',
        crash: 'CRASH / MINI'
    };

    function shuffleArray(arr) {
        for (let i = arr.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [arr[i], arr[j]] = [arr[j], arr[i]];
        }
        return arr;
    }

    function setProviderTitle(category) {
        const el = document.getElementById('provider-grid-title');
        if (el) el.textContent = providerTitleMap[category] || 'PROVIDERS';
    }

    function showProviderGridByType(types) {
        if (!providerGridEl) return;
        const want = Array.isArray(types) ? types : [types];
        let visible = 0;
        originalProviderCards.forEach(card => {
            const ok = want.includes(card.dataset.type);
            card.style.display = ok ? '' : 'none';
            if (ok) visible++;
        });
        // if none match, show all
        if (visible === 0) {
            originalProviderCards.forEach(card => { card.style.display = ''; });
        }
        document.getElementById('provider-grid-container').style.display = 'block';
    }

    function resetProviderGrid() {
        if (!providerGridEl) return;
        originalProviderCards.forEach(card => {
            card.style.display = '';
            providerGridEl.appendChild(card);
        });
    }

    function showRandomProviderGrid(excludeKeys) {
        if (!providerGridEl) return;
        let visibleCards = originalProviderCards.filter(card => !excludeKeys.includes(card.dataset.key));
        shuffleArray(visibleCards);
        visibleCards.forEach(card => {
            card.style.display = '';
            providerGridEl.appendChild(card);
        });
        originalProviderCards.forEach(card => {
            if (excludeKeys.includes(card.dataset.key)) {
                card.style.display = 'none';
            }
        });
    }

    function filterGames(category, btn) {
        document.querySelectorAll('.cat-pill').forEach(el => el.classList.remove('active'));
        btn.classList.add('active');

        requestAnimationFrame(() => {
            const sections = document.querySelectorAll('.section-container');
            sections.forEach(s => { s.style.display = 'none'; s.classList.remove('show-anim'); });
            document.getElementById('provider-grid-container').style.display = 'none';
            document.querySelector('.main-footer-section').style.display = 'none';
            document.querySelector('.game-center').style.display = 'none';

            if (category === 'slot') {
                setProviderTitle('slot');
                showProviderGridByType('slot');
                return;
            }
            if (category === 'casino') {
                setProviderTitle('casino');
                showProviderGridByType('casino');
                return;
            }
            if (category === 'table') {
                setProviderTitle('table');
                showProviderGridByType(['table', 'cockfight']);
                return;
            }
            if (category === 'fishing') {
                setProviderTitle('fishing');
                showProviderGridByType('fishing');
                return;
            }
            if (category === 'poker') {
                setProviderTitle('poker');
                showProviderGridByType('poker');
                return;
            }

            let target = document.querySelector('.section-container[data-provider="' + category + '"]');
            if (target) {
                target.style.display = 'block';
                target.classList.add('show-anim');
                document.querySelector('.main-footer-section').style.display = 'block';
                document.querySelector('.game-center').style.display = 'block';
            }
        });
    }

    const RV_LOGGED_IN = @json(auth()->check());
    const RV_LOGIN_URL = @json(route('user.login'));
    const RV_LAUNCH_BASE = @json(url('user/jili/launch'));
    const rvGamesCache = {};

    function rvEsc(s) {
        return String(s || '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    }

    function rvGameImg(game, vendor) {
        if (game.img) return game.img;
        const code = game.code;
        if (code !== undefined && code !== null && code !== '' && !isNaN(Number(code))) {
            const folder = (vendor || game.vendor || 'JILI').toUpperCase();
            return 'https://ossimg.91admin123admin.com/91club/gamelogo/' + folder + '/' + parseInt(code, 10) + '.png';
        }
        return '';
    }

    function renderDynamicGames(provider, games, title) {
        const grid = document.getElementById('provider-dynamic-grid');
        const empty = document.getElementById('provider-dynamic-empty');
        const section = document.getElementById('provider-dynamic-section');
        document.getElementById('provider-dynamic-title').textContent = (title || provider).toUpperCase();
        grid.innerHTML = '';
        if (!games || !games.length) {
            grid.style.display = 'none';
            empty.style.display = 'block';
        } else {
            empty.style.display = 'none';
            grid.style.display = '';
            const card = document.querySelector('.provider-card[data-key="' + provider + '"]');
            const vendor = (card && card.dataset.vendor) || (games[0] && games[0].vendor) || provider.toUpperCase();
            const frag = document.createDocumentFragment();
            games.forEach(game => {
                const id = game.id || game.gameID;
                if (!id) return;
                const name = game.name || game.gameNameEn || 'Game';
                const img = rvGameImg(game, vendor);
                const href = RV_LOGGED_IN
                    ? (RV_LAUNCH_BASE + '?game_code=' + encodeURIComponent(id) + '&provider=' + encodeURIComponent(provider))
                    : RV_LOGIN_URL;
                const el = document.createElement('div');
                el.className = 'game-card';
                el.dataset.status = '1';
                const imgHtml = img
                    ? '<img class="game-card-img" src="' + rvEsc(img) + '" alt="' + rvEsc(name) + '" loading="lazy" referrerpolicy="no-referrer" onerror="this.style.display=\'none\';this.nextElementSibling&&(this.nextElementSibling.style.display=\'flex\');">'
                    : '';
                const fallback = '<div class="game-card-img" style="' + (img ? 'display:none;' : 'display:flex;') + 'align-items:center;justify-content:center;background:#123b66;color:#fff;font-weight:800;font-size:18px;">' + rvEsc((name || 'G').charAt(0).toUpperCase()) + '</div>';
                el.innerHTML = '<a href="' + rvEsc(href) + '" class="game-card-img" title="' + rvEsc(name) + '" style="display:block;position:relative;">' + imgHtml + fallback + '</a><div class="game-card-name">' + rvEsc(name) + '</div>';
                frag.appendChild(el);
            });
            grid.appendChild(frag);
        }
        section.style.display = 'block';
        section.classList.add('show-anim');
    }

    async function selectProvider(provider) {
        document.getElementById('provider-grid-container').style.display = 'none';
        document.querySelectorAll('.section-container').forEach(s => { s.style.display = 'none'; s.classList.remove('show-anim'); });
        document.querySelector('.main-footer-section').style.display = 'none';
        document.querySelector('.game-center').style.display = 'none';

        const card = document.querySelector('.provider-card[data-key="' + provider + '"]');
        const name = card ? (card.querySelector('span')?.textContent || provider) : provider;

        try {
            if (!rvGamesCache[provider]) {
                const res = await fetch('/games/' + encodeURIComponent(provider) + '.json?v=2', { cache: 'no-store' });
                if (res.ok) {
                    const data = await res.json();
                    rvGamesCache[provider] = Array.isArray(data) ? data : (data.gameLists || []);
                } else {
                    rvGamesCache[provider] = [];
                }
            }
            if (rvGamesCache[provider].length) {
                renderDynamicGames(provider, rvGamesCache[provider], name);
                return;
            }
        } catch (e) {}

        let target = document.querySelector('.section-container[data-provider="' + provider + '"]');
        if (target) {
            target.style.display = 'block';
            target.classList.add('show-anim');
            return;
        }
        renderDynamicGames(provider, [], name);
    }

    function backToProviders() {
        document.querySelectorAll('.section-container').forEach(s => { s.style.display = 'none'; s.classList.remove('show-anim'); });
        document.getElementById('provider-grid-container').style.display = 'block';
    }

    function seeAll(provider) {
        // No specific swiper action needed as we are using grids
    }

    $(document).on('click', '.game-card', function(e) {
        let status = $(this).data('status');
        if (status && status != 1) {
            e.preventDefault();
            let msg = status == 2 ? "à¦à¦‡ à¦—à§‡à¦®à¦Ÿà¦¿à¦° à¦•à¦¾à¦œ à¦šà¦²à¦›à§‡à¥¤ à¦–à§à¦¬ à¦¶à§€à¦˜à§à¦°à¦‡ à¦«à¦¿à¦°à¦¬à§‡!" : "à¦à¦‡ à¦—à§‡à¦®à¦Ÿà¦¿ à¦–à§à¦¬ à¦¶à§€à¦˜à§à¦°à¦‡ à¦†à¦¸à¦›à§‡à¥¤ à¦¸à¦¾à¦¥à§‡ à¦¥à¦¾à¦•à§à¦¨!";
            if (typeof iziToast !== 'undefined') {
                iziToast.info({ message: msg, position: "topRight", timeout: 2000 });
            } else {
                alert(msg);
            }
        }
    });

</script>
@endpush
