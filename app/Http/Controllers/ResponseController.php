<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Response;
use Illuminate\Support\Facades\Auth;

class ResponseController extends Controller
{
    /**
     * Menyimpan tanggapan admin terhadap laporan
     */
    public function store(Request $request)
    {
        // 1. Validasi data input
        $validatedData = $request->validate([
            'report_id'     => 'required|exists:reports,id',
            'response_text' => 'required',
        ]);

        // 2. Simpan data ke tabel responses
        Response::create([
            'report_id'     => $validatedData['report_id'],
            'user_id'       => Auth::id(), // ID admin yang membalas
            'response_text' => $validatedData['response_text'],
        ]);

        // 3. Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()
            ->back()
            ->with('success', 'Tanggapan berhasil dikirim!');
    }
}
