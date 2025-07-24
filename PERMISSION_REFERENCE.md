# Permission Reference Guide

## Permission Naming Convention

All permissions in this application use **underscore notation** (snake_case), not spaces. This is consistent with Filament Shield's auto-generated permissions.

## Available Permissions

### Blog Permissions
- `view_blog`
- `view_any_blog`
- `create_blog`
- `update_blog`
- `delete_blog`
- `delete_any_blog`
- `force_delete_blog`
- `force_delete_any_blog`
- `restore_blog`
- `restore_any_blog`
- `replicate_blog`
- `reorder_blog`

### Comment Permissions
- `view_comment`
- `view_any_comment`
- `create_comment`
- `update_comment`
- `delete_comment`
- `delete_any_comment`
- `force_delete_comment`
- `force_delete_any_comment`
- `restore_comment`
- `restore_any_comment`
- `replicate_comment`
- `reorder_comment`

### User Permissions
- `view_user`
- `view_any_user`
- `create_user`
- `update_user`
- `delete_user`
- `delete_any_user`
- `force_delete_user`
- `force_delete_any_user`
- `restore_user`
- `restore_any_user`
- `replicate_user`
- `reorder_user`

### Role Permissions
- `view_role`
- `view_any_role`
- `create_role`
- `update_role`
- `delete_role`
- `delete_any_role`
- `force_delete_role`
- `force_delete_any_role`
- `restore_role`
- `restore_any_role`
- `replicate_role`
- `reorder_role`

### Project Permissions
- `view_project`
- `view_any_project`
- `create_project`
- `update_project`
- `delete_project`
- `delete_any_project`
- `force_delete_project`
- `force_delete_any_project`
- `restore_project`
- `restore_any_project`
- `replicate_project`
- `reorder_project`

### Newsletter Content Permissions
- `view_newsletter::content`
- `view_any_newsletter::content`
- `create_newsletter::content`
- `update_newsletter::content`
- `delete_newsletter::content`
- `delete_any_newsletter::content`
- `force_delete_newsletter::content`
- `force_delete_any_newsletter::content`
- `restore_newsletter::content`
- `restore_any_newsletter::content`
- `replicate_newsletter::content`
- `reorder_newsletter::content`

### Newsletter Issue Permissions
- `view_newsletter::issue`
- `view_any_newsletter::issue`
- `create_newsletter::issue`
- `update_newsletter::issue`
- `delete_newsletter::issue`
- `delete_any_newsletter::issue`
- `force_delete_newsletter::issue`
- `force_delete_any_newsletter::issue`
- `restore_newsletter::issue`
- `restore_any_newsletter::issue`
- `replicate_newsletter::issue`
- `reorder_newsletter::issue`

### Subscriber Permissions
- `view_subscriber`
- `view_any_subscriber`
- `create_subscriber`
- `update_subscriber`
- `delete_subscriber`
- `delete_any_subscriber`
- `force_delete_subscriber`
- `force_delete_any_subscriber`
- `restore_subscriber`
- `restore_any_subscriber`
- `replicate_subscriber`
- `reorder_subscriber`

### Impact Metric Permissions
- `view_impact::metric`
- `view_any_impact::metric`
- `create_impact::metric`
- `update_impact::metric`
- `delete_impact::metric`
- `delete_any_impact::metric`
- `force_delete_impact::metric`
- `force_delete_any_impact::metric`
- `restore_impact::metric`
- `restore_any_impact::metric`
- `replicate_impact::metric`
- `reorder_impact::metric`

### Contact Submission Permissions
- `view_contact::submission`
- `view_any_contact::submission`
- `create_contact::submission`
- `update_contact::submission`
- `delete_contact::submission`
- `delete_any_contact::submission`
- `force_delete_contact::submission`
- `force_delete_any_contact::submission`
- `restore_contact::submission`
- `restore_any_contact::submission`
- `replicate_contact::submission`
- `reorder_contact::submission`

### Widget Permissions
- `widget_BlogOverviewStats`
- `widget_EngagementStats`
- `widget_NewsletterIssueStats`
- `widget_ProjectOverviewStats`
- `widget_SubscriberStats`

### System Permissions
- `access_admin_panel`
- `manage_system_settings`

## Usage Examples

### ✅ Correct Usage
```php
// Single permission check
$user->hasPermissionTo('create_blog');
$user->hasPermissionTo('update_comment');
$user->hasPermissionTo('access_admin_panel');

// Multiple permission check
$user->hasAnyPermission(['create_blog', 'update_blog', 'delete_blog']);
$user->hasAllPermissions(['view_user', 'update_user']);
```

### ❌ Incorrect Usage (DO NOT USE)
```php
// These will NOT work - permissions don't exist with spaces
$user->hasPermissionTo('create blog');
$user->hasPermissionTo('edit blog');
$user->hasPermissionTo('moderate comments');
$user->hasAnyPermission(['create blogs', 'edit blogs']);
```

## Role Assignments

Roles are defined in `database/seeders/RolesAndPermissionsSeeder.php` with specific permission sets:

- **super_admin**: All permissions
- **admin**: Most permissions except super admin management
- **content_manager**: Blog, comment, newsletter, subscriber management
- **editor**: Limited content creation/editing
- **project_manager**: Project and impact metric management
- **subscriber_manager**: Subscriber and newsletter management
- **viewer**: Read-only access to all content

## Important Notes

1. **Always use underscores** in permission names, never spaces
2. **Resource-specific permissions** follow the pattern: `action_resource` (e.g., `create_blog`)
3. **Namespaced permissions** use `::` for complex resources (e.g., `view_newsletter::content`)
4. **Widget permissions** start with `widget_` prefix
5. **System permissions** are custom and don't follow the standard CRUD pattern

## Regenerating Permissions

If you add new resources or modify existing ones, regenerate permissions with:

```bash
php artisan shield:generate --all
```

Then update the `RolesAndPermissionsSeeder.php` to include any new permissions in the appropriate roles.
