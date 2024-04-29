<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class IntentsController extends Controller
{

    public function index()
    {
        // Fetch data from the JSON file
        $json_data = file_get_contents('C:\xampp\htdocs\capstone-chatsupport\python\intents.json');
        $data = json_decode($json_data, true);
        $paginated_intents = $data['intents'];

        // Pass the data to the view
        return view('dashboard', compact('paginated_intents'));
    }
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

    public function edit(Request $request)
    {
        $file = 'C:\xampp\htdocs\capstone-chatsupport\python\intents.json';
        $data = json_decode(file_get_contents('php://input'), true);

        // Write updated JSON data back to the file
        if ($data !== null) {
            file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }




    // public function edit(Request $request){
    //     if ($request->isMethod('post')) {
    //         $idToEdit = request()->input('idToEdit');
    //         $newTagValue = request()->input('newTagValue');
    //         $patternsToEdit = is_array(request()->input('patternsToEdit')) ? request()->input('patternsToEdit') : [request()->input('patternsToEdit')];
    //         $responsesToEdit = is_array(request()->input('responsesToEdit')) ? request()->input('responsesToEdit') : [request()->input('responsesToEdit')];

    //         $jsonPath = public_path('C:\xampp\htdocs\capstone-chatsupport\python\intents.json');
    //         $json_data = file_get_contents($jsonPath);
    //         $intents = json_decode($json_data, true);

    //         foreach ($intents['intents'] as &$intent) {
    //             if ($intent['id'] == $idToEdit) {
    //                 $intent['tag'] = $newTagValue;
    //                 $intent['patterns'] = $patternsToEdit;
    //                 $intent['responses'] = $responsesToEdit;
    //             }
    //         }

    //         file_put_contents($jsonPath, json_encode($intents, JSON_PRETTY_PRINT));
    //         return redirect()->back()->with('success', 'Intent edited successfully, click the train button to refresh the data.');
    //     }
    // }





}
