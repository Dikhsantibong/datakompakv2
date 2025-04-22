<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemeriksaan5s5r;
use App\Models\ProgramKerja5r;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
                $evidenPath = $request->file("eviden_pemeriksaan_$kategori")->store('public/5s5r/pemeriksaan');
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
                $evidenPath = $request->file("eviden_program_$i")->store('public/5s5r/program');
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
        // Menggunakan subquery untuk mendapatkan ID terbaru dari setiap tanggal
        $items = DB::table('tabel_pemeriksaan_5s5r as p1')
            ->select('p1.id', 'p1.created_at', DB::raw('DATE(p1.created_at) as date'))
            ->whereIn('p1.id', function($query) {
                $query->select(DB::raw('MAX(p2.id)'))
                    ->from('tabel_pemeriksaan_5s5r as p2')
                    ->groupBy(DB::raw('DATE(p2.created_at)'));
            })
            ->orderBy('p1.created_at', 'desc')
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
} 