<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Student;
use App\Services\FonnteService;

class SendWhatsAppNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $student;
    public $type;
    public $time;

    /**
     * Create a new job instance.
     */
    public function __construct(Student $student, string $type, string $time)
    {
        $this->student = $student;
        $this->type = $type;
        $this->time = $time;
    }

    /**
     * Execute the job.
     */
    public function handle(FonnteService $fonnteService): void
    {
        if (empty($this->student->parent_phone)) {
            return;
        }

        $phone = $this->student->parent_phone;
        $message = "";

        if ($this->type === 'checkin') {
            $message = "Yth. Orang Tua/Wali,\n\nSiswa atas nama *{$this->student->name}* telah melakukan *CHECK-IN* (Hadir) di sekolah pada pukul {$this->time}.\n\nTerima kasih.\nSMPN 1 Muara Kaman";
        } elseif ($this->type === 'checkout') {
            $message = "Yth. Orang Tua/Wali,\n\nSiswa atas nama *{$this->student->name}* telah melakukan *CHECK-OUT* (Pulang) dari sekolah pada pukul {$this->time}.\n\nTerima kasih.\nSMPN 1 Muara Kaman";
        }

        if (!empty($message)) {
            $fonnteService->sendMessage($phone, $message, $this->student->id, $this->type);
        }
    }
}
