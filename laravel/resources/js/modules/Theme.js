/**
 * Sport Academy Management System - Theme Module
 * Sophisticated theme management with dark/light modes, user preferences, and smooth transitions
 */

class ThemeModule {
    constructor(core) {
        this.core = core;
        this.name = 'Theme';
        this.initialized = false;
        
        // Theme configuration
        this.themes = {
            light: {
                name: 'light',
                label: 'Light Theme',
                icon: '☀️',
                primary: '#059669',
                background: '#ffffff',
                surface: '#f8fafc',
                text: '#1f2937'
            },
            dark: {
                name: 'dark',
                label: 'Dark Theme',
                icon: '🌙',
                primary: '#10b981',
                background: '#0f172a',
                surface: '#1e293b',
                text: '#f1f5f9'
            },
            auto: {
                name: 'auto',
                label: 'Auto (System)',
                icon: '🌓',
                followsSystem: true
            }
        };
        
        this.currentTheme = 'auto';
        this.systemPrefersDark = false;
        this.mediaQuery = null;
        this.observers = new Set();
        
        // Storage keys
        this.storageKey = 'sport-academy-theme';
        this.legacyKey = 'theme';
        
        // Transition settings
        this.transitionDuration = 300;
        this.transitionClass = 'theme-transition';
        
        this.init();
    }
    
    init() {
        if (this.initialized) return;
        
        try {
            this.setupMediaQuery();
            this.loadSavedTheme();
            this.createThemeTransitions();
            this.bindEvents();
            this.applyTheme(this.currentTheme);
            this.createThemeControls();
            
            this.initialized = true;
            this.core.log('Theme module initialized', { theme: this.currentTheme });
            this.core.emit('moduleLoaded', { module: this.name });
            
        } catch (error) {
            console.error('Theme module initialization failed:', error);
        }
    }
    
    setupMediaQuery() {
        if (window.matchMedia) {
            this.mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            this.systemPrefersDark = this.mediaQuery.matches;
            
            // Listen for system theme changes
            this.mediaQuery.addEventListener('change', (e) => {
                this.systemPrefersDark = e.matches;
                if (this.currentTheme === 'auto') {
                    this.applyTheme('auto');
                }
                this.notifyObservers();
            });
        }
    }
    
    loadSavedTheme() {
        // Check for saved theme preference
        const saved = localStorage.getItem(this.storageKey) || 
                     localStorage.getItem(this.legacyKey) || 
                     'auto';
        
        if (this.themes[saved]) {
            this.currentTheme = saved;
        } else {
            this.currentTheme = 'auto';
        }
    }
    
    createThemeTransitions() {
        // Add CSS for smooth theme transitions
        const style = document.createElement('style');
        style.textContent = `
            .${this.transitionClass} {
                transition: 
                    background-color ${this.transitionDuration}ms ease,
                    border-color ${this.transitionDuration}ms ease,
                    color ${this.transitionDuration}ms ease,
                    fill ${this.transitionDuration}ms ease,
                    stroke ${this.transitionDuration}ms ease,
                    box-shadow ${this.transitionDuration}ms ease;
            }
            
            .${this.transitionClass} * {
                transition: 
                    background-color ${this.transitionDuration}ms ease,
                    border-color ${this.transitionDuration}ms ease,
                    color ${this.transitionDuration}ms ease,
                    fill ${this.transitionDuration}ms ease,
                    stroke ${this.transitionDuration}ms ease,
                    box-shadow ${this.transitionDuration}ms ease;
            }
            
            .theme-toggle-button {
                position: relative;
                overflow: hidden;
                transform: translateZ(0);
            }
            
            .theme-toggle-button::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 0;
                height: 0;
                background: currentColor;
                border-radius: 50%;
                opacity: 0.1;
                transform: translate(-50%, -50%);
                transition: width 0.3s ease, height 0.3s ease;
                pointer-events: none;
            }
            
            .theme-toggle-button:hover::before {
                width: 100%;
                height: 100%;
            }
            
            .theme-option {
                transition: all 0.2s ease;
                transform: translateY(0);
            }
            
            .theme-option:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }
            
            .theme-option.active {
                transform: scale(1.02);
                box-shadow: 0 0 0 2px var(--color-primary);
            }
        `;
        document.head.appendChild(style);
    }
    
    bindEvents() {
        // Listen for theme change events
        this.core.on('themeChange', (data) => {
            this.setTheme(data.theme);
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey && e.shiftKey && e.key === 'T') {
                e.preventDefault();
                this.cycleTheme();
            }
        });
    }
    
    applyTheme(themeName, smooth = true) {
        const resolvedTheme = this.resolveTheme(themeName);
        const themeConfig = this.themes[resolvedTheme];
        
        if (smooth) {
            document.documentElement.classList.add(this.transitionClass);
            setTimeout(() => {
                document.documentElement.classList.remove(this.transitionClass);
            }, this.transitionDuration);
        }
        
        // Apply theme classes
        document.documentElement.classList.remove('light', 'dark', 'theme-light', 'theme-dark');
        document.documentElement.classList.add(resolvedTheme, `theme-${resolvedTheme}`);
        document.documentElement.setAttribute('data-theme', resolvedTheme);
        
        // Set CSS custom properties
        if (themeConfig && !themeConfig.followsSystem) {
            this.setCSSProperties(themeConfig);
        }
        
        // Update meta theme-color for mobile browsers
        this.updateMetaThemeColor(resolvedTheme);
        
        // Save to localStorage
        localStorage.setItem(this.storageKey, themeName);
        localStorage.setItem(this.legacyKey, themeName);
        
        // Notify observers
        this.notifyObservers();
        
        // Emit event
        this.core.emit('themeChanged', { 
            theme: themeName, 
            resolved: resolvedTheme,
            config: themeConfig 
        });
        
        this.core.log('Theme applied', { theme: themeName, resolved: resolvedTheme });
    }
    
    resolveTheme(themeName) {
        if (themeName === 'auto') {
            return this.systemPrefersDark ? 'dark' : 'light';
        }
        return themeName;
    }
    
    setCSSProperties(config) {
        const root = document.documentElement;
        Object.entries(config).forEach(([key, value]) => {
            if (typeof value === 'string' && value.startsWith('#')) {
                root.style.setProperty(`--theme-${key}`, value);
            }
        });
    }
    
    updateMetaThemeColor(theme) {
        let metaThemeColor = document.querySelector('meta[name="theme-color"]');
        if (!metaThemeColor) {
            metaThemeColor = document.createElement('meta');
            metaThemeColor.name = 'theme-color';
            document.head.appendChild(metaThemeColor);
        }
        
        const color = theme === 'dark' ? '#0f172a' : '#ffffff';
        metaThemeColor.content = color;
    }
    
    createThemeControls() {
        // Create theme toggle button for quick access
        this.createQuickToggle();
        
        // Create theme selector modal/dropdown
        this.createThemeSelector();
    }
    
    createQuickToggle() {
        // Look for existing theme toggle button
        const existingToggle = document.querySelector('[data-theme-toggle]');
        if (existingToggle) {
            this.enhanceExistingToggle(existingToggle);
            return;
        }
        
        // Create floating theme toggle if no existing button found
        const toggle = document.createElement('button');
        toggle.className = `
            fixed bottom-4 left-4 z-40 w-12 h-12 rounded-full
            bg-white dark:bg-slate-800 shadow-lg border border-slate-200 dark:border-slate-700
            flex items-center justify-center text-lg
            hover:shadow-xl transition-all duration-200
            theme-toggle-button
        `.replace(/\s+/g, ' ');
        
        toggle.innerHTML = this.getThemeIcon(this.currentTheme);
        toggle.title = `Current theme: ${this.themes[this.currentTheme].label}`;
        toggle.setAttribute('data-theme-toggle', '');
        
        toggle.addEventListener('click', () => {
            this.showThemeSelector();
        });
        
        document.body.appendChild(toggle);
        this.quickToggle = toggle;
    }
    
    enhanceExistingToggle(button) {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            this.cycleTheme();
        });
        
        // Update button content
        this.updateToggleButton(button);
        
        this.quickToggle = button;
    }
    
    updateToggleButton(button = this.quickToggle) {
        if (!button) return;
        
        const icon = this.getThemeIcon(this.currentTheme);
        const label = this.themes[this.currentTheme].label;
        
        // Update icon if button only contains icon
        if (button.textContent.length <= 2) {
            button.innerHTML = icon;
        }
        
        button.title = `Current theme: ${label}`;
    }
    
    getThemeIcon(themeName) {
        return this.themes[themeName]?.icon || '🎨';
    }
    
    createThemeSelector() {
        const selector = document.createElement('div');
        selector.id = 'theme-selector-modal';
        selector.className = `
            fixed inset-0 z-50 hidden items-center justify-center
            bg-black/50 backdrop-blur-sm
        `.replace(/\s+/g, ' ');
        
        selector.innerHTML = `
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-700 p-6 max-w-sm w-full mx-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Choose Theme</h3>
                    <button data-close class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="space-y-2" data-theme-options>
                    ${Object.entries(this.themes).map(([key, theme]) => `
                        <button 
                            data-theme="${key}"
                            class="theme-option w-full p-3 rounded-lg border text-left transition-all duration-200
                                   flex items-center gap-3 hover:bg-slate-50 dark:hover:bg-slate-700
                                   ${this.currentTheme === key ? 'active border-emerald-500 bg-emerald-50 dark:bg-emerald-950' : 'border-slate-200 dark:border-slate-600'}"
                        >
                            <span class="text-xl">${theme.icon}</span>
                            <div class="flex-1">
                                <div class="font-medium text-slate-900 dark:text-slate-100">${theme.label}</div>
                                ${theme.followsSystem ? '<div class="text-sm text-slate-500 dark:text-slate-400">Follows system preference</div>' : ''}
                            </div>
                            ${this.currentTheme === key ? '<svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>' : ''}
                        </button>
                    `).join('')}
                </div>
                <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Tip: Press Ctrl+Shift+T to cycle themes quickly
                    </p>
                </div>
            </div>
        `;
        
        // Event listeners
        selector.addEventListener('click', (e) => {
            if (e.target === selector || e.target.closest('[data-close]')) {
                this.hideThemeSelector();
            }
            
            const themeButton = e.target.closest('[data-theme]');
            if (themeButton) {
                const theme = themeButton.dataset.theme;
                this.setTheme(theme);
                this.hideThemeSelector();
            }
        });
        
        document.body.appendChild(selector);
        this.themeSelector = selector;
    }
    
    showThemeSelector() {
        if (!this.themeSelector) this.createThemeSelector();
        
        this.themeSelector.classList.remove('hidden');
        this.themeSelector.classList.add('flex');
        
        // Update active state
        this.updateSelectorActiveState();
        
        // Focus first option
        const firstOption = this.themeSelector.querySelector('[data-theme]');
        if (firstOption) firstOption.focus();
        
        // Add escape key listener
        this.escapeListener = (e) => {
            if (e.key === 'Escape') {
                this.hideThemeSelector();
            }
        };
        document.addEventListener('keydown', this.escapeListener);
    }
    
    hideThemeSelector() {
        if (!this.themeSelector) return;
        
        this.themeSelector.classList.add('hidden');
        this.themeSelector.classList.remove('flex');
        
        // Remove escape listener
        if (this.escapeListener) {
            document.removeEventListener('keydown', this.escapeListener);
            this.escapeListener = null;
        }
    }
    
    updateSelectorActiveState() {
        if (!this.themeSelector) return;
        
        const options = this.themeSelector.querySelectorAll('[data-theme]');
        options.forEach(option => {
            const isActive = option.dataset.theme === this.currentTheme;
            option.classList.toggle('active', isActive);
            option.classList.toggle('border-emerald-500', isActive);
            option.classList.toggle('bg-emerald-50', isActive);
            option.classList.toggle('dark:bg-emerald-950', isActive);
            option.classList.toggle('border-slate-200', !isActive);
            option.classList.toggle('dark:border-slate-600', !isActive);
            
            // Update checkmark
            const checkmark = option.querySelector('svg');
            if (checkmark) {
                checkmark.style.display = isActive ? 'block' : 'none';
            }
        });
    }
    
    setTheme(themeName) {
        if (!this.themes[themeName]) {
            console.warn(`Unknown theme: ${themeName}`);
            return;
        }
        
        this.currentTheme = themeName;
        this.applyTheme(themeName);
        this.updateToggleButton();
        this.updateSelectorActiveState();
    }
    
    cycleTheme() {
        const themeKeys = Object.keys(this.themes);
        const currentIndex = themeKeys.indexOf(this.currentTheme);
        const nextIndex = (currentIndex + 1) % themeKeys.length;
        const nextTheme = themeKeys[nextIndex];
        
        this.setTheme(nextTheme);
        
        // Show brief notification
        const notificationModule = this.core.getModule('Notification');
        if (notificationModule) {
            notificationModule.show(`Theme changed to ${this.themes[nextTheme].label}`, {
                type: 'info',
                duration: 2000
            });
        }
    }
    
    getTheme() {
        return this.currentTheme;
    }
    
    getResolvedTheme() {
        return this.resolveTheme(this.currentTheme);
    }
    
    getThemeConfig(themeName = this.currentTheme) {
        return this.themes[themeName];
    }
    
    isDark() {
        return this.resolveTheme(this.currentTheme) === 'dark';
    }
    
    isLight() {
        return this.resolveTheme(this.currentTheme) === 'light';
    }
    
    addThemeObserver(callback) {
        this.observers.add(callback);
        return () => this.observers.delete(callback);
    }
    
    notifyObservers() {
        const themeData = {
            theme: this.currentTheme,
            resolved: this.resolveTheme(this.currentTheme),
            config: this.themes[this.currentTheme],
            isDark: this.isDark(),
            isLight: this.isLight()
        };
        
        this.observers.forEach(callback => {
            try {
                callback(themeData);
            } catch (error) {
                console.error('Theme observer error:', error);
            }
        });
    }
    
    destroy() {
        // Clean up event listeners
        if (this.mediaQuery) {
            this.mediaQuery.removeEventListener('change', this.systemPrefersDark);
        }
        
        // Remove theme selector
        if (this.themeSelector) {
            this.themeSelector.remove();
        }
        
        // Remove quick toggle if we created it
        if (this.quickToggle && !this.quickToggle.hasAttribute('data-existing')) {
            this.quickToggle.remove();
        }
        
        // Clear observers
        this.observers.clear();
        
        this.initialized = false;
        this.core.log('Theme module destroyed');
    }
}

// Auto-register with core if available
if (window.SportAcademy) {
    window.SportAcademy.registerModule(ThemeModule);
} else {
    // Queue for when core becomes available
    window.addEventListener('SportAcademyCoreReady', () => {
        window.SportAcademy.registerModule(ThemeModule);
    });
}

export default ThemeModule;