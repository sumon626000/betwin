@extends($activeTemplate . 'layouts.master')
@section('content')

@php
    $refCode = auth()->user()->username ?? 'REF' . rand(100000, 999999);
    $refLink = route('user.register', ['ref' => $refCode]);
    
    // Safe fallback for referrals count - use your actual model relation
    $totalReferrals = 0;
    if (method_exists(auth()->user(), 'referees')) {
        $totalReferrals = auth()->user()->referees()->count();
    } elseif (method_exists(auth()->user(), 'referrals')) {
        $totalReferrals = auth()->user()->referrals()->count();
    }
    
    // Use zero values for now - replace with your actual bonus/commission table
    $todayRewards = 0;
    $yesterdayRewards = 0;
    $totalRewards = 0;
@endphp

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

<style>
    :root {
        --bg-color: #e8f0fa;
        --header-color: #154b77;
        --accent-color: #43a047;
        --card-bg: #ffffff;
        --text-main: #333333;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body { 
        background-color: var(--bg-color); 
        color: var(--text-main); 
        font-family: 'Roboto', sans-serif; 
        padding-bottom: 90px;
        -webkit-tap-highlight-color: transparent;
    }

    .referral-page-wrapper {
        min-height: 100vh;
    }

    /* â”€â”€â”€ HEADER â”€â”€â”€ */
    .header-bg { 
        background: linear-gradient(135deg, #0f395c 0%, #1a5c92 100%);
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 50;
    }
    .header-bg a {
        color: #ffffff;
        font-size: 20px;
        padding: 4px;
        text-decoration: none;
    }
    .header-bg h1 {
        color: #ffffff;
        font-weight: 700;
        font-size: 15px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* â”€â”€â”€ TABS â”€â”€â”€ */
    .tabs-container {
        display: flex;
        background-color: #ffffff;
        border-bottom: 1px solid var(--border-color);
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        position: sticky;
        top: 60px;
        z-index: 40;
    }
    .tab-btn {
        flex: 1;
        text-align: center;
        padding: 16px 0;
        font-size: 14px;
        font-weight: 600;
        color: var(--text-muted);
        cursor: pointer;
        position: relative;
        transition: all 0.2s;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: none;
        border: none;
    }
    .tab-btn.active {
        color: var(--header-color);
        font-weight: 800;
    }
    .tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: var(--accent-color);
        border-top-left-radius: 3px;
        border-top-right-radius: 3px;
    }

    /* â”€â”€â”€ WHITE CARD â”€â”€â”€ */
    .white-card {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .white-card.no-padding {
        padding: 0;
        overflow: hidden;
    }
    .white-card .inner-padding {
        padding: 16px;
    }

    .section-title {
        border-left: 4px solid var(--accent-color);
        padding-left: 10px;
        font-size: 14px;
        color: var(--header-color);
        margin-bottom: 16px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* â”€â”€â”€ COPY BOX â”€â”€â”€ */
    .copy-box {
        background-color: #f8fafc;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        display: flex;
        align-items: center;
        padding: 4px;
        margin-top: 6px;
    }
    .copy-input {
        background: transparent;
        border: none;
        color: var(--header-color);
        width: 100%;
        padding: 8px 10px;
        font-size: 13px;
        font-weight: 700;
        outline: none;
    }
    .copy-btn {
        background: linear-gradient(to bottom, #4caf50 0%, #388e3c 100%);
        color: #ffffff;
        font-weight: 800;
        font-size: 12px;
        padding: 10px 18px;
        border-radius: 6px;
        white-space: nowrap;
        border: none;
        text-transform: uppercase;
        box-shadow: 0 2px 4px rgba(67, 160, 71, 0.2);
        cursor: pointer;
    }
    .copy-btn:active { transform: scale(0.95); }

    .copy-icon-btn {
        background: linear-gradient(to bottom, #4caf50 0%, #388e3c 100%);
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        color: #ffffff;
        box-shadow: 0 2px 4px rgba(67, 160, 71, 0.2);
        cursor: pointer;
        border: none;
        flex-shrink: 0;
    }
    .copy-icon-btn:active { transform: scale(0.95); }

    /* â”€â”€â”€ SHARE BUTTON FULL WIDTH â”€â”€â”€ */
    .share-full-btn {
        display: block;
        width: 100%;
        text-align: center;
        font-size: 12px;
        font-weight: 800;
        color: #ffffff;
        background: linear-gradient(to bottom, #4caf50 0%, #388e3c 100%);
        border: none;
        border-radius: 6px;
        padding: 10px;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(67, 160, 71, 0.2);
    }
    .share-full-btn:active { transform: scale(0.97); }

    /* â”€â”€â”€ STAT GRID â”€â”€â”€ */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        text-align: center;
        background-color: #f8fafc;
        border-top: 1px solid var(--border-color);
        margin: 0;
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
    }
    .stat-item {
        padding: 12px 8px;
        border-right: 1px solid var(--border-color);
    }
    .stat-item:last-child { border-right: none; }
    .stat-item h4 {
        font-size: 10px;
        color: var(--text-muted);
        margin-bottom: 4px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .stat-item p {
        font-size: 15px;
        color: var(--header-color);
        font-weight: 900;
    }
    .stat-item p.text-green { color: #43a047; }

    /* â”€â”€â”€ CLAIM ROW â”€â”€â”€ */
    .claim-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 8px;
    }
    .claim-amount {
        font-size: 30px;
        color: var(--header-color);
        font-weight: 900;
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    .claim-btn {
        background: #e0f2fe;
        color: var(--header-color);
        font-weight: 800;
        font-size: 13px;
        padding: 8px 24px;
        border-radius: 6px;
        border: 1px solid #bae6fd;
        text-transform: uppercase;
        cursor: pointer;
    }
    .claim-btn:active { background: #bae6fd; }

    /* â”€â”€â”€ PYRAMID IMAGE â”€â”€â”€ */
    .pyramid-img {
        width: 100%;
        max-width: 320px;
        margin: 20px auto;
        display: block;
        border-radius: 8px;
    }

    /* â”€â”€â”€ TAB CONTENT â”€â”€â”€ */
    .tab-content { display: none; animation: fadeIn 0.3s ease; }
    .tab-content.active { display: block; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

    /* â”€â”€â”€ EMPTY STATE â”€â”€â”€ */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 350px;
        background-color: #ffffff;
    }
    .empty-state .empty-icon {
        width: 80px;
        height: 80px;
        background: #f8fafc;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }
    .empty-state .empty-icon i {
        font-size: 32px;
        color: #cbd5e1;
    }
    .empty-state p {
        color: #94a3b8;
        font-size: 14px;
        font-weight: 700;
    }

    /* â”€â”€â”€ INFO BOX â”€â”€â”€ */
    .info-box {
        background: #e0f2fe;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #bae6fd;
        margin-top: 16px;
    }
    .info-box p {
        font-size: 11px;
        color: var(--header-color);
        text-align: center;
        margin-bottom: 4px;
        font-weight: 700;
    }
    .info-box .highlight {
        font-size: 13px;
        color: var(--accent-color);
        font-weight: 900;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

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
        background: #e8f0fa;
    }

    .bottom-nav {
        width: 100%;
        height: 58px;
        background: linear-gradient(180deg, #0e3d2c 0%, #0a2d1f 100%);
        border-radius: 999px;
        border: 1.5px solid #1a5c40;
        box-shadow:
            0 0 0 2px #e8f0fa,
            inset 0 1px 0 rgba(37,99,235,0.12),
            0 -2px 0 0 #2563eb,
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
        color: #3db88a;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.2px;
        padding: 6px 0;
        transition: color 0.2s;
        position: relative;
    }

    .nav-item.active { color: #f5c518; }
    .nav-item.active span { border-bottom: 2px solid #f5c518; padding-bottom: 1px; }

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

    /* â”€â”€â”€ TOAST â”€â”€â”€ */
    .copy-toast {
        position: fixed;
        bottom: 100px;
        left: 50%;
        transform: translateX(-50%);
        background: #43a047;
        color: #fff;
        padding: 10px 24px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        z-index: 99999;
        opacity: 0;
        transition: opacity 0.3s;
        pointer-events: none;
    }
    .copy-toast.show { opacity: 1; }

    @media (min-width: 900px) {
        .bottom-nav-container { max-width: 600px; }
    }
</style>

<div class="referral-page-wrapper">

    <!-- HEADER -->
    <div class="header-bg">
        <a href="{{ route('user.home') }}"><i class="fas fa-home"></i></a>
        <span class="page-accent-icon"><i class="fas fa-user-group"></i></span>
        <h1>@lang('Referral Program')</h1>
        <a href="{{ route('user.home') }}"><i class="fas fa-times"></i></a>
    </div>

    <!-- TABS -->
    <div class="tabs-container">
        <button class="tab-btn active" onclick="switchTab('invite', this)">Invite</button>
        <button class="tab-btn" onclick="switchTab('details', this)">Details</button>
    </div>

    <!-- INVITE TAB -->
    <div id="invite" class="tab-content active" style="padding: 16px;">

        <!-- Refer Banner Card -->
        <div class="white-card no-padding">
            <div class="inner-padding" style="padding-bottom: 0;">
                <div class="section-title">Refer Friends and Earn</div>
                <img src="{{ asset('assets/images/frontend/img/refer_banner.png') }}" 
                     onerror="this.src='https://placehold.co/600x220/154b77/fff?text=REFER+A+FRIEND'" 
                     class="w-full rounded-lg mb-4 shadow-sm border border-gray-100" 
                     style="width:100%;">

                <div style="display: flex; gap: 16px;">
                    <!-- QR Code -->
                    <div style="background: #fff; padding: 4px; border-radius: 8px; width: 110px; height: 110px; flex-shrink: 0; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($refLink) }}" 
                             style="width: 100%; height: 100%; object-fit: contain;">
                    </div>

                    <!-- Link & Code -->
                    <div style="flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <p style="font-size: 10.5px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">Invitation Link</p>
                            <div class="copy-box">
                                <button class="share-full-btn" onclick="shareLink()">
                                    <i class="fas fa-share-alt mr-1"></i> Share Link
                                </button>
                            </div>
                        </div>
                        <div style="margin-top: 8px;">
                            <p style="font-size: 10.5px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">Invitation Code</p>
                            <div class="copy-box">
                                <input type="text" value="{{ $refCode }}" readonly class="copy-input" style="text-align: center;">
                                <button class="copy-icon-btn" onclick="copyToClipboard('{{ $refCode }}')">
                                    <i class="far fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stat-grid" style="margin-top: 20px;">
                <div class="stat-item">
                    <h4>Total Referrals</h4>
                    <p class="text-green">{{ $totalReferrals }}</p>
                </div>
                <div class="stat-item">
                    <h4>Today's Rewards</h4>
                    <p>৳ {{ number_format($todayRewards, 0) }}</p>
                </div>
                <div class="stat-item">
                    <h4>Yesterday's Rewards</h4>
                    <p>৳ {{ number_format($yesterdayRewards, 0) }}</p>
                </div>
            </div>
        </div>

        <!-- Available Cash Rewards -->
        <div class="white-card">
            <div class="section-title">Available Cash Rewards</div>
            <div class="claim-row">
                <span class="claim-amount">৳ {{ number_format($totalRewards, 0) }}</span>
                <button class="claim-btn" onclick="claimReward()">Claim Now</button>
            </div>
        </div>

        <!-- How to earn more -->
        <div class="white-card">
            <div class="section-title">How to earn more rewards</div>
            <p style="font-size: 12px; color: #64748b; line-height: 1.6; margin-bottom: 16px; font-weight: 500; text-align: justify;">
                All referrers will receive a certain cash reward percentage for every referee when they play games on the platform. Build your network to increase earnings.
            </p>
            <img src="{{ asset('assets/images/frontend/img/refer_pyramid.png') }}" 
                 onerror="this.src='https://placehold.co/400x250/f8fafc/154b77?text=Reward+Structure'" 
                 class="pyramid-img border border-gray-100 shadow-sm">
            <div class="info-box">
                <p>Be diligent in referring, be the upline and earn up to 3 tiers easily!</p>
                <div class="highlight">Welcome to lifetime commissions!</div>
            </div>
        </div>
    </div>

    <!-- DETAILS TAB -->
    <div id="details" class="tab-content">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-box-open"></i>
            </div>
            <p>@lang('No Records Found')</p>
        </div>
    </div>

</div>

<!-- COPY TOAST -->
<div class="copy-toast" id="copyToast">Copied to clipboard!</div>

@include($activeTemplate . 'partials.mobile_bottom_nav')

<script>
    const refLink = "{{ $refLink }}";
    const refCode = "{{ $refCode }}";

    function switchTab(tabId, btn) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.getElementById(tabId).classList.add('active');
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
        btn.classList.add('active');
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            const toast = document.getElementById('copyToast');
            toast.textContent = 'Copied: ' + text;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 2000);
        });
    }

    function shareLink() {
        if (navigator.share) {
            navigator.share({
                title: '{{ __(gs("site_name")) }} Referral',
                text: 'Join me and earn rewards!',
                url: refLink,
            }).catch(() => {
                copyToClipboard(refLink);
            });
        } else {
            copyToClipboard(refLink);
        }
    }

    function claimReward() {
        @auth
            alert('Reward claim feature coming soon!');
        @else
            window.location.href = "{{ route('user.login') }}";
        @endauth
    }
</script>

@endsection
