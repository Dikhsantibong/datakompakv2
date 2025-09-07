<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailySummary;
use App\Models\PowerPlant;
use Carbon\Carbon;

class DailySummaryResultController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $carbonBulan = Carbon::createFromFormat('Y-m', $bulan);
        $daysInMonth = $carbonBulan->daysInMonth;
        $unitFilter = $request->input('unit');
        $isMain = session('unit') === 'mysql';

        // Ambil semua unit jika session mysql
        $units = $isMain ? PowerPlant::orderBy('name')->get() : collect();

        $query = DailySummary::whereYear('date', $carbonBulan->year)
            ->whereMonth('date', $carbonBulan->month);
        if ($isMain && $unitFilter) {
            $query->where('power_plant_id', $unitFilter);
        } elseif (!$isMain) {
            // Untuk unit lokal, filter otomatis by session
        }
        $summaries = $query->orderBy('date')->get();

        // Group by tanggal dan unit jika session mysql
        if ($isMain) {
            $grouped = [];
            foreach ($summaries as $summary) {
                $day = $summary->date->format('d');
                $unitId = $summary->power_plant_id;
                $grouped[$unitId][$day][] = $summary;
            }
            $summaries = $grouped;
        } else {
            $summaries = $summaries->groupBy(function($item) {
                return $item->date->format('d');
            });
        }

        return view('admin.daily-summary.result-excel', [
            'bulan' => $bulan,
            'carbonBulan' => $carbonBulan,
            'daysInMonth' => $daysInMonth,
            'summaries' => $summaries,
            'units' => $units,
            'unitFilter' => $unitFilter,
            'isMain' => $isMain,
        ]);
    }
}
