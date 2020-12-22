<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockCount extends Model
{
    protected $fillable =[
        "reference_no", "store_id", "brand_id", "category_id", "user_id", "type", "initial_file", "final_file", "note", "is_adjusted"
    ];
}
