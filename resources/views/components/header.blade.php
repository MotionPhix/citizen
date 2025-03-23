<app-header
  :links="{{ Js::from([
    [
      'name' => 'Home',
      'url' => route('home'),
      'isActive' => request()->routeIs('home')
    ],
    [
      'name' => 'Organisation',
      'url' => route('about'),
      'isActive' => request()->routeIs('about')
    ],
    [
      'name' => 'Projects',
      'url' => route('projects.index'),
      'isActive' => request()->routeIs('projects.*')
    ],
    [
      'name' => 'Contact',
      'url' => route('contact.index'),
      'isActive' => request()->routeIs('contact.*')
    ],
    [
      'name' => 'Donate',
      'url' => route('donation.form'),
      'isActive' => request()->routeIs('donation.*')
    ]
  ]) }}">
</app-header>
