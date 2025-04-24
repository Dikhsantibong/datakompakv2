<?php

namespace App\Http\Controllers\Admin\OperasiUpkd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RjppDprController extends Controller
{
    public function index()
    {
        // Initialize empty data array for the view
        $rjppDpr = [
            'items' => []
        ];
        
        return view('admin.operasi-upkd.rjpp-dpr.index', compact('rjppDpr'));
    }

    public function store(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'data' => 'required|array',
                'data.*.tahun' => 'required|string',
                'data.*.uraian' => 'required|string',
                'data.*.goal' => 'required|string',
                'data.*.deadline' => 'required|date',
                'data.*.pic' => 'required|string',
                'data.*.anggaran' => 'required|numeric',
                'data.*.progress' => 'required|numeric|min:0|max:100',
                'data.*.feedback' => 'nullable|string',
                'data.*.status' => 'required|string'
            ]);

            // TODO: Add logic to store the data in the database
            
            return response()->json([
                'success' => true,
                'message' => 'Data RJPP-DPR berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
} 