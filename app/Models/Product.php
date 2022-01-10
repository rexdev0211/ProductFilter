<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'dev_product';

    protected $fillable = [
        'category',
        'name',
        'model',
        'sku',
        'url',
        'location',
        'ean',
        'jan',
        'mpn',
        'upc',
        'discount_price',
        'price',
        'stock_status',
        'manufacturer',
        'description',
        'attributes',
        'images',
        'date_parsing',
        'quantity',
        'status',
    ];

    const CREATED_AT = 'date_added';
    const UPDATED_AT = 'date_modified';
}
