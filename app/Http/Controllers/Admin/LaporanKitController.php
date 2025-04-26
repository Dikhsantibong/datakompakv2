<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanKitController extends Controller
{
    public function index()
    {
        return view('admin.laporan-kit.index');
    }

    public function create()
    {
        return view('admin.laporan-kit.create');
    }
} 