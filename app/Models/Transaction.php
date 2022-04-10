<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'detail_transaction_id', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detail_transaction()
    {
        return $this->belongsTo(DetailTransaction::class);
    }
}
