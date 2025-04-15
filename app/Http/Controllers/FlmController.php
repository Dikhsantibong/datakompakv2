<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FlmController extends Controller
{
    public function index()
    {
        return view('admin.flm.index');
    }

    public function store(Request $request)
    {
        // This is just a placeholder for now since we're not implementing database functionality yet
        return redirect()->back()->with('success', 'Form submitted successfully');
    }
} 