@php
$crashGames = [
    [
        "id" => "a04d1f3eb8ccec8a4823bdf18e3f0e84",
        "name" => "Aviator",
        "provider" => "spribe",
        "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/SPRIBE/aviator.png"
    ],
    [
        "id" => "edef29b5eda8e2eaf721d7315491c51d",
        "name" => "Go Rush",
        "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/JILI/224.png"
    ],
    [
        "id" => "a990de177577a2e6a889aaac5f57b429",
        "name" => "Fortune Gems",
        "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/JILI/109.png"
    ],
    [
        "id" => "984615c9385c42b3dad0db4a9ef89070",
        "name" => "Charge Buffalo",
        "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/JILI/47.png"
    ],
    [
        "id" => "8c62471fd4e28c084a61811a3958f7a1",
        "name" => "Crazy777",
        "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/JILI/35.png"
    ],
    [
        "id" => "bdfb23c974a2517198c5443adeea77a8",
        "name" => "Super Ace",
        "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/JILI/49.png"
    ],
    [
        "id" => "a47b17970036b37c1347484cf6956920",
        "name" => "Hyper Burst",
        "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/JILI/14.png"
    ],
    [
        "id" => "9a3b65e2ae5343df349356d548f3fc4b",
        "name" => "Wild Ace",
        "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/JILI/181.png"
    ],
    [
        "id" => "72ce7e04ce95ee94eef172c0dfd6dc17",
        "name" => "Mines",
        "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/JILI/229.png"
    ],
    [
        "id" => "e333695bcff28acdbecc641ae6ee2b23",
        "name" => "Bombing Fishing",
        "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/JILI/20.png"
    ],
    [
        "id" => "2f0c5f96cda3c6e16b3929dd6103df8e",
        "name" => "Wild Racer",
        "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/JILI/166.png"
    ],
    [
        "id" => "f02ede19c5953fce22c6098d860dadf4",
        "name" => "Boom Legend",
        "img" => "https://ossimg.91admin123admin.com/91club/gamelogo/JILI/71.png"
    ],
];
@endphp
@php if (!empty($homeTileLimit)) { $crashGames = array_slice($crashGames, 0, (int) $homeTileLimit); } @endphp

@foreach ($crashGames as $i => $game)
    @auth
        <a href="{{ url('user/jili/launch?game_code='.$game['id'].'&provider='.($game['provider'] ?? 'jili')) }}" class="game-card" data-status="1">
    @else
        <a href="{{ route('user.login') }}" class="game-card" data-status="1">
    @endauth
            <img class="game-card-img" src="{{ $game['img'] }}" alt="{{ $game['name'] }}" loading="lazy" decoding="async" referrerpolicy="no-referrer" width="120" height="120"
                 onerror="this.closest('.game-card')?.remove()">
            <div class="game-card-name">{{ $game['name'] }}</div>
        </a>
@endforeach
