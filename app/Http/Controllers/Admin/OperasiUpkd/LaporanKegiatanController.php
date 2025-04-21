<?php

namespace App\Http\Controllers\Admin\OperasiUpkd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanKegiatanController extends Controller
{
    public function index()
    {
        return view('admin.operasi-upkd.laporan-kegiatan.index');
    }
} 