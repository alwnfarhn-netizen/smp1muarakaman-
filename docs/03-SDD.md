# Software Design Document (SDD)
## Sistem Informasi Terpadu SMPN 1 Muara Kaman

> **Versi:** 2.0 (Revisi — diselaraskan dengan seluruh 6 modul)
> **Tanggal:** 18 Agustus 2026

---

### 1. Technology Stack

#### Backend
| Teknologi | Versi | Keterangan |
|---|---|---|
| **Laravel** | 11.x | Framework utama (RESTful API) |
| **PHP** | 8.2+ | Bahasa pemrograman |
| **Laravel Sanctum** | — | Cookie-based SPA authentication |
| **Laravel Queue** | — | Async job processing (untuk kirim WhatsApp) |
| **Fonnte SDK/API** | — | WhatsApp Gateway integration |
| **Laravel Excel** | (maatwebsite) | Export data ke Excel |
| **DomPDF / Snappy** | — | Generate PDF (bukti PPDB, laporan absensi) |

#### Frontend
| Teknologi | Versi | Keterangan |
|---|---|---|
| **Vue** | 3.x | Framework UI (Composition API) |
| **Vite** | 6.x | Build tool & dev server |
| **Vue Router** | 4.x | Client-side routing |
| **Pinia** | 2.x | State management |
| **Axios** | — | HTTP client untuk REST API |
| **Vanilla CSS** | — | Styling utama (tanpa Tailwind) |
| **Google Fonts** | — | Poppins (heading) + Inter (body) |

#### Database & Infrastruktur
| Teknologi | Keterangan |
|---|---|
| **MySQL** | 8.0+ — database utama |
| **Nginx** | Web server |
| **Cloudflare** | CDN, SSL, WAF (Anti-DDoS) |
| **Fonnte** | WhatsApp Gateway API |

---

### 2. Struktur Direktori

```
smpn1-muarakaman/
├── backend/                   # Laravel 11 API
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   ├── Api/           # Public API controllers
│   │   │   │   └── Admin/         # Admin API controllers
│   │   │   ├── Middleware/
│   │   │   └── Requests/          # Form Request validations
│   │   ├── Models/                # Eloquent models
│   │   ├── Jobs/                  # Queue jobs (WhatsApp notification)
│   │   ├── Services/              # Business logic (FonnteService, PinService)
│   │   └── Exports/               # Excel export classes
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   ├── routes/
│   │   ├── api.php                # API routes
│   │   └── web.php
│   └── storage/
│       └── app/public/            # Upload files (gambar, PDF)
│
├── frontend/                  # Vue 3 SPA
│   ├── src/
│   │   ├── assets/                # Gambar, ikon statis
│   │   ├── components/            # Komponen reusable
│   │   │   ├── common/            # Button, Card, Modal, Alert
│   │   │   ├── layout/            # Header, Footer, Sidebar, Navbar
│   │   │   └── modules/           # Komponen per modul
│   │   ├── views/
│   │   │   ├── public/            # Halaman publik (Beranda, Profil, dll)
│   │   │   ├── ppdb/              # Halaman PPDB
│   │   │   ├── attendance/        # Halaman absensi (kiosk)
│   │   │   └── admin/             # Halaman dashboard admin
│   │   ├── router/                # Vue Router config
│   │   ├── stores/                # Pinia stores
│   │   ├── services/              # API service layer (axios)
│   │   ├── composables/           # Composable functions
│   │   ├── App.vue
│   │   ├── main.js
│   │   └── style.css              # Global CSS
│   └── index.html
│
└── docs/                      # Dokumentasi
    ├── 01-PRD.md
    ├── 02-Architecture.md
    ├── 03-SDD.md
    ├── 04-Design.md
    ├── 05-Database.md
    └── 06-Proposal-Biaya.md
```

---

### 3. API Endpoint Design

#### A. Public API (Tanpa auth)

| Method | Endpoint | Keterangan |
|---|---|---|
| `GET` | `/api/pages/{slug}` | Ambil konten halaman statis |
| `GET` | `/api/articles` | List berita (paginated) |
| `GET` | `/api/articles/{slug}` | Detail berita |
| `GET` | `/api/galleries` | List galeri (foto + video) |
| `GET` | `/api/teachers` | List guru & staf |
| `GET` | `/api/ppdb/info` | Info status PPDB (buka/tutup) |
| `POST` | `/api/ppdb/register` | Submit pendaftaran PPDB |
| `GET` | `/api/ppdb/status/{reg_number}` | Cek status pendaftaran |
| `POST` | `/api/attendance/checkin` | Siswa submit PIN (check-in/check-out) |
| `POST` | `/api/contact` | Submit formulir kontak |

#### B. Admin API (Wajib auth — Sanctum)

| Method | Endpoint | Keterangan |
|---|---|---|
| `POST` | `/api/admin/login` | Login admin |
| `POST` | `/api/admin/logout` | Logout admin |
| `GET` | `/api/admin/dashboard` | Data statistik dashboard |
| — | — | — |
| `GET/POST/PUT/DELETE` | `/api/admin/pages` | CRUD halaman statis |
| `GET/POST/PUT/DELETE` | `/api/admin/articles` | CRUD berita |
| `GET/POST/PUT/DELETE` | `/api/admin/galleries` | CRUD galeri |
| `GET/POST/PUT/DELETE` | `/api/admin/teachers` | CRUD guru/staf |
| — | — | — |
| `GET` | `/api/admin/ppdb` | List pendaftar PPDB |
| `GET` | `/api/admin/ppdb/{id}` | Detail pendaftar |
| `PUT` | `/api/admin/ppdb/{id}/status` | Ubah status pendaftar |
| — | — | — |
| `GET/POST/PUT/DELETE` | `/api/admin/students` | CRUD siswa |
| — | — | — |
| `GET` | `/api/admin/attendance` | Rekap absensi (filter: kelas, tanggal) |
| `GET` | `/api/admin/attendance/export` | Export absensi ke Excel/PDF |
| — | — | — |
| `GET/PUT` | `/api/admin/settings` | Baca/update pengaturan situs |

---

### 4. Design Patterns

#### Backend (Laravel)
- **MVC Pattern** — Controller menangani request, Model berinteraksi dengan database.
- **Service Layer** — Logic kompleks (seperti `FonnteService`, `PinGeneratorService`, `AttendanceService`) dipisah ke `app/Services/`.
- **Form Request Validation** — Setiap endpoint punya class validasi tersendiri.
- **API Resources** — Standardisasi format JSON response.
- **Queue Jobs** — Pengiriman WhatsApp dilakukan async via `SendWhatsAppNotification` job.
- **Observer / Event-Listener** — Saat record `attendances` dibuat/diupdate → trigger event → dispatch notification job.

#### Frontend (Vue)
- **Component-Based Architecture** — UI dipecah menjadi komponen kecil yang reusable.
- **Single-File Components (SFC)** — Template + Script + Style dalam satu file `.vue`.
- **Composable Functions** — Logic reusable (misal: `useAuth`, `useApi`, `usePagination`).
- **Service Layer** — File `services/*.js` untuk encapsulate semua HTTP call ke API.

---

### 5. Security Considerations

| Aspek | Implementasi |
|---|---|
| **Authentication** | Laravel Sanctum (cookie-based SPA auth) |
| **Authorization** | Middleware role-check (`admin`, `staff`) |
| **CORS** | Konfigurasi hanya izinkan domain frontend |
| **CSRF & XSS** | Perlindungan bawaan Laravel + Vue escaped output |
| **SQL Injection** | Eloquent ORM + parameterized queries |
| **File Upload** | Validasi tipe file (PDF/JPG/PNG), ukuran max 5MB |
| **PIN Hashing** | PIN siswa disimpan dengan `bcrypt` hash |
| **Rate Limiting** | Throttle pada endpoint absensi (anti brute-force PIN) |
| **Cloudflare WAF** | Layer tambahan anti-DDoS |
| **API Token (Fonnte)** | Disimpan di `.env`, tidak hardcode |
