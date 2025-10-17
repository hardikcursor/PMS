<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosMachine extends Model
{
    protected $fillable = ['company_id', 'serial_number'];

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id', 'id');
    }

  
}
