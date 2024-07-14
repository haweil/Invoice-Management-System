<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\invoices;
use App\Models\sections;
use Illuminate\Http\Request;
use App\Exports\InvoicesExport;
use App\Models\invoices_details;
use App\Notifications\AddInvoice;
use App\Notifications\Add_invoice_new;
use Illuminate\Support\Facades\DB;
use App\Models\invoice_attachments;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\InvoiceAttachmentsController;

class InvoicesController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */
public function __construct()
{
}
public static function middleware(): array
     {
         return [
            new Middleware(middleware: 'permission:قائمة الفواتير', only: ['index']),
             new Middleware(middleware: 'permission:اضافة فاتورة', only: ['store', 'create']),
             new Middleware(middleware: 'permission:حذف الفاتورة', only: ['destroy']),
             new Middleware(middleware: 'permission:تصدير EXCEL', only: ['export']),
             new Middleware(middleware: 'permission:تغير حالة الدفع', only: ['Status_Update','Status_show']),
             new Middleware(middleware: 'permission:تعديل الفاتورة', only: ['update', 'edit']),
             new Middleware(middleware: 'permission:طباعةالفاتورة', only: ['Print_invoice']),
             new Middleware(middleware: 'permission:حذف المرفق', only: ['deleteAttachment']),
             new Middleware(middleware: 'permission:الفواتير المدفوعة', only: ['invoices_paid']),
             new Middleware(middleware: 'permission:الفواتير الغير مدفوعة', only: ['invoices_unpaid']),
             new Middleware(middleware: 'permission:الفواتير المدفوعة جزئيا', only: ['invoices_partial']),
         ];
    }
    public function index()
    {
        $section=sections::with('invoices')->get();
        $invoices = invoices::all();
        return view('invoices.invoices',compact('invoices','section'));
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
        $invoice_id=invoices::latest()->first()->id;
        $invoicesDetailsController = new InvoicesDetailsController();
        $invoicesDetailsController->createInvoiceDetails($invoice->id, $request);

        // Handle file upload if exists
        if ($request->hasFile('pic'))
        {
            $invoiceAttachmentController = new InvoiceAttachmentsController();
            $invoiceAttachmentController->handleFileUpload($request, $invoice);
        }

        //send Email
        //$user = User::find(1); // Ensure this user exists in your database
        //Notification::send($user, new AddInvoice($invoice_id));

        $users = User::where('id', '!=', Auth::user()->id)->get();
        $invoices=invoices::latest()->first();
        Notification::send($users,new Add_invoice_new($invoices));

        return redirect()->back()->with(['Add' => 'تم اضافة الفاتورة بنجاح ']);
    }



    public function show(invoices $invoices)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoices = invoices::where('id', $id)->first();
        $sections = sections::all();
        return view('invoices.edit_invoice',compact('invoices','sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->invoice_id;
        $invoiceAttributes = $this->validateInvoice($request);
        invoices::where('id', $id)->update($invoiceAttributes);
        $invoicesDetailsController = new InvoicesDetailsController();
        $invoicesDetailsController->updateInvoiceDetails($id, $request);
        $invoiceAttachmentController = new InvoiceAttachmentsController();
        $invoiceAttachmentController->update($request, $id);
        return redirect()->back()->with(['Update' => 'تم تعديل الفاتورة بنجاح']);
    }

    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoice = invoices::where('id', $id)->first();
        $details = invoice_attachments::where('invoice_id', $id)->get();
        $id_page = $request->id_page;
        if (!$id_page ==2)
        {
        foreach ($details as $detail)
        {
        if (!empty($detail->invoice_number))
        {
                Storage::disk('public_uploads')->deleteDirectory($detail->invoice_number);
        }
        }
        $invoice->forcedelete();
        DB::table('notifications')->where('data->id', $id)->delete();
        session()->flash('delete_invoice');
        return redirect('/invoices');
        }
        else
        {
            $invoice->delete();
            session()->flash('archive_invoice');
            return redirect('/Archive');
        }
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
    public function Status_show($id)
    {
        $invoices = invoices::where('id', $id)->first();
        return view('invoices.status_update',compact('invoices'));
    }
    public function Status_Update($id, Request $request)
    {
        $invoice = invoices::findOrFail($id);

        // Determine the status and value
        $status = $request->Status;
        $valueStatus = ($status === 'مدفوعة') ? 1 : 3;

        // Update the invoice
        $invoice->update([
            'Status' => $status,
            'Value_Status' => $valueStatus,
            'Payment_Date' => $request->Payment_Date
        ]);

        // Create invoice details
        invoices_details::create([
            'invoice_id' => $id,
            'invoice_number' => $invoice->invoice_number,
            'product' => $invoice->product,
            'Section' => $invoice->section_id,
            'Status' => $status,
            'Value_Status' => $valueStatus,
            'Payment_Date' => $request->Payment_Date,
            'note' => $request->note,
            'user' => auth()->user()->name,
        ]);

        session()->flash('Status_Update');

        return redirect('/invoices');
     }

     public function invoices_paid()
     {
         $invoices = invoices::where('Value_Status',1)->get();
         return view('invoices.invoices_paid', compact('invoices'));
     }
     public function invoices_unpaid()
     {
         $invoices = invoices::where('Value_Status',2)->get();
         return view('invoices.invoices_unpaid', compact('invoices'));
     }
    public function invoices_partial()
    {
        $invoices = invoices::where('Value_Status',3)->get();
        return view('invoices.invoices_partial', compact('invoices'));
    }
    public function Print_invoice($id)
    {
        $invoices = invoices::where('id', $id)->first();
        return view('invoices.Print_invoice',compact('invoices'));
    }
    public function export()
    {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }
    public function MarkAsRead_all(Request $request)
    {
        $userUnreadNotification = auth()->user()->unreadNotifications;
        if ($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
            return back();
        }
    }

        public function MarkAsRead($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            $invoiceId = $notification->data['id'];
            return redirect()->route('InvoicesDetails', $invoiceId);
        }

        return redirect()->back();
    }
    public function notifications_unread () {

        return auth()->user()->unreadNotifications;
    }
}