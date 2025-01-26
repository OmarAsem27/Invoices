<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Section;
use Illuminate\Http\Request;

class CustomerReportController extends Controller
{
    public function index()
    {
        $sections = Section::all();
        return view('reports.customer-report', compact('sections'));
    }


    public function generateReport(Request $request)
    {
        if ($request->section && $request->product && $request->start_at == '' && $request->end_at == '') {
            $details = Invoice::where('section_id', $request->section)
                ->where('product', $request->product)
                ->get();

            $sections = Section::all();
            return view('reports.customer-report', compact('sections', 'details'));

        } else {

            $start_at = date($request->start_at);
            $end_at = date($request->end_at);

            $details = Invoice::whereBetween('invoice_date', [$start_at, $end_at])
                ->where('section_id', $request->section)
                ->where('product', $request->product)
                ->get();

            $sections = Section::all();
            return view('reports.customer-report', compact('sections', 'details'));
        }

    }
}
