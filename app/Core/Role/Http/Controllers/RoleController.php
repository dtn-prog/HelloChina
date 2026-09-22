<?php

namespace App\Core\Role\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Core\Audit\Services\AuditService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct(
        protected AuditService $auditService
    ) {}

    public function index(Request $request)
    {
        $roles = Role::withCount('permissions')->latest()->paginate(15);

        if ($request->expectsJson()) {
            return response()->json(['data' => $roles]);
        }

        return view('pages.roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        $this->auditService->logCreate($role, $role->toArray());

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'data' => $role], 201);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function show(Role $role)
    {
        $role->load('permissions');

        if (request()->expectsJson()) {
            return response()->json(['data' => $role]);
        }

        return view('pages.roles.show', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $old = $role->toArray();
        $role->update(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        $this->auditService->logUpdate($role, $old, $role->toArray());

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'data' => $role]);
        }

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Request $request, Role $role)
    {
        $this->auditService->logDelete($role);
        $role->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }

    public function permissions(Request $request)
    {
        $permissions = Permission::all()->groupBy(function ($p) {
            return explode('.', $p->name)[0] ?? 'other';
        });

        if ($request->expectsJson()) {
            return response()->json(['data' => $permissions]);
        }

        return view('pages.roles.permissions', compact('permissions'));
    }
}
