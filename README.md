# Demo User Manager

A Laravel + Livewire demo project for a multi-user login system with roles and permissions.

## Project goal

This project demonstrates a simple role-based access control (RBAC) flow where:

- users can authenticate
- users are assigned one or more roles
- roles are assigned one or more permissions
- routes are protected by permission-based middleware
- different users can access different sections of the app based on assigned permissions

## Tech stack

- PHP 8.3+
- Laravel 13
- Livewire 4
- TallStackUI
- SQLite for local demo database
- PHPUnit for test coverage

## Demo users

The database seeder creates a few ready-to-use demo accounts:

- Admin User
  - email: [admin@ex.com]<admin@ex.com>
  - password: 12345-passworD
  - permissions: all permissions

- User Editor
  - email: [editor@ex.com]<editor@ex.com>
  - password: 12345-passworD
  - permissions: CREATE_USER, DELETE_USER, UPDATE_USER

- RoleEditor
  - email: [role-editor@ex.com]<role-editor@ex.com>
  - password: 12345-passworD
  - permissions: CREATE_ROLE, DELETE_ROLE, UPDATE_ROLE

- Basic User
  - email: [basic@ex.com]<basic@ex.com>
  - password: 12345-passworD
  - permissions: none

## Permissions included

- CREATE_USER
- DELETE_USER
- UPDATE_USER
- EXPORT_USER_DATA
- CREATE_ROLE
- DELETE_ROLE
- UPDATE_ROLE

## Local setup

1. Install dependencies:

   ```bash
   composer install
   npm install
   ```

2. Copy environment file and generate app key:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Run migrations and seed demo data:

   ```bash
   php artisan migrate
   php artisan db:seed --class=PermissionsSeeder
   php artisan db:seed --class=DatabaseSeeder
   ```

4. Start the app:

   ```bash
   php artisan serve
   ```

5. Optional Vite frontend build/watch:

   ```bash
   npm run dev
   ```

## Route protection example

The main protected routes are defined in `routes/web.php`:

- `/accounts` → requires `CREATE_USER|DELETE_USER|UPDATE_USER`
- `/accounts/new` → requires `CREATE_USER`
- `/accounts/{user}/edit` → requires `UPDATE_USER`
- `/roles` → requires `CREATE_ROLE|DELETE_ROLE|UPDATE_ROLE`
- `/roles/new` → requires `CREATE_ROLE`
- `/roles/{role}/edit` → requires `UPDATE_ROLE`


## Testing

Run the focused tests:

```bash
php artisan test tests/Feature/PermissionMiddlewareTest.php tests/Feature/ProfilePhotoStorageTest.php
```

You can also run all tests:

```bash
php artisan test
```

## Notes

This is a demo project for learning and showcasing role-based access control in Laravel with Livewire. It is intentionally simple and suitable for educational or prototype use.
