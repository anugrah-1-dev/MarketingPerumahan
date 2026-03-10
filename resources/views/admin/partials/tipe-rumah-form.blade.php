<div class="space-y-3">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Tipe <span class="text-red-500">*</span></label>
        <input type="text" name="nama_tipe" placeholder="contoh: Tipe 36/72"
            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Luas Bangunan (m²) <span class="text-red-500">*</span></label>
            <input type="number" name="luas_bangunan" min="1" placeholder="36"
                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Luas Tanah (m²) <span class="text-red-500">*</span></label>
            <input type="number" name="luas_tanah" min="1" placeholder="72"
                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Harga Normal <span class="text-red-500">*</span></label>
            <input type="number" name="harga" min="0" placeholder="310000000"
                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Harga Diskon</label>
            <input type="number" name="harga_diskon" min="0" placeholder="285000000"
                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Stok Tersedia <span class="text-red-500">*</span></label>
        <input type="number" name="stok_tersedia" min="0" placeholder="10"
            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
        <textarea name="deskripsi" rows="2" placeholder="Deskripsi singkat tipe rumah..."
            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Rumah</label>
        <input type="file" name="gambar" accept="image/*"
            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-blue-50 file:text-blue-600 cursor-pointer">
        <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, WebP. Maksimal 2MB.</p>
    </div>
    <div class="flex items-center gap-3 py-2">
        <input type="checkbox" name="is_diskon" id="is_diskon_form" value="1"
            class="w-4 h-4 rounded text-blue-600 cursor-pointer">
        <label for="is_diskon_form" class="text-sm font-medium text-gray-700 cursor-pointer">
            Tampilkan di homepage sebagai <span class="text-green-600 font-semibold">Tipe Diskon</span>
        </label>
    </div>
</div>
