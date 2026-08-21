<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendWhatsAppNotification;
use Carbon\Carbon;
use Exception;

class AttendanceService
{
    /**
     * Process student check-in or check-out based on PIN
     *
     * @param string $nis
     * @param string $pin
     * @return array
     * @throws Exception
     */
    public function processAttendance(string $nis, string $pin): array
    {
        $student = Student::where('nis', $nis)->first();

        if (!$student || !Hash::check($pin, $student->pin)) {
            throw new Exception('NIS atau PIN salah.', 401);
        }

        return $this->handleAttendanceRecord($student);
    }

    /**
     * Process student check-in or check-out based on QR Token
     *
     * @param string $qrToken
     * @return array
     * @throws Exception
     */
    public function processAttendanceByToken(string $qrToken): array
    {
        $student = Student::where('qr_token', $qrToken)->first();

        if (!$student) {
            throw new Exception('QR Code tidak valid atau kadaluarsa.', 401);
        }

        return $this->handleAttendanceRecord($student);
    }

    private function handleAttendanceRecord(Student $student): array
    {
        if (!$student->is_active) {
            throw new Exception('Status siswa tidak aktif.', 403);
        }

        $today = Carbon::today()->toDateString();
        $currentTime = Carbon::now()->toTimeString();

        $attendance = Attendance::where('student_id', $student->id)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            // Check-in
            $attendance = Attendance::create([
                'student_id' => $student->id,
                'date' => $today,
                'check_in_time' => $currentTime,
                'status' => 'hadir'
            ]);

            // Dispatch WhatsApp Job
            SendWhatsAppNotification::dispatch($student, 'checkin', $currentTime);

            return [
                'message' => 'Check-in berhasil.',
                'student' => $student->name,
                'time' => $currentTime,
                'type' => 'checkin'
            ];
        } else {
            // Check-out
            if ($attendance->check_out_time) {
                throw new Exception('Anda sudah melakukan check-out hari ini.', 400);
            }

            $attendance->update([
                'check_out_time' => $currentTime
            ]);

            // Dispatch WhatsApp Job
            SendWhatsAppNotification::dispatch($student, 'checkout', $currentTime);

            return [
                'message' => 'Check-out berhasil.',
                'student' => $student->name,
                'time' => $currentTime,
                'type' => 'checkout'
            ];
        }
    }
}
