<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
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
        $totalCount = Invoice::count();
        $countUnpaid = Invoice::where('value_status', 2)->count();
        $countPaid = Invoice::where('value_status', 1)->count();
        $countPartiallyPaid = Invoice::where('value_status', 3)->count();


        if ($countUnpaid == 0) {
            $percentageOfInvoices2 = 0;
        } else {
            $percentageOfInvoices2 = $countUnpaid / $totalCount * 100;
        }

        if ($countPaid == 0) {
            $percentageOfInvoices1 = 0;
        } else {
            $percentageOfInvoices1 = $countPaid / $totalCount * 100;
        }

        if ($countPartiallyPaid == 0) {
            $percentageOfInvoices3 = 0;
        } else {
            $percentageOfInvoices3 = $countPartiallyPaid / $totalCount * 100;
        }


        $chart = Chartjs::build()
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 350, 'height' => 150])
            ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة', 'الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    "label" => "الفواتير الغير المدفوعة",
                    'backgroundColor' => ['#ec5858'],
                    'data' => [$percentageOfInvoices2]
                ],
                [
                    "label" => "الفواتير المدفوعة",
                    'backgroundColor' => ['#81b214'],
                    'data' => [$percentageOfInvoices1]
                ],
                [
                    "label" => "الفواتير المدفوعة جزئيا",
                    'backgroundColor' => ['#ff9642'],
                    'data' => [$percentageOfInvoices3]
                ],
            ])
            ->options([
                "scales" => [
                    "y" => [
                        "beginAtZero" => true
                    ]
                ]
            ]);

        return view('home', compact('chart'));
    }
}
