<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id', 'title', 'description', 'video_link', 'cover_image', 'duration',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}

