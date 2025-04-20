<?php

namespace App\Http\Controllers\Admin\OperasiUpkd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengadaanController extends Controller
{
    public function index()
    {
        return view('admin.operasi-upkd.pengadaan.index');
    }
} 