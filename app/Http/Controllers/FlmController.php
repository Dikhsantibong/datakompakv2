<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FlmController extends Controller
{
    public function index()
    {
        return view('admin.flm.index');
    }

    public function store(Request $request)
    {
        // This is just a placeholder for now since we're not implementing database functionality yet
        return redirect()->back()->with('success', 'Form submitted successfully');
    }

    public function list()
    {
        // Dummy data for demonstration
        $flmData = [
            [
                'id' => 1,
                'tanggal' => '2024-03-20',
                'mesin' => 'Mesin A',
                'sistem' => 'Sistem Pembangkit 1',
                'masalah' => 'Kebocoran',
                'kondisi_awal' => 'Tidak optimal',
                'tindakan' => ['bersihkan', 'kencangkan'],
                'kondisi_akhir' => 'Sudah diperbaiki',
                'catatan' => 'Perlu pemantauan rutin',
                'eviden' => 'foto1.jpg'
            ],
            [
                'id' => 2,
                'tanggal' => '2024-03-21',
                'mesin' => 'Mesin B',
                'sistem' => 'Sistem Pembangkit 2',
                'masalah' => 'Getaran berlebih',
                'kondisi_awal' => 'Tidak stabil',
                'tindakan' => ['lumasi', 'kencangkan'],
                'kondisi_akhir' => 'Normal',
                'catatan' => 'Sudah normal kembali',
                'eviden' => 'foto2.jpg'
            ],
        ];

        return view('admin.flm.list', compact('flmData'));
    }

    public function show($id)
    {
        // Dummy data for demonstration
        $flmDetail = [
            'id' => $id,
            'tanggal' => '2024-03-20',
            'shift' => 'A',
            'operator' => 'John Doe',
            'mesin' => 'Mesin A',
            'sistem' => 'Sistem Pembangkit 1',
            'masalah' => 'Kebocoran pada pipa pendingin',
            'kondisi_awal' => 'Terdapat kebocoran yang menyebabkan air pendingin menetes',
            'tindakan' => [
                'bersihkan' => true,
                'lumasi' => false,
                'kencangkan' => true,
                'perbaikan_koneksi' => false,
                'lainnya' => false
            ],
            'kondisi_akhir' => 'Kebocoran sudah diperbaiki, sistem berjalan normal',
            'catatan' => 'Perlu pemantauan rutin untuk mencegah kebocoran berulang',
            'eviden' => [
                'sebelum' => 'foto_sebelum.jpg',
                'sesudah' => 'foto_sesudah.jpg'
            ],
            'status' => 'selesai',
            'created_at' => '2024-03-20 08:30:00',
            'updated_at' => '2024-03-20 09:15:00'
        ];

        return view('admin.flm.show', compact('flmDetail'));
    }
} 