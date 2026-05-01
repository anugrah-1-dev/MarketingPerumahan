/**
 * agents.js — Agent CRUD via Laravel API
 * Optimized: local in-memory cache + optimistic UI updates
 * - Edit: baca dari cache, TIDAK fetch ulang ke server
 * - Toggle/Delete: update DOM langsung (optimistic), rollback jika server error
 * - Create: loadAgents() sekali untuk dapat referral_code baru
 */

// ── In-memory cache ──────────────────────────────────────────
let agentsCache = [];

// ── Panel detection (admin / manager) ───────────────────────
const agentsBasePath = (() => {
    const seg = window.location.pathname.split("/").filter(Boolean)[0];
    return seg === "manager" ? "/manager/agents" : "/manager/agents";
})();
const agentsDataPath = `${agentsBasePath}/data`;

// ── Helpers ──────────────────────────────────────────────────
function getCsrf() {
    return document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
}
function getInitials(name) {
    return name
        .split(" ")
        .map((n) => n[0])
        .join("")
        .toUpperCase()
        .slice(0, 2);
}
function showLoading(cols = 6) {
    document.getElementById("agentsTableBody").innerHTML = `
        <tr><td colspan="${cols}" style="text-align:center;padding:2rem;color:#94a3b8;">
            <i class="fas fa-spinner fa-spin"></i> Memuat data…
        </td></tr>`;
}

// ── Inject notification styles (once) ────────────────────────
function initStyles() {
    if (document.getElementById("_agentsNotifStyle")) return;
    const s = document.createElement("style");
    s.id = "_agentsNotifStyle";
    s.textContent = `
        @keyframes _nfSlideIn  { from { opacity:0; transform:translateX(110%); } to { opacity:1; transform:translateX(0); } }
        @keyframes _nfFadeOut  { from { opacity:1; } to { opacity:0; } }
        @keyframes _nfPopIn    { from { opacity:0; transform:scale(.92); } to { opacity:1; transform:scale(1); } }
        #_toastWrap { position:fixed; top:1.25rem; right:1.25rem; z-index:9999;
            display:flex; flex-direction:column; gap:.5rem; pointer-events:none; }
        ._toast { display:flex; align-items:flex-start; gap:.75rem;
            min-width:260px; max-width:360px; padding:.75rem 1rem;
            border-radius:.625rem; box-shadow:0 4px 24px rgba(0,0,0,.18);
            color:#fff; font-size:.875rem; line-height:1.45; pointer-events:all;
            animation:_nfSlideIn .3s ease forwards; }
        ._toast.success { background:#10b981; }
        ._toast.error   { background:#ef4444; }
        ._toast.warning { background:#f59e0b; }
        ._toast.info    { background:#3b82f6; }
        ._toast-msg { flex:1; }
        ._toast-x { background:none; border:none; color:#fff; cursor:pointer;
            font-size:.8rem; opacity:.75; padding:0; line-height:1; flex-shrink:0; margin-top:2px; }
        ._toast-x:hover { opacity:1; }
        ._cov { position:fixed; inset:0; background:rgba(0,0,0,.42);
            backdrop-filter:blur(3px); z-index:10000;
            display:flex; align-items:center; justify-content:center; }
        ._ccard { background:#fff; border-radius:1rem; padding:2rem 1.75rem;
            max-width:400px; width:90%; box-shadow:0 24px 60px rgba(0,0,0,.22);
            text-align:center; animation:_nfPopIn .25s ease forwards; }
        ._cicon { width:56px; height:56px; border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            margin:0 auto 1rem; font-size:1.5rem; }
        ._cicon.danger { background:#fef2f2; color:#ef4444; }
        ._cicon.info   { background:#eff6ff; color:#3b82f6; }
        ._ctitle { margin:0 0 .4rem; color:#111827; font-size:1.1rem; font-weight:600; }
        ._cmsg  { color:#6b7280; margin:0 0 1.5rem; font-size:.875rem; line-height:1.5; }
        ._cactions { display:flex; gap:.75rem; }
        ._cbtn { flex:1; padding:.65rem 1rem; border-radius:.5rem; cursor:pointer;
            font-size:.875rem; font-weight:500; border:none;
            transition:opacity .15s, transform .1s; }
        ._cbtn:hover { opacity:.87; }
        ._cbtn:active { transform:scale(.97); }
        ._ccancel { background:#f1f5f9; color:#374151; border:1px solid #e2e8f0 !important; }
    `;
    document.head.appendChild(s);
}

// ── Toast Notification ────────────────────────────────────────
const _TOAST_ICONS = {
    success: "fa-check-circle",
    error: "fa-times-circle",
    warning: "fa-exclamation-triangle",
    info: "fa-info-circle",
};
function showToast(message, type = "info") {
    let wrap = document.getElementById("_toastWrap");
    if (!wrap) {
        wrap = document.createElement("div");
        wrap.id = "_toastWrap";
        document.body.appendChild(wrap);
    }
    const t = document.createElement("div");
    t.className = `_toast ${type}`;
    t.innerHTML = `<i class="fas ${_TOAST_ICONS[type] || _TOAST_ICONS.info}"></i>
        <span class="_toast-msg">${message}</span>
        <button class="_toast-x" aria-label="Tutup"><i class="fas fa-times"></i></button>`;
    t.querySelector("._toast-x").addEventListener("click", () => t.remove());
    wrap.appendChild(t);
    setTimeout(() => {
        t.style.animation = "_nfFadeOut .3s ease forwards";
        setTimeout(() => t.remove(), 300);
    }, 3800);
}

// ── Custom Confirm Dialog ─────────────────────────────────────
function showConfirm(
    title,
    message,
    onConfirm,
    { label = "Ya, Lanjutkan", danger = false } = {},
) {
    const overlay = document.createElement("div");
    overlay.className = "_cov";
    const okStyle = `background:${danger ? "#ef4444" : "#3b82f6"};color:#fff;`;
    overlay.innerHTML = `
        <div class="_ccard">
            <div class="_cicon ${danger ? "danger" : "info"}">
                <i class="fas ${danger ? "fa-trash-alt" : "fa-question-circle"}"></i>
            </div>
            <p class="_ctitle">${title}</p>
            <p class="_cmsg">${message}</p>
            <div class="_cactions">
                <button class="_cbtn _ccancel">Batal</button>
                <button class="_cbtn _cok" style="${okStyle}">${label}</button>
            </div>
        </div>`;
    document.body.appendChild(overlay);
    overlay
        .querySelector("._ccancel")
        .addEventListener("click", () => overlay.remove());
    overlay.querySelector("._cok").addEventListener("click", () => {
        overlay.remove();
        onConfirm();
    });
    overlay.addEventListener("click", (e) => {
        if (e.target === overlay) overlay.remove();
    });
}

// ── Init ─────────────────────────────────────────────────────
document.addEventListener("DOMContentLoaded", () => {
    initStyles();
    loadAgents();

    document
        .getElementById("agentsTableBody")
        .addEventListener("click", function (e) {
            const btn = e.target.closest("button[data-action]");
            if (!btn) return;
            const act = btn.dataset.action;
            const id = btn.dataset.id; // string, tidak pakai parseInt

            if (act === "copy") {
                copyLink(btn.dataset.link, e);
                return;
            }
            if (!id) return;

            if (act === "edit") editAgent(id);
            if (act === "view") viewAgent(id);
            if (act === "toggle") toggleStatus(id);
            if (act === "delete") deleteAgent(id);
        });
});

// ── READ — Load semua agents dari server ─────────────────────
async function loadAgents() {
    showLoading();
    try {
        const resp = await fetch(agentsDataPath, {
            headers: { Accept: "application/json" },
        });
        if (!resp.ok) throw new Error("Gagal memuat data agent.");
        agentsCache = await resp.json();
        renderTable(agentsCache);
    } catch (err) {
        document.getElementById("agentsTableBody").innerHTML = `
            <tr><td colspan="6" style="text-align:center;padding:2rem;color:#ef4444;">
                <i class="fas fa-exclamation-circle"></i> ${err.message}
            </td></tr>`;
    }
}

// ── Render tabel dari cache ───────────────────────────────────
function renderTable(agents) {
    const tbody = document.getElementById("agentsTableBody");
    if (!agents.length) {
        tbody.innerHTML = `
            <tr><td colspan="6" style="text-align:center;padding:2rem;color:#94a3b8;">
                Belum ada agent. Klik "Tambah Agent" untuk menambahkan.
            </td></tr>`;
        return;
    }
    tbody.innerHTML = "";
    agents.forEach((agent) => tbody.appendChild(buildRow(agent)));
}

// ── Build satu baris <tr> ─────────────────────────────────────
function buildRow(agent) {
    const tr = document.createElement("tr");
    tr.dataset.agentId = String(agent.id);

    const statusBadge = agent.aktif
        ? '<span class="badge success">Aktif</span>'
        : '<span class="badge danger">Nonaktif</span>';
    const origin = window.location.origin;
    const refCode = agent.user?.referral_code || agent.slug;
    const affiliateLink = `${origin}/${agent.slug}?ref=${refCode}`;
    const emailRow = agent.email ? `<div>${agent.email}</div>` : "";
    const phoneRow = agent.phone
        ? `<div style="color:#64748b;font-size:.875rem;">${agent.phone}</div>`
        : "";
    const commission = agent.commission != null ? `${agent.commission}%` : "—";

    tr.innerHTML = `
        <td>
            <div style="display:flex;align-items:center;gap:.75rem;cursor:pointer;" onclick="viewAgent('${agent.id}')">
                <div class="agent-avatar">${getInitials(agent.nama)}</div>
                <div>
                    <strong>${agent.nama}</strong>
                    <div style="color:#64748b;font-size:.875rem;">${agent.jabatan}</div>
                </div>
            </div>
        </td>
        <td>
            ${emailRow}${phoneRow}
            ${!agent.email && !agent.phone ? '<span style="color:#94a3b8;">—</span>' : ""}
        </td>
        <td>
            <div style="display:flex;align-items:center;gap:.5rem;">
                <code style="background:#f1f5f9;padding:.25rem .5rem;border-radius:.25rem;font-size:.875rem;">/${agent.slug}?ref=${refCode}</code>
                <button class="btn-icon" data-action="copy" data-link="${affiliateLink}" title="Salin link affiliate">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </td>
        <td>${commission}</td>
        <td>${statusBadge}</td>
        <td>
            <div style="display:flex;gap:.5rem;">
                <button class="btn-icon" data-action="view" data-id="${agent.id}" title="Detail">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="btn-icon" data-action="edit" data-id="${agent.id}" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-icon" data-action="toggle" data-id="${agent.id}"
                    title="${agent.aktif ? "Nonaktifkan" : "Aktifkan"}">
                    <i class="fas fa-${agent.aktif ? "ban" : "check"}"></i>
                </button>
                <button class="btn-icon danger" data-action="delete" data-id="${agent.id}" title="Hapus">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    `;
    return tr;
}

// ── Cari baris di DOM ─────────────────────────────────────────
function findRow(id) {
    return document.querySelector(`tr[data-agent-id="${id}"]`);
}

// ── Modal helpers ─────────────────────────────────────────────
function openAddAgentModal() {
    const modal = document.getElementById("agentModal");
    if (modal.parentElement !== document.body) document.body.appendChild(modal);
    document.getElementById("modalTitle").textContent = "Tambah Affiliate";
    document.getElementById("agentForm").reset();
    document.getElementById("agentId").value = "";
    document.getElementById("agentPassword").required = true;
    document.getElementById("helpPasswordEdit").style.display = "none";
    modal.style.display = "flex";
    document.body.style.overflow = "hidden";
}
function closeAgentModal() {
    document.getElementById("agentModal").style.display = "none";
    document.getElementById("agentForm").reset();
    document.body.style.overflow = "";
}

// ── EDIT — pakai cache lokal, TIDAK fetch ke server ───────────
function editAgent(id) {
    const agent = agentsCache.find((a) => String(a.id) === String(id));
    if (!agent) {
        showToast("Agent tidak ditemukan.", "error");
        return;
    }

    document.getElementById("modalTitle").textContent = "Edit Affiliate";
    document.getElementById("agentId").value = agent.id;
    document.getElementById("agentName").value = agent.nama;
    document.getElementById("agentJabatan").value = agent.jabatan;
    document.getElementById("agentEmail").value = agent.email ?? "";
    document.getElementById("agentPhone").value = agent.phone ?? "";
    // Tampilkan teks '1%' di popup (komisi dikunci)
    document.getElementById("agentCommission").value = agent.commission ?? 0;
    document.getElementById("agentNamaBank").value = agent.nama_bank ?? "";
    document.getElementById("agentNoRekening").value = agent.no_rekening ?? "";
    document.getElementById("agentAtasNama").value =
        agent.atas_nama_rekening ?? "";
    document.getElementById("agentPassword").value = "";
    document.getElementById("agentPassword").required = false;
    document.getElementById("helpPasswordEdit").style.display = "block";
    const modal = document.getElementById("agentModal");
    if (modal.parentElement !== document.body) document.body.appendChild(modal);
    modal.style.display = "flex";
    document.body.style.overflow = "hidden";
}

// ── CREATE / UPDATE ───────────────────────────────────────────
async function saveAgent() {
    const nama = document.getElementById("agentName").value.trim();
    const jabatan = document.getElementById("agentJabatan").value.trim();
    const email = document.getElementById("agentEmail").value.trim();
    const phone = document.getElementById("agentPhone").value.trim();
    const password = document.getElementById("agentPassword").value;
    // Komisi dikunci menjadi 1% — jangan ambil dari input
    const id = document.getElementById("agentId").value;
    const isEdit = !!id;

    if (!nama || !email || (!isEdit && !password)) {
        showToast("Nama, Email, dan Password wajib diisi!", "warning");
        return;
    }

    const btn = document.getElementById("saveBtn");
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan…';

    try {
        const url = isEdit ? `${agentsBasePath}/${id}` : agentsBasePath;
        const method = isEdit ? "PUT" : "POST";

        const commissionVal = parseFloat(
            document.getElementById("agentCommission").value,
        );
        const payload = { nama, jabatan, email };
        if (phone) payload.phone = phone;
        if (password) payload.password = password;
        payload.commission = isNaN(commissionVal) ? 0 : commissionVal;
        payload.nama_bank =
            document.getElementById("agentNamaBank").value.trim() || null;
        payload.no_rekening =
            document.getElementById("agentNoRekening").value.trim() || null;
        payload.atas_nama_rekening =
            document.getElementById("agentAtasNama").value.trim() || null;
        payload.nama_bank =
            document.getElementById("agentNamaBank").value.trim() || null;
        payload.no_rekening =
            document.getElementById("agentNoRekening").value.trim() || null;
        payload.atas_nama_rekening =
            document.getElementById("agentAtasNama").value.trim() || null;

        const resp = await fetch(url, {
            method,
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": getCsrf(),
            },
            body: JSON.stringify(payload),
        });

        if (!resp.ok) {
            const err = await resp.json();
            const msg = err.errors
                ? Object.values(err.errors).flat().join(" | ")
                : err.message || "Terjadi kesalahan.";
            showToast(msg, "error");
            return;
        }

        const saved = await resp.json();
        closeAgentModal();
        showToast(
            isEdit
                ? "Data agent berhasil diperbarui."
                : "Agent baru berhasil ditambahkan.",
            "success",
        );

        if (isEdit) {
            // Update cache dan baris yang ada di DOM — tanpa fetch ulang
            const idx = agentsCache.findIndex(
                (a) => String(a.id) === String(id),
            );
            if (idx !== -1) {
                // Pertahankan user.referral_code dari cache lama
                agentsCache[idx] = {
                    ...agentsCache[idx],
                    nama: saved.nama ?? nama,
                    jabatan: saved.jabatan ?? jabatan,
                    email: saved.email ?? email,
                    phone: saved.phone ?? (phone || null),
                    commission: saved.commission ?? agentsCache[idx].commission,
                    nama_bank: saved.nama_bank ?? agentsCache[idx].nama_bank,
                    no_rekening:
                        saved.no_rekening ?? agentsCache[idx].no_rekening,
                    atas_nama_rekening:
                        saved.atas_nama_rekening ??
                        agentsCache[idx].atas_nama_rekening,
                    aktif: saved.aktif ?? agentsCache[idx].aktif,
                };
                const oldRow = findRow(id);
                if (oldRow) oldRow.replaceWith(buildRow(agentsCache[idx]));
            }
        } else {
            // Create: perlu fetch sekali untuk dapat user.referral_code baru
            await loadAgents();
        }
    } catch {
        showToast("Gagal menyimpan. Cek koneksi atau coba lagi.", "error");
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Simpan';
    }
}

// ── TOGGLE STATUS — optimistic UI ────────────────────────────
async function toggleStatus(id) {
    const agent = agentsCache.find((a) => String(a.id) === String(id));
    if (!agent) return;

    const aksi = agent.aktif ? "nonaktifkan" : "aktifkan";
    const aksiCap = agent.aktif ? "Nonaktifkan" : "Aktifkan";

    showConfirm(
        `${aksiCap} Agent`,
        `Yakin ingin <strong>${aksi}</strong> agent <strong>${agent.nama}</strong>?`,
        async () => {
            // Optimistic: balik status di cache dan DOM seketika
            agent.aktif = !agent.aktif;
            const oldRow = findRow(id);
            if (oldRow) oldRow.replaceWith(buildRow(agent));

            try {
                const resp = await fetch(`${agentsBasePath}/${id}/status`, {
                    method: "PATCH",
                    headers: {
                        Accept: "application/json",
                        "X-CSRF-TOKEN": getCsrf(),
                    },
                });
                if (!resp.ok) throw new Error();
                showToast(`Agent berhasil di${aksi}.`, "success");
            } catch {
                // Rollback: balik cache dan DOM ke state semula
                agent.aktif = !agent.aktif;
                const rolledRow = findRow(id);
                if (rolledRow) rolledRow.replaceWith(buildRow(agent));
                showToast("Gagal mengubah status agent.", "error");
            }
        },
        { label: `Ya, ${aksiCap}` },
    );
}

// ── DELETE — optimistic UI ────────────────────────────────────
async function deleteAgent(id) {
    // Guard: tolak ID yang tidak valid sebelum request ke server
    if (!id || id === "NaN" || id === "undefined" || id === "null") {
        console.error("[deleteAgent] ID tidak valid:", id);
        showToast("ID agent tidak valid — coba muat ulang halaman.", "error");
        return;
    }

    const agent = agentsCache.find((a) => String(a.id) === String(id));
    const namaAgent = agent?.nama ?? "agent ini";

    showConfirm(
        "Hapus Agent",
        `Agent <strong>${namaAgent}</strong> akan dihapus permanen.<br>Tindakan ini tidak dapat dibatalkan!`,
        async () => {
            // Simpan snapshot untuk rollback
            const snapshot = [...agentsCache];

            // Optimistic: hapus dari cache dan DOM seketika
            agentsCache = agentsCache.filter(
                (a) => String(a.id) !== String(id),
            );
            const row = findRow(id);
            if (row) row.remove();
            if (!agentsCache.length) renderTable([]);

            try {
                const resp = await fetch(`${agentsBasePath}/${id}`, {
                    method: "DELETE",
                    headers: {
                        Accept: "application/json",
                        "X-CSRF-TOKEN": getCsrf(),
                    },
                });
                if (!resp.ok) throw new Error();
                showToast(`Agent "${namaAgent}" berhasil dihapus.`, "success");
            } catch {
                // Rollback: kembalikan cache dan render ulang
                agentsCache = snapshot;
                renderTable(agentsCache);
                showToast("Gagal menghapus agent. Coba lagi.", "error");
            }
        },
        { label: '<i class="fas fa-trash"></i>&nbsp;Ya, Hapus', danger: true },
    );
}

// ── SEARCH — filter di sisi klien ────────────────────────────
function searchAgents() {
    const term = document.getElementById("searchAgen").value.toLowerCase();
    document.querySelectorAll("#agentsTableBody tr").forEach((row) => {
        row.style.display = row.textContent.toLowerCase().includes(term)
            ? ""
            : "none";
    });
}

// ── COPY LINK ─────────────────────────────────────────────────
function copyLink(link, event) {
    navigator.clipboard
        .writeText(link)
        .then(() => {
            const btn = event.target.closest("button");
            const ori = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i>';
            btn.style.color = "#10b981";
            setTimeout(() => {
                btn.innerHTML = ori;
                btn.style.color = "";
            }, 2000);
        })
        .catch(() => showToast("Gagal menyalin link.", "error"));
}

// ── VIEW DETAIL — fetch dari server ────────────────────────────
async function viewAgent(id) {
    const modal = document.getElementById("agentDetailModal");
    const body = document.getElementById("agentDetailBody");
    modal.style.display = "flex";
    body.innerHTML = `<div style="text-align:center;padding:2rem;color:#94a3b8;">
        <i class="fas fa-spinner fa-spin"></i> Memuat detail…</div>`;

    try {
        const resp = await fetch(`${agentsBasePath}/${id}/detail`, {
            headers: { Accept: "application/json" },
        });
        if (!resp.ok) throw new Error("Gagal memuat detail agent.");
        const d = await resp.json();

        const statusBadge = d.aktif
            ? '<span class="badge success">Aktif</span>'
            : '<span class="badge danger">Nonaktif</span>';
        const fmtRp = (v) =>
            v ? "Rp " + Number(v).toLocaleString("id-ID") : "Rp 0";

        let closingsHtml = "";
        if (d.recent_closings && d.recent_closings.length) {
            closingsHtml = `
                <h4 style="margin:1rem 0 .5rem;font-size:.9rem;color:#334155;">
                    <i class="fas fa-history" style="margin-right:.3rem;color:#3d81af;"></i> Closing Terakhir
                </h4>
                <div style="overflow-x:auto;">
                <table style="width:100%;font-size:.82rem;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#f8fafc;text-align:left;">
                            <th style="padding:.4rem .5rem;border-bottom:1px solid #e2e8f0;">Customer</th>
                            <th style="padding:.4rem .5rem;border-bottom:1px solid #e2e8f0;">Tipe</th>
                            <th style="padding:.4rem .5rem;border-bottom:1px solid #e2e8f0;">Komisi</th>
                            <th style="padding:.4rem .5rem;border-bottom:1px solid #e2e8f0;">Status</th>
                            <th style="padding:.4rem .5rem;border-bottom:1px solid #e2e8f0;">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${d.recent_closings
                            .map(
                                (c) => `
                            <tr>
                                <td style="padding:.4rem .5rem;border-bottom:1px solid #f1f5f9;">${c.customer}</td>
                                <td style="padding:.4rem .5rem;border-bottom:1px solid #f1f5f9;">${c.tipe}</td>
                                <td style="padding:.4rem .5rem;border-bottom:1px solid #f1f5f9;">${fmtRp(c.komisi)}</td>
                                <td style="padding:.4rem .5rem;border-bottom:1px solid #f1f5f9;">
                                    <span class="badge ${c.status === "paid" ? "success" : "warning"}" style="font-size:.75rem;">
                                        ${c.status === "paid" ? "Dibayar" : "Belum"}
                                    </span>
                                </td>
                                <td style="padding:.4rem .5rem;border-bottom:1px solid #f1f5f9;">${c.tanggal ?? "-"}</td>
                            </tr>`,
                            )
                            .join("")}
                    </tbody>
                </table>
                </div>`;
        }

        body.innerHTML = `
            <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.25rem;">
                <div class="agent-avatar" style="width:56px;height:56px;font-size:1.2rem;">${getInitials(d.nama)}</div>
                <div>
                    <h3 style="margin:0;font-size:1.1rem;color:#111827;">${d.nama}</h3>
                    <div style="color:#64748b;font-size:.85rem;">${d.jabatan} ${statusBadge}</div>
                    <div style="color:#94a3b8;font-size:.8rem;">Bergabung: ${d.created_at ?? "-"}</div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:1rem;">
                <div style="background:#f0fdf4;border-radius:.5rem;padding:.75rem;text-align:center;">
                    <div style="font-size:1.25rem;font-weight:700;color:#16a34a;">${d.total_closing}</div>
                    <div style="font-size:.78rem;color:#64748b;">Total Closing</div>
                </div>
                <div style="background:#eff6ff;border-radius:.5rem;padding:.75rem;text-align:center;">
                    <div style="font-size:1.25rem;font-weight:700;color:#2563eb;">${d.total_klik}</div>
                    <div style="font-size:.78rem;color:#64748b;">Total Klik WA</div>
                </div>
                <div style="background:#fefce8;border-radius:.5rem;padding:.75rem;text-align:center;">
                    <div style="font-size:1rem;font-weight:700;color:#ca8a04;">${fmtRp(d.total_komisi)}</div>
                    <div style="font-size:.78rem;color:#64748b;">Total Komisi</div>
                </div>
                <div style="background:#f0fdf4;border-radius:.5rem;padding:.75rem;text-align:center;">
                    <div style="font-size:1rem;font-weight:700;color:#16a34a;">${fmtRp(d.komisi_terbayar)}</div>
                    <div style="font-size:.78rem;color:#64748b;">Komisi Terbayar</div>
                </div>
            </div>

            <div style="background:#f8fafc;border-radius:.5rem;padding:.75rem 1rem;margin-bottom:.75rem;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem;font-size:.85rem;">
                    <div><span style="color:#94a3b8;">Email:</span> ${d.email || "-"}</div>
                    <div><span style="color:#94a3b8;">Telepon:</span> ${d.phone || "-"}</div>
                    <div><span style="color:#94a3b8;">Komisi:</span> ${d.commission != null ? d.commission + "%" : "-"}</div>
                    <div><span style="color:#94a3b8;">Slug:</span> /${d.slug}</div>
                </div>
            </div>

            ${
                d.nama_bank
                    ? `<div style="background:#f8fafc;border-radius:.5rem;padding:.75rem 1rem;margin-bottom:.75rem;">
                    <h4 style="margin:0 0 .4rem;font-size:.85rem;color:#334155;">
                        <i class="fas fa-university" style="margin-right:.3rem;color:#3d81af;"></i> Informasi Rekening
                    </h4>
                    <div style="font-size:.85rem;display:grid;grid-template-columns:1fr 1fr;gap:.3rem;">
                        <div><span style="color:#94a3b8;">Bank:</span> ${d.nama_bank}</div>
                        <div><span style="color:#94a3b8;">No. Rek:</span> ${d.no_rekening || "-"}</div>
                        <div><span style="color:#94a3b8;">Atas Nama:</span> ${d.atas_nama_rekening || "-"}</div>
                    </div>
                </div>`
                    : ""
            }

            ${closingsHtml}
        `;
    } catch (err) {
        body.innerHTML = `<div style="text-align:center;padding:2rem;color:#ef4444;">
            <i class="fas fa-exclamation-circle"></i> ${err.message}</div>`;
    }
}

function closeDetailModal() {
    document.getElementById("agentDetailModal").style.display = "none";
}

// Close modal bila klik di luar
window.onclick = function (event) {
    const modal = document.getElementById("agentModal");
    if (event.target === modal) closeAgentModal();
    const detailModal = document.getElementById("agentDetailModal");
    if (event.target === detailModal) closeDetailModal();
};
