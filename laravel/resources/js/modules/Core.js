/**
 * SPORT ACADEMY MANAGEMENT SYSTEM - CORE JAVASCRIPT MODULE
 * Sophisticated ES6+ JavaScript Framework for Modern UI Interactions
 * Created: October 29, 2025
 */

class SportAcademyCore {
    constructor() {
        this.version = '2.0.0';
        this.modules = new Map();
        this.eventBus = new EventTarget();
        this.animations = new Map();
        this.observers = new Map();
        this.utils = new Utils();
        
        console.log(`🚀 Sport Academy MS Core v${this.version} Initialized`);
        this.init();
    }

    /**
     * Initialize the core system
     */
    init() {
        this.setupEventListeners();
        this.initializeObservers();
        this.loadModules();
        this.setupPerformanceMonitoring();
    }

    /**
     * Module registration system
     */
    registerModule(name, moduleClass) {
        if (this.modules.has(name)) {
            console.warn(`⚠️ Module '${name}' is already registered`);
            return;
        }
        
        const module = new moduleClass(this);
        this.modules.set(name, module);
        console.log(`✅ Module '${name}' registered successfully`);
        
        // Emit module loaded event
        this.emit('moduleLoaded', { name, module });
    }

    /**
     * Get a module instance
     */
    getModule(name) {
        return this.modules.get(name);
    }

    /**
     * Event bus system
     */
    on(event, callback) {
        this.eventBus.addEventListener(event, callback);
    }

    off(event, callback) {
        this.eventBus.removeEventListener(event, callback);
    }

    emit(event, data = {}) {
        this.eventBus.dispatchEvent(new CustomEvent(event, { detail: data }));
    }

    /**
     * Setup global event listeners
     */
    setupEventListeners() {
        // DOM Content Loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.onDOMReady());
        } else {
            this.onDOMReady();
        }

        // Window load
        window.addEventListener('load', () => this.onWindowLoad());

        // Resize handling with debouncing
        window.addEventListener('resize', this.utils.debounce(() => {
            this.emit('windowResize', {
                width: window.innerWidth,
                height: window.innerHeight
            });
        }, 250));

        // Scroll handling with throttling
        window.addEventListener('scroll', this.utils.throttle(() => {
            this.emit('windowScroll', {
                top: window.scrollY,
                left: window.scrollX
            });
        }, 16));

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => this.handleKeyboardShortcuts(e));
    }

    /**
     * DOM Ready handler
     */
    onDOMReady() {
        console.log('📄 DOM Content Loaded');
        this.emit('domReady');
        
        // Initialize scroll animations
        this.initScrollAnimations();
        
        // Initialize theme system
        this.initThemeSystem();
        
        // Setup accessibility features
        this.setupAccessibility();
    }

    /**
     * Window Load handler
     */
    onWindowLoad() {
        console.log('🎯 Window Fully Loaded');
        this.emit('windowLoad');
        
        // Hide loading spinner if exists
        const loader = document.querySelector('.page-loader');
        if (loader) {
            this.animations.set('pageLoader', this.utils.fadeOut(loader, 500));
        }

        // Performance metrics
        this.logPerformanceMetrics();
    }

    /**
     * Initialize observers
     */
    initializeObservers() {
        // Intersection Observer for scroll animations
        const intersectionObserver = new IntersectionObserver(
            (entries) => this.handleIntersection(entries),
            {
                threshold: [0, 0.1, 0.5, 1],
                rootMargin: '-50px 0px'
            }
        );
        this.observers.set('intersection', intersectionObserver);

        // Mutation Observer for dynamic content
        const mutationObserver = new MutationObserver(
            (mutations) => this.handleMutations(mutations)
        );
        this.observers.set('mutation', mutationObserver);

        // Start observing
        mutationObserver.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['class', 'data-animate']
        });
    }

    /**
     * Handle intersection observer events
     */
    handleIntersection(entries) {
        entries.forEach(entry => {
            const element = entry.target;
            const ratio = entry.intersectionRatio;

            if (entry.isIntersecting) {
                // Trigger animations
                if (element.hasAttribute('data-animate')) {
                    this.triggerAnimation(element);
                }

                // Lazy load images
                if (element.tagName === 'IMG' && element.hasAttribute('data-src')) {
                    this.lazyLoadImage(element);
                }

                this.emit('elementVisible', { element, ratio });
            }
        });
    }

    /**
     * Handle mutation observer events
     */
    handleMutations(mutations) {
        mutations.forEach(mutation => {
            if (mutation.type === 'childList') {
                mutation.addedNodes.forEach(node => {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        this.processNewElement(node);
                    }
                });
            }
        });
    }

    /**
     * Process newly added elements
     */
    processNewElement(element) {
        // Auto-observe elements with animation attributes
        if (element.hasAttribute('data-animate')) {
            this.observers.get('intersection').observe(element);
        }

        // Process child elements
        element.querySelectorAll('[data-animate]').forEach(child => {
            this.observers.get('intersection').observe(child);
        });

        this.emit('elementAdded', { element });
    }

    /**
     * Initialize scroll animations
     */
    initScrollAnimations() {
        const animatedElements = document.querySelectorAll('[data-animate]');
        
        animatedElements.forEach(element => {
            this.observers.get('intersection').observe(element);
        });
    }

    /**
     * Trigger animation on element
     */
    triggerAnimation(element) {
        const animationType = element.getAttribute('data-animate');
        const delay = parseInt(element.getAttribute('data-delay')) || 0;
        const duration = parseInt(element.getAttribute('data-duration')) || 600;

        setTimeout(() => {
            element.classList.add('animate-in');
            
            switch (animationType) {
                case 'fadeIn':
                    this.utils.fadeIn(element, duration);
                    break;
                case 'slideUp':
                    this.utils.slideUp(element, duration);
                    break;
                case 'slideDown':
                    this.utils.slideDown(element, duration);
                    break;
                case 'slideLeft':
                    this.utils.slideLeft(element, duration);
                    break;
                case 'slideRight':
                    this.utils.slideRight(element, duration);
                    break;
                case 'scaleIn':
                    this.utils.scaleIn(element, duration);
                    break;
                default:
                    this.utils.fadeIn(element, duration);
            }
        }, delay);
    }

    /**
     * Initialize theme system
     */
    initThemeSystem() {
        const savedTheme = localStorage.getItem('sport-academy-theme') || 'light';
        this.setTheme(savedTheme);
        
        // Listen for theme toggle events
        this.on('themeToggle', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            this.setTheme(newTheme);
        });
    }

    /**
     * Set theme
     */
    setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('sport-academy-theme', theme);
        this.emit('themeChanged', { theme });
        
        console.log(`🎨 Theme changed to: ${theme}`);
    }

    /**
     * Setup accessibility features
     */
    setupAccessibility() {
        // Skip to content link
        this.addSkipToContentLink();
        
        // Focus management
        this.setupFocusManagement();
        
        // Keyboard navigation
        this.setupKeyboardNavigation();
        
        // Screen reader announcements
        this.setupScreenReaderAnnouncements();
    }

    /**
     * Add skip to content link
     */
    addSkipToContentLink() {
        const skipLink = document.createElement('a');
        skipLink.href = '#main-content';
        skipLink.className = 'skip-to-content';
        skipLink.textContent = 'Skip to main content';
        skipLink.style.cssText = `
            position: absolute;
            top: -40px;
            left: 6px;
            background: var(--primary-600);
            color: white;
            padding: 8px;
            text-decoration: none;
            border-radius: 4px;
            z-index: 10000;
            transition: top 0.3s;
        `;
        
        skipLink.addEventListener('focus', () => {
            skipLink.style.top = '6px';
        });
        
        skipLink.addEventListener('blur', () => {
            skipLink.style.top = '-40px';
        });
        
        document.body.insertBefore(skipLink, document.body.firstChild);
    }

    /**
     * Handle keyboard shortcuts
     */
    handleKeyboardShortcuts(e) {
        const shortcuts = {
            'Escape': () => this.emit('escapePressed'),
            'KeyK': (e) => {
                if (e.ctrlKey || e.metaKey) {
                    e.preventDefault();
                    this.emit('searchShortcut');
                }
            },
            'KeyT': (e) => {
                if (e.ctrlKey && e.shiftKey) {
                    e.preventDefault();
                    this.emit('themeToggle');
                }
            }
        };

        const handler = shortcuts[e.code];
        if (handler) {
            handler(e);
        }
    }

    /**
     * Setup focus management
     */
    setupFocusManagement() {
        let lastFocusedElement = null;

        document.addEventListener('focusin', (e) => {
            lastFocusedElement = e.target;
        });

        this.on('modalOpen', (e) => {
            const modal = e.detail.modal;
            const focusableElements = modal.querySelectorAll(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );
            
            if (focusableElements.length > 0) {
                focusableElements[0].focus();
            }
        });

        this.on('modalClose', () => {
            if (lastFocusedElement) {
                lastFocusedElement.focus();
            }
        });
    }

    /**
     * Setup keyboard navigation
     */
    setupKeyboardNavigation() {
        // Arrow key navigation for cards/items
        document.addEventListener('keydown', (e) => {
            if (['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                const focused = document.activeElement;
                if (focused && focused.hasAttribute('data-keyboard-nav')) {
                    this.handleArrowNavigation(e, focused);
                }
            }
        });
    }

    /**
     * Handle arrow key navigation
     */
    handleArrowNavigation(e, currentElement) {
        const container = currentElement.closest('[data-keyboard-nav-container]');
        if (!container) return;

        const items = Array.from(container.querySelectorAll('[data-keyboard-nav]'));
        const currentIndex = items.indexOf(currentElement);
        
        let nextIndex = currentIndex;
        
        switch (e.key) {
            case 'ArrowUp':
                nextIndex = currentIndex > 0 ? currentIndex - 1 : items.length - 1;
                break;
            case 'ArrowDown':
                nextIndex = currentIndex < items.length - 1 ? currentIndex + 1 : 0;
                break;
            case 'ArrowLeft':
                nextIndex = currentIndex > 0 ? currentIndex - 1 : items.length - 1;
                break;
            case 'ArrowRight':
                nextIndex = currentIndex < items.length - 1 ? currentIndex + 1 : 0;
                break;
        }
        
        if (nextIndex !== currentIndex) {
            e.preventDefault();
            items[nextIndex].focus();
        }
    }

    /**
     * Setup screen reader announcements
     */
    setupScreenReaderAnnouncements() {
        const announcer = document.createElement('div');
        announcer.setAttribute('aria-live', 'polite');
        announcer.setAttribute('aria-atomic', 'true');
        announcer.className = 'sr-only';
        announcer.style.cssText = `
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        `;
        document.body.appendChild(announcer);

        this.announcer = announcer;
    }

    /**
     * Announce to screen readers
     */
    announce(message) {
        if (this.announcer) {
            this.announcer.textContent = message;
        }
    }

    /**
     * Lazy load image
     */
    lazyLoadImage(img) {
        const src = img.getAttribute('data-src');
        if (src) {
            img.src = src;
            img.removeAttribute('data-src');
            img.classList.add('loaded');
        }
    }

    /**
     * Load modules
     */
    loadModules() {
        // Modules will be registered by individual module files
        console.log('📦 Loading modules...');
    }

    /**
     * Setup performance monitoring
     */
    setupPerformanceMonitoring() {
        if ('PerformanceObserver' in window) {
            const observer = new PerformanceObserver((list) => {
                list.getEntries().forEach((entry) => {
                    if (entry.entryType === 'navigation') {
                        console.log('🔍 Navigation Performance:', entry);
                    }
                });
            });
            observer.observe({ entryTypes: ['navigation'] });
        }
    }

    /**
     * Log performance metrics
     */
    logPerformanceMetrics() {
        if (window.performance && window.performance.timing) {
            const timing = window.performance.timing;
            const loadTime = timing.loadEventEnd - timing.navigationStart;
            const domTime = timing.domContentLoadedEventEnd - timing.navigationStart;
            
            console.log(`⏱️ Page Load Time: ${loadTime}ms`);
            console.log(`📄 DOM Ready Time: ${domTime}ms`);
            
            this.emit('performanceMetrics', {
                loadTime,
                domTime,
                timing
            });
        }
    }

    /**
     * Destroy and cleanup
     */
    destroy() {
        this.observers.forEach(observer => observer.disconnect());
        this.modules.clear();
        this.animations.clear();
        console.log('🧹 Sport Academy Core destroyed');
    }
}

/**
 * Utility class with helper functions
 */
class Utils {
    /**
     * Debounce function
     */
    debounce(func, wait, immediate = false) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                timeout = null;
                if (!immediate) func(...args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func(...args);
        };
    }

    /**
     * Throttle function
     */
    throttle(func, limit) {
        let inThrottle;
        return function (...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    /**
     * Animation utilities
     */
    fadeIn(element, duration = 300) {
        element.style.opacity = '0';
        element.style.display = 'block';
        
        const start = performance.now();
        
        const animate = (currentTime) => {
            const elapsed = currentTime - start;
            const progress = Math.min(elapsed / duration, 1);
            
            element.style.opacity = progress;
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };
        
        requestAnimationFrame(animate);
    }

    fadeOut(element, duration = 300) {
        const start = performance.now();
        const startOpacity = parseFloat(getComputedStyle(element).opacity);
        
        const animate = (currentTime) => {
            const elapsed = currentTime - start;
            const progress = Math.min(elapsed / duration, 1);
            
            element.style.opacity = startOpacity * (1 - progress);
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                element.style.display = 'none';
            }
        };
        
        requestAnimationFrame(animate);
    }

    slideUp(element, duration = 300) {
        element.style.transform = 'translateY(30px)';
        element.style.opacity = '0';
        
        const start = performance.now();
        
        const animate = (currentTime) => {
            const elapsed = currentTime - start;
            const progress = Math.min(elapsed / duration, 1);
            const easeOut = 1 - Math.pow(1 - progress, 3);
            
            element.style.transform = `translateY(${30 * (1 - easeOut)}px)`;
            element.style.opacity = easeOut;
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };
        
        requestAnimationFrame(animate);
    }

    slideDown(element, duration = 300) {
        element.style.transform = 'translateY(-30px)';
        element.style.opacity = '0';
        
        const start = performance.now();
        
        const animate = (currentTime) => {
            const elapsed = currentTime - start;
            const progress = Math.min(elapsed / duration, 1);
            const easeOut = 1 - Math.pow(1 - progress, 3);
            
            element.style.transform = `translateY(${-30 * (1 - easeOut)}px)`;
            element.style.opacity = easeOut;
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };
        
        requestAnimationFrame(animate);
    }

    slideLeft(element, duration = 300) {
        element.style.transform = 'translateX(30px)';
        element.style.opacity = '0';
        
        const start = performance.now();
        
        const animate = (currentTime) => {
            const elapsed = currentTime - start;
            const progress = Math.min(elapsed / duration, 1);
            const easeOut = 1 - Math.pow(1 - progress, 3);
            
            element.style.transform = `translateX(${30 * (1 - easeOut)}px)`;
            element.style.opacity = easeOut;
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };
        
        requestAnimationFrame(animate);
    }

    slideRight(element, duration = 300) {
        element.style.transform = 'translateX(-30px)';
        element.style.opacity = '0';
        
        const start = performance.now();
        
        const animate = (currentTime) => {
            const elapsed = currentTime - start;
            const progress = Math.min(elapsed / duration, 1);
            const easeOut = 1 - Math.pow(1 - progress, 3);
            
            element.style.transform = `translateX(${-30 * (1 - easeOut)}px)`;
            element.style.opacity = easeOut;
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };
        
        requestAnimationFrame(animate);
    }

    scaleIn(element, duration = 300) {
        element.style.transform = 'scale(0.8)';
        element.style.opacity = '0';
        
        const start = performance.now();
        
        const animate = (currentTime) => {
            const elapsed = currentTime - start;
            const progress = Math.min(elapsed / duration, 1);
            const easeOut = 1 - Math.pow(1 - progress, 3);
            
            element.style.transform = `scale(${0.8 + (0.2 * easeOut)})`;
            element.style.opacity = easeOut;
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };
        
        requestAnimationFrame(animate);
    }

    /**
     * DOM utilities
     */
    createElement(tag, className = '', attributes = {}) {
        const element = document.createElement(tag);
        if (className) element.className = className;
        
        Object.entries(attributes).forEach(([key, value]) => {
            element.setAttribute(key, value);
        });
        
        return element;
    }

    /**
     * Format numbers
     */
    formatNumber(number, options = {}) {
        return new Intl.NumberFormat('en-US', options).format(number);
    }

    /**
     * Format currency
     */
    formatCurrency(amount, currency = 'USD') {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: currency
        }).format(amount);
    }

    /**
     * Format date
     */
    formatDate(date, options = {}) {
        return new Intl.DateTimeFormat('en-US', options).format(new Date(date));
    }

    /**
     * Generate unique ID
     */
    generateId(prefix = '') {
        return prefix + Math.random().toString(36).substr(2, 9);
    }

    /**
     * Check if element is in viewport
     */
    isInViewport(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= window.innerHeight &&
            rect.right <= window.innerWidth
        );
    }

    /**
     * Get scroll position
     */
    getScrollPosition() {
        return {
            x: window.pageXOffset || document.documentElement.scrollLeft,
            y: window.pageYOffset || document.documentElement.scrollTop
        };
    }

    /**
     * Smooth scroll to element
     */
    scrollTo(target, offset = 0) {
        const element = typeof target === 'string' ? document.querySelector(target) : target;
        if (!element) return;

        const targetPosition = element.getBoundingClientRect().top + window.pageYOffset - offset;
        
        window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
        });
    }
}

// Initialize the core system
const SportAcademy = new SportAcademyCore();

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { SportAcademyCore, Utils };
} else {
    window.SportAcademy = SportAcademy;
}