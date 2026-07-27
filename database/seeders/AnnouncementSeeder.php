<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;

class AnnouncementSeeder extends Seeder
{
    /**
     * Seeds the two announcements that used to be hardcoded on the home page
     * (Scholarship Requirements + Submission Period), so admins can now edit
     * or delete them from Admin > Announcements like any other post.
     *
     * Uses updateOrCreate on the title so re-running this seeder won't
     * create duplicates.
     */
    public function run(): void
    {
        Announcement::updateOrCreate(
            ['title' => 'Scholarship Requirements'],
            [
                'body' => "Please prepare the following requirements:\n"
                    . "- Certified True Copy of Grades\n"
                    . "- Photocopy PSA Birth Certificate\n"
                    . "- Photocopy Latest School ID\n"
                    . "- Long Brown Envelope",
                'is_published' => true,
            ]
        );

        Announcement::updateOrCreate(
            ['title' => 'Period for the Submission of Requirements'],
            [
                'body' => "July 20, 2026 – November 20, 2026",
                'is_published' => true,
            ]
        );
    }
}