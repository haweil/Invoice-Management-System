<?php

namespace App\Http\Controllers;

use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class SectionsController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */

     public static function middleware(): array
     {
         return [
             new Middleware(middleware: 'permission:اضافة قسم', only: ['store', 'create']),
             new Middleware(middleware: 'permission:تعديل قسم', only: ['update', 'edit']),
             new Middleware(middleware: 'permission:حذف قسم', only: ['destroy']),
             new Middleware(middleware: 'permission:عرض قسم', only: ['index']),
         ];
     }
    public function index()
    {
        $sections=sections::all();
        return view ('sections.sections',compact('sections'));
    }


    public function create()
    {

    }


    public function store(Request $request)
    {

            $sectionAttributes = $request->validate([
                'section_name' => 'required|unique:sections|max:255',
                'description' => 'required',
            ],[
                'section_name.unique' => 'هذا القسم مسجل مسبقا',
                'section_name.required' => 'يجب ادخال اسم القسم',
                'description.required' => 'يجب ادخال الوصف',
            ]);
            $sectionAttributes['Created_by'] = Auth::user()->name;
            sections::create($sectionAttributes);
             return redirect()->back()->with(['Add' => 'تم اضافة القسم بنجاح ']);
    }

    /**
     * Display the specified resource.
     */
    public function show(sections $sections)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(sections $sections)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id =$request->id;
        $sectionAttributes = $request->validate([
            'section_name' => 'required|unique:sections,section_name,' . $id,
            'description' => 'nullable',
        ],[
            'section_name.unique' => 'هذا القسم مسجل مسبقا',
            'section_name.required' => 'يجب ادخال اسم القسم',
        ]);

        $section=sections::findOrFail($id);
        $sectionAttributes['Created_by'] = Auth::user()->name;
        $section->update($sectionAttributes);
        return redirect()->back()->with(['Update' => 'تم تعديل القسم بنجاح ']);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $section =sections::findOrFail($request->id)->delete();
        return redirect()->back()->with(['Delete' => 'تم حذف القسم بنجاح ']);

    }
}