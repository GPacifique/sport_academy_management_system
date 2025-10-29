/**
 * NAVIGATION & SIDEBAR MODULE
 * Enhanced navigation with smooth animations, search, and responsive interactions
 */

class NavigationModule {
    constructor(core) {
        this.core = core;
        this.sidebar = null;
        this.isCollapsed = false;
        this.isMobile = false;
        this.searchIndex = [];
        this.activeSubmenu = null;
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.checkMobileStatus();
        
        console.log('🧭 Navigation Module Initialized');
    }

    setupEventListeners() {
        this.core.on('domReady', () => this.onDomReady());
        this.core.on('windowResize', (e) => this.handleResize(e.detail));
        
        // Keyboard shortcuts
        this.core.on('searchShortcut', () => this.focusSearch());
        
        document.addEventListener('click', (e) => this.handleDocumentClick(e));
        document.addEventListener('keydown', (e) => this.handleKeyboardNavigation(e));
    }

    onDomReady() {
        this.initializeSidebar();
        this.setupNavigation();
        this.setupSearch();
        this.setupBreadcrumbs();
        this.setupQuickActions();
    }

    /**
     * Initialize sidebar
     */
    initializeSidebar() {
        this.sidebar = document.querySelector('.sidebar, [data-sidebar]');
        if (!this.sidebar) return;
        
        this.setupSidebarToggle();
        this.setupSidebarMenus();
        this.setupSidebarUserProfile();
        this.loadSidebarState();
        
        // Add modern styling
        this.addSidebarEnhancements();
    }

    /**
     * Setup sidebar toggle
     */
    setupSidebarToggle() {
        const toggleButtons = document.querySelectorAll('[data-sidebar-toggle]');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', () => this.toggleSidebar());
        });
        
        // Auto-collapse on mobile when clicking outside
        document.addEventListener('click', (e) => {
            if (this.isMobile && 
                !this.sidebar.contains(e.target) && 
                !e.target.matches('[data-sidebar-toggle]')) {
                this.collapseSidebar();
            }
        });
    }

    /**
     * Toggle sidebar
     */
    toggleSidebar() {
        this.isCollapsed = !this.isCollapsed;
        this.updateSidebarState();
        this.saveSidebarState();
    }

    /**
     * Collapse sidebar
     */
    collapseSidebar() {
        this.isCollapsed = true;
        this.updateSidebarState();
        this.saveSidebarState();
    }

    /**
     * Expand sidebar
     */
    expandSidebar() {
        this.isCollapsed = false;
        this.updateSidebarState();
        this.saveSidebarState();
    }

    /**
     * Update sidebar visual state
     */
    updateSidebarState() {
        if (!this.sidebar) return;
        
        this.sidebar.classList.toggle('collapsed', this.isCollapsed);
        
        // Update toggle button icons
        const toggleIcons = document.querySelectorAll('[data-sidebar-toggle] svg');
        toggleIcons.forEach(icon => {
            icon.style.transform = this.isCollapsed ? 'rotate(180deg)' : 'rotate(0deg)';
        });
        
        // Animate navigation items
        const navItems = this.sidebar.querySelectorAll('.nav-item');
        navItems.forEach((item, index) => {
            setTimeout(() => {
                item.style.transform = this.isCollapsed ? 'translateX(-10px)' : 'translateX(0)';
                item.style.opacity = this.isCollapsed ? '0.7' : '1';
            }, index * 50);
        });
        
        // Emit event
        this.core.emit('sidebarToggle', { collapsed: this.isCollapsed });
    }

    /**
     * Setup sidebar menus
     */
    setupSidebarMenus() {
        const menuItems = this.sidebar.querySelectorAll('[data-submenu]');
        
        menuItems.forEach(item => {
            this.setupSubmenu(item);
        });
        
        // Active state management
        this.updateActiveMenuItem();
    }

    /**
     * Setup submenu
     */
    setupSubmenu(menuItem) {
        const submenuId = menuItem.getAttribute('data-submenu');
        const submenu = document.querySelector(`[data-submenu-target="${submenuId}"]`);
        
        if (!submenu) return;
        
        const toggle = menuItem.querySelector('.submenu-toggle');
        if (toggle) {
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleSubmenu(submenuId, submenu);
            });
        }
        
        // Auto-close other submenus
        menuItem.addEventListener('click', () => {
            this.closeOtherSubmenus(submenuId);
        });
    }

    /**
     * Toggle submenu
     */
    toggleSubmenu(submenuId, submenu) {
        const isOpen = submenu.classList.contains('open');
        
        if (isOpen) {
            this.closeSubmenu(submenu);
            this.activeSubmenu = null;
        } else {
            this.openSubmenu(submenu);
            this.activeSubmenu = submenuId;
        }
    }

    /**
     * Open submenu
     */
    openSubmenu(submenu) {
        submenu.classList.add('open');
        submenu.style.maxHeight = submenu.scrollHeight + 'px';
        
        // Animate submenu items
        const items = submenu.querySelectorAll('.submenu-item');
        items.forEach((item, index) => {
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            }, index * 100);
        });
    }

    /**
     * Close submenu
     */
    closeSubmenu(submenu) {
        submenu.classList.remove('open');
        submenu.style.maxHeight = '0';
        
        const items = submenu.querySelectorAll('.submenu-item');
        items.forEach(item => {
            item.style.opacity = '0';
            item.style.transform = 'translateX(-10px)';
        });
    }

    /**
     * Close other submenus
     */
    closeOtherSubmenus(currentSubmenuId) {
        const openSubmenus = this.sidebar.querySelectorAll('[data-submenu-target].open');
        
        openSubmenus.forEach(submenu => {
            const submenuId = submenu.getAttribute('data-submenu-target');
            if (submenuId !== currentSubmenuId) {
                this.closeSubmenu(submenu);
            }
        });
    }

    /**
     * Update active menu item
     */
    updateActiveMenuItem() {
        const currentPath = window.location.pathname;
        const menuItems = this.sidebar.querySelectorAll('.nav-item[href]');
        
        menuItems.forEach(item => {
            item.classList.remove('active');
            
            const href = item.getAttribute('href');
            if (href && currentPath.includes(href)) {
                item.classList.add('active');
                
                // Open parent submenu if exists
                const parentSubmenu = item.closest('[data-submenu-target]');
                if (parentSubmenu) {
                    this.openSubmenu(parentSubmenu);
                }
            }
        });
    }

    /**
     * Setup sidebar user profile
     */
    setupSidebarUserProfile() {
        const userProfile = this.sidebar.querySelector('.user-profile, [data-user-profile]');
        if (!userProfile) return;
        
        // Add dropdown menu
        const dropdown = userProfile.querySelector('.user-dropdown');
        if (dropdown) {
            userProfile.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdown.classList.toggle('open');
            });
            
            document.addEventListener('click', () => {
                dropdown.classList.remove('open');
            });
        }
        
        // Add status indicator
        this.addUserStatusIndicator(userProfile);
    }

    /**
     * Add user status indicator
     */
    addUserStatusIndicator(userProfile) {
        const avatar = userProfile.querySelector('.user-avatar');
        if (!avatar) return;
        
        const statusIndicator = document.createElement('div');
        statusIndicator.className = 'user-status-indicator';
        statusIndicator.setAttribute('data-status', 'online');
        
        avatar.appendChild(statusIndicator);
        
        // Update status periodically
        setInterval(() => {
            this.updateUserStatus(statusIndicator);
        }, 30000);
    }

    /**
     * Update user status
     */
    updateUserStatus(indicator) {
        // This would typically check with server
        const statuses = ['online', 'away', 'busy'];
        const currentStatus = indicator.getAttribute('data-status');
        const newStatus = statuses[Math.floor(Math.random() * statuses.length)];
        
        if (currentStatus !== newStatus) {
            indicator.setAttribute('data-status', newStatus);
            indicator.style.animation = 'pulse 0.5s ease-in-out';
            setTimeout(() => {
                indicator.style.animation = '';
            }, 500);
        }
    }

    /**
     * Setup navigation search
     */
    setupSearch() {
        this.buildSearchIndex();
        this.createSearchInterface();
    }

    /**
     * Build search index
     */
    buildSearchIndex() {
        const menuItems = document.querySelectorAll('.nav-item, .submenu-item');
        
        this.searchIndex = Array.from(menuItems).map(item => ({
            element: item,
            text: item.textContent.trim().toLowerCase(),
            href: item.getAttribute('href'),
            keywords: item.getAttribute('data-keywords') || ''
        }));
    }

    /**
     * Create search interface
     */
    createSearchInterface() {
        const searchContainer = document.createElement('div');
        searchContainer.className = 'nav-search-container';
        searchContainer.innerHTML = `
            <div class="nav-search">
                <input type="text" placeholder="Search navigation..." class="nav-search-input">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <div class="nav-search-results" style="display: none;"></div>
        `;
        
        // Insert at top of sidebar navigation
        const navSection = this.sidebar.querySelector('.sidebar-nav, .nav-section');
        if (navSection) {
            navSection.insertBefore(searchContainer, navSection.firstChild);
        }
        
        const searchInput = searchContainer.querySelector('.nav-search-input');
        const searchResults = searchContainer.querySelector('.nav-search-results');
        
        // Search functionality
        searchInput.addEventListener('input', (e) => {
            this.performSearch(e.target.value, searchResults);
        });
        
        searchInput.addEventListener('keydown', (e) => {
            this.handleSearchKeyboard(e, searchResults);
        });
        
        searchInput.addEventListener('focus', () => {
            if (searchInput.value) {
                searchResults.style.display = 'block';
            }
        });
        
        searchInput.addEventListener('blur', () => {
            setTimeout(() => {
                searchResults.style.display = 'none';
            }, 150);
        });
    }

    /**
     * Perform navigation search
     */
    performSearch(query, resultsContainer) {
        if (!query.trim()) {
            resultsContainer.style.display = 'none';
            return;
        }
        
        const results = this.searchIndex.filter(item => 
            item.text.includes(query.toLowerCase()) ||
            item.keywords.toLowerCase().includes(query.toLowerCase())
        );
        
        this.renderSearchResults(results, resultsContainer, query);
    }

    /**
     * Render search results
     */
    renderSearchResults(results, container, query) {
        if (results.length === 0) {
            container.innerHTML = '<div class="no-results">No results found</div>';
            container.style.display = 'block';
            return;
        }
        
        container.innerHTML = '';
        
        results.slice(0, 8).forEach((result, index) => {
            const resultItem = document.createElement('div');
            resultItem.className = 'search-result-item';
            if (index === 0) resultItem.classList.add('active');
            
            const highlightedText = this.highlightSearchTerm(result.text, query);
            resultItem.innerHTML = `
                <span class="result-text">${highlightedText}</span>
                ${result.href ? `<span class="result-path">${result.href}</span>` : ''}
            `;
            
            resultItem.addEventListener('click', () => {
                if (result.href) {
                    window.location.href = result.href;
                } else {
                    result.element.click();
                }
            });
            
            container.appendChild(resultItem);
        });
        
        container.style.display = 'block';
    }

    /**
     * Highlight search term
     */
    highlightSearchTerm(text, term) {
        if (!term) return text;
        
        const regex = new RegExp(`(${term})`, 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    }

    /**
     * Handle search keyboard navigation
     */
    handleSearchKeyboard(e, resultsContainer) {
        const items = resultsContainer.querySelectorAll('.search-result-item');
        const activeItem = resultsContainer.querySelector('.search-result-item.active');
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (activeItem && activeItem.nextElementSibling) {
                activeItem.classList.remove('active');
                activeItem.nextElementSibling.classList.add('active');
            } else if (items.length > 0) {
                if (activeItem) activeItem.classList.remove('active');
                items[0].classList.add('active');
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (activeItem && activeItem.previousElementSibling) {
                activeItem.classList.remove('active');
                activeItem.previousElementSibling.classList.add('active');
            } else if (items.length > 0) {
                if (activeItem) activeItem.classList.remove('active');
                items[items.length - 1].classList.add('active');
            }
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (activeItem) {
                activeItem.click();
            }
        }
    }

    /**
     * Focus search
     */
    focusSearch() {
        const searchInput = this.sidebar.querySelector('.nav-search-input');
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
        }
    }

    /**
     * Setup breadcrumbs
     */
    setupBreadcrumbs() {
        const breadcrumbContainer = document.querySelector('.breadcrumb, [data-breadcrumb]');
        if (!breadcrumbContainer) return;
        
        this.generateBreadcrumbs(breadcrumbContainer);
        this.addBreadcrumbInteractions(breadcrumbContainer);
    }

    /**
     * Generate breadcrumbs
     */
    generateBreadcrumbs(container) {
        const path = window.location.pathname;
        const segments = path.split('/').filter(segment => segment);
        
        if (segments.length === 0) return;
        
        container.innerHTML = '';
        
        // Add home link
        const homeLink = this.createBreadcrumbItem('Home', '/');
        container.appendChild(homeLink);
        
        // Add path segments
        let currentPath = '';
        segments.forEach((segment, index) => {
            currentPath += '/' + segment;
            
            const breadcrumbItem = this.createBreadcrumbItem(
                this.formatBreadcrumbText(segment),
                currentPath,
                index === segments.length - 1
            );
            
            container.appendChild(breadcrumbItem);
        });
    }

    /**
     * Create breadcrumb item
     */
    createBreadcrumbItem(text, href, isLast = false) {
        const item = document.createElement('div');
        item.className = 'breadcrumb-item';
        
        if (isLast) {
            item.innerHTML = `<span class="breadcrumb-current">${text}</span>`;
        } else {
            item.innerHTML = `
                <a href="${href}" class="breadcrumb-link">${text}</a>
                <svg class="breadcrumb-separator" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            `;
        }
        
        return item;
    }

    /**
     * Format breadcrumb text
     */
    formatBreadcrumbText(segment) {
        return segment
            .replace(/-/g, ' ')
            .replace(/_/g, ' ')
            .split(' ')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');
    }

    /**
     * Add breadcrumb interactions
     */
    addBreadcrumbInteractions(container) {
        const links = container.querySelectorAll('.breadcrumb-link');
        
        links.forEach(link => {
            link.addEventListener('mouseenter', () => {
                link.style.transform = 'translateY(-1px)';
            });
            
            link.addEventListener('mouseleave', () => {
                link.style.transform = 'translateY(0)';
            });
        });
    }

    /**
     * Setup quick actions
     */
    setupQuickActions() {
        this.createQuickActionsFab();
        this.setupQuickActionsMenu();
    }

    /**
     * Create quick actions floating action button
     */
    createQuickActionsFab() {
        const fab = document.createElement('div');
        fab.className = 'quick-actions-fab';
        fab.innerHTML = `
            <button class="fab-button" data-quick-actions-toggle>
                <svg class="fab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </button>
            <div class="quick-actions-menu" style="display: none;">
                <div class="quick-action-item" data-action="new-student">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>New Student</span>
                </div>
                <div class="quick-action-item" data-action="new-session">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>New Session</span>
                </div>
                <div class="quick-action-item" data-action="quick-attendance">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span>Quick Attendance</span>
                </div>
            </div>
        `;
        
        document.body.appendChild(fab);
        
        const button = fab.querySelector('.fab-button');
        const menu = fab.querySelector('.quick-actions-menu');
        
        button.addEventListener('click', () => {
            const isOpen = menu.style.display !== 'none';
            
            if (isOpen) {
                this.closeQuickActionsMenu(menu, button);
            } else {
                this.openQuickActionsMenu(menu, button);
            }
        });
        
        // Close on outside click
        document.addEventListener('click', (e) => {
            if (!fab.contains(e.target)) {
                this.closeQuickActionsMenu(menu, button);
            }
        });
    }

    /**
     * Open quick actions menu
     */
    openQuickActionsMenu(menu, button) {
        menu.style.display = 'block';
        button.style.transform = 'rotate(45deg)';
        
        const items = menu.querySelectorAll('.quick-action-item');
        items.forEach((item, index) => {
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0) scale(1)';
            }, index * 100);
        });
    }

    /**
     * Close quick actions menu
     */
    closeQuickActionsMenu(menu, button) {
        button.style.transform = 'rotate(0deg)';
        
        const items = menu.querySelectorAll('.quick-action-item');
        items.forEach(item => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(10px) scale(0.9)';
        });
        
        setTimeout(() => {
            menu.style.display = 'none';
        }, 200);
    }

    /**
     * Setup quick actions menu
     */
    setupQuickActionsMenu() {
        document.addEventListener('click', (e) => {
            const actionItem = e.target.closest('[data-action]');
            if (!actionItem) return;
            
            const action = actionItem.getAttribute('data-action');
            this.handleQuickAction(action);
        });
    }

    /**
     * Handle quick action
     */
    handleQuickAction(action) {
        const actions = {
            'new-student': () => {
                window.location.href = '/admin/students/create';
            },
            'new-session': () => {
                window.location.href = '/admin/sessions/create';
            },
            'quick-attendance': () => {
                window.location.href = '/coach/attendance';
            }
        };
        
        const handler = actions[action];
        if (handler) {
            handler();
        }
        
        this.core.emit('quickAction', { action });
    }

    /**
     * Handle resize
     */
    handleResize(dimensions) {
        const wasMobile = this.isMobile;
        this.isMobile = dimensions.width < 768;
        
        if (wasMobile !== this.isMobile) {
            this.updateSidebarForMobile();
        }
    }

    /**
     * Update sidebar for mobile
     */
    updateSidebarForMobile() {
        if (this.isMobile) {
            this.sidebar.classList.add('mobile');
            this.collapseSidebar();
        } else {
            this.sidebar.classList.remove('mobile');
            this.loadSidebarState();
        }
    }

    /**
     * Check mobile status
     */
    checkMobileStatus() {
        this.isMobile = window.innerWidth < 768;
        if (this.isMobile) {
            this.isCollapsed = true;
        }
    }

    /**
     * Handle document click
     */
    handleDocumentClick(e) {
        // Close dropdowns
        const openDropdowns = document.querySelectorAll('.dropdown.open');
        openDropdowns.forEach(dropdown => {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
            }
        });
    }

    /**
     * Handle keyboard navigation
     */
    handleKeyboardNavigation(e) {
        // Alt + S for sidebar toggle
        if (e.altKey && e.key === 's') {
            e.preventDefault();
            this.toggleSidebar();
        }
        
        // Alt + Q for quick actions
        if (e.altKey && e.key === 'q') {
            e.preventDefault();
            const fabButton = document.querySelector('.fab-button');
            if (fabButton) {
                fabButton.click();
            }
        }
    }

    /**
     * Add sidebar enhancements
     */
    addSidebarEnhancements() {
        if (!this.sidebar) return;
        
        this.sidebar.classList.add('enhanced-sidebar');
        
        // Add hover effects to nav items
        const navItems = this.sidebar.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.addEventListener('mouseenter', () => {
                if (!this.isCollapsed) {
                    item.style.transform = 'translateX(4px)';
                }
            });
            
            item.addEventListener('mouseleave', () => {
                item.style.transform = 'translateX(0)';
            });
        });
        
        // Add ripple effect to clickable items
        navItems.forEach(item => {
            item.addEventListener('click', (e) => {
                this.createNavRipple(e, item);
            });
        });
    }

    /**
     * Create navigation ripple effect
     */
    createNavRipple(event, element) {
        const rect = element.getBoundingClientRect();
        const ripple = document.createElement('span');
        const size = Math.max(rect.width, rect.height);
        
        ripple.style.cssText = `
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            transform: scale(0);
            animation: nav-ripple 0.6s linear;
            pointer-events: none;
            width: ${size}px;
            height: ${size}px;
            left: ${event.clientX - rect.left - size / 2}px;
            top: ${event.clientY - rect.top - size / 2}px;
        `;
        
        element.style.position = 'relative';
        element.style.overflow = 'hidden';
        element.appendChild(ripple);
        
        setTimeout(() => ripple.remove(), 600);
    }

    /**
     * Save sidebar state
     */
    saveSidebarState() {
        localStorage.setItem('sidebar-collapsed', this.isCollapsed);
    }

    /**
     * Load sidebar state
     */
    loadSidebarState() {
        if (this.isMobile) return;
        
        const saved = localStorage.getItem('sidebar-collapsed');
        if (saved !== null) {
            this.isCollapsed = saved === 'true';
            this.updateSidebarState();
        }
    }
}

// Register the module
if (typeof SportAcademy !== 'undefined') {
    SportAcademy.registerModule('Navigation', NavigationModule);
}