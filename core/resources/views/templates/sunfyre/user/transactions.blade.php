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

    html, body {
        max-width: 100vw;
        overflow-x: hidden;
        touch-action: manipulation;
    }

    body { 
        background-color: var(--bg-color); 
        color: var(--text-main); 
        font-family: 'Roboto', sans-serif; 
        padding-bottom: 90px; 
        -webkit-tap-highlight-color: transparent;
        user-select: none;
    }

    .transactions-wrapper {
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

    /* â”€â”€â”€ TAB CONTENT â”€â”€â”€ */
    .tab-content { 
        display: none; 
        animation: fadeIn 0.3s ease; 
        padding-top: 15px; 
    }
    .tab-content.active { display: block; }
    @keyframes fadeIn { 
        from { opacity: 0; transform: translateY(5px); } 
        to { opacity: 1; transform: translateY(0); } 
    }

    /* â”€â”€â”€ DATA CARD â”€â”€â”€ */
    .data-card { 
        background-color: var(--card-bg); 
        padding: 16px; 
        margin: 0 15px 12px 15px;
        border-radius: 12px; 
        border: 1px solid var(--border-color); 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        box-shadow: 0 2px 6px rgba(0,0,0,0.02); 
        transition: all 0.2s;
    }
    .data-card:active { 
        transform: scale(0.98); 
        border-color: #bae6fd; 
        box-shadow: 0 4px 10px rgba(21, 75, 119, 0.08); 
    }

    .card-icon {
        width: 40px; 
        height: 40px; 
        border-radius: 8px;
        background: #f8fafc; 
        border: 1px solid var(--border-color);
        display: flex; 
        align-items: center; 
        justify-content: center;
        color: var(--header-color); 
        font-size: 18px; 
        margin-right: 12px;
        flex-shrink: 0;
    }
    .card-icon.green { color: #166534; background: #dcfce7; border-color: #bbf7d0; }
    .card-icon.red { color: #991b1b; background: #fee2e2; border-color: #fecaca; }

    .data-val { 
        font-size: 14px; 
        color: var(--header-color); 
        font-weight: 800; 
        text-transform: uppercase; 
        letter-spacing: 0.5px; 
        margin-bottom: 2px;
    }
    .data-label { 
        font-size: 11.5px; 
        color: var(--text-muted); 
        font-weight: 500;
    }

    .badge-running { 
        color: #92400e; 
        background: #fef3c7; 
        padding: 4px 8px; 
        border-radius: 4px; 
        border: 1px solid #fde68a; 
        font-size: 10px; 
        font-weight: 800; 
        text-transform: uppercase; 
        display: inline-block;
    }
    .badge-win { 
        color: #166534; 
        background: #dcfce7; 
        padding: 4px 8px; 
        border-radius: 4px; 
        border: 1px solid #bbf7d0; 
        font-size: 10px; 
        font-weight: 800; 
        text-transform: uppercase; 
        display: inline-block;
    }
    .badge-loss { 
        color: #991b1b; 
        background: #fee2e2; 
        padding: 4px 8px; 
        border-radius: 4px; 
        border: 1px solid #fecaca; 
        font-size: 10px; 
        font-weight: 800; 
        text-transform: uppercase; 
        display: inline-block;
    }

    .amount-positive {
        color: #166534;
        font-weight: 800;
    }
    .amount-negative {
        color: #991b1b;
        font-weight: 800;
    }

    /* â”€â”€â”€ EMPTY STATE â”€â”€â”€ */
    .no-data { 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        justify-content: center; 
        min-height: 50vh; 
        opacity: 0.8; 
    }
    .no-data .empty-icon-wrap {
        width: 80px;
        height: 80px;
        background: #f3f4f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        border: 1px solid #e5e7eb;
    }
    .no-data .empty-icon-wrap i {
        font-size: 36px;
        color: #d1d5db;
    }
    .no-data p {
        font-weight: 700;
        font-size: 14px;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .no-data .sub-text {
        font-size: 12px;
        margin-top: 4px;
        color: #9ca3af;
        font-weight: 500;
        text-transform: none;
        letter-spacing: 0;
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

<div class="transactions-wrapper">

    <!-- HEADER -->
    <div class="header-bg">
        <a href="{{ route('user.account') }}"><i class="fas fa-chevron-left"></i></a>
        <h1>Transactions</h1>
        <a href="{{ route('user.home') }}"><i class="fas fa-times"></i></a>
    </div>

    <!-- TABS -->
    <div class="tabs-container">
        <button class="tab-btn active" onclick="switchTab('all', this)">All</button>
        <button class="tab-btn" onclick="switchTab('deposit', this)">@lang('Deposits')</button>
        <button class="tab-btn" onclick="switchTab('withdraw', this)">@lang('Withdrawals')</button>
    </div>

    <!-- ALL TRANSACTIONS TAB -->
    <div id="all" class="tab-content active">
        @forelse($transactions as $trx)
            @php
                $isCredit = $trx->trx_type == '+';
                $isDeposit = str_contains(strtolower($trx->details ?? ''), 'deposit');
                $isWithdraw = str_contains(strtolower($trx->details ?? ''), 'withdraw');
            @endphp
            <div class="data-card" data-type="{{ $isDeposit ? 'deposit' : ($isWithdraw ? 'withdraw' : 'other') }}">
                <div style="display: flex; align-items: center; flex: 1; min-width: 0;">
                    <div class="card-icon {{ $isCredit ? 'green' : 'red' }}">
                        <i class="fas {{ $isCredit ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                    </div>
                    <div style="min-width: 0;">
                        <div class="data-val" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ __($trx->details) }}
                        </div>
                        <div class="data-label">{{ $trx->trx }}</div>
                        <div style="font-size: 10px; color: #9ca3af; margin-top: 2px;">
                            {{ showDateTime($trx->created_at, 'd M Y, h:i A') }}
                        </div>
                    </div>
                </div>
                <div style="text-align: right; flex-shrink: 0; margin-left: 12px;">
                    <div class="{{ $isCredit ? 'amount-positive' : 'amount-negative' }}" style="font-size: 15px; font-weight: 800;">
                        {{ $isCredit ? '+' : '-' }}৳{{ number_format($trx->amount, 0) }}
                    </div>
                    <span class="{{ $isCredit ? 'badge-win' : 'badge-loss' }}">
                        {{ __(keyToTitle($trx->remark)) }}
                    </span>
                </div>
            </div>
        @empty
            <div class="no-data">
                <div class="empty-icon-wrap">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <p>No Transactions Found</p>
                <span class="sub-text">Your transaction history will appear here.</span>
            </div>
        @endforelse
    </div>

    <!-- DEPOSITS TAB -->
    <div id="deposit" class="tab-content">
        @php $depositCount = 0; @endphp
        @foreach($transactions as $trx)
            @if(str_contains(strtolower($trx->details ?? ''), 'deposit'))
                @php $depositCount++; @endphp
                <div class="data-card">
                    <div style="display: flex; align-items: center; flex: 1; min-width: 0;">
                        <div class="card-icon green">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                        <div style="min-width: 0;">
                            <div class="data-val" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ __($trx->details) }}
                            </div>
                            <div class="data-label">{{ $trx->trx }}</div>
                            <div style="font-size: 10px; color: #9ca3af; margin-top: 2px;">
                                {{ showDateTime($trx->created_at, 'd M Y, h:i A') }}
                            </div>
                        </div>
                    </div>
                    <div style="text-align: right; flex-shrink: 0; margin-left: 12px;">
                        <div class="amount-positive" style="font-size: 15px; font-weight: 800;">
                            +৳{{ number_format($trx->amount, 0) }}
                        </div>
                        <span class="badge-win">Deposit</span>
                    </div>
                </div>
            @endif
        @endforeach
        @if($depositCount == 0)
            <div class="no-data">
                <div class="empty-icon-wrap">
                    <i class="fas fa-wallet"></i>
                </div>
                <p>@lang('No Deposits Found')</p>
                <span class="sub-text">Your deposit history will appear here.</span>
            </div>
        @endif
    </div>

    <!-- WITHDRAWALS TAB -->
    <div id="withdraw" class="tab-content">
        @php $withdrawCount = 0; @endphp
        @foreach($transactions as $trx)
            @if(str_contains(strtolower($trx->details ?? ''), 'withdraw'))
                @php $withdrawCount++; @endphp
                <div class="data-card">
                    <div style="display: flex; align-items: center; flex: 1; min-width: 0;">
                        <div class="card-icon red">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                        <div style="min-width: 0;">
                            <div class="data-val" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ __($trx->details) }}
                            </div>
                            <div class="data-label">{{ $trx->trx }}</div>
                            <div style="font-size: 10px; color: #9ca3af; margin-top: 2px;">
                                {{ showDateTime($trx->created_at, 'd M Y, h:i A') }}
                            </div>
                        </div>
                    </div>
                    <div style="text-align: right; flex-shrink: 0; margin-left: 12px;">
                        <div class="amount-negative" style="font-size: 15px; font-weight: 800;">
                            -৳{{ number_format($trx->amount, 0) }}
                        </div>
                        <span class="badge-loss">Withdraw</span>
                    </div>
                </div>
            @endif
        @endforeach
        @if($withdrawCount == 0)
            <div class="no-data">
                <div class="empty-icon-wrap">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <p>@lang('No Withdrawals Found')</p>
                <span class="sub-text">Your withdrawal history will appear here.</span>
            </div>
        @endif
    </div>

    <!-- PAGINATION -->
    @if($transactions->hasPages())
        <div style="padding: 16px; text-align: center;">
            {{ paginateLinks($transactions) }}
        </div>
    @endif

</div>

@include($activeTemplate . 'partials.mobile_bottom_nav')

<script>
    function switchTab(tabId, btn) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
        document.getElementById(tabId).classList.add('active');
        btn.classList.add('active');
    }
</script>

@endsection
