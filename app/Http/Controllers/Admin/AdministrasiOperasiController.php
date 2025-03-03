<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdministrasiOperasiController extends Controller
{
    public function index()
    {
        return view('admin.administrasi_operasi.index');
    }
} 