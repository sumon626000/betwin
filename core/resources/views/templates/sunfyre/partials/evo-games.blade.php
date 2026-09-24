@php
$evoGames = [
    [
        "id" => "8ef39602e589bf9f32fc351b1cbb338b", 
        "name" => "Auto-Roulette", 
        "img" => "https://ossimg.dkwinpicture.com/dkwin/gamelogo/EVO_Video/48z5pjps3ntvqc1b.png"
    ],
    [
        "id" => "8ef39602e589bf9f32fc351b1cbb338b", 
        "name" => "Auto-Roulette", 
        "img" => "https://ossimg.dkwinpicture.com/dkwin/gamelogo/EVO_Video/lnofoyxv756qaezy.png"
    ],
    [
        "id" => "8ef39602e589bf9f32fc351b1cbb338b", 
        "name" => "Classic Speed Blackjack 97", 
        "img" => "https://ossimg.dkwinpicture.com/dkwin/gamelogo/EVO_Video/ruoirrlptcjroslk.png"
    ],
    [
        "id" => "917c0c51d248c33eb058e3210a2e7371", 
        "name" => "Crazy Time", 
        "img" => "https://ossimg.dkwinpicture.com/dkwin/gamelogo/EVO_Video/CrazyTime0000001.png"
    ],
    [
        "id" => "8405541014f364b7dc59657aa6892446", 
        "name" => "Funky Time", 
        "img" => "https://ossimg.dkwinpicture.com/dkwin/gamelogo/EVO_Video/FunkyTime0000001.png"
    ],
    [
        "id" => "814aa56348ac4165588f2a3e251f8732", 
        "name" => "Crazy Time A", 
        "img" => "https://ossimg.dkwinpicture.com/dkwin/gamelogo/EVO_Video/CrazyTime0000002.png"
    ],
    [
        "id" => "d496ac5fd91702331133e44b6bd12b26", 
        "name" => "MONOPOLY Live", 
        "img" => "https://ossimg.dkwinpicture.com/dkwin/gamelogo/EVO_Video/Monopoly00000001.png"
    ],
    [
        "id" => "d1365619bb9e9126b8ee9cbabb268853", 
        "name" => "Auto Lightning Roulette", 
        "img" => "https://ossimg.dkwinpicture.com/dkwin/gamelogo/EVO_Video/k37tle5hfceqacik.png"
    ],
    [
        "id" => "9b25f8d744859c6840d16ff6103dc5a6", 
        "name" => "Bac Bo", 
        "img" => "https://ossimg.dkwinpicture.com/dkwin/gamelogo/EVO_Video/BacBo00000000001.png"
    ],
    [
        "id" => "5cb6aa4e2ce1c775c568561401ffdfca", 
        "name" => "Fan Tan", 
        "img" => "https://ossimg.dkwinpicture.com/dkwin/gamelogo/EVO_Video/FanTan0000000001.png"
    ]
];
@endphp
@php if (!empty($homeTileLimit)) { $evoGames = array_slice($evoGames, 0, (int) $homeTileLimit); } @endphp

@foreach ($evoGames as $i => $game)
    <div class="swiper-slide game-item-box game-card" data-category="evo">
        @auth
            <a href="{{ url('user/jili/launch?game_code='.$game['id'].'&provider=evo') }}" class="game-card-img">
        @else
            <a href="{{ route('user.login') }}" class="game-card-img">
        @endauth
                <img src="{{ $i < ($homeEagerCount ?? 0) ? $game['img'] : '' }}" @if($i >= ($homeEagerCount ?? 0)) data-src="{{ $game['img'] }}" @endif alt="{{ $game['name'] }}" loading="{{ $i < ($homeEagerCount ?? 0) ? 'eager' : 'lazy' }}" @if($i < 3) fetchpriority="high" @endif decoding="async" referrerpolicy="no-referrer" width="120" height="120">
            </a>
    </div>
@endforeach