@php
// RapidVerse versév1: only this sports gameCode launches a real lobby.
$sportsGames = [
    [
        'id' => '92b24e4c25107367a80e0fe1a97c24e4',
        'name' => 'Lucky Sport',
        'provider' => 'luckysport',
        'img' => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyMDAgMjAwIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+IDxkZWZzPjxsaW5lYXJHcmFkaWVudCBpZD0iZyIgeDE9IjAiIHkxPSIwIiB4Mj0iMSIgeTI9IjEiPjxzdG9wIG9mZnNldD0iMCUiIHN0b3AtY29sb3I9IiMwYjNkMmUiLz48c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiMwNjIwMTgiLz48L2xpbmVhckdyYWRpZW50PjwvZGVmcz4gPHJlY3Qgd2lkdGg9IjIwMCIgaGVpZ2h0PSIyMDAiIGZpbGw9InVybCgjZykiLz4gPGNpcmNsZSBjeD0iMTAwIiBjeT0iNzgiIHI9IjQyIiBmaWxsPSJub25lIiBzdHJva2U9IiNmMGMwMzAiIHN0cm9rZS13aWR0aD0iNCIvPiA8dGV4dCB4PSIxMDAiIHk9Ijg4IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0iQXJpYWwgQmxhY2ssQXJpYWwsc2Fucy1zZXJpZiIgZm9udC1zaXplPSIyOCIgZm9udC13ZWlnaHQ9IjkwMCIgZmlsbD0iI2YwYzAzMCI+TFM8L3RleHQ+IDx0ZXh0IHg9IjEwMCIgeT0iMTQ4IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0iQXJpYWwsc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNiIgZm9udC13ZWlnaHQ9IjcwMCIgZmlsbD0iI2ZmZmZmZiI+TFVDS1k8L3RleHQ+IDx0ZXh0IHg9IjEwMCIgeT0iMTY4IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0iQXJpYWwsc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxMiIgZmlsbD0iIzg2ZWZhYyI+U1BPUlQ8L3RleHQ+IDwvc3ZnPg==',
    ],
];
@endphp

@foreach ($sportsGames as $game)
    <div class="game-card" data-category="sports">
        @auth
            <a href="{{ url('user/jili/launch?game_code='.$game['id'].'&provider='.$game['provider']) }}" class="game-card-img" title="{{ $game['name'] }}">
        @else
            <a href="{{ route('user.login') }}" class="game-card-img" title="{{ $game['name'] }}">
        @endauth
                <img src="{{ $game['img'] }}" alt="{{ $game['name'] }}" loading="lazy">
            </a>
        <div class="game-card-name" style="font-size:11px;font-weight:700;text-align:center;padding:4px 2px 6px;color:#123b66;">{{ $game['name'] }}</div>
    </div>
@endforeach
