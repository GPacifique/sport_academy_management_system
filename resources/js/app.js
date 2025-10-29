import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Theme (dark/light) handling with localStorage persistence
const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
const getStoredTheme = () => localStorage.getItem('theme');
const setStoredTheme = (theme) => localStorage.setItem('theme', theme);
const applyTheme = (theme) => {
	const isDark = theme === 'dark';
	document.documentElement.classList.toggle('dark', isDark);
	document.documentElement.classList.toggle('light', !isDark);
	document.documentElement.classList.toggle('theme-dark', isDark);
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
};

// Simple toast/flash system
window.toast = (message, options = {}) => {
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

// Initialize after DOM is ready
document.addEventListener('DOMContentLoaded', () => {
	initTheme();
	// Auto-toast flash message if present
	const flash = document.querySelector('[data-flash]');
	if (flash && flash.textContent.trim()) {
		const type = flash.getAttribute('data-flash') || 'success';
		toast(flash.textContent.trim(), { type });
	}
});

Alpine.start();
