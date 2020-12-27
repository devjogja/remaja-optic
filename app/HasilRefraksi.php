<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HasilRefraksi extends Model
{
    protected $table = 'hasil_refraksi';
    protected $fillable = [
        "payment_id", "sphr", "cylr", "axisr", "addr", "sphl", "cyll", "axisl", "addl", "pdd"
    ];
}
