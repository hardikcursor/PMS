<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
     protected  $fillable = [
        'bill_no',
        'vehicle_no',
        'vehicle_type',
        'duration_type',
        'in_time',
        'out_time',
        'date',
        'amount',
        'pos_user_id',
        'serial_number',

     ];
}
