<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'bill_no',
        'vehicle_no',
        'vehicle_type',
        'duration_type',
        'in_time',
        'out_time',
        'date',
        'amount',
        'serial_number',
        'name',
        'company_id',
        'pos_user_id',
        'vehicle_id',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id'); // foreign key
    }
}
