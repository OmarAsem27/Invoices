<?php

namespace App\Http\Controllers;

use App\Models\Invoice_attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceAttachmentsController extends Controller
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
        $this->validate($request, [
            'file_name' => 'mimes:png,jpg,pdf,jpeg',
        ], [
            'file_name.mimes' => 'صيغة المرفق يجب ان تكون pdf,jpeg,png,jpg'
        ]);

        $file = $request->file('file_name');
        $fileName = $file->getClientOriginalName();

        $invoiceAttachment = new Invoice_attachments();
        $invoiceAttachment->file_name = $fileName;
        $invoiceAttachment->invoice_number = $request->invoice_number;
        $invoiceAttachment->invoice_id = $request->invoice_id;
        $invoiceAttachment->created_by = Auth::user()->name;
        $invoiceAttachment->save();

        // $file->store($invoiceAttachment->invoice_number, 'invoice_attachments'); // NBE0001/yTQyB3OncrnQfjVpiB4Vc4OcNTugzcsuhnXPfrJL.jpg
        $file->storeAs($invoiceAttachment->invoice_number, $file->getClientOriginalName(), 'invoice_attachments');
        session()->flash('Add', 'تم إضافة المرفق بنجاح');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice_attachments $invoice_attachments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice_attachments $invoice_attachments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice_attachments $invoice_attachments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice_attachments $invoice_attachments)
    {
        //
    }
}
