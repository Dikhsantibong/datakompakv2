<?php

namespace App\Http\Controllers\Admin\OperasiUpkd;

use App\Http\Controllers\Controller;

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
}
