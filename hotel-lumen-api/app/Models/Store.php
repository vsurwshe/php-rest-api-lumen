<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'store_product_name', 
        'store_product_qty', 
        'store_product_total_price',
        'user_id'
    ];

    public function user(){
       return $this->belongsTo('App\Models\User');
    }
}
