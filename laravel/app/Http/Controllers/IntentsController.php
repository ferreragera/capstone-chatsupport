<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Archive;

class IntentsController extends Controller
{

    public function index()
    {
        $json_data = file_get_contents('C:\xampp\htdocs\capstone-chatsupport\python\intents.json');
        $data = json_decode($json_data, true);
        $paginated_intents = $data['intents'];

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
    


    public function editIntent(Request $request)
    {
        if ($request->isMethod('post')) {
            // Retrieve data from the request
            $newTagValue = $request->input('tag');
            $patternsToEdit = $request->input('patterns'); 
            $responsesToEdit = $request->input('responses');

            // Load the contents of the intents.json file
            $filePath = 'C:\xampp\htdocs\capstone-chatsupport\python\intents.json';
            $jsonContents = file_get_contents($filePath);

            // Decode the JSON contents into a PHP associative array
            $intents = json_decode($jsonContents, true);

            // Find the intent in the array by id and update its tag, patterns, and responses
            $updated = false;
            foreach ($intents['intents'] as &$intent) {
                if ($intent['tag'] == $newTagValue) {
                    $intent['patterns'] = $patternsToEdit;
                    $intent['responses'] = $responsesToEdit;
                    $updated = true;
                    break;
                }
            }

            if ($updated) {
                $updatedContents = json_encode($intents, JSON_PRETTY_PRINT);

                file_put_contents($filePath, $updatedContents);

                return response()->json(['message' => 'Intent updated successfully'], 200);
            } else {
                Log::error('Intent not found for ID: ');
                return redirect()->back()->with('error', 'Failed to update intent: Intent not found');
            }
        }
        return redirect()->back()->with('error', 'Invalid request');
    }

    

    public function archiveIntent(Request $request)
    {
        if ($request->isMethod('post')) {
            $tag = $request->input('tag');
            $patterns = $request->input('patterns');
            $responses = $request->input('responses');

            $archive = new Archive();
            $archive->tag = $tag;
            $archive->patterns = json_encode($patterns); 
            $archive->responses = json_encode($responses);
            $archive->save();

            $json_file = 'C:\xampp\htdocs\capstone-chatsupport\python\intents.json';
            $json_data = file_get_contents($json_file);
            $intents = json_decode($json_data, true);

            $index = array_search($tag, array_column($intents['intents'], 'tag'));

            if ($index !== false) {
                array_splice($intents['intents'], $index, 1);

                file_put_contents($json_file, json_encode($intents, JSON_PRETTY_PRINT));

                return response()->json(['message' => 'Intent archived successfully']);
            } else {
                return response()->json(['error' => 'Intent not found in JSON file'], 404);
            }
        } else {
            return response()->json(['error' => 'Invalid request method'], 405);
        }
    }

}
