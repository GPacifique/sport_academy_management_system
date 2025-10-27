<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Sport Academy MS') }} - Excellence in Sports Training</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Custom CSS (No Tailwind) -->
        <link rel="stylesheet" href="{{ asset('css/inline-styles.css') }}">
    </head>
    <body class="font-sans antialiased bg-white">
        <!-- Navigation -->
        <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center space-x-3">
                        <svg class="w-10 h-10" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="trophy-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#8b5cf6;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <path d="M30 25 L30 35 Q30 50 40 55 L40 70 L35 70 L35 80 L65 80 L65 70 L60 70 L60 55 Q70 50 70 35 L70 25 Z M25 25 L25 35 Q25 40 30 40 L30 30 L25 30 Z M70 30 L70 40 Q75 40 75 35 L75 25 Z" fill="url(#trophy-grad)"/>
                            <polygon points="45,15 47,22 54,23 49,27 51,34 45,30 39,34 41,27 36,23 43,22" fill="#fbbf24"/>
                        </svg>
                        <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Sport Academy MS</span>
                    </div>
                    
                    <!-- Auth Links -->
                    @if (Route::has('login'))
                        <div class="flex items-center space-x-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-full hover:shadow-lg hover:scale-105 transition-all duration-300">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="px-6 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                                    Login
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-6 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-full hover:shadow-lg hover:scale-105 transition-all duration-300">
                                        Register Now
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </nav>
        
        <!-- Hero Section -->
        <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50">
            <div class="max-w-7xl mx-auto">
                <div class="text-center">
                    <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold text-gray-900 mb-6 leading-tight">
                        Excellence in
                        <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            Sports Training
                        </span>
                    </h1>
                    <p class="text-xl sm:text-2xl text-gray-600 mb-12 max-w-3xl mx-auto">
                        Empowering athletes of all ages through world-class training programs in Football, Basketball, and multiple sport disciplines
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="{{ route('register') }}" class="px-8 py-4 text-lg font-semibold text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-full hover:shadow-2xl hover:scale-105 transition-all duration-300">
                            Start Your Journey
                        </a>
                        <a href="#programs" class="px-8 py-4 text-lg font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-full hover:border-blue-600 hover:text-blue-600 transition-all duration-300">
                            Explore Programs
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sports Disciplines Section -->
        <section id="programs" class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">Our Sport Disciplines</h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        Professional training programs designed to develop skills, teamwork, and championship mentality
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Football -->
                    <div class="group relative bg-gradient-to-br from-blue-500 to-blue-600 rounded-3xl p-8 hover:shadow-2xl hover:scale-105 transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity duration-300 pointer-events-none"></div>
                        <div class="relative z-10">
                            <div class="text-6xl mb-4">⚽</div>
                            <h3 class="text-2xl font-bold text-white mb-3">Football</h3>
                            <p class="text-blue-100 mb-6">Master ball control, tactical awareness, and team coordination with UEFA-certified coaches</p>
                            <ul class="space-y-2 text-blue-50">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Technical Skills
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Match Strategy
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Physical Fitness
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Basketball -->
                    <div class="group relative bg-gradient-to-br from-orange-500 to-orange-600 rounded-3xl p-8 hover:shadow-2xl hover:scale-105 transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity duration-300 pointer-events-none"></div>
                        <div class="relative z-10">
                            <div class="text-6xl mb-4">🏀</div>
                            <h3 class="text-2xl font-bold text-white mb-3">Basketball</h3>
                            <p class="text-orange-100 mb-6">Develop shooting accuracy, court vision, and defensive skills with NBA-standard training</p>
                            <ul class="space-y-2 text-orange-50">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Ball Handling
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Shooting Skills
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Team Play
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Volleyball -->
                    <div class="group relative bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-3xl p-8 hover:shadow-2xl hover:scale-105 transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity duration-300 pointer-events-none"></div>
                        <div class="relative z-10">
                            <div class="text-6xl mb-4">🏐</div>
                            <h3 class="text-2xl font-bold text-white mb-3">Volleyball</h3>
                            <p class="text-emerald-100 mb-6">Perfect your serve, spike, and block with Olympic-level coaching expertise</p>
                            <ul class="space-y-2 text-emerald-50">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Serving Power
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Spike Techniques
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Defense Skills
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tennis -->
                    <div class="group relative bg-gradient-to-br from-purple-500 to-purple-600 rounded-3xl p-8 hover:shadow-2xl hover:scale-105 transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition-opacity duration-300 pointer-events-none"></div>
                        <div class="relative z-10">
                            <div class="text-6xl mb-4">🎾</div>
                            <h3 class="text-2xl font-bold text-white mb-3">Tennis</h3>
                            <p class="text-purple-100 mb-6">Master forehand, backhand, and serve with Grand Slam coaching methodologies</p>
                            <ul class="space-y-2 text-purple-50">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Stroke Technique
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Court Strategy
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Mental Game
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 to-gray-100">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">Why Choose Our Academy</h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        World-class facilities, expert coaches, and proven training methodologies
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-shadow duration-300">
                        <div class="text-5xl mb-4">👨‍🏫</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Expert Coaches</h3>
                        <p class="text-gray-600">
                            Learn from certified professionals with international experience and proven track records in athlete development
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-shadow duration-300">
                        <div class="text-5xl mb-4">🏟️</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Modern Facilities</h3>
                        <p class="text-gray-600">
                            Train in state-of-the-art facilities with professional-grade equipment and cutting-edge technology
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-shadow duration-300">
                        <div class="text-5xl mb-4">📊</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Performance Tracking</h3>
                        <p class="text-gray-600">
                            Monitor your progress with advanced analytics, attendance tracking, and personalized performance reports
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-shadow duration-300">
                        <div class="text-5xl mb-4">🏆</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Competitive Programs</h3>
                        <p class="text-gray-600">
                            Participate in local and regional tournaments to test your skills and gain valuable competition experience
                        </p>
                    </div>

                    <!-- Feature 5 -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-shadow duration-300">
                        <div class="text-5xl mb-4">👥</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Small Group Training</h3>
                        <p class="text-gray-600">
                            Benefit from personalized attention with organized groups (A-F) ensuring optimal coach-to-student ratios
                        </p>
                    </div>

                    <!-- Feature 6 -->
                    <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-shadow duration-300">
                        <div class="text-5xl mb-4">💳</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Flexible Payment Plans</h3>
                        <p class="text-gray-600">
                            Choose from various payment options with transparent fee structures and family discount programs
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-r from-blue-600 to-purple-600">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white">
                    <div>
                        <div class="text-5xl font-bold mb-2">500+</div>
                        <div class="text-xl opacity-90">Active Students</div>
                    </div>
                    <div>
                        <div class="text-5xl font-bold mb-2">50+</div>
                        <div class="text-xl opacity-90">Expert Coaches</div>
                    </div>
                    <div>
                        <div class="text-5xl font-bold mb-2">15+</div>
                        <div class="text-xl opacity-90">Sport Disciplines</div>
                    </div>
                    <div>
                        <div class="text-5xl font-bold mb-2">10+</div>
                        <div class="text-xl opacity-90">Years Experience</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-6">Ready to Start Your Journey?</h2>
                <p class="text-xl text-gray-600 mb-8">
                    Join hundreds of athletes who are already achieving their dreams with our professional training programs
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('register') }}" class="px-10 py-4 text-lg font-semibold text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-full hover:shadow-2xl hover:scale-105 transition-all duration-300">
                        Register Now
                    </a>
                    <a href="{{ route('login') }}" class="px-10 py-4 text-lg font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-full hover:border-blue-600 hover:text-blue-600 transition-all duration-300">
                        Login to Your Account
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-900 to-gray-900 text-white">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <div>
                        <div class="flex items-center space-x-3 mb-4">
                            <svg class="w-10 h-10" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="trophy-grad-footer" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
                                        <stop offset="100%" style="stop-color:#8b5cf6;stop-opacity:1" />
                                    </linearGradient>
                                </defs>
                                <path d="M30 25 L30 35 Q30 50 40 55 L40 70 L35 70 L35 80 L65 80 L65 70 L60 70 L60 55 Q70 50 70 35 L70 25 Z M25 25 L25 35 Q25 40 30 40 L30 30 L25 30 Z M70 30 L70 40 Q75 40 75 35 L75 25 Z" fill="url(#trophy-grad-footer)"/>
                                <polygon points="45,15 47,22 54,23 49,27 51,34 45,30 39,34 41,27 36,23 43,22" fill="#fbbf24"/>
                            </svg>
                            <span class="text-2xl font-bold">Sport Academy MS</span>
                        </div>
                        <p class="text-gray-400">
                            Excellence in sports training and athlete development since 2014
                        </p>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="#programs" class="hover:text-white transition-colors">Our Programs</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Register</a></li>
                            <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Login</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Contact</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li>📧 info@sportacademyms.com</li>
                            <li>📞 +250 XXX XXX XXX</li>
                            <li>📍 Kigali, Rwanda</li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                    <p>&copy; {{ date('Y') }} Sport Academy MS. All rights reserved.</p>
                </div>
            </div>
        </footer>
    @include('partials.inline-scripts')
        <script>
            // Smooth-scroll for in-page anchors and defensive clickability helpers
            document.addEventListener('click', function (e) {
                const link = e.target.closest('a[href]');
                if (!link) return;
                const href = link.getAttribute('href');
                if (!href) return;
                if (href.startsWith('#')) {
                    const target = document.querySelector(href);
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            }, true);

            // Ensure links/buttons remain interactive even inside complex components
            document.querySelectorAll('a, button').forEach(el => {
                el.style.pointerEvents = 'auto';
                el.style.cursor = 'pointer';
            });
        </script>
    <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
