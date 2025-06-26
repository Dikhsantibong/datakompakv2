<?php

namespace App\Http\Controllers\Admin\OperasiUpkd;

use App\Http\Controllers\Controller;
use App\Models\LinkKoordinasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LinkKoordinasiController extends Controller
{
    public function index()
    {
        $links = LinkKoordinasi::latest()->get();
        return view('admin.operasi-upkd.link-koordinasi.index', compact('links'));
    }

    public function create()
    {
        return view('admin.operasi-upkd.link-koordinasi.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uraian' => 'required|string',
            'link' => 'required|url',
            'monitoring' => 'required|in:harian,mingguan,bulanan',
            'koordinasi' => 'required|in:eng,bs,ops'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.operasi-upkd.link-koordinasi.create')
                ->withErrors($validator)
                ->withInput();
        }

        LinkKoordinasi::create($request->all());

        return redirect()
            ->route('admin.operasi-upkd.link-koordinasi.index')
            ->with('success', 'Link koordinasi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $link = LinkKoordinasi::findOrFail($id);
        return view('admin.operasi-upkd.link-koordinasi.edit', compact('link'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'uraian' => 'required|string',
            'link' => 'required|url',
            'monitoring' => 'required|in:harian,mingguan,bulanan',
            'koordinasi' => 'required|in:eng,bs,ops'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.operasi-upkd.link-koordinasi.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        $link = LinkKoordinasi::findOrFail($id);
        $link->update($request->all());

        return redirect()
            ->route('admin.operasi-upkd.link-koordinasi.index')
            ->with('success', 'Link koordinasi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $link = LinkKoordinasi::findOrFail($id);
        $link->delete();

        return redirect()
            ->route('admin.operasi-upkd.link-koordinasi.index')
            ->with('success', 'Link koordinasi berhasil dihapus');
    }
}
