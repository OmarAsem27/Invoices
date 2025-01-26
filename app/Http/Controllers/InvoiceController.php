<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Models\Invoice;
use App\Models\Invoice_attachments;
use App\Models\Invoices_details;
use App\Models\Product;
use App\Models\Section;
use App\Notifications\InvoiceCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::all();
        return view('invoices.add_invoice', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'product' => $request->product,
            'section_id' => $request->section,
            'amount_collection' => $request->amount_collection,
            'amount_commission' => $request->amount_commission,
            'discount' => $request->discount,
            'value_VAT' => $request->value_VAT,
            'rate_VAT' => $request->rate_VAT,
            'total' => $request->total,
            'status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' => $request->note,
        ]);

        $invoice_id = Invoice::latest()->first()->id;

        $test = Invoices_details::create([
            'id_invoice' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'section' => $request->section,
            'status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' => $request->note,
            'user' => Auth::user()->name,
        ]);

        if ($request->hasFile('pic')) {

            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new Invoice_attachments();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move picture
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        }

        // event to send mail
        $user = Auth::user();
        $invoice = Invoice::latest()->first();
        $user->notify(new InvoiceCreatedNotification($invoice));
        session()->flash('Add', 'تم إضافة الفاتورة بنجاح');
        return redirect('/invoices');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoice = Invoice::where('id', $id)->first();
        return view('invoices.status-update', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoice = Invoice::where('id', $id)->first();
        $sections = Section::all();
        return view('invoices.edit_invoice', compact('invoice', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $invoice = Invoice::findOrFail($request->invoice_id);
        $invoice->update([
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'product' => $request->product,
            'section_id' => $request->section,
            'amount_collection' => $request->amount_collection,
            'amount_commission' => $request->amount_commission,
            'discount' => $request->discount,
            'value_VAT' => $request->value_VAT,
            'rate_VAT' => $request->rate_VAT,
            'total' => $request->total,
            'note' => $request->note,
        ]);

        session()->flash('Edit', 'تم تعديل الفاتورة بنجاح');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoice = Invoice::where('id', $id)->first();
        $attachments = Invoice_attachments::where('invoice_id', $id)->first();
        $id_page = $request->id_page;


        if (!$id_page == 2) {

            if (!empty($attachments->invoice_number)) {
                Storage::disk('invoice_attachments')->deleteDirectory($attachments->invoice_number);
            }
            $invoice->forceDelete();
            session()->flash('Delete', 'تم حذف الفاتورة بنجاح');
            return redirect('/invoices');

        } else {
            $invoice->delete();
            session()->flash('archive_invoice');
            return redirect('/archived-invoices');
        }
    }

    public function getProducts($id)
    {
        $products = Product::where('section_id', $id)->pluck('product_name', 'id');
        return json_encode($products);
    }

    public function changeStatus(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        if ($request->status === 'مدفوعة') {
            $invoice->update([
                'value_status' => 1,
                'status' => $request->status,
                'payment_date' => $request->payment_date,
            ]);
            Invoices_details::create([
                'id_invoice' => $request->id_Invoice,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->section,
                'status' => $request->status,
                'value_status' => 1,
                'note' => $request->note,
                'payment_date' => $request->payment_date,
                'user' => Auth::user()->name,
            ]);
        } else {
            $invoice->update([
                'value_status' => 3,
                'status' => $request->status,
                'payment_date' => $request->payment_date,
            ]);
            Invoices_details::create([
                'id_invoice' => $request->id_Invoice,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->section,
                'status' => $request->status,
                'value_status' => 3,
                'note' => $request->note,
                'payment_date' => $request->payment_date,
                'user' => Auth::user()->name,
            ]);
        }
        session()->flash('status_updated', 'تم تحديث حالة الدفع بنجاح');
        return redirect('/invoices');
    }

    public function paidInvoices()
    {
        $invoices = Invoice::where('value_status', 1)->get();
        return view('invoices.paid-invoices', compact('invoices'));
    }

    public function unpaidInvoices()
    {
        $invoices = Invoice::where('value_status', 2)->get();
        return view('invoices.unpaid-invoices', compact('invoices'));
    }

    public function partiallyPaidInvoices()
    {
        $invoices = Invoice::where('value_status', 3)->get();
        return view('invoices.partially-paid-invoices', compact('invoices'));
    }

    public function printInvoice(Request $request, $id)
    {
        $invoice = Invoice::where('id', $id)->first();
        return view('invoices.print-invoice', compact('invoice'));
    }

    public function export()
    {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }

}
