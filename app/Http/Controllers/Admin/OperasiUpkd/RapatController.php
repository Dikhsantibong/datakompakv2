<?php

namespace App\Http\Controllers\Admin\OperasiUpkd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RapatController extends Controller
{
    public function index()
    {
        return view('admin.operasi-upkd.rapat.index');
    }

    public function create()
    {
        return view('admin.operasi-upkd.rapat.create');
    }

    public function store(Request $request)
    {
        // TODO: Implement store logic
        return redirect()->route('admin.operasi-upkd.rapat.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        // TODO: Implement edit logic
        return view('admin.operasi-upkd.rapat.edit');
    }

    public function update(Request $request, $id)
    {
        // TODO: Implement update logic
        return redirect()->route('admin.operasi-upkd.rapat.index')
            ->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        // TODO: Implement delete logic
        return redirect()->route('admin.operasi-upkd.rapat.index')
            ->with('success', 'Data berhasil dihapus');
    }
} 