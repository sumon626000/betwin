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

    .deposit-history-wrapper {
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
        z-index: 40; 
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
    .header-bg button {
        color: #ffffff;
        font-size: 18px;
        padding: 4px;
        background: none;
        border: none;
        cursor: pointer;
    }

    /* â”€â”€â”€ FILTER BAR â”€â”€â”€ */
    .filter-bar {
        background-color: var(--card-bg);
        padding: 12px 16px;
        display: flex; 
        justify-content: space-between; 
        align-items: center;
        border-bottom: 1px solid var(--border-color);
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        position: sticky; 
        top: 56px; 
        z-index: 30;
    }
    .filter-date-badge {
        background: #e0f2fe;
        color: #154b77;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 6px 12px;
        border-radius: 4px;
        border: 1px solid #bae6fd;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .filter-open-btn {
        color: #154b77;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 6px;
        background: #eff6ff;
        padding: 6px 12px;
        border-radius: 6px;
        border: 1px solid #bfdbfe;
        cursor: pointer;
        transition: all 0.15s;
    }
    .filter-open-btn:active { transform: scale(0.95); }

    /* â”€â”€â”€ TABLE HEADER â”€â”€â”€ */
    .tbl-header {
        display: grid; 
        grid-template-columns: 1fr 1fr 1fr 1fr;
        background-color: #e0f2fe; 
        padding: 12px 5px; 
        font-size: 11px; 
        font-weight: 800; 
        color: var(--header-color);
        text-align: center; 
        border-bottom: 2px solid #bae6fd;
        text-transform: uppercase;
    }
    .tbl-col { 
        border-right: 1px solid #bae6fd; 
        padding: 0 2px;
    }
    .tbl-col:last-child { border-right: none; }

    /* â”€â”€â”€ DATA ROW â”€â”€â”€ */
    .data-row {
        display: grid; 
        grid-template-columns: 1fr 1fr 1fr 1fr;
        padding: 14px 5px; 
        font-size: 12px; 
        text-align: center;
        border-bottom: 1px solid var(--border-color); 
        align-items: center;
        background-color: var(--card-bg); 
        transition: background 0.2s;
    }
    .data-row:active { background-color: #f8fafc; }
    
    .st-approved { 
        color: #166534; 
        background: #dcfce7; 
        padding: 4px 6px; 
        border-radius: 4px; 
        border: 1px solid #bbf7d0; 
        font-size: 10px; 
        font-weight: 800; 
        text-transform: uppercase; 
        display: inline-block; 
        width: 100%;
    }
    .st-pending { 
        color: #92400e; 
        background: #fef3c7; 
        padding: 4px 6px; 
        border-radius: 4px; 
        border: 1px solid #fde68a; 
        font-size: 10px; 
        font-weight: 800; 
        text-transform: uppercase; 
        display: inline-block; 
        width: 100%;
    }
    .st-rejected { 
        color: #991b1b; 
        background: #fee2e2; 
        padding: 4px 6px; 
        border-radius: 4px; 
        border: 1px solid #fecaca; 
        font-size: 10px; 
        font-weight: 800; 
        text-transform: uppercase; 
        display: inline-block; 
        width: 100%;
    }

    /* â”€â”€â”€ EMPTY STATE â”€â”€â”€ */
    .no-data { 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        justify-content: center; 
        min-height: 60vh;
        opacity: 0.8; 
        background: #ffffff;
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

    /* â”€â”€â”€ FILTER MODAL â”€â”€â”€ */
    .filter-modal {
        position: fixed; 
        inset: 0; 
        z-index: 100;
        background-color: var(--bg-color);
        transform: translateX(100%); 
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }
    .filter-modal.open { transform: translateX(0); }
    
    .filter-modal-header {
        background: linear-gradient(135deg, #0f395c 0%, #1a5c92 100%);
        padding: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    .filter-modal-header h2 {
        color: #ffffff;
        font-size: 15px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .filter-modal-header button {
        color: #ffffff;
        font-size: 20px;
        padding: 4px;
        background: none;
        border: none;
        cursor: pointer;
    }

    .filter-modal-body {
        padding: 16px;
        flex: 1;
        overflow-y: auto;
        background: #ffffff;
    }
    .filter-modal-footer {
        padding: 16px;
        background: #ffffff;
        border-top: 1px solid var(--border-color);
        padding-bottom: 32px;
    }
    
    .filter-sec-title { 
        font-size: 11.5px; 
        font-weight: 800; 
        color: var(--header-color); 
        margin-bottom: 10px; 
        margin-top: 24px; 
        text-transform: uppercase; 
        letter-spacing: 0.5px; 
        border-left: 3px solid var(--accent-color); 
        padding-left: 8px;
    }
    .filter-sec-title:first-child { margin-top: 0; }
    
    .filter-btn {
        background-color: var(--card-bg); 
        color: var(--text-muted);
        font-size: 12px; 
        font-weight: 600; 
        padding: 12px 10px; 
        border-radius: 6px;
        text-align: center; 
        border: 1px solid var(--border-color); 
        transition: 0.2s;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02); 
        cursor: pointer;
        display: inline-block;
        width: 100%;
    }
    .filter-btn.active {
        background-color: var(--blue-light); 
        color: var(--header-color); 
        font-weight: 800; 
        border-color: #bae6fd; 
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
    }

    .apply-btn {
        width: 100%;
        background: linear-gradient(to bottom, #4caf50, #388e3c);
        color: #ffffff;
        font-weight: 800;
        font-size: 15px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    .apply-btn:active { transform: scale(0.98); }

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

<div class="deposit-history-wrapper">

    <!-- HEADER -->
    <div class="header-bg">
        <a href="{{ route('user.account') }}"><i class="fas fa-chevron-left"></i></a>
        <span class="page-accent-icon"><i class="fas fa-file-invoice-dollar"></i></span>
        <h1>@lang('Deposit Records')</h1>
        <button onclick="openFilter()"><i class="fas fa-filter"></i></button>
    </div>

    <!-- FILTER BAR -->
    <div class="filter-bar">
        <div class="filter-date-badge">
            <i class="far fa-calendar-alt"></i>
            <span id="currentFilterLabel">
                @if(request('date') == 'today') Today
                @elseif(request('date') == 'yesterday') Yesterday
                @elseif(request('date') == 'week') Last 7 days
                @else All @endif
            </span>
        </div>
        <button onclick="openFilter()" class="filter-open-btn">
            Filter <i class="fas fa-sliders-h" style="color:#43a047;"></i>
        </button>
    </div>

    <!-- TABLE HEADER -->
    <div class="tbl-header">
        <div class="tbl-col">Type</div>
        <div class="tbl-col">Amount</div>
        <div class="tbl-col">Status</div>
        <div class="tbl-col">Txn Date</div>
    </div>

    <!-- DATA ROWS -->
    <div class="min-h-[60vh]" style="background:#fff;">
        @forelse($deposits as $deposit)
            @php
                $statusClass = match($deposit->status) {
                    1 => 'st-approved',
                    2 => 'st-pending',
                    3 => 'st-rejected',
                    default => 'st-pending'
                };
                $statusText = match($deposit->status) {
                    1 => 'Approved',
                    2 => 'Pending',
                    3 => 'Rejected',
                    default => 'Pending'
                };
                $gatewayName = optional($deposit->gateway)->name ?? ($deposit->methodName() ?: 'Deposit');
            @endphp
            <div class="data-row">
                <div style="font-weight:700; font-size:11px;">{{ __($gatewayName) }}</div>
                <div style="font-weight:700;">৳{{ number_format($deposit->amount, 0) }}</div>
                <div>
                    <span class="{{ $statusClass }}">{{ $statusText }}</span>
                </div>
                <div style="font-size:11px; color:#6b7280;">{{ showDateTime($deposit->created_at, 'd M Y') }}</div>
            </div>
        @empty
            <div class="no-data">
                <div class="empty-icon-wrap">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <p>@lang('No Records Found')</p>
            </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    @if($deposits->hasPages())
        <div style="padding: 16px; text-align: center; background: #fff;">
            {{ paginateLinks($deposits) }}
        </div>
    @endif

</div>

<!-- FILTER MODAL -->
<div id="filterModal" class="filter-modal">
    <div class="filter-modal-header">
        <h2>Deposit Filter</h2>
        <button onclick="closeFilter()"><i class="fas fa-times"></i></button>
    </div>

    <div class="filter-modal-body">
        <p class="filter-sec-title">Status</p>
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 10px;">
            <div class="filter-btn f-status {{ !request('status') ? 'active' : '' }}" data-val="" onclick="selectFilter(this, 'status')">All</div>
            <div class="filter-btn f-status {{ request('status') == 'pending' ? 'active' : '' }}" data-val="pending" onclick="selectFilter(this, 'status')">Processing</div>
            <div class="filter-btn f-status {{ request('status') == 'rejected' ? 'active' : '' }}" data-val="rejected" onclick="selectFilter(this, 'status')">Rejected</div>
            <div class="filter-btn f-status {{ request('status') == 'approved' ? 'active' : '' }}" data-val="approved" onclick="selectFilter(this, 'status')">Approved</div>
        </div>

        <p class="filter-sec-title">Date</p>
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 10px;">
            <div class="filter-btn f-date {{ !request('date') ? 'active' : '' }}" data-val="" onclick="selectFilter(this, 'date')">All</div>
            <div class="filter-btn f-date {{ request('date') == 'today' ? 'active' : '' }}" data-val="today" onclick="selectFilter(this, 'date')">Today</div>
            <div class="filter-btn f-date {{ request('date') == 'yesterday' ? 'active' : '' }}" data-val="yesterday" onclick="selectFilter(this, 'date')">Yesterday</div>
            <div class="filter-btn f-date {{ request('date') == 'week' ? 'active' : '' }}" data-val="week" onclick="selectFilter(this, 'date')">Last 7 days</div>
        </div>
    </div>

    <div class="filter-modal-footer">
        <button onclick="applyFilters()" class="apply-btn">Apply Filter</button>
    </div>
</div>

@include($activeTemplate . 'partials.mobile_bottom_nav')

<script>
    let selectedStatus = '{{ request('status', '') }}';
    let selectedDate = '{{ request('date', '') }}';

    function openFilter() {
        document.getElementById('filterModal').classList.add('open');
    }

    function closeFilter() {
        document.getElementById('filterModal').classList.remove('open');
    }

    function selectFilter(btn, type) {
        const group = document.querySelectorAll('.f-' + type);
        group.forEach(el => el.classList.remove('active'));
        btn.classList.add('active');

        const val = btn.getAttribute('data-val');
        if (type === 'status') selectedStatus = val;
        if (type === 'date') selectedDate = val;
    }

    function applyFilters() {
        let url = '{{ route('user.deposit.history') }}?';
        if (selectedDate) url += 'date=' + selectedDate + '&';
        if (selectedStatus) url += 'status=' + selectedStatus + '&';
        window.location.href = url;
    }
</script>

@endsection
