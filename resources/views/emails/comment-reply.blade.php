<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New Reply to Your Comment</title>
  <style>
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
      line-height: 1.6;
      color: #333;
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
      background-color: #f8f9fa;
    }

    .container {
      background-color: white;
      border-radius: 8px;
      padding: 30px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .header {
      text-align: center;
      margin-bottom: 30px;
      padding-bottom: 20px;
      border-bottom: 2px solid #e9ecef;
    }

    .header h1 {
      color: #2563eb;
      margin: 0;
      font-size: 24px;
    }

    .content {
      margin-bottom: 30px;
    }

    .comment-box {
      background-color: #f8f9fa;
      border-left: 4px solid #2563eb;
      padding: 15px;
      margin: 20px 0;
      border-radius: 4px;
    }

    .comment-author {
      font-weight: bold;
      color: #2563eb;
      margin-bottom: 5px;
    }

    .comment-content {
      color: #555;
      font-style: italic;
    }

    .button {
      display: inline-block;
      background-color: #2563eb;
      color: white;
      padding: 12px 24px;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
      margin: 10px 5px;
    }

    .button:hover {
      background-color: #1d4ed8;
    }

    .button-secondary {
      background-color: #6b7280;
    }

    .button-secondary:hover {
      background-color: #4b5563;
    }

    .footer {
      margin-top: 30px;
      padding-top: 20px;
      border-top: 1px solid #e9ecef;
      text-align: center;
      color: #6b7280;
      font-size: 14px;
    }

    .footer a {
      color: #2563eb;
      text-decoration: none;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="header">
    <h1>{{ config('app.name') }}</h1>
    <p>Someone replied to your comment!</p>
  </div>

  <div class="content">
    <p>Hello <strong>{{ $originalAuthor }}</strong>!</p>

    <p>Someone has replied to your comment on the blog post "<strong>{{ $blogTitle }}</strong>".</p>

    <div class="comment-box">
      <div class="comment-author">{{ $replyAuthor }} replied:</div>
      <div class="comment-content">"{{ Str::limit($reply->content, 200) }}"</div>
    </div>

    <div class="comment-box">
      <div class="comment-author">Your original comment:</div>
      <div class="comment-content">"{{ Str::limit($originalComment->content, 200) }}"</div>
    </div>

    <p>You can view the full conversation and reply by clicking the button below:</p>

    <div style="text-align: center; margin: 30px 0;">
      <a href="{{ $blogUrl }}" class="button">View Full Conversation</a>
    </div>

    <p>If you want to stop receiving notifications for replies to this comment, you can unsubscribe using the link
      below.</p>
  </div>

  <div class="footer">
    <p>
      <a href="{{ $unsubscribeUrl }}" class="button button-secondary">Unsubscribe from this thread</a>
    </p>

    <p>
      This email was sent by {{ config('app.name') }}.<br>
      Thank you for being part of our community!
    </p>
  </div>
</div>
</body>
</html>
