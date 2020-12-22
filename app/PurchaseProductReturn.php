<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseProductReturn extends Model
{
    protected $table = 'purchase_product_return';
    protected $fillable =[
        "return_id", "product_id", "qty", "unit", "net_unit_cost", "discount", "tax_rate", "tax", "total"
    ];
}
