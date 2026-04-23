# PRD - Product Requirement

## 1. Overview

**Nama Aplikasi:** SIMPEL-CMT  
**Latar Belakang:**  
Aplikasi ini bertujuan untuk mendigitalkan proses pelaporan pekerjaan yang dilakukan oleh teknisi lapangan PT. Wahana Cahaya Sukses kepada PLN (Persero). Aplikasi ini akan mempermudah teknisi dalam membuat laporan pekerjaan, mengunggah foto dokumentasi, dan mengirimkannya ke admin untuk verifikasi. Selain itu, aplikasi ini juga akan mempermudah admin dalam memverifikasi laporan pekerjaan dan mengirimkannya ke pimpinan untuk ditinjau.  
**Tujuan Utama:** Memudahkan pegawai untuk menginput laporan di lapangan dan memudahkan pimpinan untuk mengexport laporan dalam format excel secara realtime beserta dengan foto dokumentasi yang diinput pegawai  
**Outcome:** Laporan kerja dalam bentuk excel

## 2. User

Disini aplikasi ada 3 user roles yaitu:

- Admin: Admin memiliki akses penuh untuk mengelola data pengguna (membuat, mengubah akses dan juga menonaktifkan login pengguna), laporan pekerjaan, dan foto dokumentasi. Admin juga dapat mengirimkan laporan pekerjaan ke pimpinan untuk ditinjau.
- Pimpinan: Pimpinan memiliki akses untuk melihat laporan pekerjaan yang telah dikirimkan oleh admin dan dapat mengexport laporan pekerjaan dalam format excel.
- Pegawai: Pegawai memiliki akses untuk membuat laporan pekerjaan, mengunggah foto dokumentasi, dan mengirimkannya ke admin untuk verifikasi.

## 3. Requirement

- User Management:
    - Admin dapat membuat, mengubah dan menonaktifkan user
    - Admin dapat menghapus user hanya jika user tidak ada data yang terikat

- Form Laporan pekerjaan:
    - Ada 2 tipe laporan. Laporan pemasangan Remote Control (RC) dan Pemasangan Ground Fault Detector (GFD)
    - Field ada yang berbeda akan di jabarkan di table database design
    - Pegawai dapat membuat laporan pekerjaan, mengisi form laporan pekerjaan dan commissioning (RC), mengunggah foto dokumentasi
    - Pegawai dapat mensubmit laporan
    - Pegawai dapat mengedit laporan yang belum disubmit
    - Pegawai dapat menghapus laporan yang belum disubmit
    - Pegawai dapat membatalkan laporan yang belum di verifikasi oleh admin (submit dan tolak)
    - Pegawai tidak dapat mengedit dan menghapus laporan yang sudah disubmit
    - Pegawai tidak dapat melihat laporan yang sudah disubmit oleh pegawai lain
    - Admin dapat melihat laporan, memverifikasi dan menolak laporan yang sudah disubmit
    - Admin tidak dapat mengedit dan menghapus laporan yang sudah disubmit
    - Pimpinan dapat mereview laporan yang sudah di verifikasi oleh admin
    - Admin dan Pimpinan dapat mengexport Laporan ke excel

- User dashboard:
    - Admin dapat melihat summary user (total user, user aktif, user nonaktif)
    - Admin dapat melihat summary laporan seperti laporan yang sudah submit pegawai tapi belum di approve/tolak, laporan yang sudah di approve tapi belum di review pimpinan.
    - Pimpinan dapat melihat summary laporan yang sudah diverifikasi

## 4. Workflow or User Flow

### 4.1 User Login

User memasukan email dan password guna memverifikasi identitas dan menentukan hak akses pengguna

- Aktor: Semua user
- Langkah-Langkah:
    1.  User memasukan email dan password jika valid dan user tidak di block atau di nonaktifkan oleh admin maka user akan diarahkan ke dashboard sesuai dengan hak aksesnya
    2.  Jika user di block munculkan pesan user sedang di blok atau di nonaktifkan admin muncul kan peringatan user tidak ada.

### 4.2 Configuration

User mengkonfigurasi form laporan seperti commisioning yang harus diisi pegawai dan daftar uraian pemeriksaan untuk form pemasangan ground fauld detector (GFD)

- Aktor: Admin
- Alur:
    1. Admin membuka menu configuration
    2. Admin mengkonfigurasi form laporan
    3. Admin menyimpan konfigurasi

### 4.3 Pegawai Membuat Laporan

Pegawai membuat laporan baru

- Aktor: Pegawai
- Alur:
    1.  Pegawai membuat laporan baru
    2.  Pegawai mengisi form laporan
    3.  Pegawai mengunggah foto dokumentasi
    4.  Pegawai mengirimkan/submit laporan

### 4.4 Admin Memverifikasi Laporan

Admin akan memverifikasi laporan

- Aktor: Admin
- Alur
    1. Admin membuka laporan yang masih dalam status submit
    2. Admin melihat laporan
    3. Admin klik tombolol verifikasi laporan
    4. status laporan menjadi terverifikasi

### 4.5 Admin menolak laporan

- Aktor: Admin
- Alur:
    1. Admin membuka laporan yang masih dalam status submit
    2. Admin melihat laporan
    3. Admin menekan tombol tolak
    4. Admin memasukan catatan penolakan
    5. Admin menekan tombol simpan
    6. Status laporan menjadi ditolak

### 4.6 Pegawai memperbaiki laporan yang ditolak

- Aktor: Pegawai
- Alur:
    1. membuka menu laporan
    2. memfilter laporan yang ditolak
    3. mengedit laporan yang ditolak
    4. mensubmit ulang laporan yang ditolak
    5. status laporan menjadi disubmit

### 4.7 Pimpinan mereview laporan

- Aktor: Pimpinan
- Alur:
    1. membuka daftar laporan yang sudah terverifikasi
    2. membuka laporan yang sudah terverifikasi
    3. melihat laporan
    4. menekan tombol review
    5. status laporan menjadi direview

### 4.8 Memprint/export laporan ke dalam excel

- Aktor: Pimpinan dan Admin
- Alur:
    1. membuka daftar laporan yang sudah direview
    2. mengklik tombol export

## 5 Desain Data

- Users
    - id
    - nama
    - email
    - password
    - roles

- Remote Control Type Sigal
    - id
    - name

- Remote Control Point
    - id
    - type_signal_id (many2one ke Remote Control Type Sigal)
    - name

- Task Report Arah Remote Control List
    - id
    - report_id
    - arah_remote_control
    - penyulang

- Task Report Remote Control Comisioning List
    - id
    - database (varchar)
    - point (many2one ke Remote Control Point)
    - terminal_kubiker (varchar)
    - signaling_gh (boolean)
    - signaling_dcc (boolean)
    - control_dcc_rtu (bool)
    - control_dcc_rele/plat (bool)
    - control_dcc_lbs (bool)
    - arah_remote_control
    - note (bool)

- Task Report Gambar Dokumentasi
    - id
    - description
    - image

- Ground Fault Detector Uraian Pemeriksaan Item
    - id
    - name (example: Elektronik GFD, CT GFD, Instalasi Power Supply TR, Lampu Indikator, battery, wiring power supply, etc)

- Task Report Ground Fault Detector Uraian Pemeriksaan
    - id
    - item_id (Ground Fault Detector Uraian Pemeriksaan Item)
    - ada (bool)
    - tidak ada (bool)
    - rusak (bool)

- Task Reports
    - field general
        - id
        - type (pemasangan remote control / pemasangan ground fault detector)
        - date
        - status (disubmit, terverifikasi, tertolak, direview)
        - user_id
        - gardu (varchar)
        - address (varchar)
        - latitude
        - longtitude
        - arah (varchar)
        - notes (varchar)
        - one2many ke Task Report Gambar Dokumentasi
    - field pemasang remote control
        - rtu (varchar)
        - modem (varchar)
        - one2many ke Task Report Arah Remote Control List
        - one2many ke Task Report Remote Control Comisioning List
    - field pemasang ground fault detector
        - task_type (varchar)
        - penyulang
        - gardu_induk
        - old_gfd (varchar)
        - old_gfd_type_serial_number (varchar)
        - old_gfd_setting_kepekaan_arus
        - old_gfd_setting_kepekaan_waktu
        - old_gfd_setting_waktu
        - old_gfd_injek_arus
        - old_gfd_condition
        - new_gfd (varchar)
        - new_gfd_type_serial_number (varchar)
        - new_gfd_setting_kepekaan_arus
        - new_gfd_setting_kepekaan_waktu
        - new_gfd_setting_waktu
        - new_gfd_injek_arus
        - new_gfd_condition
        - one2many ke Task Report Ground Fault Detector Uraian Pemeriksaan

## Technical Specs

- Menggunakan Framework laravel 13
- Menggunakan Database MySQL
- Menggunakan Tailwind CSS
- Menggunakan Alpine JS
- Menggunakan Laravel Jetstream
- ubah sesuai tema halaman login, profile pengguna, reset password dan halaman default laravel lainnya
