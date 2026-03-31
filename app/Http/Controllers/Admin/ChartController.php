<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    /**
     * Ambil data voting realtime (JSON)
     */
    public function data()
    {
        // ambil kandidat + jumlah vote
        $candidates = Candidate::withCount('votes')->get();

        return response()->json([
            'labels' => $candidates->pluck('nama'),
            'votes'  => $candidates->pluck('votes_count'),
        ]);
    }
}