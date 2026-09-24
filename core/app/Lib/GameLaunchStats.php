<?php

namespace App\Lib;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class GameLaunchStats
{
    public static function ensureTable(): void
    {
        if (Schema::hasTable('game_launch_stats')) {
            return;
        }

        Schema::create('game_launch_stats', function ($table) {
            $table->bigIncrements('id');
            $table->string('provider', 40)->default('');
            $table->string('game_code', 80)->default('');
            $table->unsignedBigInteger('launches')->default(0);
            $table->timestamps();
            $table->unique(['provider', 'game_code'], 'gls_provider_game_unique');
        });
    }

    public static function record(string $provider, string $gameCode): void
    {
        try {
            self::ensureTable();
            $provider = strtoupper(substr(trim($provider) ?: 'UNKNOWN', 0, 40));
            $gameCode = substr(trim($gameCode) ?: 'unknown', 0, 80);
            $now = now()->toDateTimeString();

            DB::statement(
                'INSERT INTO game_launch_stats (provider, game_code, launches, created_at, updated_at)
                 VALUES (?, ?, 1, ?, ?)
                 ON DUPLICATE KEY UPDATE launches = launches + 1, updated_at = VALUES(updated_at)',
                [$provider, $gameCode, $now, $now]
            );
        } catch (\Throwable $e) {
            Log::warning('GameLaunchStats failed: ' . $e->getMessage());
        }
    }
}
