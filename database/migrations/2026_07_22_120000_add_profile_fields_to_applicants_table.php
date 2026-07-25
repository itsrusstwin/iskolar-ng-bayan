<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('applicants', function (Blueprint $table) {
        $table->string('middle_name', 100)->nullable()->after('last_name');
        $table->date('date_of_birth')->nullable()->after('contact_number');
        $table->enum('sex', ['Male', 'Female'])->nullable()->after('date_of_birth');
        $table->string('landmark', 150)->nullable()->after('barangay');
        $table->string('sitio', 100)->nullable()->after('landmark');
        $table->string('father_name', 150)->nullable()->after('sitio');
        $table->string('mother_maiden_name', 150)->nullable()->after('father_name');
    });

    // Step 1: widen the enum temporarily so both old and new values are valid
    DB::statement("ALTER TABLE applicants MODIFY program_type ENUM('current','aspiring','new','renewal') NOT NULL DEFAULT 'new'");

    // Step 2: remap any existing old values to the new vocabulary
    // (current scholars reapplying -> renewal, aspiring/new applicants -> new)
    DB::table('applicants')->where('program_type', 'current')->update(['program_type' => 'renewal']);
    DB::table('applicants')->where('program_type', 'aspiring')->update(['program_type' => 'new']);

    // Step 3: now that no row holds the old values, narrow the enum for real
    DB::statement("ALTER TABLE applicants MODIFY program_type ENUM('new','renewal') NOT NULL DEFAULT 'new'");
}

    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn([
                'middle_name',
                'date_of_birth',
                'sex',
                'landmark',
                'sitio',
                'father_name',
                'mother_maiden_name',
            ]);
        });

        DB::statement("ALTER TABLE applicants MODIFY program_type ENUM('current','aspiring') NOT NULL");
    }
};