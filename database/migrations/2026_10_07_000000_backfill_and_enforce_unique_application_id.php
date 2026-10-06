<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * 1. Give every student account that has no Application ID a generated,
 *    numeric one (continuing after the highest existing number).
 * 2. Clear duplicate IDs (the oldest account keeps it) and regenerate them.
 * 3. Keep the "Created Accounts" list in sync.
 * 4. Make Application IDs unique from now on.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Step 2: duplicates -> keep the lowest user id, blank the rest.
        $duplicates = DB::table('users')
            ->select('application_id')
            ->whereNotNull('application_id')
            ->where('application_id', '!=', '')
            ->groupBy('application_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('application_id');

        foreach ($duplicates as $value) {
            $keep = DB::table('users')->where('application_id', $value)->orderBy('id')->value('id');
            DB::table('users')->where('application_id', $value)->where('id', '!=', $keep)
                ->update(['application_id' => null]);
        }

        // Treat empty strings as "no ID".
        DB::table('users')->where('application_id', '')->update(['application_id' => null]);

        // Step 1: backfill.
        $taken = DB::table('users')->whereNotNull('application_id')->pluck('application_id')
            ->flip()->all();

        $next = DB::table('users')->whereNotNull('application_id')->pluck('application_id')
            ->filter(fn ($v) => ctype_digit((string) $v))
            ->map(fn ($v) => (int) $v)
            ->max() ?? 0;

        $missing = DB::table('users')
            ->where('role', 'applicant')
            ->whereNull('application_id')
            ->orderBy('id')
            ->pluck('id');

        foreach ($missing as $userId) {
            do {
                $next++;
                $candidate = str_pad((string) $next, 5, '0', STR_PAD_LEFT);
            } while (isset($taken[$candidate]));

            $taken[$candidate] = true;
            DB::table('users')->where('id', $userId)->update(['application_id' => $candidate]);
        }

        // Step 3: sync the display list.
        DB::table('admin_created_accounts')
            ->whereNotNull('user_id')
            ->orderBy('id')
            ->get(['id', 'user_id'])
            ->each(function ($row) {
                $id = DB::table('users')->where('id', $row->user_id)->value('application_id');
                DB::table('admin_created_accounts')->where('id', $row->id)->update(['application_id' => $id]);
            });

        // Step 4: enforce uniqueness (many NULLs are still allowed, e.g. admins).
        Schema::table('users', function (Blueprint $table) {
            $table->unique('application_id', 'users_application_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_application_id_unique');
        });
    }
};
