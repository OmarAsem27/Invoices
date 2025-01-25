<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;

class RoleController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // examples with aliases, pipe-separated names, guards, etc:
            // 'role_or_permission:manager|edit articles',
            // new Middleware('role:author', only: ['index']),
            // new Middleware( \Spatie\Permission\Middleware\RoleMiddleware::using('manager'), except: ['show']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('عرض صلاحية'), only: ['index']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('اضافة صلاحية'), only: ['create', 'store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('تعديل صلاحية'), only: ['edit', 'update']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('حذف صلاحية'), only: ['destroy']),
        ];
    }


    // Laravel 10 and older versions
    // function __construct()
    // {
    //     $this->middleware('can:عرض صلاحية', ['only' => ['index']]);
    //     $this->middleware('can:اضافة صلاحية', ['only' => ['create', 'store']]);
    //     $this->middleware('can:تعديل صلاحية')->only(['edit', 'update']);
    //     $this->middleware('can:حذف صلاحية', ['only' => ['destroy']]);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // if (!auth()->user()->can('عرض صلاحية')) {
        //     abort(403);
        // }

        $roles = Role::orderBy('id', 'DESC')->paginate(5);
        return view('roles.index', compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // if (!auth()->user()->can('اضافة صلاحية')) {
        //     abort(403);
        // }

        $permission = Permission::get();
        return view('roles.create', compact('permission'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if (!auth()->user()->can('اضافة صلاحية')) {
        //     abort(403);
        // }

        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        \Log::info('Submitted Permissions:', $request->input('permission'));

        // Convert string IDs to integers
        $permissionIds = array_map('intval', $request->input('permission'));
        // $permissionIds = collect($request->input('permission'))
        //     ->map(fn($id) => (int) $id)
        //     ->toArray();

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($permissionIds);

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();
        return view('roles.show', compact('role', 'rolePermissions'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // if (!auth()->user()->can('تعديل صلاحية')) {
        //     abort(403);
        // }

        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();
        return view('roles.edit', compact('role', 'permission', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // if (!auth()->user()->can('تعديل صلاحية')) {
        //     abort(403);
        // }

        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
        Log::debug("IDS ARE: ", $request->permission);

        $permissionIDs = array_map('intval', $request->permission);
        $role->syncPermissions($permissionIDs);

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // if (!auth()->user()->can('حذف صلاحية')) {
        //     abort(403);
        // }

        DB::table("roles")->where('id', $id)->delete();
        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully');
    }
}
