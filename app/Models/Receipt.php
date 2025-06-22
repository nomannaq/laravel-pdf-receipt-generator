<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = ['subtotal', 'tax', 'discount', 'total'];

    public function items()
    {
        return $this->hasMany(ReceiptItem::class);
    }
}
