<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable =[

        "name", "code", "type", "barcode_symbology", "brand_id", "category_id", "unit", "cost", "price", "qty", "alert_quantity", "promotion", "promotion_price","starting_date", "last_date", "tax_id", "tax_method", "image", "file", "featured", "product_details", "is_active"
    ];
}
