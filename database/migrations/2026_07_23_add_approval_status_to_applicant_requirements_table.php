<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicant_requirements', function (Blueprint $table) {
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])
                  ->default('pending')
                  ->after('is_submitted');
        });
    }

    public function down(): void
    {
        Schema::table('applicant_requirements', function (Blueprint $table) {
            $table->dropColumn('approval_status');
        });
    }
};