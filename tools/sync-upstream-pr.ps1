# Keep battleasiav2/betwin PR #1 current (fork has no direct push rights).
# Usage: powershell -ExecutionPolicy Bypass -File tools/sync-upstream-pr.ps1
$ErrorActionPreference = "Stop"
$BASE = "battleasiav2/betwin"
# Existing PR tracks betwin-1
$HEAD = "sumon626000/betwin-1:main"
$existing = gh pr list --repo $BASE --head "sumon626000:main" --state open --json number --jq ".[0].number // empty"
if (-not $existing) {
  $existing = gh pr list --repo $BASE --state open --json number,headRepository --jq ".[0].number // empty"
}
$body = @"
## Summary
- Synced from fork so Hostinger live does not lag.
- Merge this PR (or grant write access to sumon626000) to keep production current.

## Includes
- Admin alerts (callback fail / API down / big withdraw)
- Game Analytics report
- Jackpot, provider logos, favorites, full game lists

## Test plan
- [ ] Admin alerts for callback / API / big withdraw
- [ ] Admin -> Report -> Game Analytics
- [ ] Jackpot + provider logos
"@
if ($existing) {
  gh pr edit $existing --repo $BASE --title "Sync fork: alerts, analytics, lobby updates" --body $body
  gh pr comment $existing --repo $BASE --body "Fork main updated — please merge so live stays current."
  Write-Host "Updated PR #$existing -> https://github.com/$BASE/pull/$existing"
} else {
  gh pr create --repo $BASE --head $HEAD --base main --title "Sync fork -> live" --body $body
}
