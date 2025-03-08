<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LibraryController extends Controller
{
    public function index()
    {
        $beritaAcaraFiles = Document::where('category', 'berita-acara')->get();
        $standarisasiFiles = Document::where('category', 'standarisasi')->get();
        $bacaanDigitalFiles = Document::where('category', 'bacaan-digital')->get();
        $diklatFiles = Document::where('category', 'diklat')->get();

        return view('admin.administrasi_operasi.library.index', compact(
            'beritaAcaraFiles',
            'standarisasiFiles',
            'bacaanDigitalFiles',
            'diklatFiles'
        ));
    }

    public function beritaAcara()
    {
        $beritaAcaraFiles = Document::where('category', 'berita-acara')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.administrasi_operasi.library.berita-acara', compact('beritaAcaraFiles'));
    }

    public function standarisasi()
    {
        $standarisasiFiles = Document::where('category', 'standarisasi')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.administrasi_operasi.library.standarisasi', compact('standarisasiFiles'));
    }

    public function bacaanDigital()
    {
        $bacaanDigitalFiles = Document::where('category', 'bacaan-digital')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.administrasi_operasi.library.bacaan-digital', compact('bacaanDigitalFiles'));
    }

    public function diklat()
    {
        $diklatFiles = Document::where('category', 'diklat')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.administrasi_operasi.library.diklat', compact('diklatFiles'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'category' => 'required|in:berita-acara,standarisasi,bacaan-digital,diklat',
            'description' => 'nullable|string'
        ]);

        $file = $request->file('document');
        $path = $file->store('documents/' . $request->category);

        Document::create([
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'category' => $request->category,
            'description' => $request->description,
            'user_id' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil diunggah');
    }

    public function download(Document $document)
    {
        return Storage::download($document->path, $document->name);
    }

    public function destroy(Document $document)
    {
        Storage::delete($document->path);
        $document->delete();

        return response()->json(['message' => 'File deleted successfully']);
    }
} 