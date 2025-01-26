<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceReportController extends Controller
{
    public function index()
    {
        return view('reports.invoice-report');
    }

    public function generateReport(Request $request)
    {
        // dd($request->all());
        $radio_btn = $request->radio_btn;

        // في حالة البحث بنوع الفاتورة
        if ($radio_btn == 1) {
            // في حالة عدم تحديد تاريخ
            if ($request->type && $request->start_at == '' && $request->end_at == '') {

                $invoices = Invoice::select('*')->where('status', '=', $request->type)->get();
                $type = $request->type;
                return view('reports.invoice-report', compact('type'))->withDetails($invoices);
            }
            // في حالة تحديد تاريخ استحقاق
            else {
                $start_at = date($request->start_at);
                $end_at = date($request->end_at);
                $type = $request->type;

                $invoices = Invoice::whereBetween('invoice_Date', [$start_at, $end_at])->where('status', '=', $request->type)->get();
                return view('reports.invoice-report', compact('type', 'start_at', 'end_at'))->withDetails($invoices);
            }
        }
        // في البحث برقم الفاتورة
        else {
            $invoices = Invoice::select('*')->where('invoice_number', '=', $request->invoice_number)->get();
            return view('reports.invoice-report')->withDetails($invoices);
        }
    }
}


