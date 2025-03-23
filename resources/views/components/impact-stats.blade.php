@props(['metrics'])

<section class="py-16 px-4">
  <div class="max-w-7xl mx-auto">
    <h2 class="text-3xl md:text-4xl font-bold text-center font-display mb-12 text-ca-primary dark:text-ca-highlight">
      Our Impact in Numbers
    </h2>

    <!-- Impact Stats Grid -->
    <div class="grid md:grid-cols-3 gap-8 mb-16">
      @foreach($metrics as $metric)
        <impact-card :metric="{{Js::from($metric)}}"></impact-card>
      @endforeach
    </div>
  </div>
</section>
