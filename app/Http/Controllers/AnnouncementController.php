<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->get();
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'body' => 'required|string',
            'is_published' => 'nullable|boolean',
        ]);

        Announcement::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement created.');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'body' => 'required|string',
            'is_published' => 'nullable|boolean',
        ]);

        $announcement->update([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('admin.announcements.index')->with('success', 'Announcement deleted.');
    }
}