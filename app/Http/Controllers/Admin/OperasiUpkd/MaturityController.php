<?php

namespace App\Http\Controllers\Admin\OperasiUpkd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MaturityController extends Controller
{
    public function index()
    {
        return view('admin.operasi-upkd.maturity.index');
    }
} 