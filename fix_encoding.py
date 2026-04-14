import os

files_to_fix = [
    r'd:\Projek_Web\laragon\www\MarketingPerumahan\resources\views\layouts\app.blade.php',
    r'd:\Projek_Web\laragon\www\MarketingPerumahan\resources\views\landing.blade.php',
    r'd:\Projek_Web\laragon\www\MarketingPerumahan\resources\views\detail-rumah.blade.php',
]

# These are UTF-8 strings that are actually double-encoded (UTF-8 bytes read as Latin-1 then re-encoded)
# Fix pairs: (mangled_string, correct_string)
replacements = [
    # en-dash – (U+2013)
    ('\u00e2\u20ac\u201c', '\u2013'),  # â€" -> –
    # right arrow → (U+2192)
    ('\u00e2\u20ac\u2019', '\u2192'),  # â€™ but actually â†' -> →
    # copyright © (U+00A9) - Â©
    ('\u00c2\u00a9', '\u00a9'),        # Â© -> ©
    # 📍 location pin (U+1F4CD)
    ('\u00f0\u009f\u0094\u008d', '\U0001f4cd'),  # ðŸ" -> 📍
    # 📋 clipboard (U+1F4CB)
    ('\u00f0\u009f\u0094\u008b', '\U0001f4cb'),  # ðŸ"‹ -> 📋
    # 🛏 bed (U+1F6CF)
    ('\u00f0\u009f\u009b\u008f', '\U0001f6cf'),  # ðŸ› -> 🛏
    # 🚿 shower (U+1F6BF)
    ('\u00f0\u009f\u009a\u00bf', '\U0001f6bf'),  # ðŸš¿ -> 🚿
    # 🏢 building (U+1F3E2)
    ('\u00f0\u009f\u008f\u00a2', '\U0001f3e2'),  # ðŸ¢ -> 🏢
    # 🚗 car (U+1F697)
    ('\u00f0\u009f\u009a\u0097', '\U0001f697'),  # ðŸš— -> 🚗
    # 📜 scroll (U+1F4DC)
    ('\u00f0\u009f\u0094\u009c', '\U0001f4dc'),  # ðŸ"œ -> 📜
    # 📐 ruler (U+1F4D0)
    ('\u00f0\u009f\u0094\u0090', '\U0001f4d0'),  # ðŸ" -> 📐
    # 🌿 herb (U+1F33F)
    ('\u00f0\u009f\u008c\u00bf', '\U0001f33f'),  # ðŸŒ¿ -> 🌿
    # ✔ heavy check (U+2714)
    ('\u00e2\u009c\u0094', '\u2714'),  # âœ" -> ✔
    # 💰 money bag (U+1F4B0)
    ('\u00f0\u009f\u0092\u00b0', '\U0001f4b0'),  # ðŸ'° -> 💰
    # 📞 telephone (U+1F4DE)
    ('\u00f0\u009f\u0094\u009e', '\U0001f4de'),  # ðŸ"ž -> 📞
]

for fpath in files_to_fix:
    with open(fpath, 'r', encoding='utf-8') as f:
        content = f.read()
    original = content
    for bad, good in replacements:
        content = content.replace(bad, good)
    if content != original:
        with open(fpath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f'Fixed: {os.path.basename(fpath)}')
    else:
        print(f'No changes needed: {os.path.basename(fpath)}')

print('Done.')
