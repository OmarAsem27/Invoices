<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoices_details extends Model
{
    protected $fillable = [
        'id_Invoice',
        'invoice_number',
        'product',
        'section',
        'status',
        'Value_Status',
        'note',
        'user',
        'payment_date',
    ];
}
