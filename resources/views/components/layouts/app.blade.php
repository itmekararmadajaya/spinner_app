<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'New Armada' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <script src="{{asset('asset/tweenmax/TweenMax.min.js')}}"></script>
        <script src="{{asset('asset/winwheel/Winwheel.min.js')}}"></script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

        <style>
            #custom-alert.show {
                display: flex;
            }

            #custom-alert.show #custom-alert-box {
                opacity: 1;
                transform: scale(1);
            }

        </style>
        
    </head>
    <body>
        {{ $slot }}

        @stack('scripts')
    </body>
</html>
