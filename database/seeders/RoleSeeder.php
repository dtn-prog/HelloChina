<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $roles = [
            'super_admin' => 'Full system access',
            'admin' => 'Administrative access',
            'manager' => 'Management access',
            'finance' => 'Finance operations',
            'support' => 'Customer support',
            'editor' => 'Content editing',
            'developer' => 'Developer access',
            'viewer' => 'Read-only access',
            'learner' => 'App learner / student access',
        ];

        foreach ($roles as $name => $description) {
            Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        // Create permissions
        $modules = [
            'users' => ['view', 'create', 'update', 'delete', 'export', 'import'],
            'roles' => ['view', 'create', 'update', 'delete'],
            'permissions' => ['view', 'create', 'update', 'delete'],
            'settings' => ['view', 'update'],
            'feature_flags' => ['view', 'create', 'update', 'delete'],
            'audit_logs' => ['view', 'export'],
            'media' => ['view', 'create', 'update', 'delete'],
            'notifications' => ['view', 'create', 'send'],
            'notification_templates' => ['view', 'create', 'update', 'delete'],
            'api_keys' => ['view', 'create', 'delete'],
            'webhooks' => ['view', 'create', 'update', 'delete'],
            'dashboard' => ['view', 'configure'],
            'system_health' => ['view'],
            'error_logs' => ['view', 'delete'],
            'login_history' => ['view'],
            'security' => ['view', 'manage'],
            'products' => ['view', 'create', 'update', 'delete', 'export', 'import'],
            'levels' => ['view'],
        ];

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$module}.{$action}", 'guard_name' => 'web']);
            }
        }

        // Assign permissions to roles
        $superAdmin = Role::where('name', 'super_admin')->first();
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::where('name', 'admin')->first();
        $admin->givePermissionTo(Permission::where('name', '!=', 'settings.update')->get());

        $manager = Role::where('name', 'manager')->first();
        $manager->givePermissionTo([
            'users.view', 'users.create', 'users.update',
            'dashboard.view',
            'audit_logs.view',
            'media.view', 'media.create', 'media.update',
            'notifications.view', 'notifications.create',
            'levels.view',
        ]);

        $finance = Role::where('name', 'finance')->first();
        $finance->givePermissionTo([
            'users.view',
            'dashboard.view',
            'audit_logs.view', 'audit_logs.export',
        ]);

        $support = Role::where('name', 'support')->first();
        $support->givePermissionTo([
            'users.view', 'users.update',
            'dashboard.view',
            'notifications.view', 'notifications.create',
        ]);

        $editor = Role::where('name', 'editor')->first();
        $editor->givePermissionTo([
            'media.view', 'media.create', 'media.update', 'media.delete',
            'notifications.view', 'notifications.create',
        ]);

        $developer = Role::where('name', 'developer')->first();
        $developer->givePermissionTo([
            'api_keys.view', 'api_keys.create', 'api_keys.delete',
            'webhooks.view', 'webhooks.create', 'webhooks.update', 'webhooks.delete',
            'system_health.view',
            'error_logs.view',
        ]);

        $viewer = Role::where('name', 'viewer')->first();
        $viewer->givePermissionTo([
            'dashboard.view',
            'users.view',
            'audit_logs.view',
            'levels.view',
        ]);

        // learner remains an app-side role with no admin permissions
    }
}
