<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AbnormalReport;
use App\Models\AbnormalChronology;
use App\Models\AffectedMachine;
use App\Models\FollowUpAction;
use App\Models\Recommendation;
use App\Models\AdmAction;
use App\Exports\AbnormalReportExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AbnormalEvidence;
use Illuminate\Support\Facades\Storage;
use App\Models\PowerPlant;

class AbnormalReportController extends Controller
{
    public function index()
    {
        return view('admin.abnormal-report.index');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Get unit source from current session
            $unitSource = session('unit', 'mysql');
            $unitMapping = [
                'mysql_poasia' => 'PLTD Poasia',
                'mysql_kolaka' => 'PLTD Kolaka',
                'mysql_bau_bau' => 'PLTD Bau Bau',
                'mysql_wua_wua' => 'PLTD Wua Wua',
                'mysql' => 'UP Kendari'
            ];
            
            $unitName = $unitMapping[$unitSource] ?? 'UP Kendari';

            // Create main report
            $report = AbnormalReport::create([
                'created_by' => Auth::id(),
                'sync_unit_origin' => $unitName
            ]);

            // Store evidences
            if ($request->hasFile('evidence_files')) {
                foreach ($request->file('evidence_files') as $key => $file) {
                    $path = $file->store('abnormalreport', 'public');
                    $report->evidences()->create([
                        'file_path' => $path,
                        'description' => $request->evidence_descriptions[$key] ?? null
                    ]);
                }
            }

            // Store chronologies
            if ($request->has('waktu')) {
                foreach ($request->waktu as $key => $waktu) {
                    if ($waktu) {
                        $report->chronologies()->create([
                            'waktu' => $waktu,
                            'uraian_kejadian' => $request->uraian_kejadian[$key] ?? null,
                            'visual' => $request->visual[$key] ?? null,
                            'parameter' => $request->parameter[$key] ?? null,
                            'turun_beban' => isset($request->turun_beban) && is_array($request->turun_beban) && in_array($key, array_keys($request->turun_beban)) ? 1 : 0,
                            'off_cbg' => isset($request->off_cbg) && is_array($request->off_cbg) && in_array($key, array_keys($request->off_cbg)) ? 1 : 0,
                            'stop' => isset($request->stop) && is_array($request->stop) && in_array($key, array_keys($request->stop)) ? 1 : 0,
                            'tl_ophar' => isset($request->tl_ophar) && is_array($request->tl_ophar) && in_array($key, array_keys($request->tl_ophar)) ? 1 : 0,
                            'tl_op' => isset($request->tl_op) && is_array($request->tl_op) && in_array($key, array_keys($request->tl_op)) ? 1 : 0,
                            'tl_har' => isset($request->tl_har) && is_array($request->tl_har) && in_array($key, array_keys($request->tl_har)) ? 1 : 0,
                            'mul' => isset($request->mul) && is_array($request->mul) && in_array($key, array_keys($request->mul)) ? 1 : 0
                        ]);
                    }
                }
            }

            // Store affected machines
            if ($request->has('nama_mesin')) {
                foreach ($request->nama_mesin as $key => $nama_mesin) {
                    if ($nama_mesin) {
                        $report->affectedMachines()->create([
                            'nama_mesin' => $nama_mesin,
                            'kondisi_rusak' => isset($request->kondisi_rusak) && is_array($request->kondisi_rusak) && in_array($key, array_keys($request->kondisi_rusak)) ? 1 : 0,
                            'kondisi_abnormal' => isset($request->kondisi_abnormal) && is_array($request->kondisi_abnormal) && in_array($key, array_keys($request->kondisi_abnormal)) ? 1 : 0,
                            'keterangan' => $request->keterangan[$key] ?? null
                        ]);
                    }
                }
            }

            // Store follow up actions
            if ($request->has('usul_mo_rutin')) {
                foreach ($request->usul_mo_rutin as $key => $usul) {
                    if ($usul) {
                        $report->followUpActions()->create([
                            'flm_tindakan' => isset($request->flm_tindakan) && is_array($request->flm_tindakan) && in_array($key, array_keys($request->flm_tindakan)) ? 1 : 0,
                            'usul_mo_rutin' => $usul,
                            'mo_non_rutin' => isset($request->mo_non_rutin) && is_array($request->mo_non_rutin) && in_array($key, array_keys($request->mo_non_rutin)) ? 1 : 0,
                            'lainnya' => $request->lainnya[$key] ?? null
                        ]);
                    }
                }
            }

            // Store recommendations
            if ($request->has('rekomendasi')) {
                foreach ($request->rekomendasi as $rekomendasi) {
                    if ($rekomendasi) {
                        $report->recommendations()->create([
                            'rekomendasi' => $rekomendasi
                        ]);
                    }
                }
            }

            // Store ADM actions
            if ($request->has('adm_flm')) {
                foreach ($request->adm_flm as $key => $_) {
                    $report->admActions()->create([
                        'flm' => isset($request->adm_flm) && is_array($request->adm_flm) && in_array($key, array_keys($request->adm_flm)) ? 1 : 0,
                        'pm' => isset($request->adm_pm) && is_array($request->adm_pm) && in_array($key, array_keys($request->adm_pm)) ? 1 : 0,
                        'cm' => isset($request->adm_cm) && is_array($request->adm_cm) && in_array($key, array_keys($request->adm_cm)) ? 1 : 0,
                        'ptw' => isset($request->adm_ptw) && is_array($request->adm_ptw) && in_array($key, array_keys($request->adm_ptw)) ? 1 : 0,
                        'sr' => isset($request->adm_sr) && is_array($request->adm_sr) && in_array($key, array_keys($request->adm_sr)) ? 1 : 0
                    ]);
                }
            }

            DB::commit();
            return redirect()
                ->route('admin.abnormal-report.list')
                ->with('success', 'Laporan berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        $query = AbnormalReport::with(['affectedMachines', 'creator']);

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter by unit origin
        if ($request->filled('unit_origin')) {
            $query->where('sync_unit_origin', $request->unit_origin);
        }

        // Filter by status (Rusak/Abnormal/Normal)
        if ($request->filled('status')) {
            $status = $request->status;
            $query->whereHas('affectedMachines', function($q) use ($status) {
                if ($status === 'Rusak') {
                    $q->where('kondisi_rusak', true);
                } elseif ($status === 'Abnormal') {
                    $q->where('kondisi_abnormal', true)
                      ->where('kondisi_rusak', false);
                } else {
                    $q->where('kondisi_rusak', false)
                      ->where('kondisi_abnormal', false);
                }
            });
        }

        // Search by machine name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('affectedMachines', function($q) use ($search) {
                $q->where('nama_mesin', 'like', '%' . $search . '%');
            });
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(10);

        // Append query parameters to pagination links
        $reports->appends($request->all());

        return view('admin.abnormal-report.list', compact('reports'));
    }

    public function show($id)
    {
        $report = AbnormalReport::with([
            'chronologies',
            'affectedMachines',
            'followUpActions',
            'recommendations',
            'admActions',
            'creator'
            
        ])->findOrFail($id);

        return view('admin.abnormal-report.show', compact('report'));
    }

    public function destroy($id)
    {
        try {
            $report = AbnormalReport::findOrFail($id);
            $report->delete();

            return redirect()
                ->route('admin.abnormal-report.list')
                ->with('success', 'Laporan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $report = AbnormalReport::with([
            'chronologies',
            'affectedMachines',
            'followUpActions',
            'recommendations',
            'admActions'
        ])->findOrFail($id);

        return view('admin.abnormal-report.edit', compact('report'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $report = AbnormalReport::findOrFail($id);

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
                    
                ];
                
                $unitName = $unitMapping[$unitSource] ?? 'UP Kendari';
                
                $report->update([
                    'sync_unit_origin' => $unitName
                ]);
            }

            // Delete existing related records
            $report->chronologies()->delete();
            $report->affectedMachines()->delete();
            $report->followUpActions()->delete();
            $report->recommendations()->delete();
            $report->admActions()->delete();
            
            // Handle evidence updates
            if ($request->hasFile('evidence_files')) {
                // Delete old evidence files
                foreach ($report->evidences as $evidence) {
                    if ($evidence->file_path) {
                        Storage::disk('public')->delete($evidence->file_path);
                    }
                }
                $report->evidences()->delete();
                
                // Store new evidence files
                foreach ($request->file('evidence_files') as $key => $file) {
                    $path = $file->store('abnormalreport', 'public');
                    $report->evidences()->create([
                        'file_path' => $path,
                        'description' => $request->evidence_descriptions[$key] ?? null
                    ]);
                }
            }

            // Store chronologies
            if ($request->has('waktu')) {
                foreach ($request->waktu as $key => $waktu) {
                    if ($waktu) {
                        $report->chronologies()->create([
                            'waktu' => $waktu,
                            'uraian_kejadian' => $request->uraian_kejadian[$key] ?? null,
                            'visual' => $request->visual[$key] ?? null,
                            'parameter' => $request->parameter[$key] ?? null,
                            'turun_beban' => isset($request->turun_beban) && is_array($request->turun_beban) && in_array($key, array_keys($request->turun_beban)) ? 1 : 0,
                            'off_cbg' => isset($request->off_cbg) && is_array($request->off_cbg) && in_array($key, array_keys($request->off_cbg)) ? 1 : 0,
                            'stop' => isset($request->stop) && is_array($request->stop) && in_array($key, array_keys($request->stop)) ? 1 : 0,
                            'tl_ophar' => isset($request->tl_ophar) && is_array($request->tl_ophar) && in_array($key, array_keys($request->tl_ophar)) ? 1 : 0,
                            'tl_op' => isset($request->tl_op) && is_array($request->tl_op) && in_array($key, array_keys($request->tl_op)) ? 1 : 0,
                            'tl_har' => isset($request->tl_har) && is_array($request->tl_har) && in_array($key, array_keys($request->tl_har)) ? 1 : 0,
                            'mul' => isset($request->mul) && is_array($request->mul) && in_array($key, array_keys($request->mul)) ? 1 : 0
                        ]);
                    }
                }
            }

            // Store affected machines
            if ($request->has('nama_mesin')) {
                foreach ($request->nama_mesin as $key => $nama_mesin) {
                    if ($nama_mesin) {
                        $report->affectedMachines()->create([
                            'nama_mesin' => $nama_mesin,
                            'kondisi_rusak' => isset($request->kondisi_rusak) && is_array($request->kondisi_rusak) && in_array($key, array_keys($request->kondisi_rusak)) ? 1 : 0,
                            'kondisi_abnormal' => isset($request->kondisi_abnormal) && is_array($request->kondisi_abnormal) && in_array($key, array_keys($request->kondisi_abnormal)) ? 1 : 0,
                            'keterangan' => $request->keterangan[$key] ?? null
                        ]);
                    }
                }
            }

            // Store follow up actions
            if ($request->has('usul_mo_rutin')) {
                foreach ($request->usul_mo_rutin as $key => $usul) {
                    if ($usul) {
                        $report->followUpActions()->create([
                            'flm_tindakan' => isset($request->flm_tindakan) && is_array($request->flm_tindakan) && in_array($key, array_keys($request->flm_tindakan)) ? 1 : 0,
                            'usul_mo_rutin' => $usul,
                            'mo_non_rutin' => isset($request->mo_non_rutin) && is_array($request->mo_non_rutin) && in_array($key, array_keys($request->mo_non_rutin)) ? 1 : 0,
                            'lainnya' => $request->lainnya[$key] ?? null
                        ]);
                    }
                }
            }

            // Store recommendations
            if ($request->has('rekomendasi')) {
                foreach ($request->rekomendasi as $rekomendasi) {
                    if ($rekomendasi) {
                        $report->recommendations()->create([
                            'rekomendasi' => $rekomendasi
                        ]);
                    }
                }
            }

            // Store ADM actions
            if ($request->has('adm_flm')) {
                foreach ($request->adm_flm as $key => $_) {
                    $report->admActions()->create([
                        'flm' => isset($request->adm_flm) && is_array($request->adm_flm) && in_array($key, array_keys($request->adm_flm)) ? 1 : 0,
                        'pm' => isset($request->adm_pm) && is_array($request->adm_pm) && in_array($key, array_keys($request->adm_pm)) ? 1 : 0,
                        'cm' => isset($request->adm_cm) && is_array($request->adm_cm) && in_array($key, array_keys($request->adm_cm)) ? 1 : 0,
                        'ptw' => isset($request->adm_ptw) && is_array($request->adm_ptw) && in_array($key, array_keys($request->adm_ptw)) ? 1 : 0,
                        'sr' => isset($request->adm_sr) && is_array($request->adm_sr) && in_array($key, array_keys($request->adm_sr)) ? 1 : 0
                    ]);
                }
            }

            DB::commit();
            return redirect()
                ->route('admin.abnormal-report.list')
                ->with('success', 'Laporan berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function exportExcel($id)
    {
        try {
            $report = AbnormalReport::with([
                'chronologies' => function($query) {
                    $query->orderBy('waktu', 'asc');
                },
                'affectedMachines',
                'followUpActions',
                'recommendations',
                'admActions',
                'creator'
            ])->findOrFail($id);

            return Excel::download(
                new AbnormalReportExport($report), 
                'laporan-abnormal-' . $report->created_at->format('Y-m-d') . '.xlsx'
            );
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat mengekspor Excel: ' . $e->getMessage());
        }
    }

    public function exportPdf($id)
    {
        try {
            $report = AbnormalReport::with([
                'chronologies',
                'affectedMachines',
                'followUpActions',
                'recommendations',
                'admActions',
                'creator'
            ])->findOrFail($id);

            $pdf = PDF::loadView('admin.abnormal-report.pdf', compact('report'));
            
            // Set paper size to A4 and landscape orientation for better table display
            $pdf->setPaper('a4', 'landscape');
            
            return $pdf->download('laporan-abnormal-' . $id . '.pdf');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat mengekspor PDF: ' . $e->getMessage());
        }
    }
} 