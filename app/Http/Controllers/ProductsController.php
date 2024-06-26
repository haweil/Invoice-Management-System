<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\sections;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
            'Product_name' => 'required',
            'section_id' => 'required',
            'description' => 'required',
        ],[
            'Product_name.required' => 'يرجي ادخال اسم المنتج',
            'section_id.required' => 'يرجي ادخال القسم',
            'description.required' => 'يرجي ادخال الوصف',
        ]);
        products::create($productAttributes);
        return redirect()->back()->with(['Add' => 'تم اضافة المنتج بنجاح ']);
    }

    /**
     * Display the specified resource.
     */
    public function show(products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, products $products)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(products $products)
    {
        //
    }
}