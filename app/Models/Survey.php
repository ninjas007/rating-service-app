<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function details()
    {
        return $this->hasMany(SurveyDetail::class);
    }
}
