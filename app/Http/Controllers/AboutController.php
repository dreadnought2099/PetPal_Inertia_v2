<?php

namespace App\Http\Controllers;
use Inertia\Inertia;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index() {
        $jsonContent = file_get_contents(public_path('data/about.json'));
        $aboutData = json_decode($jsonContent, true);
    
        return Inertia::render('About/Index', [
            'aboutData' => $aboutData,
        ]);
    }
}
