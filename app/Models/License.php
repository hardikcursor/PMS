<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $fillable = ['user_id', 'license_key', 'license_validity'];
    public function company()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
