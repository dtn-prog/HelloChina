<?php

namespace App\Support\Enums;

enum AuditEvent: string
{
    case LOGIN = 'login';
    case LOGOUT = 'logout';
    case LOGIN_FAILED = 'login_failed';
    case CREATE = 'created';
    case UPDATE = 'updated';
    case DELETE = 'deleted';
    case EXPORT = 'export';
    case IMPORT = 'import';
    case APPROVE = 'approved';
    case REJECT = 'rejected';
    case PASSWORD_CHANGED = 'password_changed';
    case PERMISSION_CHANGED = 'permission_changed';
    case ROLE_CHANGED = 'role_changed';
    case BULK_ACTION = 'bulk_action';
    case IMPERSONATION = 'impersonation';

    public function label(): string
    {
        return match ($this) {
            self::LOGIN => 'Login',
            self::LOGOUT => 'Logout',
            self::LOGIN_FAILED => 'Login Failed',
            self::CREATE => 'Create',
            self::UPDATE => 'Update',
            self::DELETE => 'Delete',
            self::EXPORT => 'Export',
            self::IMPORT => 'Import',
            self::APPROVE => 'Approve',
            self::REJECT => 'Reject',
            self::PASSWORD_CHANGED => 'Password Changed',
            self::PERMISSION_CHANGED => 'Permission Changed',
            self::ROLE_CHANGED => 'Role Changed',
            self::BULK_ACTION => 'Bulk Action',
            self::IMPERSONATION => 'Impersonation',
        };
    }
}
