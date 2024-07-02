<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\invoice_attachments;
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
        //
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
    public function update(Request $request, invoice_attachments $invoice_attachments)
    {
        //
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
}