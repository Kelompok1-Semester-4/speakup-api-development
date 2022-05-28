<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailQuiz extends Model
{
    use HasFactory;

    public $table = 'detail_quiz';
    protected $fillable = ['quiz_id', 'title', 'question1', 'question2', 'question3', 'question4'];

    // RELATIONSHIPS
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
