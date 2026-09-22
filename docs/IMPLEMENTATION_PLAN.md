# Base Admin Platform — Implementation Plan

## Mục tiêu

Xây dựng Base Admin Platform cho hệ thống TINOTECH, dùng làm nền tảng chung cho:
- GoPlusAI
- Ecoluck
- GapMove
- GoIMENU
- và các dự án tương lai

Base Admin không chỉ là UI CRUD — mà là **hệ điều hành Backend** có sẵn:
Auth, RBAC, Audit, Media, Notification, Settings, API, Dashboard, Logging, Feature Flags...

---

## Kiến trúc tổng quan

```
BASE ADMIN (Core + Platform)
    │
    ├── Core: Auth, User, Role, Permission, Session, Security
    ├── System: Dashboard, Settings, Feature Flags, Audit, Health, Logs
    ├── Content: Media Library, File Manager, Categories, Tags
    ├── Communication: Notifications, Email, SMS, Templates
    ├── Developer: API Keys, Webhooks, API Logs, Queue Monitor
    └── Monitoring: Server, Database, Redis, Error Tracking

Project-specific modules (GoPlusAI, Ecoluck, etc.) plug into this core.
```

---

## Các Phase triển khai

### Phase 1 — Foundation (Nền tảng)

**Mục tiêu:** Hệ thống auth hoàn chỉnh + RBAC + Audit Log cơ bản

| # | Task | Mô tả | Trạng thái |
|---|------|-------|-----------|
| 1.1 | Cài đặt packages | Spatie Permission, Laravel Sanctum, Intervention Image, Spatie Activity Log | ⬜ |
| 1.2 | Database Migration — Users extend | Thêm avatar, phone, status, 2fa, last_login, last_ip vào users table | ⬜ |
| 1.3 | Database Migration — Roles & Permissions | Tạo bảng roles, permissions, model_has_roles, model_has_permissions (Spatie) | ⬜ |
| 1.4 | Database Migration — Audit Log | T��이블 activity_log (Spatie Activity Log) | ⬜ |
| 1.5 | Database Migration — Sessions extend | Thêm ip, user_agent, device info vào sessions | ⬜ |
| 1.6 | Model — User extend | Extend User model với 2FA, status, roles, permissions, audit | ⬜ |
| 1.7 | Model — Role & Permission | Spatie Role/Permission models + Custom Permission definitions | ⬜ |
| 1.8 | Auth — Login | Login controller + throttle + IP tracking | ⬜ |
| 1.9 | Auth — Logout | Logout + revoke session | ⬜ |
| 1.10 | Auth — Register | Register controller + email verification | ⬜ |
| 1.11 | Auth — Forgot/Reset Password | Full flow forgot password + reset | ⬜ |
| 1.12 | Auth — Change Password | Đổi password từ dashboard | ⬜ |
| 1.13 | Auth — 2FA | Two-Factor Authentication (TOTP) | ⬜ |
| 1.14 | Auth — Session Management | Danh sách sessions, revoke, revoke all | ⬜ |
| 1.15 | Auth — Login History | Ghi lại lịch sử login (IP, time, device, status) | ⬜ |
| 1.16 | Middleware — Auth guards | Admin middleware, role middleware, permission middleware | ⬜ |
| 1.17 | Middleware — Audit | Auto audit middleware cho các thay đổi | ⬜ |
| 1.18 | Seeder — Default Roles | Super Admin, Admin, Manager, Finance, Support, Editor, Developer, Viewer | ⬜ |
| 1.19 | Seeder — Default Admin | Tạo tài khoản Super Admin mặc định | ⬜ |
| 1.20 | Seeder — Default Permissions | Tất cả permissions theo resource.action pattern | ⬜ |

### Phase 2 — User Management

**Mục tiêu:** CRUD Users hoàn chỉnh + bulk actions + impersonation

| # | Task | Mô tả | Trạng thái |
|---|------|-------|-----------|
| 2.1 | User Controller — List | DataTable with search, filter, sort, pagination | ⬜ |
| 2.2 | User Controller — Create | Form create user + assign roles | ⬜ |
| 2.3 | User Controller — Read | View user detail + activity history | ⬜ |
| 2.4 | User Controller — Update | Edit user + change roles | ⬜ |
| 2.5 | User Controller — Delete | Soft delete / force delete | ⬜ |
| 2.6 | User — Bulk Actions | Bulk activate, deactivate, delete, assign role | ⬜ |
| 2.7 | User — Impersonation | Login as another user (with audit) | ⬜ |
| 2.8 | User — Export | Export users to CSV/Excel | ⬜ |
| 2.9 | User — Import | Import users from CSV/Excel | ⬜ |
| 2.10 | User — API | RESTful API endpoints for users | ⬜ |

### Phase 3 — RBAC Management

**Mục tiêu:** UI quản lý Roles + Permissions hoàn chỉnh

| # | Task | Mô tả | Trạng thái |
|---|------|-------|-----------|
| 3.1 | Role Controller — CRUD | List, Create, Edit, Delete roles | ⬜ |
| 3.2 | Role Controller — Assign Permissions | Gán permissions cho role | ⬜ |
| 3.3 | Permission Controller — List | Danh sách tất cả permissions | ⬜ |
| 3.4 | Permission Controller — CRUD | Create, Edit, Delete permissions | ⬜ |
| 3.5 | Policy — User Policy | Authorization policy cho User resource | ⬜ |
| 3.6 | Policy — Role Policy | Authorization policy cho Role resource | ⬜ |
| 3.7 | Policy — Setting Policy | Authorization policy cho Setting resource | ⬜ |
| 3.8 | Blade — @can directives | Áp dụng permission checks vào views | ⬜ |
| 3.9 | RBAC — API | RESTful API endpoints | ⬜ |

### Phase 4 — System Settings

**Mục tiêu:** Dynamic settings system + Feature Flags

| # | Task | Mô tả | Trạng thái |
|---|------|-------|-----------|
| 4.1 | Migration — Settings table | key, value, group, type, description | ⬜ |
| 4.2 | Migration — Feature Flags table | name, key, description, enabled, rollout_percentage | ⬜ |
| 4.3 | Model — Setting | Setting model with caching | ⬜ |
| 4.4 | Model — FeatureFlag | FeatureFlag model | ⬜ |
| 4.5 | Service — SettingService | Get/set settings with cache | ⬜ |
| 4.6 | Service — FeatureFlagService | Check feature flags (global, user, role, percentage) | ⬜ |
| 4.7 | Controller — Settings | CRUD settings by group | ⬜ |
| 4.8 | Controller — Feature Flags | CRUD feature flags + rollout control | ⬜ |
| 4.9 | Seeder — Default Settings | General, Email, SMS, Security, Maintenance defaults | ⬜ |
| 4.10 | Helper — setting() | Global helper: setting('key'), setting('key', 'default') | ⬜ |
| 4.11 | Helper — feature_flag() | Global helper: feature_flag('key') | ⬜ |
| 4.12 | Middleware — Maintenance Mode | Check maintenance_mode setting | ⬜ |
| 4.13 | Settings — API | RESTful API endpoints | ⬜ |

### Phase 5 — Audit & Security

**Mục tiêu:** Audit Log hoàn chỉnh + Security monitoring

| # | Task | Mô tả | Trạng thái |
|---|------|-------|-----------|
| 5.1 | Service — AuditService | Log all CRUD operations with before/after | ⬜ |
| 5.2 | Trait — HasAudit | Trait cho models để auto audit | ⬜ |
| 5.3 | Controller — Audit Logs | List, view, filter, export audit logs | ⬜ |
| 5.4 | Controller — Login History | List login attempts + suspicious detection | ⬜ |
| 5.5 | Controller — Security Dashboard | Failed login, blocked IPs, permission changes | ⬜ |
| 5.6 | Service — SecurityService | Detect suspicious logins, rate limiting | ⬜ |
| 5.7 | Event — Audit events | Event/Listener cho audit logging | ⬜ |
| 5.8 | Audit — API | RESTful API endpoints | ⬜ |

### Phase 6 — Media Library

**Mục tiêu:** File upload, management, storage abstraction

| # | Task | Mô tả | Trạng thái |
|---|------|-------|-----------|
| 6.1 | Migration — Media table | name, path, disk, mime_type, size, metadata, folder | ⬜ |
| 6.2 | Migration — Media Folders | folder hierarchy | ⬜ |
| 6.3 | Model — Media | Media model | ⬜ |
| 6.4 | Service — MediaService | Upload, resize, organize, delete | ⬜ |
| 6.5 | Service — StorageService | Abstraction: Local, S3, R2, MinIO | ⬜ |
| 6.6 | Controller — Media CRUD | Upload, list, preview, rename, delete | ⬜ |
| 6.7 | Controller — Media Folders | Create, rename, delete folders | ⬜ |
| 6.8 | Controller — Media Picker | API for frontend media selection | ⬜ |
| 6.9 | Policy — Media Policy | Authorization | ⬜ |
| 6.10 | Media — API | RESTful API endpoints | ⬜ |

### Phase 7 — Notification Center

**Mục tiêu:** In-app notifications + Email + Template system

| # | Task | Mô tả | Trạng thái |
|---|------|-------|-----------|
| 7.1 | Migration — Notifications table | In-app notifications | ⬜ |
| 7.2 | Migration — Notification Templates | Subject, content, variables, language | ⬜ |
| 7.3 | Model — Notification | In-app notification model | ⬜ |
| 7.4 | Model — NotificationTemplate | Template model | ⬜ |
| 7.5 | Service — NotificationService | Send in-app, email, SMS | ⬜ |
| 7.6 | Service — EmailService | Queue email sending | ⬜ |
| 7.7 | Controller — Notifications | List, mark read, mark all read | ⬜ |
| 7.8 | Controller — Notification Templates | CRUD templates | ⬜ |
| 7.9 | Channel — Database Channel | In-app notification channel | ⬜ |
| 7.10 | Realtime — Broadcasting | WebSocket notifications (optional) | ⬜ |
| 7.11 | Notification — API | RESTful API endpoints | ⬜ |

### Phase 8 — Dashboard & Widgets

**Mục tiêu:** Configurable dashboard với widget system

| # | Task | Mô tả | Trạng thái |
|---|------|-------|-----------|
| 8.1 | Migration — Dashboard Widgets | Widget definitions per user/role | ⬜ |
| 8.2 | Interface — WidgetContract | Contract cho tất cả widgets | ⬜ |
| 8.3 | Widget — KPI Widget | Key Performance Indicators | ⬜ |
| 8.4 | Widget — Chart Widget | Line, Bar, Pie charts | ⬜ |
| 8.5 | Widget — Table Widget | Recent data tables | ⬜ |
| 8.6 | Widget — Activity Widget | Recent activities | ⬜ |
| 8.7 | Widget — System Health Widget | Quick health overview | ⬜ |
| 8.8 | Controller — Dashboard | Render dashboard with registered widgets | ⬜ |
| 8.9 | Controller — Widget Management | CRUD widgets per user/role | ⬜ |
| 8.10 | Service — DashboardService | Widget registry + rendering | ⬜ |

### Phase 9 — Developer Tools

**Mục tiêu:** API Keys, Webhooks, Queue Monitor, Cron Monitor

| # | Task | Mô tả | Trạng thái |
|---|------|-------|-----------|
| 9.1 | Migration — API Keys | token, name, abilities, expires_at, last_used_at | ⬜ |
| 9.2 | Migration — Webhooks | url, events, secret, status, retry_policy | ⬜ |
| 9.3 | Migration — Webhook Deliveries | webhook_id, event, payload, response, status, latency | ⬜ |
| 9.4 | Model — ApiKey | API key model | ⬜ |
| 9.5 | Model — Webhook | Webhook model | ⬜ |
| 9.6 | Model — WebhookDelivery | Delivery log model | ⬜ |
| 9.7 | Service — WebhookService | Dispatch + retry webhooks | ⬜ |
| 9.8 | Service — ApiKeyService | Create, revoke, validate API keys | ⬜ |
| 9.9 | Controller — API Keys | CRUD API keys | ⬜ |
| 9.10 | Controller — Webhooks | CRUD webhooks + delivery history | ⬜ |
| 9.11 | Controller — Queue Monitor | View jobs, failed jobs, retry | ⬜ |
| 9.12 | Controller — Cron Monitor | View scheduled tasks, last run, next run | ⬜ |
| 9.13 | Controller — API Logs | View API request logs | ⬜ |
| 9.14 | Middleware — ApiKeyAuth | Validate API key authentication | ⬜ |
| 9.15 | Middleware — RequestLogging | Log all API requests with Request ID | ⬜ |
| 9.16 | Developer — API | RESTful API endpoints | ⬜ |

### Phase 10 — Monitoring & Error Tracking

**Mục tiêu:** System health dashboard + error management

| # | Task | Mô tả | Trạng thái |
|---|------|-------|-----------|
| 10.1 | Service — HealthCheckService | Check DB, Redis, Queue, Storage, Mail | ⬜ |
| 10.2 | Controller — System Health | Health dashboard | ⬜ |
| 10.3 | Controller — Error Logs | List errors with stack traces | ⬜ |
| 10.4 | Controller — Server Monitor | Disk, Memory, CPU info | ⬜ |
| 10.5 | Migration — Error Logs | error tracking table | ⬜ |
| 10.6 | Handler — Error Handler | Custom exception handler with logging | ⬜ |
| 10.7 | Job — HealthCheckJob | Periodic health check job | ⬜ |
| 10.8 | Monitoring — API | RESTful API endpoints | ⬜ |

### Phase 11 — Export/Import Framework

**Mục tiêu:** Generic export/import cho mọi module

| # | Task | Mô tả | Trạng thái |
|---|------|-------|-----------|
| 11.1 | Service — ExportService | CSV, Excel, JSON export | ⬜ |
| 11.2 | Service — ImportService | CSV, Excel, JSON import | ⬜ |
| 11.3 | Job — ExportJob | Queue-based export cho大数据 | ⬜ |
| 11.4 | Job — ImportJob | Queue-based import | ⬜ |
| 11.5 | Trait — HasExport | Trait cho models | ⬜ |
| 11.6 | Trait — HasImport | Trait cho models | ⬜ |
| 11.7 | Controller — Export | Download exports | ⬜ |

### Phase 12 — Multi-language Support

**Mục tiêu:** i18n infrastructure cho admin

| # | Task | Mô tả | Trạng thái |
|---|------|-------|-----------|
| 12.1 | Config — Locale | Language config + switcher | ⬜ |
| 12.2 | Translation files — vi | Vietnamese translations | ⬜ |
| 12.3 | Translation files — en | English translations | ⬜ |
| 12.4 | Controller — Language Switcher | Switch language per session | ⬜ |
| 12.5 | Helper — __() | Translation helper | ⬜ |

### Phase 13 — API Platform

**Mục tiêu:** RESTful API hoàn chỉnh cho frontend/mobile

| # | Task | Mô tả | Trạng thái |
|---|------|-------|-----------|
| 13.1 | Routes — API v1 | Route organization cho API | ⬜ |
| 13.2 | Controller — Auth API | Login, logout, refresh, profile | ⬜ |
| 13.3 | Controller — User API | User CRUD API | ⬜ |
| 13.4 | Controller — Setting API | Settings API | ⬜ |
| 13.5 | Controller — Notification API | Notifications API | ⬜ |
| 13.6 | Controller — Media API | Media upload/list API | ⬜ |
| 13.7 | Request — API Requests | Form Request validation cho API | ⬜ |
| 13.8 | Resource — API Resources | API Resources/Transformers | ⬜ |
| 13.9 | Middleware — API Rate Limiting | Throttle middleware | ⬜ |
| 13.10 | Middleware — Request ID | UUID Request ID cho tracing | ⬜ |
| 13.11 | Documentation — API Docs | Swagger/OpenAPI documentation | ⬜ |

### Phase 14 — Blade CRUD Engine

**Mục tiêu:** Reusable CRUD components cho Blade

| # | Task | Mô tả | Trạng thái |
|---|------|-------|-----------|
| 14.1 | Component — DataTable | Reusable data table với search/filter/sort | ⬜ |
| 14.2 | Component — Form Builder | Dynamic form generation | ⬜ |
| 14.3 | Component — Filter Bar | Reusable filter components | ⬜ |
| 14.4 | Component — Bulk Actions | Bulk action dropdown | ⬜ |
| 14.5 | Component — Modal | Reusable modal dialogs | ⬜ |
| 14.6 | Trait — HasDataTable | Trait cho controllers | ⬜ |
| 14.7 | Interface — CrudResource | Contract cho CRUD resources | ⬜ |
| 14.8 | Example — UserResource | Example CRUD resource | ⬜ |

---

## Timeline ước tính

| Phase | Thời gian | Dependencies |
|-------|-----------|--------------|
| Phase 1 — Foundation | 5-7 days | None |
| Phase 2 — User Management | 3-4 days | Phase 1 |
| Phase 3 — RBAC Management | 2-3 days | Phase 1 |
| Phase 4 — System Settings | 3-4 days | Phase 1 |
| Phase 5 — Audit & Security | 2-3 days | Phase 1, 2 |
| Phase 6 — Media Library | 3-4 days | Phase 1 |
| Phase 7 — Notification | 3-4 days | Phase 1, 4 |
| Phase 8 — Dashboard | 2-3 days | Phase 1, 4 |
| Phase 9 — Developer Tools | 3-4 days | Phase 1, 5 |
| Phase 10 — Monitoring | 2-3 days | Phase 1 |
| Phase 11 — Export/Import | 2-3 days | Phase 1, 2 |
| Phase 12 — Multi-language | 1-2 days | Phase 4 |
| Phase 13 — API Platform | 4-5 days | Phase 1-5 |
| Phase 14 — CRUD Engine | 3-4 days | Phase 2, 3 |
| **Tổng** | **~40-55 days** | |

---

## Priority Order

```
P0 (Phải làm trước):
  Phase 1 → Phase 2 → Phase 3 → Phase 5

P1 (Quan trọng):
  Phase 4 → Phase 6 → Phase 7 → Phase 8

P2 (Nên làm):
  Phase 9 → Phase 10 → Phase 13

P3 (Khi cần):
  Phase 11 → Phase 12 → Phase 14
```

---

## Packages cần cài

```bash
# Auth & Security
composer require laravel/sanctum
composer require pragmarx/google2fa-laravel

# RBAC
composer require spatie/laravel-permission

# Audit
composer require spatie/laravel-activitylog

# Media
composer require intervention/image

# Export/Import
composer require maatwebsite/excel
composer require league/csv

# API Documentation
composer require darkaonline/l5-swagger

# Queue Monitor
composer require prezent/laravel-queue-monitor

# Health Check
composer require spatie/laravel-health
```

---

## Database Schema Overview

```
users
├── id, name, email, phone, avatar
├── password, remember_token
├── status (active, inactive, suspended)
├── email_verified_at
├── two_factor_secret, two_factor_enabled
├── last_login_at, last_login_ip
├── created_at, updated_at, deleted_at

roles
├── id, name, guard_name
├── created_at, updated_at

permissions
├── id, name, guard_name
├── created_at, updated_at

model_has_roles
├── role_id, model_type, model_id

model_has_permissions
├── permission_id, model_type, model_id

activity_log
├── id, log_name, description
├── subject_type, subject_id
├── event (created, updated, deleted)
├── causer_type, causer_id
├── properties (JSON: before, after)
├── batch_uuid, ip_address, user_agent
├── created_at

settings
├── id, key, value, group, type
├── description, created_at, updated_at

feature_flags
├── id, name, key, description
├── enabled, rollout_percentage
├── created_at, updated_at

notifications
├── id, type, notifiable_type, notifiable_id
├── data (JSON), read_at
├── created_at

notification_templates
├── id, name, subject, content
├── variables (JSON), language
├── status, created_at, updated_at

media
├── id, name, path, disk
├── mime_type, size
├── folder_id, metadata (JSON)
├── created_at, updated_at

api_keys
├── id, name, token, abilities
├── last_used_at, expires_at
├── created_at, updated_at

webhooks
├── id, name, url, events (JSON)
├── secret, status, retry_policy
├── last_delivery_at, created_at, updated_at

webhook_deliveries
├── id, webhook_id, event, payload
├── response, status_code
├── latency, created_at

error_logs
├── id, level, message
├── context (JSON), stack_trace
├── url, method, user_id
├── ip_address, user_agent
├── created_at

login_history
├── id, user_id, email
├── ip_address, user_agent
├── status (success, failed)
├── created_at

export_jobs
├── id, user_id, type
├── file_path, status
├── started_at, completed_at
├── created_at
```
