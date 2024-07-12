<?php
namespace App\Http\Controllers;
use DB;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;


class UserController extends Controller implements HasMiddleware
{

function __construct()
{

}

public static function middleware(): array
    {
        return [
            new Middleware(middleware: 'permission:حذف مستخدم',only: ['destroy']),
            new Middleware(middleware: 'permission:تعديل مستخدم',only: ['update','edit']),
            new Middleware(middleware: 'permission:اضافة مستخدم',only: ['store','create','index']),
        ];
    }
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
            ->with('Add', 'User created successfully');
    }
    public function show($id)
    {
        $user=User::findOrFail($id);
        return view('users.show',compact('user'));
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        return view('users.edit',compact('user','roles','userRole'));
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $attributeUsers = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
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
        $user->update($attributeUsers);
        $user->syncRoles($request->input('roles_name'));
        return redirect()->route('users.index')
            ->with('edit', 'User updated successfully');
    }
    public function destroy(Request $request)
    {
        User::find($request->user_id)->delete();
        return redirect()->route('users.index')
            ->with('delete','User deleted successfully');
    }
}