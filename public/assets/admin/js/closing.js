// -- Closing Management � Real API version ---------------------------------
const closingPanel = window.CLOSING_PANEL ?? "admin";
const closingBasePath = "/" + closingPanel + "/closing";
const agentsBasePath = "/" + closingPanel + "/agents";

let closings = [];
let agents = [];
let properties = [];
let editingClosingId = null;
let filteredClosings = [];

// -- Helpers ----------------------------------------------------------------

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content ?? "";
}

function normalizeClosing(c) {
    return {
        id: c.id,
        date: c.tanggal_closing,
        agentId: c.agent_id,
        agentName: c.agent_name ?? c.agent?.nama ?? "(tidak ada)",
        agentCommission: c.komisi_persen,
        propertyId: c.tipe_rumah_id,
        propertyName: c.tipe_rumah_nama ?? c.tipe_rumah?.nama_tipe ?? "-",
        propertyPrice: c.tipe_rumah?.harga ?? 0,
        customerName: c.customer_name,
        customerPhone: c.customer_phone,
        customerEmail: "",
        salePrice: c.harga_jual,
        commission: c.komisi_nominal,
        paymentStatus: c.payment_status,
        notes: c.catatan ?? "",
        createdAt: c.created_at,
    };
}

// -- Initialize -------------------------------------------------------------

document.addEventListener("DOMContentLoaded", function () {
    loadPropertiesForSelect();
    loadAgentsForSelect();
    document.getElementById("closingDate").valueAsDate = new Date();

    document
        .getElementById("closingProperty")
        .addEventListener("change", function () {
            const price = this.options[this.selectedIndex]?.dataset?.price;
            if (price && !document.getElementById("salePrice").value) {
                document.getElementById("salePrice").value = price;
                updateCommissionPreview();
            }
        });
});

// -- Load Properties --------------------------------------------------------

function loadPropertiesForSelect() {
    const data = window.TIPE_RUMAH ?? [];
    properties = data.map((t) => ({
        id: t.id,
        name: t.nama_tipe,
        price: t.harga,
    }));

    const select = document.getElementById("closingProperty");
    while (select.options.length > 1) select.remove(1);
    properties.forEach((p) => {
        const opt = document.createElement("option");
        opt.value = p.id;
        opt.textContent = p.name + " � " + formatCurrency(p.price);
        opt.dataset.price = p.price;
        select.appendChild(opt);
    });
}

// -- Load Agents from API ---------------------------------------------------

async function loadAgentsForSelect() {
    try {
        const res = await fetch(agentsBasePath, {
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        });
        if (!res.ok) throw new Error("HTTP " + res.status);
        const data = await res.json();
        const list = Array.isArray(data) ? data : (data.data ?? []);
        agents = list.map((a) => ({
            id: a.id,
            name: a.nama,
            commission: parseFloat(a.commission),
        }));
    } catch (e) {
        console.error("Gagal load agents:", e);
        agents = [];
    }

    const agentSel = document.getElementById("closingAgent");
    while (agentSel.options.length > 1) agentSel.remove(1);
    agents.forEach((a) => {
        const opt = document.createElement("option");
        opt.value = a.id;
        opt.textContent = a.name + " (" + a.commission + "%)";
        opt.dataset.commission = a.commission;
        agentSel.appendChild(opt);
    });

    const filterSel = document.getElementById("filterAgent");
    while (filterSel.options.length > 1) filterSel.remove(1);
    agents.forEach((a) => {
        const opt = document.createElement("option");
        opt.value = a.id;
        opt.textContent = a.name;
        filterSel.appendChild(opt);
    });

    await fetchAndRenderClosings();
}

// -- Fetch Closings from API ------------------------------------------------

async function fetchAndRenderClosings() {
    try {
        const res = await fetch(closingBasePath, {
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        });
        if (!res.ok) throw new Error("HTTP " + res.status);
        const data = await res.json();
        const list = Array.isArray(data) ? data : (data.data ?? []);
        closings = list.map(normalizeClosing);
    } catch (e) {
        console.error("Gagal load closings:", e);
        closings = [];
    }
    updateStats();
    filterClosings();
}

// -- Stats ------------------------------------------------------------------

function updateStats() {
    const now = new Date();
    document.getElementById("totalClosing").textContent = closings.length;
    document.getElementById("totalSales").textContent = formatCurrency(
        closings.reduce((s, c) => s + c.salePrice, 0),
    );
    document.getElementById("totalCommission").textContent = formatCurrency(
        closings.reduce((s, c) => s + c.commission, 0),
    );
    document.getElementById("closingThisMonth").textContent = closings.filter(
        (c) => {
            const d = new Date(c.date);
            return (
                d.getMonth() === now.getMonth() &&
                d.getFullYear() === now.getFullYear()
            );
        },
    ).length;
}

// -- Format Helpers ---------------------------------------------------------

function formatCurrency(amount) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
}

function formatDate(dateStr) {
    return new Date(dateStr).toLocaleDateString("id-ID", {
        day: "2-digit",
        month: "short",
        year: "numeric",
    });
}

function formatPhoneNumber(phone) {
    return (phone ?? "").replace(/(\d{4})(\d{4})(\d+)/, "$1-$2-$3");
}

function formatTimeAgo(datetime) {
    const diff = Math.floor((Date.now() - new Date(datetime)) / 1000);
    if (diff < 60) return "Baru saja";
    if (diff < 3600) return Math.floor(diff / 60) + " menit lalu";
    if (diff < 86400) return Math.floor(diff / 3600) + " jam lalu";
    return Math.floor(diff / 86400) + " hari lalu";
}

function getPaymentBadge(status) {
    const map = {
        dp: '<span class="badge warning">DP</span>',
        installment: '<span class="badge info">Cicilan</span>',
        "paid-off": '<span class="badge success">Lunas</span>',
    };
    return map[status] ?? '<span class="badge">-</span>';
}

// -- Filter & Render Table --------------------------------------------------

function filterClosings() {
    const monthFilter = document.getElementById("filterMonth").value;
    const agentFilter = document.getElementById("filterAgent").value;
    const paymentFilter = document.getElementById("filterPaymentStatus").value;

    filteredClosings = closings.filter((c) => {
        let monthMatch = true;
        if (monthFilter !== "all") {
            const d = new Date(c.date),
                now = new Date();
            if (monthFilter === "current") {
                monthMatch =
                    d.getMonth() === now.getMonth() &&
                    d.getFullYear() === now.getFullYear();
            } else if (monthFilter === "last") {
                const lm = new Date(now.getFullYear(), now.getMonth() - 1);
                monthMatch =
                    d.getMonth() === lm.getMonth() &&
                    d.getFullYear() === lm.getFullYear();
            }
        }
        return (
            monthMatch &&
            (agentFilter === "all" || c.agentId == agentFilter) &&
            (paymentFilter === "all" || c.paymentStatus === paymentFilter)
        );
    });

    renderClosingsTable();
}

function renderClosingsTable() {
    const tbody = document.getElementById("closingsTableBody");
    tbody.innerHTML = "";

    if (filteredClosings.length === 0) {
        tbody.innerHTML =
            '<tr><td colspan="8" style="text-align:center;padding:2rem;color:#64748b;">Tidak ada data closing</td></tr>';
        return;
    }

    [...filteredClosings]
        .sort((a, b) => new Date(b.date) - new Date(a.date))
        .forEach((c) => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
            <td>
                <div>${formatDate(c.date)}</div>
                <div style="color:#64748b;font-size:.875rem;">${formatTimeAgo(c.createdAt)}</div>
            </td>
            <td>
                <strong>${c.agentName}</strong>
                <div style="color:#64748b;font-size:.875rem;">Komisi: ${c.agentCommission}%</div>
            </td>
            <td>
                <div><strong>${c.customerName}</strong></div>
                <div style="color:#64748b;font-size:.875rem;">
                    <i class="fab fa-whatsapp"></i> ${formatPhoneNumber(c.customerPhone)}
                </div>
            </td>
            <td>${c.propertyName}</td>
            <td><strong>${formatCurrency(c.salePrice)}</strong></td>
            <td style="color:#10b981;font-weight:600;">${formatCurrency(c.commission)}</td>
            <td>${getPaymentBadge(c.paymentStatus)}</td>
            <td>
                <div style="display:flex;gap:.5rem;">
                    <button class="btn-icon" onclick="viewDetails(${c.id})" title="Lihat Detail">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-icon" onclick="editClosing(${c.id})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-icon danger" onclick="deleteClosing(${c.id})" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>`;
            tbody.appendChild(tr);
        });
}

// -- Modal: Open / Close ----------------------------------------------------

function openAddClosingModal() {
    editingClosingId = null;
    document.getElementById("modalTitle").textContent = "Input Closing Baru";
    document.getElementById("closingForm").reset();
    document.getElementById("closingId").value = "";
    document.getElementById("closingDate").valueAsDate = new Date();
    document.getElementById("commissionPreview").style.display = "none";
    document.getElementById("closingModal").style.display = "flex";
}

function editClosing(id) {
    const c = closings.find((x) => x.id === id);
    if (!c) return;
    editingClosingId = id;
    document.getElementById("modalTitle").textContent = "Edit Closing";
    document.getElementById("closingId").value = c.id;
    document.getElementById("closingDate").value = c.date;
    document.getElementById("closingAgent").value = c.agentId;
    document.getElementById("closingProperty").value = c.propertyId;
    document.getElementById("customerName").value = c.customerName;
    document.getElementById("customerPhone").value = c.customerPhone;
    document.getElementById("customerEmail").value = c.customerEmail;
    document.getElementById("salePrice").value = c.salePrice;
    document.getElementById("paymentStatus").value = c.paymentStatus;
    document.getElementById("closingNotes").value = c.notes;
    updateCommissionPreview();
    document.getElementById("closingModal").style.display = "flex";
}

function closeClosingModal() {
    document.getElementById("closingModal").style.display = "none";
    document.getElementById("closingForm").reset();
    editingClosingId = null;
}

function closeDetailsModal() {
    document.getElementById("detailsModal").style.display = "none";
}

// -- Commission Preview -----------------------------------------------------

function updateCommissionPreview() {
    const agentSelect = document.getElementById("closingAgent");
    const salePrice =
        parseFloat(document.getElementById("salePrice").value) || 0;
    if (!agentSelect.value || salePrice === 0) {
        document.getElementById("commissionPreview").style.display = "none";
        return;
    }
    const rate = parseFloat(
        agentSelect.options[agentSelect.selectedIndex].dataset.commission,
    );
    const commission = salePrice * (rate / 100);
    document.getElementById("previewPrice").textContent =
        formatCurrency(salePrice);
    document.getElementById("previewRate").textContent = rate + "%";
    document.getElementById("previewCommission").textContent =
        formatCurrency(commission);
    document.getElementById("commissionPreview").style.display = "block";
}

// -- Save Closing (API) -----------------------------------------------------

async function saveClosing() {
    const date = document.getElementById("closingDate").value;
    const agentId = document.getElementById("closingAgent").value;
    const propertyId = document.getElementById("closingProperty").value;
    const customerName = document.getElementById("customerName").value.trim();
    const customerPhone = document.getElementById("customerPhone").value.trim();
    const salePrice = document.getElementById("salePrice").value;
    const paymentStatus = document.getElementById("paymentStatus").value;
    const notes = document.getElementById("closingNotes").value.trim();

    if (!date || !propertyId || !customerName || !customerPhone || !salePrice) {
        alert("Mohon lengkapi semua field yang wajib diisi!");
        return;
    }

    const payload = {
        tanggal_closing: date,
        agent_id: agentId || null,
        tipe_rumah_id: propertyId,
        customer_name: customerName,
        customer_phone: customerPhone,
        harga_jual: salePrice,
        payment_status: paymentStatus,
        catatan: notes,
    };

    try {
        const url = editingClosingId
            ? closingBasePath + "/" + editingClosingId
            : closingBasePath;
        const method = editingClosingId ? "PUT" : "POST";
        const res = await fetch(url, {
            method,
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": csrfToken(),
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify(payload),
        });

        if (!res.ok) {
            const err = await res.json().catch(() => ({}));
            alert("Gagal menyimpan: " + (err.message ?? res.statusText));
            return;
        }

        const result = await res.json();
        if (!editingClosingId) {
            alert(
                "Closing berhasil ditambahkan!\n\nKomisi: " +
                    formatCurrency(result.komisi_nominal ?? 0),
            );
        } else {
            alert("Data closing berhasil diupdate!");
        }
        closeClosingModal();
        await fetchAndRenderClosings();
    } catch (e) {
        console.error(e);
        alert("Terjadi kesalahan jaringan.");
    }
}

// -- Delete Closing (API) ---------------------------------------------------

async function deleteClosing(id) {
    if (!confirm("Apakah Anda yakin ingin menghapus data closing ini?")) return;
    try {
        const res = await fetch(closingBasePath + "/" + id, {
            method: "DELETE",
            headers: {
                Accept: "application/json",
                "X-CSRF-TOKEN": csrfToken(),
                "X-Requested-With": "XMLHttpRequest",
            },
        });
        if (!res.ok) {
            const err = await res.json().catch(() => ({}));
            alert("Gagal menghapus: " + (err.message ?? res.statusText));
            return;
        }
        alert("Data closing berhasil dihapus!");
        await fetchAndRenderClosings();
    } catch (e) {
        console.error(e);
        alert("Terjadi kesalahan jaringan.");
    }
}

// -- View Details -----------------------------------------------------------

function viewDetails(id) {
    const c = closings.find((x) => x.id === id);
    if (!c) return;
    document.getElementById("closingDetails").innerHTML = `
        <div class="details-grid">
            <div class="detail-item">
                <label>Tanggal Closing</label>
                <p>${formatDate(c.date)}</p>
            </div>
            <div class="detail-item">
                <label>Agent</label>
                <p>${c.agentName} (Komisi: ${c.agentCommission}%)</p>
            </div>
            <div class="detail-item">
                <label>Nama Customer</label>
                <p>${c.customerName}</p>
            </div>
            <div class="detail-item">
                <label>Telepon Customer</label>
                <p><a href="https://wa.me/${c.customerPhone}" target="_blank" style="color:#10b981;">
                    <i class="fab fa-whatsapp"></i> ${formatPhoneNumber(c.customerPhone)}
                </a></p>
            </div>
            <div class="detail-item">
                <label>Properti</label>
                <p>${c.propertyName}</p>
            </div>
            <div class="detail-item">
                <label>Harga Jual</label>
                <p><strong style="font-size:1.125rem;">${formatCurrency(c.salePrice)}</strong></p>
            </div>
            <div class="detail-item">
                <label>Komisi Agent</label>
                <p><strong style="font-size:1.125rem;color:#10b981;">${formatCurrency(c.commission)}</strong></p>
            </div>
            <div class="detail-item">
                <label>Status Pembayaran</label>
                <p>${getPaymentBadge(c.paymentStatus)}</p>
            </div>
            <div class="detail-item" style="grid-column:1/-1;">
                <label>Catatan</label>
                <p>${c.notes || "Tidak ada catatan"}</p>
            </div>
        </div>`;
    document.getElementById("detailsModal").style.display = "flex";
}

// -- Print / Export ---------------------------------------------------------

function printClosing() {
    alert("Fungsi cetak akan membuka halaman print preview.");
}

function exportClosings() {
    alert("Export data closing akan segera didownload...");
}

// -- Close modal on outside click -------------------------------------------

window.onclick = function (event) {
    const cm = document.getElementById("closingModal");
    const dm = document.getElementById("detailsModal");
    if (event.target === cm) closeClosingModal();
    if (event.target === dm) closeDetailsModal();
};
