<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Reminder;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function index()
    {
        $reminders = Reminder::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.reminders.index', compact('reminders'));
    }

    public function create()
    {
        return view('admin.reminders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'body' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $reminder = Reminder::create([
            'body' => $validated['body'],
            'is_active' => $request->boolean('is_active'),
            'sort_order' => Reminder::max('sort_order') + 1,
        ]);

        AuditLog::record('reminder_created', 'A new important reminder was added.', subject: $reminder);

        return redirect()->route('admin.reminders.index')->with('success', 'Reminder added.');
    }

    public function edit(Reminder $reminder)
    {
        return view('admin.reminders.edit', compact('reminder'));
    }

    public function update(Request $request, Reminder $reminder)
    {
        $validated = $request->validate([
            'body' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $reminder->update([
            'body' => $validated['body'],
            'is_active' => $request->boolean('is_active'),
        ]);

        AuditLog::record('reminder_updated', 'An important reminder was updated.', subject: $reminder);

        return redirect()->route('admin.reminders.index')->with('success', 'Reminder updated.');
    }

    public function destroy(Reminder $reminder)
    {
        $reminder->delete();

        AuditLog::record('reminder_deleted', 'An important reminder was deleted.', subject: $reminder);

        return redirect()->route('admin.reminders.index')->with('success', 'Reminder deleted.');
    }

    public function toggle(Reminder $reminder)
    {
        $reminder->update(['is_active' => ! $reminder->is_active]);

        AuditLog::record(
            'reminder_toggled',
            ($reminder->is_active ? 'An important reminder was made visible.' : 'An important reminder was hidden.'),
            subject: $reminder
        );

        return back()->with('success', $reminder->is_active ? 'Reminder is now visible to students.' : 'Reminder is now hidden.');
    }

    public function moveUp(Reminder $reminder)
    {
        $previous = Reminder::where('sort_order', '<', $reminder->sort_order)
            ->orderByDesc('sort_order')
            ->first();

        if ($previous) {
            $this->swapOrder($reminder, $previous);
        }

        return back();
    }

    public function moveDown(Reminder $reminder)
    {
        $next = Reminder::where('sort_order', '>', $reminder->sort_order)
            ->orderBy('sort_order')
            ->first();

        if ($next) {
            $this->swapOrder($reminder, $next);
        }

        return back();
    }

    private function swapOrder(Reminder $a, Reminder $b): void
    {
        $temp = $a->sort_order;
        $a->update(['sort_order' => $b->sort_order]);
        $b->update(['sort_order' => $temp]);
    }
}