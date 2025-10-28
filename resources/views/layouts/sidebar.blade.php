<!-- Sidebar Component -->
<div x-data="{ 
    sidebarOpen: true, 
    mobileOpen: false,
    activeSubmenu: null,
    toggleSubmenu(menu) {
        this.activeSubmenu = this.activeSubmenu === menu ? null : menu;
    }
}" class="relative">
    
    <!-- Mobile Overlay -->
    <div x-show="mobileOpen" 
         @click="mobileOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900 bg-opacity-75 z-40 lg:hidden">
    </div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'w-64' : 'w-20'" 
           class="fixed left-0 top-0 h-screen bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 text-white transition-all duration-300 ease-in-out z-50 shadow-2xl border-r border-slate-700">
        
        <!-- Logo Header -->
        <div class="flex items-center justify-between px-4 py-6 border-b border-slate-700">
            <div class="flex items-center space-x-3">
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg p-2 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div x-show="sidebarOpen" x-transition class="flex flex-col">
                    <span class="font-bold text-lg tracking-tight">Sport Academy</span>
                    <span class="text-xs text-slate-400">Management System</span>
                </div>
            </div>
        </div>

        <!-- User Profile Section -->
        <div class="px-4 py-4 border-b border-slate-700">
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-cyan-500 flex items-center justify-center text-white font-bold shadow-lg">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-slate-900"></div>
                </div>
                <div x-show="sidebarOpen" x-transition class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate">
                        @foreach(Auth::user()->roles as $role)
                            <span class="inline-block px-2 py-0.5 bg-indigo-900 text-indigo-200 rounded text-xs mr-1">
                                {{ ucfirst($role->name) }}
                            </span>
                        @endforeach
                    </p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1 custom-scrollbar" style="max-height: calc(100vh - 280px);">
            
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span x-show="sidebarOpen" x-transition>Dashboard</span>
            </a>

            @role('super-admin|admin')
            <!-- Admin Section -->
            <div class="pt-4">
                <p x-show="sidebarOpen" class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                    Administration
                </p>
                
                <!-- User Management -->
                <div>
                    <button @click="toggleSubmenu('users')" 
                            class="nav-item w-full {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition class="flex-1 text-left">Users</span>
                        <svg x-show="sidebarOpen" :class="activeSubmenu === 'users' ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="activeSubmenu === 'users' && sidebarOpen" x-transition class="ml-11 mt-1 space-y-1">
                        <a href="{{ route('admin.users.index') }}" class="submenu-item">All Users</a>
                        <a href="{{ route('admin.users.create') }}" class="submenu-item">Add New User</a>
                    </div>
                </div>

                <!-- Students -->
                <div>
                    <button @click="toggleSubmenu('students')" 
                            class="nav-item w-full {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition class="flex-1 text-left">Students</span>
                        <svg x-show="sidebarOpen" :class="activeSubmenu === 'students' ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="activeSubmenu === 'students' && sidebarOpen" x-transition class="ml-11 mt-1 space-y-1">
                        <a href="{{ route('admin.students.index') }}" class="submenu-item">All Students</a>
                        <a href="{{ route('admin.students.create') }}" class="submenu-item">Add Student</a>
                        <a href="{{ route('admin.students.importForm') }}" class="submenu-item">Import Photos</a>
                    </div>
                </div>

                <!-- Training Sessions -->
                <div>
                    <button @click="toggleSubmenu('sessions')" 
                            class="nav-item w-full {{ request()->routeIs('admin.sessions.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition class="flex-1 text-left">Sessions</span>
                        <svg x-show="sidebarOpen" :class="activeSubmenu === 'sessions' ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="activeSubmenu === 'sessions' && sidebarOpen" x-transition class="ml-11 mt-1 space-y-1">
                        <a href="{{ route('admin.sessions.index') }}" class="submenu-item">All Sessions</a>
                        <a href="{{ route('admin.sessions.create') }}" class="submenu-item">Schedule Session</a>
                    </div>
                </div>

                <!-- Subscription Plans -->
                <div>
                    <button @click="toggleSubmenu('plans')" 
                            class="nav-item w-full {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition class="flex-1 text-left">Plans</span>
                        <svg x-show="sidebarOpen" :class="activeSubmenu === 'plans' ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="activeSubmenu === 'plans' && sidebarOpen" x-transition class="ml-11 mt-1 space-y-1">
                        <a href="{{ route('admin.plans.index') }}" class="submenu-item">All Plans</a>
                        <a href="{{ route('admin.plans.create') }}" class="submenu-item">Create Plan</a>
                    </div>
                </div>

                <!-- Equipment & Facilities -->
                <div>
                    <button @click="toggleSubmenu('equipment')" 
                            class="nav-item w-full {{ request()->routeIs('admin.equipment.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition class="flex-1 text-left">Equipment</span>
                        <svg x-show="sidebarOpen" :class="activeSubmenu === 'equipment' ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="activeSubmenu === 'equipment' && sidebarOpen" x-transition class="ml-11 mt-1 space-y-1">
                        <a href="{{ route('admin.equipment.index') }}" class="submenu-item">All Equipment</a>
                        <a href="{{ route('admin.equipment.create') }}" class="submenu-item">Add Equipment</a>
                    </div>
                </div>
            </div>
            @endrole

            @role('super-admin|admin|accountant')
            <!-- Finance Section -->
            <div class="pt-4">
                <p x-show="sidebarOpen" class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                    Financial
                </p>

                <!-- Subscriptions -->
                <a href="{{ route('accountant.subscriptions.index') }}" 
                   class="nav-item {{ request()->routeIs('accountant.subscriptions.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition>Subscriptions</span>
                    @php
                        $activeCount = \App\Models\Subscription::where('status', 'active')->count();
                    @endphp
                    @if($activeCount > 0)
                        <span x-show="sidebarOpen" class="ml-auto bg-emerald-500 text-white text-xs rounded-full px-2 py-0.5">
                            {{ $activeCount }}
                        </span>
                    @endif
                </a>

                <!-- Invoices -->
                <a href="{{ route('accountant.invoices.index') }}" 
                   class="nav-item {{ request()->routeIs('accountant.invoices.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition>Invoices</span>
                    @php
                        $pendingCount = \App\Models\Invoice::whereIn('status', ['pending', 'overdue'])->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span x-show="sidebarOpen" class="ml-auto bg-amber-500 text-white text-xs rounded-full px-2 py-0.5">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>

                <!-- Payments -->
                <a href="{{ route('accountant.payments.index') }}" 
                   class="nav-item {{ request()->routeIs('accountant.payments.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition>Payments</span>
                </a>

                <!-- Expenses -->
                <a href="{{ route('admin.expenses.index') }}" 
                   class="nav-item {{ request()->routeIs('admin.expenses.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition>Expenses</span>
                    @php
                        $pendingExpenses = \App\Models\Expense::where('status', 'pending')->count();
                    @endphp
                    @if($pendingExpenses > 0)
                        <span x-show="sidebarOpen" class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-0.5">
                            {{ $pendingExpenses }}
                        </span>
                    @endif
                </a>
            </div>
            @endrole

            @role('super-admin|admin|coach')
            <!-- Training Section -->
            <div class="pt-4">
                <p x-show="sidebarOpen" class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                    Training
                </p>

                <!-- Attendance -->
                <a href="{{ route('coach.attendance.index') }}" 
                   class="nav-item {{ request()->routeIs('coach.attendance.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition>Attendance</span>
                </a>
            </div>
            @endrole

        </nav>

        <!-- Bottom Actions -->
        <div class="border-t border-slate-700 px-3 py-3 space-y-2">
            <!-- Settings -->
            <a href="{{ route('profile.edit') }}" 
               class="nav-item text-slate-300 hover:text-white hover:bg-slate-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span x-show="sidebarOpen" x-transition>Settings</span>
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-item w-full text-red-300 hover:text-red-100 hover:bg-red-900/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition>Logout</span>
                </button>
            </form>

            <!-- Toggle Sidebar -->
            <button @click="sidebarOpen = !sidebarOpen" 
                    class="nav-item w-full text-slate-400 hover:text-white hover:bg-slate-700 hidden lg:flex">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
                <span x-show="sidebarOpen" x-transition>Collapse</span>
            </button>
        </div>
    </aside>

    <!-- Mobile Menu Button -->
    <button @click="mobileOpen = !mobileOpen" 
            class="fixed top-4 left-4 z-50 lg:hidden bg-slate-900 text-white p-2 rounded-lg shadow-lg">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
</div>

<style>
    .nav-item {
        @apply flex items-center space-x-3 px-3 py-2.5 rounded-lg text-slate-300 hover:bg-slate-700/50 hover:text-white transition-all duration-200 cursor-pointer;
    }
    
    .nav-item.active {
        @apply bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg;
    }
    
    .submenu-item {
        @apply block px-3 py-2 text-sm text-slate-400 hover:text-white hover:bg-slate-700/30 rounded-md transition-colors duration-200;
    }
    
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.3);
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(148, 163, 184, 0.5);
    }
</style>
