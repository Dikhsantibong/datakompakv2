<?php

namespace App\Http\Controllers\Admin\OperasiUpkd;

use App\Http\Controllers\Controller;
use App\Models\PengadaanBarang;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PengadaanController extends Controller
{
    public function index(Request $request): View
    {
        $query = PengadaanBarang::query();

        // Filter by tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Filter by jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter by status fields
        $statusFields = [
            'pengusulan',
            'proses_kontrak',
            'pengadaan',
            'pekerjaan_fisik',
            'pemberkasan',
            'pembayaran'
        ];

        foreach ($statusFields as $field) {
            if ($request->filled($field)) {
                $query->where($field, $request->input($field));
            }
        }

        $pengadaan = $query->orderBy('id', 'desc')->get();
        return view('admin.operasi-upkd.pengadaan.index', compact('pengadaan'));
    }

    public function create(): View
    {
        return view('admin.operasi-upkd.pengadaan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'judul' => 'required',
            'tahun' => 'required|numeric',
            'jenis' => 'required|in:Rutin,Non Rutin',
            'intensitas' => 'required',
            'pengusulan' => 'required|in:Open,Close,On Progress',
            'proses_kontrak' => 'required|in:Open,Close,On Progress',
            'pengadaan' => 'required|in:Open,Close,On Progress',
            'pekerjaan_fisik' => 'required|in:Open,Close,On Progress',
            'pemberkasan' => 'required|in:Open,Close,On Progress',
            'pembayaran' => 'required|in:Open,Close,On Progress',
        ]);

        PengadaanBarang::create($request->all());
        return redirect()->route('admin.operasi-upkd.pengadaan.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit(int $id): View
    {
        $pengadaan = PengadaanBarang::findOrFail($id);
        return view('admin.operasi-upkd.pengadaan.edit', compact('pengadaan'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $pengadaan = PengadaanBarang::findOrFail($id);
        
        $request->validate([
            'judul' => 'required',
            'tahun' => 'required|numeric',
            'jenis' => 'required|in:Rutin,Non Rutin',
            'intensitas' => 'required',
            'pengusulan' => 'required|in:Open,Close,On Progress',
            'proses_kontrak' => 'required|in:Open,Close,On Progress',
            'pengadaan' => 'required|in:Open,Close,On Progress',
            'pekerjaan_fisik' => 'required|in:Open,Close,On Progress',
            'pemberkasan' => 'required|in:Open,Close,On Progress',
            'pembayaran' => 'required|in:Open,Close,On Progress',
        ]);

        $pengadaan->update($request->all());
        return redirect()->route('admin.operasi-upkd.pengadaan.index')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy(int $id): RedirectResponse
    {
        $pengadaan = PengadaanBarang::findOrFail($id);
        $pengadaan->delete();
        return redirect()->route('admin.operasi-upkd.pengadaan.index')->with('success', 'Data berhasil dihapus');
    }
} 