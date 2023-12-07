<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    protected $table = 'account';
    use Notifiable;


    protected $primaryKey = 'id_account';
    public $timestamps  = false;

    public function isAdmin()
    {
        return Admin::where('id_admin', $this->id_account)->exists();
    }
    protected $fillable = [
        'username', 'email', 'password', 'phone', 'is_banned', 'profile_pic'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id_customer', 'id_account');
    }
    public function admin()
    {
        return $this->hasOne(Admin::class, 'id_admin', 'id_account');
    }
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'id_customer', 'id_account');
    }
    
    
}
