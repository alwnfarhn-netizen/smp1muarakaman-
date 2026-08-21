<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
            ['key' => 'ppdb_is_open',       'value' => 'true'],
            ['key' => 'ppdb_year',          'value' => '2026/2027'],
            ['key' => 'fonnte_api_token',   'value' => ''],
            ['key' => 'google_maps_embed',  'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.8!2d116.7287!3d-0.1478!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2sSMPN+1+Muara+Kaman!5e0!3m2!1sid!2sid!4v1!5m2!1sid!2sid'],
            ['key' => 'footer_info',        'value' => "SMP Negeri 1 Muara Kaman didirikan pada 7 November 1983. Terakreditasi B.\nMisi: Melahirkan generasi yang beriman, berakhlak mulia, cerdas, kreatif, berprestasi, berbudaya, dan berwawasan global."],
            ['key' => 'footer_contact',     'value' => "<p><strong>Alamat:</strong> Jl. Mulawarman RT.15 No.03<br>Desa Muara Kaman Ulu, Kec. Muara Kaman<br>Kab. Kutai Kartanegara, Kaltim 75553</p><p><strong>Telepon:</strong> 082250169235<br><strong>Email:</strong> ryantiryanti184@gmail.com</p>"],
            ['key' => 'footer_map',         'value' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.8!2d116.7287!3d-0.1478!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2sSMPN+1+Muara+Kaman!5e0!3m2!1sid!2sid!4v1!5m2!1sid!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'],
            ['key' => 'footer_links',       'value' => json_encode([
                ['title' => 'Visi & Misi', 'url' => '/profil/visi-misi'],
                ['title' => 'Info PPDB Online', 'url' => '/ppdb'],
                ['title' => 'Direktori Guru', 'url' => '/profil/tenaga-kependidikan'],
                ['title' => 'Kiosk Absensi', 'url' => '/kiosk']
            ])],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], ['value' => $setting['value']]);
        }
    }
}
