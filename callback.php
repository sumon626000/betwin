<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json');

// Domain DB (same as ZIP structure — only credentials changed)
define('DB_HOST','localhost');
define('DB_USER','u811189100_betwin');
define('DB_PASS','^dtc?6r@eIT/tcP-');
define('DB_NAME','u811189100_betwin');

$host = DB_HOST;
$user = DB_USER;
$pass = DB_PASS;
$name = DB_NAME;
$envPath = __DIR__ . '/core/.env';
if (is_readable($envPath)) {
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) continue;
        [$k, $v] = explode('=', $line, 2);
        $k = trim($k);
        $v = trim($v);
        if ((str_starts_with($v, '"') && str_ends_with($v, '"')) || (str_starts_with($v, "'") && str_ends_with($v, "'"))) {
            $v = substr($v, 1, -1);
        }
        if ($k === 'DB_HOST') $host = $v;
        if ($k === 'DB_USERNAME') $user = $v;
        if ($k === 'DB_PASSWORD') $pass = $v;
        if ($k === 'DB_DATABASE') $name = $v;
    }
}

/**
 * Write admin_notifications row (rate-limited by title key file).
 */
function rv_admin_alert(mysqli $conn, string $title, string $dedupeKey = ''): void
{
    $title = mb_substr($title, 0, 250);
    if ($dedupeKey !== '') {
        $dir = __DIR__ . '/core/storage/framework/cache';
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $file = $dir . '/cb_alert_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $dedupeKey) . '.lock';
        if (is_file($file) && (time() - filemtime($file)) < 300) {
            return;
        }
        @file_put_contents($file, (string) time());
    }

    try {
        $stmt = $conn->prepare('INSERT INTO admin_notifications (user_id, title, click_url, is_read, created_at, updated_at) VALUES (0, ?, ?, 0, NOW(), NOW())');
        if (!$stmt) {
            return;
        }
        $url = '#';
        $stmt->bind_param('ss', $title, $url);
        $stmt->execute();
        $stmt->close();
    } catch (Throwable $e) {
        // never break wallet settle
    }
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!$data) {
    // Empty/health probes from RapidVerse should not spam alerts
    if ($raw !== false && trim((string) $raw) !== '') {
        // connect briefly to log if possible
        $tmp = @new mysqli($host, $user, $pass, $name);
        if (!$tmp->connect_error) {
            rv_admin_alert($tmp, 'CALLBACK FAIL: invalid JSON body', 'bad_json');
            $tmp->close();
        }
    }
    echo json_encode(['code' => 1]);
    exit;
}

$userId   = (int)$data['user_id'];
$gameId   = 0;
$gameName = !empty($data['game_code']) ? trim($data['game_code']) : "API Game";
$gameName = substr($gameName, 0, 40);
$bet      = (float)$data['bet_amount'];
$win      = (float)$data['win_amount'];
$serial   = substr(trim((string)$data['serial_number']), 0, 100);
$winStat  = $win > $bet ? 1 : 0;

$conn = new mysqli($host, $user, $pass, $name);
if ($conn->connect_error) {
    echo json_encode(['code'=>1]);
    exit;
}
$conn->set_charset('utf8mb4');

// Deduplicate by serial
$chk = $conn->prepare("SELECT id FROM game_logs WHERE serial_number=?");
$chk->bind_param("s",$serial);
$chk->execute();
if($chk->get_result()->num_rows){ echo json_encode(['code'=>0]); exit; }

$q = $conn->prepare("SELECT balance, turnover_requirement FROM users WHERE id=?");
$q->bind_param("i",$userId);
$q->execute();
$userData = $q->get_result()->fetch_assoc();
if(!$userData){
    rv_admin_alert($conn, 'CALLBACK FAIL: user not found #' . $userId, 'nouser_' . $userId);
    echo json_encode(['code'=>1]);
    exit;
}
$bal = (float)$userData['balance'];
$turnover_req = (float)$userData['turnover_requirement'];

$newBal = $bal - $bet + $win;

if($turnover_req > 0 && $bet > 0){
    $new_turnover = $turnover_req - $bet;
    if($new_turnover < 0) $new_turnover = 0;
} else {
    $new_turnover = $turnover_req;
}

$conn->begin_transaction();
try {
    $u = $conn->prepare("UPDATE users SET balance=?, turnover_requirement=? WHERE id=?");
    $u->bind_param("ddi", $newBal, $new_turnover, $userId);
    if (!$u->execute()) {
        throw new Exception('user update failed');
    }

    $l = $conn->prepare("INSERT INTO game_logs (user_id, game_id, game_name, invest, win_amo, serial_number, win_status, demo_play, status, created_at) VALUES (?,?,?,?,?,?,?,0,1,NOW())");
    $l->bind_param("iisddsi",$userId,$gameId,$gameName,$bet,$win,$serial,$winStat);
    if (!$l->execute()) {
        throw new Exception('game_log insert failed');
    }

    $conn->commit();
} catch (Throwable $e) {
    $conn->rollback();
    rv_admin_alert($conn, 'CALLBACK FAIL: ' . $e->getMessage() . ' user#' . $userId, 'txfail');
    echo json_encode(['code'=>1]);
    exit;
}

echo json_encode(['code'=>0,'balance'=>$newBal]);
