<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="color-scheme" content="light">
  <meta name="supported-color-schemes" content="light">
  <style>
    body {
      font-family: "Courier New", monospace, -apple-system  !important;
    }

    @media (prefers-color-scheme: dark) {
      .email-body {
        background-color: #1a202c !important;
        color: #e2e8f0 !important;
      }
      .content-box {
        background-color: #2d3748 !important;
      }
    }

    @media only screen and (max-width: 600px) {
      .main-container {
        width: 100% !important;
      }
      .content-padding {
        padding: 24px !important;
      }
      .mobile-text-center {
        text-align: center !important;
      }
      .mobile-full-width {
        width: 100% !important;
      }
      .mobile-padding {
        padding-left: 15px !important;
        padding-right: 15px !important;
      }
      .header-text {
        font-size: 20px !important;
      }
      .mobile-stack {
        display: block !important;
        width: 100% !important;
      }
      .mobile-center {
        margin: 0 auto !important;
        text-align: center !important;
      }
      .mobile-hide {
        display: none !important;
      }
      .mobile-no-padding {
        padding: 0 !important;
      }
    }
  </style>
  <!--[if mso]>
  <style type="text/css">
    .fallback-text {
      font-family: "Courier New", monospace;
    }
  </style>
  <![endif]-->
</head>
<body style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; position: relative; -webkit-text-size-adjust: none; background-color: #f8fafc; color: #4a5568; height: 100%; line-height: 1.4; margin: 0; padding: 0; width: 100% !important;">
<div style="display: none; max-height: 0px; overflow: hidden;">
  New contact form submission from {{ $data['name'] }} - {{ $data['subject'] }}
</div>
<!-- Add white space after preview text to prevent email content from showing -->
<div style="display: none; max-height: 0px; overflow: hidden;">
  &nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
</div>

<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #f8fafc; margin: 0; padding: 0; width: 100%;">
  <tr>
    <td align="center" style="vertical-align: top; padding: 24px;">
      <table width="600" cellpadding="0" cellspacing="0" role="presentation" class="main-container" style="margin: 0 auto; width: 600px;">
        <!-- Header with Background Image -->
        <tr>
          <td style="background-color: #2d3748; background-image: linear-gradient(135deg, #2d3748 0%, #1a202c 100%); padding: 40px 20px; text-align: center; border-radius: 12px 12px 0 0;" class="content-padding">
            <img src="{{ asset('images/logo-white.png') }}" alt="Citizen Alliance Logo" style="max-width: 180px; height: auto; margin-bottom: 20px;" class="mobile-full-width">
            <h1 style="color: #ffffff; font-size: 24px; font-weight: 700; margin: 0; text-transform: uppercase; letter-spacing: 2px;" class="header-text">New Message Received</h1>
            <p style="color: #e2e8f0; font-size: 16px; margin: 10px 0 0;">{{ now()->format('F j, Y - g:i A') }}</p>
          </td>
        </tr>

        <!-- Email Body -->
        <tr>
          <td style="background-color: #ffffff; padding: 40px; border-radius: 0 0 12px 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);" class="content-padding">
            <!-- Intro Message -->
            <p style="color: #4a5568; font-size: 16px; line-height: 1.6; margin: 0 0 30px;">
              A new contact form submission has been received from the website. Here are the details:
            </p>

            <!-- Contact Information Card -->
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #f7fafc; border-radius: 12px; margin-bottom: 30px;">
              <tr>
                <td style="padding: 24px;" class="mobile-padding">
                  <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                      <td width="50" style="vertical-align: top;" class="mobile-center mobile-stack">
                        <div style="background-color: #4299e1; border-radius: 50%; color: #ffffff; font-size: 20px; font-weight: 700; height: 50px; line-height: 50px; text-align: center; width: 50px; margin-bottom: 15px;">
                          {{ strtoupper(substr($data['name'], 0, 1)) }}
                        </div>
                      </td>

                      <td style="padding-left: 15px; vertical-align: top;" class="mobile-center mobile-stack mobile-no-padding">
                        <h3 style="color: #2d3748; font-size: 18px; font-weight: 600; margin: 0 0 5px;" class="mobile-text-center">{{ $data['name'] }}</h3>
                        <p style="color: #4a5568; font-size: 14px; margin: 0;" class="mobile-text-center">
                          <a href="mailto:{{ $data['email'] }}" style="color: #4299e1; text-decoration: none;">{{ $data['email'] }}</a>
                        </p>
                      </td>
                    </tr>

                    <tr>
                      <td style="padding: 20px; border-top: 1px solid #e2e8f0;">
                        <p style="color: #718096; font-size: 12px; margin: 0;">
                          From the following IP address<br>
                          <strong>{{ $data['ip_address'] }}</strong>
                        </p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            <!-- Message Content -->
            <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 30px; padding: 24px;" class="mobile-padding">
              <h2 style="color: #1a202c; font-size: 16px; font-weight: 600; margin: 0 0 15px;">{{ $data['subject'] }}</h2>
              <div style="color: #4a5568; font-size: 14px; line-height: 1.6; white-space: pre-wrap;">{{ $data['message'] }}</div>
            </div>

            <!-- Action Button -->
            <!--table width="100%" cellpadding="0" cellspacing="0" role="presentation">
              <tr>
                <td align="center">
                  <a href="_#"
                     style="background-color: #4299e1; border-radius: 8px; color: #ffffff; display: inline-block; font-size: 14px; font-weight: 600; padding: 14px 28px; text-decoration: none; text-transform: uppercase; letter-spacing: 1px; transition: background-color 0.2s ease-in-out;">
                    View in Dashboard
                  </a>
                </td>
              </tr>
            </table-->
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style="padding: 32px 24px;" class="mobile-padding">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="text-align: center;">
              <tr>
                <td>
                  <p style="color: #718096; font-size: 13px; line-height: 1.5; margin: 0;">
                    This email was sent from the contact form at
                    <a href="{{ config('app.url') }}" title="Visit Citizen Alliance" target="_blank" style="color: #4299e1; text-decoration: none;">Citizen Alliance</a>
                  </p>
                  <p style="color: #718096; font-size: 13px; line-height: 1.5; margin: 10px 0 0;">
                    © {{ date('Y') }} Citizen Alliance. All rights reserved.
                  </p>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
