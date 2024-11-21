<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="application-name" content="{{ config('app.name') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400..700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Cookie&display=swap" rel="stylesheet">

        <title>
            @if(View::hasSection('title'))
                @yield('title') - {{ $pengaturan_website->brand_name }}
            @else
                {{ $pengaturan_website->brand_name }}
            @endif
        </title>

        @vite('resources/css/app.css')
        @vite('resources/js/app.js')

        <link rel="stylesheet" href="{{asset('css/preline.css')}}">

        @yield('styles')
        @stack('styles')
        @livewireStyles
    </head>

    <body class="dark:bg-neutral-900 font-inter">

        {{ $slot }}
        @livewireScripts
        @yield('scripts')
        @stack('scripts')
        <script src="https://preline.co/assets/vendor/preline/dist/index.js?v=2.3.0"></script>
    </body>
</html>
