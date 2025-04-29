<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PatrolCheckController extends Controller
{
    public function index()
    {
        return view('admin.patrol-check.index');
    }

    public function list()
    {
        // Create empty collection and paginate it
        $collection = new Collection();
        $page = request()->get('page', 1);
        $perPage = 10;
        
        $patrols = new LengthAwarePaginator(
            $collection->forPage($page, $perPage),
            $collection->count(),
            $perPage,
            $page,
            ['path' => request()->url()]
        );

        return view('admin.patrol-check.list', compact('patrols'));
    }

    public function show($id)
    {
        // For now, we'll create a dummy patrol object
        $patrol = (object)[
            'creator' => (object)['name' => 'User Test'],
            'created_at' => now(),
            'conditions' => new Collection(),
            'abnormalConditions' => new Collection()
        ];
        return view('admin.patrol-check.show', compact('patrol'));
    }

    public function store(Request $request)
    {
        // Will implement later
        return redirect()->route('admin.patrol-check.list')->with('success', 'Data berhasil disimpan');
    }

    public function edit($id)
    {
        // Will implement later
        return view('admin.patrol-check.index');
    }

    public function update(Request $request, $id)
    {
        // Will implement later
        return redirect()->route('admin.patrol-check.list')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        // Will implement later
        return redirect()->route('admin.patrol-check.list')->with('success', 'Data berhasil dihapus');
    }

    public function exportExcel($id)
    {
        // Will implement later
        return redirect()->back()->with('success', 'Data berhasil diekspor ke Excel');
    }

    public function exportPdf($id)
    {
        // Will implement later
        return redirect()->back()->with('success', 'Data berhasil diekspor ke PDF');
    }
} 