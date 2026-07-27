<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'disqualification_id',
        'filed_at',
        'reconsideration_notes',
        'result',
    ];

    protected $casts = [
        'filed_at' => 'datetime',
    ];

    public function disqualification()
    {
        return $this->belongsTo(Disqualification::class);
    }
}