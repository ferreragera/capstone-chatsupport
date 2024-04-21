<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch data from the JSON file or any other source
        $json_data = file_get_contents('C:\xampp\htdocs\capstone-chatsupport\python\intents.json');
        $data = json_decode($json_data, true);
        
        // Assuming 'intents' is the key holding your data
        $paginated_intents = $data['intents'];

        // Pass the data to the view
        return view('dashboard', compact('paginated_intents'));
    }

}
