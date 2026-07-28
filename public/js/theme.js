(function () {
    var STORAGE_KEY = 'iskolar-theme';

    function applyTheme(theme) {
        var isDark = theme === 'dark';
        document.documentElement.setAttribute('data-theme', theme);
        document.documentElement.setAttribute('data-bs-theme', theme);
        document.documentElement.classList.toggle('dark', isDark);
        localStorage.setItem(STORAGE_KEY, theme);

        document.querySelectorAll('.theme-toggle').forEach(function (btn) {
            btn.setAttribute('aria-pressed', isDark ? 'true' : 'false');
        });

        window.dispatchEvent(new CustomEvent('themechange', { detail: { theme: theme } }));
    }

    window.toggleTheme = function () {
        var current = document.documentElement.getAttribute('data-theme') || 'light';
        applyTheme(current === 'dark' ? 'light' : 'dark');
    };

    window.getThemeColors = function () {
        var styles = getComputedStyle(document.documentElement);
        return {
            ink: styles.getPropertyValue('--text-900').trim(),
            muted: styles.getPropertyValue('--text-500').trim(),
            surface: styles.getPropertyValue('--surface-0').trim(),
            grid: styles.getPropertyValue('--surface-border').trim(),
            tooltip: styles.getPropertyValue('--ink-900').trim(),
        };
    };

    document.addEventListener('DOMContentLoaded', function () {
        var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        document.querySelectorAll('.theme-toggle').forEach(function (btn) {
            btn.setAttribute('aria-pressed', isDark ? 'true' : 'false');
            btn.addEventListener('click', window.toggleTheme);
        });
    });
})();
