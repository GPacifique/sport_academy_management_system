/**
 * Sport Academy Management System - Integration Module
 * Handles integration with existing Laravel Blade templates and Alpine.js components
 */

class IntegrationModule {
    constructor(core) {
        this.core = core;
        this.name = 'Integration';
        this.initialized = false;
        
        // Alpine.js integration
        this.alpineComponents = new Map();
        this.alpineDirectives = new Map();
        
        // Laravel integration
        this.csrfToken = null;
        this.routes = new Map();
        this.flashMessages = [];
        
        // Performance monitoring
        this.performanceMetrics = {
            moduleLoadTimes: new Map(),
            interactionMetrics: [],
            errorCount: 0
        };
        
        this.init();
    }
    
    init() {
        if (this.initialized) return;
        
        try {
            this.setupLaravelIntegration();
            this.setupAlpineIntegration();
            this.enhanceExistingElements();
            this.setupPerformanceMonitoring();
            this.setupErrorHandling();
            this.setupAccessibilityEnhancements();
            
            this.initialized = true;
            this.core.log('Integration module initialized');
            this.core.emit('moduleLoaded', { module: this.name });
            
        } catch (error) {
            console.error('Integration module initialization failed:', error);
        }
    }
    
    setupLaravelIntegration() {
        // Extract CSRF token
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta) {
            this.csrfToken = csrfMeta.content;
            
            // Set default headers for fetch requests
            const originalFetch = window.fetch;
            window.fetch = (url, options = {}) => {
                options.headers = {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    ...options.headers
                };
                return originalFetch(url, options);
            };
        }
        
        // Process Laravel routes if available
        if (window.routes) {
            Object.entries(window.routes).forEach(([name, url]) => {
                this.routes.set(name, url);
            });
        }
        
        // Process flash messages
        this.processFlashMessages();
        
        // Setup Laravel validation integration
        this.setupValidationIntegration();
    }
    
    setupAlpineIntegration() {
        // Wait for Alpine to be available
        if (window.Alpine) {
            this.enhanceAlpineComponents();
        } else {
            // Wait for Alpine to load
            document.addEventListener('alpine:init', () => {
                this.enhanceAlpineComponents();
            });
        }
    }
    
    enhanceAlpineComponents() {
        // Add custom Alpine directives
        if (window.Alpine) {
            // x-tooltip directive
            Alpine.directive('tooltip', (el, { expression }, { evaluate }) => {
                const tooltip = evaluate(expression);
                el.title = tooltip;
                el.classList.add('tooltip-enabled');
            });
            
            // x-confirm directive for delete operations
            Alpine.directive('confirm', (el, { expression }, { evaluate }) => {
                const message = evaluate(expression) || 'Are you sure?';
                el.addEventListener('click', async (e) => {
                    e.preventDefault();
                    const modalModule = this.core.getModule('Modal');
                    if (modalModule) {
                        const confirmed = await modalModule.confirm(message);
                        if (confirmed) {
                            // Proceed with original action
                            const form = el.closest('form');
                            const href = el.href;
                            
                            if (form) {
                                form.submit();
                            } else if (href) {
                                window.location.href = href;
                            }
                        }
                    } else {
                        if (confirm(message)) {
                            const form = el.closest('form');
                            const href = el.href;
                            
                            if (form) {
                                form.submit();
                            } else if (href) {
                                window.location.href = href;
                            }
                        }
                    }
                });
            });
            
            // x-modal directive
            Alpine.directive('modal', (el, { value, expression }, { evaluate }) => {
                el.addEventListener('click', (e) => {
                    e.preventDefault();
                    const modalOptions = evaluate(expression) || {};
                    const modalModule = this.core.getModule('Modal');
                    
                    if (modalModule) {
                        modalModule.show({
                            title: modalOptions.title || 'Modal',
                            content: modalOptions.content || '',
                            size: modalOptions.size || 'md',
                            ...modalOptions
                        });
                    }
                });
            });
        }
    }
    
    enhanceExistingElements() {
        // Enhance tables
        this.enhanceDataTables();
        
        // Enhance forms
        this.enhanceForms();
        
        // Enhance buttons and links
        this.enhanceInteractiveElements();
        
        // Enhance cards and panels
        this.enhanceCards();
        
        // Add loading states
        this.addLoadingStates();
    }
    
    enhanceDataTables() {
        const tables = document.querySelectorAll('table:not([data-enhanced])');
        
        tables.forEach(table => {
            table.setAttribute('data-enhanced', 'true');
            table.classList.add('enhanced-table');
            
            // Add responsive wrapper if not present
            if (!table.closest('.table-responsive')) {
                const wrapper = document.createElement('div');
                wrapper.className = 'table-responsive';
                table.parentNode.insertBefore(wrapper, table);
                wrapper.appendChild(table);
            }
            
            // Add sorting capabilities to headers
            const headers = table.querySelectorAll('th[data-sortable]');
            headers.forEach(header => {
                header.style.cursor = 'pointer';
                header.addEventListener('click', () => this.handleTableSort(table, header));
                
                // Add sort icon
                if (!header.querySelector('.sort-icon')) {
                    const icon = document.createElement('span');
                    icon.className = 'sort-icon ml-1 text-gray-400';
                    icon.innerHTML = '↕️';
                    header.appendChild(icon);
                }
            });
            
            // Add search functionality if search input exists
            const searchInput = document.querySelector(`[data-table-search="${table.id}"]`);
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    this.filterTable(table, e.target.value);
                });
            }
        });
    }
    
    enhanceForms() {
        const forms = document.querySelectorAll('form:not([data-enhanced])');
        
        forms.forEach(form => {
            form.setAttribute('data-enhanced', 'true');
            
            // Add loading state on submit
            form.addEventListener('submit', (e) => {
                const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
                if (submitBtn && !submitBtn.disabled) {
                    this.addLoadingState(submitBtn);
                }
            });
            
            // Enhance file inputs
            const fileInputs = form.querySelectorAll('input[type="file"]');
            fileInputs.forEach(input => this.enhanceFileInput(input));
            
            // Add auto-save functionality for drafts
            if (form.dataset.autosave) {
                this.setupAutoSave(form);
            }
        });
    }
    
    enhanceFileInput(input) {
        const wrapper = document.createElement('div');
        wrapper.className = 'file-input-wrapper relative';
        
        const preview = document.createElement('div');
        preview.className = 'file-preview mt-2 hidden';
        
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);
        wrapper.appendChild(preview);
        
        input.addEventListener('change', (e) => {
            const files = Array.from(e.target.files);
            this.updateFilePreview(preview, files);
        });
    }
    
    updateFilePreview(preview, files) {
        preview.innerHTML = '';
        preview.classList.toggle('hidden', files.length === 0);
        
        files.forEach(file => {
            const item = document.createElement('div');
            item.className = 'file-item flex items-center gap-2 p-2 bg-gray-50 rounded border';
            
            const icon = this.getFileIcon(file.type);
            const size = this.formatFileSize(file.size);
            
            item.innerHTML = `
                <span class="file-icon">${icon}</span>
                <div class="flex-1">
                    <div class="font-medium text-sm">${file.name}</div>
                    <div class="text-xs text-gray-500">${size}</div>
                </div>
            `;
            
            preview.appendChild(item);
        });
    }
    
    getFileIcon(mimeType) {
        if (mimeType.startsWith('image/')) return '🖼️';
        if (mimeType.startsWith('video/')) return '🎥';
        if (mimeType.startsWith('audio/')) return '🎵';
        if (mimeType.includes('pdf')) return '📄';
        if (mimeType.includes('word')) return '📝';
        if (mimeType.includes('excel') || mimeType.includes('spreadsheet')) return '📊';
        return '📁';
    }
    
    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    enhanceInteractiveElements() {
        // Enhance buttons with ripple effect
        const buttons = document.querySelectorAll('button:not([data-enhanced]), a.btn:not([data-enhanced])');
        
        buttons.forEach(button => {
            button.setAttribute('data-enhanced', 'true');
            this.addRippleEffect(button);
        });
        
        // Enhance delete links/buttons with confirmation
        const deleteElements = document.querySelectorAll('[data-confirm]:not([data-enhanced])');
        deleteElements.forEach(el => {
            el.setAttribute('data-enhanced', 'true');
            el.addEventListener('click', async (e) => {
                e.preventDefault();
                const message = el.dataset.confirm || 'Are you sure you want to delete this item?';
                const modalModule = this.core.getModule('Modal');
                
                if (modalModule) {
                    const confirmed = await modalModule.confirm(message, {
                        type: 'danger',
                        confirmText: 'Delete',
                        cancelText: 'Cancel'
                    });
                    
                    if (confirmed) {
                        this.executeAction(el);
                    }
                } else {
                    if (confirm(message)) {
                        this.executeAction(el);
                    }
                }
            });
        });
    }
    
    addRippleEffect(element) {
        element.style.position = 'relative';
        element.style.overflow = 'hidden';
        
        element.addEventListener('click', (e) => {
            const ripple = document.createElement('span');
            const rect = element.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: rgba(255, 255, 255, 0.5);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s ease-out;
                pointer-events: none;
            `;
            
            element.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
        
        // Add ripple animation CSS if not already present
        if (!document.querySelector('#ripple-animation')) {
            const style = document.createElement('style');
            style.id = 'ripple-animation';
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }
    }
    
    executeAction(element) {
        if (element.tagName === 'A') {
            window.location.href = element.href;
        } else if (element.tagName === 'BUTTON') {
            const form = element.closest('form');
            if (form) {
                form.submit();
            }
        }
    }
    
    addLoadingState(element) {
        if (element.dataset.loading) return;
        
        element.dataset.loading = 'true';
        element.dataset.originalText = element.textContent;
        element.disabled = true;
        
        const spinner = '<span class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-current mr-2"></span>';
        element.innerHTML = spinner + 'Loading...';
        
        // Auto-remove loading state after 10 seconds (fallback)
        setTimeout(() => {
            this.removeLoadingState(element);
        }, 10000);
    }
    
    removeLoadingState(element) {
        if (!element.dataset.loading) return;
        
        delete element.dataset.loading;
        element.disabled = false;
        element.textContent = element.dataset.originalText || 'Submit';
        delete element.dataset.originalText;
    }
    
    processFlashMessages() {
        const flashContainer = document.querySelector('[data-flash-messages]');
        if (flashContainer) {
            const messages = JSON.parse(flashContainer.textContent || '[]');
            
            messages.forEach(message => {
                const notificationModule = this.core.getModule('Notification');
                if (notificationModule) {
                    notificationModule.show(message.content, {
                        type: message.type || 'info',
                        duration: 5000
                    });
                }
            });
        }
    }
    
    setupValidationIntegration() {
        // Handle Laravel validation errors
        const errorBags = document.querySelectorAll('[data-errors]');
        
        errorBags.forEach(bag => {
            const errors = JSON.parse(bag.textContent || '{}');
            
            Object.entries(errors).forEach(([field, messages]) => {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    this.showFieldError(input, messages[0]);
                }
            });
        });
    }
    
    showFieldError(input, message) {
        input.classList.add('border-red-500', 'error');
        
        // Remove existing error message
        const existingError = input.parentNode.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }
        
        // Add new error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message text-red-500 text-sm mt-1';
        errorDiv.textContent = message;
        
        input.parentNode.appendChild(errorDiv);
        
        // Remove error when user starts typing
        input.addEventListener('input', () => {
            input.classList.remove('border-red-500', 'error');
            errorDiv.remove();
        }, { once: true });
    }
    
    setupPerformanceMonitoring() {
        // Monitor module load times
        this.core.on('moduleLoaded', (data) => {
            this.performanceMetrics.moduleLoadTimes.set(data.module, performance.now());
        });
        
        // Monitor page interactions
        ['click', 'submit', 'input'].forEach(event => {
            document.addEventListener(event, (e) => {
                this.performanceMetrics.interactionMetrics.push({
                    type: event,
                    target: e.target.tagName,
                    timestamp: performance.now()
                });
                
                // Keep only last 100 interactions
                if (this.performanceMetrics.interactionMetrics.length > 100) {
                    this.performanceMetrics.interactionMetrics.shift();
                }
            });
        });
    }
    
    setupErrorHandling() {
        // Global error handler
        window.addEventListener('error', (e) => {
            this.performanceMetrics.errorCount++;
            console.error('Global error caught:', e.error);
            
            // Show user-friendly error message
            const notificationModule = this.core.getModule('Notification');
            if (notificationModule) {
                notificationModule.show('An unexpected error occurred. Please refresh the page.', {
                    type: 'error',
                    duration: 8000
                });
            }
        });
        
        // Promise rejection handler
        window.addEventListener('unhandledrejection', (e) => {
            this.performanceMetrics.errorCount++;
            console.error('Unhandled promise rejection:', e.reason);
        });
    }
    
    setupAccessibilityEnhancements() {
        // Add keyboard navigation enhancements
        document.addEventListener('keydown', (e) => {
            // Escape key closes modals
            if (e.key === 'Escape') {
                const openModal = document.querySelector('.modal.show, [data-modal].show');
                if (openModal) {
                    const modalModule = this.core.getModule('Modal');
                    if (modalModule) {
                        modalModule.closeAll();
                    }
                }
            }
            
            // Tab navigation improvements
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
            }
        });
        
        // Remove keyboard navigation class on mouse use
        document.addEventListener('mousedown', () => {
            document.body.classList.remove('keyboard-navigation');
        });
        
        // Add skip navigation link
        this.addSkipNavigation();
        
        // Enhance form labels and ARIA attributes
        this.enhanceAccessibility();
    }
    
    addSkipNavigation() {
        const skipLink = document.createElement('a');
        skipLink.href = '#main-content';
        skipLink.className = 'skip-navigation sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:bg-blue-600 focus:text-white focus:px-4 focus:py-2 focus:rounded';
        skipLink.textContent = 'Skip to main content';
        
        document.body.insertBefore(skipLink, document.body.firstChild);
        
        // Ensure main content area exists
        let mainContent = document.getElementById('main-content');
        if (!mainContent) {
            mainContent = document.querySelector('main, [role="main"], .main-content');
            if (mainContent) {
                mainContent.id = 'main-content';
            }
        }
    }
    
    enhanceAccessibility() {
        // Enhance form inputs without labels
        const inputs = document.querySelectorAll('input:not([aria-label]):not([aria-labelledby])');
        inputs.forEach(input => {
            const placeholder = input.placeholder;
            if (placeholder && !input.closest('label')) {
                input.setAttribute('aria-label', placeholder);
            }
        });
        
        // Add role attributes to common elements
        const navElements = document.querySelectorAll('nav:not([role])');
        navElements.forEach(nav => nav.setAttribute('role', 'navigation'));
        
        const mainElements = document.querySelectorAll('main:not([role])');
        mainElements.forEach(main => main.setAttribute('role', 'main'));
    }
    
    // Utility methods
    getPerformanceMetrics() {
        return {
            ...this.performanceMetrics,
            loadTime: performance.now(),
            memoryUsage: performance.memory ? {
                used: Math.round(performance.memory.usedJSHeapSize / 1048576),
                total: Math.round(performance.memory.totalJSHeapSize / 1048576),
                limit: Math.round(performance.memory.jsHeapSizeLimit / 1048576)
            } : null
        };
    }
    
    getRouteUrl(name, params = {}) {
        let url = this.routes.get(name);
        if (!url) return null;
        
        // Replace route parameters
        Object.entries(params).forEach(([key, value]) => {
            url = url.replace(`{${key}}`, value);
        });
        
        return url;
    }
    
    destroy() {
        // Clean up enhanced elements
        document.querySelectorAll('[data-enhanced]').forEach(el => {
            el.removeAttribute('data-enhanced');
        });
        
        this.initialized = false;
        this.core.log('Integration module destroyed');
    }
}

// Auto-register with core if available
if (window.SportAcademy) {
    window.SportAcademy.registerModule(IntegrationModule);
} else {
    // Queue for when core becomes available
    window.addEventListener('SportAcademyCoreReady', () => {
        window.SportAcademy.registerModule(IntegrationModule);
    });
}

export default IntegrationModule;