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

        {{-- Vite: Tailwind CSS + App JS --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-slate-100 to-slate-200">
            <div class="text-center">
                <a href="/" class="inline-flex items-center gap-2">
                    <x-application-logo class="w-14 h-14 fill-current text-slate-700" />
                    <span class="text-xl font-semibold text-slate-800">{{ config('app.name', 'App') }}</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white/90 backdrop-blur shadow-xl ring-1 ring-slate-200/70 sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
