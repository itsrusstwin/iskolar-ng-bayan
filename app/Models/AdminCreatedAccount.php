<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A display-only record of a student account created by an admin.
 *
 * Stores the temporary credentials (name, email, password) the admin
 * generated so they can be reviewed on the Create Account page. Deleting
 * one of these records only hides it from that list — it never deletes
 * the actual User/Applicant account.
 */
class AdminCreatedAccount extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_id',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
