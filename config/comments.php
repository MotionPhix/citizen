<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Comment Moderation Settings
    |--------------------------------------------------------------------------
    |
    | These settings control how comments are moderated and approved.
    |
    */

    // Auto-approve comments from authenticated users
    'auto_approve_authenticated' => true,

    // Auto-approve comments from anonymous users (not recommended)
    'auto_approve_anonymous' => false,

    // Maximum spam score for auto-approval (0.0 to 1.0)
    'max_spam_score_for_approval' => 0.3,

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Control how many comments users can post within a time period.
    |
    */

    // Maximum comments per hour per IP address
    'rate_limit_per_hour' => 5,

    // Rate limit window in seconds
    'rate_limit_window' => 3600, // 1 hour

    /*
    |--------------------------------------------------------------------------
    | Comment Validation
    |--------------------------------------------------------------------------
    |
    | Validation rules for comment content and author information.
    |
    */

    // Minimum comment length
    'min_comment_length' => 10,

    // Maximum comment length
    'max_comment_length' => 2000,

    // Maximum author name length
    'max_author_name_length' => 100,

    // Allow HTML in comments
    'allow_html' => false,

    // Allowed HTML tags if HTML is enabled
    'allowed_html_tags' => '<p><br><strong><em><a><ul><ol><li>',

    /*
    |--------------------------------------------------------------------------
    | Spam Detection
    |--------------------------------------------------------------------------
    |
    | Settings for automatic spam detection.
    |
    */

    // Enable spam detection
    'spam_detection_enabled' => true,

    // Spam keywords (will increase spam score)
    'spam_keywords' => [
        'viagra', 'casino', 'lottery', 'bitcoin', 'cryptocurrency',
        'make money', 'click here', 'buy now', 'limited time',
        'free money', 'work from home', 'get rich quick'
    ],

    // Suspicious email domains
    'suspicious_email_domains' => [
        'tempmail.com', '10minutemail.com', 'guerrillamail.com',
        'mailinator.com', 'throwaway.email', 'temp-mail.org'
    ],

    // Maximum number of links allowed in a comment
    'max_links_per_comment' => 2,

    /*
    |--------------------------------------------------------------------------
    | Email Notifications
    |--------------------------------------------------------------------------
    |
    | Settings for email notifications when comments receive replies.
    |
    */

    // Enable email notifications for comment replies
    'email_notifications_enabled' => true,

    // From email address for notifications
    'notification_from_email' => env('MAIL_FROM_ADDRESS', 'noreply@example.com'),

    // From name for notifications
    'notification_from_name' => env('MAIL_FROM_NAME', 'Blog Comments'),

    /*
    |--------------------------------------------------------------------------
    | Comment Display
    |--------------------------------------------------------------------------
    |
    | Settings for how comments are displayed on the frontend.
    |
    */

    // Number of comments to show per page
    'comments_per_page' => 20,

    // Default comment order ('asc' or 'desc')
    'default_order' => 'desc',

    // Show Gravatar images
    'show_gravatar' => true,

    // Default Gravatar image type
    'gravatar_default' => 'identicon', // identicon, monsterid, wavatar, retro, robohash

    // Gravatar image size
    'gravatar_size' => 80,

    /*
    |--------------------------------------------------------------------------
    | Comment Editing
    |--------------------------------------------------------------------------
    |
    | Settings for comment editing capabilities.
    |
    */

    // Allow users to edit their own comments
    'allow_editing' => true,

    // Time limit for editing comments (in minutes)
    'edit_time_limit' => 15,

    // Allow users to delete their own comments
    'allow_deletion' => true,

    // Time limit for deleting comments (in minutes)
    'delete_time_limit' => 15,

    /*
    |--------------------------------------------------------------------------
    | Nested Comments
    |--------------------------------------------------------------------------
    |
    | Settings for nested/threaded comments.
    |
    */

    // Enable nested comments (replies)
    'enable_nested_comments' => true,

    // Maximum nesting depth
    'max_nesting_depth' => 3,

    /*
    |--------------------------------------------------------------------------
    | Content Filtering
    |--------------------------------------------------------------------------
    |
    | Settings for filtering comment content.
    |
    */

    // Enable profanity filter
    'profanity_filter_enabled' => true,

    // Profanity words to filter
    'profanity_words' => [
        // Add profanity words here
    ],

    // Action to take when profanity is detected
    'profanity_action' => 'moderate', // 'moderate', 'reject', 'replace'

    // Replacement text for profanity
    'profanity_replacement' => '***',
];
