<?php

namespace App\Http\Controllers;

use App\Models\archive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArchiveController extends Controller
{
    public function index()
    {
        $archive = DB::table('archive')->get();
        return view('archivePage', compact('archive')); 
    }

    public function restoreIntent(Request $request)
    {
        if ($request->isMethod('post')) {
            $tag = $request->input('tag');

            $archive = archive::where('tag', $tag)->first();

            if (!$archive) {
                return response()->json(['error' => 'Archived intent not found'], 404);
            }

            $patterns = json_decode($archive->patterns, true);
            $responses = json_decode($archive->responses, true);

            $json_file = 'C:\xampp\htdocs\capstone-chatsupport\python\intents.json';
            $json_data = file_get_contents($json_file);
            $intents = json_decode($json_data, true);

            $intents['intents'][] = [
                'tag' => $tag,
                'patterns' => $patterns,
                'responses' => $responses
            ];

            file_put_contents($json_file, json_encode($intents, JSON_PRETTY_PRINT));

            $archive->delete();

            return response()->json(['message' => 'Intent restored successfully']);
        } else {
            return response()->json(['error' => 'Invalid request method'], 405);
        }
    }


    public function deleteArchive(Request $request) {
        $tag = $request->input('tag');
    
        archive::where('tag', $tag)->delete();
        
        return response()->json(['message' => 'Archived intent deleted successfully']);
    }


}
