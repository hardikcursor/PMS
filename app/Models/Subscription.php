<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    public function company()
    {
        return $this->belongsTo(User::class, 'company_id', 'id');
    }

    public function license()
    {
        return $this->hasOne(License::class, 'user_id', 'id');
    }

    public function subcreatedcompany()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
