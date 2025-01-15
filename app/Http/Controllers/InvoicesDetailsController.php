<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Invoice_attachments;
use App\Models\Invoices_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoicesDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(Invoices_details $invoices_details)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoice = Invoice::where('id', $id)->first();
        $invoiceDetails = Invoices_details::where('id_Invoice', $id)->get();
        $attachments = Invoice_attachments::where('invoice_id', $id)->get();
        // dd($attachments);
        return view('invoices.invoice_details', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoices_details $invoices_details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoices_details $invoices_details)
    {
        //
    }

    public function open_file($invoice_number, $file_name)
    {
        $path = "{$invoice_number}/{$file_name}";
        if (!Storage::disk('invoice_attachments')->exists($path)) {
            abort(404);
        }

        return response()->file(
            Storage::disk('invoice_attachments')->path($path)
        );
    }


    public function download_file($invoice_number, $file_name)
    {
        $path = "{$invoice_number}/{$file_name}";
        if (!Storage::disk('invoice_attachments')->exists($path)) {
            abort(404);
        }

        return response()->download(
            Storage::disk('invoice_attachments')->path($path)
        );
    }
    public function delete_file(Request $request)
    {
        $attachment = Invoice_attachments::findOrFail($request->id_file);
        $attachment->delete();
        $path = "{$request->invoice_number}/{$request->file_name}";
        if (!Storage::disk('invoice_attachments')->exists($path)) {
            abort(404);
        }

        Storage::disk('invoice_attachments')->delete($path);
        session()->flash('Delete', 'تم حذف المرفق بنجاح');
        return back();
    }


}



