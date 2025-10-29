<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'App') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- Vite: Tailwind CSS + Custom Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="font-sans antialiased">
<div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="hidden md:flex md:w-64 flex-col bg-slate-900 text-slate-200">
        <div class="h-16 flex items-center px-6 border-b border-slate-800">
            <a href="/" class="text-lg font-semibold tracking-wide">{{ config('app.name','Sports Academy') }}</a>
        </div>
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            @auth
                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-slate-800 {{ request()->routeIs('user.dashboard') ? 'bg-slate-800' : '' }}">
                    <span>🏠</span><span>User</span>
                </a>
                @role('admin')
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-slate-800 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800' : '' }}">
                        <span>🛡️</span><span>Admin</span>
                    </a>
                @endrole
                @role('coach')
                    <a href="{{ route('coach.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-slate-800 {{ request()->routeIs('coach.dashboard') ? 'bg-slate-800' : '' }}">
                        <span>🎯</span><span>Coach</span>
                    </a>
                @endrole
                @role('accountant')
                    <a href="{{ route('accountant.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-slate-800 {{ request()->routeIs('accountant.dashboard') ? 'bg-slate-800' : '' }}">
                        <span>💵</span><span>Accountant</span>
                    </a>
                @endrole
                @role('parent')
                    <a href="{{ route('parent.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-slate-800 {{ request()->routeIs('parent.dashboard') ? 'bg-slate-800' : '' }}">
                        <span>👪</span><span>Parent</span>
                    </a>
                @endrole
            @endauth
        </nav>
        @auth
        <div class="p-4 border-t border-slate-800 text-xs space-y-2">
            <div class="flex flex-wrap gap-2">
                @role('admin')<span class="px-2 py-0.5 rounded-full bg-fuchsia-700/40 text-fuchsia-200">admin</span>@endrole
                @role('coach')<span class="px-2 py-0.5 rounded-full bg-blue-700/40 text-blue-200">coach</span>@endrole
                @role('accountant')<span class="px-2 py-0.5 rounded-full bg-emerald-700/40 text-emerald-200">accountant</span>@endrole
                @role('parent')<span class="px-2 py-0.5 rounded-full bg-amber-700/40 text-amber-200">parent</span>@endrole
            </div>
            <div class="text-slate-400">
                @if(Auth::user()->branch)
                    Branch: <span class="text-slate-200">{{ Auth::user()->branch->name }}</span>
                @endif
                @if(Auth::user()->group)
                    · Group: <span class="text-slate-200">{{ Auth::user()->group->name }}</span>
                @endif
            </div>
        </div>
        @endauth
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Topbar -->
        <header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex items-center px-4 sm:px-6 justify-between">
            <div class="flex items-center gap-2">
                <button class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-slate-500 hover:bg-slate-100" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <div class="font-semibold text-slate-700 dark:text-slate-200">{{ $title ?? 'Dashboard' }}</div>
            </div>
            <div class="flex items-center gap-3">
                @can('manage users')<span class="badge badge-slate">manage users</span>@endcan
                @can('manage finances')<span class="badge badge-slate">manage finances</span>@endcan
                <button type="button" class="btn-secondary" title="Toggle theme" onclick="toggleTheme()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                        <path d="M12 3a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0V4a1 1 0 0 1 1-1Zm0 14a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm8-5a1 1 0 0 1-1 1h-1a1 1 0 1 1 0-2h1a1 1 0 0 1 1 1ZM5 12a1 1 0 0 1-1 1H3a1 1 0 1 1 0-2h1a1 1 0 0 1 1 1Zm11.657 6.657a1 1 0 0 1-1.414 0L14.1 17.514a1 1 0 0 1 1.414-1.415l1.142 1.143a1 1 0 0 1 0 1.415Zm0-13.314a1 1 0 0 1 0 1.414L15.515 7.9A1 1 0 1 1 14.1 6.485l1.143-1.142a1 1 0 0 1 1.414 0ZM6.485 14.1a1 1 0 0 1 0 1.414l-1.142 1.143a1 1 0 0 1-1.415-1.414L5.07 14.1A1 1 0 0 1 6.485 14.1Zm0-7.071L5.343 5.886A1 1 0 1 1 6.757 4.47l1.143 1.143A1 1 0 1 1 6.485 7.03Z"/>
                    </svg>
                </button>
                @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn-primary">Logout</button>
                </form>
                @else
                <a class="btn-primary" href="{{ route('login') }}">Login</a>
                @endauth
            </div>
        </header>

        <!-- Content -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @if (session('status'))
                <div data-flash="success" class="hidden">{{ session('status') }}</div>
            @endif
            @yield('content')
        </main>

        <!-- Footer -->
        <x-app-footer />
    </div>
    
    <!-- Mobile menu placeholder (optional extension) -->
    <div id="mobileMenu" class="md:hidden hidden fixed inset-y-0 left-0 z-40 w-64 bg-slate-900 text-slate-200">
        <div class="h-16 flex items-center px-6 border-b border-slate-800">
            <div class="text-lg font-semibold">Menu</div>
        </div>
        <nav class="px-3 py-4 space-y-1">
            @auth
                <a href="{{ route('user.dashboard') }}" class="block px-3 py-2 rounded-md hover:bg-slate-800">User</a>
                @role('admin')<a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-md hover:bg-slate-800">Admin</a>@endrole
                @role('coach')<a href="{{ route('coach.dashboard') }}" class="block px-3 py-2 rounded-md hover:bg-slate-800">Coach</a>@endrole
                @role('accountant')<a href="{{ route('accountant.dashboard') }}" class="block px-3 py-2 rounded-md hover:bg-slate-800">Accountant</a>@endrole
                @role('parent')<a href="{{ route('parent.dashboard') }}" class="block px-3 py-2 rounded-md hover:bg-slate-800">Parent</a>@endrole
            @endauth
        </nav>
    </div>
</div>

@stack('scripts')
<script src="{{ asset('js/custom-interactions.js') }}"></script>
</body>
</html>
