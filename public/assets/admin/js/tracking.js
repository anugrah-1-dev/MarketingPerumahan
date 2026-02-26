// Mock Clicks Data
let clicks = [
    {
        id: 1,
        timestamp: '2024-02-26 14:30:25',
        agentId: 1,
        agentName: 'Budi Santoso',
        agentCode: 'AGT001',
        propertyId: 1,
        propertyName: 'Rumah Type 45 - Blok A',
        phoneNumber: '6281234567890',
        device: 'Mobile',
        browser: 'Chrome Mobile',
        ipAddress: '192.168.1.100',
        status: 'new',
        notes: '',
        followUpDate: null,
        location: 'Jakarta, Indonesia'
    },
    {
        id: 2,
        timestamp: '2024-02-26 13:15:10',
        agentId: 2,
        agentName: 'Siti Nurhaliza',
        agentCode: 'AGT002',
        propertyId: 2,
        propertyName: 'Rumah Type 60 - Blok B',
        phoneNumber: '6281234567891',
        device: 'Desktop',
        browser: 'Chrome',
        ipAddress: '192.168.1.101',
        status: 'follow-up',
        notes: 'Customer tertarik, akan survey lokasi besok',
        followUpDate: '2024-02-27 10:00:00',
        location: 'Bandung, Indonesia'
    },
    {
        id: 3,
        timestamp: '2024-02-26 12:45:30',
        agentId: 3,
        agentName: 'Ahmad Rizki',
        agentCode: 'AGT003',
        propertyId: 3,
        propertyName: 'Rumah Type 36 - Blok C',
        phoneNumber: '6281234567892',
        device: 'Mobile',
        browser: 'Safari',
        ipAddress: '192.168.1.102',
        status: 'interested',
        notes: 'Sangat tertarik, ingin diskusi KPR',
        followUpDate: null,
        location: 'Surabaya, Indonesia'
    },
    {
        id: 4,
        timestamp: '2024-02-26 11:20:15',
        agentId: 1,
        agentName: 'Budi Santoso',
        agentCode: 'AGT001',
        propertyId: 1,
        propertyName: 'Rumah Type 45 - Blok A',
        phoneNumber: '6281234567893',
        device: 'Mobile',
        browser: 'Chrome Mobile',
        ipAddress: '192.168.1.103',
        status: 'not-interested',
        notes: 'Lokasi terlalu jauh dari kantor',
        followUpDate: null,
        location: 'Jakarta, Indonesia'
    },
    {
        id: 5,
        timestamp: '2024-02-26 10:05:45',
        agentId: 4,
        agentName: 'Dewi Lestari',
        agentCode: 'AGT004',
        propertyId: 4,
        propertyName: 'Rumah Type 70 - Blok D',
        phoneNumber: '6281234567894',
        device: 'Desktop',
        browser: 'Firefox',
        ipAddress: '192.168.1.104',
        status: 'closed',
        notes: 'Sudah closing, pembayaran DP minggu depan',
        followUpDate: null,
        location: 'Semarang, Indonesia'
    },
    {
        id: 6,
        timestamp: '2024-02-26 09:30:20',
        agentId: 5,
        agentName: 'Rudi Hartono',
        agentCode: 'AGT005',
        propertyId: 2,
        propertyName: 'Rumah Type 60 - Blok B',
        phoneNumber: '6281234567895',
        device: 'Mobile',
        browser: 'Chrome Mobile',
        ipAddress: '192.168.1.105',
        status: 'new',
        notes: '',
        followUpDate: null,
        location: 'Yogyakarta, Indonesia'
    }
];

let agents = [];
let properties = [];
let currentPage = 1;
let itemsPerPage = 10;
let filteredClicks = [];

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadAgentsForFilter();
    loadPropertiesForFilter();
    updateStats();
    filterClicks();
});

// Load Agents for Filter
function loadAgentsForFilter() {
    const filterAgent = document.getElementById('filterAgent');
    agents = [
        { id: 1, name: 'Budi Santoso' },
        { id: 2, name: 'Siti Nurhaliza' },
        { id: 3, name: 'Ahmad Rizki' },
        { id: 4, name: 'Dewi Lestari' },
        { id: 5, name: 'Rudi Hartono' }
    ];
    
    agents.forEach(agent => {
        const option = document.createElement('option');
        option.value = agent.id;
        option.textContent = agent.name;
        filterAgent.appendChild(option);
    });
}

// Load Properties for Filter
function loadPropertiesForFilter() {
    const filterProperty = document.getElementById('filterProperty');
    properties = [
        { id: 1, name: 'Rumah Type 45 - Blok A' },
        { id: 2, name: 'Rumah Type 60 - Blok B' },
        { id: 3, name: 'Rumah Type 36 - Blok C' },
        { id: 4, name: 'Rumah Type 70 - Blok D' }
    ];
    
    properties.forEach(property => {
        const option = document.createElement('option');
        option.value = property.id;
        option.textContent = property.name;
        filterProperty.appendChild(option);
    });
}

// Update Stats
function updateStats() {
    const today = new Date().toISOString().split('T')[0];
    const todayClicks = clicks.filter(c => c.timestamp.startsWith(today));
    
    document.getElementById('totalClicksToday').textContent = todayClicks.length;
    document.getElementById('totalPending').textContent = clicks.filter(c => c.status === 'new').length;
    document.getElementById('totalFollowUp').textContent = clicks.filter(c => c.status === 'follow-up').length;
    document.getElementById('totalConverted').textContent = clicks.filter(c => c.status === 'interested').length;
}

// Filter Clicks
function filterClicks() {
    const dateFilter = document.getElementById('filterDate').value;
    const agentFilter = document.getElementById('filterAgent').value;
    const propertyFilter = document.getElementById('filterProperty').value;
    const statusFilter = document.getElementById('filterStatus').value;
    
    filteredClicks = clicks.filter(click => {
        // Date filter
        let dateMatch = true;
        if (dateFilter !== 'all') {
            const clickDate = new Date(click.timestamp);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (dateFilter === 'today') {
                dateMatch = clickDate >= today;
            } else if (dateFilter === 'yesterday') {
                const yesterday = new Date(today);
                yesterday.setDate(yesterday.getDate() - 1);
                dateMatch = clickDate >= yesterday && clickDate < today;
            } else if (dateFilter === 'week') {
                const weekAgo = new Date(today);
                weekAgo.setDate(weekAgo.getDate() - 7);
                dateMatch = clickDate >= weekAgo;
            } else if (dateFilter === 'month') {
                const monthAgo = new Date(today);
                monthAgo.setDate(monthAgo.getDate() - 30);
                dateMatch = clickDate >= monthAgo;
            }
        }
        
        // Agent filter
        const agentMatch = agentFilter === 'all' || click.agentId == agentFilter;
        
        // Property filter
        const propertyMatch = propertyFilter === 'all' || click.propertyId == propertyFilter;
        
        // Status filter
        const statusMatch = statusFilter === 'all' || click.status === statusFilter;
        
        return dateMatch && agentMatch && propertyMatch && statusMatch;
    });
    
    currentPage = 1;
    loadClicks();
}

// Search Clicks
function searchClickData() {
    const searchTerm = document.getElementById('searchClicks').value.toLowerCase();
    
    if (!searchTerm) {
        filterClicks();
        return;
    }
    
    filteredClicks = clicks.filter(click => {
        return click.phoneNumber.toLowerCase().includes(searchTerm) ||
               click.agentName.toLowerCase().includes(searchTerm) ||
               click.propertyName.toLowerCase().includes(searchTerm) ||
               click.location.toLowerCase().includes(searchTerm);
    });
    
    currentPage = 1;
    loadClicks();
}

// Load Clicks
function loadClicks() {
    const tbody = document.getElementById('clicksTableBody');
    tbody.innerHTML = '';
    
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const paginatedClicks = filteredClicks.slice(startIndex, endIndex);
    
    if (paginatedClicks.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; padding: 2rem; color: #64748b;">Tidak ada data klik</td></tr>';
        return;
    }
    
    paginatedClicks.forEach(click => {
        const tr = document.createElement('tr');
        
        const statusBadge = getStatusBadge(click.status);
        const deviceIcon = click.device === 'Mobile' ? 'fa-mobile-alt' : 'fa-desktop';
        
        tr.innerHTML = `
            <td>
                <div>${formatDateTime(click.timestamp)}</div>
                <div style="color: #64748b; font-size: 0.875rem;">${formatTimeAgo(click.timestamp)}</div>
            </td>
            <td>
                <strong>${click.agentName}</strong>
                <div style="color: #64748b; font-size: 0.875rem;">${click.agentCode}</div>
            </td>
            <td>${click.propertyName}</td>
            <td>
                <a href="https://wa.me/${click.phoneNumber}" target="_blank" style="color: #10b981;">
                    <i class="fab fa-whatsapp"></i> ${formatPhoneNumber(click.phoneNumber)}
                </a>
            </td>
            <td>
                <i class="fas ${deviceIcon}"></i> ${click.device}
                <div style="color: #64748b; font-size: 0.875rem;">${click.browser}</div>
            </td>
            <td>${statusBadge}</td>
            <td>
                <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    ${click.notes || '-'}
                </div>
            </td>
            <td>
                <div style="display: flex; gap: 0.5rem;">
                    <button class="btn-icon" onclick="viewDetails(${click.id})" title="Lihat Detail">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-icon" onclick="openStatusModal(${click.id})" title="Update Status">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });
    
    renderPagination();
}

// Get Status Badge
function getStatusBadge(status) {
    const badges = {
        'new': '<span class="badge info">Baru</span>',
        'follow-up': '<span class="badge warning">Follow Up</span>',
        'interested': '<span class="badge success">Tertarik</span>',
        'not-interested': '<span class="badge danger">Tidak Tertarik</span>',
        'closed': '<span class="badge" style="background: #8b5cf6; color: white;">Closing</span>'
    };
    return badges[status] || '<span class="badge">-</span>';
}

// Format Phone Number
function formatPhoneNumber(phone) {
    return phone.replace('62', '0').replace(/(\d{4})(\d{4})(\d+)/, '$1-$2-$3');
}

// Format DateTime
function formatDateTime(datetime) {
    const date = new Date(datetime);
    return date.toLocaleString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Format Time Ago
function formatTimeAgo(datetime) {
    const now = new Date();
    const date = new Date(datetime);
    const diff = Math.floor((now - date) / 1000); // in seconds
    
    if (diff < 60) return 'Baru saja';
    if (diff < 3600) return `${Math.floor(diff / 60)} menit lalu`;
    if (diff < 86400) return `${Math.floor(diff / 3600)} jam lalu`;
    return `${Math.floor(diff / 86400)} hari lalu`;
}

// Render Pagination
function renderPagination() {
    const pagination = document.getElementById('pagination');
    const totalPages = Math.ceil(filteredClicks.length / itemsPerPage);
    
    if (totalPages <= 1) {
        pagination.innerHTML = '';
        return;
    }
    
    let html = '<div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 1.5rem;">';
    
    // Previous button
    html += `<button class="btn btn-secondary" ${currentPage === 1 ? 'disabled' : ''} onclick="changePage(${currentPage - 1})">
        <i class="fas fa-chevron-left"></i>
    </button>`;
    
    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
            html += `<button class="btn ${i === currentPage ? 'btn-primary' : 'btn-secondary'}" onclick="changePage(${i})">${i}</button>`;
        } else if (i === currentPage - 2 || i === currentPage + 2) {
            html += '<span style="padding: 0.625rem;">...</span>';
        }
    }
    
    // Next button
    html += `<button class="btn btn-secondary" ${currentPage === totalPages ? 'disabled' : ''} onclick="changePage(${currentPage + 1})">
        <i class="fas fa-chevron-right"></i>
    </button>`;
    
    html += '</div>';
    pagination.innerHTML = html;
}

// Change Page
function changePage(page) {
    currentPage = page;
    loadClicks();
}

// View Details
function viewDetails(id) {
    const click = clicks.find(c => c.id === id);
    if (!click) return;
    
    const detailsDiv = document.getElementById('clickDetails');
    detailsDiv.innerHTML = `
        <div class="details-grid">
            <div class="detail-item">
                <label>Waktu Klik</label>
                <p>${formatDateTime(click.timestamp)}</p>
            </div>
            <div class="detail-item">
                <label>Agent</label>
                <p>${click.agentName} (${click.agentCode})</p>
            </div>
            <div class="detail-item">
                <label>Properti</label>
                <p>${click.propertyName}</p>
            </div>
            <div class="detail-item">
                <label>No. WhatsApp</label>
                <p><a href="https://wa.me/${click.phoneNumber}" target="_blank" style="color: #10b981;">
                    <i class="fab fa-whatsapp"></i> ${formatPhoneNumber(click.phoneNumber)}
                </a></p>
            </div>
            <div class="detail-item">
                <label>Device</label>
                <p>${click.device} - ${click.browser}</p>
            </div>
            <div class="detail-item">
                <label>IP Address</label>
                <p>${click.ipAddress}</p>
            </div>
            <div class="detail-item">
                <label>Lokasi</label>
                <p>${click.location}</p>
            </div>
            <div class="detail-item">
                <label>Status Lead</label>
                <p>${getStatusBadge(click.status)}</p>
            </div>
            ${click.followUpDate ? `
            <div class="detail-item">
                <label>Jadwal Follow Up</label>
                <p>${formatDateTime(click.followUpDate)}</p>
            </div>` : ''}
            <div class="detail-item" style="grid-column: 1 / -1;">
                <label>Catatan</label>
                <p>${click.notes || 'Tidak ada catatan'}</p>
            </div>
        </div>
    `;
    
    document.getElementById('detailsModal').style.display = 'flex';
}

// Open Status Modal
function openStatusModal(id) {
    const click = clicks.find(c => c.id === id);
    if (!click) return;
    
    document.getElementById('clickId').value = id;
    document.getElementById('leadStatus').value = click.status;
    document.getElementById('leadNotes').value = click.notes || '';
    document.getElementById('followUpDate').value = click.followUpDate ? click.followUpDate.replace(' ', 'T') : '';
    
    document.getElementById('statusModal').style.display = 'flex';
}

// Close Modals
function closeStatusModal() {
    document.getElementById('statusModal').style.display = 'none';
    document.getElementById('statusForm').reset();
}

function closeDetailsModal() {
    document.getElementById('detailsModal').style.display = 'none';
}

// Update Click Status
function updateClickStatus() {
    const id = parseInt(document.getElementById('clickId').value);
    const status = document.getElementById('leadStatus').value;
    const notes = document.getElementById('leadNotes').value;
    const followUpDate = document.getElementById('followUpDate').value;
    
    const clickIndex = clicks.findIndex(c => c.id === id);
    if (clickIndex === -1) return;
    
    clicks[clickIndex].status = status;
    clicks[clickIndex].notes = notes;
    clicks[clickIndex].followUpDate = followUpDate ? followUpDate.replace('T', ' ') : null;
    
    // In Laravel, you would make a PATCH request:
    // fetch(`/api/clicks/${id}/status`, {
    //     method: 'PATCH',
    //     headers: { 'Content-Type': 'application/json' },
    //     body: JSON.stringify({ status, notes, followUpDate })
    // });
    
    updateStats();
    filterClicks();
    closeStatusModal();
    alert('Status lead berhasil diupdate!');
}

// Export Clicks
function exportClicks() {
    // In Laravel, you would redirect to export route:
    // window.location.href = '/api/clicks/export?' + new URLSearchParams({
    //     date: document.getElementById('filterDate').value,
    //     agent: document.getElementById('filterAgent').value,
    //     property: document.getElementById('filterProperty').value,
    //     status: document.getElementById('filterStatus').value
    // });
    
    alert('Export data akan segera didownload...\n\nDi Laravel, ini akan generate file Excel/CSV');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const statusModal = document.getElementById('statusModal');
    const detailsModal = document.getElementById('detailsModal');
    
    if (event.target === statusModal) {
        closeStatusModal();
    }
    if (event.target === detailsModal) {
        closeDetailsModal();
    }
}
