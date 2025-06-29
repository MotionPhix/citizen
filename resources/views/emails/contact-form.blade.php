<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="x-apple-disable-message-reformatting">
  <meta name="color-scheme" content="light dark">
  <meta name="supported-color-schemes" content="light dark">
  <meta name="format-detection" content="telephone=no,date=no,address=no,email=no,url=no">
  <meta name="robots" content="noindex,nofollow">
  <title>New Contact Form Submission - {{ config('app.name') }}</title>

  <!-- Preheader text (hidden but shows in email preview) -->
  <div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all;" aria-hidden="true">
    New contact form submission from {{ $data['name'] }} regarding "{{ $data['subject'] }}". Requires your attention and response.
  </div>

  <!--[if mso]>
  <noscript>
    <xml>
      <o:OfficeDocumentSettings>
        <o:AllowPNG/>
        <o:PixelsPerInch>96</o:PixelsPerInch>
      </o:OfficeDocumentSettings>
    </xml>
  </noscript>
  <![endif]-->
  <style>
    /* Reset styles */
    body, table, td, p, a, li, blockquote {
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
    }
    table, td {
      mso-table-lspace: 0pt;
      mso-table-rspace: 0pt;
    }
    img {
      -ms-interpolation-mode: bicubic;
      border: 0;
      height: auto;
      line-height: 100%;
      outline: none;
      text-decoration: none;
    }

    /* Main styles */
    body {
      margin: 0 !important;
      padding: 0 !important;
      background-color: #f4f4f4;
      font-family: 'Courier New', monospace;
    }

    .email-container {
      max-width: 600px;
      margin: 0 auto;
      background-color: #ffffff;
    }

    .header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      padding: 40px 30px;
      text-align: center;
    }

    .header h1 {
      color: #ffffff;
      margin: 0;
      font-size: 28px;
      font-weight: 600;
      text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .header p {
      color: #e8eaff;
      margin: 10px 0 0 0;
      font-size: 16px;
    }

    .content {
      padding: 40px 30px;
    }

    .greeting {
      font-size: 18px;
      color: #333333;
      margin-bottom: 20px;
      line-height: 1.5;
    }

    .info-card {
      background-color: #f8f9ff;
      border-left: 4px solid #667eea;
      padding: 20px;
      margin: 25px 0;
      border-radius: 0 8px 8px 0;
    }

    .info-row {
      display: flex;
      margin-bottom: 15px;
      align-items: flex-start;
    }

    .info-row:last-child {
      margin-bottom: 0;
    }

    .info-label {
      font-weight: 600;
      color: #555555;
      min-width: 120px;
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .info-value {
      color: #333333;
      font-size: 15px;
      line-height: 1.5;
      word-break: break-word;
    }

    .message-content {
      background-color: #ffffff;
      border: 1px solid #e1e5e9;
      border-radius: 8px;
      padding: 20px;
      margin: 20px 0;
      font-size: 15px;
      line-height: 1.6;
      color: #333333;
    }

    .status-badge {
      display: inline-block;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .status-unread {
      background-color: #fee2e2;
      color: #dc2626;
    }

    .status-read {
      background-color: #fef3c7;
      color: #d97706;
    }

    .status-responded {
      background-color: #dcfce7;
      color: #16a34a;
    }

    .next-steps {
      background-color: #f0f9ff;
      border: 1px solid #bae6fd;
      border-radius: 8px;
      padding: 20px;
      margin: 25px 0;
    }

    .next-steps h3 {
      color: #0369a1;
      margin: 0 0 15px 0;
      font-size: 16px;
    }

    .next-steps ul {
      margin: 0;
      padding-left: 20px;
      color: #374151;
    }

    .next-steps li {
      margin-bottom: 8px;
      line-height: 1.5;
    }

    .footer {
      background-color: #f9fafb;
      padding: 30px;
      text-align: center;
      border-top: 1px solid #e5e7eb;
    }

    .footer p {
      color: #6b7280;
      font-size: 14px;
      margin: 5px 0;
      line-height: 1.5;
    }

    .footer a {
      color: #667eea;
      text-decoration: none;
    }

    .footer a:hover {
      text-decoration: underline;
    }

    .divider {
      height: 1px;
      background-color: #e5e7eb;
      margin: 30px 0;
    }

    /* Responsive styles */
    @media only screen and (max-width: 600px) {
      .email-container {
        width: 100% !important;
      }

      .header, .content, .footer {
        padding: 20px !important;
      }

      .header h1 {
        font-size: 24px !important;
      }

      .info-row {
        flex-direction: column;
      }

      .info-label {
        margin-bottom: 5px;
        min-width: auto;
      }
    }
  </style>
</head>
<body>
<div class="email-container">
  <!-- Header -->
  <div class="header">
    <h1>📧 Contact Form Submission</h1>
    <p>New message received from your website</p>
  </div>

  <!-- Content -->
  <div class="content">
    <div class="greeting">
      <strong>Hello Admin,</strong><br>
      You have received a new contact form submission. Here are the details:
    </div>

    <!-- Submission Details -->
    <div class="info-card">
      <div class="info-row">
        <div class="info-label">Name:</div>
        <div class="info-value">{{ $data['name'] }}</div>
      </div>

      <div class="info-row">
        <div class="info-label">Email:</div>
        <div class="info-value">
          <a href="mailto:{{ $data['email'] }}" style="color: #667eea; text-decoration: none;">
            {{ $data['email'] }}
          </a>
        </div>
      </div>

      <div class="info-row">
        <div class="info-label">Subject:</div>
        <div class="info-value"><strong>{{ $data['subject'] }}</strong></div>
      </div>

      <div class="info-row">
        <div class="info-label">Status:</div>
        <div class="info-value">
          <span class="status-badge status-unread">New</span>
        </div>
      </div>

      <div class="info-row">
        <div class="info-label">Submitted:</div>
        <div class="info-value">{{ now()->format('M j, Y \a\t g:i A') }}</div>
      </div>

      <div class="info-row">
        <div class="info-label">IP Address:</div>
        <div class="info-value">{{ $data['ip_address'] ?? 'Not recorded' }}</div>
      </div>
    </div>

    <!-- Message Content -->
    <div style="margin: 25px 0;">
      <h3 style="color: #374151; margin-bottom: 10px; font-size: 16px;">Message:</h3>
      <div class="message-content">
        {!! $data['message'] !!}
      </div>
    </div>

    <div class="divider"></div>

    <!-- Next Steps -->
    <div class="next-steps">
      <h3>📋 Next Steps</h3>
      <ul>
        <li><strong>Review:</strong> Carefully read through the message content</li>
        <li><strong>Respond:</strong> Reply directly to <a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a></li>
        <li><strong>Track:</strong> Update the submission status in your admin panel</li>
      </ul>
    </div>

    <!-- Quick Actions -->
    <div style="text-align: center; margin: 30px 0;">
      <table cellpadding="0" cellspacing="0" style="margin: 0 auto;">
        <tr>
          <td style="padding: 0 10px;">
            <a href="mailto:{{ $data['email'] }}?subject=Re: {{ $data['subject'] }}"
               style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 12px 24px;
                text-decoration: none;
                border-radius: 6px;
                font-weight: 600;
                display: inline-block;">
              📧 Reply Now
            </a>
          </td>
          <td style="padding: 0 10px;">
            <a href="{{ config('app.url') }}/admin/contact-submissions/{{ $data['serial'] }}"
               style="background-color: #f3f4f6;
                color: #374151;
                padding: 12px 24px;
                text-decoration: none;
                border-radius: 6px;
                font-weight: 600;
                display: inline-block;
                border: 1px solid #d1d5db;">
              🔍 View in Admin
            </a>
          </td>
        </tr>
      </table>
    </div>

    <!-- Security Notice -->
    @if(isset($data['ip_address']))
      <div style="background-color: #fffbeb; border: 1px solid #fbbf24; border-radius: 6px; padding: 15px; margin: 20px 0;">
        <p style="margin: 0; font-size: 13px; color: #92400e;">
          <strong>🔒 Security Info:</strong> This submission was received from IP address {{ $data['ip_address'] }}.
          Always verify the sender's identity before sharing sensitive information.
        </p>
      </div>
    @endif
  </div>

  <!-- Footer -->
  <div class="footer">
    <p><strong>{{ config('app.name') }}</strong></p>
    <p>This is an automated notification from your contact form system.</p>
    <p>
      <a href="{{ config('app.url') }}">Visit Website</a> |
      <a href="{{ config('app.url') }}/admin">Admin Panel</a>
    </p>
    <p style="font-size: 12px; color: #9ca3af; margin-top: 15px;">
      © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </p>
  </div>
</div>
</body>
</html>
