<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderFood extends Model
{
    protected $fillable = [
        'order_food_name', 'order_food_unit_price', 'order_food_qty', 'order_food_total_price', 'booked_tabel_id'
    ];

    public function user(){
       return $this->belongsTo('App\Models\BookedTabel');
    }
}
