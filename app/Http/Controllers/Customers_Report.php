<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class Customers_Report extends Controller implements HasMiddleware
{
    public static function middleware(): array
     {
         return [
             new Middleware(middleware: 'permission:تقرير العملاء', only: ['index','Search_customers']),

         ];
    }
    public function index()
    {
        $sections = sections::all();
        return view('reports.customers_report',compact('sections'));
    }
    public function Search_customers(Request $request)
    {
        if ($request->Section && $request->product && $request->start_at == '' && $request->end_at == '') {
            $invoices = invoices::where('section_id', $request->Section)
                ->where('product', $request->product)
                ->get();
            $sections = sections::all();
            return view('reports.customers_report', compact('invoices', 'sections'));
        }
        else {
            $invoices = invoices::where('section_id', $request->Section)
                ->where('product', $request->product)
                ->whereBetween('invoice_Date', [$request->start_at, $request->end_at])
                ->get();
            $sections = sections::all();
            return view('reports.customers_report', compact('invoices', 'sections'));
        }
    }
}