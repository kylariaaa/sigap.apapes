<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Menampilkan Form Lapor
     */
    public function index()
    {
        $myReports = Report::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('user.lapor', compact('myReports'));
    }

    /**
     * Memproses Data & Foto
     */
    public function store(Request $request)
    {
        // A. Validasi
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'location'    => 'required|string', // Tambahkan validasi lokasi
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // B. Logika Upload Foto
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Gunakan storePublicly atau tentukan disk 'public'
            $imagePath = $request->file('image')->store('reports', 'public');
        }

        // C. Simpan ke Database
        // Pastikan model Report sudah memiliki protected $fillable
        Report::create([
            'user_id'     => Auth::id(),
            'title'       => $request->title,
            'description' => $request->description,
            'location'    => $request->location,
            'image'       => $imagePath,
            'status'      => '0',
        ]);

        return redirect()
            ->route('user.lapor') // Disarankan redirect ke route tertentu
            ->with('success', 'Laporan Anda telah berhasil terkirim!');
    }
}
