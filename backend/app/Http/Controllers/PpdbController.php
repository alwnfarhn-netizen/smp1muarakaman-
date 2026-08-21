<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PpdbRegistration;
use App\Models\Setting;
use Illuminate\Support\Str;

class PpdbController extends Controller
{
    public function register(Request $request)
    {
        // Check if PPDB is open
        $isOpen = Setting::where('key', 'ppdb_is_open')->value('value');
        if ($isOpen !== 'true') {
            return response()->json(['message' => 'Pendaftaran PPDB sedang ditutup.'], 403);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'nisn' => 'required|string|max:20|unique:ppdb_registrations,nisn',
            'place_of_birth' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:L,P',
            'religion' => 'required|string',
            'previous_school' => 'required|string',
            'address' => 'required|string',
            'parent_name' => 'required|string',
            'parent_phone' => 'required|string',
            'document' => 'required|file|mimes:pdf|max:5120' // max 5MB PDF
        ]);

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('ppdb_documents', 'public');
            $validated['document_path'] = $path;
            unset($validated['document']);
        }

        // Generate Registration Number
        $year = Setting::where('key', 'ppdb_year')->value('value');
        $yearPrefix = explode('/', $year)[0] ?? date('Y');
        $count = PpdbRegistration::count() + 1;
        $validated['registration_number'] = 'PPDB-' . $yearPrefix . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
        
        $validated['status'] = 'pending';

        $registration = PpdbRegistration::create($validated);

        return response()->json([
            'message' => 'Pendaftaran berhasil dikirim!',
            'registration_number' => $registration->registration_number
        ]);
    }

    public function status($registration_number)
    {
        $registration = PpdbRegistration::where('registration_number', $registration_number)->first();

        if (!$registration) {
            return response()->json(['message' => 'Nomor registrasi tidak ditemukan.'], 404);
        }

        return response()->json($registration);
    }
}
