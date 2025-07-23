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
  <title>Thank you for contacting {{ config('app.name') }}</title>

  <!-- Preheader text (hidden but shows in email preview) -->
  <div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all;" aria-hidden="true">
    Thank you for contacting {{ config('app.name') }}. We've received your message about "{{ $data['subject'] }}" and will respond {{ $data['expectedResponseTime'] }}.
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
    /* Reset and base styles */
    * {
      box-sizing: border-box;
    }

    body, table, td, p, a, li, blockquote {
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
      font-weight: normal;
      margin: 0;
      padding: 0;
    }

    table, td {
      mso-table-lspace: 0pt;
      mso-table-rspace: 0pt;
      border-collapse: collapse;
    }

    img {
      -ms-interpolation-mode: bicubic;
      border: 0;
      height: auto;
      line-height: 100%;
      outline: none;
      text-decoration: none;
      max-width: 100%;
    }

    /* Main body styles */
    body {
      font-family: 'Courier New', monospace !important;
      background-color: #f8fafc;
      margin: 0;
      padding: 0;
      width: 100% !important;
      min-width: 100%;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      line-height: 1.6;
    }

    /* Container styles */
    .email-container {
      max-width: 600px;
      margin: 0 auto;
      background-color: #ffffff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    /* Header styles */
    .header {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      padding: 40px 30px;
      text-align: center;
      position: relative;
    }

    .header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
      opacity: 0.3;
    }

    .header-content {
      position: relative;
      z-index: 1;
    }

    .header-title {
      color: #ffffff;
      font-size: 28px;
      font-weight: 700;
      margin: 0 0 10px 0;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .header-subtitle {
      color: #d1fae5;
      font-size: 16px;
      margin: 0;
      opacity: 0.9;
    }

    /* Content styles */
    .content {
      padding: 40px 30px;
      background-color: #ffffff;
    }

    .greeting {
      color: #2d3748;
      font-size: 18px;
      line-height: 1.6;
      margin: 0 0 30px 0;
      font-weight: 500;
    }

    .confirmation-section {
      background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
      border-left: 4px solid #10b981;
      border-radius: 0 12px 12px 0;
      padding: 25px;
      margin-bottom: 30px;
      position: relative;
    }

    .confirmation-section::before {
      content: '✅';
      position: absolute;
      top: -10px;
      left: -10px;
      background: #10b981;
      width: 30px;
      height: 30px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
    }

    .confirmation-title {
      color: #065f46;
      font-size: 16px;
      font-weight: 600;
      margin: 0 0 15px 0;
      display: flex;
      align-items: center;
    }

    .confirmation-message {
      color: #047857;
      font-size: 15px;
      line-height: 1.7;
      margin: 0;
    }

    .submission-details {
      background-color: #f7fafc;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 25px;
      margin-bottom: 30px;
    }

    .detail-row {
      display: flex;
      margin-bottom: 12px;
      align-items: flex-start;
    }

    .detail-row:last-child {
      margin-bottom: 0;
    }

    .detail-label {
      font-weight: 600;
      color: #555555;
      min-width: 120px;
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .detail-value {
      color: #333333;
      font-size: 15px;
      line-height: 1.5;
      word-break: break-word;
    }

    .next-steps {
      background-color: #f0f9ff;
      border: 1px solid #bae6fd;
      border-radius: 12px;
      padding: 25px;
      margin: 30px 0;
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

    .contact-info {
      background-color: #fefefe;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 20px;
      margin: 20px 0;
      text-align: center;
    }

    .contact-info h4 {
      color: #2d3748;
      margin: 0 0 15px 0;
      font-size: 16px;
      font-weight: 600;
    }

    .contact-info p {
      color: #4a5568;
      margin: 5px 0;
      font-size: 14px;
    }

    .footer {
      background-color: #f7fafc;
      padding: 30px;
      text-align: center;
      border-top: 1px solid #e2e8f0;
    }

    .footer p {
      color: #6b7280;
      font-size: 14px;
      margin: 5px 0;
      line-height: 1.5;
    }

    .footer a {
      color: #10b981;
      text-decoration: none;
    }

    .footer a:hover {
      text-decoration: underline;
    }

    .divider {
      height: 1px;
      background: linear-gradient(to right, transparent, #e2e8f0, transparent);
      margin: 30px 0;
    }

    .status-badge {
      display: inline-block;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      background-color: #dcfce7;
      color: #16a34a;
    }

    /* Responsive styles */
    @media only screen and (max-width: 600px) {
      .email-container {
        width: 100% !important;
        margin: 0 !important;
        border-radius: 0 !important;
      }

      .header, .content, .footer {
        padding: 20px !important;
      }

      .header-title {
        font-size: 24px !important;
      }

      .greeting {
        font-size: 16px !important;
      }

      .confirmation-section, .submission-details, .next-steps, .contact-info {
        padding: 20px !important;
        margin-left: -5px !important;
        margin-right: -5px !important;
      }

      .detail-row {
        flex-direction: column;
      }

      .detail-label {
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
    <div class="header-content">
      <h1 class="header-title">✅ Message Received!</h1>
      <p class="header-subtitle">Thank you for contacting {{ config('app.name') }}</p>
    </div>
  </div>

  <!-- Content -->
  <div class="content">
    <div class="greeting">
      <strong>Hello {{ $data['name'] }},</strong><br>
      Thank you for reaching out to us! This email confirms that we have successfully received your message and it has been added to our support queue.
    </div>

    <!-- Confirmation Section -->
    <div class="confirmation-section">
      <div class="confirmation-title">Message Confirmed</div>
      <div class="confirmation-message">
        Your inquiry has been received and assigned reference ID <strong>#{{ $data['submissionId'] }}</strong>.
        Our team will review your message and respond <strong>{{ $data['expectedResponseTime'] }}</strong>.
      </div>
    </div>

    <!-- Submission Details -->
    <div class="submission-details">
      <h3 style="color: #374151; margin-bottom: 20px; font-size: 16px;">📋 Submission Details</h3>

      <div class="detail-row">
        <div class="detail-label">Reference ID:</div>
        <div class="detail-value">#{{ $data['submissionId'] }}</div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Subject:</div>
        <div class="detail-value">{{ $data['subject'] }}</div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Submitted:</div>
        <div class="detail-value">{{ $data['submittedAt']->format('M j, Y \a\t g:i A') }}</div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Status:</div>
        <div class="detail-value">
          <span class="status-badge">Received</span>
        </div>
      </div>

      <div class="detail-row">
        <div class="detail-label">Expected Response:</div>
        <div class="detail-value">{{ ucfirst($data['expectedResponseTime']) }}</div>
      </div>
    </div>

    <div class="divider"></div>

    <!-- What Happens Next -->
    <div class="next-steps">
      <h3>🚀 What Happens Next?</h3>
      <ul>
        <li><strong>Review:</strong> Our team will carefully review your message and any attachments</li>
        <li><strong>Research:</strong> We'll gather any necessary information to provide you with the best possible response</li>
        <li><strong>Response:</strong> You'll receive a detailed reply from our team {{ $data['expectedResponseTime'] }}</li>
        <li><strong>Follow-up:</strong> If needed, we may reach out for additional clarification</li>
      </ul>
    </div>

    <!-- Urgent Matters -->
    <div style="background-color: #fef3c7; border: 1px solid #f59e0b; border-radius: 8px; padding: 20px; margin: 25px 0;">
      <h4 style="color: #92400e; margin: 0 0 10px 0; font-size: 16px;">⚡ Need Immediate Assistance?</h4>
      <p style="color: #92400e; margin: 0; font-size: 14px; line-height: 1.5;">
        If your matter is urgent and requires immediate attention, please call us directly at
        <strong>{{ $data['supportPhone'] ?? 'our main number' }}</strong> or send an email to
        <a href="mailto:{{ $data['supportEmail'] }}" style="color: #92400e; font-weight: 600;">{{ $data['supportEmail'] }}</a>
        with "URGENT" in the subject line.
      </p>
    </div>

    <!-- Contact Information -->
    <div class="contact-info">
      <h4>📞 Contact Information</h4>
      <p><strong>Email:</strong> <a href="mailto:{{ $data['supportEmail'] }}">{{ $data['supportEmail'] }}</a></p>
      @if($data['supportPhone'])
        <p><strong>Phone:</strong> {{ $data['supportPhone'] }}</p>
      @endif
      <p><strong>Website:</strong> <a href="{{ $data['websiteUrl'] }}">{{ $data['websiteUrl'] }}</a></p>
      <p style="font-size: 12px; color: #6b7280; margin-top: 15px;">
        Business Hours: Monday - Friday, 9:00 AM - 5:00 PM
      </p>
    </div>

    <!-- Your Original Message -->
    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin: 25px 0;">
      <h4 style="color: #374151; margin: 0 0 15px 0; font-size: 16px;">📝 Your Original Message</h4>
      <div style="background-color: #ffffff; padding: 15px; border-radius: 6px; border-left: 3px solid #cbd5e0;">
        <p style="color: #4a5568; font-size: 14px; margin: 0 0 10px 0; font-weight: 600;">
          Subject: {{ $data['subject'] }}
        </p>
        <div style="color: #4a5568; font-size: 14px; line-height: 1.6; white-space: pre-wrap; margin: 0;">{{ $data['originalMessage'] }}</div>
      </div>
    </div>

    <!-- Security Notice -->
    <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; padding: 15px; margin: 20px 0;">
      <p style="margin: 0; font-size: 13px; color: #1e40af;">
        <strong>🔒 Security Notice:</strong> This is an automated confirmation email. Please do not reply to this email as it is sent from an unmonitored address.
        For any questions, please use the contact information provided above or submit a new message through our website.
      </p>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    <p><strong>{{ config('app.name') }}</strong></p>
    <p>This is an automated confirmation of your contact form submission.</p>
    <p>
      <a href="{{ $data['websiteUrl'] }}">Visit Website</a> |
      <a href="{{ $data['websiteUrl'] }}/contact">Contact Us</a> |
      <a href="{{ $data['websiteUrl'] }}/support">Support Center</a>
    </p>
    <p style="font-size: 12px; color: #9ca3af; margin-top: 15px;">
      © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </p>
  </div>
</div>
</body>
</html>
