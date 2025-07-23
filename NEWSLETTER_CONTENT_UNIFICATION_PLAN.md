# Newsletter Content Unification Plan

## Current State Analysis

### Existing Models
You currently have three separate models for newsletter content:

1. **Story** - News articles/stories with title, excerpt, content, image, URL
2. **Event** - Calendar events with title, description, location, dates, registration
3. **Update** - General updates with title, excerpt, content, category, image, URL

### Problems with Current Approach
- **Redundancy**: All three models share similar fields (title, content, image, order)
- **Complexity**: Managing three separate resources in Filament
- **Confusion**: Unclear boundaries between what constitutes a "story" vs "update"
- **Maintenance**: Three separate forms, tables, and relation managers

## Recommended Solution: Unified NewsletterContent Model

### Option 1: Single Unified Model (Recommended)

Create a single `NewsletterContent` model that replaces all three:

```php
class NewsletterContent extends Model
{
    protected $fillable = [
        'newsletter_issue_id',
        'type',           // 'story', 'event', 'update', 'announcement'
        'title',
        'excerpt',
        'content',
        'image',
        'url',
        'category',       // For updates: 'news', 'announcements', etc.
        'metadata',       // JSON field for type-specific data
        'published_at',
        'order',
        'is_featured',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'metadata' => 'array',
        'is_featured' => 'boolean',
    ];
}
```

### Metadata Field Usage

The `metadata` JSON field would store type-specific information:

**For Events:**
```json
{
    "location": "Conference Center",
    "start_date": "2024-01-15T09:00:00Z",
    "end_date": "2024-01-15T17:00:00Z",
    "registration_url": "https://example.com/register",
    "capacity": 100
}
```

**For Stories:**
```json
{
    "author": "John Doe",
    "read_time": 5,
    "source": "Internal"
}
```

**For Updates:**
```json
{
    "priority": "high",
    "department": "IT",
    "effective_date": "2024-01-01"
}
```

## Implementation Plan

### Phase 1: Create Unified Model

1. **Create Migration**
```php
Schema::create('newsletter_contents', function (Blueprint $table) {
    $table->id();
    $table->foreignId('newsletter_issue_id')->constrained()->cascadeOnDelete();
    $table->enum('type', ['story', 'event', 'update', 'announcement']);
    $table->string('title');
    $table->text('excerpt')->nullable();
    $table->longText('content')->nullable();
    $table->string('image')->nullable();
    $table->string('url')->nullable();
    $table->string('category')->nullable();
    $table->json('metadata')->nullable();
    $table->timestamp('published_at')->nullable();
    $table->integer('order')->default(0);
    $table->boolean('is_featured')->default(false);
    $table->timestamps();
    
    $table->index(['newsletter_issue_id', 'type']);
    $table->index(['type', 'order']);
});
```

2. **Create Unified Model**
```php
class NewsletterContent extends Model
{
    // Scopes for different types
    public function scopeStories($query) {
        return $query->where('type', 'story');
    }
    
    public function scopeEvents($query) {
        return $query->where('type', 'event');
    }
    
    public function scopeUpdates($query) {
        return $query->where('type', 'update');
    }
    
    // Accessors for metadata
    public function getEventDataAttribute() {
        return $this->type === 'event' ? $this->metadata : null;
    }
    
    public function getLocationAttribute() {
        return $this->event_data['location'] ?? null;
    }
    
    public function getStartDateAttribute() {
        return $this->event_data['start_date'] ?? null;
    }
}
```

### Phase 2: Create Unified Filament Resource

```php
class NewsletterContentResource extends Resource
{
    protected static ?string $model = NewsletterContent::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Newsletter';
    
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('newsletter_issue_id')
                ->relationship('newsletterIssue', 'title')
                ->required(),
                
            Forms\Components\Select::make('type')
                ->options([
                    'story' => 'Story',
                    'event' => 'Event', 
                    'update' => 'Update',
                    'announcement' => 'Announcement',
                ])
                ->required()
                ->reactive(),
                
            Forms\Components\TextInput::make('title')
                ->required(),
                
            Forms\Components\Textarea::make('excerpt')
                ->visible(fn ($get) => in_array($get('type'), ['story', 'update'])),
                
            Forms\Components\RichEditor::make('content')
                ->columnSpanFull(),
                
            // Event-specific fields
            Forms\Components\Section::make('Event Details')
                ->schema([
                    Forms\Components\TextInput::make('metadata.location')
                        ->label('Location'),
                    Forms\Components\DateTimePicker::make('metadata.start_date')
                        ->label('Start Date'),
                    Forms\Components\DateTimePicker::make('metadata.end_date')
                        ->label('End Date'),
                    Forms\Components\TextInput::make('metadata.registration_url')
                        ->label('Registration URL')
                        ->url(),
                ])
                ->visible(fn ($get) => $get('type') === 'event'),
                
            // Update-specific fields  
            Forms\Components\Select::make('category')
                ->options([
                    'news' => 'News',
                    'announcements' => 'Announcements',
                    'updates' => 'Updates',
                ])
                ->visible(fn ($get) => $get('type') === 'update'),
                
            Forms\Components\FileUpload::make('image')
                ->image()
                ->directory('newsletter/content'),
                
            Forms\Components\TextInput::make('url')
                ->url(),
                
            Forms\Components\TextInput::make('order')
                ->numeric()
                ->default(0),
                
            Forms\Components\Toggle::make('is_featured'),
        ]);
    }
}
```

### Phase 3: Migration Strategy

1. **Data Migration Script**
```php
// Migrate existing data
$stories = Story::all();
foreach ($stories as $story) {
    NewsletterContent::create([
        'newsletter_issue_id' => $story->newsletter_issue_id,
        'type' => 'story',
        'title' => $story->title,
        'excerpt' => $story->excerpt,
        'content' => $story->content,
        'image' => $story->image,
        'url' => $story->url,
        'published_at' => $story->published_at,
        'order' => $story->order,
    ]);
}

// Similar for Events and Updates...
```

2. **Remove Old Resources**
- Delete StoryResource, EventResource, UpdateResource
- Remove old relation managers
- Drop old tables after data migration

## Benefits of Unified Approach

### 1. Simplified Management
- **Single Resource**: One Filament resource instead of three
- **Unified Interface**: Consistent form fields and table columns
- **Better Organization**: All newsletter content in one place

### 2. Improved Flexibility
- **Easy Type Addition**: Add new content types without new models
- **Dynamic Forms**: Form fields change based on content type
- **Consistent Ordering**: Single ordering system across all content

### 3. Better User Experience
- **Less Confusion**: Clear content type selection
- **Streamlined Workflow**: Create all content from one place
- **Better Overview**: See all newsletter content at once

### 4. Easier Maintenance
- **Single Codebase**: One set of forms, tables, and logic
- **Consistent Validation**: Same validation rules across types
- **Simplified Testing**: Test one resource instead of three

## Alternative: Keep Separate Models with Shared Interface

If you prefer to keep the models separate, we can create a shared interface:

```php
interface NewsletterContentInterface
{
    public function getTitle(): string;
    public function getExcerpt(): ?string;
    public function getContent(): ?string;
    public function getImage(): ?string;
    public function getOrder(): int;
    public function getType(): string;
}

// Then create a unified resource that works with all three models
class UnifiedNewsletterContentResource extends Resource
{
    // Handle all three models through polymorphic relationships
}
```

## Recommendation

**Go with Option 1 (Single Unified Model)** because:

1. **Simplicity**: Much easier to manage and understand
2. **Flexibility**: Easy to add new content types
3. **Performance**: Better database queries and caching
4. **Maintenance**: Single point of truth for newsletter content
5. **User Experience**: Cleaner admin interface

The metadata JSON field provides all the flexibility you need for type-specific data while keeping the core structure simple and unified.

## Next Steps

1. **Review and approve** this unification plan
2. **Create the unified model** and migration
3. **Build the new Filament resource** with dynamic forms
4. **Migrate existing data** from the three separate tables
5. **Update newsletter templates** to use the unified model
6. **Remove old models and resources** after testing

This approach will significantly simplify your newsletter content management while maintaining all current functionality.
