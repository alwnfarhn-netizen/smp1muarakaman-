<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\NotificationLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    /**
     * Send WhatsApp message via Fonnte API
     *
     * @param string $phone
     * @param string $message
     * @param int|null $studentId
     * @param string $type
     * @return bool
     */
    public function sendMessage(string $phone, string $message, ?int $studentId = null, string $type = 'general'): bool
    {
        $token = Setting::where('key', 'fonnte_api_token')->value('value');
        
        if (empty($token) || empty($phone)) {
            Log::warning('Fonnte token or phone number missing.', ['phone' => $phone]);
            return false;
        }

        // Log the pending notification
        $log = NotificationLog::create([
            'student_id' => $studentId,
            'phone_number' => $phone,
            'message' => $message,
            'type' => $type,
            'status' => 'pending'
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => $token
            ])->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['status']) && $responseData['status'] == true) {
                $log->update([
                    'status' => 'sent',
                    'response' => json_encode($responseData)
                ]);
                return true;
            } else {
                $log->update([
                    'status' => 'failed',
                    'response' => json_encode($responseData)
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Fonnte API Error: ' . $e->getMessage());
            $log->update([
                'status' => 'failed',
                'response' => $e->getMessage()
            ]);
            return false;
        }
    }
}
