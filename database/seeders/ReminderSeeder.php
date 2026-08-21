<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reminder;

class ReminderSeeder extends Seeder
{
    /**
     * Seeds the reminders that used to be hardcoded on the student dashboard,
     * so admins can now edit, reorder, hide, or delete them from
     * Admin > Important Reminders.
     */
    public function run(): void
    {
        $defaults = [
            'All applications must be submitted within the deadline.',
            'Only complete requirements will be processed.',
            'Applicant will undergo validation, exam, and orientation.',
            'Scholars must comply with academic and plastic waste submission requirements.',
            'Non-compliance may result in forfeiture of scholarship benefits.',
        ];

        foreach ($defaults as $index => $body) {
            Reminder::updateOrCreate(
                ['body' => $body],
                ['is_active' => true, 'sort_order' => $index + 1]
            );
        }
    }
}