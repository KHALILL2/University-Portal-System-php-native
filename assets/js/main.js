// University Portal System - Main JS
document.addEventListener('DOMContentLoaded', function () {

    // --- Mobile Nav Toggle ---
    const toggle = document.querySelector('.nav-toggle');
    const nav = document.querySelector('.main-nav');
    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            nav.classList.toggle('open');
        });
    }

    // --- Auto-dismiss alerts after 5 seconds ---
    document.querySelectorAll('.alert').forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(function () { alert.remove(); }, 400);
        }, 5000);
    });

    // --- Delete confirmation ---
    document.querySelectorAll('.btn-delete').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });

    // --- Scroll Animations ---
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
            }
        });
    }, observerOptions);
    
    // Add animation class to cards and elements dynamically
    document.querySelectorAll('.card, .stat-card, .quick-link, .news-card').forEach(function(el, index) {
        if (!el.classList.contains('animate-on-scroll')) {
            el.classList.add('animate-on-scroll');
            // Stagger animations slightly based on index
            if (index % 3 === 1) el.classList.add('animate-delay-1');
            if (index % 3 === 2) el.classList.add('animate-delay-2');
        }
    });

    // Start observing all animated elements
    document.querySelectorAll('.animate-on-scroll').forEach(function(el) {
        observer.observe(el);
    });

});
