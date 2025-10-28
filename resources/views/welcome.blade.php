<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Modern Sport Academy Management System - Manage members, coaches, schedules, attendance, and billing in one platform.">
    <title>{{ config('app.name', 'Sport Academy MS') }} - Modern Academy Management Platform</title>

    <!-- Preconnect to font service for faster load -->
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Custom Design System (Platform Independent) -->
    <link rel="stylesheet" href="{{ asset('css/custom-design.css') }}" media="screen">
</head>
<body>
    <!-- Navigation Header -->
    <nav class="navbar" role="navigation" aria-label="Main navigation">
        <div class="navbar-container">
            <!-- Logo / Branding -->
            <div class="logo">
                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                    <defs>
                        <linearGradient id="trophy-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#2563eb;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#7c3aed;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <path d="M30 25 L30 35 Q30 50 40 55 L40 70 L35 70 L35 80 L65 80 L65 70 L60 70 L60 55 Q70 50 70 35 L70 25 Z M25 25 L25 35 Q25 40 30 40 L30 30 L25 30 Z M70 30 L70 40 Q75 40 75 35 L75 25 Z" fill="url(#trophy-grad)"/>
                    <polygon points="45,15 47,22 54,23 49,27 51,34 45,30 39,34 41,27 36,23 43,22" fill="#f59e0b"/>
                </svg>
                <a href="/" class="logo-text" title="Sport Academy MS - Home">Sport Academy MS</a>
            </div>

            <!-- Navigation Menu -->
            <ul class="nav-links" role="menubar">
                @if (Route::has('login'))
                    @auth
                        <li role="none"><a href="{{ url('/dashboard') }}" class="btn btn-nav" role="menuitem">Dashboard</a></li>
                    @else
                        <li role="none"><a href="{{ route('login') }}" role="menuitem">Login</a></li>
                        @if (Route::has('register'))
                            <li role="none"><a href="{{ route('register') }}" class="btn btn-nav" role="menuitem">Register</a></li>
                        @endif
                    @endauth
                @endif
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" role="region" aria-label="Hero section with main platform introduction">
        <div class="container">
            <div class="hero-content">
                <h1 role="heading" aria-level="1">
                    All‑in‑One<br><span class="gradient-text">Sport Academy Management System</span>
                </h1>
                <p class="hero-subtitle" role="doc-subtitle">
                    Manage Students, coaches, schedules, attendance, payments, and reports in one secure, easy-to-use platform.
                </p>
                <div class="hero-buttons" role="group" aria-label="Primary actions">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg" role="button">Get Started</a>
                    <a href="#modules" class="btn btn-secondary btn-lg" role="button" aria-label="Explore features section">Explore Features</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Modules Section -->
    <section id="modules" class="section-light" role="region" aria-label="Core platform modules">
        <div class="container">
            <header class="section-title">
                <h2 role="heading" aria-level="2">Core Modules</h2>
                <p class="section-subtitle">Everything you need to run your academy efficiently</p>
            </header>

            <div class="grid grid-cols-4" role="list">
                <!-- Members -->
                <article class="module-card" role="listitem" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);" aria-label="Member Management module">
                    <div class="module-icon" aria-hidden="true">👤</div>
                    <h3 class="module-title" role="heading" aria-level="3">Students Management</h3>
                    <p class="module-desc">Profiles, roles, memberships, and communication in one place</p>
                    <ul class="module-features" aria-label="Features of Member Management">
                        <li><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Members & Coaches</li>
                        <li><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Branches & Groups</li>
                        <li><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Role-based Access</li>
                    </ul>
                </article>

                <!-- Scheduling -->
                <article class="module-card" role="listitem" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);" aria-label="Scheduling module">
                    <div class="module-icon" aria-hidden="true">🗓️</div>
                    <h3 class="module-title" role="heading" aria-level="3">Scheduling</h3>
                    <p class="module-desc">Plan training sessions, assign coaches, and manage venues</p>
                    <ul class="module-features" aria-label="Features of Scheduling">
                        <li><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Calendar & Filters</li>
                        <li><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Coach Assignment</li>
                        <li><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Venue Management</li>
                    </ul>
                </article>

                <!-- Attendance -->
                <article class="module-card" role="listitem" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);" aria-label="Attendance module">
                    <div class="module-icon" aria-hidden="true">✅</div>
                    <h3 class="module-title" role="heading" aria-level="3">Attendance</h3>
                    <p class="module-desc">Track attendance for students and coaches with quick actions</p>
                    <ul class="module-features" aria-label="Features of Attendance">
                        <li><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Student Logs</li>
                        <li><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Exportable Reports</li>
                        <li><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Insights</li>
                    </ul>
                </article>

                <!-- Payments -->
                <article class="module-card" role="listitem" style="background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);" aria-label="Payments and Billing module">
                    <div class="module-icon" aria-hidden="true">💳</div>
                    <h3 class="module-title" role="heading" aria-level="3">Payments & Billing</h3>
                    <p class="module-desc">Subscriptions, invoices, and payment tracking with summaries</p>
                    <ul class="module-features" aria-label="Features of Payments and Billing">
                        <li><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Subscriptions</li>
                        <li><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Invoices</li>
                        <li><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Revenue</li>
                    </ul>
                </article>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section-alt" role="region" aria-label="Key features and benefits">
        <div class="container">
            <header class="section-title">
                <h2 role="heading" aria-level="2">Why Academies Choose Us</h2>
                <p class="section-subtitle">Powerful features designed for real academy operations</p>
            </header>

            <div class="features-grid" role="list">
                <article class="feature-item card" role="listitem" aria-label="Role-based access control feature">
                    <div class="feature-icon" aria-hidden="true">🔐</div>
                    <h3 class="feature-title" role="heading" aria-level="3">Role-based Access</h3>
                    <p class="feature-desc">Admin, coach, and member roles with fine-grained permissions</p>
                </article>

                <article class="feature-item card" role="listitem" aria-label="Multi-branch support feature">
                    <div class="feature-icon" aria-hidden="true">🏢</div>
                    <h3 class="feature-title" role="heading" aria-level="3">Multi-Branch Support</h3>
                    <p class="feature-desc">Organize your organization by branches and groups/teams</p>
                </article>

                <article class="feature-item card" role="listitem" aria-label="Analytics and reports feature">
                    <div class="feature-icon" aria-hidden="true">📊</div>
                    <h3 class="feature-title" role="heading" aria-level="3">Analytics & Reports</h3>
                    <p class="feature-desc">Attendance, revenue, and participation insights at a glance</p>
                </article>

                <article class="feature-item card" role="listitem" aria-label="Notifications feature">
                    <div class="feature-icon" aria-hidden="true">🔔</div>
                    <h3 class="feature-title" role="heading" aria-level="3">Notifications</h3>
                    <p class="feature-desc">Keep members informed via email/SMS (provider dependent)</p>
                </article>

                <article class="feature-item card" role="listitem" aria-label="Integrations feature">
                    <div class="feature-icon" aria-hidden="true">🔗</div>
                    <h3 class="feature-title" role="heading" aria-level="3">Integrations</h3>
                    <p class="feature-desc">Export data or integrate with gateways and accounting tools</p>
                </article>

                <article class="feature-item card" role="listitem" aria-label="Billing and invoicing feature">
                    <div class="feature-icon" aria-hidden="true">🧾</div>
                    <h3 class="feature-title" role="heading" aria-level="3">Billing & Invoicing</h3>
                    <p class="feature-desc">Subscriptions, invoices, receipts, and revenue tracking</p>
                </article>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="section-gradient" role="region" aria-label="Platform statistics">
        <div class="container">
            <div class="stats-grid" role="list">
                <article class="stat-item" role="listitem" aria-label="Active members count">
                    <div class="stat-number" role="doc-subtitle" aria-live="polite">500+</div>
                    <div class="stat-label">Active Members</div>
                </article>
                <article class="stat-item" role="listitem" aria-label="Coaches count">
                    <div class="stat-number" role="doc-subtitle" aria-live="polite">50+</div>
                    <div class="stat-label">Coaches</div>
                </article>
                <article class="stat-item" role="listitem" aria-label="Branches count">
                    <div class="stat-number" role="doc-subtitle" aria-live="polite">10+</div>
                    <div class="stat-label">Branches</div>
                </article>
                <article class="stat-item" role="listitem" aria-label="Years in service">
                    <div class="stat-number" role="doc-subtitle" aria-live="polite">10+</div>
                    <div class="stat-label">Years Serving</div>
                </article>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-light" role="region" aria-label="Call to action section">
        <div class="container">
            <article class="cta-section">
                <h2 class="cta-title" role="heading" aria-level="2">Ready to streamline your academy?</h2>
                <p class="cta-text" role="doc-subtitle">Join organizations already saving time with modern scheduling, attendance, and billing tools</p>
                <div class="cta-buttons" role="group" aria-label="Get started options">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg" role="button">Get Started</a>
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-lg" role="button">Login</a>
                </div>
            </article>
        </div>
    </section>

    <!-- Footer -->
    <footer role="contentinfo" aria-label="Site footer">
        <div class="footer-content">
            <section class="footer-section" aria-labelledby="footer-brand">
                <div class="logo" style="margin-bottom: var(--space-lg);">
                    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                        <defs>
                            <linearGradient id="trophy-grad-footer" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#2563eb;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#7c3aed;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        <path d="M30 25 L30 35 Q30 50 40 55 L40 70 L35 70 L35 80 L65 80 L65 70 L60 70 L60 55 Q70 50 70 35 L70 25 Z M25 25 L25 35 Q25 40 30 40 L30 30 L25 30 Z M70 30 L70 40 Q75 40 75 35 L75 25 Z" fill="url(#trophy-grad-footer)"/>
                        <polygon points="45,15 47,22 54,23 49,27 51,34 45,30 39,34 41,27 36,23 43,22" fill="#f59e0b"/>
                    </svg>
                    <span class="logo-text" style="color: var(--white);" id="footer-brand">Sport Academy MS</span>
                </div>
                <p>All-in-one sport academy management platform</p>
            </section>

            <nav class="footer-section" aria-label="Quick navigation links">
                <h3 role="heading" aria-level="3">Quick Links</h3>
                <ul class="footer-links" role="list">
                    <li role="listitem"><a href="#features">Features</a></li>
                    <li role="listitem"><a href="{{ route('register') }}">Register</a></li>
                    <li role="listitem"><a href="{{ route('login') }}">Login</a></li>
                </ul>
            </nav>

            <section class="footer-section" aria-labelledby="footer-contact">
                <h3 role="heading" aria-level="3" id="footer-contact">Contact</h3>
                <address style="font-style: normal;">
                    <ul class="footer-links" role="list">
                        <li role="listitem"><a href="mailto:info@sportacademyms.com">📧 info@sportacademyms.app.com</a></li>
                        <li role="listitem"><a href="tel:+250786163963">📞 +250 786 163 963</a></li>
                        <li role="listitem">📍 Kigali, Rwanda</li>
                    </ul>
                </address>
            </section>
        </div>

        <div class="footer-divider">
            <p>&copy; {{ date('Y') }} Sport Academy MS. All rights reserved.</p>
        </div>
    </footer>

    <!-- Custom Interactions JS -->
    <script src="{{ asset('js/custom-interactions.js') }}"></script>
</body>
</html>
