<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FiveS5RController extends Controller
{
    public function index()
    {
        return view('admin.5s5r.index');
    }

    public function store(Request $request)
    {
        // This will be implemented later when we add database functionality
        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function show($id)
    {
        // For now, we'll just pass dummy data
        $data = [
            'id' => $id,
            'date' => now()->format('Y-m-d'),
            'created_by' => 'Admin',
            'details' => [
                'ringkas' => [
                    'kondisi_awal' => 'Kondisi awal ringkas',
                    'pic' => 'John Doe',
                    'area_kerja' => 'Area 1',
                    'area_produksi' => 'Produksi 1',
                    'tindakan' => ['membersihkan', 'merapikan'],
                    'kondisi_akhir' => 'Kondisi akhir ringkas',
                ],
                'rapi' => [
                    'kondisi_awal' => 'Kondisi awal rapi',
                    'pic' => 'Jane Doe',
                    'area_kerja' => 'Area 2',
                    'area_produksi' => 'Produksi 2',
                    'tindakan' => ['merapikan', 'membuang sampah'],
                    'kondisi_akhir' => 'Kondisi akhir rapi',
                ],
                // Add other 5S5R categories with dummy data
            ]
        ];

        return view('admin.5s5r.show', compact('data'));
    }

    public function list()
    {
        // For now, we'll just pass dummy data
        $items = collect([
            [
                'id' => 1,
                'date' => '2024-03-20',
                'created_by' => 'Admin 1',
                'status' => 'Completed'
            ],
            [
                'id' => 2,
                'date' => '2024-03-19',
                'created_by' => 'Admin 2',
                'status' => 'In Progress'
            ],
            // Add more dummy data as needed
        ]);

        return view('admin.5s5r.list', compact('items'));
    }
} 