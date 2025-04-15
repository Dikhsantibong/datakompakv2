<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FiveS5RController extends Controller
{
    public function index()
    {
        return view('admin.5s5r.index');
    }

    public function store(Request $request)
    {
        // This will be implemented later when we add database functionality
        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }
} 