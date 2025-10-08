<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Header extends Model
{
    protected $fillable = ['user_id', 'header1', 'header2', 'header3', 'header4'];
}
