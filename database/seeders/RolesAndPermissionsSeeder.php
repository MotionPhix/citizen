<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
  public function run(): void
  {
    // Reset cached roles and permissions
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // Create permissions
    $permissions = [
      // Blog permissions
      'view blogs',
      'create blogs',
      'edit blogs',
      'delete blogs',
      'publish blogs',

      // Comment permissions
      'view comments',
      'create comments',
      'edit comments',
      'delete comments',
      'moderate comments',

      // User management
      'view users',
      'create users',
      'edit users',
      'delete users',

      // Impact metrics
      'view metrics',
      'edit metrics',

      // Media management
      'upload media',
      'manage media',

      // Settings
      'manage settings',

      // Newsletter
      'manage newsletter',
      'send newsletter',

      // Projects
      'view projects',
      'create projects',
      'edit projects',
      'delete projects',
    ];

    foreach ($permissions as $permission) {
      Permission::create(['name' => $permission]);
    }

    // Create roles and assign permissions

    // Super Admin
    $superAdminRole = Role::create(['name' => 'super-admin']);
    $superAdminRole->givePermissionTo(Permission::all());

    // Admin
    $contentManagerRole = Role::create(['name' => 'admin']);
    $contentManagerRole->givePermissionTo([
      'view blogs',
      'create blogs',
      'edit blogs',
      'delete blogs',
      'publish blogs',
      'view comments',
      'moderate comments',
      'upload media',
      'manage media',
      'view metrics',
      'view projects',
      'view users',
      'create users',
      'edit users',
      'delete users',
    ]);

    // Content Manager
    $contentManagerRole = Role::create(['name' => 'content-manager']);
    $contentManagerRole->givePermissionTo([
      'view blogs',
      'create blogs',
      'edit blogs',
      'delete blogs',
      'publish blogs',
      'view comments',
      'moderate comments',
      'upload media',
      'manage media',
      'view metrics',
      'view projects',
    ]);

    // Editor
    $editorRole = Role::create(['name' => 'editor']);
    $editorRole->givePermissionTo([
      'view blogs',
      'create blogs',
      'edit blogs',
      'view comments',
      'moderate comments',
      'upload media',
    ]);

    // Project Manager
    $projectManagerRole = Role::create(['name' => 'project-manager']);
    $projectManagerRole->givePermissionTo([
      'view projects',
      'create projects',
      'edit projects',
      'delete projects',
      'view metrics',
      'edit metrics',
    ]);

    // Basic User
    $userRole = Role::create(['name' => 'user']);
    $userRole->givePermissionTo([
      'view blogs',
      'create comments',
      'edit comments',
    ]);

    // Assign super-admin role to the first user
    $admin = User::whereEmail('admin@example.com')->first();

    if ($admin) {
      $admin->assignRole('super-admin');
    }
  }
}
