<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArchiveController extends Controller
{
    public function index()
    {
        $archive = DB::table('archive')->get();
        return view('archivePage', compact('archive')); 
    }
}
