<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubsistemController extends Controller
{
    public function kendari()
    {
        return view('admin.subsistem.kendari');
    }

    public function bauBau()
    {
        return view('admin.subsistem.bau-bau');
    }
} 