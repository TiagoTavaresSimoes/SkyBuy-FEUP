<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'id_customer';
    

    public $timestamps = true;
    

    protected $fillable = [
        'id_cart',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'id_account');
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class, 'id_customer', 'id_customer');
    }
}