<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class PinGeneratorService
{
    /**
     * Generate a unique 4-digit PIN for a student
     * Returns the raw PIN. It's the caller's responsibility to hash it before saving.
     */
    public function generateUniquePin(): string
    {
        // For school kiosk, NIS + PIN is used. So PIN doesn't have to be globally unique.
        // Generate a random 4-digit PIN
        return str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
    }
}
