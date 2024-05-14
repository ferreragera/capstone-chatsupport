<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ratings;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedback = DB::table('feedback')->get();
        return view('reports', compact('feedback')); 
    }
    
    public function fetchChartData()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $data = ratings::selectRaw('rating_value, COUNT(*) as total')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->groupBy('rating_value')
            ->orderBy('rating_value')
            ->get();

        $labels = ['1', '2', '3', '4', '5']; // Ensures labels are strings for Chart.js
        $values = [];
        for ($i = 1; $i <= 5; $i++) {
            $values[] = $data->where('rating_value', $i)->first()->total ?? 0;
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }





    // public function fetchFeedbackData()
    // {
    //     $startOfWeek = Carbon::now()->startOfWeek();
    //     $endOfWeek = Carbon::now()->endOfWeek();
    //     dd($startOfWeek, $endOfWeek);
    //     $data = Feedback::selectRaw('DATE_FORMAT(created_at, "%M %d") as start_date, DATE_FORMAT(DATE_ADD(created_at, INTERVAL 6 DAY), "%M %d, %Y") as end_date, COUNT(*) as total')
    //     ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
    //     ->groupBy('start_date', 'end_date')
    //     ->orderBy('start_date', 'asc')
    //     ->toSql();

    //     dd($data);


    //     $labels = [];
    //     $values = [];
    //     foreach ($data as $item) {
    //         $labels[] = $item->start_date . ' - ' . $item->end_date;
    //         $values[] = $item->total;
    //     }

    //     return response()->json([
    //         'labels' => $labels,
    //         'values' => $values,
    //     ]);
    // }



}
