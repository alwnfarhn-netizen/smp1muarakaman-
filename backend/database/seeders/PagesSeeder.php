<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Page;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
            [
                'slug'    => 'program-kerja',
                'title'   => 'Program Kerja',
                'content' => '<p>Informasi Program Kerja Tahunan SMP Negeri 1 Muara Kaman.</p>',
            ],
            [
                'slug'    => 'kondisi-siswa',
                'title'   => 'Kondisi Siswa',
                'content' => '<p>Data statistik dan kondisi perkembangan siswa terkini.</p>',
            ],
            [
                'slug'    => 'komite-sekolah',
                'title'   => 'Komite Sekolah',
                'content' => '<p>Profil dan program kerja Komite Sekolah sebagai mitra pengembangan institusi.</p>',
            ],
            [
                'slug'    => 'prestasi-siswa',
                'title'   => 'Prestasi Siswa',
                'content' => '<p>Daftar penghargaan dan prestasi gemilang siswa-siswi di berbagai tingkat kompetisi.</p>',
            ],
            [
                'slug'    => 'tenaga-kependidikan',
                'title'   => 'Direktori Tenaga Kependidikan',
                'content' => '<p>Daftar profil staf dan tenaga kependidikan (TU, Pustakawan, dll).</p>',
            ],
            [
                'slug'    => 'silabus',
                'title'   => 'Silabus Pembelajaran',
                'content' => '<p>Dokumen silabus pembelajaran dapat diunduh di halaman ini (Segera Hadir).</p>',
            ],
            [
                'slug'    => 'ujian-online',
                'title'   => 'Portal Ujian Online (CBT)',
                'content' => '<h3>Selamat Datang di Portal Ujian Online (CBT)</h3><p>Fitur ini sedang dalam tahap pengembangan dan integrasi dengan bank soal. Siswa nantinya dapat mengikuti try out dan ujian semester melalui perangkat masing-masing.</p>',
            ],
            [
                'slug'    => 'kalender-akademik',
                'title'   => 'Kalender Akademik',
                'content' => '<p>Agenda kegiatan dan kalender akademik tahun ajaran berjalan.</p>',
            ],
            [
                'slug'    => 'direktori-siswa',
                'title'   => 'Direktori Siswa',
                'content' => '<p>Pangkalan data siswa aktif (dilindungi hak privasi, sebagian data dibatasi untuk publik).</p>',
            ],
            [
                'slug'    => 'osis',
                'title'   => 'OSIS',
                'content' => '<p>Profil Organisasi Siswa Intra Sekolah (OSIS) SMP Negeri 1 Muara Kaman dan daftar program kerja unggulan.</p>',
            ],
            [
                'slug'    => 'beasiswa',
                'title'   => 'Info Beasiswa',
                'content' => '<p>Informasi penyaluran PIP (Program Indonesia Pintar) dan beasiswa prestasi lainnya.</p>',
            ],
            [
                'slug'    => 'e-raport',
                'title'   => 'e-Raport Kurikulum Merdeka',
                'content' => '<h3>Akses e-Raport</h3><p>Sistem e-Raport saat ini dikelola terpusat. Orang tua dan wali akan menerima link akses unik pada masa pembagian hasil belajar.</p>',
            ],
            [
                'slug'    => 'direktori-alumni',
                'title'   => 'Direktori Alumni',
                'content' => '<p>Jaringan alumni dari berbagai angkatan.</p>',
            ],
            [
                'slug'    => 'info-alumni',
                'title'   => 'Info & Agenda Alumni',
                'content' => '<p>Pengumuman temu kangen, reuni akbar, dan berita pencapaian alumni sekolah.</p>',
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(['slug' => $page['slug']], ['title' => $page['title'], 'content' => $page['content']]);
        }
    }
}
