<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyDetail extends Model
{
    protected $fillable = [
        'survey_id',
        'question_id',
    ];

    use HasFactory;

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
