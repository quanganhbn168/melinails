<?php

$dir = __DIR__ . '/resources/views';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
foreach ($iterator as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $path = $file->getRealPath();
        $content = file_get_contents($path);
        
        $original = $content;

        // Pattern: optional($VAR->mainImage())->getUrl() or ->url()
        // Wait, looking at the grep, it is usually: optional($xxx->mainImage())->url()
        // Or !empty($xxx->image) ? asset($xxx->image) : ...
        
        // CNETPOS BLADE MEDIA REFACTOR SCRIPT 
        // 1. replace optional($xxx->mainImage())->url() with $xxx->image?->url
        $content = preg_replace('/optional\(\$([a-zA-Z0-9_]+)->mainImage\(\)\)->url\(\)/', '\$$1->image?->url', $content);
        
        // 2. replace optional($xxx->bannerImage())->url() with $xxx->banner?->url
        $content = preg_replace('/optional\(\$([a-zA-Z0-9_]+)->bannerImage\(\)\)->url\(\)/', '\$$1->banner?->url', $content);

        // 3. replace $xxx->mainImage() => $xxx->image 
        $content = preg_replace('/\$([a-zA-Z0-9_]+)->mainImage\(\)/', '\$$1->image', $content);

        // 4. replace !empty($xxx->image) ? asset($xxx->image) : ... With standard fallback
        // Because $xxx->image is now an Object, asset($xxx->image) will break!
        // We will replace asset($xxx->image) with \$$1->image?->url
        $content = preg_replace('/asset\(\$([a-zA-Z0-9_]+)->image\)/', '\$$1->image?->url', $content);
        
        // 5. replace !empty($xxx->image) with !empty($xxx->image_id) to check database column safely
        $content = preg_replace('/!empty\(\$([a-zA-Z0-9_]+)->image\)/', '\$$1->image_id', $content);

        // 6. replace $xxx->image ? ... with $xxx->image_id ?
        // Be careful here. Let's do $xxx->image ? $xxx->image?->url : asset(...)
        
        if ($content !== $original) {
            file_put_contents($path, $content);
            echo "Updated: $path\n";
        }
    }
}
