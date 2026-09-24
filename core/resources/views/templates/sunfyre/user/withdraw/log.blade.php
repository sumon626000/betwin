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

    .withdraw-history-wrapper {
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
        text-decoration: none;
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
        cursor: pointer;
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

    /* â”€â”€â”€ DETAILS MODAL â”€â”€â”€ */
    .modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 100;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }
    .modal-overlay.hidden { display: none; }
    .modal-box {
        background: #ffffff;
        border-radius: 12px;
        width: 100%;
        max-width: 400px;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }
    .modal-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-header h3 {
        font-size: 16px;
        font-weight: 800;
        color: var(--header-color);
        text-transform: uppercase;
    }
    .modal-close {
        font-size: 20px;
        color: #9ca3af;
        cursor: pointer;
        background: none;
        border: none;
    }
    .modal-body {
        padding: 16px 20px;
    }
    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .detail-item:last-child { border-bottom: none; }
    .detail-label {
        font-size: 12px;
        color: #9ca3af;
        font-weight: 600;
    }
    .detail-value {
        font-size: 13px;
        color: #333;
        font-weight: 700;
        text-align: right;
    }
    .feedback-box {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 8px;
        padding: 12px;
        margin-top: 12px;
    }
    .feedback-box h6 {
        color: #dc2626;
        font-size: 12px;
        font-weight: 800;
        margin-bottom: 4px;
    }
    .feedback-box p {
        color: #991b1b;
        font-size: 11px;
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
        font-size: 12px;
        color: #9ca3af;
        font-weight: 700;
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

    @media (min-width: 900px) {
        .bottom-nav-container { max-width: 600px; }
    }
</style>

<div class="withdraw-history-wrapper">

    <!-- HEADER -->
    <div class="header-bg">
        <a href="{{ route('user.account') }}"><i class="fas fa-chevron-left"></i></a>
        <span class="page-accent-icon navy"><i class="fas fa-money-bill-transfer"></i></span>
        <h1>@lang('Withdrawal Records')</h1>
        <a href="{{ route('user.home') }}"><i class="fas fa-times"></i></a>
    </div>

    <!-- TABS -->
    <div class="tabs-container">
        <a href="{{ route('user.withdraw.history', ['status' => 'all']) }}" 
           class="tab-btn {{ request('status', 'all') == 'all' ? 'active' : '' }}">@lang('ALL')</a>
        <a href="{{ route('user.withdraw.history', ['status' => 'pending']) }}" 
           class="tab-btn {{ request('status') == 'pending' ? 'active' : '' }}">@lang('PENDING')</a>
        <a href="{{ route('user.withdraw.history', ['status' => 'approved']) }}" 
           class="tab-btn {{ request('status') == 'approved' ? 'active' : '' }}">@lang('SUCCESS')</a>
    </div>

    <!-- TABLE HEADER -->
    <div class="tbl-header">
        <div class="tbl-col">Method</div>
        <div class="tbl-col">Amount</div>
        <div class="tbl-col">Status</div>
        <div class="tbl-col">Date</div>
    </div>

    <!-- DATA ROWS -->
    <div style="min-height: 60vh; background: #fff;">
        @forelse($withdraws as $withdraw)
            @php
                $statusClass = match($withdraw->status) {
                    1 => 'st-approved',
                    2 => 'st-pending',
                    3 => 'st-rejected',
                    default => 'st-pending'
                };
                $statusText = match($withdraw->status) {
                    1 => 'Approved',
                    2 => 'Pending',
                    3 => 'Rejected',
                    default => 'Pending'
                };
                $methodName = optional($withdraw->method)->name ?? 'Withdraw';
                
                $details = [];
                if($withdraw->withdraw_information){
                    foreach ($withdraw->withdraw_information as $key => $info) {
                        if(is_object($info)){
                            $details[] = ['name' => $info->name ?? $key, 'value' => $info->value ?? ''];
                        } else {
                            $details[] = ['name' => $key, 'value' => $info];
                        }
                    }
                }
            @endphp
            <div class="data-row" onclick="showDetails({{ json_encode($details) }}, '{{ $withdraw->status == 3 ? $withdraw->admin_feedback : '' }}', '{{ __($methodName) }}', '{{ showDateTime($withdraw->created_at, 'd M Y, h:i A') }}', '{{ $withdraw->trx }}', '{{ number_format($withdraw->amount, 0) }}', '{{ $statusText }}')">
                <div style="font-weight:700; font-size:11px;">{{ __($methodName) }}</div>
                <div style="font-weight:700;">৳{{ number_format($withdraw->amount, 0) }}</div>
                <div>
                    <span class="{{ $statusClass }}">{{ $statusText }}</span>
                </div>
                <div style="font-size:11px; color:#6b7280;">{{ showDateTime($withdraw->created_at, 'd M Y') }}</div>
            </div>
        @empty
            <div class="no-data">
                <div class="empty-icon-wrap">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <p>No withdrawal records found</p>
            </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    @if($withdraws->hasPages())
        <div style="padding: 16px; text-align: center; background: #fff;">
            {{ paginateLinks($withdraws) }}
        </div>
    @endif

</div>

<!-- DETAILS MODAL -->
<div id="detailsModal" class="modal-overlay hidden">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Withdraw Details</h3>
            <button onclick="closeModal()" class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <div class="detail-item">
                <span class="detail-label">Method</span>
                <span class="detail-value" id="modalMethod"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Amount</span>
                <span class="detail-value" id="modalAmount"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">TRX</span>
                <span class="detail-value" id="modalTrx"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Date</span>
                <span class="detail-value" id="modalDate"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Status</span>
                <span class="detail-value" id="modalStatus"></span>
            </div>
            <div id="modalDetailsContainer"></div>
            <div id="modalFeedback" class="feedback-box hidden"></div>
        </div>
    </div>
</div>

@include($activeTemplate . 'partials.mobile_bottom_nav')

<script>
    function showDetails(details, feedback, method, date, trx, amount, status) {
        document.getElementById('modalMethod').textContent = method;
        document.getElementById('modalAmount').textContent = '৳' + amount;
        document.getElementById('modalTrx').textContent = trx;
        document.getElementById('modalDate').textContent = date;
        document.getElementById('modalStatus').textContent = status;

        let detailsHtml = '';
        if (details && details.length > 0) {
            details.forEach(function(item) {
                detailsHtml += `
                    <div class="detail-item">
                        <span class="detail-label">${item.name}</span>
                        <span class="detail-value">${item.value}</span>
                    </div>`;
            });
        }
        document.getElementById('modalDetailsContainer').innerHTML = detailsHtml;

        const feedbackBox = document.getElementById('modalFeedback');
        if (feedback) {
            feedbackBox.classList.remove('hidden');
            feedbackBox.innerHTML = `
                <h6>Admin Feedback</h6>
                <p>${feedback}</p>`;
        } else {
            feedbackBox.classList.add('hidden');
        }

        document.getElementById('detailsModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('detailsModal').classList.add('hidden');
    }
</script>

@endsection