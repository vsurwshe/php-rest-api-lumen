<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    protected $fillable = [
        'room_number', 
        'room_locations', 
        'room_type', 
        'room_booking_status', 
        'room_rate',
        'user_id'
    ];

    protected $hidden = [
        'user_id'
    ];

    public function user(){
       return $this->belongsTo('App\Models\Users');
    }
}
