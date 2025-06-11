<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HistoryController extends Controller
{
    /**
     * Display the history page.
     */
    public function index(Request $request): View
    {
        // Placeholder data - you can replace with actual history data
        $historyData = [
            ['date' => '2024-01-15', 'time' => '14:30', 'max_db' => 85, 'avg_db' => 65, 'duration' => '02:15:30'],
            ['date' => '2024-01-14', 'time' => '09:45', 'max_db' => 78, 'avg_db' => 58, 'duration' => '01:45:20'],
            ['date' => '2024-01-13', 'time' => '16:20', 'max_db' => 92, 'avg_db' => 72, 'duration' => '03:20:15'],
        ];

        return view('history', compact('historyData'));
    }
}
