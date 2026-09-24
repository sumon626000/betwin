<?php

namespace App\Http\Controllers\GameProviders\Api;

use App\Http\Controllers\Controller;
use App\Lib\AdminAlert;
use App\Lib\GameLaunchStats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ApiGameController extends Controller
{
    public function launch(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('user.login');
        }

        $request->validate([
            'game_code' => 'required|string',
            'provider'  => 'nullable|string'
        ]);

        $user->refresh();
        $settings = $this->getApiSettings();

        $provider = $request->provider ?? 'JILI';
        $vendorCode = $this->getVendorCode($provider);

        if (empty($settings['api_url']) || empty($settings['api_token'])) {
            AdminAlert::apiDown('Missing API URL or token', $provider);
            return back()->withErrors('Game API is not configured');
        }

        // Official docs: https://rapidverse.site/api-docs — required launch fields only.
        // returnUrl = player return page (NOT wallet callback; wallet is set in RapidVerse panel).
        $payload = [
            'userId'      => (string) $user->id,
            'gameCode'    => $request->game_code,
            'userBalance' => round((float) $user->balance, 2),
            'vendorCode'  => $vendorCode,
            'language'    => '0',
            'phonetype'   => '1',
            'returnUrl'   => route('user.home'),
        ];

        $ch = curl_init($settings['api_url']);

        $headers = [
            'Content-Type: application/json',
            'X-API-Token: ' . $settings['api_token'],
            'X-Secret-Key: ' . $settings['secret_key'],
        ];

        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            AdminAlert::apiDown('Connection: ' . $error, $provider);
            return back()->withErrors('Connection error: ' . $error);
        }

        $result = json_decode($response, true);

        // Live API: {code:0, data:{url}} — docs sample also shows {status, gameUrl}
        $gameUrl = $result['data']['url']
            ?? $result['gameUrl']
            ?? $result['data']['gameUrl']
            ?? null;

        $ok = $httpCode === 200 && $gameUrl
            && (
                (isset($result['code']) && (int) $result['code'] === 0)
                || (($result['status'] ?? '') === 'success')
            );

        if (!$ok) {
            $msg = $result['msg'] ?? $result['message'] ?? 'Game server error';
            // Rate-limit alerts for transport / auth failures (not every bad game code)
            if ($httpCode === 0 || $httpCode >= 500 || $httpCode === 401 || $httpCode === 403) {
                AdminAlert::apiDown("HTTP $httpCode — $msg", $provider);
            }
            return back()->withErrors($msg);
        }

        GameLaunchStats::record($provider, (string) $request->game_code);

        return view('templates.sunfyre.gamelunch', [
            'game_url' => $gameUrl,
            'game_provider' => strtoupper($provider),
            'pageTitle' => strtoupper($provider) . ' Game'
        ]);
    }

    private function getApiSettings(): array
    {
        $defaults = [
            'api_url'      => env('RAPIDVERSE_API_URL', 'https://rapidverse.site/api/verse'),
            'api_token'    => env('RAPIDVERSE_API_TOKEN', ''),
            'secret_key'   => env('RAPIDVERSE_SECRET_KEY', ''),
            'callback_url' => env('RAPIDVERSE_CALLBACK_URL', 'https://bet369win.com/callback.php'),
        ];

        if (!Schema::hasTable('api_game_settings')) {
            return $defaults;
        }

        $row = DB::table('api_game_settings')->orderBy('id')->first();
        if (!$row) {
            return $defaults;
        }

        return [
            'api_url'      => $row->api_url ?: $defaults['api_url'],
            'api_token'    => $row->api_token ?: $defaults['api_token'],
            'secret_key'   => $row->secret_key ?: $defaults['secret_key'],
            'callback_url' => $row->callback_url ?: $defaults['callback_url'],
        ];
    }

    private function getVendorCode(string $provider): string
    {
        $slug = strtolower($provider);
        foreach (config('rapidverse_providers', []) as $p) {
            if (($p['slug'] ?? '') === $slug && !empty($p['vendor'])) {
                return (string) $p['vendor'];
            }
        }

        return match ($slug) {
            'pg' => 'PG',
            'jdb' => 'JDB',
            'cq9' => 'CQ9',
            'g9' => 'G9',
            'evo', 'casino' => 'EVOLUTION',
            'card365' => 'Card365',
            'idg' => 'IDG',
            'km' => 'KM',
            'v8' => 'V8',
            'mg' => 'MG',
            'bti' => 'BTI',
            'saba', 'sb', 'sabasports' => 'SABASPORTS',
            'luckysport', 'ls' => 'LuckySport',
            'cmd' => 'CMD',
            '9wicket', '9w' => '9Wicket',
            'ug', 'unitedgaming' => 'UG',
            'tf' => 'TF',
            'sbo' => 'SBO',
            'dpsports', 'dps' => 'DPSports',
            'dpesports', 'esport' => 'DPEsports',
            'spribe', 'aviator' => 'SPRIBE',
            'pp', 'pp_asia', 'pp_live', 'pp_live_asia' => 'PP',
            'fc' => 'FC',
            'tada' => 'TADA',
            'jili', 'hot', 'crash', 'sports', 'default' => 'JILI',
            default => strtoupper($provider),
        };
    }
}
