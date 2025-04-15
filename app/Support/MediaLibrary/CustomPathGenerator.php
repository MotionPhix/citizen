<?php

namespace App\Support\MediaLibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CustomPathGenerator implements PathGenerator
{
  public function getPath(Media $media): string
  {
    return sprintf(
      '%s/%s/%s/',
      $media->model_type::$mediaFolder ?? strtolower(class_basename($media->model_type)),
      $media->model_id,
      $media->collection_name
    );
  }

  public function getPathForConversions(Media $media): string
  {
    return $this->getPath($media) . 'conversions/';
  }

  public function getPathForResponsiveImages(Media $media): string
  {
    return $this->getPath($media) . 'responsive/';
  }
}
