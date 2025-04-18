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
    /* Copy your existing email styles */
  </style>
</head>
<body style="box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; position: relative; -webkit-text-size-adjust: none; background-color: #f8fafc; margin: 0; padding: 0;">
<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #f8fafc; margin: 0; padding: 0; width: 100%;">
  <tr>
    <td align="center" style="vertical-align: top; padding: 24px;">
      <table width="600" cellpadding="0" cellspacing="0" role="presentation" class="main-container" style="margin: 0 auto; width: 600px;">
        <!-- Header -->
        <tr>
          <td style="background-color: #2d3748; padding: 40px 20px; text-align: center; border-radius: 12px 12px 0 0;">
            <img src="{{ asset('images/logo-white.png') }}" alt="Citizen Alliance Logo" style="max-width: 180px; height: auto;">
          </td>
        </tr>

        <!-- Content -->
        <tr>
          <td style="background-color: #ffffff; padding: 40px; border-radius: 0 0 12px 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
            {!! $content !!}

            <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
              <p style="font-size: 12px; color: #718096; text-align: center;">
                If you no longer wish to receive these emails, you can
                <a href="{{ $unsubscribeUrl }}" style="color: #4a5568; text-decoration: underline;">unsubscribe here</a>.
              </p>
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
