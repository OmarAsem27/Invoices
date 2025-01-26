<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalAmount = Invoice::sum('amount_collection');
        $totalUnpaid = Invoice::where('value_status', 2)->sum('amount_collection');
        $totalPaid = Invoice::where('value_status', 1)->sum('amount_collection');
        $totalPartiallyPaid = Invoice::where('value_status', 3)->sum('amount_collection');
        return view('home');
    }
}
