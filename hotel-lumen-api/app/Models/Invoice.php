<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_table', 'invoice_sub_total', 'invoice_gst', 'invoice_total_price', 'user_id'
    ];

    public function user(){
       return $this->belongsTo('App\Models\Users');
    }
}
