# SMPN 1 Muara Kaman — Sistem Informasi Terpadu

Sistem informasi sekolah berbasis web untuk **SMP Negeri 1 Muara Kaman**, Kabupaten Kutai Kartanegara, Kalimantan Timur.

## Arsitektur

| Layer    | Teknologi                         | Port Default |
|----------|-----------------------------------|-------------|
| Frontend | Vue 3 + Vite + Pinia              | `5173`      |
| Backend  | Laravel 11 + Sanctum (SQLite)     | `8000`      |

## Struktur Proyek

```
smpn1-muarakaman/
├── backend/          # Laravel 11 API (PHP)
├── frontend/         # Vue 3 SPA (JavaScript)
└── docs/             # Dokumentasi proyek (PRD, SDD, Database, dll.)
```

## Cara Menjalankan

### 1. Backend (Laravel)

```bash
cd backend
composer install
php artisan migrate --seed
php artisan serve --port=8000
```

### 2. Frontend (Vue)

```bash
cd frontend
npm install
npm run dev
```

Buka **http://localhost:5173** di browser.

## Fitur Utama

### Halaman Publik
- Homepage dengan slider dinamis
- Profil sekolah multi-halaman (Visi-Misi, Sejarah, Fasilitas, dll.)
- Berita & artikel
- Galeri foto & video
- Formulir PPDB online
- Halaman kontak

### Panel Admin (`/admin`)
- Dashboard statistik
- CRUD Berita, Guru, Galeri, Siswa
- Manajemen pendaftar PPDB
- Rekap absensi (Kiosk)
- Builder Beranda & Footer
- Pengaturan umum sekolah

### Kiosk Absensi (`/kiosk`)
- Absensi siswa menggunakan NIS + PIN

## Akun Default

| Email                          | Password   |
|--------------------------------|------------|
| `admin@smpn1muarakaman.sch.id` | `password` |

## Lisensi

Hak cipta © 2026 SMP Negeri 1 Muara Kaman. Seluruh hak dilindungi.
