<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\K3KampReport;
use App\Models\K3KampItem;
use App\Models\K3KampMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class K3KampController extends Controller
{
    public function index()
    {
        $report = K3KampReport::with(['items.media'])
            ->where('date', today())
            ->first();

        return view('admin.k3-kamp.index', compact('report'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Create report
            $report = K3KampReport::create([
                'date' => today(),
                'created_by' => auth()->id()
            ]);

            // Process K3 & Keamanan items
            $k3Items = [
                'Potensi gangguan keamanan',
                'Potensi gangguan kebakaran',
                'Peralatan K3 KAM (CCTV, etc)',
                'Peralatan Fire Fighting',
                'Peralatan safety',
                'Lainnya'
            ];

            foreach ($k3Items as $index => $itemName) {
                $report->items()->create([
                    'item_type' => 'k3_keamanan',
                    'item_name' => $itemName,
                    'status' => $request->input("status_$index"),
                    'kondisi' => $request->input("kondisi_$index"),
                    'keterangan' => $request->input("keterangan_$index")
                ]);
            }

            // Process Lingkungan items
            $lingkunganItems = [
                'Unsafe action',
                'Unsafe condition',
                'Lainnya'
            ];

            foreach ($lingkunganItems as $index => $itemName) {
                $report->items()->create([
                    'item_type' => 'lingkungan',
                    'item_name' => $itemName,
                    'status' => $request->input("status_lingkungan_$index"),
                    'kondisi' => $request->input("kondisi_lingkungan_$index"),
                    'keterangan' => $request->input("keterangan_lingkungan_$index")
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Laporan berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan laporan');
        }
    }

    public function uploadMedia(Request $request)
    {
        $request->validate([
            'media_file' => 'required|file|max:51200', // 50MB max
            'media_type' => 'required|in:image,video',
            'row_id' => 'required'
        ]);

        try {
            $file = $request->file('media_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Store file
            $path = $file->storeAs(
                'public/k3-kamp-media',
                $fileName
            );

            // Create media record
            $media = K3KampMedia::create([
                'item_id' => $request->row_id,
                'media_type' => $request->media_type,
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Media berhasil diupload',
                'data' => $media
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload media'
            ], 500);
        }
    }

    public function deleteMedia($id)
    {
        try {
            $media = K3KampMedia::findOrFail($id);
            Storage::delete($media->file_path);
            $media->delete();

            return response()->json([
                'success' => true,
                'message' => 'Media berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus media'
            ], 500);
        }
    }
} 