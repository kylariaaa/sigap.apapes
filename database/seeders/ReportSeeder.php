<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\User;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID user warga
        $budi = User::where('username', 'budi_santoso')->first();
        $siti = User::where('username', 'siti_rahayu')->first();

        $reports = [
            [
                'user_id' => $budi->id,
                'title' => 'Jalan Berlubang di Jl. Merdeka RT 05',
                'description' => 'Terdapat lubang besar di tengah jalan Merdeka depan RT 05 yang berbahaya bagi pengendara, terutama di malam hari. Lubang sudah ada sejak 2 bulan lalu dan belum diperbaiki.',
                'location' => 'Jl. Merdeka No.1, RT 05/RW 02, Kebayoran Baru, Jakarta Selatan',
                'latitude' => -6.2441,
                'longitude' => 106.7994,
                'image' => null,
                'status' => '0',
            ],
            [
                'user_id' => $siti->id,
                'title' => 'Sampah Menumpuk di Pinggir Sungai Ciliwung',
                'description' => 'Tumpukan sampah yang sangat banyak di pinggiran sungai Ciliwung dekat Jembatan Pemuda sudah menimbulkan bau tidak sedap dan rawan banjir. Mohon segera ditangani.',
                'location' => 'Jembatan Pemuda, Jl. Otista Raya, Kampung Melayu, Jakarta Timur',
                'latitude' => -6.2167,
                'longitude' => 106.8686,
                'image' => null,
                'status' => 'proses',
            ],
            [
                'user_id' => $budi->id,
                'title' => 'Lampu Jalan Mati di Perumahan Cempaka',
                'description' => 'Tiga lampu jalan di Perumahan Cempaka Blok B sudah mati selama seminggu dan membuat kawasan tersebut sangat gelap di malam hari, meningkatkan risiko kejahatan.',
                'location' => 'Perumahan Cempaka Blok B, Ciputat Timur, Tangerang Selatan',
                'latitude' => -6.3197,
                'longitude' => 106.7617,
                'image' => null,
                'status' => 'selesai',
            ],
            [
                'user_id' => $siti->id,
                'title' => 'Saluran Air Mampet Menyebabkan Genangan',
                'description' => 'Saluran drainase di Jl. Kenanga tersumbat sampah plastik sehingga air meluap ke jalan setiap kali hujan. Kondisi ini menyulitkan warga dan merusak jalan aspal.',
                'location' => 'Jl. Kenanga Raya, Pondok Jaya, Mampang Prapatan, Jakarta Selatan',
                'latitude' => -6.2543,
                'longitude' => 106.8219,
                'image' => null,
                'status' => '0',
            ],
            [
                'user_id' => $budi->id,
                'title' => 'Fasilitas Taman Bermain Rusak Berbahaya',
                'description' => 'Ayunan dan perosotan di Taman RW 03 dalam kondisi rusak parah. Baut berkarat dan beberapa besi tajam terlihat yang dapat melukai anak-anak yang bermain.',
                'location' => 'Taman RW 03, Jl. Flamboyan, Ragunan, Pasar Minggu, Jakarta Selatan',
                'latitude' => -6.3087,
                'longitude' => 106.8162,
                'image' => null,
                'status' => 'proses',
            ],
        ];

        foreach ($reports as $data) {
            Report::create($data);
        }
    }
}
