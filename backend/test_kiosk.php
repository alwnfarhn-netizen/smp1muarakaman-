<?php
use App\Models\Student;
use App\Services\PinGeneratorService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

$pinService = app(PinGeneratorService::class);
$rawPin = '1234';

// Insert dummy student
$student = Student::create([
    'name' => 'Kiosk Test User',
    'nis' => '112233',
    'nisn' => '11223344',
    'class' => 'VIII A',
    'gender' => 'L',
    'parent_phone' => '0812345678',
    'qr_token' => 'STU-TESTQR',
    'pin' => Hash::make($rawPin),
]);

echo "Created Student NIS: {$student->nis}, PIN: {$rawPin}\n";

// HTTP POST to the running server
$response = Http::post('http://127.0.0.1:8000/api/attendance/checkin', [
    'nis' => $student->nis,
    'pin' => $rawPin,
]);

echo "Response Status: " . $response->status() . "\n";
echo "Response Body: " . $response->body() . "\n";
