<?php

namespace App\Http\Controllers;

use App\Models\SiteContent;

class HomeController extends Controller
{
    public function index()
    {
        $announcements = \App\Models\Announcement::where('is_published', true)->latest()->get();
        return view('home', compact('announcements'));
    }

    public function about()
    {
        $content = SiteContent::allContent();
        return view('about', compact('content'));
    }

    public function guides()
    {
        $content = SiteContent::allContent();
        return view('guides', compact('content'));
    }
}