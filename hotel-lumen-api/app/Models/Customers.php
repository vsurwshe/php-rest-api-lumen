<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    protected $fillable = [
        'customer_name', 
        'customer_card_number', 
        'customer_card_type', 
        'customer_email', 
        'customer_mobile_number',
        'customer_address',
        'user_id'
    ];

    protected $hidden = [
        'user_id'
    ];

    public function user(){
       return $this->belongsTo('App\Models\Users');
    }
}
