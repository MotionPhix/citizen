<x-email-layout>
  <div class="header">
    <img
      src="{{ asset('images/email/logo.png') }}"
      alt="Citizen Alliance Logo"
      style="width: auto; height: 80px; margin-bottom: 24px;">

    <h1>Welcome to the movement for change!</h1>

    <p class="subtitle">
      You've joined over {{ \App\Models\Subscriber::count() + 100 }} engaged citizens working<br>
      together for a better Malawi.
    </p>
  </div>

  <div class="hero">
    <img src="{{ asset('images/email/hero.jpg') }}" alt="Community Impact" class="hero-image">
  </div>

  <div class="content">
    <p>Dear {{ $subscriber->name ?: 'Change Maker' }},</p>

    <p>
      We're thrilled to have you join our community of passionate citizens committed to
      building a stronger, more prosperous Malawi. Your decision to stay informed and
      engaged is the first step toward meaningful change.
    </p>

    <div class="feature-box">
      <h2>3 Ways We're Making an Impact</h2>

      <div class="feature-item">
        <img src="{{ asset('images/email/icons/civic-education.png') }}"
             alt="Civic Education"
             class="feature-icon">
        <div>
          <h3>Civic Education</h3>
          <p>Empowering citizens with knowledge about their rights and responsibilities.</p>
        </div>
      </div>

      <div class="feature-item">
        <img src="{{ asset('images/email/icons/community-projects.png') }}"
             alt="Community Projects"
             class="feature-icon">
        <div>
          <h3>Community Projects</h3>
          <p>Implementing initiatives that directly improve lives in local communities.</p>
        </div>
      </div>

      <div class="feature-item">
        <img src="{{ asset('images/email/icons/advocacy.png') }}"
             alt="Advocacy"
             class="feature-icon">
        <div>
          <h3>Policy Advocacy</h3>
          <p>Working with stakeholders to promote policies that benefit all Malawians.</p>
        </div>
      </div>
    </div>

    <div class="whats-next">
      <h2>What's Next?</h2>
      <p>Look out for our bi-weekly newsletters featuring:</p>
      <ul>
        <li>📰 Latest community updates</li>
        <li>🎯 Upcoming project announcements</li>
        <li>🤝 Opportunities to get involved</li>
        <li>📚 Educational resources</li>
      </ul>
    </div>

    <a href="{{ config('app.url') }}" class="button">
      Visit Our Website
    </a>

    <div class="signature">
      <p>Together for a Better Malawi,</p>
      <p><strong>The Citizen Alliance Team</strong></p>
    </div>

    <div class="social-links">
      <a href="#"><img src="{{ asset('images/email/icons/facebook.png') }}" alt="Facebook" class="social-icon"></a>
      <a href="#"><img src="{{ asset('images/email/icons/twitter.png') }}" alt="Twitter" class="social-icon"></a>
      <a href="#"><img src="{{ asset('images/email/icons/instagram.png') }}" alt="Instagram" class="social-icon"></a>
    </div>

    <div class="footer">
      <p class="small">
        You received this email because you subscribed to Citizen Alliance's newsletter.<br>
        To stop receiving these emails, <a href="{{ $unsubscribeUrl }}">unsubscribe here</a>.
      </p>
    </div>
  </div>
</x-email-layout>
