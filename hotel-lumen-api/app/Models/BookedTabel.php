<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookedTabel extends Model
{
    protected $fillable = [
        'booked_tabel_name', 'booked_tabel_customer_size', 'table_id'
    ];

    public function user(){
       return $this->belongsTo('App\Models\HotelTable');
    }
}
