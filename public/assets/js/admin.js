(() => {
    const toggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');

    if (toggle && sidebar) {
        toggle.addEventListener('click', () => sidebar.classList.toggle('sidebar-open'));
        document.addEventListener('click', (event) => {
            if (window.innerWidth <= 900 &&
                !sidebar.contains(event.target) &&
                !toggle.contains(event.target)) {
                sidebar.classList.remove('sidebar-open');
            }
        });
    }
})();
