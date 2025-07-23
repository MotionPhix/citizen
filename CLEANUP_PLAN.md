# Application Cleanup Plan

## Overview
Since you've migrated to Vue.js with Inertia, many Blade views and related files are no longer needed. This plan identifies what can be safely removed.

## Analysis Summary

### ✅ Keep (Still Used)
- **Email templates** (`resources/views/emails/`) - Used for notifications
- **Newsletter views** (`resources/views/newsletters/`) - Used by newsletter controllers
- **Main app template** (`resources/views/app.blade.php`) - Inertia entry point

### ❌ Remove (No longer used)
- **Page views** (`resources/views/pages/`) - Replaced by Vue components
- **Layout views** (`resources/views/layouts/`) - Replaced by Vue layouts  
- **Component views** (`resources/views/components/`) - Replaced by Vue components
- **Old newsletter models** - Replaced by unified NewsletterContent

## Files to Remove

### 1. Blade Views (No longer used with Vue)

#### Pages Directory
```
resources/views/pages/
├── home.blade.php              ❌ Remove (using Vue Home.vue)
├── about.blade.php             ❌ Remove (using Vue About.vue)
├── contact.blade.php           ❌ Remove (using Vue Contact.vue)
├── partnerships.blade.php      ❌ Remove (not referenced)
├── blogs/                      ❌ Remove entire directory
└── projects/                   ❌ Remove entire directory
```

#### Layouts Directory
```
resources/views/layouts/
└── app-layout.blade.php        ❌ Remove (using Vue layouts)
```

#### Components Directory
```
resources/views/components/
├── badge.blade.php             ❌ Remove
├── footer.blade.php            ❌ Remove
├── header.blade.php            ❌ Remove
├── dropdown.blade.php          ❌ Remove
├── nav-link.blade.php          ❌ Remove
├── blog-list.blade.php         ❌ Remove
├── blog-post.blade.php         ❌ Remove
├── blog-tags.blade.php         ❌ Remove
├── mode-switch.blade.php       ❌ Remove
├── impact-stats.blade.php      ❌ Remove
├── social-icons.blade.php      ❌ Remove
├── social-share.blade.php      ❌ Remove
├── testimonials.blade.php      ❌ Remove
├── donation-form.blade.php     ❌ Remove
├── dropdown-link.blade.php     ❌ Remove
├── hero-carousel.blade.php     ❌ Remove
├── blog-post-card.blade.php    ❌ Remove
├── mobile-nav-link.blade.php   ❌ Remove
├── project-gallery.blade.php   ❌ Remove
├── programs-section.blade.php  ❌ Remove
├── team-member-card.blade.php  ❌ Remove
├── content-container.blade.php ❌ Remove
├── counter-animation.blade.php ❌ Remove
├── icons/                      ❌ Remove entire directory
└── emails/                     ✅ Keep (used in email templates)
```

### 2. Old Newsletter Models (After unification)

#### Models
```
app/Models/
├── Story.php                   ❌ Remove (replaced by NewsletterContent)
├── Event.php                   ❌ Remove (replaced by NewsletterContent)  
└── Update.php                  ❌ Remove (replaced by NewsletterContent)
```

#### Filament Resources
```
app/Filament/Resources/
├── StoryResource.php           ❌ Remove
├── EventResource.php           ❌ Remove
├── UpdateResource.php          ❌ Remove
├── StoryResource/              ❌ Remove entire directory
├── EventResource/              ❌ Remove entire directory
└── UpdateResource/             ❌ Remove entire directory
```

#### Relation Managers
```
app/Filament/Resources/NewsletterIssueResource/RelationManagers/
├── StoriesRelationManager.php  ❌ Remove
├── UpdatesRelationManager.php  ❌ Remove
└── EventsRelationManager.php   ❌ Remove
```

### 3. Migrations (After data migration)

```
database/migrations/
├── 2025_04_20_051914_create_stories_table.php  ❌ Remove (after migration)
├── 2025_04_20_052002_create_updates_table.php  ❌ Remove (after migration)
└── 2025_04_20_052102_create_events_table.php   ❌ Remove (after migration)
```

### 4. Unused Controller Methods

Check these controllers for Blade-specific methods:
- Any methods returning `view()` instead of `Inertia::render()`
- Helper methods for Blade components

## Files to Keep

### ✅ Essential Files

#### Email Templates
```
resources/views/emails/
├── contact-form.blade.php           ✅ Keep
├── contact-auto-reply.blade.php     ✅ Keep
├── contact-form-response.blade.php  ✅ Keep
├── styles.blade.php                 ✅ Keep
└── newsletter/                      ✅ Keep entire directory
```

#### Newsletter Views
```
resources/views/newsletters/
├── preview.blade.php                ✅ Keep (newsletter preview)
├── feedback.php                     ✅ Keep (feedback form)
└── preferences.php                  ✅ Keep (subscriber preferences)
```

#### Core Templates
```
resources/views/
├── app.blade.php                    ✅ Keep (Inertia entry point)
└── vendor/                          ✅ Keep (package views)
```

## Cleanup Commands

### Step 1: Remove Blade Views
```bash
# Remove page views
rm -rf resources/views/pages/

# Remove layout views  
rm -rf resources/views/layouts/

# Remove component views (except emails)
rm -rf resources/views/components/icons/
rm resources/views/components/badge.blade.php
rm resources/views/components/footer.blade.php
rm resources/views/components/header.blade.php
rm resources/views/components/dropdown.blade.php
rm resources/views/components/nav-link.blade.php
rm resources/views/components/blog-list.blade.php
rm resources/views/components/blog-post.blade.php
rm resources/views/components/blog-tags.blade.php
rm resources/views/components/mode-switch.blade.php
rm resources/views/components/impact-stats.blade.php
rm resources/views/components/social-icons.blade.php
rm resources/views/components/social-share.blade.php
rm resources/views/components/testimonials.blade.php
rm resources/views/components/donation-form.blade.php
rm resources/views/components/dropdown-link.blade.php
rm resources/views/components/hero-carousel.blade.php
rm resources/views/components/blog-post-card.blade.php
rm resources/views/components/mobile-nav-link.blade.php
rm resources/views/components/project-gallery.blade.php
rm resources/views/components/programs-section.blade.php
rm resources/views/components/team-member-card.blade.php
rm resources/views/components/content-container.blade.php
rm resources/views/components/counter-animation.blade.php
rm resources/views/components/email-layout.blade.php
```

### Step 2: Remove Old Newsletter Models (After testing unified model)
```bash
# Remove old models
rm app/Models/Story.php
rm app/Models/Event.php  
rm app/Models/Update.php

# Remove old Filament resources
rm -rf app/Filament/Resources/StoryResource.php
rm -rf app/Filament/Resources/EventResource.php
rm -rf app/Filament/Resources/UpdateResource.php
rm -rf app/Filament/Resources/StoryResource/
rm -rf app/Filament/Resources/EventResource/
rm -rf app/Filament/Resources/UpdateResource/

# Remove old relation managers
rm app/Filament/Resources/NewsletterIssueResource/RelationManagers/StoriesRelationManager.php
rm app/Filament/Resources/NewsletterIssueResource/RelationManagers/UpdatesRelationManager.php
rm app/Filament/Resources/NewsletterIssueResource/RelationManagers/EventsRelationManager.php
```

### Step 3: Remove Old Migrations (After successful data migration)
```bash
# Only after confirming unified model works
rm database/migrations/2025_04_20_051914_create_stories_table.php
rm database/migrations/2025_04_20_052002_create_updates_table.php
rm database/migrations/2025_04_20_052102_create_events_table.php
```

## Safety Checklist

Before removing files, verify:

- [ ] All routes use Inertia::render() instead of view()
- [ ] Vue components exist for all removed Blade views
- [ ] Email functionality still works
- [ ] Newsletter preview/feedback/preferences still work
- [ ] Unified NewsletterContent model is working
- [ ] All data migrated successfully
- [ ] No broken imports or references

## Post-Cleanup Tasks

### 1. Update Imports
Remove any imports of deleted models/resources:
```php
// Remove these imports from controllers/other files
use App\Models\Story;
use App\Models\Event;
use App\Models\Update;
use App\Filament\Resources\StoryResource;
// etc.
```

### 2. Update NewsletterIssue Resource
```php
// In NewsletterIssueResource.php
public static function getRelations(): array
{
    return [
        ContentsRelationManager::class, // New unified manager
        // Remove old relation managers
    ];
}
```

### 3. Clear Caches
```bash
php artisan config:clear
php artisan view:clear
php artisan route:clear
composer dump-autoload
```

## Estimated Space Savings

- **Blade Views**: ~50+ files removed
- **Old Models**: 3 models + resources removed  
- **Migrations**: 3 migration files removed
- **Total**: Significant reduction in codebase complexity

## Risk Assessment

**Low Risk**: Blade view removal (Vue handles frontend)
**Medium Risk**: Old model removal (after data migration)
**High Risk**: Migration removal (backup first)

## Rollback Plan

1. **Git Backup**: Commit before cleanup
2. **Database Backup**: Before removing migrations
3. **File Backup**: Archive removed files temporarily
4. **Testing**: Verify all functionality after each step
