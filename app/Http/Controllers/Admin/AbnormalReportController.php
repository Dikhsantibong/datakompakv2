<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AbnormalReportController extends Controller
{
    public function index()
    {
        return view('admin.abnormal-report.index');
    }

    public function store(Request $request)
    {
        // TODO: Implement store logic
        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function list()
    {
        return view('admin.abnormal-report.list');
    }

    public function show($id)
    {
        return view('admin.abnormal-report.show');
    }
} 