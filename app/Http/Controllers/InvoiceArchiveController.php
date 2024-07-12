<?php
namespace App\Http\Controllers;

use App\Models\invoices;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class InvoiceArchiveController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware(middleware: 'permission:تعديل الفاتورة', only: ['update']),
            new Middleware(middleware: 'permission:حذف الفاتورة', only: ['destroy']),
            new Middleware(middleware: 'permission:ارشيف الفواتير', only: ['index']),
        ];
    }
    public function index()
    {
        $invoices = invoices::onlyTrashed()->get();
        return view('invoices.archive_invoices',compact('invoices'));
    }

    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoice = invoices::withTrashed()->where('id',$id)->first();
        $invoice->forceDelete();
        session()->flash('delete_invoice');
        return redirect('/invoices');
    }
    public function update(Request $request)
    {
        $id = $request->invoice_id;
        $invoice = invoices::withTrashed()->where('id',$id)->restore();
        session()->flash('restore_invoice');
        return redirect('/invoices');
    }
}