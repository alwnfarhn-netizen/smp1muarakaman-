<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Student;

class PinGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_generate_pins_for_students_without_pins(): void
    {
        // 1. Arrange: Create an admin user
        $admin = User::factory()->create();

        // Create 2 students without a PIN
        $student1 = Student::create([
            'name' => 'Budi',
            'nis' => '1001',
            'nisn' => '001001',
            'class' => 'VII A',
            'gender' => 'L',
            'parent_phone' => '08123456789',
            'qr_token' => 'STU-123456',
            'pin' => '',
        ]);

        $student2 = Student::create([
            'name' => 'Ani',
            'nis' => '1002',
            'nisn' => '001002',
            'class' => 'VII A',
            'gender' => 'P',
            'parent_phone' => '08123456790',
            'qr_token' => 'STU-123457',
            'pin' => '',
        ]);

        // Create 1 student WITH a PIN (should be ignored by generate-pins)
        $student3 = Student::create([
            'name' => 'Cita',
            'nis' => '1003',
            'nisn' => '001003',
            'class' => 'VII A',
            'gender' => 'P',
            'parent_phone' => '08123456791',
            'qr_token' => 'STU-123458',
            'pin' => 'hashed_old_pin',
        ]);

        // 2. Act: Call the endpoint as the authenticated admin
        $response = $this->actingAs($admin)->postJson('/api/admin/students/generate-pins');

        // 3. Assert: Check the response
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Generated PINs for 2 students.',
            'updated_count' => 2
        ]);

        // 4. Assert: Check the database
        $this->assertNotNull($student1->fresh()->pin);
        $this->assertNotEmpty($student1->fresh()->pin);
        
        $this->assertNotNull($student2->fresh()->pin);
        $this->assertNotEmpty($student2->fresh()->pin);

        // Make sure student3's PIN was NOT changed
        $this->assertEquals('hashed_old_pin', $student3->fresh()->pin);
    }
}
