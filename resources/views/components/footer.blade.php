<app-footer
  :routes="{{Js::from([
    ['name' => 'Home', 'url' => 'home'],
    ['name' => 'Organisation', 'url' => 'about'],
    ['name' => 'Projects', 'url' => 'projects.index'],
    ['name' => 'Contact Us', 'url' => 'contact.index'],
    ['name' => 'Donate', 'url' => 'donation.form']
  ])}}"
  :socials="{{Js::from([
    [
      "platform" => "facebook",
      "url" => "https://facebook.com/citizenalliance",
      "label" => "Follow us on Facebook"
    ],
    [
      "platform" => "twitter",
      "url" => "https://twitter.com/citizenalliance",
      "label" => "Follow us on Twitter"
    ],
    [
      "platform" => "linkedin",
      "url" => "https://linkedin.com/company/citizenalliance",
      "label" => "Connect with us on LinkedIn"
    ],
    [
      "platform" => "instagram",
      "url" => "https://instagram.com/citizenalliance",
      "label" => "Follow us on Instagram"
    ]
  ])}}">
</app-footer>
