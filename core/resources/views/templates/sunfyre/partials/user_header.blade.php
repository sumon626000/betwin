{{-- user_header.blade.php --}}

<div class="site-topbar">
    @include($activeTemplate . 'partials.apk_banner')

<header class="site-header">
    <button class="hamburger-btn" onclick="toggleSidebar()" type="button" aria-label="@lang('Menu')">
        <div class="hamburger-icon">
            <div class="hb-arrow">
                <span class="hb-arrow-left"></span>
                <span class="hb-line l1"></span>
            </div>
            <span class="hb-line l2"></span>
            <span class="hb-line l3"></span>
        </div>
    </button>
    <a class="navbar-brand logo me-auto" href="{{ route('home') }}" style="text-decoration: none; margin-left: 8px;">
        <img src="{{ asset('assets/images/logo_icon/logo.png') }}" alt="{{ __(gs('site_name')) }}" class="brand-logo" width="120" height="36" decoding="async" fetchpriority="high">
    </a>
    <div class="header-right">
        @include($activeTemplate . 'partials.lang_switch')
        @auth
            <div class="user-balance">
                <i class="fas fa-wallet"></i>
                <span class="js-live-balance" data-live-balance="full">{{ showAmount(auth()->user()->balance) }} {{ __(gs('cur_text')) }}</span>
            </div>
        @else
            <a href="{{ route('user.login') }}" class="btn-login">@lang('Log In')</a>
        @endauth
    </div>
</header>
</div>

<div id="sidebarOverlay" onclick="toggleSidebar()" class="hidden"></div>

<aside id="sidebar" aria-label="BET369WIN menu">
    <div class="sb-header">
        <a href="{{ route('home') }}" class="sb-logo-link">
            <img src="{{ asset('assets/images/logo_icon/logo.png') }}" alt="{{ __(gs('site_name')) }}" class="brand-logo brand-logo--sidebar">
        </a>
        <button type="button" onclick="toggleSidebar()" class="sb-close-btn" aria-label="@lang('Close')">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="sb-scroll">
        @guest
            <a href="{{ route('user.login') }}" class="sb-login-row">
                <span class="sb-login-row__avatar" aria-hidden="true"><i class="fas fa-user"></i></span>
                <span class="sb-login-row__text">@lang('Log in')</span>
                <i class="fas fa-chevron-right sb-login-row__chev" aria-hidden="true"></i>
            </a>
        @else
            <a href="{{ route('user.account') }}" class="sb-login-row">
                <span class="sb-login-row__avatar" aria-hidden="true"><i class="fas fa-user"></i></span>
                <span class="sb-login-row__text">
                    <strong>{{ auth()->user()->username ?? auth()->user()->firstname ?? __('Member') }}</strong>
                    <small class="js-live-balance" data-live-balance="full">{{ showAmount(auth()->user()->balance) }} {{ __(gs('cur_text')) }}</small>
                </span>
                <i class="fas fa-chevron-right sb-login-row__chev" aria-hidden="true"></i>
            </a>
        @endguest

        <a href="{{ route('user.promotions') }}" class="sb-promo-banner">
            <span class="sb-promo-banner__text">
                <strong>@lang('Free money')</strong>
                <small>@lang('Promotions & rewards')</small>
            </span>
            <span class="sb-promo-banner__ico" aria-hidden="true"><i class="fas fa-gift"></i></span>
        </a>

        <nav class="sb-list">
            <a href="{{ route('user.home') }}" class="sb-list__item">
                <span class="sb-list__ico"><i class="fas fa-fire"></i></span>
                <span class="sb-list__label">@lang('Hot Games')</span>
            </a>
            <a href="{{ route('user.referrals') }}" class="sb-list__item">
                <span class="sb-list__ico"><i class="fas fa-user-group"></i></span>
                <span class="sb-list__label">@lang('Invite friends')</span>
            </a>
            <a href="javascript:void(0)" class="sb-list__item" onclick="(window.B369Fav&&B369Fav.showFavorites()); if(typeof toggleSidebar==='function') toggleSidebar();">
                <span class="sb-list__ico"><i class="fas fa-heart"></i></span>
                <span class="sb-list__label">@lang('Favorites')</span>
            </a>
            <a href="{{ route('user.promotions') }}" class="sb-list__item">
                <span class="sb-list__ico"><i class="fas fa-gift"></i></span>
                <span class="sb-list__label">@lang('Promotion')</span>
            </a>
            <a href="{{ route('user.home') }}#slots" class="sb-list__item">
                <span class="sb-list__ico"><i class="fas fa-dice"></i></span>
                <span class="sb-list__label">@lang('Slots')</span>
            </a>
            <a href="{{ route('user.redeem.index') }}" class="sb-list__item">
                <span class="sb-list__ico"><i class="fas fa-award"></i></span>
                <span class="sb-list__label">@lang('Reward Center')</span>
            </a>
            <a href="{{ route('user.home') }}#live" class="sb-list__item">
                <span class="sb-list__ico"><i class="fas fa-dharmachakra"></i></span>
                <span class="sb-list__label">@lang('Live Casino')</span>
            </a>
            <a href="#" class="sb-list__item">
                <span class="sb-list__ico"><i class="fas fa-hand-holding-dollar"></i></span>
                <span class="sb-list__label">@lang('Manual Rebate')</span>
            </a>
            <a href="{{ route('user.home') }}#sports" class="sb-list__item">
                <span class="sb-list__ico"><i class="fas fa-futbol"></i></span>
                <span class="sb-list__label">@lang('Sports')</span>
            </a>
            <a href="#" class="sb-list__item">
                <span class="sb-list__ico"><i class="fas fa-gem"></i></span>
                <span class="sb-list__label">@lang('VIP')</span>
            </a>
            <a href="#" class="sb-list__item">
                <span class="sb-list__ico"><i class="fas fa-gamepad"></i></span>
                <span class="sb-list__label">@lang('E-sports')</span>
            </a>
            <a href="#" class="sb-list__item">
                <span class="sb-list__ico"><i class="fas fa-bullseye"></i></span>
                <span class="sb-list__label">@lang('Mission')</span>
            </a>
            <a href="#" class="sb-list__item">
                <span class="sb-list__ico"><i class="fas fa-chess"></i></span>
                <span class="sb-list__label">@lang('Poker')</span>
            </a>
            <a href="#" class="sb-list__item">
                <span class="sb-list__ico"><i class="fas fa-fish"></i></span>
                <span class="sb-list__label">@lang('Fish')</span>
            </a>
            <a href="#" class="sb-list__item">
                <span class="sb-list__ico"><i class="fas fa-ticket"></i></span>
                <span class="sb-list__label">@lang('Lottery')</span>
            </a>
        </nav>

        <div class="sb-divider"></div>

        <nav class="sb-list">
            <a href="{{ route('download.apk') }}" class="sb-list__item" id="sbAppInstall" onclick="if(window.__b369InstallApp){event.preventDefault();window.__b369InstallApp();}">
                <span class="sb-list__ico"><i class="fas fa-cloud-arrow-down"></i></span>
                <span class="sb-list__label">@lang('APP Download')</span>
            </a>
            <a href="{{ auth()->check() ? route('ticket.index') : route('contact') }}" class="sb-list__item">
                <span class="sb-list__ico"><i class="fas fa-headset"></i></span>
                <span class="sb-list__label">@lang('Customer Service')</span>
            </a>
            <a href="{{ route('user.referrals') }}" class="sb-list__item">
                <span class="sb-list__ico"><i class="fas fa-handshake"></i></span>
                <span class="sb-list__label">@lang('Affiliate')</span>
            </a>
        </nav>

        <div class="sb-social-row">
            <a href="https://t.me/bet369win" target="_blank" rel="noopener" class="sb-social" title="Telegram" aria-label="Telegram">
                <i class="fab fa-telegram-plane"></i>
            </a>
            <a href="https://www.facebook.com/bet369win" target="_blank" rel="noopener" class="sb-social" title="Facebook" aria-label="Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://t.me/bet369win" target="_blank" rel="noopener" class="sb-social" title="Chat" aria-label="Chat">
                <i class="fab fa-whatsapp"></i>
            </a>
            <a href="{{ auth()->check() ? route('ticket.index') : route('contact') }}" class="sb-social" title="Support" aria-label="Support">
                <i class="fas fa-headset"></i>
            </a>
        </div>

        <a href="{{ auth()->check() ? route('ticket.index') : route('contact') }}" class="sb-support-btn">
            <i class="fas fa-comment-dots"></i>
            <span>@lang('Support')</span>
            <span class="sb-badge sb-badge--live">24/7</span>
        </a>

        @auth
            <a href="{{ route('user.logout') }}" class="sb-logout">
                <i class="fas fa-sign-out-alt"></i> @lang('Logout')
            </a>
        @endauth
    </div>
</aside>

<style>
    :root {
        --bg-deep:    #e8f0fa;
        --bg-main:    #f5f7fa;
        --bg-card:    #ffffff;
        --teal:       #2563eb;
        --teal-light: #2563eb;
        --gold:       #f4b942;
        --gold-dark:  #d9a12a;
        --gold-text:  #123b66;
        --text-main:  #172033;
        --text-muted: #6b7280;
        --border:     rgba(18,59,102,0.1);
    }

    .site-header {
        position: relative; top: auto; z-index: 1; height: 56px;
        background: rgba(255,255,255,0.96); backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: flex-start; padding: 0 12px;
    }
    .header-right { display: flex; align-items: center; gap: 8px; margin-left: auto; flex-shrink: 0; }

    .btn-login {
        padding: 7px 16px; border-radius: 10px; font-size: 13px; font-weight: 700;
        cursor: pointer; border: none; text-decoration: none;
        display: inline-flex; align-items: center; justify-content: center;
        background: #2563eb; color: #fff; box-shadow: 0 6px 14px rgba(37,99,235,0.28);
    }

    .user-balance {
        background: #e8f0fa; padding: 6px 12px; border-radius: 8px;
        border: 1px solid #d5e4f7; white-space: nowrap; color: #123b66;
        font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 6px;
    }

    .hamburger-btn {
        background: #e8f0fa; border: 1px solid #d5e4f7; border-radius: 8px;
        cursor: pointer; padding: 7px 8px; display: flex; align-items: center;
        justify-content: center; width: 36px; height: 36px; flex-shrink: 0;
    }
    .hamburger-icon { display: flex; flex-direction: column; gap: 4px; width: 18px; }
    .hamburger-icon .hb-arrow { display: flex; align-items: center; gap: 2px; }
    .hamburger-icon .hb-arrow-left {
        width: 0; height: 0; border-top: 4px solid transparent;
        border-bottom: 4px solid transparent; border-right: 5px solid #123b66;
    }
    .hamburger-icon .hb-line { height: 2px; background: #123b66; border-radius: 2px; display: block; }
    .hamburger-icon .hb-line.l1, .hamburger-icon .hb-line.l2 { width: 100%; }
    .hamburger-icon .hb-line.l3 { width: 65%; }

    @media (min-width: 900px) {
        #sidebarOverlay { display: none !important; }
        #sidebar {
            position: fixed !important; top: 0 !important; left: 0 !important;
            transform: translateX(0) !important; height: 100vh !important; z-index: 50 !important;
        }
        body { padding-left: 300px; }
        .sb-close-btn { display: none !important; }
    }
    @media (max-width: 899px) {
        #sidebar { transform: translateX(-100%); transition: transform 0.3s ease !important; }
        #sidebar.open { transform: translateX(0) !important; }
    }

    #sidebar {
        background: #ffffff !important; width: 300px !important; height: 100% !important;
        position: fixed !important; top: 0 !important; left: 0 !important; z-index: 70 !important;
        overflow: hidden !important; display: flex !important; flex-direction: column !important;
        box-shadow: 4px 0 24px rgba(18,59,102,0.12) !important;
    }
    .sb-header {
        background: #ffffff !important; display: flex !important; align-items: center !important;
        justify-content: space-between !important; padding: 12px 14px !important;
        border-bottom: 1px solid #e8f0fa !important; flex-shrink: 0 !important;
    }
    .sb-close-btn {
        width: 34px !important; height: 34px !important; border-radius: 50% !important;
        border: 0 !important; background: #e8f0fa !important; color: #123b66 !important;
        display: flex !important; align-items: center !important; justify-content: center !important;
        cursor: pointer !important;
    }
    .sb-scroll {
        flex: 1 !important; overflow-y: auto !important; padding: 12px 12px 24px !important;
        -webkit-overflow-scrolling: touch;
    }
    .sb-scroll::-webkit-scrollbar { width: 0; }

    .sb-login-row {
        display: flex !important; align-items: center !important; gap: 12px !important;
        padding: 10px 8px 14px !important; margin-bottom: 4px !important;
        text-decoration: none !important; color: #172033 !important;
    }
    .sb-login-row__avatar {
        width: 42px; height: 42px; border-radius: 50%; flex-shrink: 0;
        background: #e8f0fa; color: #6b7280; display: grid; place-items: center; font-size: 18px;
        border: 1px solid #d5e4f7;
    }
    .sb-login-row__text {
        flex: 1; font-size: 16px; font-weight: 800; color: #123b66;
        display: flex; flex-direction: column; gap: 2px; min-width: 0;
    }
    .sb-login-row__text strong { font-size: 15px; font-weight: 800; color: #123b66; }
    .sb-login-row__text small { font-size: 12px; font-weight: 600; color: #6b7280; }
    .sb-login-row__chev { color: #9aa3b2; font-size: 13px; }

    .sb-promo-banner {
        display: flex !important; align-items: center !important; justify-content: space-between !important;
        gap: 10px !important; padding: 12px 14px !important; margin-bottom: 10px !important;
        border-radius: 14px !important; text-decoration: none !important;
        background: linear-gradient(135deg, #123b66 0%, #1a4a7a 55%, #2563eb 100%) !important;
        color: #ffffff !important; box-shadow: 0 8px 18px rgba(18,59,102,0.2) !important;
    }
    .sb-promo-banner__text { display: flex; flex-direction: column; gap: 2px; }
    .sb-promo-banner__text strong { font-size: 15px; font-weight: 800; }
    .sb-promo-banner__text small { font-size: 11px; opacity: 0.85; }
    .sb-promo-banner__ico {
        width: 40px; height: 40px; border-radius: 12px; background: rgba(244,185,66,0.2);
        color: #f4b942; display: grid; place-items: center; font-size: 18px;
    }

    .sb-list { display: flex; flex-direction: column; gap: 0; }
    .sb-list__item {
        display: flex !important; align-items: center !important; gap: 12px !important;
        padding: 12px 10px !important; border-radius: 0 !important;
        text-decoration: none !important; color: #172033 !important;
        transition: background 0.15s ease;
        border-bottom: 1px dotted #d5e4f7 !important;
    }
    .sb-list__item:last-child { border-bottom: 0 !important; }
    .sb-list__item:active { background: #e8f0fa !important; }
    .sb-list__ico {
        width: 22px; text-align: center; color: #123b66; font-size: 16px; flex-shrink: 0;
    }
    .sb-list__label { flex: 1; font-size: 14px; font-weight: 600; }
    .sb-list__chev { color: #9aa3b2; font-size: 11px; }

    .sb-badge {
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 10px; font-weight: 800; line-height: 1; color: #fff;
    }
    .sb-badge--dot {
        min-width: 18px; height: 18px; border-radius: 50%; background: #f59e0b; padding: 0 4px;
    }
    .sb-badge--pill {
        border-radius: 999px; background: #f59e0b; padding: 4px 8px; text-transform: uppercase;
    }
    .sb-badge--live {
        border-radius: 999px; background: #2563eb; padding: 4px 8px; margin-left: auto;
    }

    .sb-divider {
        height: 1px; background: #e8f0fa; margin: 10px 4px;
    }

    .sb-app-card {
        display: flex !important; align-items: center !important; gap: 12px !important;
        margin-top: 12px !important; padding: 12px !important; border-radius: 14px !important;
        background: #f5f7fa !important; border: 1px solid #e8f0fa !important;
        text-decoration: none !important; color: #172033 !important;
    }
    .sb-app-card__ico {
        width: 40px; height: 40px; border-radius: 12px; background: #22c55e; color: #fff;
        display: grid; place-items: center; font-size: 20px; flex-shrink: 0;
    }
    .sb-app-card__text { flex: 1; display: flex; flex-direction: column; gap: 2px; min-width: 0; }
    .sb-app-card__text strong { font-size: 13px; font-weight: 800; color: #123b66; }
    .sb-app-card__text small { font-size: 11px; color: #6b7280; }
    .sb-app-card__chev { color: #9aa3b2; font-size: 12px; }

    .sb-social-row {
        display: flex; align-items: center; gap: 8px; margin-top: 14px; flex-wrap: wrap;
    }
    .sb-social {
        width: 40px; height: 40px; border-radius: 12px; background: #e8f0fa; color: #123b66;
        display: grid; place-items: center; text-decoration: none; font-size: 16px;
        border: 1px solid #d5e4f7;
    }
    .sb-lang { margin-left: auto; }

    .sb-support-btn {
        margin-top: 12px !important; display: flex !important; align-items: center !important;
        gap: 10px !important; padding: 12px 14px !important; border-radius: 14px !important;
        background: #ffffff !important; border: 1px solid #d5e4f7 !important;
        color: #123b66 !important; text-decoration: none !important; font-weight: 700 !important;
        box-shadow: 0 4px 12px rgba(18,59,102,0.06) !important;
    }

    .sb-logout {
        margin-top: 10px !important; display: flex !important; align-items: center !important;
        justify-content: center !important; gap: 8px !important; padding: 12px !important;
        border-radius: 12px !important; color: #ef4444 !important; text-decoration: none !important;
        font-weight: 700 !important; border: 1px solid rgba(239,68,68,0.2) !important;
        background: #fff5f5 !important;
    }

    #sidebarOverlay {
        background: rgba(18,59,102,0.35) !important; backdrop-filter: blur(6px) !important;
        z-index: 60 !important; position: fixed !important; inset: 0;
    }
    .hidden { display: none !important; }
</style>

@push('script')
<script>
    function toggleSidebar() {
        if (window.innerWidth >= 900) return;
        var sidebar = document.getElementById('sidebar');
        var overlay = document.getElementById('sidebarOverlay');
        if (sidebar) sidebar.classList.toggle('open');
        if (overlay) overlay.classList.toggle('hidden');
    }
</script>
@endpush
