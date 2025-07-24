<?php

namespace App\Models;

use App\Traits\Likeable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Comment extends Model
{
    use HasFactory, Likeable;

    protected $fillable = [
        'content',
        'user_id',
        'blog_id',
        'parent_id',
        'author_name',
        'author_email',
        'author_website',
        'ip_address',
        'user_agent',
        'is_approved',
        'status',
        'approved_at',
        'approved_by',
        'notify_on_reply',
        'reply_token',
        'spam_score',
        'spam_data',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
        'notify_on_reply' => 'boolean',
        'spam_score' => 'decimal:2',
        'spam_data' => 'array',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_SPAM = 'spam';
    const STATUS_TRASH = 'trash';

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($comment) {
            // Generate reply token for email notifications
            if (!$comment->reply_token) {
                $comment->reply_token = Str::random(32);
            }

            // Set default status based on moderation settings
            if (!$comment->status) {
                $comment->status = config('comments.auto_approve', false)
                    ? self::STATUS_APPROVED
                    : self::STATUS_PENDING;
            }

            // Auto-approve if user is authenticated and trusted
            if ($comment->user_id && !$comment->status) {
                $user = User::find($comment->user_id);
                if ($user && $user->hasPermissionTo('create_comment')) {
                    $comment->status = self::STATUS_APPROVED;
                    $comment->approved_at = now();
                    $comment->approved_by = $comment->user_id;
                }
            }

            // Sync is_approved with status for backward compatibility
            $comment->is_approved = $comment->status === self::STATUS_APPROVED;
        });

        static::updating(function ($comment) {
            // Sync is_approved with status for backward compatibility
            $comment->is_approved = $comment->status === self::STATUS_APPROVED;

            // Set approved_at when status changes to approved
            if ($comment->isDirty('status') && $comment->status === self::STATUS_APPROVED) {
                $comment->approved_at = now();
                if (!$comment->approved_by && auth()->check()) {
                    $comment->approved_by = auth()->id();
                }
            }
        });
    }

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')
            ->where('status', self::STATUS_APPROVED)
            ->orderBy('created_at', 'asc');
    }

    public function allReplies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'comment_likes')
            ->withTimestamps();
    }

    /**
     * Scopes
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeSpam($query)
    {
        return $query->where('status', self::STATUS_SPAM);
    }

    public function scopeByUser($query)
    {
        return $query->whereNotNull('user_id');
    }

    public function scopeAnonymous($query)
    {
        return $query->whereNull('user_id');
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeReplies($query)
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * Accessors & Mutators
     */
    public function displayName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user ? $this->user->name : ($this->author_name ?: 'Anonymous')
        );
    }

    public function displayEmail(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user ? $this->user->email : $this->author_email
        );
    }

    public function isAnonymous(): Attribute
    {
        return Attribute::make(
            get: fn () => is_null($this->user_id)
        );
    }

    public function isApproved(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === self::STATUS_APPROVED
        );
    }

    public function isPending(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === self::STATUS_PENDING
        );
    }

    public function isSpam(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === self::STATUS_SPAM
        );
    }

    public function statusColor(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->status) {
                self::STATUS_APPROVED => 'success',
                self::STATUS_PENDING => 'warning',
                self::STATUS_SPAM => 'danger',
                self::STATUS_TRASH => 'gray',
                default => 'gray'
            }
        );
    }

    public function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->status) {
                self::STATUS_APPROVED => 'Approved',
                self::STATUS_PENDING => 'Pending',
                self::STATUS_SPAM => 'Spam',
                self::STATUS_TRASH => 'Trash',
                default => 'Unknown'
            }
        );
    }

    public function gravatarUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $email = $this->display_email;
                if (!$email) return null;

                $hash = md5(strtolower(trim($email)));
                return "https://www.gravatar.com/avatar/{$hash}?s=80&d=identicon&r=pg";
            }
        );
    }

    /**
     * Business Logic Methods
     */
    public function approve(): bool
    {
        return $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);
    }

    public function reject(): bool
    {
        return $this->update(['status' => self::STATUS_TRASH]);
    }

    public function markAsSpam(): bool
    {
        return $this->update(['status' => self::STATUS_SPAM]);
    }

    public function canReply(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function canEdit(): bool
    {
        if (auth()->check()) {
            $user = auth()->user();
            return $user->can('update_comment') ||
                   ($this->user_id === $user->id && $this->created_at->gt(now()->subMinutes(15)));
        }
        return false;
    }

    public function canDelete(): bool
    {
        if (auth()->check()) {
            $user = auth()->user();
            return $user->can('delete_comment') ||
                   ($this->user_id === $user->id && $this->created_at->gt(now()->subMinutes(15)));
        }
        return false;
    }

    public function getReplyUrl(): string
    {
        return route('comments.reply', [
            'comment' => $this->id,
            'token' => $this->reply_token
        ]);
    }

    public function getUnsubscribeUrl(): string
    {
        return route('comments.unsubscribe', [
            'comment' => $this->id,
            'token' => $this->reply_token
        ]);
    }

    /**
     * Calculate spam score based on various factors
     */
    public function calculateSpamScore(): float
    {
        $score = 0.0;

        // Check content for spam indicators
        $content = strtolower($this->content);

        // Common spam keywords
        $spamKeywords = ['viagra', 'casino', 'lottery', 'bitcoin', 'make money', 'click here'];
        foreach ($spamKeywords as $keyword) {
            if (str_contains($content, $keyword)) {
                $score += 0.3;
            }
        }

        // Check for excessive links
        $linkCount = substr_count($content, 'http');
        if ($linkCount > 2) {
            $score += 0.4;
        }

        // Check for repeated characters
        if (preg_match('/(.)\1{4,}/', $content)) {
            $score += 0.2;
        }

        // Check email domain
        if ($this->author_email) {
            $domain = substr(strrchr($this->author_email, "@"), 1);
            $suspiciousDomains = ['tempmail.com', '10minutemail.com', 'guerrillamail.com'];
            if (in_array($domain, $suspiciousDomains)) {
                $score += 0.5;
            }
        }

        return min($score, 1.0);
    }

    /**
     * Get all available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_SPAM => 'Spam',
            self::STATUS_TRASH => 'Trash',
        ];
    }
}
