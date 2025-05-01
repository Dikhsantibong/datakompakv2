<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlackstartController extends Controller
{
    public function index()
    {
        return view('admin.blackstart.index');
    }

    public function show()
    {
        // This will show the table of all entries
        return view('admin.blackstart.show');
    }
} 