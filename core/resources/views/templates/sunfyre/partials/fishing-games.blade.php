@php
$fishingGames = [
    ["id"=>"e794bf5717aca371152df192341fe68b","name"=>"Royal Fishing","provider"=>"jili","img"=>"https://ossimg.91admin123admin.com/91club/gamelogo/JILI/1.png"],
    ["id"=>"e333695bcff28acdbecc641ae6ee2b23","name"=>"Bombing Fishing","provider"=>"jili","img"=>"https://ossimg.91admin123admin.com/91club/gamelogo/JILI/20.png"],
    ["id"=>"eef3e28f0e3e7b72cbca61e7924d00f1","name"=>"Dinosaur Tycoon","provider"=>"jili","img"=>"https://ossimg.91admin123admin.com/91club/gamelogo/JILI/42.png"],
    ["id"=>"3cf4a85cb6dcf4d8836c982c359cd72d","name"=>"Jackpot Fishing","provider"=>"jili","img"=>"https://ossimg.91admin123admin.com/91club/gamelogo/JILI/32.png"],
    ["id"=>"1200b82493e4788d038849bca884d773","name"=>"Dragon Fortune","provider"=>"jili","img"=>"https://ossimg.91admin123admin.com/91club/gamelogo/JILI/60.png"],
    ["id"=>"caacafe3f64a6279e10a378ede09ff38","name"=>"Mega Fishing","provider"=>"jili","img"=>"https://ossimg.91admin123admin.com/91club/gamelogo/JILI/74.png"],
    ["id"=>"f02ede19c5953fce22c6098d860dadf4","name"=>"Boom Legend","provider"=>"jili","img"=>"https://ossimg.91admin123admin.com/91club/gamelogo/JILI/71.png"],
    ["id"=>"71c68a4ddb63bdc8488114a08e603f1c","name"=>"Happy Fishing","provider"=>"jili","img"=>"https://ossimg.91admin123admin.com/91club/gamelogo/JILI/82.png"],
    ["id"=>"9ec2a18752f83e45ccedde8dfeb0f6a7","name"=>"All-star Fishing","provider"=>"jili","img"=>"https://ossimg.91admin123admin.com/91club/gamelogo/JILI/119.png"],
    ["id"=>"bbae6016f79f3df74e453eda164c08a4","name"=>"Dinosaur Tycoon II","provider"=>"jili","img"=>"https://ossimg.91admin123admin.com/91club/gamelogo/JILI/212.png"],
    ["id"=>"564c48d53fcddd2bcf0bf3602d86c958","name"=>"Ocean King Jackpot","provider"=>"jili","img"=>"https://ossimg.91admin123admin.com/91club/gamelogo/JILI/289.png"],
    ["id"=>"f2b04833d555ef9989748f9ecabd5249","name"=>"Fortune King Jackpot","provider"=>"jili","img"=>"https://ossimg.91admin123admin.com/91club/gamelogo/JILI/464.png"],
];
@endphp
@php if (!empty($homeTileLimit)) { $fishingGames = array_slice($fishingGames, 0, (int) $homeTileLimit); } @endphp

@foreach ($fishingGames as $i => $game)
    <div class="game-card" data-category="fishing">
        @auth
            <a href="{{ url('user/jili/launch?game_code='.$game['id'].'&provider='.($game['provider'] ?? 'jili')) }}" class="game-card-img" title="{{ $game['name'] }}">
        @else
            <a href="{{ route('user.login') }}" class="game-card-img" title="{{ $game['name'] }}">
        @endauth
                <img src="{{ $game['img'] }}" alt="{{ $game['name'] }}" loading="lazy" decoding="async" referrerpolicy="no-referrer" width="120" height="120"
                     onerror="this.closest('.game-card')?.remove()">
            </a>
        <div class="game-card-name">{{ $game['name'] }}</div>
    </div>
@endforeach
