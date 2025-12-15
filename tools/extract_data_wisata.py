#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Script untuk mengekstrak data objek wisata dari PDF
DATA WISATA ,OBVITNAS & OBVITER-1.pdf
"""

import re
from pathlib import Path

def extract_from_pdf(pdf_path):
    """Ekstrak data dari PDF menggunakan PyPDF2 atau pdfplumber"""
    try:
        import pdfplumber
        
        data_wisata = []
        
        with pdfplumber.open(pdf_path) as pdf:
            for page in pdf.pages:
                text = page.extract_text()
                
                # Parse data dari teks
                # Format: NO | NAMA OBJEK WISATA | KOTA/KAB | JENIS | WILKUM | KET
                lines = text.split('\n')
                
                for line in lines:
                    line = line.strip()
                    if not line or len(line) < 10:
                        continue
                    
                    # Skip header
                    if 'NO' in line and 'NAMA OBJEK WISATA' in line:
                        continue
                    if '---' in line:
                        continue
                    
                    # Pattern untuk mencari nomor di awal baris
                    match = re.match(r'^\s*(\d+)\s+(.+)$', line)
                    if match:
                        no = match.group(1)
                        rest = match.group(2)
                        
                        # Split berdasarkan | atau tab
                        parts = [p.strip() for p in re.split(r'\||\t', rest)]
                        
                        if len(parts) >= 5:
                            nama = parts[0] if len(parts) > 0 else ''
                            kota_kab = parts[1] if len(parts) > 1 else ''
                            jenis = parts[2] if len(parts) > 2 else ''
                            wilkum = parts[3] if len(parts) > 3 else ''
                            ket = parts[4] if len(parts) > 4 else ''
                            
                            if nama and nama != 'NAMA OBJEK WISATA':
                                data_wisata.append({
                                    'no': no,
                                    'nama': nama,
                                    'kota_kab': kota_kab,
                                    'jenis': jenis,
                                    'wilkum': wilkum,
                                    'ket': ket
                                })
        
        return data_wisata
    except ImportError:
        return None
    except Exception as e:
        print(f"Error: {str(e)}")
        return None

def get_manual_data():
    """Data manual berdasarkan isi PDF yang sudah diketahui"""
    data = [
        {'no': '1', 'nama': 'Pantai Pasir Putih Parbaba', 'kota_kab': 'Parbaba/Samosir', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Kawasan Ekonomi Khusus'},
        {'no': '2', 'nama': 'Pantai Indah Situngkir', 'kota_kab': 'Situngkir/Samosir', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '3', 'nama': 'Pantai Tanda Rabun', 'kota_kab': 'Desa Dos roha/Samosir', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '4', 'nama': 'Pantai Batu Hoda', 'kota_kab': 'Simanindo/Samosir', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '5', 'nama': 'Huta Sialagan', 'kota_kab': 'Siallagan/Samosir', 'jenis': 'Wisata Alam, Wisata Sejarah dan Budaya', 'wilkum': 'Polres Samosir', 'ket': 'Kawasan Ekonomi Khusus'},
        {'no': '6', 'nama': 'Patung Si Gale-gale', 'kota_kab': 'Tomok/Samosir', 'jenis': 'Wisata Sejarah dan Budaya', 'wilkum': 'Polres Samosir', 'ket': 'Kawasan Ekonomi Khusus'},
        {'no': '7', 'nama': 'Kawasan Tomok', 'kota_kab': 'Tomok/Samosir', 'jenis': 'Wisata Belanja', 'wilkum': 'Polres Samosir', 'ket': 'Kawasan Ekonomi Khusus'},
        {'no': '8', 'nama': 'Air Terjun Efrata', 'kota_kab': 'Harian/Samosir', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '9', 'nama': 'Pantai Pandua', 'kota_kab': 'Nainggolan/Samosir', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '10', 'nama': 'Pantai Indah Sipinggan', 'kota_kab': 'Sipinggan/Samosir', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '11', 'nama': 'Danau Aek Natonang', 'kota_kab': 'Tanjungan/Samosir', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '12', 'nama': 'Dermaga Jetty', 'kota_kab': 'Situngkir/Samosir', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '13', 'nama': 'Pantai Sigur-gur', 'kota_kab': 'Simanindo/Samosir', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '14', 'nama': 'Bukit Sibeabea', 'kota_kab': 'Harian/Samosir', 'jenis': 'Wisata Alam dan Wisata Religi', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '15', 'nama': 'Aek Sipitu Dai', 'kota_kab': 'Sianjur mula-mula/Samosir', 'jenis': 'Wisata budaya dan Wisata Religi', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '16', 'nama': 'Batu Sawan', 'kota_kab': 'Sianjur mula-mula /Samosir', 'jenis': 'Wisata budaya', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '17', 'nama': 'Air Terjun Naisogop', 'kota_kab': 'Sianjur mula-mula /Samosir', 'jenis': 'Wisata alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '18', 'nama': 'Menara Pandang Tele', 'kota_kab': 'Harian/Samosir', 'jenis': 'Wisata alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '19', 'nama': 'Kawasan Sigulatti-Pusat Informasi Geopark', 'kota_kab': 'Sianjur mula-mula /Samosir', 'jenis': 'Wisata budaya', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '20', 'nama': 'Ruma Parsaktian Guru Tatea Bulan', 'kota_kab': 'Sianjur mula-mula /Samosir', 'jenis': 'Wisata Budaya dan wisata religi', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '21', 'nama': 'Bukit Holbung', 'kota_kab': 'Dolokraja/Samosir', 'jenis': 'Wisata alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '22', 'nama': 'Pemandian Aek Rangat', 'kota_kab': 'Pangururan/Samosir', 'jenis': 'Wisata alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '23', 'nama': 'Sitalmaktalmak', 'kota_kab': 'Sihotang/Samosir', 'jenis': 'Wisata alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '24', 'nama': 'Kawasan Hutan Pinus', 'kota_kab': 'Harian/Samosir', 'jenis': 'Wisata alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '25', 'nama': 'Wetland Biocord-Putri Lopian (DLH)', 'kota_kab': 'Pangururan/Samosir', 'jenis': 'Wisata alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '26', 'nama': 'Kampung Tenun Lumban Suhi', 'kota_kab': 'Pangururan/ Samosir', 'jenis': 'Wisata Belanja', 'wilkum': 'Polres Samosir', 'ket': 'Kawasan Ekonomi Khusus'},
        {'no': '27', 'nama': 'Pantai Sibolazi', 'kota_kab': 'Simanindo/Samosir', 'jenis': 'Wisata alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '28', 'nama': 'Pantai Langat', 'kota_kab': 'Simanindo/Samosir', 'jenis': 'Wisata alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '29', 'nama': 'Sijukjuk Hill', 'kota_kab': 'Harian boho/Samosir', 'jenis': 'Wisata alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '30', 'nama': 'Bona Bona Hill', 'kota_kab': 'Harian boho/Samosir', 'jenis': 'Wisata alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '31', 'nama': 'Hidden Beach', 'kota_kab': 'Simanindo/Samosir', 'jenis': 'Wisata alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '32', 'nama': 'Kuburan diatas pohon', 'kota_kab': 'Tomok/Samosir', 'jenis': 'Wisata Religi', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '33', 'nama': 'Objek Wisata Lagundi', 'kota_kab': 'Onanrunggu/Samosir', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '34', 'nama': 'Museum Huta Bolon', 'kota_kab': 'Simanindo/Samosir', 'jenis': 'Wisata Budaya', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '35', 'nama': 'Tugu toga Sinaga', 'kota_kab': 'Palipi/Samosir', 'jenis': 'Wisata Budaya', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '36', 'nama': 'Danau Sidihoni', 'kota_kab': 'Pangururan/Samosir', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '37', 'nama': 'Aek Sipaloinggang', 'kota_kab': 'Pangururan/Samosir', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '38', 'nama': 'Aek Tawar', 'kota_kab': 'Ronggur Nihuta/Samosir', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '39', 'nama': 'Padan togu', 'kota_kab': 'Ronggur Nihuta/Samosir', 'jenis': 'Wisata Budaya', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '40', 'nama': 'Wisata Pangaloan', 'kota_kab': 'Nainggolan/Samosir', 'jenis': 'Wisata Budaya', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '41', 'nama': 'Batu Guru', 'kota_kab': 'Nainggolan/Samosir', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '42', 'nama': 'Mual Boru Saroding', 'kota_kab': 'Sitio-tio/Samosir', 'jenis': 'Wisata Budaya', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '43', 'nama': 'Dolok Sipatungan', 'kota_kab': 'Sitio-tio/ Samosir', 'jenis': 'Wisata alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '44', 'nama': 'Batu Parpadanan', 'kota_kab': 'Sitio-tio/ Samosir', 'jenis': 'Wisata alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '45', 'nama': 'Mual Boru Pareme', 'kota_kab': 'Sitio-tio/Samosir', 'jenis': 'Wisata Budaya', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '46', 'nama': 'Parhutaan Siraja Lottung', 'kota_kab': 'Sitio-tio/Samosir', 'jenis': 'Wisata Budaya', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '47', 'nama': 'Rumah Parsaktian Lumban Raja', 'kota_kab': 'Onanrunggu/Samosir', 'jenis': 'Wisata Budaya', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '48', 'nama': 'Mual Tornong Parhuta hutaan', 'kota_kab': 'Sitio-tio/ Samosir', 'jenis': 'Wisata Budaya', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '49', 'nama': 'Agrowisata Raptamawijaya', 'kota_kab': 'Ronggur Nihuta/Samosir', 'jenis': 'Agrowisata', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '50', 'nama': 'Batu Maroppa', 'kota_kab': 'Sitio-tio/Samosir', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '51', 'nama': 'Wisata Leluhur Gunung Uluh Darat', 'kota_kab': 'Sitio-tio/Samosir', 'jenis': 'Wisata alam dan Budaya', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '52', 'nama': 'Aek Bunga-bunga', 'kota_kab': 'Sianjur mula-mula/Samosir', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '53', 'nama': 'Tulas View', 'kota_kab': 'Pangururan/Samosir', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '54', 'nama': 'Perkampungan Siraja Batak', 'kota_kab': 'Sianjur Mula-mula/Samosir', 'jenis': 'Wisata Budaya', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '55', 'nama': 'Kawasan Wisata Desa Siboro', 'kota_kab': 'Sianjur Mula-mula/Samosir', 'jenis': 'Wisata alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '56', 'nama': 'Air Terjun Sampuran Pangaribuan', 'kota_kab': 'Palipi/Samosir', 'jenis': 'Wisata alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '57', 'nama': 'Siborbor Rinabolak', 'kota_kab': 'Onanrunggu/Samosir', 'jenis': 'Wisata alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '58', 'nama': 'Eco Village Silimalombu', 'kota_kab': 'Onanrunggu/Samosir', 'jenis': 'Agrowisata', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '59', 'nama': 'Hariara Bolon Sukkean', 'kota_kab': 'Onanrunggu/Samosir', 'jenis': 'Wisata Religi', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '60', 'nama': 'Liang Sipogu', 'kota_kab': 'Simanindo/Samosir', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '61', 'nama': 'Pea Porohan', 'kota_kab': 'Ronggur Nihuta/Samosir', 'jenis': 'Wisata alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '62', 'nama': 'Goa Lumban Dolok', 'kota_kab': 'Ronggur Nihuta/Samosir', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '63', 'nama': 'Sampuran Bona-bona', 'kota_kab': 'Ronggur Nihuta/Samosir', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '64', 'nama': 'Sampuran Sigaol', 'kota_kab': 'Ronggur Nihuta/Samosir', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '65', 'nama': 'Aek Marulak', 'kota_kab': 'Ronggur Nihuta/Samosir', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '66', 'nama': 'Batu Rante', 'kota_kab': 'Palipi/Samosir', 'jenis': 'Wisata Budaya', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '67', 'nama': 'Hariara Maranak', 'kota_kab': 'Palipi/Samosir', 'jenis': 'Wisata Budaya', 'wilkum': 'Polres Samosir', 'ket': 'Objek Wisata Biasa'},
        {'no': '68', 'nama': 'Jembatan tano Ponggol', 'kota_kab': 'Kec. Pangururan', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Vital nasional'},
        {'no': '69', 'nama': 'Water Front City', 'kota_kab': 'Kec. Pangururan', 'jenis': 'Wisata Alam', 'wilkum': 'Polres Samosir', 'ket': 'Objek Vital nasional'},
    ]
    
    return data

def save_to_sql(data_wisata, output_file):
    """Simpan data ke file SQL INSERT"""
    with open(output_file, 'w', encoding='utf-8') as f:
        f.write("-- =====================================================\n")
        f.write("-- DATA OBJEK WISATA\n")
        f.write("-- Risk Assessment Objek Wisata\n")
        f.write("-- Dari: DATA WISATA ,OBVITNAS & OBVITER-1.pdf\n")
        f.write("-- Wilayah: Polres Samosir\n")
        f.write("-- =====================================================\n\n")
        f.write("USE risk_assessment_db;\n\n")
        f.write("INSERT INTO objek_wisata (nama, alamat) VALUES\n")
        
        values = []
        for item in data_wisata:
            nama = item['nama'].replace("'", "''")  # Escape single quote
            
            # Gabungkan kota_kab, jenis, wilkum, dan ket sebagai alamat/info
            alamat_parts = []
            if item.get('kota_kab'):
                alamat_parts.append(f"Lokasi: {item['kota_kab']}")
            if item.get('jenis'):
                alamat_parts.append(f"Jenis: {item['jenis']}")
            if item.get('wilkum'):
                alamat_parts.append(f"Wilayah: {item['wilkum']}")
            if item.get('ket'):
                alamat_parts.append(f"Keterangan: {item['ket']}")
            
            alamat = " | ".join(alamat_parts)
            alamat = alamat.replace("'", "''")  # Escape single quote
            
            value = f"('{nama}', '{alamat}')"
            values.append(value)
        
        f.write(",\n".join(values) + ";\n")
    
    print(f"SQL INSERT disimpan ke: {output_file}")

def save_to_csv(data_wisata, output_file):
    """Simpan data ke file CSV"""
    import csv
    
    with open(output_file, 'w', newline='', encoding='utf-8') as f:
        writer = csv.DictWriter(f, fieldnames=['no', 'nama', 'kota_kab', 'jenis', 'wilkum', 'ket'])
        writer.writeheader()
        writer.writerows(data_wisata)
    
    print(f"CSV disimpan ke: {output_file}")

def save_to_txt(data_wisata, output_file):
    """Simpan data ke file teks"""
    with open(output_file, 'w', encoding='utf-8') as f:
        f.write("=" * 80 + "\n")
        f.write("DATA OBJEK WISATA - POLRES SAMOSIR\n")
        f.write("Risk Assessment Objek Wisata\n")
        f.write("=" * 80 + "\n\n")
        
        for item in data_wisata:
            f.write(f"{item['no']}. {item['nama']}\n")
            f.write(f"   Lokasi: {item.get('kota_kab', '')}\n")
            f.write(f"   Jenis: {item.get('jenis', '')}\n")
            f.write(f"   Wilayah: {item.get('wilkum', '')}\n")
            f.write(f"   Keterangan: {item.get('ket', '')}\n")
            f.write("-" * 80 + "\n")
        
        f.write(f"\nTotal: {len(data_wisata)} objek wisata\n")
    
    print(f"TXT disimpan ke: {output_file}")

def main():
    """Main function"""
    pdf_path = Path('DATA WISATA ,OBVITNAS & OBVITER-1.pdf')
    
    print("Mengekstrak data objek wisata dari PDF...")
    print("=" * 60)
    
    # Coba ekstrak dari PDF
    data_wisata = None
    if pdf_path.exists():
        print("\n1. Mencoba ekstraksi dari PDF...")
        data_wisata = extract_from_pdf(pdf_path)
    
    # Jika gagal, gunakan data manual
    if not data_wisata:
        print("[INFO] Ekstraksi PDF tidak berhasil atau tidak tersedia.")
        print("[INFO] Menggunakan data manual berdasarkan isi PDF...")
        data_wisata = get_manual_data()
    
    if not data_wisata:
        print("[ERROR] Tidak ada data yang ditemukan!")
        return
    
    print(f"\n[SUCCESS] Ditemukan {len(data_wisata)} objek wisata")
    
    # Simpan ke berbagai format
    base_dir = Path('.')
    
    # SQL
    sql_file = base_dir / 'sql' / 'data_objek_wisata.sql'
    sql_file.parent.mkdir(exist_ok=True)
    save_to_sql(data_wisata, sql_file)
    
    # CSV
    csv_file = base_dir / 'data_objek_wisata.csv'
    save_to_csv(data_wisata, csv_file)
    
    # TXT
    txt_file = base_dir / 'data_objek_wisata.txt'
    save_to_txt(data_wisata, txt_file)
    
    print("\n" + "=" * 60)
    print("Ekstraksi selesai!")
    print(f"Total objek wisata: {len(data_wisata)}")

if __name__ == '__main__':
    main()

