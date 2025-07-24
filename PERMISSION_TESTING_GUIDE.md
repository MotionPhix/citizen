# Permission Testing Guide

## Test Users Created

The following test users have been created with different roles to test the permission system:

### 🔴 Super Admin
- **Email:** `superadmin@test.com`
- **Password:** `password123`
- **Role:** `super_admin`
- **Expected Access:** Full access to everything

### 🟠 Content Manager
- **Email:** `content@test.com`
- **Password:** `password123`
- **Role:** `content_manager`
- **Expected Access:** Blogs, Comments, Newsletters, Subscribers

### 🟡 Editor
- **Email:** `editor@test.com`
- **Password:** `password123`
- **Role:** `editor`
- **Expected Access:** Limited blog/newsletter editing, no delete permissions

### 🟢 Project Manager
- **Email:** `projects@test.com`
- **Password:** `password123`
- **Role:** `project_manager`
- **Expected Access:** Projects, Impact Metrics, limited other access

### 🔵 Subscriber Manager
- **Email:** `subscribers@test.com`
- **Password:** `password123`
- **Role:** `subscriber_manager`
- **Expected Access:** Subscribers, Newsletters, Contact Messages

### ⚪ Viewer
- **Email:** `viewer@test.com`
- **Password:** `password123`
- **Role:** `viewer`
- **Expected Access:** Read-only access to all content

## Testing Checklist

### For Each User Role, Test:

#### Navigation Menu
- [ ] Which resources appear in the navigation
- [ ] Which navigation groups are visible
- [ ] Badge counts on navigation items

#### Resource Access
- [ ] Can view resource list pages
- [ ] Can view individual records
- [ ] Can create new records
- [ ] Can edit existing records
- [ ] Can delete records
- [ ] Can bulk delete records

#### Specific Actions to Test
- [ ] Create blog post
- [ ] Edit blog post
- [ ] Delete blog post
- [ ] Moderate comments
- [ ] Create newsletter issue
- [ ] Add newsletter content
- [ ] Manage subscribers
- [ ] View contact submissions
- [ ] Respond to contact submissions
- [ ] Create/edit projects
- [ ] Manage impact metrics
- [ ] Access user management
- [ ] Access role management

#### Widget Visibility
- [ ] Dashboard widgets visible
- [ ] Widget data accessible
- [ ] Widget actions available

## Expected Results by Role

### Super Admin (`superadmin@test.com`)
- ✅ All navigation items visible
- ✅ All CRUD operations available
- ✅ User and role management
- ✅ System settings access
- ✅ All widgets visible

### Content Manager (`content@test.com`)
- ✅ Content navigation group (Blogs, Comments)
- ✅ Newsletter navigation group (Issues, Content)
- ✅ Communications navigation group (Subscribers, Contact Messages)
- ✅ Full CRUD on blogs, comments, newsletters, subscribers
- ❌ No user/role management
- ❌ No system settings
- ✅ Content-related widgets

### Editor (`editor@test.com`)
- ✅ Content navigation group (Blogs, Comments)
- ✅ Newsletter navigation group (limited)
- ✅ Can create/edit blogs and comments
- ❌ Limited delete permissions
- ❌ Cannot create newsletter issues
- ❌ No user management
- ✅ Basic content widgets

### Project Manager (`projects@test.com`)
- ✅ Projects navigation group
- ✅ Analytics navigation group (Impact Metrics)
- ✅ Limited content viewing
- ✅ Full project and metrics management
- ❌ No newsletter management
- ❌ No user management
- ✅ Project-related widgets

### Subscriber Manager (`subscribers@test.com`)
- ✅ Newsletter navigation group
- ✅ Communications navigation group
- ✅ Full subscriber management
- ✅ Newsletter content creation
- ✅ Contact submission management
- ❌ No blog management
- ❌ No user management
- ✅ Subscriber-related widgets

### Viewer (`viewer@test.com`)
- ✅ All navigation items visible
- ✅ Can view all content
- ❌ No create buttons
- ❌ No edit buttons
- ❌ No delete buttons
- ❌ No bulk actions
- ✅ All widgets visible (read-only)

## How to Test

1. **Run the seeders:**
   ```bash
   php artisan migrate:fresh --seed
   ```

2. **Access the admin panel:**
   Visit `/admin` in your browser

3. **Login with each test user:**
   Use the credentials above to test each role

4. **Check navigation:**
   - Note which menu items appear
   - Check navigation groups
   - Look for badge counts

5. **Test resource access:**
   - Try to access each resource
   - Check available actions (Create, Edit, Delete)
   - Test bulk actions

6. **Test specific permissions:**
   - Try to perform actions that should be restricted
   - Verify error messages or redirects

7. **Check widgets:**
   - Note which widgets appear on dashboard
   - Test widget functionality

## Common Issues to Look For

- ❌ Users seeing resources they shouldn't have access to
- ❌ Missing navigation items for allowed resources
- ❌ Create/Edit/Delete buttons appearing when they shouldn't
- ❌ Bulk actions available to restricted users
- ❌ Widgets showing for users without proper permissions
- ❌ Error messages when accessing restricted content

## Sample Data Available

The seeders create sample data for testing:
- 3 blog posts with comments
- 3 projects (active and completed)
- 3 newsletter issues with content
- 5 subscribers (including unsubscribed)
- 3 contact submissions
- 3 impact metrics

This provides realistic data to test permissions against.
