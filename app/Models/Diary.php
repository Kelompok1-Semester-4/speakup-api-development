<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{
    use HasFactory;

    protected $fillable = [
        'detail_user_id',
        'title',
        'content',
        'duration_read',
        'file',
        'cover_image',
    ];

    public function detailUser()
    {
        return $this->belongsTo(DetailUser::class);
    }

    public function diaryType()
    {
        return $this->belongsTo(DiaryType::class);
    }
}
