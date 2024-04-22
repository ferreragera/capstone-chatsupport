<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class IntentsController extends Controller
{

    public function store(Request $request)
    {
        if ($request->isMethod('post')) {

            $tag = $request->input('createTag');
            $patterns = $request->input('createPatterns');
            $responses = $request->input('createResponses');

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

            return redirect()->back()->with('success', 'Intent added successfully, click the train button to refresh the data.');

        }
    }
    


}
