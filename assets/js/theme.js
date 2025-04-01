document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const mobileThemeToggle = document.getElementById('mobile-theme-toggle');
    const themeStyle = document.getElementById('theme-style');
    
    // Check for saved theme preference or use preferred color scheme
    const currentTheme = localStorage.getItem('theme') || 
                        (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    
    // Apply the current theme
    applyTheme(currentTheme);
    
    // Toggle theme
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }
    
    if (mobileThemeToggle) {
        mobileThemeToggle.addEventListener('click', function() {
            toggleTheme();
            document.querySelector('.mobile-menu').classList.remove('active');
        });
    }
    
    function toggleTheme() {
        const newTheme = themeStyle.getAttribute('href').includes('light.css') ? 'dark' : 'light';
        applyTheme(newTheme);
        localStorage.setItem('theme', newTheme);
    }
    
    function applyTheme(theme) {
        if (theme === 'dark') {
            themeStyle.setAttribute('href', 'assets/css/dark.css');
            if (themeToggle) themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            if (mobileThemeToggle) mobileThemeToggle.innerHTML = '<i class="fas fa-sun"></i> Toggle Theme';
        } else {
            themeStyle.setAttribute('href', 'assets/css/light.css');
            if (themeToggle) themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            if (mobileThemeToggle) mobileThemeToggle.innerHTML = '<i class="fas fa-moon"></i> Toggle Theme';
        }
    }
});