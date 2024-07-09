<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderBy('id', 'desc')->paginate(5);
        return view('users.show_users', compact('users'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('users.Add_user',compact('roles'));
    }
    public function store(Request $request)
    {
         $attributeUsers = $request->validate([
           'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles_name' => 'required',
            'status' => 'required',
         ],[
                'roles_name.required' => 'من فضلك اختر الصلاحيات للمستخدم',
                'status.required' => 'من فضلك اختر الحالة للمستخدم',
                'email.unique' => 'هذا البريد الالكتروني مسجل مسبقا',
                'name.required' => 'من فضلك ادخل الاسم',
                'email.required' => 'من فضلك ادخل البريد الالكتروني',
                'password.required' => 'من فضلك ادخل كلمة المرور',
                'password.same' => 'كلمة المرور غير متطابقة',
        ]);

         $attributeUsers['password'] = bcrypt($attributeUsers['password']);
         $user = User::create($attributeUsers);
        $user->assignRole($request->input('roles_name'));

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }
    public function show($id)
    {

    }
    public function edit($id)
    {

    }
    public function update(Request $request, $id)
    {

    }
    public function destroy($id)
    {

    }
}
