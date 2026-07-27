<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MswdoAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_id',
        'referral_slip_no',
        'social_case_study_report_path',
        'is_qualified',
        'assessed_at',
    ];

    protected $casts = [
        'assessed_at' => 'datetime',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}