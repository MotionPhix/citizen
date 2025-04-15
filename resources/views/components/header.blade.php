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
      'name' => 'Blog',
      'url' => route('blogs.index'),
      'isActive' => request()->routeIs('blogs.*')
    ],
  ]) }}">
</app-header>
