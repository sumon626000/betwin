@extends($activeTemplate . 'layouts.master')
@section('content')

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
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

    .game-log-wrapper {
        min-height: 100vh;
    }

    /* â”€â”€â”€ HEADER â”€â”€â”€ */
    .header-bg { 
        background: linear-gradient(135deg, #0f395c 0%, #1a5c92 100%);
        padding: 16px; 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        position: sticky; 
        top: 0; 
        z-index: 50; 
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
        color: var(--text-muted); 
        cursor: pointer; 
        position: relative; 
        transition: 0.2s;
        text-transform: uppercase; 
        letter-spacing: 0.5px;
        text-decoration: none;
        background: none;
        border: none;
    }
    .tab-btn.active { color: var(--header-color); font-weight: 900; }
    .tab-btn.active::after {
        content: ''; 
        position: absolute; 
        bottom: 0; 
        left: 0; 
        width: 100%; 
        height: 3px;
        background-color: var(--accent-color);
    }

    /* â”€â”€â”€ FILTER BAR â”€â”€â”€ */
    .filter-bar { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        padding: 12px 16px; 
        background-color: #f8fafc; 
        border-bottom: 1px solid var(--border-color); 
        position: sticky;
        top: 108px;
        z-index: 30; 
    }
    .date-btn { 
        background-color: var(--card-bg); 
        color: var(--header-color); 
        font-size: 12px; 
        font-weight: 700; 
        padding: 8px 14px; 
        border-radius: 6px; 
        border: 1px solid #bae6fd; 
        display: flex; 
        align-items: center; 
        gap: 6px; 
        cursor: pointer;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }
    .filter-icon-btn { 
        color: var(--header-color); 
        font-size: 15px; 
        cursor: pointer; 
        padding: 8px; 
        background: var(--card-bg); 
        border: 1px solid var(--border-color);
        border-radius: 6px; 
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }

    /* â”€â”€â”€ DROPDOWN â”€â”€â”€ */
    .dropdown-menu { 
        display: none; 
        position: absolute; 
        top: 55px; 
        left: 16px; 
        background-color: var(--card-bg); 
        border: 1px solid var(--border-color); 
        border-radius: 8px; 
        width: 180px; 
        box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
        z-index: 50; 
        overflow: hidden;
    }
    .dropdown-menu.show { display: block; animation: fadeIn 0.2s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    
    .dropdown-item { 
        display: block; 
        padding: 12px 16px; 
        color: var(--text-main); 
        font-size: 12.5px; 
        font-weight: 600; 
        border-bottom: 1px solid var(--border-color); 
        cursor: pointer; 
        text-decoration: none;
    }
    .dropdown-item:last-child { border-bottom: none; }
    .dropdown-item:hover { background-color: var(--blue-light); color: var(--header-color); }

    /* â”€â”€â”€ TABLE HEADER â”€â”€â”€ */
    .table-header { 
        display: grid; 
        grid-template-columns: 1fr 1.2fr 0.8fr 1fr; 
        background-color: #e0f2fe; 
        padding: 12px 6px; 
        font-size: 11px; 
        font-weight: 800; 
        color: var(--header-color); 
        text-align: center; 
        border-bottom: 2px solid #bae6fd; 
        text-transform: uppercase;
    }
    .col-item { 
        border-right: 1px solid #bae6fd; 
        padding: 0 4px;
    }
    .col-item:last-child { border-right: none; }
    
    /* â”€â”€â”€ DATA ROW â”€â”€â”€ */
    .data-row { 
        display: grid; 
        grid-template-columns: 1fr 1.2fr 0.8fr 1fr; 
        padding: 14px 6px; 
        font-size: 12px; 
        text-align: center; 
        border-bottom: 1px solid var(--border-color); 
        align-items: center; 
        background-color: var(--card-bg);
        transition: background 0.2s;
    }
    .data-row:active { background-color: #f8fafc; }
    
    .profit-win { 
        color: #166534; 
        background: #dcfce7; 
        padding: 4px 6px; 
        border-radius: 4px; 
        border: 1px solid #bbf7d0; 
        display: inline-block; 
        width: 100%;
        font-size: 11px;
        font-weight: 700;
    }
    .profit-loss { 
        color: #991b1b; 
        background: #fee2e2; 
        padding: 4px 6px; 
        border-radius: 4px; 
        border: 1px solid #fecaca; 
        display: inline-block; 
        width: 100%;
        font-size: 11px;
        font-weight: 700;
    }

    /* â”€â”€â”€ EMPTY STATE â”€â”€â”€ */
    .no-data { 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        justify-content: center; 
        min-height: 40vh; 
        opacity: 0.8; 
        background: #ffffff;
    }
    .no-data .empty-icon {
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
    .no-data .empty-icon i {
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

    /* â”€â”€â”€ MODAL â”€â”€â”€ */
    .modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 100;
        background: rgba(0,0,0,0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        backdrop-filter: blur(4px);
    }
    .modal-overlay.hidden { display: none; }
    .modal-box {
        background: #ffffff;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        width: 100%;
        max-width: 360px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        border: 1px solid #e5e7eb;
    }
    .modal-icon-wrap {
        width: 80px;
        height: 80px;
        background: #fef2f2;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        border: 1px solid #fecaca;
        position: relative;
    }
    .modal-icon-wrap i.fa-filter {
        font-size: 40px;
        color: #fca5a5;
    }
    .modal-close-btn {
        width: 100%;
        background: #154b77;
        color: #ffffff;
        font-weight: 700;
        padding: 12px 32px;
        border-radius: 8px;
        font-size: 14px;
        border: none;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    .modal-close-btn:active { background: #0d2a45; }

    /* â”€â”€â”€ SUMMARY BAR â”€â”€â”€ */
    .fixed-bottom-summary {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        z-index: 90;
        background: #ffffff;
        box-shadow: 0 -4px 12px rgba(0,0,0,0.08);
        border-top: 1px solid var(--border-color);
    }
    .summary-inner {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
    }
    .summary-item {
        padding: 10px 16px;
        text-align: center;
        border-right: 1px solid var(--border-color);
    }
    .summary-item:last-child { border-right: none; }
    .summary-value {
        font-size: 14px;
        font-weight: 900;
    }
    .summary-value.green { color: #166534; }
    .summary-value.red { color: #991b1b; }
    .summary-label {
        font-size: 10px;
        color: var(--text-muted);
        text-transform: uppercase;
        font-weight: 600;
        margin-top: 2px;
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
        .filter-bar { top: 108px; }
    }
</style>

<div class="game-log-wrapper">

    <!-- HEADER -->
    <div class="header-bg">
        <a href="{{ route('user.account') }}"><i class="fas fa-chevron-left"></i></a>
        <h1>Betting Records</h1>
        <a href="{{ route('user.home') }}"><i class="fas fa-times"></i></a>
    </div>

    <!-- TABS -->
    <div class="tabs-container">
        <a href="{{ route('user.game.log', ['tab' => 'settled', 'days' => request()->days, 'provider' => request()->provider]) }}" 
           class="tab-btn {{ request()->tab != 'unsettled' ? 'active' : '' }}">Settled</a>
        <a href="{{ route('user.game.log', ['tab' => 'unsettled', 'days' => request()->days, 'provider' => request()->provider]) }}" 
           class="tab-btn {{ request()->tab == 'unsettled' ? 'active' : '' }}">Unsettled</a>
    </div>

    <!-- FILTER BAR -->
    <div class="filter-bar" style="position: relative;">
        <button class="date-btn" onclick="toggleDropdown()">
            <i class="far fa-calendar-alt" style="color:#154b77;"></i>
            <span id="selectedDate">
                @if($days == 'today') Today
                @elseif($days == 'yesterday') Yesterday
                @elseif($days == '7days') Last 7 days
                @elseif($days == '30days') Last 1 month
                @elseif($days == 'all') All time
                @else All time @endif
            </span> 
            <i class="fas fa-caret-down" style="margin-left:4px; font-size:10px;"></i>
        </button>
        
        <div id="dateDropdown" class="dropdown-menu">
            <a href="{{ route('user.game.log', ['days' => 'today', 'provider' => request()->provider, 'tab' => request()->tab]) }}" class="dropdown-item">Today</a>
            <a href="{{ route('user.game.log', ['days' => 'yesterday', 'provider' => request()->provider, 'tab' => request()->tab]) }}" class="dropdown-item">Yesterday</a>
            <a href="{{ route('user.game.log', ['days' => '7days', 'provider' => request()->provider, 'tab' => request()->tab]) }}" class="dropdown-item">Last 7 days</a>
            <a href="{{ route('user.game.log', ['days' => '30days', 'provider' => request()->provider, 'tab' => request()->tab]) }}" class="dropdown-item">Last 1 month</a>
            <a href="{{ route('user.game.log', ['days' => 'all', 'provider' => request()->provider, 'tab' => request()->tab]) }}" class="dropdown-item">All time</a>
        </div>

        <button class="filter-icon-btn" onclick="openApiModal()">
            <i class="fas fa-filter"></i>
        </button>
    </div>

    <!-- TABLE HEADER -->
    <div class="table-header">
        <div class="col-item">Platform</div>
        <div class="col-item">Game Type</div>
        <div class="col-item">Turnover</div>
        <div class="col-item">Profit/Loss</div>
    </div>

    <!-- DATA ROWS -->
    <div class="min-h-[50vh]" style="background:#fff;">
        @forelse($logs as $log)
            @php
                $isWin = ((float) $log->win_amo > (float) $log->invest) || (int) $log->win_status !== 0;
                $profitLoss = (float) $log->win_amo - (float) $log->invest;
                $displayName = $log->game_name ?: (optional($log->game)->name ?? 'Game Result');
                $provider = 'API';
                $gn = strtolower((string) ($log->game_name ?? ''));
                if (strlen($gn) >= 24 && ctype_xdigit($gn)) {
                    $provider = 'SPORTS';
                } elseif (optional($log->game)->name) {
                    $provider = strtoupper((string) strtok($log->game->name, ' '));
                }
            @endphp
            <div class="data-row">
                <div style="font-weight:700; font-size:11px;">{{ $provider }}</div>
                <div style="font-size:11px; color:#4b5563;">{{ __($displayName) }}</div>
                <div style="font-weight:700;">৳{{ number_format($log->invest, 2) }}</div>
                <div>
                    <span class="{{ $isWin ? 'profit-win' : 'profit-loss' }}">
                        {{ $isWin ? '+' : '' }}{{ number_format($profitLoss, 2) }}
                    </span>
                </div>
            </div>
        @empty
            <div class="no-data">
                <div class="empty-icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <p>@lang('No Records Found')</p>
            </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    @if($logs->hasPages())
        <div style="padding: 16px; background:#fff;">
            {{ paginateLinks($logs) }}
        </div>
    @endif

</div>

<!-- SUMMARY BAR -->
<div class="fixed-bottom-summary" style="bottom: 70px;">
    <div class="summary-inner">
        <div class="summary-item">
            <div class="summary-value green">৳{{ number_format($widget['bet_amount'] ?? 0, 2) }}</div>
            <div class="summary-label">Bet Amount</div>
        </div>
        <div class="summary-item">
            <div class="summary-value green">৳{{ number_format($widget['valid_bet'] ?? 0, 2) }}</div>
            <div class="summary-label">Valid Bet</div>
        </div>
        <div class="summary-item">
            <div class="summary-value green">৳{{ number_format($widget['winnings'] ?? 0, 2) }}</div>
            <div class="summary-label">Winnings</div>
        </div>
        <div class="summary-item">
            <div class="summary-value red">৳{{ number_format($widget['profit_loss'] ?? 0, 2) }}</div>
            <div class="summary-label">Profit/Loss</div>
        </div>
    </div>
</div>

<!-- FILTER MODAL -->
<div id="apiModal" class="modal-overlay hidden">
    <div class="modal-box">
        <div class="modal-icon-wrap">
            <i class="fas fa-filter"></i>
        </div>
        <h3 style="color:#154b77; font-size:18px; font-weight:900; margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px;">
            Filter Not Available
        </h3>
        <p style="font-size:12px; color:#6b7280; margin-bottom:24px; font-weight:500;">
            Advanced filtering options are currently disabled.
        </p>
        <button onclick="document.getElementById('apiModal').classList.add('hidden')" class="modal-close-btn">
            Close
        </button>
    </div>
</div>

@include($activeTemplate . 'partials.mobile_bottom_nav')

<script>
    function toggleDropdown() {
        const dropdown = document.getElementById('dateDropdown');
        dropdown.classList.toggle('show');
    }

    function openApiModal() {
        document.getElementById('apiModal').classList.remove('hidden');
    }

    window.onclick = function(event) {
        if (!event.target.closest('.date-btn')) {
            var dropdowns = document.getElementsByClassName("dropdown-menu");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
</script>

@endsection