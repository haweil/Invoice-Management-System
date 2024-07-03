<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use Illuminate\Http\Request;
use App\Models\invoice_attachments;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InvoiceAttachmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */


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
        $request->validate([
            'file_name' => 'required|mimes:pdf,jpeg,png,jpg',
        ], [
            'file_name.required' => 'يرجى اختيار ملف للرفع.',
            'file_name.mimes' => 'صيغة المرفق يجب ان تكون pdf, jpeg, png, jpg.',
        ]);

        $this->addFileUpload($request);
        return redirect()->back()->with(['Add' => 'تم اضافة المرفق بنجاح ']);
    }

    /**
     * Display the specified resource.
     */
    public function show(invoice_attachments $invoice_attachments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(invoice_attachments $invoice_attachments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $invoice_id)
    {
        invoice_attachments::where('invoice_id', $invoice_id)->update([
            'invoice_number' => $request->invoice_number,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(invoice_attachments $invoice_attachments)
    {
        //
    }
    public function handleFileUpload(Request $request, $invoice)
    {
        if ($request->hasFile('pic')) {
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number=$request->invoice_number;

            $attachments = new invoice_attachments();
            $attachments->file_name =$file_name;
            $attachments->invoice_id=$invoice->id;
            $attachments->Created_by=Auth::user()->name;
            $attachments->invoice_number=$invoice_number;
            $attachments->save();

            // move pic
            $imageName =$request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/'.$invoice_number),$imageName);
        }
    }

    public function addFileUpload(Request $request)
    {
        if ($request->hasFile('file_name')) {
            $image = $request->file('file_name');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new invoice_attachments();
            $attachments->file_name = $file_name;
            $attachments->invoice_id = $request->invoice_id;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoice_number = $invoice_number;
            $attachments->save();

            // Move the file
            $imageName =$request->file_name->getClientOriginalName();
            $request->file_name->move(public_path('Attachments/'.$invoice_number),$imageName);        }
    }
}
