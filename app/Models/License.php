<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    
    public function company()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
