<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Schema;

class InvoicesExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // return Invoice::select([
        //     'invoice_number',
        //     'invoice_date',
        //     'due_date',
        //     'product',
        //     'amount_collection',
        //     'amount_commission',
        //     'discount',
        //     'value_VAT',
        //     'rate_VAT',
        //     'total',
        //     'status',
        //     'note',
        //     'payment_date'
        // ])->get();

        return Invoice::all();
    }

    // column names
    public function headings(): array
    {
        // return [
        //     'invoice_number',
        //     'invoice_date',
        //     'due_date',
        //     'product',
        //     'amount_collection',
        //     'amount_commission',
        //     'discount',
        //     'value_VAT',
        //     'rate_VAT',
        //     'total',
        //     'status',
        //     'note',
        //     'payment_date'
        // ];
        return $this->getTableColumns('invoices');
    }

    public function getTableColumns($table)
    {
        return Schema::getColumnListing($table);
    }


}
