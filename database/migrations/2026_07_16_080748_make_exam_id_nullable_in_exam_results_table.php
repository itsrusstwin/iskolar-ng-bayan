<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropForeign(['exam_id']);
            $table->foreignId('exam_id')->nullable()->change();
            $table->foreign('exam_id')->references('id')->on('exams')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropForeign(['exam_id']);
            $table->foreignId('exam_id')->nullable(false)->change();
            $table->foreign('exam_id')->references('id')->on('exams')->cascadeOnDelete();
        });
    }
};