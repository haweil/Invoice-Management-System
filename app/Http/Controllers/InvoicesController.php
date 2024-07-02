<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\sections;
use Illuminate\Http\Request;
use App\Models\invoices_details;
use Illuminate\Support\Facades\DB;
use App\Models\invoice_attachments;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\InvoiceAttachmentsController;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('invoices.invoices');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections= sections::all();
       return view  ('invoices.add_invoice',compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validation
        $invoiceAttributes = $this->validateInvoice($request);

        $invoice = $this->createInvoice($invoiceAttributes);

        $invoicesDetailsController = new InvoicesDetailsController();
        $invoicesDetailsController->createInvoiceDetails($invoice->id, $request);

        // Handle file upload if exists
        if ($request->hasFile('pic'))
        {
            $invoiceAttachmentController = new InvoiceAttachmentsController();
            $invoiceAttachmentController->handleFileUpload($request, $invoice);
        }
        return redirect()->back()->with(['Add' => 'تم اضافة الفاتورة بنجاح ']);
    }



    public function show(invoices $invoices)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(invoices $invoices)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoices $invoices)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(invoices $invoices)
    {
        //
    }
    public function getProducts($id)
    {
        $products=DB::table('products')->where('section_id',$id)->pluck('product_name','id');
        return json_encode($products);
    }
    protected function validateInvoice(Request $request)
    {
        return $request->validate([
            'invoice_number' => 'required',
            'invoice_Date' => 'required',
            'Due_date' => 'required',
            'section_id' => 'required',
            'product' => 'required',
            'Amount_collection' => 'required',
            'Amount_Commission' => 'required',
            'Discount' => 'required',
            'Value_VAT' => 'required',
            'Rate_VAT' => 'required',
            'Total' => 'required',
            'note' => 'nullable',
            'pic' => 'nullable|mimes:jpg,jpeg,png,pdf',
        ], [
            'invoice_number.required' => 'يرجي ادخال رقم الفاتورة',
            'invoice_Date.required' => 'يرجي ادخال تاريخ الفاتورة',
            'Due_date.required' => 'يرجي ادخال تاريخ الاستحقاق',
            'section_id.required' => 'يرجي ادخال القسم',
            'product.required' => 'يرجي ادخال المنتج',
            'Amount_collection.required' => 'يرجي ادخال مبلغ التحصيل',
            'Amount_Commission.required' => 'يرجي ادخال مبلغ العمولة',
            'Discount.required' => 'يرجي ادخال الخصم',
            'Value_VAT.required' => 'يرجي ادخال قيمة الضريبة',
            'Rate_VAT.required' => 'يرجي ادخال نسبة الضريبة',
            'Total.required' => 'يرجي ادخال الاجمالي',
        ]);
    }

    protected function createInvoice(array $attributes)
    {
        $attributes['Status'] = 'غير مدفوعة';
        $attributes['Value_Status'] = 2;

        return invoices::create($attributes);
    }
}