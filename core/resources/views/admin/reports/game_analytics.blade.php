@extends('admin.layouts.app')

@section('panel')
    <div class="row gy-4">
        <div class="col-12">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <p class="mb-0 text-muted">@lang('Launch counts + bet volume from wallet settles. Data starts after this feature is live.')</p>
                <form method="get" class="d-flex gap-2">
                    <select name="days" class="form-control form-control-sm" style="min-width:140px" onchange="this.form.submit()">
                        <option value="7" @selected($days == 7)>@lang('Last 7 days')</option>
                        <option value="30" @selected($days == 30)>@lang('Last 30 days')</option>
                        <option value="90" @selected($days == 90)>@lang('Last 90 days')</option>
                        <option value="0" @selected($days == 0)>@lang('All time')</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Top Providers (launches)')</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table--light style--two mb-0">
                            <thead>
                                <tr>
                                    <th>@lang('#')</th>
                                    <th>@lang('Provider')</th>
                                    <th>@lang('Launches')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topProviders as $i => $row)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td><strong>{{ $row->provider }}</strong></td>
                                        <td>{{ number_format($row->launches) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">@lang('No launch data yet')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Top Games (launches)')</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table--light style--two mb-0">
                            <thead>
                                <tr>
                                    <th>@lang('#')</th>
                                    <th>@lang('Provider')</th>
                                    <th>@lang('Game')</th>
                                    <th>@lang('Launches')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topGames as $i => $row)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $row->provider }}</td>
                                        <td><code>{{ $row->game_code }}</code></td>
                                        <td>{{ number_format($row->launches) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">@lang('No launch data yet')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Top Games by bet volume') @if($days > 0)<small class="text-muted">({{ $days }}d)</small>@endif</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table--light style--two mb-0">
                            <thead>
                                <tr>
                                    <th>@lang('#')</th>
                                    <th>@lang('Game code')</th>
                                    <th>@lang('Rounds')</th>
                                    <th>@lang('Total bet')</th>
                                    <th>@lang('Total win')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topByBet as $i => $row)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td><code>{{ $row->game_name }}</code></td>
                                        <td>{{ number_format($row->rounds) }}</td>
                                        <td>{{ showAmount($row->total_bet) }}</td>
                                        <td>{{ showAmount($row->total_win) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">@lang('No game log data')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
