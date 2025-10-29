/**
 * MODAL & NOTIFICATION MODULE
 * Advanced modal dialogs, notifications, and alert systems
 */

class ModalModule {
    constructor(core) {
        this.core = core;
        this.activeModals = new Map();
        this.defaultOptions = {
            backdrop: true,
            keyboard: true,
            size: 'medium',
            animation: 'fadeIn',
            autoClose: false
        };
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.createModalContainer();
        this.setupKeyboardHandlers();
        
        console.log('🪟 Modal Module Initialized');
    }

    setupEventListeners() {
        this.core.on('domReady', () => this.onDomReady());
        this.core.on('escapePressed', () => this.handleEscapeKey());
        
        // Listen for modal triggers
        document.addEventListener('click', (e) => {
            const trigger = e.target.closest('[data-modal-trigger]');
            if (trigger) {
                e.preventDefault();
                this.handleModalTrigger(trigger);
            }
            
            const close = e.target.closest('[data-modal-close]');
            if (close) {
                e.preventDefault();
                this.handleModalClose(close);
            }
        });
    }

    onDomReady() {
        this.initializeExistingModals();
    }

    /**
     * Create modal container
     */
    createModalContainer() {
        if (document.getElementById('modal-container')) return;
        
        const container = document.createElement('div');
        container.id = 'modal-container';
        container.className = 'modal-container';
        container.setAttribute('aria-hidden', 'true');
        
        document.body.appendChild(container);
        this.container = container;
    }

    /**
     * Show modal
     */
    show(options = {}) {
        const config = { ...this.defaultOptions, ...options };
        const modalId = config.id || this.core.utils.generateId('modal-');
        
        if (this.activeModals.has(modalId)) {
            console.warn(`Modal '${modalId}' is already active`);
            return;
        }
        
        const modal = this.createModal(modalId, config);
        this.activeModals.set(modalId, modal);
        
        this.container.appendChild(modal.element);
        this.container.setAttribute('aria-hidden', 'false');
        
        // Show with animation
        requestAnimationFrame(() => {
            this.animateModalIn(modal, config.animation);
        });
        
        // Auto close if specified
        if (config.autoClose) {
            setTimeout(() => this.hide(modalId), config.autoClose);
        }
        
        // Focus management
        this.manageFocus(modal.element);
        
        // Emit events
        this.core.emit('modalOpen', { modal: modal.element, id: modalId });
        
        return modalId;
    }

    /**
     * Hide modal
     */
    hide(modalId) {
        const modal = this.activeModals.get(modalId);
        if (!modal) return;
        
        this.animateModalOut(modal, () => {
            modal.element.remove();
            this.activeModals.delete(modalId);
            
            if (this.activeModals.size === 0) {
                this.container.setAttribute('aria-hidden', 'true');
            }
            
            this.core.emit('modalClose', { id: modalId });
        });
    }

    /**
     * Create modal element
     */
    createModal(id, config) {
        const overlay = document.createElement('div');
        overlay.className = 'modal-overlay';
        overlay.setAttribute('role', 'dialog');
        overlay.setAttribute('aria-modal', 'true');
        overlay.setAttribute('aria-labelledby', `${id}-title`);
        
        if (config.backdrop) {
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) {
                    this.hide(id);
                }
            });
        }
        
        const dialog = document.createElement('div');
        dialog.className = `modal-dialog modal-${config.size}`;
        
        const content = document.createElement('div');
        content.className = 'modal-content';
        
        // Header
        const header = this.createModalHeader(id, config);
        content.appendChild(header);
        
        // Body
        const body = document.createElement('div');
        body.className = 'modal-body';
        body.innerHTML = config.content || '';
        content.appendChild(body);
        
        // Footer
        if (config.footer !== false) {
            const footer = this.createModalFooter(id, config);
            content.appendChild(footer);
        }
        
        dialog.appendChild(content);
        overlay.appendChild(dialog);
        
        return {
            element: overlay,
            dialog,
            content,
            header,
            body,
            footer: config.footer !== false ? content.querySelector('.modal-footer') : null
        };
    }

    /**
     * Create modal header
     */
    createModalHeader(id, config) {
        const header = document.createElement('div');
        header.className = 'modal-header';
        
        const title = document.createElement('h3');
        title.id = `${id}-title`;
        title.className = 'modal-title';
        title.textContent = config.title || 'Modal';
        
        const closeButton = document.createElement('button');
        closeButton.type = 'button';
        closeButton.className = 'modal-close-button';
        closeButton.setAttribute('data-modal-close', id);
        closeButton.setAttribute('aria-label', 'Close modal');
        closeButton.innerHTML = `
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        `;
        
        header.appendChild(title);
        header.appendChild(closeButton);
        
        return header;
    }

    /**
     * Create modal footer
     */
    createModalFooter(id, config) {
        const footer = document.createElement('div');
        footer.className = 'modal-footer';
        
        if (config.buttons) {
            config.buttons.forEach(buttonConfig => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = `btn ${buttonConfig.class || 'btn-secondary'}`;
                button.textContent = buttonConfig.text;
                
                if (buttonConfig.action) {
                    button.addEventListener('click', () => {
                        const result = buttonConfig.action();
                        if (result !== false) {
                            this.hide(id);
                        }
                    });
                }
                
                footer.appendChild(button);
            });
        } else {
            // Default buttons
            const closeButton = document.createElement('button');
            closeButton.type = 'button';
            closeButton.className = 'btn btn-secondary';
            closeButton.textContent = 'Close';
            closeButton.setAttribute('data-modal-close', id);
            
            footer.appendChild(closeButton);
        }
        
        return footer;
    }

    /**
     * Animate modal in
     */
    animateModalIn(modal, animation = 'fadeIn') {
        const overlay = modal.element;
        const dialog = modal.dialog;
        
        overlay.style.opacity = '0';
        overlay.style.visibility = 'visible';
        
        switch (animation) {
            case 'slideDown':
                dialog.style.transform = 'translateY(-100px) scale(0.9)';
                break;
            case 'slideUp':
                dialog.style.transform = 'translateY(100px) scale(0.9)';
                break;
            case 'scaleIn':
                dialog.style.transform = 'scale(0.8)';
                break;
            default:
                dialog.style.transform = 'scale(0.95)';
        }
        
        overlay.style.transition = 'opacity 0.3s ease';
        dialog.style.transition = 'transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1)';
        
        requestAnimationFrame(() => {
            overlay.style.opacity = '1';
            dialog.style.transform = 'translateY(0) scale(1)';
        });
    }

    /**
     * Animate modal out
     */
    animateModalOut(modal, callback) {
        const overlay = modal.element;
        const dialog = modal.dialog;
        
        overlay.style.transition = 'opacity 0.2s ease';
        dialog.style.transition = 'transform 0.2s ease';
        
        overlay.style.opacity = '0';
        dialog.style.transform = 'scale(0.95)';
        
        setTimeout(callback, 200);
    }

    /**
     * Manage focus
     */
    manageFocus(modal) {
        const focusableElements = modal.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        if (focusableElements.length > 0) {
            focusableElements[0].focus();
        }
        
        // Trap focus within modal
        modal.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                this.trapFocus(e, focusableElements);
            }
        });
    }

    /**
     * Trap focus within modal
     */
    trapFocus(e, focusableElements) {
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];
        
        if (e.shiftKey) {
            if (document.activeElement === firstElement) {
                e.preventDefault();
                lastElement.focus();
            }
        } else {
            if (document.activeElement === lastElement) {
                e.preventDefault();
                firstElement.focus();
            }
        }
    }

    /**
     * Handle modal trigger
     */
    handleModalTrigger(trigger) {
        const target = trigger.getAttribute('data-modal-trigger');
        const size = trigger.getAttribute('data-modal-size') || 'medium';
        const title = trigger.getAttribute('data-modal-title') || 'Modal';
        
        if (target.startsWith('#')) {
            // Show existing modal
            const existingModal = document.querySelector(target);
            if (existingModal) {
                this.showExistingModal(existingModal);
            }
        } else {
            // Create new modal
            this.show({
                title,
                size,
                content: this.getModalContent(target)
            });
        }
    }

    /**
     * Handle modal close
     */
    handleModalClose(closeButton) {
        const modalId = closeButton.getAttribute('data-modal-close');
        if (modalId) {
            this.hide(modalId);
        } else {
            // Close topmost modal
            const modals = Array.from(this.activeModals.keys());
            if (modals.length > 0) {
                this.hide(modals[modals.length - 1]);
            }
        }
    }

    /**
     * Handle escape key
     */
    handleEscapeKey() {
        const modals = Array.from(this.activeModals.keys());
        if (modals.length > 0) {
            this.hide(modals[modals.length - 1]);
        }
    }

    /**
     * Setup keyboard handlers
     */
    setupKeyboardHandlers() {
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.activeModals.size > 0) {
                e.preventDefault();
                this.handleEscapeKey();
            }
        });
    }

    /**
     * Initialize existing modals
     */
    initializeExistingModals() {
        const modals = document.querySelectorAll('.modal[id]');
        modals.forEach(modal => {
            this.setupExistingModal(modal);
        });
    }

    /**
     * Get modal content
     */
    getModalContent(contentType) {
        switch (contentType) {
            case 'confirmation':
                return `
                    <div class="confirmation-modal">
                        <div class="confirmation-icon">
                            <svg class="w-12 h-12 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <p class="confirmation-message">Are you sure you want to continue?</p>
                    </div>
                `;
            default:
                return '<p>Modal content goes here.</p>';
        }
    }

    /**
     * Show confirmation dialog
     */
    confirm(message, options = {}) {
        return new Promise((resolve) => {
            const modalId = this.show({
                title: options.title || 'Confirm Action',
                content: `
                    <div class="confirmation-modal">
                        <div class="confirmation-icon">
                            <svg class="w-12 h-12 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <p class="confirmation-message">${message}</p>
                    </div>
                `,
                buttons: [
                    {
                        text: options.cancelText || 'Cancel',
                        class: 'btn-secondary',
                        action: () => {
                            resolve(false);
                            return true;
                        }
                    },
                    {
                        text: options.confirmText || 'Confirm',
                        class: 'btn-primary',
                        action: () => {
                            resolve(true);
                            return true;
                        }
                    }
                ]
            });
        });
    }

    /**
     * Show alert dialog
     */
    alert(message, options = {}) {
        return new Promise((resolve) => {
            const modalId = this.show({
                title: options.title || 'Alert',
                content: `
                    <div class="alert-modal">
                        <div class="alert-icon">
                            <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="alert-message">${message}</p>
                    </div>
                `,
                buttons: [
                    {
                        text: options.okText || 'OK',
                        class: 'btn-primary',
                        action: () => {
                            resolve(true);
                            return true;
                        }
                    }
                ]
            });
        });
    }
}

/**
 * NOTIFICATION MODULE
 * Toast notifications and alert systems
 */
class NotificationModule {
    constructor(core) {
        this.core = core;
        this.notifications = new Map();
        this.container = null;
        this.defaultOptions = {
            type: 'info',
            duration: 4000,
            position: 'top-right',
            closable: true,
            pauseOnHover: true
        };
        
        this.init();
    }

    init() {
        this.createContainer();
        this.setupEventListeners();
        
        console.log('🔔 Notification Module Initialized');
    }

    setupEventListeners() {
        this.core.on('domReady', () => this.onDomReady());
    }

    onDomReady() {
        // Check for flash messages
        this.checkFlashMessages();
    }

    /**
     * Create notification container
     */
    createContainer() {
        const container = document.createElement('div');
        container.id = 'notification-container';
        container.className = 'notification-container';
        container.setAttribute('aria-live', 'polite');
        
        document.body.appendChild(container);
        this.container = container;
    }

    /**
     * Show notification
     */
    show(message, options = {}) {
        const config = { ...this.defaultOptions, ...options };
        const id = this.core.utils.generateId('notification-');
        
        const notification = this.createNotification(id, message, config);
        this.notifications.set(id, notification);
        
        this.container.appendChild(notification.element);
        
        // Animate in
        requestAnimationFrame(() => {
            notification.element.classList.add('show');
        });
        
        // Auto remove
        if (config.duration > 0) {
            notification.timeout = setTimeout(() => {
                this.hide(id);
            }, config.duration);
        }
        
        return id;
    }

    /**
     * Hide notification
     */
    hide(id) {
        const notification = this.notifications.get(id);
        if (!notification) return;
        
        if (notification.timeout) {
            clearTimeout(notification.timeout);
        }
        
        notification.element.classList.add('hide');
        
        setTimeout(() => {
            if (notification.element.parentNode) {
                notification.element.remove();
            }
            this.notifications.delete(id);
        }, 300);
    }

    /**
     * Create notification element
     */
    createNotification(id, message, config) {
        const element = document.createElement('div');
        element.className = `notification notification-${config.type}`;
        element.setAttribute('role', 'alert');
        
        const icon = this.getNotificationIcon(config.type);
        
        element.innerHTML = `
            <div class="notification-icon">${icon}</div>
            <div class="notification-content">
                <div class="notification-message">${message}</div>
                ${config.action ? `<button class="notification-action">${config.action}</button>` : ''}
            </div>
            ${config.closable ? '<button class="notification-close" aria-label="Close">&times;</button>' : ''}
        `;
        
        // Close button
        if (config.closable) {
            const closeButton = element.querySelector('.notification-close');
            closeButton.addEventListener('click', () => this.hide(id));
        }
        
        // Pause on hover
        if (config.pauseOnHover) {
            let timeout = null;
            
            element.addEventListener('mouseenter', () => {
                const notification = this.notifications.get(id);
                if (notification && notification.timeout) {
                    clearTimeout(notification.timeout);
                }
            });
            
            element.addEventListener('mouseleave', () => {
                const notification = this.notifications.get(id);
                if (notification && config.duration > 0) {
                    notification.timeout = setTimeout(() => this.hide(id), 1000);
                }
            });
        }
        
        return { element, timeout: null };
    }

    /**
     * Get notification icon
     */
    getNotificationIcon(type) {
        const icons = {
            success: `
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            `,
            error: `
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            `,
            warning: `
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            `,
            info: `
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            `
        };
        
        return icons[type] || icons.info;
    }

    /**
     * Check for flash messages
     */
    checkFlashMessages() {
        const flashMessages = document.querySelectorAll('[data-flash-message]');
        
        flashMessages.forEach(element => {
            const message = element.textContent.trim();
            const type = element.getAttribute('data-flash-type') || 'info';
            
            if (message) {
                this.show(message, { type });
                element.remove();
            }
        });
    }

    /**
     * Convenience methods
     */
    success(message, options = {}) {
        return this.show(message, { ...options, type: 'success' });
    }

    error(message, options = {}) {
        return this.show(message, { ...options, type: 'error' });
    }

    warning(message, options = {}) {
        return this.show(message, { ...options, type: 'warning' });
    }

    info(message, options = {}) {
        return this.show(message, { ...options, type: 'info' });
    }

    /**
     * Clear all notifications
     */
    clear() {
        this.notifications.forEach((notification, id) => {
            this.hide(id);
        });
    }
}

// Register the modules
if (typeof SportAcademy !== 'undefined') {
    SportAcademy.registerModule('Modal', ModalModule);
    SportAcademy.registerModule('Notification', NotificationModule);
}