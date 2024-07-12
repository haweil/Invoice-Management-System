<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\invoices;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }


    public function index()
    {
     // ExampleController.php

$chartjs = app()->chartjs
->name('pieChartTest')
->type('pie')
->size(['width' => 400, 'height' => 200])
->labels(['Label x', 'Label y'])
->datasets([
    [
        'backgroundColor' => ['#FF6384', '#36A2EB'],
        'hoverBackgroundColor' => ['#FF6384', '#36A2EB'],
        'data' => [69, 59]
    ]
])
->options([]);

return view('dashboard', compact('chartjs'));

        return view('dashboard');

    }


}