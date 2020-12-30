<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelTable extends Model
{
    protected $fillable = [
        'table_name', 'table_customer_size', 'table_direction', 'table_booked', 'user_id'
    ];

    public function user(){
       return $this->belongsTo('App\Models\Users');
    }
}
