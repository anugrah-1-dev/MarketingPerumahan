// ===== API Configuration =====
var API_BASE_URL = 'http://localhost:8000/api';

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

// ===== Format Rupiah Helper =====
function formatRupiah(angka) {
    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function formatHargaJuta(harga) {
    var juta = harga / 1000000;
    if (juta >= 1000) {
        return 'Rp ' + (juta / 1000).toFixed(1).replace('.0', '') + ' Miliar';
    }
    return 'Rp ' + Math.round(juta) + ' Juta';
}

// ===== Load Units from API =====
function loadUnits() {
    var container = document.getElementById('units-container');
    var loading = document.getElementById('units-loading');
    var errorEl = document.getElementById('units-error');

    loading.style.display = 'flex';
    errorEl.style.display = 'none';

    fetch(API_BASE_URL + '/units')
        .then(function(response) {
            if (!response.ok) {
                throw new Error('HTTP error ' + response.status);
            }
            return response.json();
        })
        .then(function(result) {
            loading.style.display = 'none';

            if (!result.success || !result.data || result.data.length === 0) {
                errorEl.style.display = 'flex';
                return;
            }

            renderUnits(result.data);
            updateKPROptions(result.data);
        })
        .catch(function(error) {
            console.error('Gagal memuat unit:', error);
            loading.style.display = 'none';
            errorEl.style.display = 'flex';
        });
}

function getStatusClass(status) {
    switch (status) {
        case 'tersedia': return 'status-available';
        case 'booking': return 'status-booking';
        case 'terjual': return 'status-sold';
        default: return 'status-available';
    }
}

function getStatusLabel(status) {
    switch (status) {
        case 'tersedia': return '✓ Tersedia';
        case 'booking': return '⏳ Booking';
        case 'terjual': return '✗ Terjual';
        default: return '✓ Tersedia';
    }
}

function getFullImageUrl(path) {
    if (!path) return null;
    // Jika path sudah berupa URL lengkap, gunakan langsung
    if (path.startsWith('http')) return path;
    // Jika path adalah relative path dari backend
    return 'http://localhost:8000/' + path;
}

function renderUnits(units) {
    var container = document.getElementById('units-container');
    // Remove loading and error elements, keep only cards
    var loading = document.getElementById('units-loading');
    var errorEl = document.getElementById('units-error');

    // Clear previous cards but keep loading/error elements
    var cards = container.querySelectorAll('.unit-card');
    cards.forEach(function(card) { card.remove(); });

    units.forEach(function(unit) {
        var card = document.createElement('div');
        card.className = 'unit-card fade-in';

        var imageUrl = getFullImageUrl(unit.gambar);

        card.innerHTML =
            '<div class="unit-image">' +
                (imageUrl
                    ? '<img src="' + imageUrl + '" alt="Tipe ' + unit.nama + '" onerror="this.parentElement.innerHTML=\'Gambar Tipe ' + unit.nama + '\'"> '
                    : 'Gambar Tipe ' + unit.nama) +
            '</div>' +
            '<div class="unit-info">' +
                '<div class="unit-type">Tipe ' + unit.tipe + '</div>' +
                '<div class="unit-name">Rumah Tipe ' + unit.nama + '</div>' +
                '<div class="unit-specs">' +
                    '<span>' + unit.kamar_tidur + ' KT</span>' +
                    '<span>' + unit.kamar_mandi + ' KM</span>' +
                    '<span>LT ' + unit.luas_tanah + 'm²</span>' +
                '</div>' +
                '<div class="unit-price">' + formatHargaJuta(unit.harga) + ' <small>/ unit</small></div>' +
                '<div class="unit-status ' + getStatusClass(unit.status) + '">' + getStatusLabel(unit.status) + '</div>' +
            '</div>';

        container.appendChild(card);

        // Observe for fade-in animation
        observer.observe(card);
    });
}

function updateKPROptions(units) {
    var select = document.getElementById('tipeRumah');
    select.innerHTML = '';

    units.forEach(function(unit) {
        var option = document.createElement('option');
        option.value = unit.harga;
        option.textContent = 'Tipe ' + unit.nama + ' - ' + formatHargaJuta(unit.harga);
        select.appendChild(option);
    });

    // Recalculate KPR with new options
    hitungKPR();
}

// ===== Simulasi KPR =====
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

// ===== Initialize =====
document.addEventListener('DOMContentLoaded', function() {
    loadUnits();
});

