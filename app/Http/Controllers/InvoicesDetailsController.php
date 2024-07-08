<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use Illuminate\Http\Request;
use App\Models\invoices_details;
use App\Models\invoice_attachments;
use Illuminate\Support\Facades\Storage;

class InvoicesDetailsController extends Controller
{


    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoices = invoices::where('id',$id)->first();
        $details = invoices_details::where('invoice_id',$id)->get();
        $attachments = invoice_attachments::where('invoice_id',$id)->get();
        return view ('invoices.invoices_details', compact('invoices','details','attachments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(invoices_details $invoices_details)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoices_details $invoices_details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(invoices_details $invoices_details)
    {
        //
    }
    public function createInvoiceDetails($invoiceId, Request $request)
    {
        invoices_details::create([
            'invoice_id' => $invoiceId,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->section_id,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => auth()->user()->name,
        ]);
    }
    public function updateInvoiceDetails($invoice_id, Request $request)
    {

        invoices_details::where('invoice_id', $invoice_id)->update([
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->section_id,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => auth()->user()->name,
        ]);
    }
    public function Open_file($invoices_number , $file_name)
    {
        $path = "Attachments/$invoices_number/$file_name";
        if (Storage::disk('public')->exists($path)) {
            $fullPath = storage_path('app/public/' . $path);
            if (file_exists($fullPath)) {

                return response()->file($fullPath);
            }
        }

    }
    public function download_file($invoiceNumber, $fileName)
    {
        $files = invoice_attachments::where('invoice_id', $invoiceNumber)->where('file_name', $fileName)->first();
        $path = public_path() . '/Attachments/' . $invoiceNumber . '/' . $fileName;
        return response()->download($path);
    }

    public function Delete_file(Request $request)
    {
        $id = $request->id_file;
        $invoices_number = $request->invoice_number;
        $file_name = $request->file_name;

        $attachments = invoice_attachments::findOrFail($id);
        $attachments->delete();
        Storage::disk('public_uploads')->delete( $invoices_number . '/' . $file_name);
        return redirect()->back()->with(['Success' => 'تم حذف المرفق بنجاح']);
    }

}
