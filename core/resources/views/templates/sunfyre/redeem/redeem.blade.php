@extends($activeTemplate . 'layouts.master')
@section('content')

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body { 
        background-color: #0b0b0b; 
        color: #ffffff; 
        font-family: 'Segoe UI', sans-serif; 
        padding-bottom: 90px;
        -webkit-tap-highlight-color: transparent;
    }

    .redeem-page-wrapper {
        min-height: 100vh;
    }

    /* â”€â”€â”€ REWARD HEADER â”€â”€â”€ */
    .reward-header { 
        background: linear-gradient(180deg, #FF6B4A 0%, #FF9F4A 100%); 
        height: 220px; 
        border-bottom-left-radius: 30px; 
        border-bottom-right-radius: 30px; 
        position: relative;
        padding: 24px 16px;
    }
    .reward-header-top {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 10;
    }
    .reward-header-top .back-btn {
        position: absolute;
        left: 0;
        color: #ffffff;
        font-size: 20px;
        text-decoration: none;
    }
    .reward-header-top h1 {
        font-size: 18px;
        font-weight: 700;
        width: 100%;
        text-align: center;
    }
    .reward-header-watermark {
        font-size: 60px;
        font-weight: 900;
        color: rgba(255,255,255,0.1);
        text-align: center;
        letter-spacing: 4px;
        margin-top: 16px;
    }

    /* â”€â”€â”€ PROFILE CARD â”€â”€â”€ */
    .profile-card { 
        background: linear-gradient(135deg, #ffffff 0%, #e0e0e0 100%); 
        border-radius: 20px; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.3); 
        margin-top: -80px; 
        color: #333;
        padding: 20px;
        margin-left: 16px;
        margin-right: 16px;
        position: relative;
    }
    .sign-in-tag {
        position: absolute;
        top: 0;
        right: 0;
        background: #dc2626;
        color: #ffffff;
        font-size: 10px;
        font-weight: 700;
        padding: 6px 16px;
        border-radius: 0 20px 0 16px;
        display: flex;
        align-items: center;
        gap: 4px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    .sign-in-tag i {
        font-size: 10px;
    }
    .avatar-sm {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
        flex-shrink: 0;
    }
    .avatar-sm img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .vip-progress-bar {
        width: 100%;
        background: #e5e7eb;
        border-radius: 999px;
        height: 8px;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
        margin-top: 4px;
    }
    .vip-progress-fill {
        background: linear-gradient(to right, #f97316, #ea580c);
        height: 8px;
        border-radius: 999px;
        transition: width 0.5s;
    }

    /* â”€â”€â”€ GRID BUTTONS â”€â”€â”€ */
    .grid-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        padding: 32px 16px;
    }
    .grid-btn {
        border-radius: 18px;
        padding: 20px;
        color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 120px;
        position: relative;
        overflow: hidden;
        transition: all 0.15s;
        cursor: pointer;
        border: 1px solid rgba(255,255,255,0.1);
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    .grid-btn:active { transform: scale(0.95); }

    .bg-green-grad { background: linear-gradient(135deg, #10B981 0%, #059669 100%); }
    .bg-blue-grad { background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); }
    .bg-pink-grad { background: linear-gradient(135deg, #EC4899 0%, #DB2777 100%); }
    .bg-orange-grad { background: linear-gradient(135deg, #F97316 0%, #EA580C 100%); }

    .btn-icon {
        width: 48px;
        height: 48px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
        font-size: 22px;
        position: relative;
    }
    .badge-count { 
        position: absolute; 
        top: -5px; 
        right: -5px; 
        background: #EF4444; 
        color: white; 
        width: 22px; 
        height: 22px; 
        border-radius: 50%; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 11px; 
        font-weight: bold; 
        border: 2px solid #fff;
    }
    .grid-label {
        font-size: 13px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: center;
    }

    /* â”€â”€â”€ MODALS â”€â”€â”€ */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.9);
        z-index: 10001;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }
    .modal-overlay.hidden { display: none; }
    .modal-box {
        background: #ffffff;
        border-radius: 16px;
        width: 100%;
        max-width: 400px;
        padding: 24px;
        position: relative;
    }
    .modal-close {
        position: absolute;
        top: 12px;
        right: 16px;
        color: #9ca3af;
        font-size: 24px;
        cursor: pointer;
        background: none;
        border: none;
    }
    .modal-close:hover { color: #333; }
    .modal-title {
        font-size: 20px;
        font-weight: 900;
        text-align: center;
        color: #059669;
        margin-bottom: 24px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .modal-empty {
        text-align: center;
        padding: 32px 0;
    }
    .modal-empty i {
        font-size: 48px;
        color: #e5e7eb;
        margin-bottom: 12px;
    }
    .modal-empty p {
        color: #9ca3af;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 12px;
    }

    /* â”€â”€â”€ NOTIFICATION TICKER â”€â”€â”€ */
    .notification-frame {
        margin-top: 24px;
        background: rgba(207, 166, 67, 0.05);
        border: 1px dashed rgba(0, 255, 136, 0.3);
        border-radius: 8px;
        height: 70px;
        overflow: hidden;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .msg-content {
        position: absolute;
        width: 100%;
        text-align: center;
        color: #00ff88;
        font-size: 15px;
        font-weight: 500;
        transform: translateY(50px);
        opacity: 0;
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .msg-content.show {
        transform: translateY(0);
        opacity: 1;
    }
    .msg-content.hide {
        transform: translateY(-50px);
        opacity: 0;
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

    @media (min-width: 900px) {
        .bottom-nav-container { max-width: 600px; }
    }
</style>

<div class="redeem-page-wrapper">

    <!-- REWARD HEADER -->
    <div class="reward-header">
        <div class="reward-header-top">
            <a href="{{ route('user.home') }}" class="back-btn"><i class="fas fa-chevron-left"></i></a>
            <h1>Reward Center</h1>
        </div>
        <div class="reward-header-watermark">REWARDS</div>
    </div>

    <!-- PROFILE CARD -->
    <div class="profile-card">
        <div class="sign-in-tag">
            <i class="far fa-calendar-check"></i> Sign In <i class="fas fa-chevron-right" style="font-size:8px;"></i>
        </div>

        <div style="display: flex; align-items: center; gap: 16px;">
            <div class="avatar-sm">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->username ?? 'User') }}&background=ffd700&color=333&bold=true" 
                     onerror="this.src='{{ asset('assets/images/frontend/img/avatar.png') }}'">
            </div>
            <div style="flex: 1;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <h2 style="font-weight: 900; color: #333; font-size: 18px;">{{ auth()->user()->username }}</h2>
                    <i onclick="copyText('{{ auth()->user()->username }}')" class="far fa-copy" style="color:#9ca3af; font-size:12px; cursor:pointer;"></i>
                </div>
                <p style="color:#6b7280; font-size:12px; font-weight:600;">@lang('Nickname:') {{ auth()->user()->username }}</p>
                <div style="display: flex; align-items: center; gap: 4px; color:#333; font-weight:900; margin-top: 4px;">
                    <span style="font-size:18px;">৳ {{ number_format(auth()->user()->balance, 2) }}</span>
                    <i class="fas fa-sync-alt" style="color:#9ca3af; font-size:12px; margin-left:8px; cursor:pointer;" onclick="location.reload()"></i>
                </div>
            </div>
        </div>

        <!-- VIP Progress -->
        <div style="margin-top: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; font-size:11px; color:#6b7280; margin-bottom: 6px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">
                <span style="display: flex; align-items: center; gap: 4px; color:#f97316;">
                    <i class="fas fa-crown"></i> VIP{{ auth()->user()->vip_level ?? 0 }} Member
                </span>
                <a href="#" style="color:#3b82f6; text-decoration:none;">Benefits <i class="fas fa-chevron-right" style="font-size:8px;"></i></a>
            </div>
            <div class="vip-progress-bar">
                <div class="vip-progress-fill" style="width: 15%"></div>
            </div>
            <p style="text-align:right; font-size:10px; color:#9ca3af; margin-top:4px; font-weight:700;">Progress: 0 / 2</p>
        </div>
    </div>

    <!-- GRID BUTTONS -->
    <div class="grid-container">
        <!-- Claim Bonus -->
        <div onclick="openModal('modal-claim')" class="grid-btn bg-green-grad">
            <div class="btn-icon">
                <i class="fas fa-gift"></i>
            </div>
            <span class="grid-label">Claim Bonus</span>
        </div>

        <!-- Daily Check-in -->
        <a href="#" class="grid-btn bg-blue-grad">
            <div class="btn-icon"><i class="far fa-calendar-check"></i></div>
            <span class="grid-label">Daily Check-in</span>
        </a>

        <!-- Invite Friends -->
        <a href="{{ route('user.referrals') }}" class="grid-btn bg-pink-grad">
            <div class="btn-icon"><i class="fas fa-user-plus"></i></div>
            <span class="grid-label">@lang('Invite Friends')</span>
        </a>

        <!-- Lucky Tickets -->
        <div onclick="openModal('modal-temu')" class="grid-btn bg-orange-grad">
            <div class="btn-icon">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <span class="grid-label">Lucky Tickets</span>
        </div>
    </div>

    <!-- NOTIFICATION TICKER -->
    <div style="padding: 0 16px;">
        <div class="notification-frame">
            <div id="msg-1" class="msg-content"></div>
            <div id="msg-2" class="msg-content"></div>
        </div>
    </div>

</div>

<!-- MODAL: Claim Bonus -->
<div id="modal-claim" class="modal-overlay hidden">
    <div class="modal-box">
        <button onclick="closeModal('modal-claim')" class="modal-close">&times;</button>
        <h3 class="modal-title">Available Bonuses</h3>
        <div class="modal-empty">
            <i class="fas fa-box-open"></i>
            <p>No bonuses available</p>
        </div>
    </div>
</div>

<!-- MODAL: Lucky Tickets -->
<div id="modal-temu" class="modal-overlay hidden">
    <div class="modal-box" style="text-align:center;">
        <button onclick="closeModal('modal-temu')" class="modal-close">&times;</button>
        <i class="fas fa-ticket-alt" style="color:#fed7aa; font-size:48px; margin-bottom:16px;"></i>
        <h3 style="font-size:20px; font-weight:900; color:#333; margin-bottom:8px; text-transform:uppercase;">Lucky Tickets</h3>
        <p style="color:#9ca3af; font-size:14px; font-weight:700; text-transform:uppercase;">You have 0 active tickets</p>
    </div>
</div>

@include($activeTemplate . 'partials.mobile_bottom_nav')

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function copyText(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(function() {
                alert("Copied to clipboard!");
            });
        } else {
            const el = document.createElement('textarea');
            el.value = text;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            alert("Copied: " + text);
        }
    }

    // Notification ticker
    const prefixes = ["017", "018", "019", "016", "013", "015"];
    const amounts = ["100", "200", "500", "1000", "2000", "5000"];
    let activeIndex = 1;

    function getNewMessage() {
        const num = prefixes[Math.floor(Math.random() * prefixes.length)] + "******" + Math.floor(10 + Math.random() * 90);
        const amt = amounts[Math.floor(Math.random() * amounts.length)];
        return `ðŸŽ‰ User ${num} successfully redeemed ৳${amt}`;
    }

    function rotateMessage() {
        const currentMsg = document.getElementById('msg-' + activeIndex);
        activeIndex = activeIndex === 1 ? 2 : 1;
        const nextMsg = document.getElementById('msg-' + activeIndex);

        nextMsg.innerHTML = getNewMessage();
        
        currentMsg.classList.remove('show');
        currentMsg.classList.add('hide');

        nextMsg.classList.remove('hide');
        nextMsg.classList.add('show');

        setTimeout(() => {
            currentMsg.classList.remove('hide');
        }, 600);
    }

    window.onload = function() {
        const first = document.getElementById('msg-1');
        first.innerHTML = getNewMessage();
        first.classList.add('show');
        setInterval(rotateMessage, 4000);
    };
</script>

@endsection
