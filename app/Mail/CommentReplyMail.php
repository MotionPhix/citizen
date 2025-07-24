<?php

namespace App\Mail;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class CommentReplyMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Comment $reply;
    public Comment $originalComment;

    /**
     * Create a new message instance.
     */
    public function __construct(Comment $reply, Comment $originalComment)
    {
        $this->reply = $reply;
        $this->originalComment = $originalComment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $blogTitle = $this->reply->blog->title;

        return new Envelope(
            subject: "New reply to your comment on \"{$blogTitle}\"",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.comment-reply',
            with: [
                'reply' => $this->reply,
                'originalComment' => $this->originalComment,
                'blogTitle' => $this->reply->blog->title,
                'replyAuthor' => $this->reply->display_name,
                'originalAuthor' => $this->originalComment->display_name,
                'blogUrl' => $this->getBlogUrl(),
                'unsubscribeUrl' => $this->originalComment->getUnsubscribeUrl(),
            ]
        );
    }

    /**
     * Get the blog post URL with comment anchor.
     */
    protected function getBlogUrl(): string
    {
        $blog = $this->reply->blog;

        // Generate the blog URL - adjust this based on your actual blog route structure
        return route('blogs.show', $blog->slug) . '#comment-' . $this->reply->id;
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
