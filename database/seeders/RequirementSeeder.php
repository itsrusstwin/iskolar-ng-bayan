<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Requirement;

class RequirementSeeder extends Seeder
{
    public function run(): void
    {
        $requirements = [
            'Certified True Copy of Grades (First Semester)',
            'Photocopy PSA Birth Certificate',
            'Photocopy Latest School ID',
            
        ];

        foreach ($requirements as $name) {
            Requirement::create(['name' => $name]);
        }
    }
}