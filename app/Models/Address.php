<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
     protected $fillable = ['lat', 'lng', 'formatted_address', 'raw_json'];

        protected $casts = [
        'raw_json' => 'array',
    ];
}
