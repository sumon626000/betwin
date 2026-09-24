<?php
/**
 * RapidVerse wallet callback (original payload format).
 * Expects: user_id, game_code, bet_amount, win_amount, serial_number
 * Also accepts camelCase + API-prefixed user ids.
 */
error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json');

function cb_log(string $msg, $ctx = null): void
{
    $dir = __DIR__ . '/core/storage/logs';
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    $line = date('Y-m-d H:i:s') . ' ' . $msg;
    if ($ctx !== null) {
        $line .= ' ' . (is_string($ctx) ? $ctx : json_encode($ctx, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
    @file_put_contents($dir . '/rapidverse_callback.log', $line . "\n", FILE_APPEND);
}

function cb_fail(string $msg, $ctx = null): void
{
    cb_log($msg, $ctx);
    echo json_encode(['code' => 1, 'msg' => $msg]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(['code' => 0, 'msg' => 'callback alive']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw ?: 'null', true);

// Panel / URL probe often POSTs empty body — treat as health check (not a bet).
if (!is_array($data) || $data === []) {
    cb_log('probe', $raw === false || $raw === '' ? '(empty)' : $raw);
    echo json_encode(['code' => 0, 'msg' => 'callback alive']);
    exit;
}

// DB from .env (Hostinger) — same as site
$db = [
    'host' => 'localhost',
    'user' => 'u811189100_betwin',
    'pass' => '',
    'name' => 'u811189100_betwin',
];
$apiPrefix = 'nix6260006107';
$envPath = __DIR__ . '/core/.env';
if (is_readable($envPath)) {
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$k, $v] = explode('=', $line, 2);
        $k = trim($k);
        $v = trim($v);
        if ((str_starts_with($v, '"') && str_ends_with($v, '"')) || (str_starts_with($v, "'") && str_ends_with($v, "'"))) {
            $v = substr($v, 1, -1);
        }
        match ($k) {
            'DB_HOST' => $db['host'] = $v,
            'DB_USERNAME' => $db['user'] = $v,
            'DB_PASSWORD' => $db['pass'] = $v,
            'DB_DATABASE' => $db['name'] = $v,
            'RAPIDVERSE_API_PREFIX' => $apiPrefix = $v,
            default => null,
        };
    }
}

// Original RapidVerse fields (+ camelCase fallbacks)
$userRaw = $data['user_id'] ?? $data['userId'] ?? $data['member_account'] ?? $data['memberAccount'] ?? $data['username'] ?? 0;
$userRaw = trim((string) $userRaw);
if ($apiPrefix !== '' && str_starts_with($userRaw, $apiPrefix)) {
    $userRaw = substr($userRaw, strlen($apiPrefix));
}
$userId = (int) $userRaw;

$gameId = 0;
$gameName = trim((string) ($data['game_code'] ?? $data['gameCode'] ?? 'API Game'));
if ($gameName === '') {
    $gameName = 'API Game';
}
$gameName = substr($gameName, 0, 40);

$bet = (float) ($data['bet_amount'] ?? $data['betAmount'] ?? $data['bet'] ?? $data['amount'] ?? 0);
$win = (float) ($data['win_amount'] ?? $data['winAmount'] ?? $data['win'] ?? $data['payout'] ?? 0);
$serial = trim((string) ($data['serial_number'] ?? $data['serialNumber'] ?? $data['transaction_id'] ?? $data['transactionId'] ?? $data['txnId'] ?? ''));
$winStat = $win > $bet ? 1 : 0;

if ($userId <= 0) {
    cb_fail('bad_user', $data);
}

// Balance-only inquiry (optional)
$action = strtolower((string) ($data['action'] ?? $data['type'] ?? $data['method'] ?? ''));
$isBalanceOnly = in_array($action, ['balance', 'getbalance', 'get_balance', 'getuserbalance'], true)
    || ($serial === '' && $bet == 0.0 && $win == 0.0 && (isset($data['action']) || isset($data['type']) || isset($data['method'])));

if ($isBalanceOnly) {
    $conn = new mysqli($db['host'], $db['user'], $db['pass'], $db['name']);
    if ($conn->connect_error) {
        cb_fail('db_error', $conn->connect_error);
    }
    $conn->set_charset('utf8mb4');
    $q = $conn->prepare('SELECT balance FROM users WHERE id=? LIMIT 1');
    $q->bind_param('i', $userId);
    $q->execute();
    $row = $q->get_result()->fetch_assoc();
    if (!$row) {
        cb_fail('user_missing', $userId);
    }
    $bal = round((float) ($row['balance'] ?? 0), 2);
    echo json_encode(['code' => 0, 'balance' => $bal, 'userBalance' => $bal]);
    exit;
}

if ($serial === '') {
    $serial = 'auto-' . $userId . '-' . md5($raw . microtime(true));
}
$serial = substr($serial, 0, 100);

$conn = new mysqli($db['host'], $db['user'], $db['pass'], $db['name']);
if ($conn->connect_error) {
    cb_fail('db_error', $conn->connect_error);
}
$conn->set_charset('utf8mb4');

// Duplicate transaction check (original behavior)
$chk = $conn->prepare('SELECT id FROM game_logs WHERE serial_number=? LIMIT 1');
$chk->bind_param('s', $serial);
$chk->execute();
if ($chk->get_result()->num_rows) {
    $q = $conn->prepare('SELECT balance FROM users WHERE id=? LIMIT 1');
    $q->bind_param('i', $userId);
    $q->execute();
    $bal = round((float) ($q->get_result()->fetch_assoc()['balance'] ?? 0), 2);
    echo json_encode(['code' => 0, 'balance' => $bal]);
    exit;
}

$q = $conn->prepare('SELECT balance, turnover_requirement FROM users WHERE id=? LIMIT 1');
$q->bind_param('i', $userId);
$q->execute();
$userData = $q->get_result()->fetch_assoc();
if (!$userData) {
    cb_fail('user_missing', $userId);
}

$bal = (float) $userData['balance'];
$turnover_req = (float) $userData['turnover_requirement'];

if ($bet > $bal + 0.0001) {
    echo json_encode(['code' => 1, 'msg' => 'insufficient balance', 'balance' => $bal]);
    exit;
}

$newBal = round($bal - $bet + $win, 2);
if ($newBal < 0) {
    $newBal = 0.0;
}

if ($turnover_req > 0 && $bet > 0) {
    $new_turnover = max(0, $turnover_req - $bet);
} else {
    $new_turnover = $turnover_req;
}

$u = $conn->prepare('UPDATE users SET balance=?, turnover_requirement=? WHERE id=?');
$u->bind_param('ddi', $newBal, $new_turnover, $userId);
$u->execute();

$l = $conn->prepare('INSERT INTO game_logs (user_id, game_id, game_name, invest, win_amo, serial_number, win_status, demo_play, status, created_at) VALUES (?,?,?,?,?,?,?,0,1,NOW())');
$l->bind_param('iisddsi', $userId, $gameId, $gameName, $bet, $win, $serial, $winStat);
$l->execute();

cb_log('ok', ['user' => $userId, 'bet' => $bet, 'win' => $win, 'bal' => $newBal, 'serial' => $serial]);
echo json_encode(['code' => 0, 'balance' => $newBal, 'userBalance' => $newBal]);
