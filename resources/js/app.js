import './bootstrap';
import './auth-modals';
import './recaptcha-forms';

import Alpine from 'alpinejs';

const THEME_KEY = 'theme';

function normalizeTheme(value) {
	return value === 'light' || value === 'dark' ? value : null;
}

function getForcedTheme() {
	return normalizeTheme(document.documentElement?.dataset?.forceTheme);
}

function getInitialTheme() {
	const forced = getForcedTheme();
	if (forced) return forced;

	const stored = normalizeTheme(window.localStorage?.getItem(THEME_KEY));
	if (stored) return stored;

	// Default to dark (site was designed dark-first)
	return 'dark';
}

function applyTheme(theme) {
	const resolved = normalizeTheme(theme) ?? 'dark';
	const isDark = resolved === 'dark';

	document.documentElement.classList.toggle('dark', isDark);
	document.documentElement.dataset.theme = resolved;
	document.documentElement.style.colorScheme = isDark ? 'dark' : 'light';
}

function setTheme(theme) {
	const forced = getForcedTheme();
	if (forced) {
		applyTheme(forced);
		return;
	}

	const resolved = normalizeTheme(theme) ?? 'dark';
	try {
		window.localStorage?.setItem(THEME_KEY, resolved);
	} catch (_) {
		// ignore
	}
	applyTheme(resolved);
}

function toggleTheme() {
	if (getForcedTheme()) return;

	const current = normalizeTheme(document.documentElement.dataset.theme)
		?? (document.documentElement.classList.contains('dark') ? 'dark' : 'light');
	setTheme(current === 'dark' ? 'light' : 'dark');
}

// Apply theme ASAP (after bundle loads)
applyTheme(getInitialTheme());

// Delegate click handler so it works across all pages/layouts
document.addEventListener('click', (event) => {
	const button = event.target?.closest?.('[data-theme-toggle]');
	if (!button) return;
	event.preventDefault();
	toggleTheme();
});

window.__setTheme = setTheme;
window.__toggleTheme = toggleTheme;

window.Alpine = Alpine;

Alpine.start();
