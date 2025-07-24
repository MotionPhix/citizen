<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TestUsersSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Ensure roles exist (in case they haven't been seeded yet)
    $this->ensureRolesExist();

    // Create test users with different roles
    $testUsers = [
      [
        'name' => 'Super Admin User',
        'email' => 'superadmin@test.com',
        'password' => Hash::make('password123'),
        'role' => 'super_admin',
        'description' => 'Full access to everything - can manage all resources, users, and system settings'
      ],
      [
        'name' => 'Content Manager User',
        'email' => 'content@test.com',
        'password' => Hash::make('password123'),
        'role' => 'content_manager',
        'description' => 'Manages blogs, newsletters, comments, and subscribers - cannot manage users or system settings'
      ],
      [
        'name' => 'Editor User',
        'email' => 'editor@test.com',
        'password' => Hash::make('password123'),
        'role' => 'editor',
        'description' => 'Can create and edit content but limited delete permissions - read-only access to newsletters'
      ],
      [
        'name' => 'Project Manager User',
        'email' => 'projects@test.com',
        'password' => Hash::make('password123'),
        'role' => 'project_manager',
        'description' => 'Manages projects and impact metrics - limited access to other content'
      ],
      [
        'name' => 'Subscriber Manager User',
        'email' => 'subscribers@test.com',
        'password' => Hash::make('password123'),
        'role' => 'subscriber_manager',
        'description' => 'Manages subscribers, newsletters, and contact submissions'
      ],
      [
        'name' => 'Viewer User',
        'email' => 'viewer@test.com',
        'password' => Hash::make('password123'),
        'role' => 'viewer',
        'description' => 'Read-only access to all content - cannot create, edit, or delete anything'
      ]
    ];

    foreach ($testUsers as $userData) {
      // Check if user already exists
      $existingUser = User::where('email', $userData['email'])->first();

      if ($existingUser) {
        $this->command->info("User {$userData['email']} already exists, updating role...");
        $user = $existingUser;
      } else {
        // Create new user
        $user = User::create([
          'name' => $userData['name'],
          'email' => $userData['email'],
          'password' => $userData['password'],
          'email_verified_at' => now(),
        ]);
        $this->command->info("Created user: {$userData['email']}");
      }

      // Assign role
      $role = Role::where('name', $userData['role'])->first();
      if ($role) {
        $user->syncRoles([$userData['role']]);
        $this->command->info("Assigned role '{$userData['role']}' to {$userData['email']}");
      } else {
        $this->command->error("Role '{$userData['role']}' not found for user {$userData['email']}");
      }
    }

    $this->command->info('');
    $this->command->info('=== TEST USERS CREATED ===');
    $this->command->info('');
    $this->command->info('Login credentials for testing:');
    $this->command->info('Password for all users: password123');
    $this->command->info('');

    foreach ($testUsers as $userData) {
      $this->command->info("📧 {$userData['email']} ({$userData['role']})");
      $this->command->info("   → {$userData['description']}");
      $this->command->info('');
    }

    $this->command->info('=== TESTING INSTRUCTIONS ===');
    $this->command->info('');
    $this->command->info('1. Visit /admin to access the Filament admin panel');
    $this->command->info('2. Login with different users to test their permissions');
    $this->command->info('3. Check what resources each role can access:');
    $this->command->info('   - Navigation menu items');
    $this->command->info('   - Create/Edit/Delete buttons');
    $this->command->info('   - Bulk actions');
    $this->command->info('   - Widget visibility');
    $this->command->info('');
    $this->command->info('Expected Access Levels:');
    $this->command->info('');
    $this->command->info('🔴 SUPER ADMIN: Full access to everything');
    $this->command->info('🟠 CONTENT MANAGER: Blogs, Comments, Newsletters, Subscribers');
    $this->command->info('🟡 EDITOR: Limited blog/newsletter editing, no delete permissions');
    $this->command->info('🟢 PROJECT MANAGER: Projects, Impact Metrics, limited other access');
    $this->command->info('🔵 SUBSCRIBER MANAGER: Subscribers, Newsletters, Contact Messages');
    $this->command->info('⚪ VIEWER: Read-only access to all content');
  }

  /**
   * Ensure all required roles exist
   */
  private function ensureRolesExist(): void
  {
    $requiredRoles = [
      'super_admin',
      'admin',
      'content_manager',
      'editor',
      'project_manager',
      'subscriber_manager',
      'viewer'
    ];

    foreach ($requiredRoles as $roleName) {
      if (!Role::where('name', $roleName)->exists()) {
        $this->command->warn("Role '{$roleName}' does not exist. Please run RolesAndPermissionsSeeder first.");
      }
    }
  }
}
