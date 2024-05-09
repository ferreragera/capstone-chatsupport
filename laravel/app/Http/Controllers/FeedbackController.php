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
        $feedback = DB::table('feedback')->select('feedback')->get();

        $data = compact('feedback');
        return view('reports')->with('$data');
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

        $labels = [];
        $values = [];
        for ($i = 1; $i <= 5; $i++) {
            $labels[] = $i;
            $values[] = $data->where('rating_value', $i)->first()->total ?? 0;
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }

    public function fetchFeedbackData()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $data = Feedback::selectRaw('DATE_FORMAT(created_at, "%M %d") as start_date, DATE_FORMAT(DATE_ADD(created_at, INTERVAL 6 DAY), "%M %d, %Y") as end_date, COUNT(*) as total')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->groupBy('start_date', 'end_date')
            ->orderBy('start_date', 'asc')
            ->get();

        $labels = [];
        $values = [];
        foreach ($data as $item) {
            $labels[] = $item->start_date . ' - ' . $item->end_date;
            $values[] = $item->total;
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }


}
