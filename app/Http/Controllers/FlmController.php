<?php

namespace App\Http\Controllers;

use App\Models\FlmInspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class FlmController extends Controller
{
    public function index()
    {
        return view('admin.flm.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'mesin.*' => 'required|string|max:100',
            'sistem.*' => 'required|string|max:100',
            'masalah.*' => 'required|string',
            'kondisi_awal.*' => 'required|string',
            'kondisi_akhir.*' => 'required|string',
            'catatan.*' => 'nullable|string',
            'eviden_sebelum.*' => 'nullable|image|max:2048',
            'eviden_sesudah.*' => 'nullable|image|max:2048',
        ]);

        foreach ($request->mesin as $key => $mesin) {
            if (empty($mesin)) continue;

            $data = [
                'tanggal' => $request->tanggal,
                'mesin' => $mesin,
                'sistem' => $request->sistem[$key],
                'masalah' => $request->masalah[$key],
                'kondisi_awal' => $request->kondisi_awal[$key],
                'tindakan_bersihkan' => in_array('bersihkan', $request->tindakan[$key] ?? []),
                'tindakan_lumasi' => in_array('lumasi', $request->tindakan[$key] ?? []),
                'tindakan_kencangkan' => in_array('kencangkan', $request->tindakan[$key] ?? []),
                'tindakan_perbaikan_koneksi' => in_array('perbaikan_koneksi', $request->tindakan[$key] ?? []),
                'tindakan_lainnya' => in_array('lainnya', $request->tindakan[$key] ?? []),
                'kondisi_akhir' => $request->kondisi_akhir[$key],
                'catatan' => $request->catatan[$key],
                'status' => 'selesai'
            ];

            // Handle file uploads
            if ($request->hasFile("eviden_sebelum.{$key}")) {
                $path = $request->file("eviden_sebelum.{$key}")->store('public/flm/eviden');
                $data['eviden_sebelum'] = Storage::url($path);
            }

            if ($request->hasFile("eviden_sesudah.{$key}")) {
                $path = $request->file("eviden_sesudah.{$key}")->store('public/flm/eviden');
                $data['eviden_sesudah'] = Storage::url($path);
            }

            FlmInspection::create($data);
        }

        return redirect()->route('admin.flm.list')->with('success', 'Data FLM berhasil disimpan');
    }

    public function list()
    {
        $flmData = FlmInspection::orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.flm.list', compact('flmData'));
    }

    public function show($id)
    {
        $flmDetail = FlmInspection::findOrFail($id);
        return view('admin.flm.show', compact('flmDetail'));
    }
} 