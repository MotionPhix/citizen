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
  <title>Response to Your Message - {{ config('app.name') }}</title>

  <!-- Preheader text (hidden but shows in email preview) -->
  <div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all;" aria-hidden="true">
    Thank you for contacting {{ config('app.name') }}. Here's our response to your inquiry about "{{ $data['subject'] }}". We appreciate your message and hope this response is helpful.
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
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.05)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
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
      color: #e2e8f0;
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

    .response-section {
      background: linear-gradient(135deg, #f0fff4 0%, #ecfdf5 100%);
      border-left: 4px solid #10b981;
      border-radius: 0 12px 12px 0;
      padding: 25px;
      margin-bottom: 30px;
      position: relative;
    }

    .response-section::before {
      content: '💬';
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

    .response-title {
      color: #065f46;
      font-size: 16px;
      font-weight: 600;
      margin: 0 0 15px 0;
      display: flex;
      align-items: center;
    }

    .response-message {
      color: #047857;
      font-size: 15px;
      line-height: 1.7;
      white-space: pre-wrap;
      margin: 0;
      word-wrap: break-word;
    }

    .original-message-section {
      background-color: #f7fafc;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 25px;
      margin-bottom: 30px;
    }

    .original-title {
      color: #2d3748;
      font-size: 16px;
      font-weight: 600;
      margin: 0 0 15px 0;
      display: flex;
      align-items: center;
    }

    .original-title::before {
      content: '📝';
      margin-right: 8px;
    }

    .original-subject {
      color: #4a5568;
      font-size: 14px;
      margin: 0 0 15px 0;
      padding: 10px;
      background-color: #ffffff;
      border-radius: 6px;
      border-left: 3px solid #cbd5e0;
      font-weight: 600;
    }

    .original-message {
      color: #4a5568;
      font-size: 14px;
      line-height: 1.6;
      white-space: pre-wrap;
      margin: 0;
      word-wrap: break-word;
    }

    /* Call-to-action styles */
    .cta-section {
      text-align: center;
      margin: 30px 0;
      padding: 25px;
      background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
      border-radius: 12px;
      border: 1px solid #bae6fd;
    }

    .cta-title {
      color: #0369a1;
      font-size: 16px;
      font-weight: 600;
      margin: 0 0 15px 0;
    }

    .cta-button {
      display: inline-block;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: #ffffff !important;
      padding: 14px 28px;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 600;
      font-size: 15px;
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
      margin: 0 5px;
    }

    .cta-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
    }

    .cta-button-secondary {
      background: #ffffff !important;
      color: #667eea !important;
      border: 2px solid #667eea !important;
      box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
    }

    /* Footer styles */
    .footer {
      background-color: #f7fafc;
      padding: 30px;
      text-align: center;
      border-top: 1px solid #e2e8f0;
    }

    .footer-content {
      color: #718096;
      font-size: 13px;
      line-height: 1.6;
      margin: 0;
    }

    .footer-links {
      margin: 15px 0 0 0;
    }

    .footer-link {
      color: #667eea;
      text-decoration: none;
      margin: 0 10px;
      font-weight: 500;
    }

    .footer-link:hover {
      text-decoration: underline;
    }

    /* Contact info section */
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

    /* Divider */
    .divider {
      height: 1px;
      background: linear-gradient(to right, transparent, #e2e8f0, transparent);
      margin: 30px 0;
    }

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
      .email-container {
        background-color: #1a202c !important;
      }

      .content {
        background-color: #1a202c !important;
        color: #e2e8f0 !important;
      }

      .greeting, .response-title, .original-title {
        color: #e2e8f0 !important;
      }

      .response-message, .original-message, .original-subject {
        color: #cbd5e0 !important;
      }

      .response-section {
        background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%) !important;
      }

      .original-message-section, .contact-info {
        background-color: #2d3748 !important;
        border-color: #4a5568 !important;
      }
    }

    /* Mobile responsive */
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
        font-size: 22px !important;
      }

      .greeting {
        font-size: 16px !important;
      }

      .response-section, .original-message-section, .cta-section, .contact-info {
        padding: 20px !important;
        margin-left: -5px !important;
        margin-right: -5px !important;
      }

      .cta-button {
        display: block !important;
        width: calc(100% - 10px) !important;
        text-align: center !important;
        margin: 5px 0 !important;
      }
    }

    /* High contrast mode support */
    @media (prefers-contrast: high) {
      .header {
        background: #000000 !important;
      }

      .header-title, .header-subtitle {
        color: #ffffff !important;
      }

      .response-section {
        border-left-width: 6px !important;
        background: #f0f0f0 !important;
      }

      .cta-button {
        background: #0000ff !important;
        border: 2px solid #000000 !important;
      }
    }

    /* Print styles */
    @media print {
      .email-container {
        box-shadow: none !important;
        border: 1px solid #000000 !important;
      }

      .header {
        background: #f0f0f0 !important;
        color: #000000 !important;
      }

      .header-title, .header-subtitle {
        color: #000000 !important;
      }

      .cta-button {
        background: #ffffff !important;
        color: #000000 !important;
        border: 2px solid #000000 !important;
      }
    }
  </style>

  <!--[if mso]>
  <style type="text/css">
    .fallback-text {
      font-family: Arial, sans-serif !important;
    }
    .header-title {
      font-size: 24px !important;
    }
    .cta-button {
      padding: 12px 24px !important;
    }
  </style>
  <![endif]-->
</head>
<body role="article" aria-label="Response to your contact form submission" style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; position: relative; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; background-color: #f8fafc; margin: 0; padding: 0; width: 100% !important; min-width: 100%;">

<!-- Hidden preheader text for email preview -->
<div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all;">
  Thank you for contacting {{ config('app.name') }}. Here's our response to your inquiry about "{{ $data['subject'] }}".
</div>

<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #f8fafc; margin: 0; padding: 0; width: 100%; min-width: 100%; border-collapse: collapse;">
  <tr>
    <td align="center" style="vertical-align: top; padding: 24px 12px;">

      <!--[if mso | IE]>
      <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="width:600px;">
        <tr>
          <td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;">
      <![endif]-->

      <table width="100%" cellpadding="0" cellspacing="0" role="presentation" class="main-container" style="margin: 0 auto; max-width: 600px; width: 100%; border-collapse: collapse; background-color: #ffffff; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); overflow: hidden;">

        <!-- Header Section -->
        <tr>
          <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 48px 32px; text-align: center; position: relative;">

            <!-- Decorative pattern overlay -->
            <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-image: url('data:image/svg+xml,<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; viewBox=&quot;0 0 100 100&quot;><defs><pattern id=&quot;grain&quot; width=&quot;100&quot; height=&quot;100&quot; patternUnits=&quot;userSpaceOnUse&quot;><circle cx=&quot;25&quot; cy=&quot;25&quot; r=&quot;1&quot; fill=&quot;rgba(255,255,255,0.05)&quot;/><circle cx=&quot;75&quot; cy=&quot;75&quot; r=&quot;1&quot; fill=&quot;rgba(255,255,255,0.05)&quot;/></pattern></defs><rect width=&quot;100&quot; height=&quot;100&quot; fill=&quot;url(%23grain)&quot;/></svg>'); opacity: 0.3;"></div>

            <!-- Header content -->
            <div style="position: relative; z-index: 1;">
              @if(file_exists(public_path('images/logo-white.png')))
                <img src="{{ asset('images/logo-white.png') }}" alt="{{ config('app.name') }} Logo" style="max-width: 200px; height: auto; margin-bottom: 24px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">
              @else
                <div style="background-color: rgba(255,255,255,0.1); border-radius: 12px; padding: 16px 24px; margin-bottom: 24px; display: inline-block;">
                  <span style="color: #ffffff; font-size: 24px; font-weight: 700; letter-spacing: 1px;">{{ config('app.name') }}</span>
                </div>
              @endif

              <h1 style="color: #ffffff; font-size: 32px; font-weight: 700; margin: 0 0 12px; text-shadow: 0 2px 4px rgba(0,0,0,0.3); line-height: 1.2;">
                📧 Response to Your Message
              </h1>
              <p style="color: #e8eaff; font-size: 18px; margin: 0; opacity: 0.9; font-weight: 500;">
                {{ now()->format('F j, Y \a\t g:i A') }}
              </p>
            </div>
          </td>
        </tr>

        <!-- Main Content Section -->
        <tr>
          <td style="background-color: #ffffff; padding: 48px 32px;">

            <!-- Personal Greeting -->
            <div style="margin-bottom: 32px;">
              <h2 style="color: #2d3748; font-size: 20px; font-weight: 600; margin: 0 0 16px; line-height: 1.3;">
                Hello {{ $data['name'] }},
              </h2>
              <p style="color: #4a5568; font-size: 16px; line-height: 1.6; margin: 0;">
                Thank you for reaching out to us. We've carefully reviewed your message and are pleased to provide you with the following response:
              </p>
            </div>

            <!-- Response Message Section -->
            <div style="background: linear-gradient(135deg, #f0fff4 0%, #ecfdf5 100%); border-left: 4px solid #10b981; border-radius: 0 12px 12px 0; padding: 28px; margin-bottom: 32px; position: relative; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">

              <!-- Response icon -->
              <div style="position: absolute; top: -12px; left: -12px; background: #10b981; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(16,185,129,0.3);">
                <span style="color: white; font-size: 16px;">💬</span>
              </div>

              <h3 style="color: #065f46; font-size: 18px; font-weight: 600; margin: 0 0 16px; display: flex; align-items: center;">
                Our Response
              </h3>
              <div style="color: #047857; font-size: 16px; line-height: 1.7; white-space: pre-wrap; margin: 0; word-wrap: break-word; font-weight: 500;">{{ $data['responseMessage'] }}</div>
            </div>

            <!-- Divider -->
            <div style="height: 1px; background: linear-gradient(to right, transparent, #e2e8f0, transparent); margin: 40px 0;"></div>

            <!-- Original Message Reference -->
            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 28px; margin-bottom: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
              <h3 style="color: #2d3748; font-size: 18px; font-weight: 600; margin: 0 0 20px; display: flex; align-items: center;">
                <span style="margin-right: 8px;">📝</span>
                Your Original Message
              </h3>

              <!-- Subject line -->
              <div style="background-color: #ffffff; border-left: 3px solid #cbd5e0; border-radius: 0 6px 6px 0; padding: 12px 16px; margin-bottom: 16px;">
                <p style="color: #4a5568; font-size: 14px; margin: 0; font-weight: 600;">
                  <span style="color: #718096; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Subject:</span><br>
                  {{ $data['subject'] }}
                </p>
              </div>

              <!-- Original message content -->
              <div style="color: #4a5568; font-size: 15px; line-height: 1.6; white-space: pre-wrap; margin: 0; word-wrap: break-word; background-color: #ffffff; padding: 16px; border-radius: 8px; border: 1px solid #f1f5f9;">{{ $data['originalMessage'] }}</div>
            </div>

            <!-- Call-to-Action Section -->
            <div style="text-align: center; margin: 40px 0; padding: 28px; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius: 12px; border: 1px solid #bae6fd;">
              <h3 style="color: #0369a1; font-size: 18px; font-weight: 600; margin: 0 0 16px;">
                Need Further Assistance?
              </h3>
              <p style="color: #0284c7; font-size: 14px; margin: 0 0 20px; line-height: 1.5;">
                If you have any follow-up questions or need additional support, don't hesitate to reach out to us.
              </p>

              <!-- Action buttons -->
              <table cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                <tr>
                  <td style="padding: 0 8px;">
                    <a href="mailto:{{ config('mail.from.address') }}?subject=Re: {{ $data['subject'] }}"
                       style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px; display: inline-block; box-shadow: 0 4px 12px rgba(102,126,234,0.3); transition: all 0.3s ease;">
                      📧 Reply
                    </a>
                  </td>
                  <td style="padding: 0 8px;">
                    <a href="{{ config('app.url') }}/contact"
                       style="background-color: #ffffff; color: #667eea; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px; display: inline-block; border: 2px solid #667eea; box-shadow: 0 2px 8px rgba(102,126,234,0.1);">
                      🌐 Our Website
                    </a>
                  </td>
                </tr>
              </table>
            </div>

            <!-- Contact Information -->
            <div style="background-color: #fefefe; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; margin: 32px 0; text-align: center;">
              <h4 style="color: #2d3748; margin: 0 0 16px; font-size: 16px; font-weight: 600;">
                📞 Get in Touch
              </h4>
              <p style="color: #4a5568; margin: 8px 0; font-size: 14px; line-height: 1.5;">
                <strong>Email:</strong> <a href="mailto:{{ config('mail.from.address') }}" style="color: #667eea; text-decoration: none;">{{ config('mail.from.address') }}</a>
              </p>
              @if(config('app.phone'))
                <p style="color: #4a5568; margin: 8px 0; font-size: 14px; line-height: 1.5;">
                  <strong>Phone:</strong> {{ config('app.phone') }}
                </p>
              @endif
              <p style="color: #4a5568; margin: 8px 0; font-size: 14px; line-height: 1.5;">
                <strong>Website:</strong> <a href="{{ config('app.url') }}" style="color: #667eea; text-decoration: none;">{{ config('app.url') }}</a>
              </p>
            </div>

            <!-- Thank you message -->
            <div style="text-align: center; margin: 32px 0;">
              <p style="color: #4a5568; font-size: 16px; line-height: 1.6; margin: 0; font-style: italic;">
                Thank you for choosing {{ config('app.name') }}.
              </p>
            </div>

          </td>
        </tr>

        <!-- Enhanced Footer -->
        <tr>
          <td style="background-color: #f7fafc; padding: 32px; text-align: center; border-top: 1px solid #e2e8f0;">

            <!-- Company info -->
            <div style="margin-bottom: 20px;">
              <h4 style="color: #2d3748; font-size: 16px; font-weight: 600; margin: 0 0 12px;">
                {{ config('app.name') }}
              </h4>
              <p style="color: #718096; font-size: 13px; line-height: 1.5; margin: 0;">
                This email was sent in response to your contact form submission.
              </p>
            </div>

            <!-- Footer links -->
            <div style="margin: 20px 0;">
              <a href="{{ config('app.url') }}" style="color: #667eea; text-decoration: none; margin: 0 12px; font-size: 13px; font-weight: 500;">Visit Website</a>
              <span style="color: #cbd5e0;">|</span>
              <a href="{{ config('app.url') }}/privacy" style="color: #667eea; text-decoration: none; margin: 0 12px; font-size: 13px; font-weight: 500;">Privacy Policy</a>
              <span style="color: #cbd5e0;">|</span>
              <a href="{{ config('app.url') }}/contact" style="color: #667eea; text-decoration: none; margin: 0 12px; font-size: 13px; font-weight: 500;">Contact Us</a>
            </div>

            <!-- Compliance and Legal Information -->
            <div style="margin: 24px 0; padding: 16px; background-color: #f1f5f9; border-radius: 8px; border: 1px solid #e2e8f0;">
              <p style="color: #64748b; font-size: 12px; line-height: 1.4; margin: 0 0 8px;">
                <strong>Email Delivery Information:</strong><br>
                This email was sent to {{ $data['name'] ?? 'you' }} in response to your contact form submission on {{ now()->format('F j, Y') }}.
                If you did not submit this form, please contact us immediately.
              </p>

              <p style="color: #64748b; font-size: 12px; line-height: 1.4; margin: 0;">
                <strong>Data Protection:</strong> Your personal information is handled in accordance with our Privacy Policy.
                We only use your contact details to respond to your inquiry and will not share them with third parties without your consent.
              </p>
            </div>

            <!-- Response tracking and metadata -->
            <div style="margin: 16px 0; padding: 12px; background-color: #fafbfc; border-radius: 6px; border-left: 3px solid #cbd5e0;">
              <p style="color: #6b7280; font-size: 11px; line-height: 1.3; margin: 0;">
                <strong>Response Details:</strong><br>
                Response ID: {{ uniqid('resp_') }}<br>
                Sent: {{ now()->format('Y-m-d H:i:s T') }}<br>
                Original Inquiry: {{ $data['subject'] ?? 'Contact Form Submission' }}
              </p>
            </div>

            <!-- Social Media Links (if configured) -->
            @if(config('app.social.facebook') || config('app.social.twitter') || config('app.social.linkedin'))
            <div style="margin: 24px 0;">
              <p style="color: #718096; font-size: 13px; margin: 0 0 12px; font-weight: 500;">Follow Us:</p>
              <div style="text-align: center;">
                @if(config('app.social.facebook'))
                  <a href="{{ config('app.social.facebook') }}" style="display: inline-block; margin: 0 8px; color: #667eea; text-decoration: none; font-size: 12px;">Facebook</a>
                @endif
                @if(config('app.social.twitter'))
                  <a href="{{ config('app.social.twitter') }}" style="display: inline-block; margin: 0 8px; color: #667eea; text-decoration: none; font-size: 12px;">Twitter</a>
                @endif
                @if(config('app.social.linkedin'))
                  <a href="{{ config('app.social.linkedin') }}" style="display: inline-block; margin: 0 8px; color: #667eea; text-decoration: none; font-size: 12px;">LinkedIn</a>
                @endif
              </div>
            </div>
            @endif

            <!-- Copyright and final disclaimer -->
            <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #e2e8f0;">
              <p style="color: #9ca3af; font-size: 11px; line-height: 1.4; margin: 0;">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
                This email and any attachments are confidential and intended solely for the addressee.
                If you have received this email in error, please notify us immediately and delete it from your system.
              </p>
            </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
