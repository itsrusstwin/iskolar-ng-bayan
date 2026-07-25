<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $announcements = \App\Models\Announcement::where('is_published', true)->latest()->get();
        return view('home', compact('announcements'));
    }

    public function about()
    {
        return view('about');
    }

    public function guides()
    {
        return view('guides');
    }
}