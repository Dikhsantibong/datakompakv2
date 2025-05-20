<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemeriksaan5s5r;
use App\Models\ProgramKerja5r;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FiveS5RExport;
use Illuminate\Support\Str;
use App\Models\FiveS5rBatch;
use Illuminate\Support\Facades\Auth;

class FiveS5RController extends Controller
{
    public function index()
    {
        return view('admin.5s5r.index');
    }

    public function store(Request $request)
    {
        // Get unit source from current session
        $unitSource = session('unit', 'mysql');
        $unitMapping = [
            'mysql_poasia' => 'PLTD POASIA',
            'mysql_kolaka' => 'PLTD KOLAKA',
            'mysql_bau_bau' => 'PLTD BAU BAU',
            'mysql_wua_wua' => 'PLTD WUA WUA',
            'mysql_winning' => 'PLTD WINNING',
            'mysql_erkee' => 'PLTD ERKEE',
            'mysql_ladumpi' => 'PLTD LADUMPI',
            'mysql_langara' => 'PLTD LANGARA',
            'mysql_lanipa_nipa' => 'PLTD LANIPA-NIPA',
            'mysql_pasarwajo' => 'PLTD PASARWAJO',
            'mysql_poasia_containerized' => 'PLTD POASIA CONTAINERIZED',
            'mysql_raha' => 'PLTD RAHA',
            'mysql_wajo' => 'PLTD WAJO',
            'mysql_wangi_wangi' => 'PLTD WANGI-WANGI',
            'mysql_rongi' => 'PLTD RONGI',
            'mysql_sabilambo' => 'PLTD SABILAMBO',
            'mysql_pltmg_bau_bau' => 'PLTD BAU BAU',
            'mysql_pltmg_kendari' => 'PLTD KENDARI',
            'mysql_baruta' => 'PLTD BARUTA',
            'mysql_moramo' => 'PLTD MORAMO',
        ];
        
        $unitName = $unitMapping[$unitSource] ?? 'UP Kendari';

        $batch = FiveS5rBatch::create([
            'created_by' => Auth::id(),
            'sync_unit_origin' => $unitName,
        ]);
        $batchId = $batch->id;

        // Handle Pemeriksaan 5S5R data
        foreach(['Ringkas', 'Rapi', 'Resik', 'Rawat', 'Rajin'] as $kategori) {
            $evidenPath = null;
            if ($request->hasFile("eviden_pemeriksaan_$kategori")) {
                $file = $request->file("eviden_pemeriksaan_$kategori");
                $filename = time() . '_' . $kategori . '_' . $file->getClientOriginalName();
                Storage::putFileAs('public/5s5r/pemeriksaan', $file, $filename);
                $evidenPath = '5s5r/pemeriksaan/' . $filename;
            }

            Pemeriksaan5s5r::create([
                'kategori' => $kategori,
                'detail' => $this->getDetailForKategori($kategori),
                'kondisi_awal' => $request->input("kondisi_awal_pemeriksaan_$kategori"),
                'pic' => $request->input("pic_$kategori"),
                'area_kerja' => $request->input("area_kerja_$kategori"),
                'area_produksi' => $request->input("area_produksi_$kategori"),
                'membersihkan' => $request->has("membersihkan_$kategori"),
                'merapikan' => $request->has("merapikan_$kategori"),
                'membuang_sampah' => $request->has("membuang_sampah_$kategori"),
                'mengecat' => $request->has("mengecat_$kategori"),
                'lainnya' => $request->has("lainnya_$kategori"),
                'kondisi_akhir' => $request->input("kondisi_akhir_pemeriksaan_$kategori"),
                'eviden' => $evidenPath,
                'batch_id' => $batchId
            ]);
        }

        // Handle Program Kerja 5R data
        for($i = 1; $i <= 4; $i++) {
            $evidenPath = null;
            if ($request->hasFile("eviden_program_$i")) {
                $file = $request->file("eviden_program_$i");
                $filename = time() . '_program_' . $i . '_' . $file->getClientOriginalName();
                Storage::putFileAs('public/5s5r/program', $file, $filename);
                $evidenPath = '5s5r/program/' . $filename;
            }

            ProgramKerja5r::create([
                'program_kerja' => "Shift $i",
                'goal' => $request->input("goal_$i"),
                'kondisi_awal' => $request->input("kondisi_awal_program_$i"),
                'progress' => $request->input("progress_$i"),
                'kondisi_akhir' => $request->input("kondisi_akhir_program_$i"),
                'catatan' => $request->input("catatan_$i"),
                'eviden' => $evidenPath,
                'batch_id' => $batchId
            ]);
        }

        return redirect()->route('admin.5s5r.list')->with('success', 'Data berhasil disimpan');
    }

    private function getDetailForKategori($kategori)
    {
        $details = [
            'Ringkas' => 'membedakan antara yang diperlukan dan yang tidak diperlukan serta membuang yang tidak diperlukan. Prinsip dan Ringkas (Seiri) yaitu dengan mengguanakan stratifikasi dan menangani sebab masalah.',
            'Rapi' => 'Menentukan tata letak yang tertata rapi sehingga kita selalu menemukan barang yang dibutuhkan. Prinsipnya adalah penyimpanan fungsional dan menghilangkan waktu untuk mencari barang.',
            'Resik' => 'Berarti menghilangkan sampah kotoran dan barang asing untuk memperoleh tempat kerja yang lebih bersih. Prinsipnya adalah pembersihan sebagai pemeriksaan dan tingkat kebersihan.',
            'Rawat' => 'Berarti memelihara barang dengan teratur, rapih, bersih dan dalam aspek personal serta kaitannya dengan polusi. Prinsipnya adalah manajemen visual dan pemantapan 5S.',
            'Rajin' => 'Berarti melakukan sesuatu yang benar sebagai kebiasaan. Prinsipnya adalah pembentukan kebiasaan dan tempat kerja yang mantap.'
        ];

        return $details[$kategori] ?? '';
    }

    public function show($id)
    {
        $batch = FiveS5rBatch::findOrFail($id);
        $pemeriksaan = $batch->pemeriksaan;
        $programKerja = $batch->programKerja;
        $sync_unit_origin = $batch->sync_unit_origin;
        if ($pemeriksaan->isEmpty()) {
            return redirect()->route('admin.5s5r.list')->with('error', 'Data tidak ditemukan');
        }
        return view('admin.5s5r.show', compact('pemeriksaan', 'programKerja', 'sync_unit_origin'));
    }

    public function list()
    {
        $query = FiveS5rBatch::query();
        if (request('unit_origin')) {
            $query->where('sync_unit_origin', request('unit_origin'));
        }
        $batches = $query->orderBy('created_at', 'desc')->get();
        $unitOrigins = FiveS5rBatch::select('sync_unit_origin')->distinct()->pluck('sync_unit_origin')->toArray();
        return view('admin.5s5r.list', compact('batches', 'unitOrigins'));
    }

    public function edit($id)
    {
        $batch = FiveS5rBatch::findOrFail($id);
        $pemeriksaan = $batch->pemeriksaan;
        $programKerja = $batch->programKerja;
        if ($pemeriksaan->isEmpty()) {
            return redirect()->route('admin.5s5r.list')->with('error', 'Data tidak ditemukan');
        }
        return view('admin.5s5r.edit', compact('pemeriksaan', 'programKerja'));
    }

    public function update(Request $request, $id)
    {
        $batch = FiveS5rBatch::findOrFail($id);
        $pemeriksaan = $batch->pemeriksaan;
        $programKerja = $batch->programKerja;

        // Get unit source from current session
        $unitSource = session('unit', 'mysql');
        $unitMapping = [
            'mysql_poasia' => 'PLTD POASIA',
            'mysql_kolaka' => 'PLTD KOLAKA',
            'mysql_bau_bau' => 'PLTD BAU BAU',
            'mysql_wua_wua' => 'PLTD WUA WUA',
            'mysql_winning' => 'PLTD WINNING',
            'mysql_erkee' => 'PLTD ERKEE',
            'mysql_ladumpi' => 'PLTD LADUMPI',
            'mysql_langara' => 'PLTD LANGARA',
            'mysql_lanipa_nipa' => 'PLTD LANIPA-NIPA',
            'mysql_pasarwajo' => 'PLTD PASARWAJO',
            'mysql_poasia_containerized' => 'PLTD POASIA CONTAINERIZED',
            'mysql_raha' => 'PLTD RAHA',
            'mysql_wajo' => 'PLTD WAJO',
            'mysql_wangi_wangi' => 'PLTD WANGI-WANGI',
            'mysql_rongi' => 'PLTD RONGI',
            'mysql_sabilambo' => 'PLTD SABILAMBO',
            'mysql_pltmg_bau_bau' => 'PLTD BAU BAU',
            'mysql_pltmg_kendari' => 'PLTD KENDARI',
            'mysql_baruta' => 'PLTD BARUTA',
            'mysql_moramo' => 'PLTD MORAMO',
        ];
        
        $unitName = $unitMapping[$unitSource] ?? 'UP Kendari';

        DB::beginTransaction();
        try {
            // Update Pemeriksaan 5S5R data
            foreach(['Ringkas', 'Rapi', 'Resik', 'Rawat', 'Rajin'] as $kategori) {
                $record = Pemeriksaan5s5r::where('batch_id', $batch->id)
                    ->where('kategori', $kategori)
                    ->first();

                if ($record) {
                    $updateData = [
                        'kondisi_awal' => $request->input("kondisi_awal_pemeriksaan_$kategori"),
                        'pic' => $request->input("pic_$kategori"),
                        'area_kerja' => $request->input("area_kerja_$kategori"),
                        'area_produksi' => $request->input("area_produksi_$kategori"),
                        'membersihkan' => $request->has("membersihkan_$kategori"),
                        'merapikan' => $request->has("merapikan_$kategori"),
                        'membuang_sampah' => $request->has("membuang_sampah_$kategori"),
                        'mengecat' => $request->has("mengecat_$kategori"),
                        'lainnya' => $request->has("lainnya_$kategori"),
                        'kondisi_akhir' => $request->input("kondisi_akhir_pemeriksaan_$kategori"),
                        'sync_unit_origin' => $unitName
                    ];

                    if ($request->hasFile("eviden_pemeriksaan_$kategori")) {
                        // Delete old file if exists
                        if ($record->eviden) {
                            Storage::delete('public/' . $record->eviden);
                        }
                        
                        $file = $request->file("eviden_pemeriksaan_$kategori");
                        $filename = time() . '_' . $kategori . '_' . $file->getClientOriginalName();
                        Storage::putFileAs('public/5s5r/pemeriksaan', $file, $filename);
                        $updateData['eviden'] = '5s5r/pemeriksaan/' . $filename;
                    }

                    $record->update($updateData);
                }
            }

            // Update Program Kerja 5R data
            for($i = 1; $i <= 4; $i++) {
                $record = ProgramKerja5r::where('batch_id', $batch->id)
                    ->where('program_kerja', "Shift $i")
                    ->first();

                if ($record) {
                    $updateData = [
                        'goal' => $request->input("goal_$i"),
                        'kondisi_awal' => $request->input("kondisi_awal_program_$i"),
                        'progress' => $request->input("progress_$i"),
                        'kondisi_akhir' => $request->input("kondisi_akhir_program_$i"),
                        'catatan' => $request->input("catatan_$i")
                    ];

                    if ($request->hasFile("eviden_program_$i")) {
                        // Delete old file if exists
                        if ($record->eviden) {
                            Storage::delete('public/' . $record->eviden);
                        }
                        
                        $file = $request->file("eviden_program_$i");
                        $filename = time() . '_program_' . $i . '_' . $file->getClientOriginalName();
                        Storage::putFileAs('public/5s5r/program', $file, $filename);
                        $updateData['eviden'] = '5s5r/program/' . $filename;
                    }

                    $record->update($updateData);
                }
            }

            DB::commit();
            return redirect()->route('admin.5s5r.show', $id)->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data');
        }
    }

    public function exportPdf($id)
    {
        $batch = FiveS5rBatch::findOrFail($id);
        $pemeriksaan = $batch->pemeriksaan;
        $programKerja = $batch->programKerja;

        $pdf = PDF::loadView('admin.5s5r.export.pdf', compact('pemeriksaan', 'programKerja'));
        
        return $pdf->download('5s5r-report-' . $id . '.pdf');
    }

    public function exportExcel($id)
    {
        $batch = FiveS5rBatch::findOrFail($id);
        return Excel::download(new FiveS5RExport($id), '5s5r-report-' . $id . '.xlsx');
    }
} 