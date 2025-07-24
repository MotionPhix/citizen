# Anonymous Comment System Guide

## Overview

The comment system has been completely reworked to support realistic anonymous commenting. Users can now comment without creating an account by simply providing their name and email address.

## Key Features

### ✅ **Anonymous Commenting**
- Users can comment without registration
- Only requires name and email
- Optional website field
- Gravatar integration for profile pictures

### ✅ **Smart Moderation**
- Automatic spam detection with scoring
- Configurable auto-approval rules
- Manual moderation interface in admin panel
- Different statuses: pending, approved, spam, trash

### ✅ **Email Notifications**
- Users get notified when someone replies to their comment
- Unsubscribe functionality
- Email-based reply system (optional)

### ✅ **Rate Limiting**
- Prevents spam with IP-based rate limiting
- Configurable limits (default: 5 comments per hour)

### ✅ **Enhanced Admin Interface**
- View anonymous vs authenticated comments
- Bulk moderation actions
- Spam score indicators
- Comment approval workflow

## API Endpoints

### Get Comments
```
GET /blogs/{slug}/comments
```

### Post Comment
```
POST /blogs/{slug}/comments
Content-Type: application/json

{
  "content": "Your comment here",
  "author_name": "John Doe",           // Required for anonymous
  "author_email": "john@example.com",  // Required for anonymous
  "author_website": "https://john.com", // Optional
  "parent_id": 123,                    // Optional, for replies
  "notify_on_reply": true              // Optional, default true
}
```

### Update Comment
```
PUT /comments/{id}
Content-Type: application/json

{
  "content": "Updated comment content"
}
```

### Delete Comment
```
DELETE /comments/{id}
```

## Frontend Integration Example

### Basic Comment Form (HTML)
```html
<form id="comment-form" data-blog-slug="your-blog-slug">
  <div class="form-group">
    <label for="author_name">Name *</label>
    <input type="text" id="author_name" name="author_name" required>
  </div>
  
  <div class="form-group">
    <label for="author_email">Email *</label>
    <input type="email" id="author_email" name="author_email" required>
  </div>
  
  <div class="form-group">
    <label for="author_website">Website</label>
    <input type="url" id="author_website" name="author_website">
  </div>
  
  <div class="form-group">
    <label for="content">Comment *</label>
    <textarea id="content" name="content" rows="4" required minlength="10" maxlength="2000"></textarea>
  </div>
  
  <div class="form-group">
    <label>
      <input type="checkbox" name="notify_on_reply" checked>
      Notify me of replies via email
    </label>
  </div>
  
  <button type="submit">Post Comment</button>
</form>
```

### JavaScript Integration
```javascript
document.getElementById('comment-form').addEventListener('submit', async function(e) {
  e.preventDefault();
  
  const formData = new FormData(this);
  const blogSlug = this.dataset.blogSlug;
  
  const data = {
    content: formData.get('content'),
    author_name: formData.get('author_name'),
    author_email: formData.get('author_email'),
    author_website: formData.get('author_website'),
    notify_on_reply: formData.get('notify_on_reply') === 'on'
  };
  
  try {
    const response = await fetch(`/blogs/${blogSlug}/comments`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify(data)
    });
    
    const result = await response.json();
    
    if (response.ok) {
      alert(result.message);
      this.reset();
      // Optionally reload comments or add the new comment to the DOM
    } else {
      alert(result.message || 'Failed to post comment');
    }
  } catch (error) {
    alert('Network error. Please try again.');
  }
});
```

## Configuration

Edit `config/comments.php` to customize:

```php
return [
    // Auto-approve comments from authenticated users
    'auto_approve_authenticated' => true,
    
    // Auto-approve anonymous comments (not recommended)
    'auto_approve_anonymous' => false,
    
    // Maximum spam score for auto-approval
    'max_spam_score_for_approval' => 0.3,
    
    // Rate limiting
    'rate_limit_per_hour' => 5,
    
    // Comment length limits
    'min_comment_length' => 10,
    'max_comment_length' => 2000,
    
    // Email notifications
    'email_notifications_enabled' => true,
    
    // And many more options...
];
```

## Admin Panel Features

### Comment Management
1. **Navigate to Comments** in the admin panel
2. **View all comments** with status indicators
3. **Filter by status**: pending, approved, spam, trash
4. **Filter by type**: anonymous vs authenticated
5. **Bulk actions**: approve, reject, mark as spam
6. **Individual actions**: approve, reject, edit, delete

### Comment Moderation Workflow
1. **New comments** appear with "pending" status
2. **Review content** and spam score
3. **Approve** good comments
4. **Reject** inappropriate comments
5. **Mark as spam** obvious spam

### Spam Detection
- **Automatic scoring** based on content analysis
- **Keyword detection** for common spam terms
- **Link analysis** (too many links = higher spam score)
- **Email domain checking** for suspicious domains
- **Manual override** always available

## Sample Data

The system includes sample comments:
- ✅ **Authenticated comment** from test user
- ✅ **Approved anonymous comment** from "Sarah Johnson"
- ✅ **Pending anonymous comment** from "Mike Wilson"

## Testing the System

1. **Visit a blog post** on your frontend
2. **Try posting a comment** without logging in
3. **Check the admin panel** to see the comment in moderation
4. **Approve the comment** and see it appear on the frontend
5. **Test email notifications** by replying to a comment

## Security Features

- ✅ **Rate limiting** prevents spam
- ✅ **Input validation** prevents malicious content
- ✅ **Spam detection** automatically flags suspicious content
- ✅ **IP tracking** for moderation purposes
- ✅ **CSRF protection** on all forms
- ✅ **XSS protection** through proper escaping

## Email Notifications

When someone replies to a comment:
1. **Original commenter gets an email** with the reply
2. **Email includes unsubscribe link** to stop notifications
3. **Email includes link** to view full conversation
4. **Notifications are queued** for better performance

## Migration Notes

If you have existing comments:
- ✅ **Old comments are preserved** with `user_id`
- ✅ **New fields are nullable** so no data loss
- ✅ **Backward compatibility** maintained
- ✅ **Gradual migration** possible

This new system provides a much more realistic and user-friendly commenting experience while maintaining security and moderation capabilities!
