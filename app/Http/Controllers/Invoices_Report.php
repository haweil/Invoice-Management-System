<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class Invoices_Report extends Controller implements HasMiddleware
{

    public static function middleware(): array
     {
         return [
             new Middleware(middleware: 'permission:تقرير الفواتير', only: ['index','Search_invoices']),

         ];
    }
    public function index()
    {
        return view('reports.invoices_report');
    }

    public function Search_invoices (Request $request)
    {
        $radio = $request->radio;
        if ($radio == 1)
         {
            // في حالة عدم تحديد تاريخ
           if ($request->type&&$request->start_at =='' &&$request->end_at =='') {
               if ($request->type == 'الكل') {
                   $invoices = invoices::select('*')->get();
               }
               else {
               $invoices = invoices::select('*')->where('Status', $request->type)->get();
               }
               $type = $request->type;
               return view('reports.invoices_report', compact('type', 'invoices'));
           }
              // في حالة تحديد تاريخ
              else {
                $type = $request->type;
                if ($request->type == 'الكل') {
                    $invoices = invoices::whereBetween('invoice_Date', [$request->start_at, $request->end_at])->get();
                }
                else {
                    $invoices = invoices::whereBetween('invoice_Date', [$request->start_at, $request->end_at])->where('Status', $request->type)->get();
                }
                return view('reports.invoices_report', compact('type', 'invoices'));
              }
         }



        else {
            $invoice_number = $request->invoice_number;
            $invoices = invoices::where('invoice_number', $invoice_number)->get();
            return view('reports.invoices_report', compact('invoices'));
        }

    }
}