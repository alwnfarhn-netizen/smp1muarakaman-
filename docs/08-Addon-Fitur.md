# Add-on Fitur: Ekspansi Mega Menu (Standar Kemendikbud)
> **Tanggal:** 18 Agustus 2026
> **Sifat:** Tambahan Ekstra (Luar Proposal Awal)

Dokumen ini mencatat rancangan penambahan arsitektur navigasi dan kelengkapan halaman informasi (statis) guna menyetarakan sistem dengan standar tata letak dan struktur informasi website sekolah dari Kemendikbud.

## 1. Struktur Navigasi Utama (Navbar Dropdown)

Navigasi utama website publik (`PublicLayout`) ditingkatkan dari *single links* menjadi *Dropdown Mega Menu* dengan rincian:

### A. Profil Sekolah
- Visi dan Misi (`/profil/visi-misi`)
- Sejarah Singkat (`/profil/sejarah`)
- Sarana & Prasarana (`/profil/fasilitas`)
- Struktur Organisasi (`/profil/struktur-organisasi`)
- Kepala Sekolah (`/profil/sambutan-kepsek`)
- Program Kerja (`/profil/program-kerja`)
- Kondisi Siswa (`/profil/kondisi-siswa`)
- Komite Sekolah (`/profil/komite-sekolah`)
- Prestasi Siswa (`/profil/prestasi-siswa`)

### B. Guru
- Direktori Guru (`/guru`)
- Direktori Tenaga Kependidikan (`/profil/tenaga-kependidikan`)
- Silabus (`/profil/silabus`)
- Ujian Online (`/profil/ujian-online`) -> *Untuk sementara berupa halaman informasi persiapan CBT*
- Kalender Akademik (`/profil/kalender-akademik`)

### C. Siswa
- Direktori Siswa (`/profil/direktori-siswa`)
- Ekstrakurikuler (`/profil/ekstrakurikuler`)
- OSIS (`/profil/osis`)
- Beasiswa (`/profil/beasiswa`)
- e-Raport (`/profil/e-raport`) -> *Halaman pengantar portal nilai*

### D. Alumni
- Direktori Alumni (`/profil/direktori-alumni`)
- Info Alumni (`/profil/info-alumni`)

## 2. Dampak Teknis
1. **Frontend:** 
   - `PublicLayout.vue` memerlukan perombakan CSS *dropdown hover* dan menu *accordion* di mode *mobile*.
   - Halaman dinamis (*Dynamic Page Reader*) tidak lagi bergantung pada *sidebar* statis "Profil Sekolah", melainkan akan menggunakan lebar penuh atau *sidebar* kontekstual.
2. **Backend (Database):**
   - Tabel `pages` harus diisi dengan konten *dummy* (via Seeder) untuk belasan *slug* baru di atas agar tautan menu valid (tidak 404) dan bisa diedit oleh Admin di kemudian hari.
