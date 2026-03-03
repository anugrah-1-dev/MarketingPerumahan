// Mock Data
let closings = [
    {
        id: 1,
        date: '2024-02-25',
        agentId: 2,
        agentName: 'Siti Nurhaliza',
        agentCommission: 2.5,
        propertyId: 2,
        propertyName: 'Rumah Type 60 - Blok B',
        propertyPrice: 850000000,
        customerName: 'Bambang Wijaya',
        customerPhone: '081234567001',
        customerEmail: 'bambang@email.com',
        salePrice: 850000000,
        commission: 21250000,
        paymentStatus: 'dp',
        notes: 'DP 30%, sisa KPR Bank Mandiri',
        createdAt: '2024-02-25 14:30:00'
    },
    {
        id: 2,
        date: '2024-02-24',
        agentId: 3,
        agentName: 'Ahmad Rizki',
        agentCommission: 2.0,
        propertyId: 3,
        propertyName: 'Rumah Type 36 - Blok C',
        propertyPrice: 450000000,
        customerName: 'Susi Rahayu',
        customerPhone: '081234567002',
        customerEmail: 'susi@email.com',
        salePrice: 450000000,
        commission: 9000000,
        paymentStatus: 'installment',
        notes: 'Cicilan developer 24 bulan',
        createdAt: '2024-02-24 10:15:00'
    },
    {
        id: 3,
        date: '2024-02-20',
        agentId: 1,
        agentName: 'Budi Santoso',
        agentCommission: 2.5,
        propertyId: 1,
        propertyName: 'Rumah Type 45 - Blok A',
        propertyPrice: 650000000,
        customerName: 'Andi Setiawan',
        customerPhone: '081234567003',
        customerEmail: 'andi@email.com',
        salePrice: 650000000,
        commission: 16250000,
        paymentStatus: 'paid-off',
        notes: 'Pembayaran cash bertahap, sudah lunas',
        createdAt: '2024-02-20 16:45:00'
    },
    {
        id: 4,
        date: '2024-02-18',
        agentId: 4,
        agentName: 'Dewi Lestari',
        agentCommission: 2.0,
        propertyId: 4,
        propertyName: 'Rumah Type 70 - Blok D',
        propertyPrice: 1200000000,
        customerName: 'Hendro Kusuma',
        customerPhone: '081234567004',
        customerEmail: 'hendro@email.com',
        salePrice: 1200000000,
        commission: 24000000,
        paymentStatus: 'dp',
        notes: 'DP 40%, KPR BRI',
        createdAt: '2024-02-18 09:20:00'
    },
    {
        id: 5,
        date: '2024-02-15',
        agentId: 1,
        agentName: 'Budi Santoso',
        agentCommission: 2.5,
        propertyId: 1,
        propertyName: 'Rumah Type 45 - Blok A',
        propertyPrice: 650000000,
        customerName: 'Lisa Permata',
        customerPhone: '081234567005',
        customerEmail: 'lisa@email.com',
        salePrice: 650000000,
        commission: 16250000,
        paymentStatus: 'installment',
        notes: 'Cicilan in-house 36 bulan',
        createdAt: '2024-02-15 13:10:00'
    }
];

let agents = [];
let properties = [];
let editingClosingId = null;
let filteredClosings = [];

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadAgentsForSelect();
    loadPropertiesForSelect();
    loadAgentsForFilter();
    updateStats();
    filterClosings();
    
    // Set default date to today
    document.getElementById('closingDate').valueAsDate = new Date();
});

// Load Agents for Select
function loadAgentsForSelect() {
    const select = document.getElementById('closingAgent');
    agents = [
        { id: 1, name: 'Budi Santoso', commission: 2.5 },
        { id: 2, name: 'Siti Nurhaliza', commission: 2.5 },
        { id: 3, name: 'Ahmad Rizki', commission: 2.0 },
        { id: 4, name: 'Dewi Lestari', commission: 2.0 },
        { id: 5, name: 'Rudi Hartono', commission: 1.5 }
    ];
    
    agents.forEach(agent => {
        const option = document.createElement('option');
        option.value = agent.id;
        option.textContent = `${agent.name} (${agent.commission}%)`;
        option.dataset.commission = agent.commission;
        select.appendChild(option);
    });
}

// Load Properties for Select
function loadPropertiesForSelect() {
    const select = document.getElementById('closingProperty');
    properties = [
        { id: 1, name: 'Rumah Type 45 - Blok A', price: 650000000 },
        { id: 2, name: 'Rumah Type 60 - Blok B', price: 850000000 },
        { id: 3, name: 'Rumah Type 36 - Blok C', price: 450000000 },
        { id: 4, name: 'Rumah Type 70 - Blok D', price: 1200000000 }
    ];
    
    properties.forEach(property => {
        const option = document.createElement('option');
        option.value = property.id;
        option.textContent = `${property.name} - ${formatCurrency(property.price)}`;
        option.dataset.price = property.price;
        select.appendChild(option);
    });
}

// Load Agents for Filter
function loadAgentsForFilter() {
    const select = document.getElementById('filterAgent');
    agents.forEach(agent => {
        const option = document.createElement('option');
        option.value = agent.id;
        option.textContent = agent.name;
        select.appendChild(option);
    });
}

// Update Stats
function updateStats() {
    const totalClosing = closings.length;
    const totalSales = closings.reduce((sum, c) => sum + c.salePrice, 0);
    const totalCommission = closings.reduce((sum, c) => sum + c.commission, 0);
    
    const currentMonth = new Date().getMonth();
    const currentYear = new Date().getFullYear();
    const closingThisMonth = closings.filter(c => {
        const date = new Date(c.date);
        return date.getMonth() === currentMonth && date.getFullYear() === currentYear;
    }).length;
    
    document.getElementById('totalClosing').textContent = totalClosing;
    document.getElementById('totalSales').textContent = formatCurrency(totalSales);
    document.getElementById('totalCommission').textContent = formatCurrency(totalCommission);
    document.getElementById('closingThisMonth').textContent = closingThisMonth;
}

// Format Currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
}

// Filter Closings
function filterClosings() {
    const monthFilter = document.getElementById('filterMonth').value;
    const agentFilter = document.getElementById('filterAgent').value;
    const paymentFilter = document.getElementById('filterPaymentStatus').value;
    
    filteredClosings = closings.filter(closing => {
        // Month filter
        let monthMatch = true;
        if (monthFilter !== 'all') {
            const closingDate = new Date(closing.date);
            const now = new Date();
            
            if (monthFilter === 'current') {
                monthMatch = closingDate.getMonth() === now.getMonth() && 
                            closingDate.getFullYear() === now.getFullYear();
            } else if (monthFilter === 'last') {
                const lastMonth = new Date(now.getFullYear(), now.getMonth() - 1);
                monthMatch = closingDate.getMonth() === lastMonth.getMonth() && 
                            closingDate.getFullYear() === lastMonth.getFullYear();
            }
        }
        
        // Agent filter
        const agentMatch = agentFilter === 'all' || closing.agentId == agentFilter;
        
        // Payment status filter
        const paymentMatch = paymentFilter === 'all' || closing.paymentStatus === paymentFilter;
        
        return monthMatch && agentMatch && paymentMatch;
    });
    
    loadClosings();
}

// Load Closings
function loadClosings() {
    const tbody = document.getElementById('closingsTableBody');
    tbody.innerHTML = '';
    
    if (filteredClosings.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; padding: 2rem; color: #64748b;">Tidak ada data closing</td></tr>';
        return;
    }
    
    // Sort by date descending
    const sortedClosings = [...filteredClosings].sort((a, b) => new Date(b.date) - new Date(a.date));
    
    sortedClosings.forEach(closing => {
        const tr = document.createElement('tr');
        
        const paymentBadge = getPaymentBadge(closing.paymentStatus);
        
        tr.innerHTML = `
            <td>
                <div>${formatDate(closing.date)}</div>
                <div style="color: #64748b; font-size: 0.875rem;">${formatTimeAgo(closing.createdAt)}</div>
            </td>
            <td>
                <strong>${closing.agentName}</strong>
                <div style="color: #64748b; font-size: 0.875rem;">Komisi: ${closing.agentCommission}%</div>
            </td>
            <td>
                <div><strong>${closing.customerName}</strong></div>
                <div style="color: #64748b; font-size: 0.875rem;">
                    <i class="fab fa-whatsapp"></i> ${formatPhoneNumber(closing.customerPhone)}
                </div>
            </td>
            <td>${closing.propertyName}</td>
            <td><strong>${formatCurrency(closing.salePrice)}</strong></td>
            <td style="color: #10b981; font-weight: 600;">${formatCurrency(closing.commission)}</td>
            <td>${paymentBadge}</td>
            <td>
                <div style="display: flex; gap: 0.5rem;">
                    <button class="btn-icon" onclick="viewDetails(${closing.id})" title="Lihat Detail">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-icon" onclick="editClosing(${closing.id})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-icon danger" onclick="deleteClosing(${closing.id})" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// Get Payment Badge
function getPaymentBadge(status) {
    const badges = {
        'dp': '<span class="badge warning">DP</span>',
        'installment': '<span class="badge info">Cicilan</span>',
        'paid-off': '<span class="badge success">Lunas</span>'
    };
    return badges[status] || '<span class="badge">-</span>';
}

// Format Date
function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
}

// Format Phone Number
function formatPhoneNumber(phone) {
    return phone.replace(/(\d{4})(\d{4})(\d+)/, '$1-$2-$3');
}

// Format Time Ago
function formatTimeAgo(datetime) {
    const now = new Date();
    const date = new Date(datetime);
    const diff = Math.floor((now - date) / 1000);
    
    if (diff < 60) return 'Baru saja';
    if (diff < 3600) return `${Math.floor(diff / 60)} menit lalu`;
    if (diff < 86400) return `${Math.floor(diff / 3600)} jam lalu`;
    return `${Math.floor(diff / 86400)} hari lalu`;
}

// Open Add Closing Modal
function openAddClosingModal() {
    editingClosingId = null;
    document.getElementById('modalTitle').textContent = 'Input Closing Baru';
    document.getElementById('closingForm').reset();
    document.getElementById('closingId').value = '';
    document.getElementById('closingDate').valueAsDate = new Date();
    document.getElementById('commissionPreview').style.display = 'none';
    document.getElementById('closingModal').style.display = 'flex';
}

// Edit Closing
function editClosing(id) {
    const closing = closings.find(c => c.id === id);
    if (!closing) return;
    
    editingClosingId = id;
    document.getElementById('modalTitle').textContent = 'Edit Closing';
    document.getElementById('closingId').value = closing.id;
    document.getElementById('closingDate').value = closing.date;
    document.getElementById('closingAgent').value = closing.agentId;
    document.getElementById('closingProperty').value = closing.propertyId;
    document.getElementById('customerName').value = closing.customerName;
    document.getElementById('customerPhone').value = closing.customerPhone;
    document.getElementById('customerEmail').value = closing.customerEmail || '';
    document.getElementById('salePrice').value = closing.salePrice;
    document.getElementById('paymentStatus').value = closing.paymentStatus;
    document.getElementById('closingNotes').value = closing.notes || '';
    
    updateCommissionPreview();
    document.getElementById('closingModal').style.display = 'flex';
}

// Update Commission Preview
function updateCommissionPreview() {
    const agentSelect = document.getElementById('closingAgent');
    const salePrice = parseFloat(document.getElementById('salePrice').value) || 0;
    
    if (!agentSelect.value || salePrice === 0) {
        document.getElementById('commissionPreview').style.display = 'none';
        return;
    }
    
    const selectedOption = agentSelect.options[agentSelect.selectedIndex];
    const commissionRate = parseFloat(selectedOption.dataset.commission);
    const commission = salePrice * (commissionRate / 100);
    
    document.getElementById('previewPrice').textContent = formatCurrency(salePrice);
    document.getElementById('previewRate').textContent = commissionRate + '%';
    document.getElementById('previewCommission').textContent = formatCurrency(commission);
    document.getElementById('commissionPreview').style.display = 'block';
}

// Close Modals
function closeClosingModal() {
    document.getElementById('closingModal').style.display = 'none';
    document.getElementById('closingForm').reset();
    editingClosingId = null;
}

function closeDetailsModal() {
    document.getElementById('detailsModal').style.display = 'none';
}

// Save Closing
function saveClosing() {
    const date = document.getElementById('closingDate').value;
    const agentId = parseInt(document.getElementById('closingAgent').value);
    const propertyId = parseInt(document.getElementById('closingProperty').value);
    const customerName = document.getElementById('customerName').value;
    const customerPhone = document.getElementById('customerPhone').value;
    const customerEmail = document.getElementById('customerEmail').value;
    const salePrice = parseFloat(document.getElementById('salePrice').value);
    const paymentStatus = document.getElementById('paymentStatus').value;
    const notes = document.getElementById('closingNotes').value;
    
    if (!date || !agentId || !propertyId || !customerName || !customerPhone || !salePrice) {
        alert('Mohon lengkapi semua field yang wajib diisi!');
        return;
    }
    
    const agent = agents.find(a => a.id === agentId);
    const property = properties.find(p => p.id === propertyId);
    const commission = salePrice * (agent.commission / 100);
    
    if (editingClosingId) {
        // Update existing closing
        const index = closings.findIndex(c => c.id === editingClosingId);
        if (index !== -1) {
            closings[index] = {
                ...closings[index],
                date,
                agentId,
                agentName: agent.name,
                agentCommission: agent.commission,
                propertyId,
                propertyName: property.name,
                propertyPrice: property.price,
                customerName,
                customerPhone,
                customerEmail,
                salePrice,
                commission,
                paymentStatus,
                notes
            };
        }
        
        alert('Data closing berhasil diupdate!');
    } else {
        // Add new closing
        const newClosing = {
            id: closings.length + 1,
            date,
            agentId,
            agentName: agent.name,
            agentCommission: agent.commission,
            propertyId,
            propertyName: property.name,
            propertyPrice: property.price,
            customerName,
            customerPhone,
            customerEmail,
            salePrice,
            commission,
            paymentStatus,
            notes,
            createdAt: new Date().toISOString().replace('T', ' ').substring(0, 19)
        };
        closings.push(newClosing);
        
        alert('Closing berhasil ditambahkan!\n\nKomisi: ' + formatCurrency(commission));
    }
    
    updateStats();
    filterClosings();
    closeClosingModal();
}

// Delete Closing
function deleteClosing(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data closing ini?')) {
        closings = closings.filter(c => c.id !== id);
        updateStats();
        filterClosings();
        alert('Data closing berhasil dihapus!');
    }
}

// View Details
function viewDetails(id) {
    const closing = closings.find(c => c.id === id);
    if (!closing) return;
    
    const detailsDiv = document.getElementById('closingDetails');
    detailsDiv.innerHTML = `
        <div class="details-grid">
            <div class="detail-item">
                <label>Tanggal Closing</label>
                <p>${formatDate(closing.date)}</p>
            </div>
            <div class="detail-item">
                <label>Agent</label>
                <p>${closing.agentName} (Komisi: ${closing.agentCommission}%)</p>
            </div>
            <div class="detail-item">
                <label>Nama Customer</label>
                <p>${closing.customerName}</p>
            </div>
            <div class="detail-item">
                <label>Telepon Customer</label>
                <p><a href="https://wa.me/${closing.customerPhone}" target="_blank" style="color: #10b981;">
                    <i class="fab fa-whatsapp"></i> ${formatPhoneNumber(closing.customerPhone)}
                </a></p>
            </div>
            ${closing.customerEmail ? `
            <div class="detail-item">
                <label>Email Customer</label>
                <p>${closing.customerEmail}</p>
            </div>` : ''}
            <div class="detail-item">
                <label>Properti</label>
                <p>${closing.propertyName}</p>
            </div>
            <div class="detail-item">
                <label>Harga Jual</label>
                <p><strong style="font-size: 1.125rem;">${formatCurrency(closing.salePrice)}</strong></p>
            </div>
            <div class="detail-item">
                <label>Komisi Agent</label>
                <p><strong style="font-size: 1.125rem; color: #10b981;">${formatCurrency(closing.commission)}</strong></p>
            </div>
            <div class="detail-item">
                <label>Status Pembayaran</label>
                <p>${getPaymentBadge(closing.paymentStatus)}</p>
            </div>
            <div class="detail-item" style="grid-column: 1 / -1;">
                <label>Catatan</label>
                <p>${closing.notes || 'Tidak ada catatan'}</p>
            </div>
        </div>
    `;
    
    document.getElementById('detailsModal').style.display = 'flex';
}

// Print Closing
function printClosing() {
    alert('Fungsi cetak akan membuka halaman print preview');
    // In Laravel, redirect to print page
    // window.open('/closings/' + id + '/print', '_blank');
}

// Export Closings
function exportClosings() {
    alert('Export data closing akan segera didownload...\n\nDi Laravel, ini akan generate file Excel');
    // In Laravel:
    // window.location.href = '/api/closings/export?' + new URLSearchParams({...});
}

// Close modal when clicking outside
window.onclick = function(event) {
    const closingModal = document.getElementById('closingModal');
    const detailsModal = document.getElementById('detailsModal');
    
    if (event.target === closingModal) {
        closeClosingModal();
    }
    if (event.target === detailsModal) {
        closeDetailsModal();
    }
}

// Auto-fill sale price when property is selected
document.getElementById('closingProperty').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const price = selectedOption.dataset.price;
    
    if (price && !document.getElementById('salePrice').value) {
        document.getElementById('salePrice').value = price;
        updateCommissionPreview();
    }
});
