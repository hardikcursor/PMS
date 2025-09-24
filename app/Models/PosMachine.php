<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosMachine extends Model
{
    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }
}
