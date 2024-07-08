<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\invoices;
use App\Http\Controllers\Controller;


class InvoiceArchiveController extends Controller
{
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