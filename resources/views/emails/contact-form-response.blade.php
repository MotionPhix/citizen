<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="color-scheme" content="light">
  <meta name="supported-color-schemes" content="light">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    @media only screen and (max-width: 600px) {
      .main-container { width: 100% !important; }
      .content-padding { padding: 24px !important; }
      .mobile-text-center { text-align: center !important; }
      .mobile-full-width { width: 100% !important; }
      .mobile-padding { padding-left: 15px !important; padding-right: 15px !important; }
      .header-text { font-size: 20px !important; }
    }
  </style>
</head>
<body style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; position: relative; -webkit-text-size-adjust: none; background-color: #f8fafc; margin: 0; padding: 0;">
<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #f8fafc; margin: 0; padding: 0; width: 100%;">
  <tr>
    <td align="center" style="vertical-align: top; padding: 24px;">
      <table width="600" cellpadding="0" cellspacing="0" role="presentation" class="main-container" style="margin: 0 auto; width: 600px;">
        <!-- Header with Background Image -->
        <tr>
          <td style="background-color: #2d3748; background-image: linear-gradient(135deg, #2d3748 0%, #1a202c 100%); padding: 40px 20px; text-align: center; border-radius: 12px 12px 0 0;">
            <img src="{{ asset('images/logo-white.png') }}" alt="Citizen Alliance Logo" style="max-width: 180px; height: auto; margin-bottom: 20px;">
            <h1 style="color: #ffffff; font-size: 24px; font-weight: 700; margin: 0; text-transform: uppercase; letter-spacing: 2px;">Response to Your Message</h1>
            <p style="color: #e2e8f0; font-size: 16px; margin: 10px 0 0;">{{ now()->format('F j, Y') }}</p>
          </td>
        </tr>

        <!-- Email Body -->
        <tr>
          <td style="background-color: #ffffff; padding: 40px; border-radius: 0 0 12px 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
            <!-- Greeting -->
            <p style="color: #4a5568; font-size: 16px; line-height: 1.6; margin: 0 0 30px;">
              Dear {{ $data['name'] }},
            </p>

            <!-- Response Message -->
            <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 30px; padding: 24px;">
              <div style="color: #4a5568; font-size: 14px; line-height: 1.6; white-space: pre-wrap;">{{ $data['responseMessage'] }}</div>
            </div>

            <!-- Original Message Reference -->
            <div style="background-color: #f7fafc; border-radius: 12px; padding: 24px; margin-bottom: 30px;">
              <h3 style="color: #2d3748; font-size: 16px; font-weight: 600; margin: 0 0 15px;">Your Original Message:</h3>
              <p style="color: #4a5568; font-size: 14px; line-height: 1.6; margin: 0;">
                <strong>Subject:</strong> {{ $data['subject'] }}
              </p>
              <div style="color: #4a5568; font-size: 14px; line-height: 1.6; margin-top: 10px; white-space: pre-wrap;">{{ $data['originalMessage'] }}</div>
            </div>
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style="padding: 32px 24px;">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="text-align: center;">
              <tr>
                <td>
                  <p style="color: #718096; font-size: 13px; line-height: 1.5; margin: 0;">
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
