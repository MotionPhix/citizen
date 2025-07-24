<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentReplyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Comment $reply;
    protected Comment $originalComment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $reply, Comment $originalComment)
    {
        $this->reply = $reply;
        $this->originalComment = $originalComment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $blogTitle = $this->reply->blog->title;
        $replyAuthor = $this->reply->display_name;
        $originalAuthor = $this->originalComment->display_name;

        return (new MailMessage)
            ->subject("New reply to your comment on \"{$blogTitle}\"")
            ->greeting("Hello {$originalAuthor}!")
            ->line("Someone has replied to your comment on the blog post \"{$blogTitle}\".")
            ->line("**{$replyAuthor}** replied:")
            ->line('"' . Str::limit($this->reply->content, 200) . '"')
            ->line('Your original comment:')
            ->line('"' . Str::limit($this->originalComment->content, 200) . '"')
            ->action('View Full Conversation', $this->getBlogUrl())
            ->line('If you want to reply to this comment, you can do so by visiting the blog post.')
            ->line('To stop receiving notifications for replies to this comment, click the link below:')
            ->action('Unsubscribe from this thread', $this->originalComment->getUnsubscribeUrl())
            ->line('Thank you for being part of our community!')
            ->salutation('Best regards, ' . config('app.name'));
    }

    /**
     * Get the blog post URL with comment anchor.
     */
    protected function getBlogUrl(): string
    {
        $blog = $this->reply->blog;

        // Assuming you have a route for blog posts
        // Adjust this based on your actual blog route structure
        return route('blog.show', $blog->slug) . '#comment-' . $this->reply->id;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'reply_id' => $this->reply->id,
            'original_comment_id' => $this->originalComment->id,
            'blog_id' => $this->reply->blog_id,
            'reply_author' => $this->reply->display_name,
            'reply_content' => Str::limit($this->reply->content, 100),
        ];
    }
}
