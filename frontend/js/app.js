// ===== Navbar Scroll Effect =====
window.addEventListener('scroll', function() {
    const navbar = document.getElementById('navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// ===== Mobile Menu Toggle =====
document.getElementById('menuToggle').addEventListener('click', function() {
    document.getElementById('navLinks').classList.toggle('open');
});

// Close menu on link click
document.querySelectorAll('.nav-links a').forEach(function(link) {
    link.addEventListener('click', function() {
        document.getElementById('navLinks').classList.remove('open');
    });
});

// ===== Active Link on Scroll =====
window.addEventListener('scroll', function() {
    var sections = document.querySelectorAll('section[id]');
    var navLinks = document.querySelectorAll('.nav-links a:not(.nav-cta)');
    var scrollPos = window.scrollY + 120;

    sections.forEach(function(section) {
        if (scrollPos >= section.offsetTop && scrollPos < section.offsetTop + section.offsetHeight) {
            navLinks.forEach(function(link) {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + section.id) {
                    link.classList.add('active');
                }
            });
        }
    });
});

// ===== Fade In Animation =====
var observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.fade-in').forEach(function(el) {
    observer.observe(el);
});

// ===== Simulasi KPR =====
function formatRupiah(angka) {
    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function hitungKPR() {
    var harga = parseInt(document.getElementById('tipeRumah').value);
    var dpPersen = parseFloat(document.getElementById('dpPersen').value);
    var tenor = parseInt(document.getElementById('tenor').value);
    var bunga = parseFloat(document.getElementById('bunga').value);

    var dp = harga * (dpPersen / 100);
    var pinjaman = harga - dp;
    var bungaBulanan = bunga / 100 / 12;
    var totalBulan = tenor * 12;

    var cicilan = pinjaman * (bungaBulanan * Math.pow(1 + bungaBulanan, totalBulan)) / (Math.pow(1 + bungaBulanan, totalBulan) - 1);

    document.getElementById('resHarga').textContent = formatRupiah(harga);
    document.getElementById('resDP').textContent = formatRupiah(Math.round(dp));
    document.getElementById('resPinjaman').textContent = formatRupiah(Math.round(pinjaman));
    document.getElementById('resTenor').textContent = tenor + ' Tahun';
    document.getElementById('resBunga').textContent = bunga + '% / tahun';
    document.getElementById('resCicilan').textContent = formatRupiah(Math.round(cicilan));
}

// Initial calculation
hitungKPR();
