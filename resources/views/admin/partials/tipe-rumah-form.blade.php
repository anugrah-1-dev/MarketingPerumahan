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

    {{-- Deskripsi --}}
    <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="deskripsi" rows="2" placeholder="Deskripsi singkat tipe rumah..."
            class="form-input"></textarea>
    </div>

    {{-- Upload Foto --}}
    <div class="form-group">
        <label>Foto Rumah</label>
        <input type="file" name="gambar" accept="image/*" class="tr-file-input">
        <p class="tr-file-hint">Format: JPG, PNG, WebP. Maksimal 2MB.</p>
    </div>

    {{-- Checkbox Diskon --}}
    <div class="form-group">
        <label class="tr-checkbox-wrap">
            <input type="checkbox" name="is_diskon" id="is_diskon_form" value="1">
            <span>Tampilkan sebagai <span class="highlight">Tipe Diskon</span> di homepage</span>
        </label>
    </div>
</div>
