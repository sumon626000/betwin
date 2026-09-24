@extends($activeTemplate . 'layouts.master')
@section('content')

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
        --blue-light: #e0f2fe;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    html, body { max-width: 100vw; overflow-x: hidden; touch-action: manipulation; }
    
    body { 
        background-color: var(--bg-color); 
        color: var(--text-main); 
        font-family: 'Roboto', sans-serif; 
        padding-bottom: 90px; 
        -webkit-tap-highlight-color: transparent;
        user-select: none;
    }

    .commission-wrapper {
        min-height: 100vh;
    }

    /* â”€â”€â”€ HEADER â”€â”€â”€ */
    .header-bg { 
        background: linear-gradient(135deg, #0f395c 0%, #1a5c92 100%);
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 16px;
        position: sticky;
        top: 0;
        z-index: 50;
    }
    .header-bg a {
        color: #ffffff;
        font-size: 20px;
        padding: 4px;
        text-decoration: none;
        transition: transform 0.15s;
    }
    .header-bg a:active { transform: scale(0.9); }
    .header-bg h1 {
        color: #ffffff;
        font-weight: 800;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* â”€â”€â”€ TABS â”€â”€â”€ */
    .tabs-container { 
        display: flex; 
        background-color: var(--card-bg); 
        border-bottom: 1px solid var(--border-color); 
        position: sticky; 
        top: 56px; 
        z-index: 40;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    .tab-btn { 
        flex: 1; 
        text-align: center; 
        padding: 14px 0; 
        font-size: 13px; 
        font-weight: 700; 
        color: var(--header-color); 
        cursor: pointer; 
        background: #fff; 
        border-bottom: 2px solid transparent; 
        transition: 0.3s;
        text-decoration: none;
    }
    .tab-btn.active { 
        color: #20b1ff; 
        border-bottom-color: #20b1ff; 
        background: #f8fafc; 
    }

    /* â”€â”€â”€ REBATE CARD â”€â”€â”€ */
    .rebate-card { 
        background: #fff; 
        border-radius: 12px; 
        overflow: hidden; 
        border: 1px solid var(--border-color); 
        box-shadow: 0 4px 6px rgba(0,0,0,0.02); 
        margin: 16px;
    }
    .table-header { 
        background: #e0f2fe; 
        color: #1a5c92; 
        font-size: 11px; 
        font-weight: 800; 
        text-transform: uppercase; 
        display: flex;
        justify-content: space-between;
        padding: 12px 20px;
    }
    
    .data-row {
        display: flex;
        justify-content: space-between;
        padding: 16px 20px;
        font-size: 14px;
        border-bottom: 1px solid #f3f4f6;
        transition: background 0.2s;
    }
    .data-row:active { background: #f9fafb; }
    .data-row .date-text { color: #6b7280; font-weight: 500; }
    .data-row .amount-text { font-weight: 700; color: #1a5c92; }

    .btn-claim { 
        background: linear-gradient(180deg, #1a5c92 0%, #154b77 100%); 
        color: #fff; 
        font-weight: 700; 
        box-shadow: 0 4px 10px rgba(26, 92, 146, 0.2); 
        width: 100%;
        padding: 14px;
        border-radius: 12px;
        font-size: 14px;
        text-transform: uppercase;
        font-weight: 900;
        letter-spacing: 1px;
        border: none;
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-claim:active { transform: scale(0.95); }
    .btn-claim:disabled { 
        background: #e2e8f0; 
        color: #94a3b8; 
        box-shadow: none; 
        cursor: not-allowed; 
    }

    .min-claim-text {
        font-size: 10px;
        color: #9ca3af;
        text-align: center;
        margin-bottom: 12px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
        font-style: italic;
    }

    /* â”€â”€â”€ INFO BOX â”€â”€â”€ */
    .info-box {
        background: rgba(224, 242, 254, 0.5);
        border: 1px solid rgba(26, 92, 146, 0.1);
        padding: 20px;
        border-radius: 12px;
        margin: 24px 16px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .info-box-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }
    .info-box-header i {
        color: #1a5c92;
        font-size: 16px;
    }
    .info-box-header h4 {
        font-size: 12px;
        font-weight: 900;
        color: #1a5c92;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .info-box ul {
        list-style: disc;
        margin-left: 16px;
        font-size: 11px;
        color: #6b7280;
        line-height: 1.6;
        font-weight: 500;
    }
    .info-box ul li {
        margin-bottom: 4px;
    }

    /* â”€â”€â”€ EMPTY STATE â”€â”€â”€ */
    .no-data { 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        justify-content: center; 
        min-height: 40vh;
        opacity: 0.8; 
    }
    .no-data .empty-icon-wrap {
        width: 64px;
        height: 64px;
        background: #f3f4f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        border: 1px solid #e5e7eb;
    }
    .no-data .empty-icon-wrap i {
        font-size: 28px;
        color: #d1d5db;
    }
    .no-data p {
        font-size: 12px;
        color: #9ca3af;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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

<div class="commission-wrapper">

    <!-- HEADER -->
    <div class="header-bg">
        <a href="{{ route('user.account') }}"><i class="fas fa-chevron-left"></i></a>
        <h1>Commission Records</h1>
    </div>

    <!-- TABS -->
    <div class="tabs-container">
        <a href="{{ route('user.commission.log', ['tab' => 'manual']) }}" class="tab-btn {{ request('tab', 'history') == 'manual' ? 'active' : '' }}">Manual Rebate</a>
        <a href="{{ route('user.commission.log', ['tab' => 'history']) }}" class="tab-btn {{ request('tab', 'history') == 'history' ? 'active' : '' }}">Rebate History</a>
    </div>

    <!-- REBATE CARD -->
    <div class="rebate-card">
        <div class="table-header">
            <span>Date</span>
            <span>Amount</span>
        </div>

        <div>
            @php $totalCommission = 0; @endphp
            @forelse($logs as $log)
                @php $totalCommission += $log->amount; @endphp
                <div class="data-row">
                    <span class="date-text">{{ showDateTime($log->created_at, 'Y-m-d') }}</span>
                    <span class="amount-text">৳ {{ number_format($log->amount, 4) }}</span>
                </div>
            @empty
                <div class="no-data" style="min-height: 30vh; padding: 40px 0;">
                    <div class="empty-icon-wrap">
                        <i class="fas fa-coins"></i>
                    </div>
                    <p>No Commission Found</p>
                </div>
            @endforelse
        </div>

        <!-- Claim Section -->
        <div style="padding: 20px; background: #f9fafb; border-top: 1px solid #e5e7eb;">
            <p class="min-claim-text">Minimum claim amount: ৳ 1.00</p>
            <button class="btn-claim" {{ $totalCommission >= 1 ? '' : 'disabled' }} onclick="claimCommission()">
                Claim Commission Now
            </button>
        </div>
    </div>

    <!-- PAGINATION -->
    @if($logs->hasPages())
        <div style="padding: 16px; text-align: center;">
            {{ paginateLinks($logs) }}
        </div>
    @endif

    <!-- INFO BOX -->
    <div class="info-box">
        <div class="info-box-header">
            <i class="fas fa-info-circle"></i>
            <h4>Commission Instructions</h4>
        </div>
        <ul>
            <li>Commission is calculated automatically every day based on your activity.</li>
            <li>The commission amount is based on your total valid turnover on the platform.</li>
            <li>Please contact our 24/7 Live Support if you have any questions regarding your commission.</li>
        </ul>
    </div>

</div>

@include($activeTemplate . 'partials.mobile_bottom_nav')

<script>
    function claimCommission() {
        @auth
            alert('Commission claim feature coming soon!');
        @else
            window.location.href = "{{ route('user.login') }}";
        @endauth
    }
</script>

@endsection
