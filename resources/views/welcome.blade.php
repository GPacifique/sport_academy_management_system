<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sport Club MS') }} - Modern Sport Club Management</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Custom Design System (Platform Independent) -->
    <link rel="stylesheet" href="{{ asset('css/custom-design.css') }}">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-container">
            <!-- Logo -->
            <div class="logo">
                <svg class="w-10 h-10" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="trophy-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#2563eb;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#7c3aed;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <path d="M30 25 L30 35 Q30 50 40 55 L40 70 L35 70 L35 80 L65 80 L65 70 L60 70 L60 55 Q70 50 70 35 L70 25 Z M25 25 L25 35 Q25 40 30 40 L30 30 L25 30 Z M70 30 L70 40 Q75 40 75 35 L75 25 Z" fill="url(#trophy-grad)"/>
                    <polygon points="45,15 47,22 54,23 49,27 51,34 45,30 39,34 41,27 36,23 43,22" fill="#f59e0b"/>
                </svg>
                <span class="logo-text">Sport Club MS</span>
            </div>

            <!-- Auth Links -->
            <ul class="nav-links">
                @if (Route::has('login'))
                    @auth
                        <li><a href="{{ url('/dashboard') }}" class="btn btn-nav">Dashboard</a></li>
                    @else
                        <li><a href="{{ route('login') }}">Login</a></li>
                        @if (Route::has('register'))
                            <li><a href="{{ route('register') }}" class="btn btn-nav">Register</a></li>
                        @endif
                    @endauth
                @endif
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>All‑in‑One<br><span class="gradient-text">Sport Club Management</span></h1>
                <p>Manage members, coaches, schedules, attendance, payments, and reports in one secure, easy-to-use platform.</p>
                <div class="hero-buttons">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Get Started</a>
                    <a href="#modules" class="btn btn-secondary btn-lg">Explore Features</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Modules Section -->
    <section id="modules" class="section-light">
        <div class="container">
            <div class="section-title">
                <h2>Core Modules</h2>
                <p>Everything you need to run your club efficiently</p>
            </div>

            <div class="grid grid-cols-4">
                <!-- Members -->
                <div class="module-card" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                    <div class="module-icon">👤</div>
                    <h3 class="module-title">Member Management</h3>
                    <p class="module-desc">Profiles, roles, memberships, and communication in one place</p>
                    <ul class="module-features">
                        <li><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Members & Coaches</li>
                        <li><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Branches & Groups</li>
                        <li><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Role-based Access</li>
                    </ul>
                </div>

                <!-- Scheduling -->
                <div class="module-card" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
                    <div class="module-icon">🗓️</div>
                    <h3 class="module-title">Scheduling</h3>
                    <p class="module-desc">Plan training sessions, assign coaches, and manage venues</p>
                    <ul class="module-features">
                        <li><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Calendar & Filters</li>
                        <li><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Coach Assignment</li>
                        <li><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Venue Management</li>
                    </ul>
                </div>

                <!-- Attendance -->
                <div class="module-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="module-icon">✅</div>
                    <h3 class="module-title">Attendance</h3>
                    <p class="module-desc">Track attendance for students and coaches with quick actions</p>
                    <ul class="module-features">
                        <li><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Student Logs</li>
                        <li><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Exportable Reports</li>
                        <li><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Insights</li>
                    </ul>
                </div>

                <!-- Payments -->
                <div class="module-card" style="background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);">
                    <div class="module-icon">💳</div>
                    <h3 class="module-title">Payments & Billing</h3>
                    <p class="module-desc">Subscriptions, invoices, and payment tracking with summaries</p>
                    <ul class="module-features">
                        <li><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Subscriptions</li>
                        <li><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Invoices</li>
                        <li><svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Revenue</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section-alt">
        <div class="container">
            <div class="section-title">
                <h2>Why Clubs Choose Us</h2>
                <p>Powerful features designed for real club operations</p>
            </div>

            <div class="features-grid">
                <div class="feature-item card">
                    <div class="feature-icon">🔐</div>
                    <h3 class="feature-title">Role-based Access</h3>
                    <p class="feature-desc">Admin, coach, and member roles with fine-grained permissions</p>
                </div>

                <div class="feature-item card">
                    <div class="feature-icon">🏢</div>
                    <h3 class="feature-title">Multi-Branch Support</h3>
                    <p class="feature-desc">Organize your organization by branches and groups/teams</p>
                </div>

                <div class="feature-item card">
                    <div class="feature-icon">📊</div>
                    <h3 class="feature-title">Analytics & Reports</h3>
                    <p class="feature-desc">Attendance, revenue, and participation insights at a glance</p>
                </div>

                <div class="feature-item card">
                    <div class="feature-icon">🔔</div>
                    <h3 class="feature-title">Notifications</h3>
                    <p class="feature-desc">Keep members informed via email/SMS (provider dependent)</p>
                </div>

                <div class="feature-item card">
                    <div class="feature-icon">🔗</div>
                    <h3 class="feature-title">Integrations</h3>
                    <p class="feature-desc">Export data or integrate with gateways and accounting tools</p>
                </div>

                <div class="feature-item card">
                    <div class="feature-icon">🧾</div>
                    <h3 class="feature-title">Billing & Invoicing</h3>
                    <p class="feature-desc">Subscriptions, invoices, receipts, and revenue tracking</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="section-gradient">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Active Members</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Coaches</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">10+</div>
                    <div class="stat-label">Branches</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">10+</div>
                    <div class="stat-label">Years Serving</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-light">
        <div class="container">
            <div class="cta-section">
                <h2 class="cta-title">Ready to streamline your club?</h2>
                <p class="cta-text">Join organizations already saving time with modern scheduling, attendance, and billing tools</p>
                <div class="cta-buttons">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Get Started</a>
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-lg">Login</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <div class="logo" style="margin-bottom: var(--space-lg);">
                    <svg class="w-10 h-10" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="trophy-grad-footer" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#2563eb;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#7c3aed;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        <path d="M30 25 L30 35 Q30 50 40 55 L40 70 L35 70 L35 80 L65 80 L65 70 L60 70 L60 55 Q70 50 70 35 L70 25 Z M25 25 L25 35 Q25 40 30 40 L30 30 L25 30 Z M70 30 L70 40 Q75 40 75 35 L75 25 Z" fill="url(#trophy-grad-footer)"/>
                        <polygon points="45,15 47,22 54,23 49,27 51,34 45,30 39,34 41,27 36,23 43,22" fill="#f59e0b"/>
                    </svg>
                    <span class="logo-text" style="color: var(--white);">Sport Club MS</span>
                </div>
                <p>All-in-one sport club management platform</p>
            </div>

            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="#features">Features</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                    <li><a href="{{ route('login') }}">Login</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>Contact</h4>
                <ul class="footer-links">
                    <li>📧 info@sportacademyms.com</li>
                    <li>📞 +250 XXX XXX XXX</li>
                    <li>📍 Kigali, Rwanda</li>
                </ul>
            </div>
        </div>

        <div class="footer-divider">
            <p>&copy; {{ date('Y') }} Sport Club MS. All rights reserved.</p>
        </div>
    </footer>

    <!-- Custom Interactions JS -->
    <script src="{{ asset('js/custom-interactions.js') }}"></script>
</body>
</html>
