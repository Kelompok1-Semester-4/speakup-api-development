<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $fillable = [
        'detail_user_id',
        'level',
        'institution',
        'address',
        'major',
        'study_field',
        'graduation_year',
        'gpa',
        'file_url',
    ];
}
