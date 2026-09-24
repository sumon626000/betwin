# Keep battleasiav2/betwin PR updated from this fork (no push rights required).
# Usage: pwsh tools/sync-upstream-pr.ps1
$ErrorActionPreference = "Stop"
$BASE = "battleasiav2/betwin"
$HEAD = "sumon626000/betwin:main"
$existing = gh pr list --repo $BASE --head $HEAD --state open --json number --jq ".[0].number // empty"
$body = @"
## Summary
- Synced from sumon626000/betwin so Hostinger live does not lag.
- Merge this PR (or grant write access to sumon626000) to keep production current.

## Test plan
- [ ] Admin alerts for callback / API / big withdraw
- [ ] Admin -> Report -> Game Analytics
- [ ] Jackpot + provider logos
"@
if ($existing) {
  gh pr edit $existing --repo $BASE --title "Sync fork -> live" --body $body
  gh pr comment $existing --repo $BASE --body "Fork main updated — please merge so live stays current.

Made with [Cursor](https://cursor.com)"
  Write-Host "Updated PR #$existing"
} else {
  gh pr create --repo $BASE --head $HEAD --base main --title "Sync fork -> live" --body $body
}
