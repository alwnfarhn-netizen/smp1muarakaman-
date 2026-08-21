# UI/UX & Design Guidelines
## Sistem Informasi Terpadu SMPN 1 Muara Kaman

> **Versi:** 2.0 (Revisi — ditambahkan desain Absensi Kiosk & Admin lengkap)
> **Tanggal:** 18 Agustus 2026

---

### 1. Konsep Desain
Sistem dirancang dengan tampilan **Modern, Bersih, dan Profesional** namun tetap bersahabat untuk institusi pendidikan SMP.

- **Responsive Design:** Mobile-first approach. Mengingat banyak calon siswa, orang tua, dan siswa akan mengakses dari smartphone.
- **Aksesibilitas:** Kontras teks yang jelas, navigasi intuitif, form mudah diisi.
- **Dua Wajah:** Tampilan publik (cerah, menarik) dan dashboard admin (clean, fungsional).

---

### 2. Panduan Warna (Color Palette)

#### Halaman Publik
| Peran | Warna | Hex | Kesan |
|---|---|---|---|
| **Primary** | Navy Blue | `#1E3A8A` | Kepercayaan, akademik, profesional |
| **Primary Light** | Blue 600 | `#2563EB` | Hover states, link |
| **Secondary / Accent** | Amber/Kuning | `#F59E0B` | CTA button, penekanan info |
| **Background** | Putih | `#FFFFFF` | Latar utama |
| **Surface** | Abu-abu terang | `#F3F4F6` | Section background bergantian |
| **Text Primary** | Hitam keabu-abuan | `#1F2937` | Paragraf |
| **Text Heading** | Hitam pekat | `#111827` | Judul/heading |
| **Success** | Hijau | `#10B981` | Status berhasil |
| **Error** | Merah | `#EF4444` | Validasi error |
| **Warning** | Kuning | `#F59E0B` | Peringatan |

#### Dashboard Admin
| Peran | Warna | Hex |
|---|---|---|
| **Sidebar** | Dark Navy | `#0F172A` |
| **Sidebar Active** | Blue 700 | `#1D4ED8` |
| **Content BG** | Gray 50 | `#F9FAFB` |
| **Card BG** | White | `#FFFFFF` |

---

### 3. Tipografi (Google Fonts)
- **Heading Font:** *Poppins* (600, 700) — Modern, tebal, mudah dibaca.
- **Body Font:** *Inter* (400, 500) — Keterbacaan sangat baik pada semua ukuran.

```css
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@400;500;600&display=swap');
```

---

### 4. Struktur Halaman & Wireframe

#### A. Halaman Publik

##### Beranda (`/`)
```
┌──────────────────────────────────────────┐
│ [Logo]   Beranda  Profil  Galeri  PPDB  Kontak  │  ← Header/Navbar
├──────────────────────────────────────────┤
│                                          │
│        🎓 Selamat Datang di              │
│       SMPN 1 Muara Kaman                │  ← Hero Section
│    [Lihat Info PPDB]  [Profil Sekolah]   │     (Background image)
│                                          │
├──────────────────────────────────────────┤
│  📰 Berita Terbaru                       │
│  ┌──────┐  ┌──────┐  ┌──────┐           │  ← 3 Card berita
│  │ Card │  │ Card │  │ Card │           │
│  └──────┘  └──────┘  └──────┘           │
├──────────────────────────────────────────┤
│  🏫 Sambutan Kepala Sekolah              │
│  [Foto]  Lorem ipsum dolor sit amet...   │  ← Section dari tabel `pages`
├──────────────────────────────────────────┤
│  📋 Fasilitas Kami                       │
│  ┌────┐ ┌────┐ ┌────┐ ┌────┐           │  ← Grid card fasilitas
│  │Lab │ │Perp│ │Msjd│ │Lap │           │
│  └────┘ └────┘ └────┘ └────┘           │
├──────────────────────────────────────────┤
│  Footer: Alamat | Kontak | Sosmed        │
└──────────────────────────────────────────┘
```

##### Halaman Profil (`/profil/:slug`)
- Konten dinamis dari tabel `pages` (editable via admin).
- Layout: gambar header + teks konten.

##### Halaman Galeri (`/galeri`)
- 2 Tab: **Foto** | **Video**
- Foto: Grid masonry/equal-height dengan lightbox saat diklik.
- Video: Grid card dengan thumbnail YouTube + embed modal.

##### Halaman PPDB (`/ppdb`)
- Info PPDB (jadwal, syarat, alur — dari `pages` atau `settings`).
- Tombol CTA: "Daftar Sekarang" → `/ppdb/daftar`.
- Form multi-step sederhana (2-3 step): Data Diri → Data Orang Tua → Upload PDF → Konfirmasi.
- Halaman Bukti Daftar (`/ppdb/bukti/:reg_number`) — layout print-friendly.
- Halaman Cek Status (`/ppdb/cek-status`) — input nomor registrasi.

##### Halaman Absensi (`/absensi`) — Mode Kiosk/Fleksibel
```
┌──────────────────────────────────────────┐
│                                          │
│          🎓 SMPN 1 Muara Kaman          │
│            ABSENSI DIGITAL               │
│                                          │
│       ┌──────────────────────┐           │
│       │                      │           │
│       │    Masukkan PIN      │           │
│       │    ┌──┐┌──┐┌──┐┌──┐ │           │  ← Input PIN 4 digit
│       │    │  ││  ││  ││  │ │           │     (tampilan besar,
│       │    └──┘└──┘└──┘└──┘ │           │      touch-friendly)
│       │                      │           │
│       │   [1] [2] [3]        │           │
│       │   [4] [5] [6]        │           │  ← Numpad on-screen
│       │   [7] [8] [9]        │           │
│       │   [C] [0] [✓]        │           │
│       └──────────────────────┘           │
│                                          │
│    ✅ Ahmad Fauzi (7A) — Check-in 07:15  │  ← Feedback setelah input
│                                          │
│         📅 Senin, 18 Agustus 2026        │
│            🕐 07:15 WIB                  │
└──────────────────────────────────────────┘
```

**Catatan desain halaman Absensi:**
- Tampilan **fullscreen**, tanpa navbar/footer biasa.
- Ukuran font besar, tombol besar (touch-friendly untuk tablet).
- Animasi feedback: hijau = sukses, merah = PIN salah.
- Auto-reset setelah 3 detik tampil feedback.
- Jam dan tanggal real-time ditampilkan.

---

#### B. Dashboard Admin

##### Layout Admin
```
┌────────────────┬──────────────────────────────┐
│  🎓 SMPN 1    │  Dashboard  >  Beranda       │  ← Top Bar (Breadcrumb)
│  Muara Kaman   │                    [Admin ▼] │
├────────────────┼──────────────────────────────┤
│                │                              │
│  📊 Dashboard  │  ┌──────┐ ┌──────┐ ┌──────┐ │
│  📄 Halaman    │  │Siswa │ │PPDB  │ │Hadir │ │  ← Stat Cards
│  📰 Berita     │  │ 320  │ │  45  │ │ 298  │ │
│  🖼️ Galeri     │  └──────┘ └──────┘ └──────┘ │
│  👩‍🏫 Guru      │                              │
│  ─────────     │  📊 Grafik Kehadiran Minggu  │
│  📝 PPDB       │  ┌────────────────────────┐  │  ← Chart area
│  👨‍🎓 Siswa     │  │  ████ ███ ████ █████   │  │
│  📋 Absensi    │  └────────────────────────┘  │
│  ─────────     │                              │
│  ⚙️ Pengaturan │  📰 Berita Terbaru           │
│  🚪 Keluar     │  ┌──────────────────────┐    │  ← Recent items table
│                │  │ Judul  Tanggal Status │    │
│                │  └──────────────────────┘    │
└────────────────┴──────────────────────────────┘
```

##### Halaman CRUD (Berita, Guru, Siswa, dll)
- **List View:** Tabel data dengan search, filter, pagination.
- **Create/Edit:** Form dalam halaman terpisah atau modal.
- **Delete:** Konfirmasi dialog sebelum hapus.
- **Toolbar:** Tombol "Tambah Baru" + "Export" (jika ada).

##### Halaman Rekap Absensi (`/admin/absensi`)
- Filter: Kelas, Rentang Tanggal.
- Tabel: Nama | NIS | Kelas | Check-in | Check-out | Status.
- Tombol: Export Excel, Export PDF.
- Warna status: Hijau=Hadir, Kuning=Izin/Sakit, Merah=Alpha.

---

### 5. Komponen UI Utama

| Komponen | Spesifikasi |
|---|---|
| **Button Primary** | BG: `#1E3A8A`, Text: white, rounded-lg, hover: `#1D4ED8`, shadow on hover |
| **Button Secondary** | BG: `#F59E0B`, Text: `#1F2937`, rounded-lg, hover: `#D97706` |
| **Button Danger** | BG: `#EF4444`, Text: white, untuk delete actions |
| **Card** | BG: white, border: `1px solid #E5E7EB`, rounded-xl, shadow-sm, hover: shadow-md |
| **Form Input** | Border: `#D1D5DB`, focus-border: `#2563EB`, rounded-lg, label di atas |
| **Table** | Header BG: `#F3F4F6`, row hover: `#EFF6FF`, border-bottom antar row |
| **Alert Success** | BG: `#D1FAE5`, Border-left: `#10B981`, ikon ✅ |
| **Alert Error** | BG: `#FEE2E2`, Border-left: `#EF4444`, ikon ❌ |
| **Alert Warning** | BG: `#FEF3C7`, Border-left: `#F59E0B`, ikon ⚠️ |
| **Badge Status** | Rounded-full, warna sesuai status (pending=gray, verified=blue, accepted=green, rejected=red) |
| **Modal** | Overlay semi-transparan, card tengah, animasi fade-in |
| **Numpad (Absensi)** | Grid 3x4, tombol besar (min 60x60px), font-size 24px, border-radius besar |

---

### 6. Animasi & Micro-Interactions
- **Page Transition:** Fade-in halus saat pindah halaman.
- **Card Hover:** Scale 1.02 + shadow lebih tebal.
- **Button Hover:** Perubahan warna + slight shadow.
- **Absensi Feedback:** Animasi slide-up + fade-in untuk pesan sukses/gagal.
- **Loading State:** Skeleton loader (bukan spinner) untuk konten yang sedang dimuat.
- **Toast Notification:** Slide-in dari kanan atas untuk notifikasi admin (sukses simpan, dll).
- **Hero Parallax:** Efek parallax ringan pada gambar hero di beranda (opsional).
