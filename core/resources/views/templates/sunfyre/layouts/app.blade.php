<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> {{ gs()->siteName(__($pageTitle)) }}</title>
    @include('partials.seo')

    @php
        $baseColor = ltrim((string) gs('base_color'), '#') ?: '123B66';
        $secondColor = ltrim((string) gs('secondary_color'), '#') ?: '2563EB';
        $colorCache = substr(md5($baseColor . $secondColor), 0, 8);
        // Inline HSL so first paint matches admin color (no green FOUC from main.css defaults)
        $hexToHsl = static function (string $hex): array {
            $hex = str_pad(preg_replace('/[^a-f0-9]/i', '', $hex), 6, '0');
            $r = hexdec(substr($hex, 0, 2)) / 255;
            $g = hexdec(substr($hex, 2, 2)) / 255;
            $b = hexdec(substr($hex, 4, 2)) / 255;
            $min = min($r, $g, $b);
            $max = max($r, $g, $b);
            $d = $max - $min;
            $l = ($max + $min) / 2;
            if ($d < 0.00001) {
                $h = 0;
                $s = 0;
            } else {
                $s = $d / (1 - abs(2 * $l - 1));
                if ($max === $r) {
                    $h = fmod(($g - $b) / $d, 6);
                } elseif ($max === $g) {
                    $h = ($b - $r) / $d + 2;
                } else {
                    $h = ($r - $g) / $d + 4;
                }
                $h = round($h * 60);
                if ($h < 0) {
                    $h += 360;
                }
                $s = round($s * 100);
            }
            return ['h' => (int) $h, 's' => (int) $s, 'l' => (int) round($l * 100)];
        };
        $baseHsl = $hexToHsl($baseColor);
        $secondHsl = $hexToHsl($secondColor);
    @endphp

    {{-- Critical colors BEFORE any stylesheet — stops 1-frame green/gold flash --}}
    <style id="critical-theme-color">
        :root {
            --base-h: {{ $baseHsl['h'] }};
            --base-s: {{ $baseHsl['s'] }}%;
            --base-l: {{ $baseHsl['l'] }}%;
            --base-two-h: {{ $secondHsl['h'] }};
            --base-two-s: {{ $secondHsl['s'] }}%;
            --base-two-l: {{ $secondHsl['l'] }}%;
            --bg-deep: #e8f0fa !important;
            --bg-main: #f5f7fa !important;
            --bg-card: #ffffff !important;
            --bg-color: #e8f0fa !important;
            --teal: #2563eb !important;
            --teal-light: #2563eb !important;
            --green-btn: #2563eb !important;
            --header-color: #123b66 !important;
            --text-main: #172033 !important;
            --text-muted: #6b7280 !important;
        }
        html, body {
            background: #e8f0fa !important;
            background-image: none !important;
            color: #172033 !important;
        }
    </style>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;800&display=swap" rel="stylesheet">

    <link href="{{ asset('assets/global/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}">
    <link href="{{ asset('assets/global/css/lightcase.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/odometer.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/iconmoon.css') }}">
    <link
        href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{ $baseColor }}&secondColor={{ $secondColor }}&v={{ $colorCache }}"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}?v={{ $colorCache }}">
    <link href="{{ asset($activeTemplateTrue . 'css/custom.css') }}?v={{ $colorCache }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/theme.css') }}?v=57-{{ $colorCache }}">

    @stack('style-lib')
    <link rel="manifest" href="{{ route('pwa.configuration') }}">
    <meta name="theme-color" content="#123B66">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ gs('site_name') }}">
    @stack('style')
</head>

@php echo loadExtension('google-analytics') @endphp

<body>
    @stack('fbComment')

    <div class="body-overlay"></div>

    <div class="sidebar-overlay"></div>

    <a class="scroll-top"><i class="fas fa-angle-double-up"></i></a>

    @yield('app')

    <div class="win-loss-popup">
        <div class="win-loss-popup__bg">
            <div class="win-loss-popup__inner">
                <div class="win-loss-popup__body">
                    <img class="img-glow lose d-none"
                        src="{{ asset($activeTemplateTrue . 'images/play/lose-message.png') }}"
                        alt="lose message image">
                    <img class="img-glow win d-none"
                        src="{{ asset($activeTemplateTrue . 'images/play/win-message.png') }}" alt="win message image">
                </div>
                <div class="win-loss-popup__footer">
                    <h2 class="result-text">@lang('The result is') <span class="data-result"></span></h2>
                    <h5></h5>
                </div>
            </div>
        </div>
    </div>

    @php
        $cookie = App\Models\Frontend::where('data_keys', 'cookie.data')->first();
    @endphp

    @if ($cookie->data_values->status == Status::ENABLE && !\Cookie::get('gdpr_cookie'))
        <div class="cookies-card hide text-center">
            <div class="cookies-card__icon bg--base">
                <i class="las la-cookie-bite text--dark"></i>
            </div>
            <p class="cookies-card__content mt-4">{{ $cookie->data_values->short_desc }} <a class="text--base"
                    href="{{ route('cookie.policy') }}" target="_blank">@lang('learn more')</a></p>
            <div class="cookies-card__btn mt-4">
                <a class="btn btn--base w-100 policy" href="javascript:void(0)">@lang('Allow')</a>
            </div>
        </div>
    @endif

    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>

    @stack('script-lib')

    @php echo loadExtension('tawk-chat') @endphp

    @include('partials.notify')

    @if (gs('pn'))
        @include('partials.push_script')
    @endif

    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/lightcase.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/slick.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/odometer.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/viewport.jquery.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>

    <script>
        window.RV_LOGGED_IN = @json(auth()->check());
        window.RV_LOGIN_URL = @json(route('user.login'));
        window.RV_LAUNCH_BASE = @json(url('user/jili/launch'));
    </script>
    <script src="{{ asset($activeTemplateTrue . 'js/favorites.js') }}?v=1"></script>

    @stack('script')

    @include($activeTemplate . 'partials.live_balance')

    <script>
        (function($) {
            "use strict";

            // Removed Loader Script

            $(".langSel").on("click", function() {
                window.location.href = "{{ url('/change') }}/" + $(this).data('lang_code');
            });

            $('.policy').on('click', function() {
                $.get('{{ route('cookie.accept') }}', function(response) {
                    $('.cookies-card').addClass('d-none');
                });
            });

            setTimeout(function() {
                $('.cookies-card').removeClass('hide')
            }, 2000);

            var inputElements = $('[type=text],select,textarea');
            $.each(inputElements, function(index, element) {
                element = $(element);
                element.closest('.form-group').find('label').attr('for', element.attr('name'));
                element.attr('id', element.attr('name'))
            });

            $.each($('input:not([type=checkbox]):not([type=hidden]), select, textarea'), function(i, element) {
                var elementType = $(element);
                if (elementType.attr('type') != 'checkbox') {
                    if (element.hasAttribute('required')) {
                        $(element).closest('.form-group').find('label').addClass('required');
                    }
                }
            });

            let disableSubmission = false;
            $('.disableSubmission').on('submit', function(e) {
                if (disableSubmission) {
                    e.preventDefault()
                } else {
                    disableSubmission = true;
                }
            });

            $.each($(".select2"), function() {
                $(this)
                    .wrap(`<div class="position-relative"></div>`)
                    .select2({
                        dropdownParent: $(this).parent(),
                    });
            });


            Array.from(document.querySelectorAll('table')).forEach(table => {
                let heading = table.querySelectorAll('thead tr th');
                if (heading.length > 0) {
                    Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
                        Array.from(row.querySelectorAll('td')).forEach((colum, i) => {
                            colum.setAttribute('data-label', heading[i].innerText)
                        });
                    });
                }
            });

            let elements = document.querySelectorAll('[s-break]');
            Array.from(elements).forEach(element => {
                let html = element.innerHTML;
                if (typeof html != 'string') {
                    return false;
                }
                let breakLength = parseInt(element.getAttribute('s-break'));
                html = html.split(" ");
                var colorText = [];
                if (breakLength < 0) {
                    colorText = html.slice(breakLength);
                } else {
                    colorText = html.slice(0, breakLength);
                }
                let solidText = [];
                html.filter(ele => {
                    if (!colorText.includes(ele)) {
                        solidText.push(ele);
                    }
                });
                var color = element.getAttribute('s-color') || "title-color";
                colorText = `<span class="${color}">${colorText.toString().replaceAll(',', ' ')}</span>`;
                solidText = solidText.toString().replaceAll(',', ' ');
                breakLength < 0 ? element.innerHTML = `${solidText} ${colorText}` : element.innerHTML =
                    `${colorText} ${solidText}`
            });
        })(jQuery);

        async function registerSW() {
            if ('serviceWorker' in navigator) {
                try {
                    await navigator.serviceWorker.register(
                        "{{ asset('assets/global/js/pwa/serviceworker.js') }}",
                        { scope: '/' }
                    );
                } catch (e) {
                    console.warn('SW registration failed');
                }
            }
        }
        window.addEventListener('beforeinstallprompt', function (e) {
            e.preventDefault();
            window.__b369PwaPrompt = e;
        });
        window.addEventListener('load', () => {
            registerSW();
        });
    </script>
</body>

</html>
