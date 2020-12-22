<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductReturn extends Model
{
    protected $table = 'product_returns';
    protected $fillable =[
        "return_id", "product_id", "qty", "unit", "net_unit_price", "discount", "tax_rate", "tax", "total"
    ];
}
