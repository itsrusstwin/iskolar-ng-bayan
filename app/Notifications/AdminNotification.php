<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * In-app notification sent to admin User accounts whenever a student
 * performs an action that needs attention (files an appeal, uploads a
 * requirement, updates their profile, etc.).
 */
class AdminNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $body,
        public ?string $url = null,
        public ?string $studentName = null,
    ) {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'url' => $this->url,
            'student_name' => $this->studentName,
        ];
    }

    /**
     * Notify every admin account in the system.
     */
    public static function sendToAdmins(AdminNotification $notification): void
    {
        User::where('role', 'admin')->get()->each(fn (User $admin) => $admin->notify($notification));
    }
}
