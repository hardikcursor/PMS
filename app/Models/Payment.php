<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
     protected $fillable = [
        'qr_id', 'payment_id', 'amount', 'status'
    ];
}
