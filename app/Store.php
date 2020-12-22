<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable =[

        "name", "phone", "email", "address", "is_active"
    ];
}
