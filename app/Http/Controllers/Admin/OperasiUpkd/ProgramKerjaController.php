<?php

namespace App\Http\Controllers\Admin\OperasiUpkd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProgramKerjaController extends Controller
{
    public function index()
    {
        $programKerja = [
            'RUTIN' => [],
            'OPERATION' => [],
            'WORKSHOP' => []
        ];
        
        return view('admin.operasi-upkd.program-kerja.index', compact('programKerja'));
    }

    /**
     * Show the form for creating a new program kerja.
     */
    public function create()
    {
        return view('admin.operasi-upkd.program-kerja.create');
    }

    public function store(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'type' => 'required|string',
                'data' => 'required|array'
            ]);

            // Process and store the data
            // TODO: Add your storage logic here

            return response()->json([
                'success' => true,
                'message' => 'Program kerja berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan program kerja: ' . $e->getMessage()
            ], 500);
        }
    }
} 