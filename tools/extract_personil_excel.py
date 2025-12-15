#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Script untuk mengekstrak data personil dari Excel
Data_Personil_SatPamobvit_Resmi.xlsx
"""

import re
from pathlib import Path

def extract_from_excel(excel_path):
    """Ekstrak data dari file Excel"""
    try:
        import pandas as pd
        
        # Baca Excel
        df = pd.read_excel(excel_path)
        
        # Convert ke list of dict
        data_personil = []
        
        for index, row in df.iterrows():
            # Skip baris kosong
            if pd.isna(row.iloc[0]):
                continue
            
            # Ambil data dari setiap kolom
            # Sesuaikan nama kolom dengan struktur Excel
            personil = {}
            
            # Coba berbagai kemungkinan nama kolom
            if 'No' in df.columns or 'NO' in df.columns:
                no_col = 'No' if 'No' in df.columns else 'NO'
                personil['no'] = str(row[no_col]) if pd.notna(row[no_col]) else ''
            
            if 'Nama' in df.columns or 'NAMA' in df.columns:
                nama_col = 'Nama' if 'Nama' in df.columns else 'NAMA'
                personil['nama'] = str(row[nama_col]).strip() if pd.notna(row[nama_col]) else ''
            elif len(df.columns) > 1:
                personil['nama'] = str(row.iloc[1]).strip() if pd.notna(row.iloc[1]) else ''
            
            if 'Pangkat' in df.columns or 'PANGKAT' in df.columns:
                pangkat_col = 'Pangkat' if 'Pangkat' in df.columns else 'PANGKAT'
                personil['pangkat'] = str(row[pangkat_col]).strip() if pd.notna(row[pangkat_col]) else ''
            elif len(df.columns) > 2:
                personil['pangkat'] = str(row.iloc[2]).strip() if pd.notna(row.iloc[2]) else ''
            
            if 'NRP' in df.columns:
                nrp_col = 'NRP'
                personil['nrp'] = str(row[nrp_col]).strip() if pd.notna(row[nrp_col]) else ''
            elif 'Nrp' in df.columns:
                personil['nrp'] = str(row['Nrp']).strip() if pd.notna(row['Nrp']) else ''
            elif len(df.columns) > 3:
                nrp_val = row.iloc[3]
                if pd.notna(nrp_val):
                    # Handle jika NRP ada format khusus (seperti '00080816)
                    nrp_str = str(nrp_val).strip()
                    # Hapus apostrophe di awal jika ada
                    if nrp_str.startswith("'"):
                        nrp_str = nrp_str[1:]
                    personil['nrp'] = nrp_str
                else:
                    personil['nrp'] = ''
            
            if 'Jabatan' in df.columns or 'JABATAN' in df.columns:
                jabatan_col = 'Jabatan' if 'Jabatan' in df.columns else 'JABATAN'
                personil['jabatan'] = str(row[jabatan_col]).strip() if pd.notna(row[jabatan_col]) else ''
            elif len(df.columns) > 4:
                personil['jabatan'] = str(row.iloc[4]).strip() if pd.notna(row.iloc[4]) else ''
            
            if 'Satker' in df.columns or 'SATKER' in df.columns:
                satker_col = 'Satker' if 'Satker' in df.columns else 'SATKER'
                personil['satker'] = str(row[satker_col]).strip() if pd.notna(row[satker_col]) else ''
            elif len(df.columns) > 5:
                personil['satker'] = str(row.iloc[5]).strip() if pd.notna(row.iloc[5]) else ''
            
            # Hanya tambahkan jika ada nama
            if personil.get('nama') and personil['nama'] != 'nan' and personil['nama'].lower() != 'nama':
                data_personil.append(personil)
        
        return data_personil
    except ImportError:
        print("[ERROR] pandas atau openpyxl tidak terinstall")
        return None
    except Exception as e:
        print(f"[ERROR] {str(e)}")
        import traceback
        traceback.print_exc()
        return None

def generate_username(nrp):
    """Generate username dari NRP"""
    # Gunakan NRP langsung sebagai username
    if nrp:
        # Hapus karakter non-digit jika ada
        username = re.sub(r'\D', '', str(nrp))
        return username
    return ''

def determine_role(jabatan):
    """Tentukan role berdasarkan jabatan"""
    jabatan_upper = jabatan.upper() if jabatan else ''
    
    # Admin jika jabatan mengandung KASAT atau kepala
    if 'KASAT' in jabatan_upper or 'KEPALA' in jabatan_upper:
        return 'admin'
    
    return 'penilai'

def hash_password(password):
    """Generate password hash untuk NRP"""
    # Import bcrypt untuk hash password
    try:
        import bcrypt
        # Hash password dengan bcrypt
        salt = bcrypt.gensalt()
        hashed = bcrypt.hashpw(password.encode('utf-8'), salt)
        return hashed.decode('utf-8')
    except ImportError:
        # Fallback: gunakan password_hash PHP format (bcrypt)
        # Format: $2y$10$[22 chars salt][31 chars hash]
        # Untuk sementara, kita akan generate hash di PHP, jadi return placeholder
        # User perlu menjalankan script PHP untuk generate hash yang benar
        return None

def save_to_sql(personil_list, output_file):
    """Simpan data ke file SQL INSERT"""
    with open(output_file, 'w', encoding='utf-8') as f:
        f.write("-- =====================================================\n")
        f.write("-- DATA PERSONIL (DIPERBARUI)\n")
        f.write("-- Risk Assessment Objek Wisata\n")
        f.write("-- Dari: Data_Personil_SatPamobvit_Resmi.xlsx\n")
        f.write("-- Username dan Password menggunakan NRP\n")
        f.write("-- =====================================================\n\n")
        f.write("USE risk_assessment_db;\n\n")
        
        f.write("-- Hapus data personil lama (optional)\n")
        f.write("-- DELETE FROM users WHERE role IN ('admin', 'penilai');\n\n")
        
        f.write("-- CATATAN: Password perlu di-hash menggunakan password_hash() di PHP\n")
        f.write("-- Script PHP untuk generate password hash ada di: generate_password_hash.php\n\n")
        
        f.write("INSERT INTO users (username, password, nama, pangkat_nrp, role) VALUES\n")
        
        values = []
        for p in personil_list:
            nrp = p.get('nrp', '').strip()
            # Hapus karakter non-digit dari NRP
            nrp_clean = re.sub(r'\D', '', str(nrp)) if nrp else ''
            
            username = nrp_clean  # Username = NRP
            password_plain = nrp_clean  # Password = NRP (akan di-hash di PHP)
            
            # Password hash akan di-generate di PHP, untuk sementara gunakan placeholder
            # User perlu menjalankan script PHP untuk generate hash yang benar
            password_hash = f"PASSWORD_HASH_{nrp_clean}"  # Placeholder, akan diganti di PHP
            
            nama = p.get('nama', '').replace("'", "''")  # Escape single quote
            pangkat = p.get('pangkat', '').strip()
            
            # Format pangkat_nrp
            if pangkat and nrp_clean:
                pangkat_nrp = f"{pangkat}/{nrp_clean}"
            elif pangkat:
                pangkat_nrp = pangkat
            elif nrp_clean:
                pangkat_nrp = f"/{nrp_clean}"
            else:
                pangkat_nrp = ''
            
            # Tentukan role
            role = determine_role(p.get('jabatan', ''))
            
            value = f"('{username}', '{password_hash}', '{nama}', '{pangkat_nrp}', '{role}')"
            values.append(value)
        
        f.write(",\n".join(values) + ";\n\n")
        
        f.write("-- =====================================================\n")
        f.write("-- UPDATE PASSWORD DENGAN HASH YANG BENAR\n")
        f.write("-- Jalankan script PHP: generate_password_hash.php\n")
        f.write("-- =====================================================\n")
    
    print(f"[SUCCESS] SQL INSERT disimpan ke: {output_file}")
    print(f"[INFO] Password masih placeholder, jalankan generate_password_hash.php untuk generate hash")

def save_to_csv(personil_list, output_file):
    """Simpan data ke file CSV"""
    import csv
    
    with open(output_file, 'w', newline='', encoding='utf-8') as f:
        fieldnames = ['no', 'nama', 'pangkat', 'nrp', 'jabatan', 'satker', 'username', 'password', 'role']
        writer = csv.DictWriter(f, fieldnames=fieldnames)
        writer.writeheader()
        
        for p in personil_list:
            nrp = p.get('nrp', '').strip()
            nrp_clean = re.sub(r'\D', '', str(nrp)) if nrp else ''
            
            row = {
                'no': p.get('no', ''),
                'nama': p.get('nama', ''),
                'pangkat': p.get('pangkat', ''),
                'nrp': nrp_clean,
                'jabatan': p.get('jabatan', ''),
                'satker': p.get('satker', ''),
                'username': nrp_clean,
                'password': nrp_clean,
                'role': determine_role(p.get('jabatan', ''))
            }
            writer.writerow(row)
    
    print(f"[SUCCESS] CSV disimpan ke: {output_file}")

def save_to_txt(personil_list, output_file):
    """Simpan data ke file teks"""
    with open(output_file, 'w', encoding='utf-8') as f:
        f.write("=" * 80 + "\n")
        f.write("DATA PERSONIL (DIPERBARUI) - RISK ASSESSMENT OBJEK WISATA\n")
        f.write("Dari: Data_Personil_SatPamobvit_Resmi.xlsx\n")
        f.write("=" * 80 + "\n\n")
        
        for i, p in enumerate(personil_list, 1):
            nrp = p.get('nrp', '').strip()
            nrp_clean = re.sub(r'\D', '', str(nrp)) if nrp else ''
            
            f.write(f"{i}. NRP: {nrp_clean}\n")
            f.write(f"   Nama: {p.get('nama', '')}\n")
            f.write(f"   Pangkat: {p.get('pangkat', '')}\n")
            f.write(f"   Jabatan: {p.get('jabatan', '')}\n")
            f.write(f"   Satker: {p.get('satker', '')}\n")
            f.write(f"   Username: {nrp_clean}\n")
            f.write(f"   Password: {nrp_clean}\n")
            f.write(f"   Role: {determine_role(p.get('jabatan', ''))}\n")
            f.write("-" * 80 + "\n")
        
        f.write(f"\nTotal: {len(personil_list)} personil\n")
        
        # Statistik
        admin_count = sum(1 for p in personil_list if determine_role(p.get('jabatan', '')) == 'admin')
        penilai_count = len(personil_list) - admin_count
        
        f.write(f"\nStatistik:\n")
        f.write(f"- Admin: {admin_count} orang\n")
        f.write(f"- Penilai: {penilai_count} orang\n")
    
    print(f"[SUCCESS] TXT disimpan ke: {output_file}")

def main():
    """Main function"""
    excel_path = Path('Data_Personil_SatPamobvit_Resmi.xlsx')
    
    if not excel_path.exists():
        print(f"[ERROR] File tidak ditemukan: {excel_path}")
        return
    
    print("Mengekstrak data personil dari Excel...")
    print("=" * 60)
    
    # Ekstrak data
    personil_list = extract_from_excel(excel_path)
    
    if not personil_list:
        print("[ERROR] Tidak ada data personil yang ditemukan!")
        return
    
    print(f"[SUCCESS] Ditemukan {len(personil_list)} personil")
    
    # Tampilkan preview
    print("\nPreview data (5 pertama):")
    for i, p in enumerate(personil_list[:5], 1):
        print(f"{i}. {p.get('nama', '')} - {p.get('pangkat', '')}/{p.get('nrp', '')}")
    
    # Simpan ke berbagai format
    base_dir = Path('.')
    
    # SQL
    sql_file = base_dir / 'sql' / 'data_personil.sql'
    sql_file.parent.mkdir(exist_ok=True)
    save_to_sql(personil_list, sql_file)
    
    # CSV
    csv_file = base_dir / 'data_personil.csv'
    save_to_csv(personil_list, csv_file)
    
    # TXT
    txt_file = base_dir / 'data_personil.txt'
    save_to_txt(personil_list, txt_file)
    
    print("\n" + "=" * 60)
    print("Ekstraksi selesai!")
    print(f"Total personil: {len(personil_list)}")

if __name__ == '__main__':
    main()

