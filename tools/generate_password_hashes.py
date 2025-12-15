#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Script untuk generate password hash dari NRP
Menggunakan bcrypt untuk generate hash yang kompatibel dengan PHP password_hash()
"""

import re
from pathlib import Path

def generate_password_hash(password):
    """Generate password hash menggunakan bcrypt (kompatibel dengan PHP password_hash)"""
    try:
        import bcrypt
        
        # Generate salt dan hash
        salt = bcrypt.gensalt(rounds=10)
        hashed = bcrypt.hashpw(password.encode('utf-8'), salt)
        
        # Convert ke string (format PHP: $2y$10$...)
        hash_str = hashed.decode('utf-8')
        
        # PHP menggunakan $2y$, Python bcrypt menggunakan $2b$
        # Tapi keduanya kompatibel, atau kita bisa convert
        # Untuk kompatibilitas penuh, kita bisa gunakan format $2y$
        if hash_str.startswith('$2b$'):
            hash_str = hash_str.replace('$2b$', '$2y$', 1)
        
        return hash_str
    except ImportError:
        print("[ERROR] bcrypt tidak terinstall. Install dengan: pip install bcrypt")
        return None

def update_sql_file():
    """Update file SQL dengan password hash yang benar"""
    sql_file = Path('sql/data_personil.sql')
    
    if not sql_file.exists():
        print(f"[ERROR] File tidak ditemukan: {sql_file}")
        return
    
    # Baca file SQL
    with open(sql_file, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Cari semua placeholder PASSWORD_HASH_XXXXX
    pattern = r"PASSWORD_HASH_(\d+)"
    matches = re.findall(pattern, content)
    
    if not matches:
        print("[INFO] Tidak ada placeholder password yang ditemukan")
        return
    
    print(f"[INFO] Menemukan {len(matches)} password yang perlu di-hash...")
    
    # Generate hash untuk setiap NRP
    replacements = {}
    for nrp in matches:
        hash_value = generate_password_hash(nrp)
        if hash_value:
            replacements[f"PASSWORD_HASH_{nrp}"] = hash_value
            print(f"[OK] NRP {nrp}: Hash generated")
        else:
            print(f"[ERROR] NRP {nrp}: Gagal generate hash")
    
    # Replace semua placeholder dengan hash yang benar
    for placeholder, hash_value in replacements.items():
        content = content.replace(placeholder, hash_value)
    
    # Simpan file
    with open(sql_file, 'w', encoding='utf-8') as f:
        f.write(content)
    
    print(f"\n[SUCCESS] File {sql_file} berhasil diupdate dengan password hash yang benar!")

def main():
    """Main function"""
    print("Generate Password Hash dari NRP")
    print("=" * 60)
    
    # Cek apakah bcrypt terinstall
    try:
        import bcrypt
        print("[OK] bcrypt library tersedia")
    except ImportError:
        print("[ERROR] bcrypt tidak terinstall")
        print("[INFO] Install dengan: pip install bcrypt")
        return
    
    # Update SQL file
    update_sql_file()
    
    print("\n" + "=" * 60)
    print("Selesai!")
    print("\nCatatan:")
    print("- Username = NRP")
    print("- Password = NRP (sama dengan username)")
    print("- Password sudah di-hash menggunakan bcrypt")
    print("- File SQL siap diimport ke database")

if __name__ == '__main__':
    main()

