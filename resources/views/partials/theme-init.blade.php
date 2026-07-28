<script>
(function () {
    var key = 'iskolar-theme';
    var theme = localStorage.getItem(key);
    if (theme !== 'dark' && theme !== 'light') {
        theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }
    document.documentElement.setAttribute('data-theme', theme);
    document.documentElement.setAttribute('data-bs-theme', theme);
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
    }
})();
</script>
