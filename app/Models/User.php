<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }
    protected $casts = [
        'pos_machine_id' => 'array',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    public function license()
    {
        return $this->hasOne(License::class, 'user_id', 'id');
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'company_id'); // or 'user_id'
    }

    public function subscriptionprice()
    {
        return $this->hasMany(Subscription::class, 'company_id', 'id');
    }

    public function fares()
    {
        return $this->hasMany(Fare_metrix::class, 'user_id', 'id');
    }

    public function setsubscriptionprice()
    {
        return $this->hasOne(Subscription::class, 'company_id', 'id');
    }

    public function devices()
    {
        return $this->hasMany(PosMachine::class, 'company_id');
    }

    public function posusers()
    {
        return $this->hasMany(PosUser::class, 'company_id');
    }

    public function header()
    {
        return $this->hasOne(Header::class, 'user_id', 'id');
    }

    public function footer()
    {
        return $this->hasOne(Footer::class, 'user_id', 'id');
    }

    public function gstInfo()
    {
        return $this->hasOne(GST_INFO::class, 'user_id', 'id');
    }

    public function accountInfo()
    {
        return $this->hasOne(Account_info::class, 'user_id', 'id');
    }

}
