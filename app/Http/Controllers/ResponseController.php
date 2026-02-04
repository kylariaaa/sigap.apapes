<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Response;

class ResponseController extends Controller
{
    /**
     * Menyimpan tanggapan admin terhadap laporan
     */
    public function store(Request $request)
    {
        // VALIDASI INPUT
        $data = $request->validate([
            'report_id'     => 'required|exists:reports,id',
            'response_text' => 'required|string',
            'image'         => 'nullable|image|max:2048', // Maks 2MB
        ]);

        // DEFAULT: tidak ada gambar
        $imagePath = null;

        // UPLOAD GAMBAR (jika ada)
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store(
                'responses',
                'public'
            );
        }

        // SIMPAN KE DATABASE
        Response::create([
            'report_id'     => $data['report_id'],
            'user_id'       => Auth::id(),
            'response_text' => $data['response_text'],
            'image'         => $imagePath,
        ]);

        return back()->with('success', 'Tanggapan & bukti berhasil dikirim!');
    }
}
