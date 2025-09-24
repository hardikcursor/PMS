<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fare_metrix extends Model
{
      protected $fillable = [
        'user_id', 'vehicle_category_id', 'slot_id', 'rate',
    ];
    public function vehicleCategory()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_category_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function companyCategory()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function slot()
    {
        return $this->belongsTo(Slot::class, 'slot_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }




}
