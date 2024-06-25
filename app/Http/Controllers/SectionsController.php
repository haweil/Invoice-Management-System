<?php

namespace App\Http\Controllers;

use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view ('sections.sections');
    }


    public function create()
    {

    }


    public function store(Request $request)
    {
        $input = $request->all();
        $b_exists = sections::where('section_name', '=', $input['section_name'])->exists();
        if ($b_exists) {
            session()->flash ('Error', 'هذا القسم موجود بالفعل');
            return view ('sections.sections');
        }
        else
        {
            $sectionAttributes = $request->validate([
                'section_name' => 'required|unique:sections|max:255',
                'description' => 'required',
            ]);
            $sectionAttributes['Created_by'] = Auth::user()->name;
            sections::create($sectionAttributes);
        }
        session()->flash('Add', 'تم اضافة القسم بنجاح');
        return view ('sections.sections');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, sections $sections)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(sections $sections)
    {
        //
    }
}
