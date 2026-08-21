# 📋 Rekap Fitur Sistem Informasi Terpadu SMPN 1 Muara Kaman

Dokumen ini berisi daftar keseluruhan fitur yang tersedia dalam sistem informasi web SMPN 1 Muara Kaman (Fase MVP). Cocok digunakan sebagai lampiran proposal atau materi presentasi kepada pihak klien/sekolah.

---

## 🌐 1. Fitur Halaman Publik
Halaman ini adalah wajah depan sekolah yang bisa diakses oleh masyarakat umum (wali murid, calon siswa, publik) tanpa perlu login.

- **Beranda Dinamis**: Menampilkan slider foto pahlawan/sekolah, teks sambutan kepala sekolah, statistik (jumlah siswa/guru), dan ekstrakurikuler pilihan.
- **Profil Lengkap Sekolah**: Memuat informasi pada halaman khusus seperti Visi & Misi, Sejarah Sekolah, Fasilitas, dan Struktur Organisasi.
- **Portal Berita & Artikel**: Menampilkan berita terbaru sekolah, pengumuman, atau artikel kegiatan yang dipublikasikan oleh admin.
- **Galeri Foto & Video**: Menampilkan dokumentasi visual kegiatan sekolah (mendukung upload gambar langsung atau menautkan link video dari YouTube).
- **Formulir PPDB Online**: Calon siswa baru dapat mengisi formulir pendaftaran secara online langsung melalui website.
- **Portal Quick Access**: Akses jalan pintas ke sistem pendukung akademik lainnya (contoh: E-Raport atau sistem Ujian CBT).

---

## ⚙️ 2. Fitur Panel Admin
Panel kontrol (*backend*) yang hanya bisa diakses menggunakan kredensial login (email & password) oleh administrator atau operator sekolah. Diakses melalui rute `/admin`.

- **Dashboard Statistik**: Memantau ringkasan data operasional seperti total siswa aktif, jumlah tenaga pendidik, total artikel, dan pendaftar PPDB terbaru.
- **Manajemen Berita (Blog)**: Admin memiliki kendali penuh (Tambah, Edit, Hapus) untuk mempublikasikan berita/artikel sekolah beserta gambar sampul.
- **Manajemen Galeri**: Mengelola album foto kegiatan atau menambahkan galeri video YouTube.
- **Manajemen Data Siswa & Guru**: Mengelola database siswa (digunakan untuk sistem absen Kiosk) dan mengelola profil detail dewan guru.
- **Manajemen Pendaftar PPDB**: Melihat data calon siswa yang masuk, memverifikasi dokumen, dan mengubah status pendaftaran (Diterima/Ditolak/Menunggu).
- **Rekap Data Absensi**: Melacak histori dan jam kehadiran harian siswa yang masuk melalui mesin Kiosk.
- **Website Builder & Pengaturan**: Modul CMS (*Content Management System*) untuk mengganti identitas web (nama sekolah, logo, motto), teks sambutan kepsek, dan mengatur gambar *slider* beranda tanpa perlu menyentuh kode pemrograman.

---

## 👆 3. Fitur Kiosk Absensi Digital
Modul absensi interaktif (*self-service*) yang didesain untuk dijalankan secara *fullscreen* pada layar sentuh (tablet) atau komputer di gerbang utama / ruang piket sekolah. Diakses melalui rute `/kiosk`.

- **Input PIN Absen**: Siswa cukup menekan angka di layar (memasukkan NIS dan PIN Rahasia 4-digit) untuk merekam kehadiran mereka di pagi hari.
- **Pencatatan Waktu Real-time**: Sistem akan langsung memvalidasi input dan mencatat jam kedatangan siswa secara akurat ke dalam database Admin.
- **Feedback Interaktif**: Layar menampilkan notifikasi sukses/gagal secara visual setelah siswa melakukan percobaan absen.

---

*(Catatan Pengembangan: Modul **Notifikasi WhatsApp Otomatis** ke nomor Orang Tua saat siswa berhasil melakukan absensi di Kiosk telah disiapkan di sisi infrastruktur backend (Fonnte API), dan siap untuk dikonfigurasi/diaktifkan pada fase "Next Steps").*
