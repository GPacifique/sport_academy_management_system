/**
 * DASHBOARD MODULE - Interactive Data Visualization and Widgets
 * Modern dashboard components with real-time updates and animations
 */

class DashboardModule {
    constructor(core) {
        this.core = core;
        this.widgets = new Map();
        this.charts = new Map();
        this.realTimeData = new Map();
        this.updateInterval = null;
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initializeWidgets();
        this.startRealTimeUpdates();
        
        console.log('📊 Dashboard Module Initialized');
    }

    setupEventListeners() {
        this.core.on('domReady', () => this.onDomReady());
        this.core.on('windowResize', () => this.handleResize());
        this.core.on('themeChanged', (e) => this.handleThemeChange(e.detail.theme));
    }

    onDomReady() {
        this.initializeKPICards();
        this.initializeCharts();
        this.initializeDataTables();
        this.setupWidgetInteractions();
    }

    /**
     * Initialize KPI cards with count-up animations
     */
    initializeKPICards() {
        const kpiCards = document.querySelectorAll('[data-kpi]');
        
        kpiCards.forEach(card => {
            const value = card.querySelector('[data-count]');
            const icon = card.querySelector('.kpi-icon');
            const chart = card.querySelector('.kpi-chart');
            
            if (value) {
                this.animateKPIValue(value);
            }
            
            if (icon) {
                this.animateKPIIcon(icon);
            }
            
            if (chart) {
                this.createMiniChart(chart);
            }
            
            // Add hover effects
            this.addKPIHoverEffects(card);
        });
    }

    /**
     * Animate KPI values with count-up effect
     */
    animateKPIValue(element) {
        const finalValue = parseFloat(element.textContent.replace(/[^\d.-]/g, ''));
        const prefix = element.textContent.replace(/[\d.-]/g, '').trim();
        const duration = 2000;
        const steps = 60;
        const increment = finalValue / steps;
        
        let currentValue = 0;
        let step = 0;
        
        const animation = setInterval(() => {
            step++;
            currentValue += increment;
            
            const progress = step / steps;
            const easeOut = 1 - Math.pow(1 - progress, 3);
            const displayValue = Math.floor(finalValue * easeOut);
            
            element.textContent = this.formatKPIValue(displayValue, prefix);
            
            if (step >= steps) {
                clearInterval(animation);
                element.textContent = this.formatKPIValue(finalValue, prefix);
                element.setAttribute('data-animate-complete', 'true');
            }
        }, duration / steps);
    }

    /**
     * Format KPI values
     */
    formatKPIValue(value, prefix = '') {
        if (value >= 1000000) {
            return `${(value / 1000000).toFixed(1)}M ${prefix}`;
        } else if (value >= 1000) {
            return `${(value / 1000).toFixed(1)}K ${prefix}`;
        }
        return `${value} ${prefix}`;
    }

    /**
     * Animate KPI icons
     */
    animateKPIIcon(icon) {
        icon.style.transform = 'scale(0.8) rotate(-10deg)';
        icon.style.opacity = '0';
        
        setTimeout(() => {
            icon.style.transition = 'all 0.8s cubic-bezier(0.34, 1.56, 0.64, 1)';
            icon.style.transform = 'scale(1) rotate(0deg)';
            icon.style.opacity = '1';
        }, 500);
    }

    /**
     * Add hover effects to KPI cards
     */
    addKPIHoverEffects(card) {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-8px) scale(1.02)';
            card.style.boxShadow = '0 20px 40px rgba(0,0,0,0.15)';
            
            const icon = card.querySelector('.kpi-icon');
            if (icon) {
                icon.style.transform = 'scale(1.1) rotate(5deg)';
            }
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0) scale(1)';
            card.style.boxShadow = '';
            
            const icon = card.querySelector('.kpi-icon');
            if (icon) {
                icon.style.transform = 'scale(1) rotate(0deg)';
            }
        });
        
        // Add click effect
        card.addEventListener('click', () => {
            this.createRippleEffect(card, event);
            this.showKPIDetails(card);
        });
    }

    /**
     * Create ripple effect
     */
    createRippleEffect(element, event) {
        const rect = element.getBoundingClientRect();
        const ripple = document.createElement('span');
        const size = Math.max(rect.width, rect.height);
        
        ripple.style.cssText = `
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            transform: scale(0);
            animation: ripple 0.6s linear;
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
     * Show KPI details modal
     */
    showKPIDetails(card) {
        const kpiType = card.getAttribute('data-kpi');
        const modalModule = this.core.getModule('Modal');
        
        if (modalModule) {
            modalModule.show({
                title: `${kpiType} Details`,
                content: this.generateKPIDetailsContent(kpiType),
                size: 'large'
            });
        }
    }

    /**
     * Generate KPI details content
     */
    generateKPIDetailsContent(kpiType) {
        return `
            <div class="kpi-details">
                <div class="chart-container">
                    <canvas id="kpi-detail-chart-${kpiType}"></canvas>
                </div>
                <div class="kpi-metrics">
                    <div class="metric">
                        <span class="label">This Week</span>
                        <span class="value">+12%</span>
                    </div>
                    <div class="metric">
                        <span class="label">This Month</span>
                        <span class="value">+24%</span>
                    </div>
                    <div class="metric">
                        <span class="label">This Year</span>
                        <span class="value">+156%</span>
                    </div>
                </div>
            </div>
        `;
    }

    /**
     * Create mini charts for KPI cards
     */
    createMiniChart(container) {
        const canvas = document.createElement('canvas');
        canvas.width = container.offsetWidth;
        canvas.height = container.offsetHeight;
        container.appendChild(canvas);
        
        const ctx = canvas.getContext('2d');
        const data = this.generateRandomData(7);
        
        this.drawSparkline(ctx, data, canvas.width, canvas.height);
    }

    /**
     * Draw sparkline chart
     */
    drawSparkline(ctx, data, width, height) {
        const padding = 4;
        const chartWidth = width - (padding * 2);
        const chartHeight = height - (padding * 2);
        
        const max = Math.max(...data);
        const min = Math.min(...data);
        const range = max - min || 1;
        
        ctx.strokeStyle = '#3b82f6';
        ctx.lineWidth = 2;
        ctx.beginPath();
        
        data.forEach((value, index) => {
            const x = padding + (index / (data.length - 1)) * chartWidth;
            const y = padding + chartHeight - ((value - min) / range) * chartHeight;
            
            if (index === 0) {
                ctx.moveTo(x, y);
            } else {
                ctx.lineTo(x, y);
            }
        });
        
        ctx.stroke();
        
        // Add gradient fill
        ctx.globalAlpha = 0.2;
        ctx.fillStyle = '#3b82f6';
        ctx.lineTo(width - padding, height - padding);
        ctx.lineTo(padding, height - padding);
        ctx.closePath();
        ctx.fill();
    }

    /**
     * Generate random data for demo
     */
    generateRandomData(points) {
        return Array.from({ length: points }, () => Math.random() * 100 + 50);
    }

    /**
     * Initialize interactive charts
     */
    initializeCharts() {
        const chartContainers = document.querySelectorAll('[data-chart]');
        
        chartContainers.forEach(container => {
            const chartType = container.getAttribute('data-chart');
            const chartId = container.id || this.core.utils.generateId('chart-');
            
            container.id = chartId;
            
            switch (chartType) {
                case 'revenue':
                    this.createRevenueChart(container);
                    break;
                case 'attendance':
                    this.createAttendanceChart(container);
                    break;
                case 'enrollment':
                    this.createEnrollmentChart(container);
                    break;
                case 'performance':
                    this.createPerformanceChart(container);
                    break;
                default:
                    this.createDefaultChart(container);
            }
        });
    }

    /**
     * Create revenue chart
     */
    createRevenueChart(container) {
        const canvas = this.createChartCanvas(container);
        const ctx = canvas.getContext('2d');
        
        // Revenue chart implementation
        this.drawRevenueChart(ctx, canvas.width, canvas.height);
    }

    /**
     * Create attendance chart
     */
    createAttendanceChart(container) {
        const canvas = this.createChartCanvas(container);
        const ctx = canvas.getContext('2d');
        
        // Attendance chart implementation
        this.drawAttendanceChart(ctx, canvas.width, canvas.height);
    }

    /**
     * Create chart canvas
     */
    createChartCanvas(container) {
        const canvas = document.createElement('canvas');
        canvas.width = container.offsetWidth;
        canvas.height = container.offsetHeight || 300;
        container.appendChild(canvas);
        
        return canvas;
    }

    /**
     * Draw revenue chart
     */
    drawRevenueChart(ctx, width, height) {
        const data = [65, 78, 85, 92, 88, 95, 102, 98, 110, 115, 120, 125];
        const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        this.drawLineChart(ctx, data, labels, width, height, {
            strokeColor: '#10b981',
            fillColor: 'rgba(16, 185, 129, 0.1)',
            pointColor: '#10b981'
        });
    }

    /**
     * Draw attendance chart
     */
    drawAttendanceChart(ctx, width, height) {
        const data = [85, 90, 78, 95, 88, 92, 96];
        const labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        
        this.drawBarChart(ctx, data, labels, width, height, {
            fillColor: '#3b82f6',
            hoverColor: '#2563eb'
        });
    }

    /**
     * Draw line chart
     */
    drawLineChart(ctx, data, labels, width, height, options = {}) {
        const padding = 40;
        const chartWidth = width - (padding * 2);
        const chartHeight = height - (padding * 2);
        
        const max = Math.max(...data);
        const min = Math.min(...data);
        const range = max - min || 1;
        
        // Draw grid
        ctx.strokeStyle = '#e5e7eb';
        ctx.lineWidth = 1;
        
        for (let i = 0; i <= 5; i++) {
            const y = padding + (i / 5) * chartHeight;
            ctx.beginPath();
            ctx.moveTo(padding, y);
            ctx.lineTo(width - padding, y);
            ctx.stroke();
        }
        
        // Draw line
        ctx.strokeStyle = options.strokeColor || '#3b82f6';
        ctx.lineWidth = 3;
        ctx.beginPath();
        
        data.forEach((value, index) => {
            const x = padding + (index / (data.length - 1)) * chartWidth;
            const y = padding + chartHeight - ((value - min) / range) * chartHeight;
            
            if (index === 0) {
                ctx.moveTo(x, y);
            } else {
                ctx.lineTo(x, y);
            }
        });
        
        ctx.stroke();
        
        // Draw points
        ctx.fillStyle = options.pointColor || '#3b82f6';
        data.forEach((value, index) => {
            const x = padding + (index / (data.length - 1)) * chartWidth;
            const y = padding + chartHeight - ((value - min) / range) * chartHeight;
            
            ctx.beginPath();
            ctx.arc(x, y, 4, 0, 2 * Math.PI);
            ctx.fill();
        });
        
        // Draw labels
        ctx.fillStyle = '#6b7280';
        ctx.font = '12px sans-serif';
        ctx.textAlign = 'center';
        
        labels.forEach((label, index) => {
            const x = padding + (index / (labels.length - 1)) * chartWidth;
            ctx.fillText(label, x, height - 10);
        });
    }

    /**
     * Draw bar chart
     */
    drawBarChart(ctx, data, labels, width, height, options = {}) {
        const padding = 40;
        const chartWidth = width - (padding * 2);
        const chartHeight = height - (padding * 2);
        const barWidth = chartWidth / data.length * 0.8;
        const barSpacing = chartWidth / data.length * 0.2;
        
        const max = Math.max(...data);
        
        data.forEach((value, index) => {
            const x = padding + index * (barWidth + barSpacing) + barSpacing / 2;
            const barHeight = (value / max) * chartHeight;
            const y = padding + chartHeight - barHeight;
            
            // Draw bar
            ctx.fillStyle = options.fillColor || '#3b82f6';
            ctx.fillRect(x, y, barWidth, barHeight);
            
            // Draw value on top
            ctx.fillStyle = '#374151';
            ctx.font = '12px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText(value + '%', x + barWidth / 2, y - 5);
            
            // Draw label
            ctx.fillStyle = '#6b7280';
            ctx.fillText(labels[index], x + barWidth / 2, height - 10);
        });
    }

    /**
     * Initialize data tables with advanced features
     */
    initializeDataTables() {
        const tables = document.querySelectorAll('[data-table="enhanced"]');
        
        tables.forEach(table => {
            this.enhanceTable(table);
        });
    }

    /**
     * Enhance table with sorting, filtering, and pagination
     */
    enhanceTable(table) {
        const wrapper = document.createElement('div');
        wrapper.className = 'enhanced-table-wrapper';
        table.parentNode.insertBefore(wrapper, table);
        wrapper.appendChild(table);
        
        // Add search
        this.addTableSearch(wrapper, table);
        
        // Add sorting
        this.addTableSorting(table);
        
        // Add row selection
        this.addTableRowSelection(table);
        
        // Add hover effects
        this.addTableHoverEffects(table);
    }

    /**
     * Add table search functionality
     */
    addTableSearch(wrapper, table) {
        const searchContainer = document.createElement('div');
        searchContainer.className = 'table-search-container';
        searchContainer.innerHTML = `
            <div class="search-input-wrapper">
                <input type="text" placeholder="Search..." class="table-search-input">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        `;
        
        wrapper.insertBefore(searchContainer, table);
        
        const searchInput = searchContainer.querySelector('.table-search-input');
        searchInput.addEventListener('input', (e) => {
            this.filterTable(table, e.target.value);
        });
    }

    /**
     * Filter table rows
     */
    filterTable(table, searchTerm) {
        const rows = table.querySelectorAll('tbody tr');
        const term = searchTerm.toLowerCase();
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });
    }

    /**
     * Add table sorting
     */
    addTableSorting(table) {
        const headers = table.querySelectorAll('thead th[data-sortable]');
        
        headers.forEach((header, index) => {
            header.style.cursor = 'pointer';
            header.innerHTML += ' <span class="sort-indicator">↕</span>';
            
            header.addEventListener('click', () => {
                this.sortTable(table, index, header);
            });
        });
    }

    /**
     * Sort table by column
     */
    sortTable(table, columnIndex, header) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const isAscending = !header.classList.contains('sort-asc');
        
        // Clear other sort indicators
        table.querySelectorAll('th').forEach(th => {
            th.classList.remove('sort-asc', 'sort-desc');
            const indicator = th.querySelector('.sort-indicator');
            if (indicator) indicator.textContent = '↕';
        });
        
        // Set current sort
        header.classList.add(isAscending ? 'sort-asc' : 'sort-desc');
        const indicator = header.querySelector('.sort-indicator');
        indicator.textContent = isAscending ? '↑' : '↓';
        
        rows.sort((a, b) => {
            const aText = a.cells[columnIndex].textContent.trim();
            const bText = b.cells[columnIndex].textContent.trim();
            
            const aVal = isNaN(aText) ? aText : parseFloat(aText);
            const bVal = isNaN(bText) ? bText : parseFloat(bText);
            
            if (aVal < bVal) return isAscending ? -1 : 1;
            if (aVal > bVal) return isAscending ? 1 : -1;
            return 0;
        });
        
        rows.forEach(row => tbody.appendChild(row));
    }

    /**
     * Add row selection functionality
     */
    addTableRowSelection(table) {
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            row.addEventListener('click', (e) => {
                if (e.target.type !== 'checkbox') {
                    row.classList.toggle('selected');
                    this.updateSelectionCount(table);
                }
            });
        });
    }

    /**
     * Update selection count
     */
    updateSelectionCount(table) {
        const selectedRows = table.querySelectorAll('tbody tr.selected');
        const count = selectedRows.length;
        
        let counter = table.parentNode.querySelector('.selection-counter');
        if (!counter && count > 0) {
            counter = document.createElement('div');
            counter.className = 'selection-counter';
            table.parentNode.insertBefore(counter, table);
        }
        
        if (counter) {
            if (count > 0) {
                counter.textContent = `${count} item${count !== 1 ? 's' : ''} selected`;
                counter.style.display = 'block';
            } else {
                counter.style.display = 'none';
            }
        }
    }

    /**
     * Add table hover effects
     */
    addTableHoverEffects(table) {
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            row.addEventListener('mouseenter', () => {
                row.style.backgroundColor = 'rgba(59, 130, 246, 0.05)';
                row.style.transform = 'translateX(4px)';
            });
            
            row.addEventListener('mouseleave', () => {
                if (!row.classList.contains('selected')) {
                    row.style.backgroundColor = '';
                }
                row.style.transform = '';
            });
        });
    }

    /**
     * Setup widget interactions
     */
    setupWidgetInteractions() {
        // Refresh buttons
        const refreshButtons = document.querySelectorAll('[data-refresh]');
        refreshButtons.forEach(button => {
            button.addEventListener('click', () => {
                this.refreshWidget(button.getAttribute('data-refresh'));
            });
        });
        
        // Widget settings
        const settingsButtons = document.querySelectorAll('[data-widget-settings]');
        settingsButtons.forEach(button => {
            button.addEventListener('click', () => {
                this.showWidgetSettings(button.getAttribute('data-widget-settings'));
            });
        });
    }

    /**
     * Refresh widget data
     */
    refreshWidget(widgetId) {
        const widget = document.querySelector(`[data-widget="${widgetId}"]`);
        if (!widget) return;
        
        // Add loading state
        widget.classList.add('loading');
        
        // Simulate data refresh
        setTimeout(() => {
            widget.classList.remove('loading');
            
            // Update timestamp
            const timestamp = widget.querySelector('.last-updated');
            if (timestamp) {
                timestamp.textContent = 'Just now';
            }
            
            this.core.announce(`${widgetId} widget updated`);
        }, 1000);
    }

    /**
     * Handle window resize
     */
    handleResize() {
        // Redraw charts
        this.charts.forEach(chart => {
            chart.resize();
        });
    }

    /**
     * Handle theme change
     */
    handleThemeChange(theme) {
        // Update chart colors based on theme
        this.updateChartsTheme(theme);
    }

    /**
     * Update charts theme
     */
    updateChartsTheme(theme) {
        const isDark = theme === 'dark';
        
        // Update chart colors
        this.charts.forEach(chart => {
            chart.updateColors({
                background: isDark ? '#1f2937' : '#ffffff',
                text: isDark ? '#f3f4f6' : '#374151',
                grid: isDark ? '#374151' : '#e5e7eb'
            });
        });
    }

    /**
     * Start real-time updates
     */
    startRealTimeUpdates() {
        this.updateInterval = setInterval(() => {
            this.updateRealTimeData();
        }, 30000); // Update every 30 seconds
    }

    /**
     * Update real-time data
     */
    updateRealTimeData() {
        // Update KPI values
        const kpiElements = document.querySelectorAll('[data-realtime]');
        kpiElements.forEach(element => {
            this.updateRealTimeElement(element);
        });
    }

    /**
     * Update real-time element
     */
    updateRealTimeElement(element) {
        const currentValue = parseFloat(element.textContent.replace(/[^\d.-]/g, ''));
        const change = (Math.random() - 0.5) * 10; // Random change
        const newValue = Math.max(0, currentValue + change);
        
        element.style.transition = 'all 0.5s ease';
        element.textContent = Math.round(newValue);
        
        // Add visual feedback for changes
        if (change > 0) {
            element.style.color = '#10b981';
        } else if (change < 0) {
            element.style.color = '#ef4444';
        }
        
        setTimeout(() => {
            element.style.color = '';
        }, 2000);
    }

    /**
     * Destroy and cleanup
     */
    destroy() {
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
        }
        
        this.widgets.clear();
        this.charts.clear();
        this.realTimeData.clear();
    }
}

// Register the module
if (typeof SportAcademy !== 'undefined') {
    SportAcademy.registerModule('Dashboard', DashboardModule);
}