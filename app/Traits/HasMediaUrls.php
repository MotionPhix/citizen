<?php

namespace App\Traits;

trait HasMediaUrls
{
  public function getImageUrl(string $conversion = ''): ?string
  {
    if (!$this->hasMedia('blog_images')) {
      return null;
    }

    return $this->getFirstMediaUrl('blog_images', $conversion);
  }

  public function getThumbnailUrl(): ?string
  {
    return $this->getImageUrl('thumbnail');
  }

  public function getPreviewUrl(): ?string
  {
    return $this->getImageUrl('preview');
  }

  public function getHeroUrl(): ?string
  {
    return $this->getImageUrl('hero');
  }
}
