document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('themeToggle');
    const theme = localStorage.getItem('theme') || 'light';

    document.documentElement.setAttribute('data-theme', theme);
    if (toggle) toggle.checked = theme === 'dark';

    toggle?.addEventListener('change', function () {
        const newTheme = this.checked ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
    });
});