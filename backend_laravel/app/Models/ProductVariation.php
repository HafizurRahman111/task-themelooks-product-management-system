<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variation_attributes',
        'purchase_price',
        'selling_price'
    ];

    protected $casts = [
        'variation_attributes' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(ProductDetail::class, 'product_id');
    }

}
