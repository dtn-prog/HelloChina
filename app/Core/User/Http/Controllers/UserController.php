<?php

namespace App\Core\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Core\Audit\Services\AuditService;
use App\Core\User\Http\Requests\StoreUserRequest;
use App\Core\User\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(
        protected AuditService $auditService
    ) {}

    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($role = $request->input('role')) {
            $query->whereHas('roles', function ($q) use ($role) {
                $q->where('name', $role);
            });
        }

        $users = $query->latest()->paginate($request->input('per_page', 15));

        if ($request->expectsJson()) {
            return response()->json(['data' => $users]);
        }

        return view('pages.users.index', compact('users'));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        if ($roles = $request->input('roles')) {
            $user->syncRoles($roles);
        }

        $this->auditService->logCreate($user, $user->toArray());

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'data' => $user], 201);
        }

        return redirect()->route('users.show', $user)->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load('roles', 'loginHistory');

        if ($request->expectsJson()) {
            return response()->json(['data' => $user]);
        }

        return view('pages.users.show', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $old = $user->toArray();
        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        if ($request->has('roles')) {
            $user->syncRoles($request->input('roles'));
        }

        $this->auditService->logUpdate($user, $old, $user->toArray());

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'data' => $user]);
        }

        return redirect()->route('users.show', $user)->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request, User $user)
    {
        $this->auditService->logDelete($user);
        $user->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,assign_role',
            'user_ids' => 'required|array',
            'role' => 'required_if:action,assign_role',
        ]);

        $users = User::whereIn('id', $request->user_ids)->get();

        foreach ($users as $user) {
            match ($request->action) {
                'activate' => $user->update(['status' => 'active']),
                'deactivate' => $user->update(['status' => 'inactive']),
                'delete' => $user->delete(),
                'assign_role' => $user->syncRoles([$request->role]),
            };
        }

        $this->auditService->log("Bulk {$request->action} on " . $users->count() . ' users', null, 'default', [
            'event' => 'bulk_action',
            'user_ids' => $request->user_ids,
            'action' => $request->action,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => ucfirst($request->action) . ' applied to ' . $users->count() . ' users.']);
        }

        return back()->with('success', ucfirst($request->action) . ' applied to ' . $users->count() . ' users.');
    }

    public function impersonate(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'You cannot impersonate yourself.');
        }

        Auth::login($user);

        $this->auditService->log("Impersonated user {$user->name}", $user, 'security', [
            'event' => 'impersonation',
            'admin_id' => $request->user()->id,
        ]);

        return redirect()->route('dashboard')->with('success', 'Now impersonating ' . $user->name);
    }

    public function export(Request $request)
    {
        $users = User::with('roles')->get();

        $csv = "ID,Name,Email,Phone,Status,Roles,Created At\n";
        foreach ($users as $user) {
            $csv .= implode(',', [
                $user->id,
                '"' . str_replace('"', '""', $user->name) . '"',
                $user->email,
                $user->phone ?? '',
                $user->status,
                '"' . $user->roles->pluck('name')->implode(', ') . '"',
                $user->created_at->format('Y-m-d H:i:s'),
            ]) . "\n";
        }

        $this->auditService->log('Exported users', null, 'default', [
            'event' => 'export',
            'count' => $users->count(),
        ]);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="users_' . now()->format('Y-m-d_His') . '.csv"');
    }
}
