<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'review';
    protected $primaryKey = 'id_review';
    public $timestamps  = false;

    protected $fillable = [
        'review_text', 'rating', 'id_customer', 'id_product'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_customer', 'id_account');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product', 'id_product');
    }
}