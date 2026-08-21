<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PpdbRegistration;

class PpdbAdminController extends Controller
{
    public function index()
    {
        return response()->json(PpdbRegistration::orderBy('created_at', 'desc')->get());
    }

    public function show($id)
    {
        return response()->json(PpdbRegistration::findOrFail($id));
    }

    public function updateStatus(Request $request, $id)
    {
        $registration = PpdbRegistration::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,verified,accepted,rejected',
            'notes' => 'nullable|string'
        ]);

        $registration->update($validated);

        return response()->json(['message' => 'Status berhasil diperbarui.']);
    }
}
