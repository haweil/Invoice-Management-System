<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class ProductsController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */
    public static function middleware(): array
    {
        return [
            new Middleware(middleware: 'permission:اضافة منتج', only: ['store', 'create']),
            new Middleware(middleware: 'permission:تعديل منتج', only: ['update', 'edit']),
            new Middleware(middleware: 'permission:حذف منتج', only: ['destroy']),
            new Middleware(middleware: 'permission:عرض منتج', only: ['index']),
        ];
    }
    public function index()
    {
        $sections =sections::with('products')->get();
        $products=products::all();
        return view('products.products',compact('sections','products'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $productAttributes = $request->validate([
            'Product_name' => [
                'required',
                Rule::unique('products')->where(function ($query) use ($request) {
                    return $query->where('section_id', $request->section_id);
                }),
            ],
            'section_id' => 'required',
            'description' => 'nullable',
        ],[
            'Product_name.unique' => 'اسم المنتج موجود مسبقا في هذا القسم',
            'Product_name.required' => 'يرجي ادخال اسم المنتج',
            'section_id.required' => 'يرجي ادخال القسم',
        ]);

        products::create($productAttributes);
        return redirect()->back()->with(['Add' => 'تم اضافة المنتج بنجاح ']);
    }


    public function show(products $products)
    {

    }

    public function edit(products $products)
    {

    }


    public function update(Request $request)
    {
        $id =$request->id;
        $productAttributes = $request->validate([
            'Product_name' => [
                'required',
                Rule::unique('products')->where(function ($query) use ($request,$id) {
                    return $query->where('section_id', $request->section_id)->where('id', '!=', $id);
                }),
            ],
            'section_id' => 'required',
            'description' => 'nullable',
        ],[
            'Product_name.unique' => 'اسم المنتج موجود مسبقا في هذا القسم',
            'Product_name.required' => 'يرجي ادخال اسم المنتج',
            'section_id.required' => 'يرجي ادخال القسم',
        ]);
        $products=products::findOrFail($id)->update($productAttributes);
        return redirect()->back()->with(['Update' => 'تم تعديل المنتج بنجاح ']);

    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        products::findOrFail($id)->delete();
        return redirect()->back()->with(['delete' => 'تم حذف المنتج بنجاح ']);
    }
}