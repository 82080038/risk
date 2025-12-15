-- =====================================================
-- DATA PERSONIL (DIPERBARUI)
-- Risk Assessment Objek Wisata
-- Dari: Data_Personil_SatPamobvit_Resmi.xlsx
-- Username dan Password menggunakan NRP
-- =====================================================

USE risk_assessment_db;

-- Hapus data personil lama (optional)
-- DELETE FROM users WHERE role IN ('admin', 'penilai');

-- CATATAN:
-- Username = NRP (hanya angka)
-- Password = NRP (sama dengan username, sudah di-hash menggunakan bcrypt)
-- Password hash sudah di-generate dan siap digunakan

INSERT INTO users (username, password, nama, pangkat_nrp, role) VALUES
('72100664', '$2y$10$X98WuzkRgCzMOA9P.vjO7u8nkkjdC2p2/sOcsdOKDPsBcPRWhPKnS', 'Tangio Haojahan Sitanggang, S.H.', 'IPTU/72100664', 'admin'),
('69090552', '$2y$10$CLE/kHwmMcR13kKgzmEldum1igkXtZ5h9frBGM2B.R2NKgaK2MiYe', 'Rahmat Kurniawan', 'IPDA/69090552', 'penilai'),
('80070492', '$2y$10$qtim4WYlW1q0jIt9gYEHJumX1D5Kny80fMZWcPJ/VsyQ4qC4K8eKu', 'Aron Perangin-angin', 'AIPTU/80070492', 'penilai'),
('80100836', '$2y$10$ZnFCTC.tb0xxRjYYyqrRsOMm4Ghv/GkgIJDM/ylKk62494j2hM4O.', 'Maruba Nainggolan', 'AIPTU/80100836', 'penilai'),
('82080038', '$2y$10$m4U3Hx9z9r/gy2/ywUeIseK8m1EDovOhs8IvoTC9pqfhOk0GYQAUa', 'Patri Sihaloho', 'AIPDA/82080038', 'penilai'),
('80050898', '$2y$10$oXMoeKzYX8sl0JVZUVhd6OcKabBbWnzJEGcxj1WtQ0f/TQAl98hvC', 'Deny Wahyu', 'AIPDA/80050898', 'penilai'),
('80080892', '$2y$10$dIBPwCjKU2sGQVmY1MrIfuWtO/i/wPbIbwk2M0A1UC65ys0xAGdfC', 'Mangatur Tua Tindaon', 'AIPDA/80080892', 'penilai'),
('83050202', '$2y$10$7/j7bGYNfjYCcx7VNFaNjOfRXqSsTgyTbyTF66X2tXgR20FGVuN0O', 'Henri F. Sianipar', 'AIPDA/83050202', 'penilai'),
('85030645', '$2y$10$YM5zJhZTXtDOfv2VTsfbSufNnL2b4CAMPgSyVqMpB5odAtD8zrKze', 'Roy Haris St. Simaremare', 'AIPDA/85030645', 'penilai'),
('89080105', '$2y$10$pu7HtjrkWlXEHKmi0i831OrD3ka.oGFd5HzqJyCka7Zs9/wnBR3Xi', 'Claudius Haris Pardede', 'BRIGPOL/89080105', 'penilai'),
('96110130', '$2y$10$hyPcM/0gXJi8NgvdJvNxzusJQnixl5.CeGeLGt.RQyc0hq8RdwKWK', 'Rianto Sitanggang', 'BRIGPOL/96110130', 'penilai'),
('94090948', '$2y$10$gI09p4AkvHlo9gojoB9mge.1XImGjXWya3NBEct19V7A/FxSyDdoG', 'Roy Nanda Sembiring Ambaren', 'BRIGPOL/94090948', 'penilai'),
('96031057', '$2y$10$fa9mdlyWyZG4Yr0Yj7FLHubtmYyxzxPs8GwRgnV1o/Vlfl7CiNm4y', 'Candra Silalahi, S.H.', 'BRIPTU/96031057', 'penilai'),
('03020368', '$2y$10$aAusGodil0Au2nYTeMXY4eU4w164UmGKsZWo0qONc36DdPhLFu30a', 'Christian Prosperous Simanungkalit', 'BRIPDA/03020368', 'penilai'),
('01060884', '$2y$10$6SI.i38Vf8Ny3eW5.YVCZeKPOJaNrCPC4Obg4WKtviRa/N10DabGe', 'Horas J.M. Aritonang', 'BRIPDA/01060884', 'penilai'),
('02100599', '$2y$10$cYiR1KqkSmgBnTdbTKY5OObKEyRYp6hMz4lT6xFwQEGxPxxe.UNCC', 'Yunus Samdio Sidabutar', 'BRIPDA/02100599', 'penilai'),
('03010565', '$2y$10$n.SNvNzMQVouT3bUX9JOm.a3kPpU1SXsV90Bufj/Geu6yxy12CL9C', 'Rainheart Sitanggang', 'BRIPDA/03010565', 'penilai'),
('02011312', '$2y$10$KHBTKJBQSMTPf7I6O0BzUuRpIIVtqGb1wcCAYctLshj1TfDevDfWW', 'Bonifasius Nainggolan', 'BRIPDA/02011312', 'penilai'),
('00080816', '$2y$10$fP7x.o.Qj1tybeoj0Aszy.5VoY12XDeoF.SOcRwA9G.UTtb9XXrlC', 'Ray Yondo Siahaan', 'BRIPDA/00080816', 'penilai');

-- =====================================================
-- UPDATE PASSWORD DENGAN HASH YANG BENAR
-- Jalankan script PHP: generate_password_hash.php
-- =====================================================
