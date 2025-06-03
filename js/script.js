document.addEventListener('DOMContentLoaded', function() {
    // --- Navbar Toggle Script ---
    const navToggle = document.getElementById('navToggle');
    const mainNav = document.getElementById('mainNav');
    const body = document.body; // Untuk mencegah scrolling saat menu terbuka

    if (navToggle && mainNav) {
        navToggle.addEventListener('click', function() {
            mainNav.classList.toggle('nav-open');
            navToggle.classList.toggle('active'); // Mengubah ikon hamburger
            body.classList.toggle('no-scroll'); // Mencegah scroll pada body
        });
    }

    // --- Reveal on Scroll Animation (for elements with .reveal-item) ---
    const revealItems = document.querySelectorAll('.reveal-item');
    if (revealItems.length > 0) {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 }); // Trigger when 10% of the item is visible

        revealItems.forEach(item => {
            observer.observe(item);
        });
    }

    // --- Loader Screen (if elements with #loader and #body-content exist) ---
    // Pastikan #loader dan #body-content ada di HTML Anda agar ini berfungsi
    const loader = document.getElementById('loader');
    const bodyContent = document.getElementById('body-content');
    if (loader && bodyContent) {
        window.addEventListener('load', function() {
            loader.style.opacity = '0';
            loader.style.visibility = 'hidden';
            bodyContent.style.opacity = '1';
        });
    }
});