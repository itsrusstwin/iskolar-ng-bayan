<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to an applicant's User account whenever their Applicant->status changes
 * (verification, MSWDO assessment, exam result, orientation, waste compliance,
 * payout, disqualification, or appeal resolution).
 */
class ApplicationStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        public string $statusLabel,
        public ?string $detail = null
    ) {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Iskolar ng Bayan — Application Update')
            ->greeting('Hello ' . ($notifiable->name ?? 'Scholar') . ',')
            ->line('There has been an update on your Iskolar ng Bayan scholarship application.')
            ->line('**New status:** ' . $this->statusLabel);

        if ($this->detail) {
            $mail->line($this->detail);
        }

        $mail->action('View My Application', url('/dashboard'))
            ->line('If you have questions about this update, please contact your scholarship administrator.');

        return $mail;
    }
}