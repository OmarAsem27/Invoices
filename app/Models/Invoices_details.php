<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoices_details extends Model
{
    protected $fillable = [
        'id_invoice',
        'invoice_number',
        'product',
        'section',
        'status',
        'value_status',
        'note',
        'user',
        'payment_date',
    ];
}
