<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    public function index()
    {
        $beritaAcaraFiles = Document::where('category', 'berita-acara')->get();
        $standarisasiFiles = Document::where('category', 'standarisasi')->get();
        $bacaanDigitalFiles = Document::where('category', 'bacaan-digital')->get();
        $diklatFiles = Document::where('category', 'diklat')->get();
        $sopKitFiles = Document::where('category', 'sop-kit')->get();
        $baTransaksiFiles = Document::where('category', 'ba-transaksi')->get();
        $operasiLainnyaFiles = Document::where('category', 'operasi-lainnya')->get();

        return view('admin.library.index', compact(
            'beritaAcaraFiles',
            'standarisasiFiles',
            'bacaanDigitalFiles',
            'diklatFiles',
            'sopKitFiles',
            'baTransaksiFiles',
            'operasiLainnyaFiles'
        ));
    }

    public function beritaAcara()
    {
        $beritaAcaraFiles = Document::where('category', 'berita-acara')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.library.berita-acara', compact('beritaAcaraFiles'));
    }

    public function standarisasi()
    {
        $standarisasiFiles = Document::where('category', 'standarisasi')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.library.standarisasi', compact('standarisasiFiles'));
    }

    public function bacaanDigital()
    {
        $bacaanDigitalFiles = Document::where('category', 'bacaan-digital')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.library.bacaan-digital', compact('bacaanDigitalFiles'));
    }

    public function diklat()
    {
        $diklatFiles = Document::where('category', 'diklat')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.library.diklat', compact('diklatFiles'));
    }

    public function sopKit()
    {
        $sopKitFiles = Document::where('category', 'sop-kit')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.library.sop-kit', compact('sopKitFiles'));
    }

    public function baTransaksi()
    {
        $baTransaksiFiles = Document::where('category', 'ba-transaksi')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.library.ba-transaksi', compact('baTransaksiFiles'));
    }

    public function operasiLainnya()
    {
        $operasiLainnyaFiles = Document::where('category', 'operasi-lainnya')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.library.operasi-lainnya', compact('operasiLainnyaFiles'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'category' => 'required|in:berita-acara,standarisasi,bacaan-digital,diklat,sop-kit',
            'description' => 'nullable|string'
        ]);

        $file = $request->file('document');
        $path = $file->store('documents/' . $request->category);

        Document::create([
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'category' => $request->category,
            'description' => $request->description,
            'user_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil diunggah');
    }

    public function download(Document $document)
    {
        return Storage::download($document->path, $document->name);
    }

    public function destroy(Document $document)
    {
        try {
            // Hapus file fisik
            if (Storage::exists($document->path)) {
                Storage::delete($document->path);
            }

            // Hapus record dari database
            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting file: ' . $e->getMessage()
            ], 500);
        }
    }
} 