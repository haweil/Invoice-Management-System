<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class RoleController extends Controller implements HasMiddleware
{
/**
* Display a listing of the resource.
*
* @return \Illuminate\Http\Response
*/


function __construct()
{

}

 public static function middleware(): array
{
    return [
        new Middleware('permission:role-list|role-create|role-edit|role-delete ', ['only' => ['index','create','store','edit','update']]),
        new Middleware('permission:role-create', ['only' => ['create','store']]),
        new Middleware('permission:role-edit', ['only' => ['edit','update']]),
        new Middleware('permission:role-delete', ['only' => ['destroy']]),
    ];
}

public function index(Request $request)
{
    $roles = Role::orderBy('id','DESC')->paginate(5);
    return view('roles.index',compact('roles'))
    ->with('i', ($request->input('page', 1) - 1) * 5);
}

public function create()
{
    $permission = Permission::get();
    return view('roles.create',compact('permission'));
}

public function store(Request $request)
    {
        $attributeRoles = $request->validate([
        'name' => 'required|unique:roles,name',
        'permission' => 'required',
        ]);
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));
        return redirect()->route('roles.index')
        ->with('Add','Role created successfully');
    }

public function show($id)
 {
    $role = Role::find($id);
    $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
    ->where("role_has_permissions.role_id",$id)
    ->get();
    return view('roles.show',compact('role','rolePermissions'));
 }

public function edit($id)
 {
    $role = Role::find($id);
    $permission = Permission::get();
    $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
    ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
    ->all();
    return view('roles.edit',compact('role','permission','rolePermissions'));
 }

public function update(Request $request, $id)
    {
        $attributeRoles = $request->validate([
            'name' => 'required',
            'permission' => 'required',
        ]);
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
        $role->syncPermissions($request->input('permission'));
        return redirect()->route('roles.index')
        ->with('edit','Role updated successfully');
    }

public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('roles.index')
        ->with('delete','Role deleted successfully');
    }
}