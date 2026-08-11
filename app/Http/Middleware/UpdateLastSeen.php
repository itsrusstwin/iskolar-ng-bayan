<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/**
 * Keeps track of when the authenticated user was last active so the UI
 * can show an online/offline green dot. Throttled to avoid a DB write on
 * every single request.
 */
class UpdateLastSeen
{
    /** Only persist a new timestamp at most once per minute per user. */
    private const THROTTLE_SECONDS = 60;

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user !== null && $user->getKey() !== null) {
            $this->touch($user);
        }

        return $next($request);
    }

    private function touch($user): void
    {
        $lastSeen = $user->last_seen_at;

        if ($lastSeen !== null && (int) $lastSeen->diffInSeconds(now()) < self::THROTTLE_SECONDS) {
            return;
        }

        DB::table('users')
            ->where('id', $user->getKey())
            ->update(['last_seen_at' => now()]);
    }
}