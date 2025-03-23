@props(['metrics'])

<section class="py-16">
  <h2 class="text-3xl font-bold text-center font-display mb-12 text-ca-primary dark:text-ca-highlight">
    Our Impact in Numbers
  </h2>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    @foreach($metrics as $metric)
      <impact-card :metric="$metric" />
    @endforeach
  </div>
</section>
