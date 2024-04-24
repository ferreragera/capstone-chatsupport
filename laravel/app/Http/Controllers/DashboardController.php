<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // public function index()
    // {
    //     $json_data = file_get_contents('C:\xampp\htdocs\capstone-chatsupport\python\intents.json');
    //     $data = json_decode($json_data, true);
        
    //     $paginated_intents = $data['intents'];

    //     return view('dashboard', compact('paginated_intents'));
    // }

    public function index()
    {
        $json_data = file_get_contents('C:\xampp\htdocs\capstone-chatsupport\python\intents.json');
        $data = json_decode($json_data, true);
        
        $paginated_intents = $data['intents'];

        dd($paginated_intents); // Add this line to check the contents

        return view('dashboard', compact('paginated_intents'));
    }

}
