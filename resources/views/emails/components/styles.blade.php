<style>
  /* Custom Fonts */
  @font-face {
    font-family: 'Inter';
    src: url('{{ asset('fonts/email/Inter-Regular.woff2') }}') format('woff2');
    font-weight: 400;
    font-style: normal;
    mso-font-alt: 'Helvetica';
  }

  @font-face {
    font-family: 'Inter';
    src: url('{{ asset('fonts/email/Inter-Medium.woff2') }}') format('woff2');
    font-weight: 500;
    font-style: normal;
    mso-font-alt: 'Helvetica';
  }

  @font-face {
    font-family: 'Inter';
    src: url('{{ asset('fonts/email/Inter-Bold.woff2') }}') format('woff2');
    font-weight: 700;
    font-style: normal;
    mso-font-alt: 'Helvetica';
  }

  /* Custom Properties */
  :root {
    /* Typography */
    --font-primary: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    --font-size-base: 16px;
    --line-height-base: 1.5;
    --font-weight-normal: 400;
    --font-weight-medium: 500;
    --font-weight-bold: 700;

    /* Colors - Light Mode */
    --primary: #2563eb;
    --primary-light: #3b82f6;
    --primary-dark: #1d4ed8;
    --secondary: #1e40af;
    --accent: #fbbf24;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --text: #1a1a1a;
    --text-light: #666666;
    --text-lighter: #8f8f8f;
    --background: #f8f9fa;
    --background-alt: #ffffff;
    --border: #eaeaea;
    --shadow: rgba(0, 0, 0, 0.05);

    /* Spacing */
    --space-1: 4px;
    --space-2: 8px;
    --space-3: 12px;
    --space-4: 16px;
    --space-5: 20px;
    --space-6: 24px;
    --space-8: 32px;
    --space-10: 40px;
    --space-12: 48px;

    /* Border Radius */
    --radius-sm: 4px;
    --radius-md: 8px;
    --radius-lg: 12px;
    --radius-full: 9999px;
  }

  /* Dark Mode */
  @media (prefers-color-scheme: dark) {
    :root {
      --text: #f3f4f6;
      --text-light: #d1d5db;
      --text-lighter: #9ca3af;
      --background: #111827;
      --background-alt: #1f2937;
      --border: #374151;
      --shadow: rgba(0, 0, 0, 0.2);
    }

    .email-container {
      background-color: var(--background);
    }

    .feature-item {
      background-color: var(--background-alt);
    }

    img {
      filter: brightness(.8) contrast(1.2);
    }
  }

  /* Base Reset */
  body, html {
    margin: 0;
    padding: 0;
    width: 100%;
    font-family: var(--font-primary);
    line-height: var(--line-height-base);
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    background-color: var(--background);
    color: var(--text);
  }

  /* Container */
  .email-container {
    max-width: 600px;
    margin: 0 auto;
    padding: var(--space-5);
  }

  /* Typography */
  h1, h2, h3, p {
    font-family: var(--font-primary);
    color: var(--text);
    margin-bottom: var(--space-4);
  }

  /* Typography */
  h1, h2, h3, p {
    font-family: var(--font-primary);
    color: var(--text);
    margin-bottom: 16px;
  }

  h1 {
    font-size: 32px;
    line-height: 1.2;
    font-weight: var(--font-weight-bold);
    letter-spacing: -0.02em;
    margin-bottom: 24px;
  }

  h2 {
    font-size: 24px;
    line-height: 1.3;
    font-weight: var(--font-weight-bold);
    letter-spacing: -0.01em;
    margin-bottom: 20px;
  }

  h3 {
    font-size: 18px;
    line-height: 1.4;
    font-weight: var(--font-weight-medium);
  }

  p {
    font-size: var(--font-size-base);
    line-height: var(--line-height-base);
    font-weight: var(--font-weight-normal);
  }

  /* Enhanced Components */
  .header {
    text-align: center;
    padding: 32px 0;
  }

  .header h1 {
    margin-bottom: 16px;
  }

  .subtitle {
    font-family: var(--font-primary);
    font-size: 18px;
    color: var(--text-light);
    line-height: 1.6;
    margin-bottom: 32px;
    font-weight: var(--font-weight-normal);
  }

  .hero {
    margin-bottom: 40px;
  }

  .hero-image {
    width: 100%;
    height: auto;
    border-radius: 8px;
    margin-bottom: 24px;
  }

  .feature-box {
    background-color: var(--background);
    border-radius: 12px;
    padding: 32px;
    margin-bottom: 32px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  }

  .feature-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 24px;
    padding: 16px;
    background: #ffffff;
    border-radius: 8px;
    transition: transform 0.2s ease;
  }

  .feature-item:hover {
    transform: translateY(-2px);
  }

  .feature-icon {
    width: 48px;
    height: 48px;
    margin-right: 16px;
    flex-shrink: 0;
  }

  /* Card Variations */
  .card {
    padding: var(--space-6);
    background: var(--background-alt);
    border-radius: var(--radius-md);
    margin-bottom: var(--space-6);
    border: 1px solid var(--border);
  }

  .card--elevated {
    box-shadow: 0 4px 6px var(--shadow);
  }

  .card--highlighted {
    border-left: 4px solid var(--primary);
  }

  /* Buttons */
  .button {
    font-family: var(--font-primary);
    display: inline-block;
    padding: var(--space-3) var(--space-6);
    background-color: var(--primary);
    color: #ffffff !important;
    text-decoration: none;
    border-radius: var(--radius-md);
    font-weight: var(--font-weight-medium);
    font-size: var(--font-size-base);
    margin: var(--space-4) 0;
    transition: all 0.2s ease;
  }

  .button--secondary {
    background-color: var(--secondary);
  }

  .button--success {
    background-color: var(--success);
  }

  .button--warning {
    background-color: var(--warning);
  }

  .button--danger {
    background-color: var(--danger);
  }

  .button--outline {
    background-color: transparent;
    border: 2px solid var(--primary);
    color: var(--primary) !important;
  }

  .social-links {
    text-align: center;
    padding: 24px 0;
  }

  .social-icon {
    width: 24px;
    height: 24px;
    margin: 0 8px;
  }

  .signature {
    font-family: var(--font-primary);
    text-align: center;
    margin: 32px 0;
  }

  .signature strong {
    font-weight: var(--font-weight-medium);
    color: var(--text);
  }

  .footer {
    font-family: var(--font-primary);
    text-align: center;
    padding: 24px 0;
    border-top: 1px solid #eaeaea;
    margin-top: 32px;
    color: var(--text-light);
    font-size: 14px;
  }

  /* Badges */
  .badge {
    display: inline-block;
    padding: var(--space-1) var(--space-2);
    border-radius: var(--radius-full);
    font-size: 12px;
    font-weight: var(--font-weight-medium);
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .badge--primary { background-color: var(--primary-light); color: #ffffff; }
  .badge--success { background-color: var(--success); color: #ffffff; }
  .badge--warning { background-color: var(--warning); color: #ffffff; }
  .badge--danger { background-color: var(--danger); color: #ffffff; }

  /* Dividers */
  .divider {
    border: 0;
    border-top: 1px solid var(--border);
    margin: var(--space-8) 0;
  }

  .divider--dashed {
    border-top-style: dashed;
  }

  /* Quote */
  .quote {
    border-left: 4px solid var(--primary);
    padding-left: var(--space-4);
    margin: var(--space-6) 0;
    font-style: italic;
    color: var(--text-light);
  }

  /* Stats */
  .stats {
    display: flex;
    justify-content: space-between;
    text-align: center;
    margin: var(--space-6) 0;
  }

  .stat-item {
    flex: 1;
  }

  .stat-value {
    font-size: 24px;
    font-weight: var(--font-weight-bold);
    color: var(--primary);
    margin-bottom: var(--space-2);
  }

  .stat-label {
    font-size: 14px;
    color: var(--text-light);
  }

  /* Timeline */
  .timeline {
    position: relative;
    padding-left: var(--space-8);
  }

  .timeline::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: var(--border);
  }

  .timeline-item {
    position: relative;
    margin-bottom: var(--space-6);
  }

  .timeline-item::before {
    content: '';
    position: absolute;
    left: calc(-1 * var(--space-8));
    top: var(--space-2);
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: var(--primary);
    border: 2px solid var(--background-alt);
  }

  /* Progress Bar */
  .progress {
    background-color: var(--background);
    border-radius: var(--radius-full);
    height: 8px;
    overflow: hidden;
  }

  .progress-bar {
    height: 100%;
    background-color: var(--primary);
    transition: width 0.3s ease;
  }

  /* Enhanced Responsive Design */
  @media only screen and (max-width: 600px) {
    :root {
      --font-size-base: 15px;
    }

    .email-container {
      width: 100% !important;
      padding: 16px !important;
    }

    h1 {
      font-size: 28px;
    }

    h2 {
      font-size: 22px;
    }

    h3 {
      font-size: 17px;
    }

    .subtitle {
      font-size: 16px;
    }

    .feature-item {
      flex-direction: column;
      text-align: center;
    }

    .feature-icon {
      margin: 0 auto 16px;
    }

    .stats {
      flex-direction: column;
      gap: var(--space-4);
    }

    .stat-item {
      padding: var(--space-4);
      border-bottom: 1px solid var(--border);
    }

    .stat-item:last-child {
      border-bottom: none;
    }

    .timeline {
      padding-left: var(--space-6);
    }

    .timeline-item::before {
      left: calc(-1 * var(--space-6));
    }
  }

  /* Print Styles */
  @media print {
    .email-container {
      max-width: none;
      padding: 0;
    }

    .button {
      border: 1px solid var(--text);
      color: var(--text) !important;
      background: none;
    }

    .progress {
      border: 1px solid var(--text);
    }
  }

  /* MSO Fallbacks */
  [style*="Inter"] {
    font-family: 'Inter', Helvetica, Arial, sans-serif !important;
  }
</style>
