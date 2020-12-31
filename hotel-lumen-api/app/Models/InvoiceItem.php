<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_item_name', 'invoice_item_price', 'invoice_item_total_price', 'invoice_item_qty', 'invoice_id'
    ];

    public function user(){
       return $this->belongsTo('App\Models\Invoice')->withTimestamps();
    }
}
