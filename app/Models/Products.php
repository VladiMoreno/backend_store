<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = ['name', 'price', 'barcode', 'barcode_image_path'];

    public function detailCarts()
    {
        return $this->hasMany(DetailCarts::class);
    }
}
