<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- Custom CSS (No Tailwind) --}}
        <link rel="stylesheet" href="{{ asset('css/inline-styles.css') }}">
    </head>
    <body class="font-sans text-gray-900 antialiased flex flex-col min-h-screen">
        <div class="flex-1 flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-slate-50 via-blue-50 to-purple-50">
            <div>
                <a href="/">
                    <x-application-logo class="w-24 h-24 drop-shadow-lg" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white/80 backdrop-blur-sm shadow-xl overflow-hidden sm:rounded-2xl border border-slate-200">
                {{ $slot }}
            </div>
        </div>

        <!-- Footer -->
        <x-app-footer />
    <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
