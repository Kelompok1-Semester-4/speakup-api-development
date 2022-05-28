<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    public $table = 'quiz';
    protected $fillable = ['title', 'photo', 'description'];

    public function detailQuiz()
    {
        return $this->hasMany(DetailQuiz::class);
    }
}
