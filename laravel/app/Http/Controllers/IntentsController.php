<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

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

    // public function edit(Request $request)
    // {
    //     $file = 'C:\xampp\htdocs\capstone-chatsupport\python\intents.json';
    //     $data = json_decode(file_get_contents('php://input'), true);

    //     // Write updated JSON data back to the file
    //     if ($data !== null) {
    //         file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    //         echo json_encode(['success' => true]);
    //     } else {
    //         echo json_encode(['success' => false]);
    //     }
    // }

    // public function editIntent(Request $request) {
    //     // Retrieve and validate the input data
    //     $tag = $request->input('editTag');
    //     $patterns = $request->input('editPatterns');
    //     $responses = $request->input('editResponses');
    
    //     // Read existing intents from the file or external API
    //     $path = storage_path('C:\xampp\htdocs\capstone-chatsupport\python\intents.json'); // Adjust the path as per your file location
    
    //     if (file_exists($path)) {
    //         // Read JSON file content
    //         $intentsJson = file_get_contents($path);
    //         $intents = json_decode($intentsJson, true);
    
    //         // Check if JSON decoding was successful
    //         if ($intents === null) {
    //             return redirect()->back()->with('error', 'Error decoding intents JSON.');
    //         }
    
    //         // Check if 'intents' key exists in the decoded JSON
    //         if (!array_key_exists('intents', $intents)) {
    //             return redirect()->back()->with('error', 'Intents data not found in JSON.');
    //         }
    
    //         // Find the intent to be edited
    //         $intentIndex = array_search($tag, array_column($intents['intents'], 'tag'));
    
    //         if ($intentIndex !== false) {
    //             // Update patterns and responses
    //             $intents['intents'][$intentIndex]['patterns'] = explode("\n", $patterns);
    //             $intents['intents'][$intentIndex]['responses'] = explode("\n", $responses);
    
    //             // Write updated intents back to the file
    //             file_put_contents($path, json_encode($intents));
    
    //             return redirect()->back()->with('success', 'Intent updated successfully.');
    //         } else {
    //             return redirect()->back()->with('error', 'Intent not found.');
    //         }
    //     } else {
    //         return redirect()->back()->with('error', 'Intents file not found.');
    //     }
    // }
    
    
    // public function editIntent(Request $request){
    //     if ($request->isMethod('post')) {
    //         $idToEdit = request()->input('idToEdit');
    //         $newTagValue = request()->input('newTagValue');
    //         $patternsToEdit = is_array(request()->input('patternsToEdit')) ? request()->input('patternsToEdit') : [request()->input('patternsToEdit')];
    //         $responsesToEdit = is_array(request()->input('responsesToEdit')) ? request()->input('responsesToEdit') : [request()->input('responsesToEdit')];

    //         $jsonPath = 'C:\xampp\htdocs\capstone-chatsupport\python\intents.json';
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

    public function editIntent(Request $request)
    {
        try {
            // Validate incoming request data
            $request->validate([
                'idToEdit' => 'required|integer',
                'newTagValue' => 'required|string',
                'patternsToEdit' => 'required|array',
                'responsesToEdit' => 'required|array',
            ]);

            // Retrieve form inputs from request
            $idToEdit = $request->input('idToEdit');
            $newTagValue = $request->input('newTagValue');
            $patternsToEdit = $request->input('patternsToEdit');
            $responsesToEdit = $request->input('responsesToEdit');

            $jsonPath = 'C:\\xampp\\htdocs\\capstone-chatsupport\\python\\intents.json';
            $json_data = file_get_contents($jsonPath);
            $intents = json_decode($json_data, true);

            // Update the intent with the specified ID
            foreach ($intents['intents'] as &$intent) {
                if ($intent['id'] == $idToEdit) {
                    $intent['tag'] = $newTagValue;
                    $intent['patterns'] = $patternsToEdit;
                    $intent['responses'] = $responsesToEdit;
                }
            }

            file_put_contents($jsonPath, json_encode($intents, JSON_PRETTY_PRINT));

            return redirect()->back()->with('success', 'Intent edited successfully');

        } catch (\Exception $e) {
            Log::error('Error editing intent: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to edit intent');
        }
    }





}
