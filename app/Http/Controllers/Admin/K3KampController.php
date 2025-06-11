<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\K3KampReport;
use App\Models\K3KampItem;
use App\Models\K3KampMedia;
use App\Exports\K3KampExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

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

            K3KampReport::$isSyncing = false;

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
                'mysql' => 'UP Kendari',
                'mysql_mikuasi' => 'PLTM MIKUASI'
            ];
            
            $unitName = $unitMapping[$unitSource] ?? 'UP Kendari';

            // Create report
            $report = K3KampReport::create([
                'date' => today(),
                'sync_unit_origin' => $unitName
            ]);

            // Debug log
            Log::info('Created report', ['report_id' => $report->id]);

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
                $status = 'tidak_ada';
                if ($request->has("status_{$index}") && is_array($request->input("status_{$index}"))) {
                    $status = $request->input("status_{$index}")[0];
                }

                $kondisi = null;
                if ($request->has("kondisi_{$index}") && is_array($request->input("kondisi_{$index}"))) {
                    $kondisi = $request->input("kondisi_{$index}")[0];
                }

                if ($kondisi || $request->filled("keterangan_{$index}")) {
                    $item = $report->items()->create([
                        'item_type' => 'k3_keamanan',
                        'item_name' => $itemName,
                        'status' => $status,
                        'kondisi' => $kondisi,
                        'keterangan' => $request->input("keterangan_{$index}")
                    ]);

                    // Debug log
                    Log::info('Created k3 item', [
                        'item_id' => $item->id,
                        'item_name' => $itemName,
                        'eviden_path' => $request->input("eviden_path_{$index}")
                    ]);

                    // Update media jika ada
                    if ($request->filled("eviden_path_{$index}")) {
                        $mediaPath = $request->input("eviden_path_{$index}");
                        $updated = K3KampMedia::where('item_id', 0)
                            ->where('file_path', $mediaPath)
                            ->update(['item_id' => $item->id]);
                        
                        // Debug log
                        Log::info('Updated media for k3 item', [
                            'item_id' => $item->id,
                            'media_path' => $mediaPath,
                            'rows_updated' => $updated
                        ]);
                    }
                }
            }

            // Process Lingkungan items
            $lingkunganItems = [
                'Unsafe action',
                'Unsafe condition',
                'Lainnya'
            ];

            foreach ($lingkunganItems as $index => $itemName) {
                $status = 'tidak_ada';
                if ($request->has("status_lingkungan_{$index}") && is_array($request->input("status_lingkungan_{$index}"))) {
                    $status = $request->input("status_lingkungan_{$index}")[0];
                }

                $kondisi = null;
                if ($request->has("kondisi_lingkungan_{$index}") && is_array($request->input("kondisi_lingkungan_{$index}"))) {
                    $kondisi = $request->input("kondisi_lingkungan_{$index}")[0];
                }

                if ($kondisi || $request->filled("keterangan_lingkungan_{$index}")) {
                    $item = $report->items()->create([
                        'item_type' => 'lingkungan',
                        'item_name' => $itemName,
                        'status' => $status,
                        'kondisi' => $kondisi,
                        'keterangan' => $request->input("keterangan_lingkungan_{$index}")
                    ]);

                    // Debug log
                    Log::info('Created lingkungan item', [
                        'item_id' => $item->id,
                        'item_name' => $itemName,
                        'eviden_path' => $request->input("eviden_path_lingkungan_{$index}")
                    ]);

                    // Update media jika ada
                    if ($request->filled("eviden_path_lingkungan_{$index}")) {
                        $mediaPath = $request->input("eviden_path_lingkungan_{$index}");
                        $updated = K3KampMedia::where('item_id', 0)
                            ->where('file_path', $mediaPath)
                            ->update(['item_id' => $item->id]);
                        
                        // Debug log
                        Log::info('Updated media for lingkungan item', [
                            'item_id' => $item->id,
                            'media_path' => $mediaPath,
                            'rows_updated' => $updated
                        ]);
                    }
                }
            }

            // Update any remaining unlinked media
            $unlinkedMedia = K3KampMedia::where('item_id', 0)->get();
            if ($unlinkedMedia->count() > 0) {
                Log::warning('Found unlinked media after processing', [
                    'count' => $unlinkedMedia->count(),
                    'media_ids' => $unlinkedMedia->pluck('id')
                ]);
            }

            DB::commit();
            return redirect()->route('admin.k3-kamp.view')->with('success', 'Laporan berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving K3 KAMP report: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan laporan: ' . $e->getMessage());
        }
    }

    public function view()
    {
        $reports = K3KampReport::with(['items.media'])
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('admin.k3-kamp.view', compact('reports'));
    }

    public function show($id)
    {
        $report = K3KampReport::with(['items.media'])
            ->findOrFail($id);

        return view('admin.k3-kamp.show', compact('report'));
    }

    public function edit($id)
    {
        $report = K3KampReport::with(['items.media'])
            ->findOrFail($id);

        return view('admin.k3-kamp.edit', compact('report'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            K3KampReport::$isSyncing = false;
            
            $report = K3KampReport::findOrFail($id);

            // Update sync_unit_origin if not set
            if (!$report->sync_unit_origin) {
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
                    'mysql' => 'UP Kendari'
                ];
                
                $unitName = $unitMapping[$unitSource] ?? 'UP Kendari';
                
                $report->update([
                    'sync_unit_origin' => $unitName
                ]);
            }
            
            // Update items
            foreach ($report->items as $item) {
                $item->update([
                    'status' => $request->input("status_" . $item->id),
                    'kondisi' => $request->input("kondisi_" . $item->id),
                    'keterangan' => $request->input("keterangan_" . $item->id)
                ]);
            }

            DB::commit();
            return redirect()->route('admin.k3-kamp.view')
                ->with('success', 'Laporan berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui laporan');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // Set sync flag false untuk semua model
            K3KampReport::$isSyncing = false;
            K3KampItem::$isSyncing = false;
            K3KampMedia::$isSyncing = false;
            
            // Ambil report beserta relasi
            $report = K3KampReport::with(['items.media'])->findOrFail($id);
            
            // Debug log
            Log::info('Starting deletion process for report', [
                'report_id' => $id,
                'items_count' => $report->items->count(),
                'media_count' => $report->items->sum(function($item) {
                    return $item->media->count();
                })
            ]);

            // 1. Hapus semua media files dan records
            foreach ($report->items as $item) {
                foreach ($item->media as $media) {
                    // Hapus file fisik
                    Storage::disk('public')->delete($media->file_path);
                    
                    // Hapus record media
                    $media->delete();

                    Log::info('Deleted media', [
                        'media_id' => $media->id,
                        'file_path' => $media->file_path
                    ]);
                }
            }

            // 2. Hapus semua items
            foreach ($report->items as $item) {
                $item->delete();
                
                Log::info('Deleted item', [
                    'item_id' => $item->id,
                    'item_type' => $item->item_type,
                    'item_name' => $item->item_name
                ]);
            }

            // 3. Hapus report
            $report->delete();
            
            Log::info('Successfully deleted report and all related data', [
                'report_id' => $id
            ]);

            DB::commit();
            return redirect()->route('admin.k3-kamp.view')
                ->with('success', 'Laporan dan semua data terkait berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error deleting K3 KAMP report: ' . $e->getMessage(), [
                'exception' => $e,
                'report_id' => $id
            ]);
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus laporan: ' . $e->getMessage());
        }
    }

    public function exportExcel($id)
    {
        try {
            // Load report with all necessary relationships
            $report = K3KampReport::with([
                'items' => function($query) {
                    $query->with(['media' => function($query) {
                        $query->orderBy('created_at', 'desc');
                    }]);
                }
            ])->findOrFail($id);

            // Pastikan date dan created_at adalah instance Carbon
            if (!$report->date instanceof Carbon) {
                $report->date = Carbon::parse($report->date);
            }

            if (!$report->created_at instanceof Carbon) {
                $report->created_at = Carbon::parse($report->created_at);
            }

            // Pastikan semua relasi media sudah di-load
            foreach ($report->items as $item) {
                if (!$item->relationLoaded('media')) {
                    $item->load(['media' => function($query) {
                        $query->orderBy('created_at', 'desc');
                    }]);
                }
            }
            
            return Excel::download(
                new K3KampExport($report), 
                'laporan-k3-kamp-' . $report->date->format('dmY') . '.xlsx'
            );
        } catch (\Exception $e) {
            report($e); // Log error menggunakan Laravel error reporting
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengekspor Excel: ' . $e->getMessage());
        }
    }

    public function exportPdf($id)
    {
        try {
            $report = K3KampReport::with(['items.media'])
                ->findOrFail($id);

            $pdf = PDF::loadView('admin.k3-kamp.pdf', compact('report'));
            return $pdf->download('laporan-k3-kamp-' . $id . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengekspor PDF');
        }
    }

    public function uploadMedia(Request $request)
    {
        $request->validate([
            'media_file' => 'required|file|mimes:jpeg,png,jpg,gif|max:51200', // 50MB max
            'row_id' => 'required'
        ]);

        try {
            $file = $request->file('media_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Store file
            $path = $file->storeAs(
                'k3-kamp-media',
                $fileName,
                'public'
            );

            // Create media record with item_id 0 initially
            // It will be updated later when the form is submitted
            $media = K3KampMedia::create([
                'item_id' => 0, // Set to 0 initially
                'media_type' => 'image',
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Media berhasil diupload',
                'data' => [
                    'id' => $media->id,
                    'preview_url' => asset('storage/' . $path),
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path // Send back file_path for hidden input
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload media: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteMedia($id)
    {
        try {
            $media = K3KampMedia::findOrFail($id);
            Storage::disk('public')->delete($media->file_path);
            $media->delete();

            return response()->json([
                'success' => true,
                'message' => 'Media berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus media: ' . $e->getMessage()
            ], 500);
        }
    }
} 