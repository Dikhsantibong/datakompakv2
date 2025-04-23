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
            // Create main report
            $report = AbnormalReport::create([
                'created_by' => Auth::id()
            ]);

            // Store chronologies
            if ($request->has('waktu')) {
                foreach ($request->waktu as $key => $waktu) {
                    if ($waktu) {
                        $report->chronologies()->create([
                            'waktu' => $waktu,
                            'uraian_kejadian' => $request->uraian_kejadian[$key] ?? null,
                            'visual_parameter' => $request->visual_parameter[$key] ?? null,
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
                            'mo_non_rutin' => isset($request->mo_non_rutin) && is_array($request->mo_non_rutin) && in_array($key, array_keys($request->mo_non_rutin)) ? 1 : 0
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
                        'ptw' => isset($request->adm_ptw) && is_array($request->adm_ptw) && in_array($key, array_keys($request->adm_ptw)) ? 1 : 0
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

    public function list()
    {
        $reports = AbnormalReport::with(['affectedMachines', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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

            // Delete existing related records
            $report->chronologies()->delete();
            $report->affectedMachines()->delete();
            $report->followUpActions()->delete();
            $report->recommendations()->delete();
            $report->admActions()->delete();

            // Store chronologies
            if ($request->has('waktu')) {
                foreach ($request->waktu as $key => $waktu) {
                    if ($waktu) {
                        $report->chronologies()->create([
                            'waktu' => $waktu,
                            'uraian_kejadian' => $request->uraian_kejadian[$key] ?? null,
                            'visual_parameter' => $request->visual_parameter[$key] ?? null,
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
                            'mo_non_rutin' => isset($request->mo_non_rutin) && is_array($request->mo_non_rutin) && in_array($key, array_keys($request->mo_non_rutin)) ? 1 : 0
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
                        'ptw' => isset($request->adm_ptw) && is_array($request->adm_ptw) && in_array($key, array_keys($request->adm_ptw)) ? 1 : 0
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
                'chronologies',
                'affectedMachines',
                'followUpActions',
                'recommendations',
                'admActions',
                'creator'
            ])->findOrFail($id);

            return Excel::download(new AbnormalReportExport($report), 'laporan-abnormal-' . $id . '.xlsx');
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