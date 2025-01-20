<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Invoice_attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArchivedInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::onlyTrashed()->get();
        return view('invoices.archived-invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $invoice = Invoice::withTrashed()
            ->where('id', $request->invoice_id)
            ->restore();
        session()->flash('restored_invoice');
        return redirect('/invoices');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $invoice = Invoice::withTrashed()
            ->where('id', $request->invoice_id)
            ->first();

        $attachments = Invoice_attachments::where('invoice_id', $request->invoice_id)->first();
        if (!empty($attachments->invoice_number)) {
            Storage::disk('invoice_attachments')->deleteDirectory($attachments->invoice_number);
        }

        $invoice->forceDelete();
        session()->flash('Delete', 'تم حذف الفاتورة بنجاح');
        return redirect('/invoices');
    }
}
