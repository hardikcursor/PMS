<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account_info extends Model
{
     protected $fillable = [
        'user_id', 
        'pan_number', 
        'cin_number'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
