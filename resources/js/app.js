import './bootstrap';

import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';
import sitePicker from './date-picker';

window.Alpine = Alpine;
window.Chart = Chart;

Alpine.data('sitePicker', sitePicker);

Alpine.start();

// Reveal-on-scroll ringan dan seragam untuk seluruh halaman publik
document.addEventListener('DOMContentLoaded', function () {
    var items = document.querySelectorAll('.reveal');
    if (!('IntersectionObserver' in window) || items.length === 0) {
        items.forEach(function (el) { el.classList.add('is-visible'); });
        return;
    }
    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    items.forEach(function (el) { observer.observe(el); });
});

// Sub-nav "tab" pada halaman Detail Paket (packages/show.blade.php).
// Menjaga indikator aktif (garis emas) selalu pindah mengikuti section
// yang sedang dilihat saat scroll, maupun tab yang baru saja diklik.
document.addEventListener('DOMContentLoaded', function () {
    var nav = document.querySelector('.pkg-subnav');
    if (!nav) return;

    var links = Array.prototype.slice.call(nav.querySelectorAll('.pkg-subnav-link'));
    var sections = links
        .map(function (link) {
            var hash = link.getAttribute('href') || '';
            if (hash.charAt(0) !== '#') return null;
            var section = document.querySelector(hash);
            return section ? { link: link, section: section } : null;
        })
        .filter(Boolean);
    if (sections.length === 0) return;

    function setActive(activeLink) {
        links.forEach(function (link) {
            link.classList.toggle('is-active', link === activeLink);
        });
    }

    function navOffset() {
        return nav.getBoundingClientRect().height + 28;
    }

    var ticking = false;
    var manualLock = null;

    function updateActive() {
        ticking = false;
        if (manualLock) return;

        var offset = navOffset();
        var current = sections[0].link;
        for (var i = 0; i < sections.length; i++) {
            if (sections[i].section.getBoundingClientRect().top - offset <= 0) {
                current = sections[i].link;
            }
        }

        var nearBottom = window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 4;
        if (nearBottom) current = sections[sections.length - 1].link;

        setActive(current);
    }

    window.addEventListener('scroll', function () {
        if (!ticking) {
            window.requestAnimationFrame(updateActive);
            ticking = true;
        }
    }, { passive: true });

    window.addEventListener('resize', updateActive);

    links.forEach(function (link) {
        link.addEventListener('click', function () {
            // Aktifkan segera saat diklik, lalu lepas kunci setelah smooth-scroll selesai
            // supaya scrollspy tidak "menarik" indikator kembali di tengah animasi scroll.
            setActive(link);
            manualLock = link;
            window.clearTimeout(link._unlockTimer);
            link._unlockTimer = window.setTimeout(function () {
                manualLock = null;
                updateActive();
            }, 700);
        });
    });

    updateActive();
});
