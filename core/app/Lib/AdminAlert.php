<?php

namespace App\Lib;

use App\Models\AdminNotification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Admin bell + optional Telegram alerts (callback fail / API down / big withdraw).
 */
class AdminAlert
{
    /**
     * @param  string  $title     Short title shown in admin topnav
     * @param  string  $clickUrl  Relative admin path or '#'
     * @param  int     $userId    Related user (0 = system)
     * @param  string|null  $dedupeKey  Cache key to suppress spam (null = always alert)
     * @param  int     $dedupeSeconds
     */
    public static function send(
        string $title,
        string $clickUrl = '#',
        int $userId = 0,
        ?string $dedupeKey = null,
        int $dedupeSeconds = 600
    ): bool {
        if ($dedupeKey) {
            $cacheKey = 'admin_alert:' . $dedupeKey;
            if (Cache::has($cacheKey)) {
                return false;
            }
            Cache::put($cacheKey, 1, $dedupeSeconds);
        }

        try {
            $n = new AdminNotification();
            $n->user_id = $userId;
            $n->title = mb_substr($title, 0, 250);
            $n->click_url = $clickUrl ?: '#';
            $n->save();
        } catch (\Throwable $e) {
            Log::warning('AdminAlert DB failed: ' . $e->getMessage());
        }

        self::telegram($title);

        return true;
    }

    public static function apiDown(string $detail, string $provider = ''): void
    {
        $label = $provider !== '' ? strtoupper($provider) . ' — ' : '';
        self::send(
            'API DOWN: ' . $label . mb_substr($detail, 0, 180),
            urlPath('admin.api.game.index'),
            0,
            'api_down_' . md5($provider . '|' . substr($detail, 0, 80)),
            (int) env('ALERT_API_DOWN_DEDUP_SECONDS', 600)
        );
    }

    public static function bigWithdraw(int $userId, string $username, float $amount, int $withdrawId): void
    {
        $threshold = (float) env('BIG_WITHDRAW_THRESHOLD', 50000);
        if ($amount < $threshold) {
            return;
        }

        self::send(
            'BIG WITHDRAW ৳' . number_format($amount, 2) . ' from ' . $username,
            urlPath('admin.withdraw.data.details', $withdrawId),
            $userId,
            null
        );
    }

    public static function callbackFail(string $reason): void
    {
        self::send(
            'CALLBACK FAIL: ' . mb_substr($reason, 0, 200),
            urlPath('admin.api.game.index'),
            0,
            'callback_fail_' . md5(substr($reason, 0, 60)),
            (int) env('ALERT_CALLBACK_DEDUP_SECONDS', 300)
        );
    }

    private static function telegram(string $text): void
    {
        $token = trim((string) env('TELEGRAM_BOT_TOKEN', ''));
        $chatId = trim((string) env('TELEGRAM_CHAT_ID', ''));
        if ($token === '' || $chatId === '') {
            return;
        }

        try {
            $url = 'https://api.telegram.org/bot' . $token . '/sendMessage';
            CurlRequest::curlPostContent($url, [
                'chat_id' => $chatId,
                'text' => '🚨 ' . $text,
                'disable_web_page_preview' => 1,
            ]);
        } catch (\Throwable $e) {
            Log::warning('AdminAlert Telegram failed: ' . $e->getMessage());
        }
    }
}
