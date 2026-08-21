# Product Requirements Document (PRD)
## Sistem Informasi Terpadu SMPN 1 Muara Kaman

> **Versi:** 2.0 (Revisi — diselaraskan dengan Proposal Biaya)
> **Tanggal:** 18 Agustus 2026

---

### 1. Ringkasan Proyek
Proyek ini bertujuan membangun **Sistem Informasi Terpadu** untuk SMPN 1 Muara Kaman yang mencakup:
- **Portal Publik** — Company Profile (profil sekolah, berita, galeri)
- **PPDB Online** — Pendaftaran siswa baru secara digital
- **Absensi Digital** — Pencatatan kehadiran siswa berbasis PIN dengan notifikasi WhatsApp ke orang tua
- **Dashboard Admin** — Manajemen seluruh konten dan data

### 2. Tujuan
- **Digitalisasi Informasi:** Mempermudah penyampaian informasi sekolah kepada siswa, orang tua, dan masyarakat luas.
- **Efisiensi PPDB:** Mengotomatiskan proses pendaftaran siswa baru secara online.
- **Monitoring Kehadiran:** Mencatat kehadiran siswa secara digital dengan notifikasi real-time ke orang tua via WhatsApp.
- **Manajemen Internal:** Menyediakan platform untuk mengelola konten web, data guru, siswa, dan absensi.

### 3. Target Pengguna
| Pengguna | Akses | Kebutuhan |
|---|---|---|
| **Masyarakat Umum** | Publik | Melihat profil sekolah, berita, galeri |
| **Calon Siswa & Orang Tua** | Publik | Mendaftar PPDB online, cek status |
| **Siswa Aktif** | Absensi | Input PIN untuk absensi check-in & check-out |
| **Orang Tua Siswa Aktif** | Notifikasi | Menerima notifikasi WhatsApp saat anak absen |
| **Admin Sekolah** | Dashboard | Mengelola seluruh konten, data, dan laporan |

---

### 4. Fitur Utama (6 Modul)

#### Modul 1: 🏫 Profil Sekolah & Informasi Umum
**Sifat:** Publik — **Konten editable via Admin Dashboard**

- Halaman Beranda (Hero section, sambutan Kepala Sekolah, berita terbaru).
- Profil (Sejarah, Visi Misi, Struktur Organisasi).
- Fasilitas & Ekstrakurikuler.
- Halaman Kontak (Formulir kontak & Lokasi Google Maps).
- *Semua konten halaman statis dikelola via CMS admin (tabel `pages`).*

#### Modul 2: 📰 Berita, Pengumuman & Galeri
**Sifat:** Publik (baca) + Admin (kelola)

- Daftar berita dengan thumbnail, tanggal, dan ringkasan (paginated).
- Halaman detail berita (full article).
- Galeri Foto (grid foto kegiatan sekolah).
- Galeri Video (**embed YouTube** saja, bukan upload video langsung).
- Admin: CRUD berita/artikel + upload gambar.
- Admin: CRUD galeri foto & link video YouTube.

#### Modul 3: 👩‍🏫 Data Guru & Staff
**Sifat:** Publik (tampilan) + Admin (kelola)

- Tampilan daftar guru & staf dalam format card/grid (nama, foto, jabatan).
- Admin: CRUD data guru/staf.

#### Modul 4: 📝 PPDB Online (Penerimaan Peserta Didik Baru)
**Sifat:** Publik (pendaftaran) + Admin (verifikasi)

- Halaman info PPDB (jadwal, syarat, alur pendaftaran).
- Formulir pendaftaran online (data diri calon siswa + data orang tua).
- Upload berkas: **1 file PDF gabungan** (semua dokumen digabung jadi satu PDF).
- Cetak bukti pendaftaran (generate halaman cetak/PDF).
- Cek status pendaftaran (input nomor registrasi).
- Admin: Verifikasi berkas, ubah status (pending → verified → accepted / rejected).

#### Modul 5: 📋 Absensi Digital (Berbasis PIN Siswa)
**Sifat:** Internal + Notifikasi

- **Halaman Absensi (Kiosk/Fleksibel):**
  - Bisa diakses dari tablet di sekolah (mode kiosk) maupun HP siswa.
  - Siswa memasukkan PIN → tercatat **check-in** (masuk) atau **check-out** (pulang).
  - Sistem otomatis mendeteksi apakah ini check-in pertama atau check-out.
- **PIN Management:**
  - PIN **auto-generate** oleh sistem (4 digit angka unik).
  - Admin bisa reset/regenerate PIN siswa kapan saja.
- **Notifikasi WhatsApp:**
  - Saat siswa check-in → orang tua menerima notifikasi WhatsApp: *"[Nama Siswa] telah hadir di sekolah pukul [HH:MM]"*.
  - Saat siswa check-out → orang tua menerima notifikasi: *"[Nama Siswa] telah pulang dari sekolah pukul [HH:MM]"*.
  - Menggunakan **Fonnte API** (layanan WhatsApp Gateway populer di Indonesia, harga terjangkau).
- **Admin: Rekap Absensi:**
  - Tabel rekap per kelas, per tanggal, dengan filter.
  - Export laporan ke Excel/PDF.
- **Admin: Kelola Siswa:**
  - CRUD data siswa (nama, NIS, NISN, kelas, PIN, nomor HP orang tua).
  - Generate PIN massal untuk satu kelas.

#### Modul 6: ⚙️ Dashboard Admin & Manajemen Konten
**Sifat:** Private (admin yang login saja)

- Autentikasi (Login/Logout) — Laravel Sanctum.
- Dashboard ringkasan (statistik jumlah siswa, pendaftar PPDB, berita, kehadiran hari ini).
- Manajemen Halaman Statis (edit konten profil, visi misi, dll via editor teks).
- Manajemen Berita/Artikel (CRUD + upload gambar).
- Manajemen Galeri (CRUD foto + link video YouTube).
- Manajemen Data Guru & Staf (CRUD).
- Manajemen PPDB (verifikasi, ubah status).
- Manajemen Siswa & PIN (CRUD + generate PIN).
- Rekap & Export Absensi.
- Pengaturan Situs (nama sekolah, alamat, kontak, buka/tutup PPDB).

---

### 5. Timeline & Roadmap
| Fase | Deskripsi | Estimasi |
|---|---|---|
| **Fase 1** | Setup Infrastructure, Auth, Dashboard Admin & Halaman Statis | Minggu ke-1 |
| **Fase 2** | Profil Sekolah (Public) + Berita & Galeri + Data Guru | Minggu ke-2 |
| **Fase 3** | Modul PPDB Online (Full) | Minggu ke-3 |
| **Fase 4** | Modul Absensi Digital + Integrasi WhatsApp + Testing | Minggu ke-4 |

**Total: 3–4 Minggu** (sesuai Proposal Biaya)
