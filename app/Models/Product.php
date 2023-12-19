<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model{
    public $timestamps  = false;


    protected $table = 'product';
    protected $primaryKey = 'id_product';

    protected $fillable = [
        'name',
        'description',
        'price',
        'image_url',
        'stock',
    ];

    public function user() {
        return $this->belongsTo('App\Models\User','owner_id', 'id');
    }


}

