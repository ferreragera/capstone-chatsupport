<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ratings;
use App\Models\feedback;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedback = DB::table('feedback')->where('remarks', '0')->get();
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

    public function store(Request $request)
    {
        if ($request->isMethod('post')) {

            $tag = $request->input('addTag');
            $patterns = $request->input('addPatterns');
            $responses = $request->input('addResponses');

            // Save the data to intents.json
            $json_data = file_get_contents('C:\xampp\htdocs\capstone-chatsupport\python\intents.json');
            $intents = json_decode($json_data, true);

            $last_entry = end($intents['intents']);

            $last_id = isset($last_entry['id']) ? $last_entry['id'] : 0;

            $new_id = ++$last_id;

            $new_intent = [
                "id" => $new_id,
                "tag" => $tag,
                "patterns" => $patterns,
                "responses" => $responses
            ];

            $intents['intents'][] = $new_intent;

            file_put_contents('C:\xampp\htdocs\capstone-chatsupport\python\intents.json', json_encode($intents, JSON_PRETTY_PRINT));

            // Update the remarks column to '1' for the added feedback
            Feedback::where('feedback', $patterns)->update(['remarks' => '1']);

            // SweetAlert2 for success message
            return response()->json(['success' => 'Intent added successfully, click the train button to refresh the data.']);

        }
    }
    public function update(Request $request, $id)
    {
        // Validate the request data if necessary
        $request->validate([
            'remarks' => 'required|string'
        ]);

        // Find the feedback by ID and update the remarks
        $feedback = Feedback::findOrFail($id);
        $feedback->remarks = $request->remarks;
        $feedback->save();

        return response()->json(['message' => 'Feedback updated successfully']);
    }


}
