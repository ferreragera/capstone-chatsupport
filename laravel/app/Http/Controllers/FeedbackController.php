<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ratings;
use App\Models\feedback;
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

        $labels = ['1', '2', '3', '4', '5']; 
        $values = [];
        for ($i = 1; $i <= 5; $i++) {
            $values[] = $data->where('rating_value', $i)->first()->total ?? 0;
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }

    public function fetchFeedbackData()
    {
        $weeklyFeedbackCount = feedback::selectRaw('COUNT(*) as count, WEEK(created_at) as week, 
                            MIN(DATE(created_at)) as start_date, MAX(DATE(created_at)) as end_date')
            ->groupBy(DB::raw('WEEK(created_at)'))
            ->orderBy('week')
            ->get();

        $labels = [];
        $values = [];

        foreach ($weeklyFeedbackCount as $feedback) {
            // "May 12 - 18, 2024"
            $startDate = Carbon::parse($feedback->start_date)->format('F j');
            $endDate = Carbon::parse($feedback->end_date)->format('F j, Y');
            $label = "$startDate - $endDate";
            
            $labels[] = $label;
            $values[] = $feedback->count;
        }

        $feedbackData = [
            'labels' => $labels,
            'values' => $values
        ];

        return response()->json($feedbackData);
    }

}
