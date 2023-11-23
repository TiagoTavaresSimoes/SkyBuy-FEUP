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
    use Notifiable;

    protected $table = 'account'; // This should match your table name in PostgreSQL
    protected $primaryKey = 'id_account';
    public $timestamps  = false;


    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id_customer', 'id_account');
    }
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'id_customer', 'id_account');
    }
    
    
}
