<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailCarts extends Model
{
    use HasFactory;

    protected $table = 'detail_carts';

    protected $fillable = ['cart_id', 'product_id', 'amount', 'price'];

    public function cart()
    {
        return $this->belongsTo(Carts::class, 'cart_id');
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
