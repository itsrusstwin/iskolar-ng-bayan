<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\SiteContent;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index()
    {
        $content = SiteContent::allContent();
        $steps = json_decode($content['guides_steps'] ?? '[]', true) ?: [];

        return view('admin.content.index', compact('content', 'steps'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'about_hero_badge' => 'required|string|max:100',
            'about_hero_title' => 'required|string|max:200',
            'about_hero_subtitle' => 'required|string',
            'about_mission_title' => 'required|string|max:200',
            'about_mission' => 'required|string',
            'about_partner_label' => 'required|string|max:200',
            'guides_hero_badge' => 'required|string|max:100',
            'guides_hero_title' => 'required|string|max:200',
            'guides_hero_subtitle' => 'required|string',
            'steps_en' => 'array',
            'steps_en.*' => 'required|string',
            'steps_fil' => 'array',
            'steps_fil.*' => 'nullable|string',
        ]);

        $fields = [
            'about_hero_badge' => $validated['about_hero_badge'],
            'about_hero_title' => $validated['about_hero_title'],
            'about_hero_subtitle' => $validated['about_hero_subtitle'],
            'about_mission_title' => $validated['about_mission_title'],
            'about_mission' => $validated['about_mission'],
            'about_partner_label' => $validated['about_partner_label'],
            'guides_hero_badge' => $validated['guides_hero_badge'],
            'guides_hero_title' => $validated['guides_hero_title'],
            'guides_hero_subtitle' => $validated['guides_hero_subtitle'],
        ];

        foreach ($fields as $key => $value) {
            SiteContent::updateOrCreate(['key' => $key], ['content' => $value]);
        }

        $steps = [];
        foreach (($validated['steps_en'] ?? []) as $i => $en) {
            $fil = $validated['steps_fil'][$i] ?? '';
            if (trim((string) $en)) {
                $steps[] = ['en' => $en, 'fil' => trim((string) $fil)];
            }
        }
        SiteContent::updateOrCreate(['key' => 'guides_steps'], ['content' => json_encode($steps)]);

        AuditLog::record('content_updated', 'Page content (About Us / Guides) was updated.');

        return back()->with('success', 'Page content updated successfully.');
    }
}
