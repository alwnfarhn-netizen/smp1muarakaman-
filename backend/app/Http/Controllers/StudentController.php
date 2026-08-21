<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Attendance;
use App\Services\PinGeneratorService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function index()
    {
        return response()->json(Student::all());
    }

    public function store(Request $request, PinGeneratorService $pinService)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'nis' => 'required|string|unique:students,nis',
            'nisn' => 'required|string|unique:students,nisn',
            'class' => 'required|string',
            'gender' => 'required|in:L,P',
            'parent_name' => 'nullable|string',
            'parent_phone' => 'required|string',
        ]);

        $rawPin = $pinService->generateUniquePin();
        $validated['pin'] = Hash::make($rawPin);
        $validated['qr_token'] = 'STU-' . strtoupper(Str::random(8));
        
        $student = Student::create($validated);
        
        return response()->json([
            'student' => $student,
            'raw_pin' => $rawPin // Only returned once upon creation!
        ]);
    }

    public function generatePins(PinGeneratorService $pinService)
    {
        // Find students who do not have a pin or where pin is empty
        $students = Student::whereNull('pin')->orWhere('pin', '')->get();
        
        $updatedCount = 0;
        foreach ($students as $student) {
            $rawPin = $pinService->generateUniquePin();
            $student->pin = Hash::make($rawPin);
            // Optionally, we could save the raw pins to a temporary session or log to be exported for the school.
            // For security, raw pins should ideally only be distributed directly to the student/parent.
            // If they are generated en masse, the school will need a way to distribute them (e.g., printing cards).
            $student->save();
            $updatedCount++;
        }

        return response()->json([
            'message' => "Generated PINs for {$updatedCount} students.",
            'updated_count' => $updatedCount
        ]);
    }

    public function resetPin($id, PinGeneratorService $pinService)
    {
        $student = Student::findOrFail($id);
        
        $rawPin = $pinService->generateUniquePin();
        $student->pin = Hash::make($rawPin);
        $student->save();

        return response()->json([
            'message' => 'PIN has been reset successfully.',
            'student' => $student,
            'new_raw_pin' => $rawPin // Important: return the new pin so the admin can give it to the student
        ]);
    }

    public function attendances(Request $request)
    {
        $date = $request->query('date', today()->toDateString());
        $attendances = Attendance::with('student')->where('date', $date)->get();
        return response()->json($attendances);
    }

    public function exportAttendances(Request $request)
    {
        $date = $request->query('date', today()->toDateString());
        $attendances = Attendance::with('student')->where('date', $date)->get();

        $csvFileName = "absensi_{$date}.csv";
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $handle = fopen('php://output', 'w');
        ob_start();
        fputcsv($handle, ['No', 'NIS', 'Nama Siswa', 'Kelas', 'Jam Masuk', 'Jam Keluar', 'Status']);

        foreach ($attendances as $index => $row) {
            fputcsv($handle, [
                $index + 1,
                $row->student->nis ?? '-',
                $row->student->name ?? '-',
                $row->student->class ?? '-',
                $row->check_in_time ?? '-',
                $row->check_out_time ?? '-',
                $row->status ?? '-'
            ]);
        }
        fclose($handle);
        $output = ob_get_clean();

        return response($output, 200, $headers);
    }
}
