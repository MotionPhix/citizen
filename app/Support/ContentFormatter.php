<?php

namespace App\Support;

class ContentFormatter
{
  public static function removeImageLinks(string $content): string
  {
    // Remove <a> tags that wrap <figure> elements (Filament RichEditor behavior)
    $content = preg_replace(
      '/<a[^>]*>(<figure.*?<\/figure>)<\/a>/s',
      '$1',
      $content
    );

    // Remove <a> tags that wrap standalone <img> elements
    $content = preg_replace(
      '/<a[^>]*>(<img[^>]*>)<\/a>/s',
      '$1',
      $content
    );

    return $content;
  }

  public static function extractImageCaption(string $figureContent): ?string
  {
    // Try to extract filename from Trix data-trix-attachment
    if (preg_match('/data-trix-attachment="([^"]*)"/', $figureContent, $matches)) {
      $attachmentData = html_entity_decode($matches[1]);
      $data = json_decode($attachmentData, true);

      if (isset($data['filename'])) {
        // Clean up the filename to make it more readable
        $filename = $data['filename'];
        // Remove file extension
        $filename = preg_replace('/\.[^.]+$/', '', $filename);
        // Replace underscores and hyphens with spaces
        $filename = str_replace(['_', '-'], ' ', $filename);
        // Capitalize first letter of each word
        $filename = ucwords($filename);

        return $filename;
      }
    }

    // Fallback: try to extract from existing figcaption
    if (preg_match('/<figcaption[^>]*>(.*?)<\/figcaption>/s', $figureContent, $matches)) {
      return strip_tags(trim($matches[1]));
    }

    return null;
  }

  public static function processImages(string $content): string
  {
    // Process <figure> elements containing images (Trix/Filament structure)
    $content = preg_replace_callback(
      '/<figure[^>]*>(.*?)<\/figure>/s',
      function ($matches) {
        $figureContent = $matches[1];

        // Extract the image from the figure
        if (preg_match('/<img([^>]*)>/i', $figureContent, $imgMatches)) {
          $imgAttributes = $imgMatches[1];

          // Extract caption
          $caption = self::extractImageCaption($matches[0]);

          // Clean up the img attributes and add responsive classes
          $imgAttributes = preg_replace('/class="[^"]*"/', '', $imgAttributes);
          $newImg = '<img' . $imgAttributes . ' class="w-full h-auto rounded-lg shadow-md mx-auto block max-w-4xl">';

          // Build the complete image container with optional caption
          $imageHtml = '<div class="text-center">' . $newImg;

          if ($caption) {
            $imageHtml .= '<p class="mb-8 text-sm text-gray-600 dark:text-gray-400 text-center">' . htmlspecialchars($caption) . '</p>';
          }

          $imageHtml .= '</div>';

          return $imageHtml;
        }

        return $matches[0]; // Return original if no image found
      },
      $content
    );

    return $content;
  }

  public static function fixImageUrls(string $content): string
  {
    $appUrl = rtrim(config('app.url'), '/');

    // Handle different URL scenarios:
    // 1. Fix URLs that point to production when in development
    $content = preg_replace(
      '/https?:\/\/citizenalliance\.ultrashots\.net/',
      $appUrl,
      $content
    );

    // 2. Fix URLs that point to development when in production
    $content = preg_replace(
      '/https?:\/\/citizen-alliance\.test/',
      $appUrl,
      $content
    );

    // 3. Fix storage URLs that are relative
    $content = preg_replace(
      '/src="\/storage\/([^"]*)"/',
      'src="' . $appUrl . '/storage/$1"',
      $content
    );

    // 4. Fix href attributes in links as well
    $content = preg_replace(
      '/href="\/storage\/([^"]*)"/',
      'href="' . $appUrl . '/storage/$1"',
      $content
    );

    // 5. Fix any other relative image URLs
    $content = preg_replace(
      '/src="(?!https?:\/\/)([^"]*)"/',
      'src="' . $appUrl . '/$1"',
      $content
    );

    return $content;
  }

  public static function cleanTrixAttributes(string $content): string
  {
    // Remove Trix-specific data attributes that are not needed for display
    $content = preg_replace('/data-trix-[^=]*="[^"]*"/i', '', $content);

    // Clean up attachment classes
    $content = preg_replace('/class="attachment[^"]*"/i', '', $content);

    // Remove empty class attributes
    $content = preg_replace('/class=""\s*/', '', $content);

    return $content;
  }

  public static function cleanHtml(string $content): string
  {
    // Remove empty paragraphs
    $content = preg_replace('/<p[^>]*>[\s&nbsp;]*<\/p>/i', '', $content);

    // Remove empty divs
    $content = preg_replace('/<div[^>]*>[\s&nbsp;]*<\/div>/i', '', $content);

    // Clean up multiple consecutive line breaks
    $content = preg_replace('/(<br\s*\/?>\s*){3,}/i', '<br><br>', $content);

    // Remove extra whitespace between tags
    $content = preg_replace('/>\s+</', '><', $content);

    return trim($content);
  }

  public static function formatContent(string $content): string
  {
    // Apply all formatting steps in the correct order
    $content = self::fixImageUrls($content);           // Fix URLs first (handles dev/prod switching)
    $content = self::removeImageLinks($content);       // Remove link wrappers
    $content = self::processImages($content);          // Process images and extract captions
    $content = self::cleanTrixAttributes($content);    // Clean Trix attributes
    $content = self::cleanHtml($content);              // Final cleanup

    return $content;
  }
}
