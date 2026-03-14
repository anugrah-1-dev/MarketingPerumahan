/**
 * dashboard.js – Data real dari database via API
 */

let clickTrendsChart;

const dashBase = (() => {
    const seg = window.location.pathname.split('/').filter(Boolean)[0];
    return seg === 'manager' ? '/manager/dashboard' : '/admin/dashboard';
})();

function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
}

// ─── Init ───────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    loadDashboard();
    setInterval(loadDashboard, 60000);
});

// ─── Ambil data dari server ──────────────────────────────────────────────────
function loadDashboard() {
    fetch(`${dashBase}/data`, {
        headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    })
        .then((r) => {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        })
        .then(({ stats, clickTrends, topAgents, recentActivity }) => {
            renderStats(stats);
            renderClickTrendsChart(clickTrends);
            renderTopAgents(topAgents);
            renderRecentActivity(recentActivity);
        })
        .catch((err) => {
            console.error('Dashboard load error:', err);
        });
}

// ─── Stats ────────────────────────────────────────────────────────────────────
function renderStats(stats) {
    animateValue('totalClicks',  0, stats.totalClicks,  800);
    animateValue('totalClosing', 0, stats.totalClosing, 800);
    animateValue('activeAgents', 0, stats.activeAgents, 800);
    document.getElementById('totalCommission').textContent = formatCurrency(stats.totalCommission);
}

function animateValue(id, start, end, duration) {
    const el = document.getElementById(id);
    if (!el) return;
    const range     = end - start;
    const increment = range / (duration / 16);
    let current     = start;
    const timer = setInterval(() => {
        current += increment;
        if ((increment >= 0 && current >= end) || (increment < 0 && current <= end)) {
            current = end;
            clearInterval(timer);
        }
        el.textContent = Math.floor(current).toLocaleString('id-ID');
    }, 16);
}

// ─── Chart Tren Klik ─────────────────────────────────────────────────────────
function renderClickTrendsChart(trends) {
    const ctx = document.getElementById('clickTrendsChart')?.getContext('2d');
    if (!ctx) return;
    if (clickTrendsChart) clickTrendsChart.destroy();
    ctx.canvas.style.height = '300px';
    clickTrendsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: trends.labels,
            datasets: [{
                label: 'Jumlah Klik',
                data: trends.data,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 6,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#3b82f6',
                    borderWidth: 1,
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#e2e8f0' },
                    ticks: { color: '#64748b' },
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b' },
                },
            },
        },
    });
}

// ─── Top Agents ───────────────────────────────────────────────────────────────
function renderTopAgents(agents) {
    const container = document.getElementById('topAgentsList');
    if (!container) return;
    if (!agents || agents.length === 0) {
        container.innerHTML = '<p style="color:#94a3b8;text-align:center;padding:1.5rem">Belum ada data agent.</p>';
        return;
    }
    container.innerHTML = agents.map((agent, i) => `
        <div class="agent-item">
            <div class="agent-info">
                <div class="agent-avatar">${agent.avatar}</div>
                <div class="agent-details">
                    <h4>${agent.name}</h4>
                    <p>${agent.clicks} klik &bull; ${agent.closings} closing</p>
                </div>
            </div>
            <div class="agent-stats">
                <div class="count">#${i + 1}</div>
                <div class="label">Ranking</div>
            </div>
        </div>
    `).join('');
}

// ─── Aktivitas Terbaru ────────────────────────────────────────────────────────
function renderRecentActivity(activities) {
    const tbody = document.getElementById('recentActivityTable');
    if (!tbody) return;
    if (!activities || activities.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;color:#94a3b8;padding:1.5rem">Belum ada aktivitas.</td></tr>';
        return;
    }
    const badgeMap = {
        'new':            '<span class="badge info">Baru</span>',
        'follow-up':      '<span class="badge warning">Follow Up</span>',
        'interested':     '<span class="badge success">Tertarik</span>',
        'not-interested': '<span class="badge danger">Tidak Tertarik</span>',
        'closed':         '<span class="badge" style="background:#8b5cf6;color:white;">Closing</span>',
    };
    tbody.innerHTML = activities.map((a) => `
        <tr>
            <td>${a.time}</td>
            <td>${a.agent}</td>
            <td>${a.activity}</td>
            <td>${badgeMap[a.status] ?? '<span class="badge">-</span>'}</td>
        </tr>
    `).join('');
}
