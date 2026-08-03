<?php

namespace App\Http\Controllers;

use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /**
     * Student view — their conversation thread with the admin.
     */
    public function index()
    {
        $user = Auth::user();
        $messages = SupportMessage::with('sender')
            ->where('user_id', $user->id)
            ->orderBy('created_at')
            ->get();

        return view('support.index', compact('messages'));
    }

    /**
     * Student sends a new message to the admin.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $user = Auth::user();

        SupportMessage::create([
            'user_id' => $user->id,
            'sender_id' => $user->id,
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        return redirect()
            ->route('support.index')
            ->with('success', 'Message sent to the administrator. You will receive a reply soon.');
    }

    /**
     * Admin view — inbox of all student conversations.
     */
    public function inbox()
    {
        $threads = SupportMessage::with(['user', 'sender'])
            ->latest()
            ->get()
            ->groupBy('user_id')
            ->map(function ($messages, $userId) {
                /** @var \Illuminate\Support\Collection<int, SupportMessage> $messages */
                $last = $messages->first();
                $student = $last->user;
                $unread = $messages
                    ->filter(fn (SupportMessage $m) => $m->sender_id === $last->user_id && !$m->is_read)
                    ->count();

                return (object) [
                    'user' => $student,
                    'last_message' => $last->message,
                    'last_time' => $last->created_at,
                    'unread' => $unread,
                ];
            })
            ->sortByDesc(fn ($thread) => $thread->last_time)
            ->values();

        return view('admin.support.inbox', compact('threads'));
    }

    /**
     * Admin view — single conversation with a student.
     */
    public function show(User $user)
    {
        // Only show conversations for applicant-role users
        abort_unless($user->role === 'applicant', 404);

        $messages = SupportMessage::with('sender')
            ->where('user_id', $user->id)
            ->orderBy('created_at')
            ->get();

        // Mark student messages as read now that the admin opened the thread
        SupportMessage::where('user_id', $user->id)
            ->where('sender_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('admin.support.show', compact('user', 'messages'));
    }

    /**
     * Admin replies to a student conversation.
     */
    public function reply(Request $request, User $user)
    {
        abort_unless($user->role === 'applicant', 404);

        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        SupportMessage::create([
            'user_id' => $user->id,
            'sender_id' => Auth::id(),
            'message' => $validated['message'],
            'is_read' => true,
        ]);

        return redirect()
            ->route('admin.support.show', $user)
            ->with('success', 'Reply sent to ' . $user->name . '.');
    }
}
