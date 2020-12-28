<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentWithDebitCard extends Model
{
    protected $table = 'payment_with_debit_card';

    protected $fillable = [
        "payment_id", "customer_id", "bank_name", "bank_number"
    ];
}
