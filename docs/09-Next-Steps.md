# 📋 Langkah Selanjutnya (Next Steps)
> **Tanggal:** 18 Agustus 2026  
> **Status Saat Ini:** MVP Selesai — Audit & Cleanup ✅

---

## 🔴 Prioritas Tinggi (Harus Segera)

### 1. Setup Database & Seeder di Server
Saat ini database menggunakan SQLite lokal. Untuk production:
- [ ] Jalankan `php artisan migrate --seed` di server
- [ ] Buat akun admin pertama via seeder
- [ ] Pastikan `storage/` symlink dibuat: `php artisan storage:link`

### 2. Konfigurasi Fonnte (WhatsApp Gateway)
Notifikasi WhatsApp ke orang tua saat siswa absen **sudah ada kode backend-nya** (`FonnteService.php`, `SendWhatsAppNotification` job) tapi belum dikonfigurasi:
- [ ] Daftar akun di [fonnte.com](https://fonnte.com)
- [ ] Dapatkan API Token
- [ ] Isi di `backend/.env`:
  ```
  FONNTE_API_TOKEN=your_token_here
  ```
- [ ] Aktifkan dispatch job di `PpdbAdminController.php` (saat update status PPDB)

### 3. Upload Konten Asli Sekolah
- [ ] Ganti foto slider beranda dengan foto sekolah asli (via Admin → Pengaturan → Builder Beranda)
- [ ] Isi konten profil: Visi Misi, Sejarah, Struktur Organisasi, dll. (via Admin → Pengaturan → Konten Halaman Profil)
- [ ] Input data guru & staf yang sebenarnya (via Admin → Guru)
- [ ] Input data siswa & generate PIN absensi (via Admin → Siswa)

---

## 🟡 Prioritas Sedang (Minggu Depan)

### 4. Cetak Bukti Pendaftaran PPDB
Sesuai PRD, fitur ini belum diimplementasi:
- [ ] Tambahkan tombol "Cetak Bukti" di halaman sukses pendaftaran PPDB
- [ ] Generate halaman cetak berisi data registrasi + nomor pendaftaran

### 5. Generate PIN Massal
PRD menyebutkan fitur generate PIN untuk satu kelas sekaligus:
- [ ] Tambahkan endpoint `POST /api/admin/students/generate-pins`
- [ ] Tambahkan tombol "Generate PIN Massal" di Admin Siswa

### 6. Reset PIN Siswa
- [ ] Tambahkan tombol "Reset PIN" per-siswa di tabel Admin Siswa
- [ ] Endpoint: `PUT /api/admin/students/{id}/reset-pin`

### 7. Filter & Export Absensi
- [ ] Tambahkan filter per kelas di halaman Admin Absensi
- [ ] Tambahkan export ke Excel (selain CSV yang sudah ada)
- [ ] Tambahkan export ke PDF

---

## 🟢 Prioritas Rendah (Nice to Have)

### 8. Halaman Guru Publik
Rute `/teachers` sudah ada di backend, tapi belum ada halaman publik khusus:
- [ ] Buat `GuruView.vue` untuk menampilkan grid foto & nama guru
- [ ] Tambahkan rute `/guru` di router

### 9. Mega Menu Navbar (Standar Kemendikbud)
Sesuai dokumen `08-Addon-Fitur.md`:
- [ ] Ubah navbar di `PublicLayout.vue` dari single links → dropdown mega menu
- [ ] Kelompokkan: Profil Sekolah, Guru, Siswa, Alumni
- [ ] Responsive accordion di mobile

### 10. Galeri Publik — Lightbox
- [ ] Tambahkan efek lightbox (klik gambar → tampil besar) di `GaleriView.vue`

### 11. Dashboard Admin — Aktivitas Terbaru
Saat ini placeholder "Belum ada aktivitas terbaru":
- [ ] Tampilkan 5 aktivitas terakhir (login, artikel baru, siswa baru, dll.)

### 12. PWA (Progressive Web App)
- [ ] Tambahkan service worker agar bisa dibuka offline
- [ ] Tambahkan manifest.json untuk install di HP

---

## 🚀 Deployment / Go Live

### Opsi A: VPS (Recommended)
```
Server: Ubuntu 22.04+ / Windows Server
Web Server: Nginx / Apache
PHP: 8.2+
Database: MySQL 8 / PostgreSQL (ganti dari SQLite)
Domain: smpn1muarakaman.sch.id
SSL: Let's Encrypt (gratis)
```

Langkah deploy:
1. [ ] Sewa VPS (DigitalOcean / Vultr / IDCloudHost)
2. [ ] Install Nginx + PHP 8.2 + MySQL
3. [ ] Clone repo, setup `.env` production
4. [ ] Build frontend: `npm run build` → copy `dist/` ke public
5. [ ] Setup domain DNS → arahkan ke IP VPS
6. [ ] Pasang SSL dengan Certbot

### Opsi B: Shared Hosting
Jika budget terbatas, bisa pakai shared hosting Indonesia (Niagahoster, dll.):
1. [ ] Upload backend ke hosting via FTP
2. [ ] Build frontend (`npm run build`), upload `dist/` ke `public_html`
3. [ ] Setup subdomain untuk API

---

## 📌 Catatan Penting
- **Ganti password admin default** sebelum go-live!
- **Backup database** secara rutin setelah production
- **Ubah `APP_DEBUG=false`** di backend `.env` saat production
- **Ubah `APP_ENV=production`** di backend `.env`
