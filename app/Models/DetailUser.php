<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'gender',
        'birth',
        'phone',
        'address',
        'photo',
        'job',
        'work_address',
        'practice_place_address',
        'office_phone_number',
        'is_verified',
        'benefits',
        'price',
        'credit_card_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function diary()
    {
        return $this->hasMany(Diary::class);
    }

    public function education()
    {
        return $this->hasMany(Education::class);
    }

    public function course()
    {
        return $this->hasMany(Course::class);
    }

    
}
