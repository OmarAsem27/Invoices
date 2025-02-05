<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $countPaid = Invoice::where('value_status', 1)->count();
        $countUnpaid = Invoice::where('value_status', 2)->count();
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


        $barChart = Chartjs::build()
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 350, 'height' => 150])
            ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة', 'الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    "label" => "الفواتير الغير المدفوعة",
                    'backgroundColor' => ['#ec5858'],
                    'data' => [round($percentageOfInvoices2)]
                ],
                [
                    "label" => "الفواتير المدفوعة",
                    'backgroundColor' => ['#81b214'],
                    'data' => [round($percentageOfInvoices1)]
                ],
                [
                    "label" => "الفواتير المدفوعة جزئيا",
                    'backgroundColor' => ['#ff9642'],
                    'data' => [round($percentageOfInvoices3)]
                ],
            ])
            ->options([
                "scales" => [
                    "y" => [
                        "beginAtZero" => true
                    ]
                ]
            ]);

        $pieChart = Chartjs::build()
            ->name('pieChartTest')
            ->type('pie')
            ->size(['width' => 400, 'height' => 200])
            ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة', 'الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    'backgroundColor' => ['#ec5858', '#81b214', '#ff9642'],
                    'hoverBackgroundColor' => ['#ec5858', '#81b214', '#ff9642'],
                    'data' => [round($percentageOfInvoices2), round($percentageOfInvoices1), round($percentageOfInvoices3)]
                ]
            ])
            ->options([]);


        return view('home', compact('barChart', 'pieChart'));
    }
}
