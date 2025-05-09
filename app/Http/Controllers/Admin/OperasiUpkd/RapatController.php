<?php

namespace App\Http\Controllers\Admin\OperasiUpkd;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\MonitoringAplikasi;
use App\Models\RapatDetail;
use App\Models\Section;
use App\Models\Subsection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RapatController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with(['section', 'subsection', 'rapatDetail', 'monitoringAplikasi']);

        if ($request->has('section')) {
            $query->whereHas('section', function($q) use ($request) {
                $q->where('code', $request->section);
            });
        }

        $items = $query->orderBy('section_id')
                      ->orderBy('subsection_id')
                      ->orderBy('order_number')
                      ->get();

        $sections = Section::with(['subsections', 'items' => function($query) {
            $query->orderBy('order_number');
        }])->orderBy('order')->get();

        return view('admin.operasi-upkd.rapat.index', compact('items', 'sections'));
    }

    public function create(Request $request)
    {
        $sections = Section::with('subsections')->orderBy('order')->get();
        $selectedSection = null;
        $selectedSubsection = null;
        $nextOrderNumber = 1;

        if ($request->has('section_id')) {
            $selectedSection = Section::with('subsections')->find($request->section_id);
            
            // Get next order number for this section/subsection
            $query = Item::where('section_id', $request->section_id);
            
            if ($request->has('subsection_id')) {
                $selectedSubsection = Subsection::find($request->subsection_id);
                $query->where('subsection_id', $request->subsection_id);
            } else {
                $query->whereNull('subsection_id');
            }
            
            $lastItem = $query->orderBy('order_number', 'desc')->first();
            if ($lastItem) {
                $nextOrderNumber = $lastItem->order_number + 1;
            }
        }

        return view('admin.operasi-upkd.rapat.create', compact(
            'sections', 
            'selectedSection', 
            'selectedSubsection',
            'nextOrderNumber'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'subsection_id' => 'nullable|exists:subsections,id',
            'uraian' => 'required|string',
            'detail' => 'nullable|string',
            'pic' => 'required|string',
            'kondisi_eksisting' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
            'kondisi_akhir' => 'nullable|string',
            'goal' => 'nullable|string',
            'status' => 'required|in:completed,in_progress,pending',
            'keterangan' => 'nullable|string',
            // Rapat specific fields
            'jadwal' => 'required_if:section_code,H|nullable|date',
            'mode' => 'required_if:section_code,H|nullable|in:online,offline,hybrid',
            'resume' => 'nullable|string',
            'notulen' => 'nullable|file',
            'eviden' => 'nullable|file',
            // Monitoring Aplikasi specific fields
            'aplikasi' => 'required_if:section_code,E|nullable|string',
            'subkolom_harian' => 'required_if:section_code,E|nullable|integer',
            'subkolom_bulanan' => 'required_if:section_code,E|nullable|integer',
            'pic_operasi' => 'required_if:section_code,E|nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Get the last order number for this section/subsection
            $query = Item::where('section_id', $request->section_id);
            if ($request->subsection_id) {
                $query->where('subsection_id', $request->subsection_id);
            } else {
                $query->whereNull('subsection_id');
            }
            $orderNumber = $query->max('order_number') + 1;

            // Create the main item
            $item = Item::create([
                'section_id' => $request->section_id,
                'subsection_id' => $request->subsection_id,
                'order_number' => $orderNumber,
                'uraian' => $request->uraian,
                'detail' => $request->detail,
                'pic' => $request->pic,
                'kondisi_eksisting' => $request->kondisi_eksisting,
                'tindak_lanjut' => $request->tindak_lanjut,
                'kondisi_akhir' => $request->kondisi_akhir,
                'goal' => $request->goal,
                'status' => $request->status,
                'keterangan' => $request->keterangan
            ]);

            // If this is a rapat entry (section H)
            $section = Section::find($request->section_id);
            if ($section->code === 'H' && $request->has('jadwal')) {
                $notulenPath = null;
                $evidenPath = null;

                if ($request->hasFile('notulen')) {
                    $notulenPath = $request->file('notulen')->store('notulen');
                }
                if ($request->hasFile('eviden')) {
                    $evidenPath = $request->file('eviden')->store('eviden');
                }

                RapatDetail::create([
                    'item_id' => $item->id,
                    'jadwal' => $request->jadwal,
                    'mode' => $request->mode,
                    'resume' => $request->resume,
                    'notulen_path' => $notulenPath,
                    'eviden_path' => $evidenPath
                ]);
            }

            // If this is a monitoring aplikasi entry (section E)
            if ($section->code === 'E') {
                MonitoringAplikasi::create([
                    'item_id' => $item->id,
                    'aplikasi' => $request->aplikasi,
                    'subkolom_harian' => $request->subkolom_harian,
                    'subkolom_bulanan' => $request->subkolom_bulanan,
                    'pic_operasi' => $request->pic_operasi
                ]);
            }

            DB::commit();
            return redirect()->route('admin.operasi-upkd.rapat.index')
                ->with('success', 'Data berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function edit($id)
    {
        $item = Item::with(['section', 'subsection', 'rapatDetail', 'monitoringAplikasi'])->findOrFail($id);
        $sections = Section::with('subsections')->orderBy('order')->get();
        return view('admin.operasi-upkd.rapat.edit', compact('item', 'sections'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'uraian' => 'required|string',
            'detail' => 'nullable|string',
            'pic' => 'required|string',
            'kondisi_eksisting' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
            'kondisi_akhir' => 'nullable|string',
            'goal' => 'nullable|string',
            'status' => 'required|in:completed,in_progress,pending',
            'keterangan' => 'nullable|string',
            // Rapat specific fields
            'jadwal' => 'required_if:section_code,H|nullable|date',
            'mode' => 'required_if:section_code,H|nullable|in:online,offline,hybrid',
            'resume' => 'nullable|string',
            'notulen' => 'nullable|file',
            'eviden' => 'nullable|file',
            // Monitoring Aplikasi specific fields
            'aplikasi' => 'required_if:section_code,E|nullable|string',
            'subkolom_harian' => 'required_if:section_code,E|nullable|integer',
            'subkolom_bulanan' => 'required_if:section_code,E|nullable|integer',
            'pic_operasi' => 'required_if:section_code,E|nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $item = Item::findOrFail($id);
            
            $item->update([
                'uraian' => $request->uraian,
                'detail' => $request->detail,
                'pic' => $request->pic,
                'kondisi_eksisting' => $request->kondisi_eksisting,
                'tindak_lanjut' => $request->tindak_lanjut,
                'kondisi_akhir' => $request->kondisi_akhir,
                'goal' => $request->goal,
                'status' => $request->status,
                'keterangan' => $request->keterangan
            ]);

            if ($item->section->code === 'H') {
                $rapatDetail = $item->rapatDetail ?? new RapatDetail(['item_id' => $item->id]);
                
                if ($request->hasFile('notulen')) {
                    if ($rapatDetail->notulen_path) {
                        Storage::delete($rapatDetail->notulen_path);
                    }
                    $rapatDetail->notulen_path = $request->file('notulen')->store('notulen');
                }
                
                if ($request->hasFile('eviden')) {
                    if ($rapatDetail->eviden_path) {
                        Storage::delete($rapatDetail->eviden_path);
                    }
                    $rapatDetail->eviden_path = $request->file('eviden')->store('eviden');
                }

                $rapatDetail->fill([
                    'jadwal' => $request->jadwal,
                    'mode' => $request->mode,
                    'resume' => $request->resume
                ])->save();
            }

            if ($item->section->code === 'E') {
                $monitoringAplikasi = $item->monitoringAplikasi ?? new MonitoringAplikasi(['item_id' => $item->id]);
                $monitoringAplikasi->fill([
                    'aplikasi' => $request->aplikasi,
                    'subkolom_harian' => $request->subkolom_harian,
                    'subkolom_bulanan' => $request->subkolom_bulanan,
                    'pic_operasi' => $request->pic_operasi
                ])->save();
            }

            DB::commit();
            return redirect()->route('admin.operasi-upkd.rapat.index')
                ->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $item = Item::with(['rapatDetail', 'monitoringAplikasi'])->findOrFail($id);

            // Delete associated files if they exist
            if ($item->rapatDetail) {
                if ($item->rapatDetail->notulen_path) {
                    Storage::delete($item->rapatDetail->notulen_path);
                }
                if ($item->rapatDetail->eviden_path) {
                    Storage::delete($item->rapatDetail->eviden_path);
                }
                $item->rapatDetail->delete();
            }

            if ($item->monitoringAplikasi) {
                $item->monitoringAplikasi->delete();
            }

            // Reorder remaining items
            Item::where('section_id', $item->section_id)
                ->where('subsection_id', $item->subsection_id)
                ->where('order_number', '>', $item->order_number)
                ->decrement('order_number');

            $item->delete();

            DB::commit();
            return redirect()->route('admin.operasi-upkd.rapat.index')
                ->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
} 