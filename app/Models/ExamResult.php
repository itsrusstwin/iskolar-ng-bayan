<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_id',
        'exam_id',
        'score',
        'passed',
        'file_path',
        'posted_at',
    ];

    protected $casts = [
        'posted_at' => 'datetime',
    ];

    public function applicant() { return $this->belongsTo(Applicant::class); }
    public function exam() { return $this->belongsTo(Exam::class); }
}