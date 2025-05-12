<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KitUpKendariController extends Controller
{
    public function index()
    {
        // For now, we'll use dummy data
        $dates = range(1, 30); // Array of dates from 1 to 30
        
        // Dummy data structure for the table
        $powerPlants = [
            'A. SISTEM KENDARI' => [
                'PLTU MORAMO #1',
                'PLTU MORAMO #2',
                'LINE TRANSFER MAULI'
            ],
            'B. SISTEM BAU-BAU' => [
                'PLTU BARUTA #1',
                'PLTU BARUTA #2',
                'LINE TRANSFER RAU2-RAHA'
            ]
        ];

        return view('admin.kit-up-kendari.index', compact('dates', 'powerPlants'));
    }
} 