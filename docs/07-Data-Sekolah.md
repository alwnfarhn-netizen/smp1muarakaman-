# 📚 Data & Aset Resmi Sekolah
## SMP Negeri 1 Muara Kaman

> **Sumber Data:** Kemendikdasmen (Sekolah Kita & Data Referensi), NPSN 30400255
> **Tanggal Pengambilan:** 18 Agustus 2026

---

## 1. Identitas Sekolah

| Data | Isi |
|---|---|
| **Nama Sekolah** | SMP Negeri 1 Muara Kaman |
| **NPSN** | 30400255 |
| **Status** | Negeri |
| **Jenjang** | SMP (Sekolah Menengah Pertama) |
| **Akreditasi** | **B** (SK No. 024/BAP-SM/HK/XI/2017) |
| **Tanggal Pendirian** | 7 November 1983 |
| **Kepala Sekolah** | **Masiah** |
| **Sistem Belajar** | Pagi / 6 hari seminggu |

---

## 2. Alamat & Lokasi

| Data | Isi |
|---|---|
| **Jalan** | Jl. Mulawarman RT.15 No.03 |
| **Desa/Kelurahan** | Muara Kaman Ulu |
| **Kecamatan** | Muara Kaman |
| **Kabupaten** | Kutai Kartanegara |
| **Provinsi** | Kalimantan Timur |
| **Kode Pos** | 75553 |
| **Latitude** | -0.1478 |
| **Longitude** | 116.7287 |
| **Google Maps** | [Lihat di Maps](https://www.google.com/maps?q=-0.1478,116.7287) |

---

## 3. Kontak

| Data | Isi |
|---|---|
| **Telepon** | 082250169235 |
| **Email** | ryantiryanti184@gmail.com |

> ⚠️ **Catatan:** Email dan nomor telepon di atas adalah data dari Dapodik. Untuk website resmi, disarankan membuat email resmi baru (misal: `info@smpn1muarakaman.sch.id`) setelah domain `.sch.id` aktif.

---

## 4. Data Statistik

| Data | Isi |
|---|---|
| **Jumlah Siswa** | ± 357 siswa (data Juli 2026) |
| **Rasio Guru : Siswa** | 1 : 20 |
| **Luas Tanah** | ± 23.850 m² |

---

## 5. Visi & Misi

### Visi
> Melahirkan generasi yang **beriman, berakhlak mulia, cerdas, kreatif, berprestasi, berbudaya, dan berwawasan global**.

### Misi
1. Menanamkan nilai keimanan dan ketakwaan kepada Tuhan Yang Maha Esa.
2. Membiasakan sikap sopan santun dan bertutur kata yang baik.
3. Menciptakan lingkungan sekolah yang bersih, nyaman, dan berbudaya.
4. Meningkatkan prestasi akademik dan non-akademik siswa.
5. Mengembangkan kreativitas dan potensi siswa melalui kegiatan ekstrakurikuler.
6. Mewujudkan wawasan global melalui penguasaan teknologi dan informasi.

> ⚠️ **Catatan:** Misi di atas disusun berdasarkan informasi publik yang tersedia. Perlu dikonfirmasi dan dilengkapi oleh pihak sekolah.

---

## 6. Fasilitas (Perlu Konfirmasi Sekolah)

Berikut daftar fasilitas yang umumnya tersedia di SMP Negeri setingkat di Kutai Kartanegara:

| # | Fasilitas | Status |
|---|---|---|
| 1 | Ruang Kelas | ✅ Ada |
| 2 | Laboratorium IPA | ✅ Ada (umum SMP Negeri) |
| 3 | Laboratorium Komputer | ✅ Ada (umum SMP Negeri) |
| 4 | Perpustakaan | ✅ Ada (umum SMP Negeri) |
| 5 | Musholla/Ruang Ibadah | ✅ Ada (umum SMP Negeri) |
| 6 | Lapangan Olahraga | ✅ Ada (lahan 23.850 m²) |
| 7 | Ruang Guru | ✅ Ada |
| 8 | Ruang Kepala Sekolah | ✅ Ada |
| 9 | UKS (Unit Kesehatan Sekolah) | ⚠️ Perlu dikonfirmasi |
| 10 | Kantin | ⚠️ Perlu dikonfirmasi |

> ⚠️ **Aksi:** Minta foto-foto fasilitas dari pihak sekolah untuk konten website.

---

## 7. Ekstrakurikuler (Perlu Konfirmasi Sekolah)

Berdasarkan informasi yang tersedia, kegiatan ekskul meliputi:

| # | Ekstrakurikuler | Jenis |
|---|---|---|
| 1 | **Pramuka** | Wajib |
| 2 | Olahraga (Futsal, Bola Voli, dll) | Pilihan |
| 3 | Seni (Tari, Musik Tradisional) | Pilihan |
| 4 | Pengembangan Akademik (OSN) | Pilihan |
| 5 | Keagamaan | Pilihan |

> ⚠️ **Aksi:** Minta daftar lengkap ekskul + foto kegiatan dari pihak sekolah.

---

## 8. Data untuk Konfigurasi Awal (`settings` seeder)

Berikut data yang siap dimasukkan ke tabel `settings` sebagai konfigurasi awal website:

```php
// database/seeders/SettingsSeeder.php

$settings = [
    ['key' => 'school_name',        'value' => 'SMP Negeri 1 Muara Kaman'],
    ['key' => 'school_npsn',        'value' => '30400255'],
    ['key' => 'school_address',     'value' => 'Jl. Mulawarman RT.15 No.03, Desa Muara Kaman Ulu'],
    ['key' => 'school_district',    'value' => 'Kec. Muara Kaman'],
    ['key' => 'school_regency',     'value' => 'Kab. Kutai Kartanegara'],
    ['key' => 'school_province',    'value' => 'Kalimantan Timur'],
    ['key' => 'school_postal_code', 'value' => '75553'],
    ['key' => 'school_phone',       'value' => '082250169235'],
    ['key' => 'school_email',       'value' => 'ryantiryanti184@gmail.com'],
    ['key' => 'school_latitude',    'value' => '-0.1478'],
    ['key' => 'school_longitude',   'value' => '116.7287'],
    ['key' => 'school_accreditation', 'value' => 'B'],
    ['key' => 'school_established', 'value' => '1983-11-07'],
    ['key' => 'school_headmaster',  'value' => 'Masiah'],
    ['key' => 'school_motto',       'value' => 'Beriman, Berakhlak Mulia, Cerdas, Kreatif, Berprestasi, Berbudaya, Berwawasan Global'],
    ['key' => 'school_logo_path',   'value' => '/storage/logo.png'],
    ['key' => 'school_student_count', 'value' => '357'],
    ['key' => 'school_teacher_ratio', 'value' => '1:20'],
    ['key' => 'school_land_area',   'value' => '23850'],
    ['key' => 'contact_email',      'value' => 'info@smpn1muarakaman.sch.id'],
    ['key' => 'ppdb_is_open',       'value' => 'false'],
    ['key' => 'ppdb_year',          'value' => '2026/2027'],
    ['key' => 'fonnte_api_token',   'value' => ''],
    ['key' => 'google_maps_embed',  'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.8!2d116.7287!3d-0.1478!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2sSMPN+1+Muara+Kaman!5e0!3m2!1sid!2sid!4v1!5m2!1sid!2sid'],
];
```

---

## 9. Konten Awal untuk Tabel `pages` (Seeder)

```php
// database/seeders/PagesSeeder.php

$pages = [
    [
        'slug'    => 'sambutan-kepsek',
        'title'   => 'Sambutan Kepala Sekolah',
        'content' => '<p>Assalamu\'alaikum Warahmatullahi Wabarakatuh.</p>
<p>Puji syukur kita panjatkan kehadirat Allah SWT atas segala rahmat dan karunia-Nya. Selamat datang di website resmi SMP Negeri 1 Muara Kaman.</p>
<p>Sebagai lembaga pendidikan yang telah berdiri sejak tahun 1983, kami berkomitmen untuk terus mengembangkan kualitas pendidikan demi melahirkan generasi yang beriman, berakhlak mulia, cerdas, dan berprestasi.</p>
<p>Website ini hadir sebagai media informasi dan komunikasi antara sekolah dengan orang tua siswa dan masyarakat luas. Semoga website ini bermanfaat bagi kita semua.</p>
<p>Wassalamu\'alaikum Warahmatullahi Wabarakatuh.</p>
<p><strong>Masiah</strong><br>Kepala SMP Negeri 1 Muara Kaman</p>',
    ],
    [
        'slug'    => 'sejarah',
        'title'   => 'Sejarah Sekolah',
        'content' => '<p>SMP Negeri 1 Muara Kaman didirikan pada tanggal <strong>7 November 1983</strong> di Desa Muara Kaman Ulu, Kecamatan Muara Kaman, Kabupaten Kutai Kartanegara, Provinsi Kalimantan Timur.</p>
<p>Berdiri di atas lahan seluas ± 23.850 m², sekolah ini merupakan SMP Negeri pertama di Kecamatan Muara Kaman dan telah menjadi bagian penting dalam sejarah pendidikan di wilayah ini selama lebih dari 40 tahun.</p>
<p>Saat ini, SMP Negeri 1 Muara Kaman telah terakreditasi <strong>B</strong> berdasarkan SK No. 024/BAP-SM/HK/XI/2017 dan terus berbenah untuk meningkatkan kualitas pendidikan.</p>',
    ],
    [
        'slug'    => 'visi-misi',
        'title'   => 'Visi & Misi',
        'content' => '<h3>Visi</h3>
<p>Melahirkan generasi yang <strong>beriman, berakhlak mulia, cerdas, kreatif, berprestasi, berbudaya, dan berwawasan global</strong>.</p>
<h3>Misi</h3>
<ol>
<li>Menanamkan nilai keimanan dan ketakwaan kepada Tuhan Yang Maha Esa.</li>
<li>Membiasakan sikap sopan santun dan bertutur kata yang baik.</li>
<li>Menciptakan lingkungan sekolah yang bersih, nyaman, dan berbudaya.</li>
<li>Meningkatkan prestasi akademik dan non-akademik siswa.</li>
<li>Mengembangkan kreativitas dan potensi siswa melalui kegiatan ekstrakurikuler.</li>
<li>Mewujudkan wawasan global melalui penguasaan teknologi dan informasi.</li>
</ol>',
    ],
    [
        'slug'    => 'struktur-organisasi',
        'title'   => 'Struktur Organisasi',
        'content' => '<p>Struktur Organisasi SMP Negeri 1 Muara Kaman Tahun Ajaran 2026/2027.</p>
<p><em>(Gambar struktur organisasi akan ditambahkan oleh admin)</em></p>',
    ],
    [
        'slug'    => 'fasilitas',
        'title'   => 'Fasilitas Sekolah',
        'content' => '<p>SMP Negeri 1 Muara Kaman memiliki berbagai fasilitas penunjang kegiatan belajar mengajar, antara lain:</p>
<ul>
<li>Ruang Kelas yang nyaman</li>
<li>Laboratorium IPA</li>
<li>Laboratorium Komputer</li>
<li>Perpustakaan</li>
<li>Musholla</li>
<li>Lapangan Olahraga</li>
<li>UKS (Unit Kesehatan Sekolah)</li>
<li>Kantin Sekolah</li>
</ul>',
    ],
    [
        'slug'    => 'ekstrakurikuler',
        'title'   => 'Kegiatan Ekstrakurikuler',
        'content' => '<p>SMP Negeri 1 Muara Kaman menyediakan berbagai kegiatan ekstrakurikuler untuk mengembangkan minat dan bakat siswa:</p>
<ul>
<li><strong>Pramuka</strong> (Wajib)</li>
<li>Olahraga (Futsal, Bola Voli)</li>
<li>Seni Tari &amp; Musik Tradisional</li>
<li>Pengembangan Akademik (OSN)</li>
<li>Kegiatan Keagamaan</li>
</ul>',
    ],
];
```

---

## 10. Aset yang Perlu Diminta dari Sekolah

Berikut daftar aset yang **wajib diminta** dari pihak sekolah untuk melengkapi website:

| # | Aset | Format | Keterangan |
|---|---|---|---|
| 1 | **Logo Sekolah** | PNG/SVG (transparant) | Wajib, resolusi tinggi |
| 2 | **Foto Kepala Sekolah** | JPG/PNG | Untuk sambutan Kepsek |
| 3 | **Foto Gedung/Gerbang Sekolah** | JPG | Untuk Hero section beranda |
| 4 | **Foto Fasilitas** (Lab, Perpus, dll) | JPG | Untuk halaman fasilitas |
| 5 | **Foto Kegiatan Ekskul** | JPG | Untuk galeri |
| 6 | **Foto Upacara/Kegiatan Resmi** | JPG | Untuk galeri |
| 7 | **Gambar Struktur Organisasi** | JPG/PNG | Untuk halaman profil |
| 8 | **Daftar Guru & Staf + Foto** | Excel + JPG | Untuk halaman guru |
| 9 | **Daftar Siswa Aktif** | Excel | Untuk modul absensi (NIS, NISN, Kelas, Nama Ortu, No HP) |
| 10 | **Banner/Brosur PPDB** (jika ada) | JPG/PDF | Opsional |

---

## 11. Link Referensi

| Sumber | URL |
|---|---|
| Sekolah Kita (Kemendikdasmen) | [Profil SMPN 1 Muara Kaman](https://sekolah.data.kemendikdasmen.go.id/profil-sekolah/2057CA7E-30F5-E011-B1BF-77CF73B2470C) |
| Data Referensi (NPSN) | [NPSN 30400255](https://referensi.data.kemendikdasmen.go.id/pendidikan/npsn/30400255) |
| Google Maps | [Lokasi Sekolah](https://www.google.com/maps?q=-0.1478,116.7287) |
