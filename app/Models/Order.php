<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'purchase';
    protected $primaryKey = 'id_purchase';
    public $timestamps  = false;

    protected $fillable = [
        'order_date', 'delivery_date', 'order_status', 'id_customer', 'id_address', 'id_payment_method', 'id_cart'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_customer', 'id_account');
    }

    // Se você tiver uma relação com produtos em cada pedido, pode adicionar algo assim:
    // public function products()
    // {
    //     return $this->belongsToMany(Product::class, 'order_product', 'id_purchase', 'id_product');
    // }
}