@php
// Unique RapidVerse Game UIDs from provider sheet (each sports vendor has its own hash).
$sportsGames = [
    [
        'id' => '92b24e4c25107367a80e0fe1a97c24e4',
        'name' => 'Lucky Sport',
        'provider' => 'luckysport',
        'img' => 'https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-awcv2_fb.png?v=1&source=mcdsrc',
    ],
    [
        'id' => '08ced9dd788aed11ff3c7f387ae0f063',
        'name' => 'SABA Sports',
        'provider' => 'sabasports',
        'img' => 'https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-saba.png?v=1&source=mcdsrc',
    ],
    [
        'id' => '4d31f1186a81e208c003a7e37411ce35',
        'name' => 'BTI Sports',
        'provider' => 'bti',
        'img' => 'https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-awcv2_bti.png?v=1&source=mcdsrc',
    ],
    [
        'id' => '1f7fbf84bf1bcc08c3a7ea27db75f366',
        'name' => 'CMD Sports',
        'provider' => 'cmd',
        'img' => 'https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-awcv2_cmd.png?v=1&source=mcdsrc',
    ],
    [
        'id' => 'c4b2813f6bbc5abf502ddfb857e604eb',
        'name' => 'United Gaming',
        'provider' => 'ug',
        'img' => 'https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-awcv2_im.png?v=1&source=mcdsrc',
    ],
    [
        'id' => '341827d4370bb198b18364e2d75e6916',
        'name' => 'SBO',
        'provider' => 'sbo',
        'img' => 'https://img.b6814jd.com/bjd/h5/assets/images/brand/white/provider-saba.png?v=1&source=mcdsrc',
    ],
    [
        'id' => '4ee8e0051a035b463b47c3c473ce317d',
        'name' => 'TF Sports',
        'provider' => 'tf',
        'img' => '',
    ],
    [
        'id' => '23c2dca76f87d7b7f239833060c8751e',
        'name' => 'DP Sports',
        'provider' => 'dpsports',
        'img' => '',
    ],
    [
        'id' => 'e130116fdc9bcde2dbb31735b6c365d6',
        'name' => 'DP Esports',
        'provider' => 'dpesports',
        'img' => '',
    ],
];

$fallbackSvg = function (string $label, string $bg1 = '#123b66', string $bg2 = '#0f2d4a') {
    $t = strtoupper(mb_substr(preg_replace('/[^A-Za-z0-9]/', '', $label), 0, 3) ?: 'SP');
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="'.$bg1.'"/><stop offset="100%" stop-color="'.$bg2.'"/></linearGradient></defs><rect width="200" height="200" fill="url(#g)"/><text x="100" y="112" text-anchor="middle" font-family="Arial Black,Arial,sans-serif" font-size="42" font-weight="900" fill="#f0c030">'.$t.'</text></svg>';
    return 'data:image/svg+xml;base64,' . base64_encode($svg);
};
@endphp

@foreach ($sportsGames as $game)
    @php
        $img = $game['img'] !== '' ? $game['img'] : $fallbackSvg($game['name']);
    @endphp
    <div class="game-card" data-category="sports">
        @auth
            <a href="{{ url('user/jili/launch?game_code='.$game['id'].'&provider='.$game['provider']) }}" class="game-card-img" title="{{ $game['name'] }}">
        @else
            <a href="{{ route('user.login') }}" class="game-card-img" title="{{ $game['name'] }}">
        @endauth
                <img src="{{ $img }}" alt="{{ $game['name'] }}" loading="lazy" referrerpolicy="no-referrer"
                     onerror="this.onerror=null;this.src='{{ $fallbackSvg($game['name']) }}';">
            </a>
        <div class="game-card-name" style="font-size:11px;font-weight:700;text-align:center;padding:4px 2px 6px;color:#123b66;">{{ $game['name'] }}</div>
    </div>
@endforeach
