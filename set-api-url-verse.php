<?php
/**
 * One-shot: point live api_game_settings + .env to official /api/verse (docs).
 * Self-deletes after run.
 */
header('Content-Type: application/json');
$url = 'https://rapidverse.site/api/verse';
$out = ['api_url' => $url];

$env = __DIR__ . '/core/.env';
if (is_readable($env)) {
    $txt = file_get_contents($env);
    $txt2 = preg_replace('/^RAPIDVERSE_API_URL=.*$/m', 'RAPIDVERSE_API_URL=' . $url, $txt);
    if ($txt2 !== null && $txt2 !== $txt) {
        file_put_contents($env, $txt2);
        $out['env'] = 'updated';
    } else {
        $out['env'] = 'unchanged_or_missing';
    }
}

try {
    require __DIR__ . '/core/vendor/autoload.php';
    $app = require __DIR__ . '/core/bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    if (Illuminate\Support\Facades\Schema::hasTable('api_game_settings')) {
        $n = Illuminate\Support\Facades\DB::table('api_game_settings')->update(['api_url' => $url]);
        $out['db_rows'] = $n;
    }
} catch (Throwable $e) {
    $out['db_error'] = $e->getMessage();
}

@unlink(__FILE__);
echo json_encode($out);
