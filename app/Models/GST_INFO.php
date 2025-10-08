<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GST_INFO extends Model
{
    protected $fillable = ['user_id', 'gst_number', 'c_gst', 's_gst'];
}
