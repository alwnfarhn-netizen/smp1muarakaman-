<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Setting;

class PpdbTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_submit_ppdb_registration()
    {
        Storage::fake('public');

        // Setup settings
        Setting::create(['key' => 'ppdb_is_open', 'value' => 'true']);
        Setting::create(['key' => 'ppdb_year', 'value' => '2026/2027']);

        // Create a fake PDF document
        $file = UploadedFile::fake()->create('berkas_syarat.pdf', 1000, 'application/pdf');

        $response = $this->postJson('/api/ppdb/register', [
            'full_name' => 'Budi Santoso',
            'nisn' => '1234567890',
            'place_of_birth' => 'Samarinda',
            'date_of_birth' => '2010-01-01',
            'gender' => 'L',
            'religion' => 'Islam',
            'previous_school' => 'SDN 001 Muara Kaman',
            'address' => 'Jl. Pendidikan No. 1',
            'parent_name' => 'Joko Santoso',
            'parent_phone' => '08123456789',
            'document' => $file,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['message', 'registration_number']);

        $registrationNumber = $response->json('registration_number');
        $this->assertStringStartsWith('PPDB-2026-', $registrationNumber);

        // Assert file was stored
        $this->assertDatabaseHas('ppdb_registrations', [
            'nisn' => '1234567890',
            'status' => 'pending'
        ]);

        // Verify status endpoint
        $statusResponse = $this->getJson('/api/ppdb/status/' . $registrationNumber);
        $statusResponse->assertStatus(200)
                       ->assertJson([
                           'registration_number' => $registrationNumber,
                           'full_name' => 'Budi Santoso',
                           'status' => 'pending'
                       ]);
    }
}
