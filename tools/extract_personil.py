#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Script untuk mengekstrak data personil dari gambar personil.jpg
Menggunakan OCR (Optical Character Recognition) untuk membaca teks dari gambar
"""

import os
import re
from pathlib import Path

def extract_with_pytesseract(image_path):
    """Ekstrak data menggunakan pytesseract (Tesseract OCR)"""
    try:
        import pytesseract
        from PIL import Image
        
        # Baca gambar
        img = Image.open(image_path)
        
        # Ekstrak teks menggunakan OCR
        text = pytesseract.image_to_string(img, lang='ind')
        
        return text
    except ImportError:
        return None
    except Exception as e:
        print(f"Error dengan pytesseract: {str(e)}")
        return None

def extract_with_easyocr(image_path):
    """Ekstrak data menggunakan EasyOCR"""
    try:
        import easyocr
        
        reader = easyocr.Reader(['id', 'en'])
        results = reader.readtext(image_path)
        
        # Gabungkan semua teks
        text = '\n'.join([result[1] for result in results])
        
        return text
    except ImportError:
        return None
    except Exception as e:
        print(f"Error dengan easyocr: {str(e)}")
        return None

def parse_personil_data(text):
    """Parse data personil dari teks yang diekstrak"""
    personil_list = []
    
    if not text:
        return personil_list
    
    # Pattern untuk mencari NRP (8 digit angka)
    nrp_pattern = r'\b\d{8}\b'
    
    # Split per baris
    lines = text.split('\n')
    
    current_personil = {}
    
    for line in lines:
        line = line.strip()
        if not line:
            continue
        
        # Cari NRP
        nrp_match = re.search(nrp_pattern, line)
        if nrp_match:
            nrp = nrp_match.group()
            
            # Jika sudah ada personil sebelumnya, simpan
            if current_personil:
                personil_list.append(current_personil)
            
            # Mulai personil baru
            current_personil = {
                'nrp': nrp,
                'nama': '',
                'pangkat': '',
                'role': ''
            }
            
            # Ekstrak nama dan pangkat dari baris yang sama
            # Hapus NRP dari line untuk mendapatkan nama/pangkat
            line_without_nrp = line.replace(nrp, '').strip()
            
            # Pattern untuk pangkat (IPTU, IPDA, AIPTU, dll)
            pangkat_pattern = r'\b(IPTU|IPDA|AIPTU|AIPDA|BRIGPOL|BRIPTU|BRIPDA|KOMBES|KOMBES|AKBP|KOMPOL|AKP|IPTU|IPDA|AIPTU|AIPDA|BRIGPOL|BRIPTU|BRIPDA)\b'
            pangkat_match = re.search(pangkat_pattern, line_without_nrp, re.IGNORECASE)
            
            if pangkat_match:
                current_personil['pangkat'] = pangkat_match.group().upper()
                # Hapus pangkat dari line untuk mendapatkan nama
                nama_part = line_without_nrp.replace(pangkat_match.group(), '').strip()
                current_personil['nama'] = nama_part
            else:
                current_personil['nama'] = line_without_nrp
        
        # Cari role/jabatan
        role_keywords = [
            'SATPALOVIT', 'KASAT PAMOBVIT', 'KAURBINOPS', 
            'KANITPAMWISATA', 'KANIT PAMWASTER', 'PANITPAMWISATA',
            'PANIT PAMWASTER', 'KAURMINTU', 'BINTARA SAT PAMOBVIT'
        ]
        
        for keyword in role_keywords:
            if keyword.lower() in line.lower():
                current_personil['role'] = keyword
                break
    
    # Simpan personil terakhir
    if current_personil:
        personil_list.append(current_personil)
    
    return personil_list

def extract_manual_data():
    """Data personil berdasarkan deskripsi gambar (manual extraction)"""
    personil_data = [
        {'nrp': '72100664', 'nama': 'TANGIO HAOJAHAN SITANGGANG, S.H.', 'pangkat': 'IPTU', 'role': 'SATPALOVIT'},
        {'nrp': '69090552', 'nama': 'RAHMAT KURNIAWAN', 'pangkat': 'IPDA', 'role': 'KASAT PAMOBVIT'},
        {'nrp': '80070492', 'nama': 'ARON PERANGIN-ANGIN', 'pangkat': 'AIPTU', 'role': 'KAURBINOPS'},
        {'nrp': '80100836', 'nama': 'MARUBA NAINGGOLAN', 'pangkat': 'AIPDA', 'role': 'PS. KANITPAMWISATA'},
        {'nrp': '82080038', 'nama': 'PATRI SIHALOHO', 'pangkat': 'BRIGPOL', 'role': 'PS. KANIT PAMWASTER'},
        {'nrp': '80050898', 'nama': 'M. DENY WAHYU', 'pangkat': 'BRIPTU', 'role': 'PS. PANITPAMWISATA'},
        {'nrp': '80080892', 'nama': 'MANGATUR TUA TINDAON', 'pangkat': 'BRIPTU', 'role': 'PS. PANIT PAMWASTER'},
        {'nrp': '83050202', 'nama': 'HENRI F. SIANIPAR', 'pangkat': 'BRIPDA', 'role': 'PS. KAURMINTU'},
        {'nrp': '85030645', 'nama': 'ROY HARIS ST. SIMARE MARE', 'pangkat': 'BRIGPOL', 'role': 'BINTARA SAT PAMOBVIT'},
        {'nrp': '89080105', 'nama': 'CLAUDIUS HARIS PARDEDE', 'pangkat': 'BRIPTU', 'role': 'BINTARA SAT PAMOBVIT'},
        {'nrp': '91110130', 'nama': 'RIANTO SITANGGANG', 'pangkat': 'BRIPTU', 'role': 'BINTARA SAT PAMOBVIT'},
        {'nrp': '94090948', 'nama': 'ROY NANDA SEMBIRING EMBAREN', 'pangkat': 'BRIPDA', 'role': 'BINTARA SAT PAMOBVIT'},
        {'nrp': '96031057', 'nama': 'CANDRA SILALAHI, S.H.', 'pangkat': 'BRIPDA', 'role': 'BINTARA SAT PAMOBVIT'},
        {'nrp': '03020368', 'nama': 'CHRISTIAN PROSPEROUS SIMANUNGKALIT', 'pangkat': 'BRIPDA', 'role': 'BINTARA SAT PAMOBVIT'},
        {'nrp': '01060884', 'nama': 'HORAS J.M. ARITONANG', 'pangkat': 'BRIPDA', 'role': 'BINTARA SAT PAMOBVIT'},
        {'nrp': '02100599', 'nama': 'YUNUS SAMDIO SIDABUTAR', 'pangkat': 'BRIPDA', 'role': 'BINTARA SAT PAMOBVIT'},
        {'nrp': '03010565', 'nama': 'RAINHEART SITANGGANG', 'pangkat': 'BRIPDA', 'role': 'BINTARA SAT PAMOBVIT'},
        {'nrp': '02011312', 'nama': 'BONIFASIUS NAINGGOLAN', 'pangkat': 'BRIPDA', 'role': 'BINTARA SAT PAMOBVIT'},
        {'nrp': '', 'nama': 'RAY YONDO SIALHAAN', 'pangkat': 'BRIPDA', 'role': 'BINTARA SAT PAMOBVIT'},
    ]
    
    return personil_data

def save_to_csv(personil_list, output_file):
    """Simpan data ke file CSV"""
    import csv
    
    with open(output_file, 'w', newline='', encoding='utf-8') as f:
        writer = csv.DictWriter(f, fieldnames=['nrp', 'nama', 'pangkat', 'role'])
        writer.writeheader()
        writer.writerows(personil_list)
    
    print(f"Data disimpan ke: {output_file}")

def save_to_sql(personil_list, output_file):
    """Simpan data ke file SQL INSERT"""
    with open(output_file, 'w', encoding='utf-8') as f:
        f.write("-- =====================================================\n")
        f.write("-- DATA PERSONIL\n")
        f.write("-- Risk Assessment Objek Wisata\n")
        f.write("-- =====================================================\n\n")
        f.write("USE risk_assessment_db;\n\n")
        f.write("INSERT INTO users (username, password, nama, pangkat_nrp, role) VALUES\n")
        
        values = []
        for i, p in enumerate(personil_list):
            # Generate username dari nama (lowercase, replace spasi dengan underscore)
            username = p['nama'].lower().replace(' ', '_').replace(',', '').replace('.', '')[:50]
            # Password default: password123 (harus di-hash di aplikasi)
            password_hash = "$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi"  # password123
            nama = p['nama'].replace("'", "''")  # Escape single quote
            pangkat_nrp = f"{p['pangkat']}/{p['nrp']}" if p['nrp'] else p['pangkat']
            role = 'penilai'  # Default role
            
            # Jika ada role khusus, bisa disesuaikan
            if 'KASAT' in p.get('role', ''):
                role = 'admin'
            
            value = f"('{username}', '{password_hash}', '{nama}', '{pangkat_nrp}', '{role}')"
            values.append(value)
        
        f.write(",\n".join(values) + ";\n")
    
    print(f"SQL INSERT disimpan ke: {output_file}")

def save_to_txt(personil_list, output_file):
    """Simpan data ke file teks"""
    with open(output_file, 'w', encoding='utf-8') as f:
        f.write("=" * 80 + "\n")
        f.write("DATA PERSONIL - RISK ASSESSMENT OBJEK WISATA\n")
        f.write("=" * 80 + "\n\n")
        
        for i, p in enumerate(personil_list, 1):
            f.write(f"{i}. NRP: {p['nrp']}\n")
            f.write(f"   Nama: {p['nama']}\n")
            f.write(f"   Pangkat: {p['pangkat']}\n")
            f.write(f"   Role: {p['role']}\n")
            f.write("-" * 80 + "\n")
    
    print(f"Data teks disimpan ke: {output_file}")

def main():
    """Main function"""
    image_path = Path('personil.jpg')
    
    if not image_path.exists():
        print(f"File tidak ditemukan: {image_path}")
        return
    
    print("Mengekstrak data personil dari gambar...")
    print("=" * 60)
    
    # Coba ekstrak dengan OCR
    text = None
    
    # Coba pytesseract dulu
    print("\n1. Mencoba ekstraksi dengan pytesseract...")
    text = extract_with_pytesseract(image_path)
    
    if not text:
        # Coba easyocr
        print("2. Mencoba ekstraksi dengan easyocr...")
        text = extract_with_easyocr(image_path)
    
    if text:
        print("✓ OCR berhasil!")
        print("\nTeks yang diekstrak:")
        print("-" * 60)
        print(text[:500])  # Print sebagian teks
        print("-" * 60)
        
        # Parse data
        personil_list = parse_personil_data(text)
    else:
        print("⚠ OCR tidak tersedia atau gagal.")
        print("Menggunakan data manual berdasarkan deskripsi gambar...")
        personil_list = extract_manual_data()
    
    if not personil_list:
        print("❌ Tidak ada data personil yang ditemukan!")
        return
    
    print(f"\n✓ Ditemukan {len(personil_list)} personil")
    
    # Simpan ke berbagai format
    base_dir = Path('.')
    
    # CSV
    csv_file = base_dir / 'data_personil.csv'
    save_to_csv(personil_list, csv_file)
    
    # SQL
    sql_file = base_dir / 'sql' / 'data_personil.sql'
    sql_file.parent.mkdir(exist_ok=True)
    save_to_sql(personil_list, sql_file)
    
    # TXT
    txt_file = base_dir / 'data_personil.txt'
    save_to_txt(personil_list, txt_file)
    
    print("\n" + "=" * 60)
    print("Ekstraksi selesai!")
    print(f"Total personil: {len(personil_list)}")

if __name__ == '__main__':
    main()

