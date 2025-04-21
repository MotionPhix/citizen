<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- Preload fonts -->
  <link rel="preload" href="{{ asset('fonts/email/Inter-Regular.woff2') }}" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="{{ asset('fonts/email/Inter-Medium.woff2') }}" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="{{ asset('fonts/email/Inter-Bold.woff2') }}" as="font" type="font/woff2" crossorigin>

  <!--[if mso]>
  <style type="text/css">
    /* Outlook-specific font stack */
    body, table, td, p, a, li, blockquote {
      font-family: Inter, Helvetica, Arial, sans-serif !important;
    }
  </style>
  <![endif]-->
  @include('emails.styles')
</head>
<body>
<div class="email-container">
  {{ $slot }}
</div>
</body>
</html>
