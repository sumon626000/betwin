<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ApiGameControlController extends Controller
{
    public function index()
    {
        $pageTitle = 'API Game Management';
        $this->ensureSettingsTable();

        $apiGames = DB::table('api_game_controls')->get();
        $apiSettings = DB::table('api_game_settings')->orderBy('id')->first();

        if (!$apiSettings) {
            DB::table('api_game_settings')->insert([
                'api_url'      => env('RAPIDVERSE_API_URL', 'https://rapidverse.site/api/verse'),
                'api_token'    => env('RAPIDVERSE_API_TOKEN', ''),
                'secret_key'   => env('RAPIDVERSE_SECRET_KEY', ''),
                'callback_url' => env('RAPIDVERSE_CALLBACK_URL', 'https://bet369win.com/callback.php'),
                'agent_user'   => env('RAPIDVERSE_AGENT_USER', ''),
                'currency'     => env('RAPIDVERSE_CURRENCY', 'BDT'),
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
            $apiSettings = DB::table('api_game_settings')->orderBy('id')->first();
        }

        return view('admin.api_game.index', compact('pageTitle', 'apiGames', 'apiSettings'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:0,1,2,3',
        ]);

        DB::table('api_game_controls')->where('id', $id)->update([
            'status' => $request->status,
            'updated_at' => now()
        ]);

        $notify[] = ['success', 'Status updated successfully'];
        return back()->withNotify($notify);
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'api_url'      => 'required|url',
            'api_token'    => 'required|string|min:10',
            'secret_key'   => 'required|string|min:10',
            'callback_url' => 'required|url',
            'agent_user'   => 'nullable|string|max:100',
            'api_prefix'   => 'nullable|string|max:64',
            'currency'     => 'nullable|string|max:10',
        ]);

        $this->ensureSettingsTable();

        $payload = [
            'api_url'      => $request->api_url,
            'api_token'    => $request->api_token,
            'secret_key'   => $request->secret_key,
            'callback_url' => $request->callback_url,
            'agent_user'   => $request->agent_user,
            'currency'     => strtoupper($request->currency ?: 'BDT'),
            'updated_at'   => now(),
        ];
        if (Schema::hasColumn('api_game_settings', 'api_prefix')) {
            $payload['api_prefix'] = $request->api_prefix ?: 'nix6260006107';
        }

        $existing = DB::table('api_game_settings')->orderBy('id')->first();
        if ($existing) {
            DB::table('api_game_settings')->where('id', $existing->id)->update($payload);
        } else {
            $payload['created_at'] = now();
            DB::table('api_game_settings')->insert($payload);
        }

        $notify[] = ['success', 'RapidVerse API settings saved'];
        return back()->withNotify($notify);
    }

    private function ensureSettingsTable(): void
    {
        if (!Schema::hasTable('api_game_settings')) {
            Schema::create('api_game_settings', function ($table) {
                $table->id();
                $table->string('api_url', 255)->default('https://www.rapidverse.site/api/versev1');
                $table->text('api_token')->nullable();
                $table->text('secret_key')->nullable();
                $table->string('callback_url', 255)->nullable();
                $table->string('agent_user', 100)->nullable();
                $table->string('api_prefix', 64)->nullable();
                $table->string('currency', 10)->default('BDT');
                $table->timestamps();
            });
            return;
        }

        if (!Schema::hasColumn('api_game_settings', 'api_prefix')) {
            Schema::table('api_game_settings', function ($table) {
                $table->string('api_prefix', 64)->nullable()->after('agent_user');
            });
        }
    }
}
