// Mock Agents Data
let agents = [
    {
        id: 1,
        name: 'Budi Santoso',
        email: 'budi@example.com',
        phone: '081234567890',
        affiliateCode: 'AGT001',
        clicks: 156,
        closings: 5,
        commission: 2.5,
        status: 'active'
    },
    {
        id: 2,
        name: 'Siti Nurhaliza',
        email: 'siti@example.com',
        phone: '081234567891',
        affiliateCode: 'AGT002',
        clicks: 142,
        closings: 4,
        commission: 2.5,
        status: 'active'
    },
    {
        id: 3,
        name: 'Ahmad Rizki',
        email: 'ahmad@example.com',
        phone: '081234567892',
        affiliateCode: 'AGT003',
        clicks: 128,
        closings: 4,
        commission: 2.0,
        status: 'active'
    },
    {
        id: 4,
        name: 'Dewi Lestari',
        email: 'dewi@example.com',
        phone: '081234567893',
        affiliateCode: 'AGT004',
        clicks: 115,
        closings: 3,
        commission: 2.0,
        status: 'inactive'
    },
    {
        id: 5,
        name: 'Rudi Hartono',
        email: 'rudi@example.com',
        phone: '081234567894',
        affiliateCode: 'AGT005',
        clicks: 98,
        closings: 3,
        commission: 1.5,
        status: 'active'
    }
];

let editingAgentId = null;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadAgents();
});

// Load Agents
function loadAgents() {
    const tbody = document.getElementById('agentsTableBody');
    tbody.innerHTML = '';
    
    agents.forEach(agent => {
        const tr = document.createElement('tr');
        
        const statusBadge = agent.status === 'active' 
            ? '<span class="badge success">Aktif</span>' 
            : '<span class="badge danger">Nonaktif</span>';
        
        const affiliateLink = `${window.location.origin}/property?ref=${agent.affiliateCode}`;
        
        tr.innerHTML = `
            <td>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div class="agent-avatar">${getInitials(agent.name)}</div>
                    <div>
                        <strong>${agent.name}</strong>
                    </div>
                </div>
            </td>
            <td>
                <div>${agent.email}</div>
                <div style="color: #64748b; font-size: 0.875rem;">${agent.phone}</div>
            </td>
            <td>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <code style="background: #f1f5f9; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.875rem;">
                        ${agent.affiliateCode}
                    </code>
                    <button class="btn-icon" onclick="copyAffiliateLink('${affiliateLink}')" title="Copy link">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </td>
            <td>${agent.clicks}</td>
            <td>${agent.closings}</td>
            <td>${statusBadge}</td>
            <td>
                <div style="display: flex; gap: 0.5rem;">
                    <button class="btn-icon" onclick="editAgent(${agent.id})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-icon" onclick="toggleAgentStatus(${agent.id})" title="Toggle Status">
                        <i class="fas fa-${agent.status === 'active' ? 'ban' : 'check'}"></i>
                    </button>
                    <button class="btn-icon danger" onclick="deleteAgent(${agent.id})" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// Get Initials
function getInitials(name) {
    return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
}

// Search Agents
function searchAgents() {
    const searchTerm = document.getElementById('searchAgent').value.toLowerCase();
    const tbody = document.getElementById('agentsTableBody');
    const rows = tbody.getElementsByTagName('tr');
    
    Array.from(rows).forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
}

// Open Add Agent Modal
function openAddAgentModal() {
    editingAgentId = null;
    document.getElementById('modalTitle').textContent = 'Tambah Agent';
    document.getElementById('agentForm').reset();
    document.getElementById('agentId').value = '';
    document.getElementById('agentModal').style.display = 'flex';
}

// Edit Agent
function editAgent(id) {
    const agent = agents.find(a => a.id === id);
    if (!agent) return;
    
    editingAgentId = id;
    document.getElementById('modalTitle').textContent = 'Edit Agent';
    document.getElementById('agentId').value = agent.id;
    document.getElementById('agentName').value = agent.name;
    document.getElementById('agentEmail').value = agent.email;
    document.getElementById('agentPhone').value = agent.phone;
    document.getElementById('agentCommission').value = agent.commission;
    document.getElementById('agentModal').style.display = 'flex';
}

// Close Modal
function closeAgentModal() {
    document.getElementById('agentModal').style.display = 'none';
    document.getElementById('agentForm').reset();
    editingAgentId = null;
}

// Save Agent
function saveAgent() {
    const name = document.getElementById('agentName').value;
    const email = document.getElementById('agentEmail').value;
    const phone = document.getElementById('agentPhone').value;
    const commission = parseFloat(document.getElementById('agentCommission').value);
    
    if (!name || !email || !phone || !commission) {
        alert('Mohon lengkapi semua field!');
        return;
    }
    
    if (editingAgentId) {
        // Update existing agent
        const index = agents.findIndex(a => a.id === editingAgentId);
        if (index !== -1) {
            agents[index].name = name;
            agents[index].email = email;
            agents[index].phone = phone;
            agents[index].commission = commission;
        }
        
        // In Laravel, you would make a PUT/PATCH request:
        // fetch(`/api/agents/${editingAgentId}`, {
        //     method: 'PUT',
        //     headers: { 'Content-Type': 'application/json' },
        //     body: JSON.stringify({ name, email, phone, commission })
        // });
        
        alert('Agent berhasil diupdate!');
    } else {
        // Add new agent
        const newAgent = {
            id: agents.length + 1,
            name,
            email,
            phone,
            affiliateCode: `AGT${String(agents.length + 1).padStart(3, '0')}`,
            clicks: 0,
            closings: 0,
            commission,
            status: 'active'
        };
        agents.push(newAgent);
        
        // In Laravel, you would make a POST request:
        // fetch('/api/agents', {
        //     method: 'POST',
        //     headers: { 'Content-Type': 'application/json' },
        //     body: JSON.stringify({ name, email, phone, commission })
        // });
        
        alert('Agent berhasil ditambahkan!');
    }
    
    loadAgents();
    closeAgentModal();
}

// Toggle Agent Status
function toggleAgentStatus(id) {
    const agent = agents.find(a => a.id === id);
    if (!agent) return;
    
    const newStatus = agent.status === 'active' ? 'inactive' : 'active';
    const confirmMsg = `Apakah Anda yakin ingin ${newStatus === 'active' ? 'mengaktifkan' : 'menonaktifkan'} agent ini?`;
    
    if (confirm(confirmMsg)) {
        agent.status = newStatus;
        
        // In Laravel, you would make a PATCH request:
        // fetch(`/api/agents/${id}/status`, {
        //     method: 'PATCH',
        //     headers: { 'Content-Type': 'application/json' },
        //     body: JSON.stringify({ status: newStatus })
        // });
        
        loadAgents();
        alert(`Status agent berhasil diubah menjadi ${newStatus === 'active' ? 'aktif' : 'nonaktif'}!`);
    }
}

// Delete Agent
function deleteAgent(id) {
    if (confirm('Apakah Anda yakin ingin menghapus agent ini? Data tidak dapat dikembalikan!')) {
        agents = agents.filter(a => a.id !== id);
        
        // In Laravel, you would make a DELETE request:
        // fetch(`/api/agents/${id}`, {
        //     method: 'DELETE'
        // });
        
        loadAgents();
        alert('Agent berhasil dihapus!');
    }
}

// Copy Affiliate Link
function copyAffiliateLink(link) {
    navigator.clipboard.writeText(link).then(() => {
        // Show temporary success message
        const btn = event.target.closest('button');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.style.color = '#10b981';
        
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.style.color = '';
        }, 2000);
    }).catch(err => {
        alert('Gagal menyalin link: ' + err);
    });
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('agentModal');
    if (event.target === modal) {
        closeAgentModal();
    }
}
