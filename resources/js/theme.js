/**
 * WAKANDE - Theme Switcher (Light/Dark Mode)
 * Handles theme toggling with localStorage persistence
 */

class ThemeManager {
    constructor() {
        this.theme = localStorage.getItem('theme') || 'light';
        this.init();
    }

    init() {
        this.setTheme(this.theme);
        this.setupListeners();
        this.syncWithSystem();
    }

    setTheme(theme) {
        document.documentElement.setAttribute('data-bs-theme', theme);
        localStorage.setItem('theme', theme);
        this.theme = theme;

        // Update all theme toggles
        this.updateToggles(theme);

        // Dispatch custom event for other scripts
        window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme } }));

        // Update meta theme-color
        this.updateThemeColor(theme);
    }

    toggleTheme() {
        const newTheme = this.theme === 'light' ? 'dark' : 'light';
        this.setTheme(newTheme);
    }

    updateToggles(theme) {
        const isDark = theme === 'dark';

        document.querySelectorAll('[id^="light-icon"]').forEach(el => {
            el.style.display = isDark ? 'none' : 'inline-block';
        });

        document.querySelectorAll('[id^="dark-icon"]').forEach(el => {
            el.style.display = isDark ? 'inline-block' : 'none';
        });

        document.querySelectorAll('[id^="theme-text"]').forEach(el => {
            if (el) el.textContent = isDark ? 'Dark Mode' : 'Light Mode';
        });
    }

    updateThemeColor(theme) {
        const color = theme === 'dark' ? '#0a0c10' : '#ffffff';
        document.querySelector('meta[name="theme-color"]')?.setAttribute('content', color);
    }

    setupListeners() {
        // Theme toggle buttons
        document.querySelectorAll('[id^="theme-toggle"]').forEach(btn => {
            btn.removeEventListener('click', this.handleToggleClick);
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleTheme();
            });
        });

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('theme')) {
                this.setTheme(e.matches ? 'dark' : 'light');
            }
        });
    }

    syncWithSystem() {
        // If no theme saved, use system preference
        if (!localStorage.getItem('theme')) {
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            this.setTheme(systemPrefersDark ? 'dark' : 'light');
        }
    }

    getCurrentTheme() {
        return this.theme;
    }

    isDarkMode() {
        return this.theme === 'dark';
    }

    isLightMode() {
        return this.theme === 'light';
    }
}

// Initialize theme manager
window.theme = new ThemeManager();

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Add theme toggle to navbar if not exists
    const navbarNav = document.querySelector('.navbar .d-flex.align-items-center');
    if (navbarNav && !document.querySelector('#theme-toggle')) {
        const themeToggle = document.createElement('button');
        themeToggle.id = 'theme-toggle';
        themeToggle.className = 'btn btn-link p-0 border-0 me-3';
        themeToggle.style.color = 'var(--bs-body-color)';
        themeToggle.innerHTML = `
            <i class="bi bi-sun-fill fs-5" id="light-icon"></i>
            <i class="bi bi-moon-stars-fill fs-5" id="dark-icon" style="display: none;"></i>
        `;

        // Insert before auth buttons
        const authButtons = navbarNav.querySelector('.d-flex.gap-3');
        if (authButtons) {
            navbarNav.insertBefore(themeToggle, authButtons);
        } else {
            navbarNav.appendChild(themeToggle);
        }

        // Re-initialize listeners
        window.theme.setupListeners();
        window.theme.updateToggles(window.theme.getCurrentTheme());
    }
});

// ===== THEME UTILITIES =====

// Get CSS variable value
window.getCssVar = function(varName) {
    return getComputedStyle(document.documentElement)
        .getPropertyValue(varName).trim();
};

// Set CSS variable
window.setCssVar = function(varName, value) {
    document.documentElement.style.setProperty(varName, value);
};

// Watch for theme changes on specific elements
window.watchTheme = function(element, lightValue, darkValue) {
    const update = () => {
        element.style.color = window.theme.isDarkMode() ? darkValue : lightValue;
    };

    window.addEventListener('themeChanged', update);
    update();
};

// ===== DARK MODE MEDIA =====

// Check if dark mode is supported
window.isDarkModeSupported = function() {
    return window.matchMedia('(prefers-color-scheme: dark)').matches !== undefined;
};

// Get system theme
window.getSystemTheme = function() {
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
};

// ===== EXPORT =====
export default ThemeManager;
