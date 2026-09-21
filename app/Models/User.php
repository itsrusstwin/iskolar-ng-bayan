<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'provider',
    'provider_id',
    'terms_accepted_at',
    'last_seen_at',
];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen_at' => 'datetime',
            'deleted_at' => 'datetime',
            'terms_accepted_at' => 'datetime',
        ];
    }

    /**
     * The applicant profile linked to this user (if role = applicant).
     */
    public function applicant()
    {
        return $this->hasOne(Applicant::class);
    }

    /**
     * Check if this user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Whether the user is currently online (active within the last 5 minutes).
     */
    public function isOnline(): bool
    {
        return $this->last_seen_at !== null
            && $this->last_seen_at->gt(now()->subMinutes(5));
    }

    /**
     * Check if this user has accepted the scholarship terms/guidelines.
     */
    public function hasAcceptedTerms(): bool
    {
        return $this->terms_accepted_at !== null;
    }

    /**
     * Mark the scholarship terms/guidelines as accepted (first login).
     */
    public function acceptTerms(): void
    {
        $this->update(['terms_accepted_at' => now()]);
    }
}