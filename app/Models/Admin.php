<?php

namespace App\Models;

//criei um middleware "isadmin" e alterei o kernel para reconhecer a class

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Admin extends Model
{
    protected $table = 'admin'; 
    public $timestamps = false; 

    protected $primaryKey = 'id_admin'; 
    public $incrementing = false; 
    protected $keyType = 'int'; 

    public function user()
    {
        return $this->belongsTo(User::class, 'id_admin', 'id_account');
    }
}
