// Mock Data - In Laravel, this will come from your API/Backend
const mockData = {
    stats: {
        totalClicks: 1247,
        totalClosing: 23,
        totalCommission: 125000000,
        activeAgents: 15
    },
    clickTrends: {
        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
        data: [45, 52, 38, 65, 72, 48, 55]
    },
    topAgents: [
        { id: 1, name: 'Budi Santoso', clicks: 156, closings: 5, avatar: 'BS' },
        { id: 2, name: 'Siti Nurhaliza', clicks: 142, closings: 4, avatar: 'SN' },
        { id: 3, name: 'Ahmad Rizki', clicks: 128, closings: 4, avatar: 'AR' },
        { id: 4, name: 'Dewi Lestari', clicks: 115, closings: 3, avatar: 'DL' },
        { id: 5, name: 'Rudi Hartono', clicks: 98, closings: 3, avatar: 'RH' }
    ],
    recentActivity: [
        { 
            time: '10 menit lalu', 
            agent: 'Budi Santoso', 
            activity: 'Klik link properti Rumah Type 45', 
            status: 'new' 
        },
        { 
            time: '25 menit lalu', 
            agent: 'Siti Nurhaliza', 
            activity: 'Closing properti Rumah Type 60', 
            status: 'success' 
        },
        { 
            time: '1 jam lalu', 
            agent: 'Ahmad Rizki', 
            activity: 'Klik link properti Rumah Type 36', 
            status: 'new' 
        },
        { 
            time: '2 jam lalu', 
            agent: 'Dewi Lestari', 
            activity: 'Follow up lead - Tertarik', 
            status: 'follow-up' 
        },
        { 
            time: '3 jam lalu', 
            agent: 'Rudi Hartono', 
            activity: 'Klik link properti Rumah Type 45', 
            status: 'new' 
        }
    ]
};

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
}

// Initialize Dashboard
document.addEventListener('DOMContentLoaded', function() {
    loadStats();
    loadClickTrendsChart();
    loadTopAgents();
    loadRecentActivity();
});

// Load Stats
function loadStats() {
    document.getElementById('totalClicks').textContent = mockData.stats.totalClicks.toLocaleString('id-ID');
    document.getElementById('totalClosing').textContent = mockData.stats.totalClosing;
    document.getElementById('totalCommission').textContent = formatCurrency(mockData.stats.totalCommission);
    document.getElementById('activeAgents').textContent = mockData.stats.activeAgents;

    // Animate numbers
    animateValue('totalClicks', 0, mockData.stats.totalClicks, 1000);
    animateValue('totalClosing', 0, mockData.stats.totalClosing, 1000);
    animateValue('activeAgents', 0, mockData.stats.activeAgents, 1000);
}

// Animate number counter
function animateValue(id, start, end, duration) {
    const element = document.getElementById(id);
    const range = end - start;
    const increment = range / (duration / 16);
    let current = start;
    
    const timer = setInterval(() => {
        current += increment;
        if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
            current = end;
            clearInterval(timer);
        }
        
        if (id === 'totalCommission') {
            element.textContent = formatCurrency(current);
        } else {
            element.textContent = Math.floor(current).toLocaleString('id-ID');
        }
    }, 16);
}

// Load Click Trends Chart
let clickTrendsChart;
function loadClickTrendsChart() {
    const ctx = document.getElementById('clickTrendsChart').getContext('2d');
    
    if (clickTrendsChart) {
        clickTrendsChart.destroy();
    }
    
    clickTrendsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: mockData.clickTrends.labels,
            datasets: [{
                label: 'Jumlah Klik',
                data: mockData.clickTrends.data,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#3b82f6',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#e2e8f0'
                    },
                    ticks: {
                        color: '#64748b'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#64748b'
                    }
                }
            }
        }
    });
    
    // Set canvas height
    ctx.canvas.style.height = '300px';
}

// Load Top Agents
function loadTopAgents() {
    const container = document.getElementById('topAgentsList');
    container.innerHTML = '';
    
    mockData.topAgents.forEach((agent, index) => {
        const agentItem = document.createElement('div');
        agentItem.className = 'agent-item';
        agentItem.innerHTML = `
            <div class="agent-info">
                <div class="agent-avatar">${agent.avatar}</div>
                <div class="agent-details">
                    <h4>${agent.name}</h4>
                    <p>${agent.clicks} klik • ${agent.closings} closing</p>
                </div>
            </div>
            <div class="agent-stats">
                <div class="count">#${index + 1}</div>
                <div class="label">Ranking</div>
            </div>
        `;
        container.appendChild(agentItem);
    });
}

// Load Recent Activity
function loadRecentActivity() {
    const tbody = document.getElementById('recentActivityTable');
    tbody.innerHTML = '';
    
    mockData.recentActivity.forEach(activity => {
        const tr = document.createElement('tr');
        
        let statusBadge = '';
        switch(activity.status) {
            case 'new':
                statusBadge = '<span class="badge info">Baru</span>';
                break;
            case 'success':
                statusBadge = '<span class="badge success">Closing</span>';
                break;
            case 'follow-up':
                statusBadge = '<span class="badge warning">Follow Up</span>';
                break;
            default:
                statusBadge = '<span class="badge">-</span>';
        }
        
        tr.innerHTML = `
            <td>${activity.time}</td>
            <td>${activity.agent}</td>
            <td>${activity.activity}</td>
            <td>${statusBadge}</td>
        `;
        tbody.appendChild(tr);
    });
}

// Refresh Dashboard Data
function refreshDashboard() {
    // In Laravel, you would make an AJAX call to your API
    // fetch('/api/dashboard')
    //     .then(response => response.json())
    //     .then(data => {
    //         mockData = data;
    //         loadStats();
    //         loadClickTrendsChart();
    //         loadTopAgents();
    //         loadRecentActivity();
    //     });
    
    // For demo purposes:
    console.log('Refreshing dashboard data...');
    loadStats();
    loadClickTrendsChart();
    loadTopAgents();
    loadRecentActivity();
}

// Auto refresh every 30 seconds
setInterval(refreshDashboard, 30000);
