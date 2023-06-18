<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormationCourse extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
