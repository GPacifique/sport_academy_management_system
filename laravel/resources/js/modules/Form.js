/**
 * FORM ENHANCEMENT MODULE
 * Advanced form validation, auto-complete, dynamic fields, and interactive elements
 */

class FormModule {
    constructor(core) {
        this.core = core;
        this.forms = new Map();
        this.validators = new Map();
        this.autoCompleteInstances = new Map();
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.registerDefaultValidators();
        
        console.log('📝 Form Module Initialized');
    }

    setupEventListeners() {
        this.core.on('domReady', () => this.onDomReady());
        
        // Delegate form events
        document.addEventListener('submit', (e) => this.handleFormSubmit(e));
        document.addEventListener('input', (e) => this.handleInputChange(e));
        document.addEventListener('focus', (e) => this.handleInputFocus(e), true);
        document.addEventListener('blur', (e) => this.handleInputBlur(e), true);
    }

    onDomReady() {
        this.initializeForms();
        this.setupFileUploads();
        this.setupDynamicFields();
        this.setupAutoComplete();
        this.setupPasswordToggles();
    }

    /**
     * Initialize enhanced forms
     */
    initializeForms() {
        const forms = document.querySelectorAll('[data-form-enhanced]');
        
        forms.forEach(form => {
            this.enhanceForm(form);
        });
    }

    /**
     * Enhance a form with advanced features
     */
    enhanceForm(form) {
        const formId = form.id || this.core.utils.generateId('form-');
        form.id = formId;
        
        const formData = {
            element: form,
            fields: new Map(),
            errors: new Map(),
            isValid: true,
            isSubmitting: false
        };
        
        this.forms.set(formId, formData);
        
        // Initialize form fields
        this.initializeFormFields(form, formData);
        
        // Add form enhancements
        this.addFormValidation(form, formData);
        this.addFormStyling(form);
        
        console.log(`✅ Enhanced form: ${formId}`);
    }

    /**
     * Initialize form fields
     */
    initializeFormFields(form, formData) {
        const fields = form.querySelectorAll('input, select, textarea');
        
        fields.forEach(field => {
            const fieldData = {
                element: field,
                type: field.type || 'text',
                required: field.hasAttribute('required'),
                rules: this.parseValidationRules(field),
                errors: []
            };
            
            formData.fields.set(field.name || field.id, fieldData);
            
            // Add field enhancements
            this.enhanceField(field, fieldData);
        });
    }

    /**
     * Parse validation rules from data attributes
     */
    parseValidationRules(field) {
        const rules = [];
        
        // Required
        if (field.hasAttribute('required')) {
            rules.push({ type: 'required', message: 'This field is required' });
        }
        
        // Min length
        const minLength = field.getAttribute('data-min-length');
        if (minLength) {
            rules.push({ 
                type: 'minLength', 
                value: parseInt(minLength),
                message: `Must be at least ${minLength} characters`
            });
        }
        
        // Max length
        const maxLength = field.getAttribute('data-max-length') || field.getAttribute('maxlength');
        if (maxLength) {
            rules.push({ 
                type: 'maxLength', 
                value: parseInt(maxLength),
                message: `Must be no more than ${maxLength} characters`
            });
        }
        
        // Email
        if (field.type === 'email' || field.getAttribute('data-validate') === 'email') {
            rules.push({ 
                type: 'email', 
                message: 'Please enter a valid email address'
            });
        }
        
        // Pattern
        const pattern = field.getAttribute('pattern') || field.getAttribute('data-pattern');
        if (pattern) {
            rules.push({ 
                type: 'pattern', 
                value: new RegExp(pattern),
                message: field.getAttribute('data-pattern-message') || 'Invalid format'
            });
        }
        
        // Custom validation
        const customValidator = field.getAttribute('data-validator');
        if (customValidator && this.validators.has(customValidator)) {
            rules.push({ 
                type: 'custom', 
                validator: customValidator,
                message: field.getAttribute('data-validator-message') || 'Invalid value'
            });
        }
        
        return rules;
    }

    /**
     * Enhance individual field
     */
    enhanceField(field, fieldData) {
        // Add floating label effect
        if (field.getAttribute('data-floating-label') === 'true') {
            this.addFloatingLabel(field);
        }
        
        // Add character counter
        if (field.hasAttribute('maxlength') || field.getAttribute('data-max-length')) {
            this.addCharacterCounter(field);
        }
        
        // Add input mask
        const mask = field.getAttribute('data-mask');
        if (mask) {
            this.addInputMask(field, mask);
        }
        
        // Add real-time validation
        if (field.getAttribute('data-validate-realtime') === 'true') {
            this.addRealTimeValidation(field, fieldData);
        }
    }

    /**
     * Add floating label effect
     */
    addFloatingLabel(field) {
        const wrapper = document.createElement('div');
        wrapper.className = 'floating-label-wrapper';
        
        const label = field.labels[0];
        if (!label) return;
        
        field.parentNode.insertBefore(wrapper, field);
        wrapper.appendChild(field);
        wrapper.appendChild(label);
        
        label.className = 'floating-label';
        
        const updateLabel = () => {
            if (field.value || field === document.activeElement) {
                label.classList.add('active');
            } else {
                label.classList.remove('active');
            }
        };
        
        field.addEventListener('focus', updateLabel);
        field.addEventListener('blur', updateLabel);
        field.addEventListener('input', updateLabel);
        
        updateLabel();
    }

    /**
     * Add character counter
     */
    addCharacterCounter(field) {
        const maxLength = parseInt(field.getAttribute('maxlength') || field.getAttribute('data-max-length'));
        
        const counter = document.createElement('div');
        counter.className = 'character-counter';
        counter.textContent = `0 / ${maxLength}`;
        
        field.parentNode.appendChild(counter);
        
        const updateCounter = () => {
            const currentLength = field.value.length;
            counter.textContent = `${currentLength} / ${maxLength}`;
            
            if (currentLength > maxLength * 0.9) {
                counter.classList.add('warning');
            } else {
                counter.classList.remove('warning');
            }
            
            if (currentLength > maxLength) {
                counter.classList.add('error');
            } else {
                counter.classList.remove('error');
            }
        };
        
        field.addEventListener('input', updateCounter);
        updateCounter();
    }

    /**
     * Add input mask
     */
    addInputMask(field, mask) {
        let isUpdating = false;
        
        const applyMask = (value) => {
            if (isUpdating) return value;
            
            let maskedValue = '';
            let valueIndex = 0;
            
            for (let i = 0; i < mask.length && valueIndex < value.length; i++) {
                const maskChar = mask[i];
                const valueChar = value[valueIndex];
                
                if (maskChar === '9') {
                    if (/\d/.test(valueChar)) {
                        maskedValue += valueChar;
                        valueIndex++;
                    } else {
                        valueIndex++;
                        i--;
                    }
                } else if (maskChar === 'A') {
                    if (/[a-zA-Z]/.test(valueChar)) {
                        maskedValue += valueChar.toUpperCase();
                        valueIndex++;
                    } else {
                        valueIndex++;
                        i--;
                    }
                } else if (maskChar === 'a') {
                    if (/[a-zA-Z]/.test(valueChar)) {
                        maskedValue += valueChar.toLowerCase();
                        valueIndex++;
                    } else {
                        valueIndex++;
                        i--;
                    }
                } else {
                    maskedValue += maskChar;
                }
            }
            
            return maskedValue;
        };
        
        field.addEventListener('input', (e) => {
            const cursorPos = field.selectionStart;
            const oldValue = field.value;
            const newValue = applyMask(oldValue.replace(/[^\w]/g, ''));
            
            if (newValue !== oldValue) {
                isUpdating = true;
                field.value = newValue;
                isUpdating = false;
                
                // Restore cursor position
                const newCursorPos = Math.min(cursorPos, newValue.length);
                field.setSelectionRange(newCursorPos, newCursorPos);
            }
        });
    }

    /**
     * Add real-time validation
     */
    addRealTimeValidation(field, fieldData) {
        const validateField = this.core.utils.debounce(() => {
            this.validateField(field, fieldData);
        }, 300);
        
        field.addEventListener('input', validateField);
        field.addEventListener('blur', () => this.validateField(field, fieldData));
    }

    /**
     * Setup file uploads with progress
     */
    setupFileUploads() {
        const fileInputs = document.querySelectorAll('input[type="file"][data-enhanced]');
        
        fileInputs.forEach(input => {
            this.enhanceFileInput(input);
        });
    }

    /**
     * Enhance file input
     */
    enhanceFileInput(input) {
        const wrapper = document.createElement('div');
        wrapper.className = 'file-upload-wrapper';
        
        const dropZone = document.createElement('div');
        dropZone.className = 'file-drop-zone';
        dropZone.innerHTML = `
            <div class="drop-zone-content">
                <svg class="upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                <p class="drop-zone-text">Drop files here or click to browse</p>
                <p class="drop-zone-hint">Supports: JPG, PNG, PDF (max 10MB)</p>
            </div>
        `;
        
        const progressBar = document.createElement('div');
        progressBar.className = 'upload-progress';
        progressBar.innerHTML = `
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
            <span class="progress-text">0%</span>
        `;
        progressBar.style.display = 'none';
        
        const fileList = document.createElement('div');
        fileList.className = 'file-list';
        
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(dropZone);
        wrapper.appendChild(progressBar);
        wrapper.appendChild(fileList);
        wrapper.appendChild(input);
        
        input.style.display = 'none';
        
        // Click to browse
        dropZone.addEventListener('click', () => input.click());
        
        // Drag and drop
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('drag-over');
        });
        
        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('drag-over');
        });
        
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
            
            const files = Array.from(e.dataTransfer.files);
            this.handleFileSelection(input, files, progressBar, fileList);
        });
        
        // File selection
        input.addEventListener('change', (e) => {
            const files = Array.from(e.target.files);
            this.handleFileSelection(input, files, progressBar, fileList);
        });
    }

    /**
     * Handle file selection
     */
    handleFileSelection(input, files, progressBar, fileList) {
        fileList.innerHTML = '';
        
        files.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';
            fileItem.innerHTML = `
                <div class="file-info">
                    <span class="file-name">${file.name}</span>
                    <span class="file-size">${this.formatFileSize(file.size)}</span>
                </div>
                <button type="button" class="file-remove" data-index="${index}">×</button>
            `;
            
            fileList.appendChild(fileItem);
        });
        
        // Remove file functionality
        fileList.addEventListener('click', (e) => {
            if (e.target.classList.contains('file-remove')) {
                const index = parseInt(e.target.getAttribute('data-index'));
                files.splice(index, 1);
                this.updateFileInput(input, files);
                this.handleFileSelection(input, files, progressBar, fileList);
            }
        });
        
        // Simulate upload progress (replace with actual upload logic)
        if (files.length > 0) {
            this.simulateUploadProgress(progressBar);
        }
    }

    /**
     * Update file input with new file list
     */
    updateFileInput(input, files) {
        const dt = new DataTransfer();
        files.forEach(file => dt.items.add(file));
        input.files = dt.files;
    }

    /**
     * Format file size
     */
    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    /**
     * Simulate upload progress
     */
    simulateUploadProgress(progressBar) {
        progressBar.style.display = 'block';
        const progressFill = progressBar.querySelector('.progress-fill');
        const progressText = progressBar.querySelector('.progress-text');
        
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);
                setTimeout(() => {
                    progressBar.style.display = 'none';
                    this.core.getModule('Notification')?.success('Files uploaded successfully');
                }, 500);
            }
            
            progressFill.style.width = progress + '%';
            progressText.textContent = Math.round(progress) + '%';
        }, 200);
    }

    /**
     * Setup dynamic fields
     */
    setupDynamicFields() {
        const dynamicContainers = document.querySelectorAll('[data-dynamic-fields]');
        
        dynamicContainers.forEach(container => {
            this.setupDynamicFieldContainer(container);
        });
    }

    /**
     * Setup dynamic field container
     */
    setupDynamicFieldContainer(container) {
        const template = container.querySelector('[data-field-template]');
        if (!template) return;
        
        const addButton = container.querySelector('[data-add-field]');
        if (addButton) {
            addButton.addEventListener('click', () => {
                this.addDynamicField(container, template);
            });
        }
        
        // Setup remove buttons for existing fields
        container.addEventListener('click', (e) => {
            if (e.target.matches('[data-remove-field]')) {
                this.removeDynamicField(e.target.closest('[data-field-item]'));
            }
        });
    }

    /**
     * Add dynamic field
     */
    addDynamicField(container, template) {
        const clone = template.cloneNode(true);
        clone.removeAttribute('data-field-template');
        clone.setAttribute('data-field-item', '');
        clone.style.display = '';
        
        // Update field names and IDs
        const fields = clone.querySelectorAll('input, select, textarea');
        const index = container.querySelectorAll('[data-field-item]').length;
        
        fields.forEach(field => {
            if (field.name) {
                field.name = field.name.replace(/\[\d*\]/, `[${index}]`);
            }
            if (field.id) {
                field.id = field.id.replace(/_\d*/, `_${index}`);
            }
        });
        
        const labels = clone.querySelectorAll('label');
        labels.forEach(label => {
            if (label.getAttribute('for')) {
                label.setAttribute('for', label.getAttribute('for').replace(/_\d*/, `_${index}`));
            }
        });
        
        container.appendChild(clone);
        
        // Animate in
        clone.style.opacity = '0';
        clone.style.transform = 'translateY(20px)';
        requestAnimationFrame(() => {
            clone.style.transition = 'all 0.3s ease';
            clone.style.opacity = '1';
            clone.style.transform = 'translateY(0)';
        });
        
        // Focus first field
        const firstField = clone.querySelector('input, select, textarea');
        if (firstField) {
            firstField.focus();
        }
    }

    /**
     * Remove dynamic field
     */
    removeDynamicField(fieldItem) {
        fieldItem.style.transition = 'all 0.3s ease';
        fieldItem.style.opacity = '0';
        fieldItem.style.transform = 'translateY(-20px)';
        
        setTimeout(() => {
            fieldItem.remove();
        }, 300);
    }

    /**
     * Setup auto-complete
     */
    setupAutoComplete() {
        const autoCompleteInputs = document.querySelectorAll('[data-autocomplete]');
        
        autoCompleteInputs.forEach(input => {
            this.setupAutoCompleteInput(input);
        });
    }

    /**
     * Setup auto-complete for input
     */
    setupAutoCompleteInput(input) {
        const dataSource = input.getAttribute('data-autocomplete');
        const minChars = parseInt(input.getAttribute('data-min-chars')) || 2;
        
        const dropdown = document.createElement('div');
        dropdown.className = 'autocomplete-dropdown';
        dropdown.style.display = 'none';
        
        input.parentNode.appendChild(dropdown);
        
        let currentRequest = null;
        
        const showSuggestions = this.core.utils.debounce(async (query) => {
            if (query.length < minChars) {
                dropdown.style.display = 'none';
                return;
            }
            
            // Cancel previous request
            if (currentRequest) {
                currentRequest.abort();
            }
            
            try {
                currentRequest = new AbortController();
                const suggestions = await this.fetchSuggestions(dataSource, query, currentRequest.signal);
                this.renderSuggestions(dropdown, suggestions, input);
            } catch (error) {
                if (error.name !== 'AbortError') {
                    console.error('Auto-complete error:', error);
                }
            }
        }, 300);
        
        input.addEventListener('input', (e) => {
            showSuggestions(e.target.value);
        });
        
        input.addEventListener('blur', () => {
            setTimeout(() => {
                dropdown.style.display = 'none';
            }, 150);
        });
        
        input.addEventListener('keydown', (e) => {
            this.handleAutoCompleteKeyboard(e, dropdown);
        });
    }

    /**
     * Fetch suggestions
     */
    async fetchSuggestions(dataSource, query, signal) {
        if (dataSource.startsWith('http')) {
            // External API
            const response = await fetch(`${dataSource}?q=${encodeURIComponent(query)}`, { signal });
            return await response.json();
        } else {
            // Static data or function
            const data = window[dataSource];
            if (typeof data === 'function') {
                return data(query);
            } else if (Array.isArray(data)) {
                return data.filter(item => 
                    item.toLowerCase().includes(query.toLowerCase())
                );
            }
        }
        
        return [];
    }

    /**
     * Render suggestions
     */
    renderSuggestions(dropdown, suggestions, input) {
        if (!suggestions || suggestions.length === 0) {
            dropdown.style.display = 'none';
            return;
        }
        
        dropdown.innerHTML = '';
        
        suggestions.forEach((suggestion, index) => {
            const item = document.createElement('div');
            item.className = 'autocomplete-item';
            item.textContent = typeof suggestion === 'string' ? suggestion : suggestion.label;
            item.setAttribute('data-value', typeof suggestion === 'string' ? suggestion : suggestion.value);
            
            if (index === 0) {
                item.classList.add('active');
            }
            
            item.addEventListener('click', () => {
                input.value = item.getAttribute('data-value');
                dropdown.style.display = 'none';
                input.dispatchEvent(new Event('change'));
            });
            
            dropdown.appendChild(item);
        });
        
        dropdown.style.display = 'block';
    }

    /**
     * Handle auto-complete keyboard navigation
     */
    handleAutoCompleteKeyboard(e, dropdown) {
        const items = dropdown.querySelectorAll('.autocomplete-item');
        const activeItem = dropdown.querySelector('.autocomplete-item.active');
        
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
        } else if (e.key === 'Escape') {
            dropdown.style.display = 'none';
        }
    }

    /**
     * Setup password toggles
     */
    setupPasswordToggles() {
        const passwordInputs = document.querySelectorAll('input[type="password"][data-toggle]');
        
        passwordInputs.forEach(input => {
            this.addPasswordToggle(input);
        });
    }

    /**
     * Add password toggle
     */
    addPasswordToggle(input) {
        const wrapper = document.createElement('div');
        wrapper.className = 'password-input-wrapper';
        
        const toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'password-toggle';
        toggle.innerHTML = `
            <svg class="eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <svg class="eye-closed" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
            </svg>
        `;
        
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);
        wrapper.appendChild(toggle);
        
        toggle.addEventListener('click', () => {
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            
            const eyeOpen = toggle.querySelector('.eye-open');
            const eyeClosed = toggle.querySelector('.eye-closed');
            
            if (isPassword) {
                eyeOpen.style.display = 'none';
                eyeClosed.style.display = 'block';
            } else {
                eyeOpen.style.display = 'block';
                eyeClosed.style.display = 'none';
            }
        });
    }

    /**
     * Register default validators
     */
    registerDefaultValidators() {
        this.validators.set('phone', (value) => {
            const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
            return phoneRegex.test(value.replace(/\s/g, ''));
        });
        
        this.validators.set('strongPassword', (value) => {
            return value.length >= 8 && 
                   /[A-Z]/.test(value) && 
                   /[a-z]/.test(value) && 
                   /\d/.test(value) && 
                   /[!@#$%^&*]/.test(value);
        });
        
        this.validators.set('url', (value) => {
            try {
                new URL(value);
                return true;
            } catch {
                return false;
            }
        });
    }

    /**
     * Validate field
     */
    validateField(field, fieldData) {
        const errors = [];
        const value = field.value.trim();
        
        for (const rule of fieldData.rules) {
            const isValid = this.runValidationRule(rule, value, field);
            if (!isValid) {
                errors.push(rule.message);
            }
        }
        
        fieldData.errors = errors;
        this.updateFieldErrors(field, errors);
        
        return errors.length === 0;
    }

    /**
     * Run validation rule
     */
    runValidationRule(rule, value, field) {
        switch (rule.type) {
            case 'required':
                return value !== '';
            case 'minLength':
                return value.length >= rule.value;
            case 'maxLength':
                return value.length <= rule.value;
            case 'email':
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
            case 'pattern':
                return rule.value.test(value);
            case 'custom':
                const validator = this.validators.get(rule.validator);
                return validator ? validator(value, field) : true;
            default:
                return true;
        }
    }

    /**
     * Update field errors
     */
    updateFieldErrors(field, errors) {
        // Remove existing error messages
        const existingErrors = field.parentNode.querySelectorAll('.field-error');
        existingErrors.forEach(error => error.remove());
        
        // Remove error styling
        field.classList.remove('error');
        
        if (errors.length > 0) {
            // Add error styling
            field.classList.add('error');
            
            // Add error messages
            errors.forEach(error => {
                const errorElement = document.createElement('div');
                errorElement.className = 'field-error';
                errorElement.textContent = error;
                field.parentNode.appendChild(errorElement);
            });
        }
    }

    /**
     * Handle form submit
     */
    handleFormSubmit(e) {
        const form = e.target;
        const formData = this.forms.get(form.id);
        
        if (!formData) return;
        
        e.preventDefault();
        
        if (formData.isSubmitting) return;
        
        // Validate all fields
        let isValid = true;
        formData.fields.forEach((fieldData, fieldName) => {
            if (!this.validateField(fieldData.element, fieldData)) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            this.core.getModule('Notification')?.error('Please fix the errors in the form');
            return;
        }
        
        // Submit form
        this.submitForm(form, formData);
    }

    /**
     * Submit form
     */
    async submitForm(form, formData) {
        formData.isSubmitting = true;
        
        // Add loading state
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.classList.add('btn-loading');
            submitButton.disabled = true;
        }
        
        try {
            const formDataObj = new FormData(form);
            
            // If form has action, submit via fetch
            if (form.action) {
                const response = await fetch(form.action, {
                    method: form.method || 'POST',
                    body: formDataObj
                });
                
                if (response.ok) {
                    this.core.getModule('Notification')?.success('Form submitted successfully');
                    form.reset();
                } else {
                    throw new Error('Form submission failed');
                }
            } else {
                // Emit custom event
                this.core.emit('formSubmit', {
                    form,
                    data: Object.fromEntries(formDataObj)
                });
            }
        } catch (error) {
            console.error('Form submission error:', error);
            this.core.getModule('Notification')?.error('Form submission failed');
        } finally {
            formData.isSubmitting = false;
            
            if (submitButton) {
                submitButton.classList.remove('btn-loading');
                submitButton.disabled = false;
            }
        }
    }

    /**
     * Handle input events
     */
    handleInputChange(e) {
        const field = e.target;
        if (!field.matches('input, select, textarea')) return;
        
        // Real-time character counter update
        const counter = field.parentNode.querySelector('.character-counter');
        if (counter) {
            const maxLength = parseInt(field.getAttribute('maxlength') || field.getAttribute('data-max-length'));
            const currentLength = field.value.length;
            counter.textContent = `${currentLength} / ${maxLength}`;
            
            counter.classList.toggle('warning', currentLength > maxLength * 0.9);
            counter.classList.toggle('error', currentLength > maxLength);
        }
    }

    /**
     * Handle input focus
     */
    handleInputFocus(e) {
        const field = e.target;
        if (!field.matches('input, select, textarea')) return;
        
        field.parentNode.classList.add('focused');
    }

    /**
     * Handle input blur
     */
    handleInputBlur(e) {
        const field = e.target;
        if (!field.matches('input, select, textarea')) return;
        
        field.parentNode.classList.remove('focused');
    }

    /**
     * Add form styling
     */
    addFormStyling(form) {
        form.classList.add('enhanced-form');
        
        // Add focus rings and transitions
        const fields = form.querySelectorAll('input, select, textarea');
        fields.forEach(field => {
            field.classList.add('enhanced-field');
        });
    }
}

// Register the module
if (typeof SportAcademy !== 'undefined') {
    SportAcademy.registerModule('Form', FormModule);
}