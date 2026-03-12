<div class="space-y-1">
    {{-- Nama Tipe --}}
    <div class="form-group">
        <label>Nama Tipe <span class="req">*</span></label>
        <input type="text" name="nama_tipe" placeholder="contoh: Tipe 36/72"
            class="form-input" required>
    </div>

    {{-- Luas Bangunan & Luas Tanah --}}
    <div class="tr-form-row">
        <div class="form-group">
            <label>Luas Bangunan (m²) <span class="req">*</span></label>
            <input type="number" name="luas_bangunan" min="1" placeholder="36"
                class="form-input" required>
        </div>
        <div class="form-group">
            <label>Luas Tanah (m²) <span class="req">*</span></label>
            <input type="number" name="luas_tanah" min="1" placeholder="72"
                class="form-input" required>
        </div>
    </div>

    {{-- Harga Normal & Harga Diskon --}}
    <div class="tr-form-row">
        <div class="form-group">
            <label>Harga Normal <span class="req">*</span></label>
            <input type="number" name="harga" min="0" placeholder="310000000"
                class="form-input" required>
        </div>
        <div class="form-group">
            <label>Harga Diskon</label>
            <input type="number" name="harga_diskon" min="0" placeholder="285000000"
                class="form-input">
        </div>
    </div>

    {{-- Stok --}}
    <div class="form-group">
        <label>Stok Tersedia <span class="req">*</span></label>
        <input type="number" name="stok_tersedia" min="0" placeholder="10"
            class="form-input" required>
    </div>

    {{-- Kamar Tidur, Kamar Mandi, Lantai --}}
    <div class="tr-form-row">
        <div class="form-group">
            <label>Kamar Tidur <span class="req">*</span></label>
            <input type="number" name="kamar_tidur" min="1" placeholder="2" class="form-input" required>
        </div>
        <div class="form-group">
            <label>Kamar Mandi <span class="req">*</span></label>
            <input type="number" name="kamar_mandi" min="1" placeholder="1" class="form-input" required>
        </div>
        <div class="form-group">
            <label>Lantai <span class="req">*</span></label>
            <input type="number" name="lantai" min="1" placeholder="1" class="form-input" required>
        </div>
    </div>

    {{-- Sertifikat --}}
    <div class="form-group">
        <label>Sertifikat</label>
        <select name="sertifikat" class="form-input">
            <option value="SHM">SHM</option>
            <option value="HGB">HGB</option>
            <option value="SHMilik">SHMilik</option>
            <option value="Girik">Girik</option>
        </select>
    </div>

    {{-- Fasilitas (dinamis) --}}
    <div class="form-group">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
            <label style="margin-bottom:0">Fasilitas</label>
            <button type="button" onclick="trAddFasilitas()" title="Tambah fasilitas"
                style="display:flex;align-items:center;gap:5px;background:#16a34a;color:#fff;border:none;border-radius:8px;padding:5px 13px;font-size:13px;font-weight:600;cursor:pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah
            </button>
        </div>
        <div id="fasilitasList" style="display:flex;flex-direction:column;gap:6px;"></div>
        <p class="tr-file-hint">Contoh: Listrik 2200W, Air PDAM, Kitchen Set...</p>
    </div>

    {{-- Deskripsi --}}
    <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="deskripsi" rows="2" placeholder="Deskripsi singkat tipe rumah..."
            class="form-input"></textarea>
    </div>

    {{-- ── Foto Utama ─────────────────────────────── --}}
    <div class="form-group">
        <label>Foto Utama</label>
        <input type="file" name="gambar" accept="image/*" class="tr-file-input">
        <p class="tr-file-hint">Format: JPG, PNG, WebP. Maks 2MB.</p>
    </div>

    {{-- ── Foto Tambahan (dinamis) ─────────────────── --}}
    <div class="form-group">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
            <label style="margin-bottom:0">Foto Tambahan</label>
            <button type="button" onclick="trAddFotoSlot()" title="Tambah foto"
                style="display:flex;align-items:center;gap:5px;background:#2563eb;color:#fff;border:none;border-radius:8px;padding:5px 13px;font-size:13px;font-weight:600;cursor:pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Foto
            </button>
        </div>
        <div id="fotoTambahanList" style="display:flex;flex-direction:column;gap:8px;"></div>
        <p class="tr-file-hint">Klik "+ Tambah Foto" untuk foto-foto tambahan (opsional).</p>
    </div>
</div>
