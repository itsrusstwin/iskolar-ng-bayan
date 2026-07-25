<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
    'applicant_id',
    'requirement_id',
    'is_submitted',
    'file_path',
    'submitted_at',
    'approval_status',
];

    public function requirement() { return $this->belongsTo(Requirement::class); }
    public function applicant() { return $this->belongsTo(Applicant::class); }
}