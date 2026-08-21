<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AttendanceService;
use Exception;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function checkin(Request $request)
    {
        $request->validate([
            'nis' => 'required|string',
            'pin' => 'required|string'
        ]);

        try {
            $result = $this->attendanceService->processAttendance($request->nis, $request->pin);
            return response()->json($result);
        } catch (Exception $e) {
            $statusCode = $e->getCode() ?: 400;
            return response()->json(['message' => $e->getMessage()], $statusCode);
        }
    }

    public function scan(Request $request)
    {
        $request->validate([
            'qr_token' => 'required|string'
        ]);

        try {
            $result = $this->attendanceService->processAttendanceByToken($request->qr_token);
            return response()->json($result);
        } catch (Exception $e) {
            $statusCode = $e->getCode() ?: 400;
            return response()->json(['message' => $e->getMessage()], $statusCode);
        }
    }
}
