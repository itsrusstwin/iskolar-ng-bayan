<?php

namespace Database\Seeders;

use App\Models\SiteContent;
use Illuminate\Database\Seeder;

class SiteContentSeeder extends Seeder
{
    public function run(): void
    {
        foreach (SiteContent::defaults() as $key => $content) {
            SiteContent::updateOrCreate(['key' => $key], ['content' => $content]);
        }
    }
}
