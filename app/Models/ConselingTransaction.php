<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConselingTransaction extends Model
{
    use HasFactory;

    public $table = 'conseling_transaction';
    protected $fillable = [
        'user_id',
        'conselor_id',
        'price',
        'start_time',
        'end_time',
        'pay_status',
        'conseling_status',
        'conseling_date',
    ];

    public function user()
    {
        return $this->belongsTo(DetailUser::class, 'user_id');
    }

    public function conselor()
    {
        return $this->belongsTo(DetailUser::class, 'conselor_id');
    }
}
