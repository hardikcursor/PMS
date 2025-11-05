<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    public function company()
{
    return $this->belongsTo(User::class, 'company_id'); // or 'user_id' if your column name is user_id
}
}
