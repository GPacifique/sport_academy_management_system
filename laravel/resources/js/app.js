import './bootstrap';

// Import enhanced modules
import './modules/Core.js';
import './modules/Dashboard.js';
import './modules/Modal.js';
import './modules/Form.js';
import './modules/Navigation.js';
import './modules/Theme.js';
import './modules/Integration.js';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Legacy theme handling - now integrated with core system
const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
const getStoredTheme = () => localStorage.getItem('theme') || localStorage.getItem('sport-academy-theme');
const setStoredTheme = (theme) => {
	localStorage.setItem('theme', theme);
	localStorage.setItem('sport-academy-theme', theme);
};

const applyTheme = (theme) => {
	const isDark = theme === 'dark';
	document.documentElement.classList.toggle('dark', isDark);
	document.documentElement.classList.toggle('light', !isDark);
	document.documentElement.classList.toggle('theme-dark', isDark);
	document.documentElement.setAttribute('data-theme', theme);
};

const initTheme = () => {
	const stored = getStoredTheme();
	const theme = stored ? stored : (prefersDark ? 'dark' : 'light');
	applyTheme(theme);
};

window.toggleTheme = () => {
	const isDark = document.documentElement.classList.contains('dark');
	const next = isDark ? 'light' : 'dark';
	applyTheme(next);
	setStoredTheme(next);
	
	// Emit to core system if available
	if (window.SportAcademy) {
		window.SportAcademy.emit('themeChanged', { theme: next });
	}
};

// Enhanced toast system - delegates to notification module if available
window.toast = (message, options = {}) => {
	const notificationModule = window.SportAcademy?.getModule('Notification');
	
	if (notificationModule) {
		const { type = 'success' } = options;
		return notificationModule.show(message, { type, ...options });
	}
	
	// Fallback to simple toast system
	const { type = 'success', timeout = 3500 } = options;
	let root = document.getElementById('toast-root');
	if (!root) {
		root = document.createElement('div');
		root.id = 'toast-root';
		root.className = 'fixed z-[100] top-4 right-4 space-y-2';
		document.body.appendChild(root);
	}
	const el = document.createElement('div');
	const base = 'px-4 py-2 rounded-lg shadow ring-1 text-sm flex items-start gap-2 backdrop-blur';
	const byType = {
		success: 'bg-emerald-600 text-white ring-emerald-500/30',
		error: 'bg-rose-600 text-white ring-rose-500/30',
		info: 'bg-slate-800 text-white ring-slate-700/30',
		warning: 'bg-amber-500 text-white ring-amber-400/30',
	};
	el.className = base + ' ' + (byType[type] || byType.info);
	el.innerHTML = `<div class="mt-0.5">${message}</div>`;
	root.appendChild(el);

	const remove = () => el.remove();
	setTimeout(remove, timeout);
	el.addEventListener('click', remove);
	return remove;
};

// Enhanced modal system
window.showModal = (options) => {
	const modalModule = window.SportAcademy?.getModule('Modal');
	if (modalModule) {
		return modalModule.show(options);
	}
	console.warn('Modal module not available');
};

window.confirm = async (message, options = {}) => {
	const modalModule = window.SportAcademy?.getModule('Modal');
	if (modalModule) {
		return await modalModule.confirm(message, options);
	}
	return window.confirm(message); // Fallback
};

window.alert = async (message, options = {}) => {
	const modalModule = window.SportAcademy?.getModule('Modal');
	if (modalModule) {
		return await modalModule.alert(message, options);
	}
	window.alert(message); // Fallback
};

// Initialize after DOM is ready
document.addEventListener('DOMContentLoaded', () => {
	initTheme();
	
	// Auto-toast flash message if present
	const flash = document.querySelector('[data-flash]');
	if (flash && flash.textContent.trim()) {
		const type = flash.getAttribute('data-flash') || 'success';
		setTimeout(() => {
			toast(flash.textContent.trim(), { type });
		}, 100);
	}
	
	// Initialize Alpine.js components
	Alpine.start();
	
	console.log('🎉 Sport Academy Management System - Enhanced UI Loaded');
});
