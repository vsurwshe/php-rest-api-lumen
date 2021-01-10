<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomBooking extends Model
{
    public $table = 'room_booking';

    protected $fillable = [
        'check_in_date', 
        'check_out_date', 
        'room_booking_customer_size', 
        'room_booking_subtotal', 
        'room_booking_gst',
        'room_booking_total',
        'customer_id',
        'room_id',
        'user_id'
    ];

    protected $hidden = [
        'user_id'
    ];

    public function user(){
       return $this->belongsTo('App\Models\Users');
    }

    public function rooms(){
        return $this->belongsTo('App\Models\Rooms');
    }

    public function customers(){
        return $this->belongsTo('App\Models\Customers');
    }

}
