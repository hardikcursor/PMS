<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'bill_no',
        'vehicle_no',
        'vehicle_id',
        'pos_user_id',
        'duration_type',
        'in_time',
        'out_time',
        'date',
        'amount',
        'name',
        'serial_number',
        'company_id',
    ];

      public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id'); // foreign key
    }
}
