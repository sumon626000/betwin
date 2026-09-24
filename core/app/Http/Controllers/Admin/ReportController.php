<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\GameLaunchStats;
use App\Models\GameLog;
use App\Models\NotificationLog;
use App\Models\Transaction;
use App\Models\UserLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReportController extends Controller {
    public function transaction(Request $request, $userId = null) {
        $pageTitle = 'Transaction Logs';

        $remarks = Transaction::distinct('remark')->orderBy('remark')->get('remark');

        $transactions = Transaction::searchable(['trx', 'user:username'])->filter(['trx_type', 'remark'])->dateFilter()->orderBy('id', 'desc')->with('user');
        if ($userId) {
            $transactions = $transactions->where('user_id', $userId);
        }
        $transactions = $transactions->paginate(getPaginate());

        return view('admin.reports.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function transfer(Request $request, $userId = null)
    {
        $pageTitle = 'Transfer Logs';

        $transfers = Transaction::whereIn('remark', ['balance_transfer', 'receive_transfer'])
            ->searchable(['trx', 'user:username'])
            ->dateFilter()
            ->with('user')
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());
        return view('admin.reports.transfers', compact('pageTitle', 'transfers'));
    }

    public function loginHistory(Request $request) {
        $pageTitle = 'User Login History';
        $loginLogs = UserLogin::orderBy('id', 'desc')->searchable(['user:username'])->dateFilter()->with('user')->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs'));
    }

    public function loginIpHistory($ip) {
        $pageTitle = 'Login by - ' . $ip;
        $loginLogs = UserLogin::where('user_ip', $ip)->orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs', 'ip'));
    }

    public function notificationHistory(Request $request) {
        $pageTitle = 'Notification History';
        $logs      = NotificationLog::orderBy('id', 'desc')->searchable(['user:username'])->dateFilter()->with('user')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('pageTitle', 'logs'));
    }

    public function emailDetails($id) {
        $pageTitle = 'Email Details';
        $email     = NotificationLog::findOrFail($id);
        return view('admin.reports.email_details', compact('pageTitle', 'email'));
    }

    public function gameAnalytics(Request $request)
    {
        $pageTitle = 'Game Analytics';
        $days = (int) $request->get('days', 30);
        if (!in_array($days, [0, 7, 30, 90], true)) {
            $days = 30;
        }

        GameLaunchStats::ensureTable();

        $topProviders = collect();
        $topGames = collect();
        if (Schema::hasTable('game_launch_stats')) {
            $topProviders = DB::table('game_launch_stats')
                ->select('provider', DB::raw('SUM(launches) as launches'))
                ->groupBy('provider')
                ->orderByDesc('launches')
                ->limit(20)
                ->get();

            $topGames = DB::table('game_launch_stats')
                ->orderByDesc('launches')
                ->limit(30)
                ->get();
        }

        $betQuery = GameLog::query()
            ->select(
                'game_name',
                DB::raw('COUNT(*) as rounds'),
                DB::raw('SUM(invest) as total_bet'),
                DB::raw('SUM(win_amo) as total_win')
            )
            ->where('game_name', '!=', '')
            ->groupBy('game_name')
            ->orderByDesc('total_bet')
            ->limit(30);

        if ($days > 0) {
            $betQuery->where('created_at', '>=', now()->subDays($days));
        }

        $topByBet = $betQuery->get();

        return view('admin.reports.game_analytics', compact(
            'pageTitle',
            'topProviders',
            'topGames',
            'topByBet',
            'days'
        ));
    }
}
