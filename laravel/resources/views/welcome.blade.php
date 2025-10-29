<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Modern Sport Academy Management System - Manage members, coaches, schedules, attendance, and billing in one platform.">
    <meta name="theme-color" content="#ffffff">
    <title>{{ config('app.name', 'Sport Academy MS') }} - Modern Academy Management Platform</title>

    <!-- Preconnect to font service for faster load -->
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Vite Assets with Enhanced CSS -->
    @vite(['resources/css/app.css', 'resources/css/advanced.css', 'resources/js/app.js'])
    
    <!-- Custom Design System Fallback -->
    @if(!app()->environment('production'))
        <link rel="stylesheet" href="{{ asset('css/custom-design.css') }}" media="screen">
    @endif
</head>
<body class="antialiased min-h-screen bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300" data-theme="auto">
    <!-- Skip Navigation Link for Accessibility -->
    <a href="#main-content" class="skip-navigation sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:bg-blue-600 focus:text-white focus:px-4 focus:py-2 focus:rounded">
        Skip to main content
    </a>

    <!-- Theme Toggle (Enhanced) -->
    <button id="theme-toggle" 
            class="fixed top-4 right-4 z-40 w-12 h-12 rounded-full bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center hover:shadow-xl transition-all duration-200"
            title="Toggle theme"
            data-theme-toggle>
        <span class="text-lg">🌙</span>
    </button>

    <!-- Navigation Header (Enhanced) -->
    <nav class="navbar sticky top-0 z-30 backdrop-blur-md bg-white/80 dark:bg-gray-900/80 border-b border-gray-200 dark:border-gray-700" 
         role="navigation" 
         aria-label="Main navigation"
         data-enhanced="true">
        <div class="navbar-container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Logo / Branding (Enhanced) -->
            <div class="logo flex items-center space-x-3 group">
                <div class="relative">
                    <svg viewBox="0 0 100 100" 
                         xmlns="http://www.w3.org/2000/svg" 
                         class="w-10 h-10 transition-transform duration-300 group-hover:scale-110" 
                         aria-hidden="true" 
                         focusable="false">
                        <defs>
                            <linearGradient id="trophy-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#2563eb;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#7c3aed;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        <path d="M30 25 L30 35 Q30 50 40 55 L40 70 L35 70 L35 80 L65 80 L65 70 L60 70 L60 55 Q70 50 70 35 L70 25 Z M25 25 L25 35 Q25 40 30 40 L30 30 L25 30 Z M70 30 L70 40 Q75 40 75 35 L75 25 Z" fill="url(#trophy-grad)"/>
                        <polygon points="45,15 47,22 54,23 49,27 51,34 45,30 39,34 41,27 36,23 43,22" fill="#f59e0b"/>
                    </svg>
                </div>
                <a href="/" class="logo-text text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent" title="Sport Academy MS - Home">
                    Sport Academy MS
                </a>
            </div>

            <!-- Navigation Menu (Enhanced) -->
            <ul class="nav-links flex items-center space-x-6" role="menubar">
                @if (Route::has('login'))
                    @auth
                        <li role="none">
                            <a href="{{ url('/dashboard') }}" 
                               class="btn btn-nav px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-all duration-200 transform hover:scale-105" 
                               role="menuitem">
                                Dashboard
                            </a>
                        </li>
                    @else
                        <li role="none">
                            <a href="{{ route('login') }}" 
                               class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200" 
                               role="menuitem">
                                Login
                            </a>
                        </li>
                        @if (Route::has('register'))
                            <li role="none">
                                <a href="{{ route('register') }}" 
                                   class="btn btn-nav px-4 py-2 rounded-lg bg-gradient-to-r from-blue-600 to-purple-600 text-white hover:from-blue-700 hover:to-purple-700 transition-all duration-200 transform hover:scale-105" 
                                   role="menuitem">
                                    Register
                                </a>
                            </li>
                        @endif
                    @endauth
                @endif
            </ul>
        </div>
    </nav>

    <!-- Hero Section (Enhanced) -->
    <main id="main-content">
        <section class="hero relative overflow-hidden bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-20 lg:py-32" 
                 role="region" 
                 aria-label="Hero section with main platform introduction"
                 data-enhanced="true">
            <!-- Animated Background Elements -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-40 -right-32 w-80 h-80 bg-blue-200 dark:bg-blue-900 rounded-full opacity-20 animate-float"></div>
                <div class="absolute -bottom-40 -left-32 w-96 h-96 bg-purple-200 dark:bg-purple-900 rounded-full opacity-20 animate-float-delayed"></div>
            </div>
            
            <div class="container relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="hero-content text-center max-w-4xl mx-auto">
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold mb-6 leading-tight" 
                        role="heading" 
                        aria-level="1"
                        data-count-up="true">
                        All‑in‑One<br>
                        <span class="gradient-text bg-gradient-to-r from-blue-600 via-purple-600 to-blue-600 bg-clip-text text-transparent animate-gradient-x">
                            Sport Academy Management System
                        </span>
                    </h1>
                    <p class="hero-subtitle text-xl md:text-2xl text-gray-600 dark:text-gray-300 mb-8 max-w-3xl mx-auto leading-relaxed" 
                       role="doc-subtitle"
                       data-fade-in="true">
                        Manage Students, coaches, schedules, attendance, payments, and reports in one secure, easy-to-use platform.
                    </p>
                    <div class="hero-buttons flex flex-col sm:flex-row gap-4 justify-center items-center" 
                         role="group" 
                         aria-label="Primary actions"
                         data-stagger-animation="true">
                        <a href="{{ route('register') }}" 
                           class="btn btn-primary btn-lg px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl" 
                           role="button"
                           data-ripple="true">
                            Get Started
                            <span class="ml-2">→</span>
                        </a>
                        <a href="#modules" 
                           class="btn btn-secondary btn-lg px-8 py-4 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-semibold hover:border-blue-500 hover:text-blue-600 transition-all duration-300" 
                           role="button" 
                           aria-label="Explore features section"
                           data-scroll-to="modules">
                            Explore Features
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Core Modules Section (Enhanced) -->
        <section id="modules" 
                 class="section-light py-20 bg-white dark:bg-gray-900" 
                 role="region" 
                 aria-label="Core platform modules"
                 data-enhanced="true">
            <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <header class="section-title text-center mb-16" data-fade-in="true">
                    <h2 class="text-4xl md:text-5xl font-bold mb-4 text-gray-900 dark:text-white" role="heading" aria-level="2">
                        Core Modules
                    </h2>
                    <p class="section-subtitle text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                        Everything you need to run your academy efficiently
                    </p>
                </header>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8" role="list" data-stagger-cards="true">
                    <!-- Students Management -->
                    <article class="module-card group relative overflow-hidden rounded-xl p-6 text-white shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2" 
                             role="listitem" 
                             style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);" 
                             aria-label="Student Management module"
                             data-tilt="true">
                        <div class="absolute inset-0 bg-gradient-to-br from-transparent to-black/20"></div>
                        <div class="relative z-10">
                            <div class="module-icon text-4xl mb-4 transform group-hover:scale-110 transition-transform duration-300" aria-hidden="true">👤</div>
                            <h3 class="module-title text-xl font-bold mb-2" role="heading" aria-level="3">Students Management</h3>
                            <p class="module-desc text-blue-100 mb-4 text-sm">Profiles, roles, memberships, and communication in one place</p>
                            <ul class="module-features space-y-2" aria-label="Features of Student Management">
                                <li class="flex items-center text-sm"><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" class="mr-2 text-blue-200" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Members & Coaches</li>
                                <li class="flex items-center text-sm"><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" class="mr-2 text-blue-200" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Branches & Groups</li>
                                <li class="flex items-center text-sm"><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" class="mr-2 text-blue-200" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Role-based Access</li>
                            </ul>
                        </div>
                    </article>

                    <!-- Scheduling -->
                    <article class="module-card group relative overflow-hidden rounded-xl p-6 text-white shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2" 
                             role="listitem" 
                             style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);" 
                             aria-label="Scheduling module"
                             data-tilt="true">
                        <div class="absolute inset-0 bg-gradient-to-br from-transparent to-black/20"></div>
                        <div class="relative z-10">
                            <div class="module-icon text-4xl mb-4 transform group-hover:scale-110 transition-transform duration-300" aria-hidden="true">🗓️</div>
                            <h3 class="module-title text-xl font-bold mb-2" role="heading" aria-level="3">Scheduling</h3>
                            <p class="module-desc text-orange-100 mb-4 text-sm">Plan training sessions, assign coaches, and manage venues</p>
                            <ul class="module-features space-y-2" aria-label="Features of Scheduling">
                                <li class="flex items-center text-sm"><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" class="mr-2 text-orange-200" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Calendar & Filters</li>
                                <li class="flex items-center text-sm"><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" class="mr-2 text-orange-200" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Coach Assignment</li>
                                <li class="flex items-center text-sm"><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" class="mr-2 text-orange-200" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Venue Management</li>
                            </ul>
                        </div>
                    </article>

                    <!-- Attendance -->
                    <article class="module-card group relative overflow-hidden rounded-xl p-6 text-white shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2" 
                             role="listitem" 
                             style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);" 
                             aria-label="Attendance module"
                             data-tilt="true">
                        <div class="absolute inset-0 bg-gradient-to-br from-transparent to-black/20"></div>
                        <div class="relative z-10">
                            <div class="module-icon text-4xl mb-4 transform group-hover:scale-110 transition-transform duration-300" aria-hidden="true">✅</div>
                            <h3 class="module-title text-xl font-bold mb-2" role="heading" aria-level="3">Attendance</h3>
                            <p class="module-desc text-emerald-100 mb-4 text-sm">Track attendance for students and coaches with quick actions</p>
                            <ul class="module-features space-y-2" aria-label="Features of Attendance">
                                <li class="flex items-center text-sm"><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" class="mr-2 text-emerald-200" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Student Logs</li>
                                <li class="flex items-center text-sm"><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" class="mr-2 text-emerald-200" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Exportable Reports</li>
                                <li class="flex items-center text-sm"><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" class="mr-2 text-emerald-200" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Insights</li>
                            </ul>
                        </div>
                    </article>

                    <!-- Payments -->
                    <article class="module-card group relative overflow-hidden rounded-xl p-6 text-white shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2" 
                             role="listitem" 
                             style="background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);" 
                             aria-label="Payments and Billing module"
                             data-tilt="true">
                        <div class="absolute inset-0 bg-gradient-to-br from-transparent to-black/20"></div>
                        <div class="relative z-10">
                            <div class="module-icon text-4xl mb-4 transform group-hover:scale-110 transition-transform duration-300" aria-hidden="true">💳</div>
                            <h3 class="module-title text-xl font-bold mb-2" role="heading" aria-level="3">Payments & Billing</h3>
                            <p class="module-desc text-purple-100 mb-4 text-sm">Subscriptions, invoices, and payment tracking with summaries</p>
                            <ul class="module-features space-y-2" aria-label="Features of Payments and Billing">
                                <li class="flex items-center text-sm"><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" class="mr-2 text-purple-200" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Subscriptions</li>
                                <li class="flex items-center text-sm"><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" class="mr-2 text-purple-200" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Invoices</li>
                                <li class="flex items-center text-sm"><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" class="mr-2 text-purple-200" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Revenue</li>
                            </ul>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <!-- Features Section (Enhanced) -->
        <section id="features" 
                 class="section-alt py-20 bg-gray-50 dark:bg-gray-800" 
                 role="region" 
                 aria-label="Key features and benefits"
                 data-enhanced="true">
            <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <header class="section-title text-center mb-16" data-fade-in="true">
                    <h2 class="text-4xl md:text-5xl font-bold mb-4 text-gray-900 dark:text-white" role="heading" aria-level="2">
                        Why Academies Choose Us
                    </h2>
                    <p class="section-subtitle text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                        Powerful features designed for real academy operations
                    </p>
                </header>

                <div class="features-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" role="list" data-stagger-cards="true">
                    <article class="feature-item card bg-white dark:bg-gray-700 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" 
                             role="listitem" 
                             aria-label="Role-based access control feature">
                        <div class="feature-icon text-4xl mb-4" aria-hidden="true">🔐</div>
                        <h3 class="feature-title text-xl font-bold mb-2 text-gray-900 dark:text-white" role="heading" aria-level="3">Role-based Access</h3>
                        <p class="feature-desc text-gray-600 dark:text-gray-300">Admin, coach, and member roles with fine-grained permissions</p>
                    </article>

                    <article class="feature-item card bg-white dark:bg-gray-700 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" 
                             role="listitem" 
                             aria-label="Multi-branch support feature">
                        <div class="feature-icon text-4xl mb-4" aria-hidden="true">🏢</div>
                        <h3 class="feature-title text-xl font-bold mb-2 text-gray-900 dark:text-white" role="heading" aria-level="3">Multi-Branch Support</h3>
                        <p class="feature-desc text-gray-600 dark:text-gray-300">Organize your organization by branches and groups/teams</p>
                    </article>

                    <article class="feature-item card bg-white dark:bg-gray-700 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" 
                             role="listitem" 
                             aria-label="Analytics and reports feature">
                        <div class="feature-icon text-4xl mb-4" aria-hidden="true">📊</div>
                        <h3 class="feature-title text-xl font-bold mb-2 text-gray-900 dark:text-white" role="heading" aria-level="3">Analytics & Reports</h3>
                        <p class="feature-desc text-gray-600 dark:text-gray-300">Attendance, revenue, and participation insights at a glance</p>
                    </article>

                    <article class="feature-item card bg-white dark:bg-gray-700 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" 
                             role="listitem" 
                             aria-label="Notifications feature">
                        <div class="feature-icon text-4xl mb-4" aria-hidden="true">🔔</div>
                        <h3 class="feature-title text-xl font-bold mb-2 text-gray-900 dark:text-white" role="heading" aria-level="3">Notifications</h3>
                        <p class="feature-desc text-gray-600 dark:text-gray-300">Keep members informed via email/SMS (provider dependent)</p>
                    </article>

                    <article class="feature-item card bg-white dark:bg-gray-700 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" 
                             role="listitem" 
                             aria-label="Integrations feature">
                        <div class="feature-icon text-4xl mb-4" aria-hidden="true">🔗</div>
                        <h3 class="feature-title text-xl font-bold mb-2 text-gray-900 dark:text-white" role="heading" aria-level="3">Integrations</h3>
                        <p class="feature-desc text-gray-600 dark:text-gray-300">Export data or integrate with gateways and accounting tools</p>
                    </article>

                    <article class="feature-item card bg-white dark:bg-gray-700 p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" 
                             role="listitem" 
                             aria-label="Billing and invoicing feature">
                        <div class="feature-icon text-4xl mb-4" aria-hidden="true">🧾</div>
                        <h3 class="feature-title text-xl font-bold mb-2 text-gray-900 dark:text-white" role="heading" aria-level="3">Billing & Invoicing</h3>
                        <p class="feature-desc text-gray-600 dark:text-gray-300">Subscriptions, invoices, receipts, and revenue tracking</p>
                    </article>
                </div>
            </div>
        </section>

        <!-- Stats Section (Enhanced) -->
        <section class="section-gradient py-20 bg-gradient-to-r from-blue-600 via-purple-600 to-blue-600 relative overflow-hidden" 
                 role="region" 
                 aria-label="Platform statistics"
                 data-enhanced="true">
            <!-- Animated background -->
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 via-purple-600 to-blue-600 animate-gradient-x"></div>
            <div class="absolute inset-0 bg-black/10"></div>
            
            <div class="container relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="stats-grid grid grid-cols-2 md:grid-cols-4 gap-8" role="list" data-count-up-section="true">
                    <article class="stat-item text-center group" role="listitem" aria-label="Active members count">
                        <div class="stat-number text-4xl md:text-5xl font-bold text-white mb-2 transform group-hover:scale-110 transition-transform duration-300" 
                             role="doc-subtitle" 
                             aria-live="polite"
                             data-count-to="500">0+</div>
                        <div class="stat-label text-blue-100 font-medium">Active Members</div>
                    </article>
                    <article class="stat-item text-center group" role="listitem" aria-label="Coaches count">
                        <div class="stat-number text-4xl md:text-5xl font-bold text-white mb-2 transform group-hover:scale-110 transition-transform duration-300" 
                             role="doc-subtitle" 
                             aria-live="polite"
                             data-count-to="50">0+</div>
                        <div class="stat-label text-blue-100 font-medium">Coaches</div>
                    </article>
                    <article class="stat-item text-center group" role="listitem" aria-label="Branches count">
                        <div class="stat-number text-4xl md:text-5xl font-bold text-white mb-2 transform group-hover:scale-110 transition-transform duration-300" 
                             role="doc-subtitle" 
                             aria-live="polite"
                             data-count-to="10">0+</div>
                        <div class="stat-label text-blue-100 font-medium">Branches</div>
                    </article>
                    <article class="stat-item text-center group" role="listitem" aria-label="Years in service">
                        <div class="stat-number text-4xl md:text-5xl font-bold text-white mb-2 transform group-hover:scale-110 transition-transform duration-300" 
                             role="doc-subtitle" 
                             aria-live="polite"
                             data-count-to="10">0+</div>
                        <div class="stat-label text-blue-100 font-medium">Years Serving</div>
                    </article>
                </div>
            </div>
        </section>

        <!-- CTA Section (Enhanced) -->
        <section class="section-light py-20 bg-white dark:bg-gray-900" 
                 role="region" 
                 aria-label="Call to action section"
                 data-enhanced="true">
            <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <article class="cta-section text-center max-w-4xl mx-auto">
                    <h2 class="cta-title text-4xl md:text-5xl font-bold mb-6 text-gray-900 dark:text-white" 
                        role="heading" 
                        aria-level="2">
                        Ready to streamline your academy?
                    </h2>
                    <p class="cta-text text-xl text-gray-600 dark:text-gray-300 mb-8 max-w-2xl mx-auto" 
                       role="doc-subtitle">
                        Join organizations already saving time with modern scheduling, attendance, and billing tools
                    </p>
                    <div class="cta-buttons flex flex-col sm:flex-row gap-4 justify-center items-center" 
                         role="group" 
                         aria-label="Get started options">
                        <a href="{{ route('register') }}" 
                           class="btn btn-primary btn-lg px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl" 
                           role="button"
                           data-ripple="true">
                            Get Started
                        </a>
                        <a href="{{ route('login') }}" 
                           class="btn btn-secondary btn-lg px-8 py-4 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-semibold hover:border-blue-500 hover:text-blue-600 transition-all duration-300" 
                           role="button">
                            Login
                        </a>
                    </div>
                </article>
            </div>
        </section>

        <!-- Footer (Enhanced) -->
        <footer class="bg-gray-900 text-white py-16" 
                role="contentinfo" 
                aria-label="Site footer"
                data-enhanced="true">
            <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="footer-content grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <section class="footer-section" aria-labelledby="footer-brand">
                        <div class="logo flex items-center space-x-3 mb-4">
                            <svg viewBox="0 0 100 100" 
                                 xmlns="http://www.w3.org/2000/svg" 
                                 class="w-10 h-10" 
                                 aria-hidden="true" 
                                 focusable="false">
                                <defs>
                                    <linearGradient id="trophy-grad-footer" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#2563eb;stop-opacity:1" />
                                        <stop offset="100%" style="stop-color:#7c3aed;stop-opacity:1" />
                                    </linearGradient>
                                </defs>
                                <path d="M30 25 L30 35 Q30 50 40 55 L40 70 L35 70 L35 80 L65 80 L65 70 L60 70 L60 55 Q70 50 70 35 L70 25 Z M25 25 L25 35 Q25 40 30 40 L30 30 L25 30 Z M70 30 L70 40 Q75 40 75 35 L75 25 Z" fill="url(#trophy-grad-footer)"/>
                                <polygon points="45,15 47,22 54,23 49,27 51,34 45,30 39,34 41,27 36,23 43,22" fill="#f59e0b"/>
                            </svg>
                            <span class="logo-text text-xl font-bold" id="footer-brand">Sport Academy MS</span>
                        </div>
                        <p class="text-gray-400">All-in-one sport academy management platform</p>
                    </section>

                    <nav class="footer-section" aria-label="Quick navigation links">
                        <h3 class="text-lg font-semibold mb-4" role="heading" aria-level="3">Quick Links</h3>
                        <ul class="footer-links space-y-2" role="list">
                            <li role="listitem"><a href="#features" class="text-gray-400 hover:text-white transition-colors duration-200">Features</a></li>
                            <li role="listitem"><a href="{{ route('register') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Register</a></li>
                            <li role="listitem"><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Login</a></li>
                        </ul>
                    </nav>

                    <section class="footer-section" aria-labelledby="footer-contact">
                        <h3 class="text-lg font-semibold mb-4" role="heading" aria-level="3" id="footer-contact">Contact</h3>
                        <address class="not-italic">
                            <ul class="footer-links space-y-2" role="list">
                                <li role="listitem">
                                    <a href="mailto:info@sportacademyms.com" class="text-gray-400 hover:text-white transition-colors duration-200 flex items-center">
                                        <span class="mr-2">📧</span> info@sportacademyms.app.com
                                    </a>
                                </li>
                                <li role="listitem">
                                    <a href="tel:+250786163963" class="text-gray-400 hover:text-white transition-colors duration-200 flex items-center">
                                        <span class="mr-2">📞</span> +250 786 163 963
                                    </a>
                                </li>
                                <li role="listitem" class="text-gray-400 flex items-center">
                                    <span class="mr-2">📍</span> Kigali, Rwanda
                                </li>
                            </ul>
                        </address>
                    </section>
                </div>

                <div class="footer-divider border-t border-gray-800 pt-8 text-center">
                    <p class="text-gray-400">&copy; {{ date('Y') }} Sport Academy MS. All rights reserved.</p>
                </div>
            </div>
        </footer>

    <!-- Enhanced Custom JavaScript for Welcome Page -->
    <script>
        // Initialize enhanced welcome page functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Check if SportAcademy core is available
            if (window.SportAcademy) {
                console.log('🎉 Sport Academy Enhanced System Loaded');
                
                // Initialize modules specific to welcome page
                initWelcomeEnhancements();
            } else {
                // Fallback functionality
                console.log('📝 Using fallback welcome page functionality');
                initBasicEnhancements();
            }
        });

        function initWelcomeEnhancements() {
            const core = window.SportAcademy;
            
            // Enhanced count-up animations for stats
            const statsSection = document.querySelector('[data-count-up-section]');
            if (statsSection) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            animateCounters();
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.5 });
                
                observer.observe(statsSection);
            }
            
            // Smooth scroll for anchor links
            document.querySelectorAll('[data-scroll-to]').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const targetId = link.getAttribute('data-scroll-to');
                    const target = document.getElementById(targetId);
                    if (target) {
                        target.scrollIntoView({ 
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
            
            // Enhanced hover effects for module cards
            document.querySelectorAll('[data-tilt]').forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.transform = 'translateY(-8px) rotateX(5deg)';
                });
                
                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'translateY(0) rotateX(0)';
                });
            });
            
            // Staggered animations for elements
            const staggerElements = document.querySelectorAll('[data-stagger-animation]');
            staggerElements.forEach(container => {
                const children = container.children;
                Array.from(children).forEach((child, index) => {
                    child.style.opacity = '0';
                    child.style.transform = 'translateY(20px)';
                    
                    setTimeout(() => {
                        child.style.transition = 'all 0.6s ease';
                        child.style.opacity = '1';
                        child.style.transform = 'translateY(0)';
                    }, index * 200);
                });
            });
            
            // Enhanced ripple effects
            document.querySelectorAll('[data-ripple]').forEach(button => {
                button.addEventListener('click', createRipple);
            });
        }
        
        function initBasicEnhancements() {
            // Basic counter animation
            const counters = document.querySelectorAll('[data-count-to]');
            const observerOptions = {
                threshold: 0.5,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const counterObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounter(entry.target);
                        counterObserver.unobserve(entry.target);
                    }
                });
            }, observerOptions);
            
            counters.forEach(counter => {
                counterObserver.observe(counter);
            });
            
            // Basic smooth scrolling
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            });
            
            // Basic theme toggle
            const themeToggle = document.getElementById('theme-toggle');
            if (themeToggle) {
                themeToggle.addEventListener('click', toggleTheme);
            }
        }
        
        function animateCounters() {
            document.querySelectorAll('[data-count-to]').forEach(counter => {
                animateCounter(counter);
            });
        }
        
        function animateCounter(element) {
            const target = parseInt(element.getAttribute('data-count-to'));
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;
            
            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current) + '+';
            }, 16);
        }
        
        function createRipple(event) {
            const button = event.currentTarget;
            const circle = document.createElement('span');
            const diameter = Math.max(button.clientWidth, button.clientHeight);
            const radius = diameter / 2;
            
            const rect = button.getBoundingClientRect();
            circle.style.width = circle.style.height = `${diameter}px`;
            circle.style.left = `${event.clientX - rect.left - radius}px`;
            circle.style.top = `${event.clientY - rect.top - radius}px`;
            circle.classList.add('ripple');
            
            const rippleStyle = `
                position: absolute;
                border-radius: 50%;
                transform: scale(0);
                animation: ripple-animation 600ms linear;
                background-color: rgba(255, 255, 255, 0.6);
            `;
            circle.style.cssText = rippleStyle;
            
            const ripple = button.querySelector('.ripple');
            if (ripple) {
                ripple.remove();
            }
            
            button.appendChild(circle);
            
            setTimeout(() => {
                circle.remove();
            }, 600);
        }
        
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.classList.contains('dark') ? 'dark' : 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.classList.remove('dark', 'light');
            html.classList.add(newTheme);
            
            // Update theme toggle icon
            const themeToggle = document.getElementById('theme-toggle');
            if (themeToggle) {
                themeToggle.querySelector('span').textContent = newTheme === 'dark' ? '☀️' : '🌙';
            }
            
            // Store preference
            localStorage.setItem('sport-academy-theme', newTheme);
            
            // Notify system if available
            if (window.SportAcademy) {
                window.SportAcademy.emit('themeChanged', { theme: newTheme });
            }
        }
        
        // Initialize theme on load
        (function initTheme() {
            const savedTheme = localStorage.getItem('sport-academy-theme');
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = savedTheme || (prefersDark ? 'dark' : 'light');
            
            document.documentElement.classList.add(theme);
            
            const themeToggle = document.getElementById('theme-toggle');
            if (themeToggle) {
                themeToggle.querySelector('span').textContent = theme === 'dark' ? '☀️' : '🌙';
            }
        })();
    </script>

    <!-- Enhanced CSS Animations -->
    <style>
        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes float-delayed {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        
        @keyframes gradient-x {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        .animate-float-delayed {
            animation: float-delayed 8s ease-in-out infinite;
        }
        
        .animate-gradient-x {
            background-size: 200% 200%;
            animation: gradient-x 3s ease infinite;
        }
        
        .skip-navigation {
            position: absolute;
            left: -10000px;
            top: auto;
            width: 1px;
            height: 1px;
            overflow: hidden;
        }
        
        .skip-navigation:focus {
            position: static;
            width: auto;
            height: auto;
        }
        
        /* Enhanced transitions for theme switching */
        * {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.5);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 163, 175, 0.8);
        }
        
        /* Dark mode scrollbar */
        .dark ::-webkit-scrollbar-thumb {
            background: rgba(75, 85, 99, 0.5);
        }
        
        .dark ::-webkit-scrollbar-thumb:hover {
            background: rgba(75, 85, 99, 0.8);
        }
    </style>
    </main>

    <!-- Custom Interactions JS (Legacy Fallback) -->
    @if(!app()->environment('production'))
        <script src="{{ asset('js/custom-interactions.js') }}"></script>
    @endif
</body>
</html>
