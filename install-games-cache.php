<?php
/**
 * One-shot: clear Laravel compiled views after games import deploy.
 * Visit once then file self-deletes.
 */
$base = __DIR__;
$paths = [
    $base . '/core/storage/framework/views',
    $base . '/core/bootstrap/cache',
];
$cleared = 0;
foreach ($paths as $dir) {
    if (!is_dir($dir)) continue;
    foreach (glob($dir . '/*') as $f) {
        if (is_file($f) && basename($f) !== '.gitignore') {
            @unlink($f);
            $cleared++;
        }
    }
}
header('Content-Type: application/json');
echo json_encode(['ok' => true, 'cleared' => $cleared, 'games_json' => is_dir($base . '/games') ? count(glob($base . '/games/*.json')) : 0]);
@unlink(__FILE__);
