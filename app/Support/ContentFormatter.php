<?php

namespace App\Support;

class ContentFormatter
{
  public static function removeImageLinks(string $content): string
  {
    // This pattern matches <a> tags that contain a <figure> element and replaces them with just the figure content
    return preg_replace(
      '/<a[^>]*>(<figure.*?<\/figure>)<\/a>/s',
      '$1',
      $content
    );
  }
}
