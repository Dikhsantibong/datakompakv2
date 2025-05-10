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

class FiveS5RController extends Controller
{
    public function index()
    {
        return view('admin.5s5r.index');
    }

    public function store(Request $request)
    {
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
                'eviden' => $evidenPath
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
                'eviden' => $evidenPath
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
        // Get the date of the selected record
        $mainRecord = Pemeriksaan5s5r::findOrFail($id);
        $date = date('Y-m-d', strtotime($mainRecord->created_at));
        
        // Get all records for that date
        $pemeriksaan = Pemeriksaan5s5r::whereDate('created_at', $date)->get();
        $programKerja = ProgramKerja5r::whereDate('created_at', $date)->get();

        if ($pemeriksaan->isEmpty()) {
            return redirect()->route('admin.5s5r.list')->with('error', 'Data tidak ditemukan');
        }

        return view('admin.5s5r.show', compact('pemeriksaan', 'programKerja'));
    }

    public function list()
    {
        $query = DB::table('tabel_pemeriksaan_5s5r as p1')
            ->select('p1.id', 'p1.created_at', DB::raw('DATE(p1.created_at) as date'))
            ->whereIn('p1.id', function($query) {
                $query->select(DB::raw('MAX(p2.id)'))
                    ->from('tabel_pemeriksaan_5s5r as p2')
                    ->groupBy(DB::raw('DATE(p2.created_at)'));
            });

        // Apply date filters if provided
        if (request('start_date')) {
            $query->whereDate('p1.created_at', '>=', request('start_date'));
        }
        if (request('end_date')) {
            $query->whereDate('p1.created_at', '<=', request('end_date'));
        }

        $items = $query->orderBy('p1.created_at', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'date' => date('Y-m-d', strtotime($item->created_at)),
                    'created_by' => 'Admin',
                    'status' => 'Completed'
                ];
            });

        return view('admin.5s5r.list', compact('items'));
    }

    public function edit($id)
    {
        // Get the date of the selected record
        $mainRecord = Pemeriksaan5s5r::findOrFail($id);
        $date = date('Y-m-d', strtotime($mainRecord->created_at));
        
        // Get all records for that date
        $pemeriksaan = Pemeriksaan5s5r::whereDate('created_at', $date)->get();
        $programKerja = ProgramKerja5r::whereDate('created_at', $date)->get();

        if ($pemeriksaan->isEmpty()) {
            return redirect()->route('admin.5s5r.list')->with('error', 'Data tidak ditemukan');
        }

        return view('admin.5s5r.edit', compact('pemeriksaan', 'programKerja'));
    }

    public function update(Request $request, $id)
    {
        $mainRecord = Pemeriksaan5s5r::findOrFail($id);
        $date = date('Y-m-d', strtotime($mainRecord->created_at));

        DB::beginTransaction();
        try {
            // Update Pemeriksaan 5S5R data
            foreach(['Ringkas', 'Rapi', 'Resik', 'Rawat', 'Rajin'] as $kategori) {
                $record = Pemeriksaan5s5r::whereDate('created_at', $date)
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
                        'kondisi_akhir' => $request->input("kondisi_akhir_pemeriksaan_$kategori")
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
                $record = ProgramKerja5r::whereDate('created_at', $date)
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
        $mainRecord = Pemeriksaan5s5r::findOrFail($id);
        $date = date('Y-m-d', strtotime($mainRecord->created_at));
        
        $pemeriksaan = Pemeriksaan5s5r::whereDate('created_at', $date)->get();
        $programKerja = ProgramKerja5r::whereDate('created_at', $date)->get();

        $pdf = PDF::loadView('admin.5s5r.export.pdf', compact('pemeriksaan', 'programKerja'));
        
        return $pdf->download('5s5r-report-' . $date . '.pdf');
    }

    public function exportExcel($id)
    {
        $mainRecord = Pemeriksaan5s5r::findOrFail($id);
        $date = date('Y-m-d', strtotime($mainRecord->created_at));
        
        return Excel::download(new FiveS5RExport($id), '5s5r-report-' . $date . '.xlsx');
    }
} 