<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $fillable = [
        'food_name', 'food_price', 'user_id'
    ];

    public function user(){
       return $this->belongsTo('App\Models\Users');
    }
}
