<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        $json_data = file_get_contents('C:\xampp\htdocs\capstone-chatsupport\python\intents.json');
        $data = json_decode($json_data, true);
        
        $paginated_intents = $data['intents'];

        dd($paginated_intents);

        return view('dashboard', compact('paginated_intents'));
    }

}
