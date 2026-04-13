<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\Partner;
use App\Settings\IntroSettings;
use App\Settings\GeneralSettings;
use Awcodes\Curator\Models\Media;

class IntroController extends Controller
{
    public function index()
    {
        $intro   = app(IntroSettings::class);
        $setting = app(GeneralSettings::class);

        // Ảnh banner
        $bannerMedia = $intro->page_banner_id
            ? Media::find($intro->page_banner_id)
            : null;

        // Ảnh câu chuyện
        $storyMedia = $intro->story_image_id
            ? Media::find($intro->story_image_id)
            : null;

        // Thumbnail video
        $videoThumbnail = $intro->video_thumbnail_id
            ? Media::find($intro->video_thumbnail_id)
            : $storyMedia;

        // Embed URL từ YouTube / Vimeo
        $videoEmbed = $this->toEmbedUrl($intro->video_url);

        // Đội ngũ — lấy tất cả active
        $teams = Team::with('image')->get();

        // Đối tác — lấy active, sắp xếp theo sort_order
        $partners = Partner::where('status', 1)
            ->orderBy('sort_order')
            ->get();

        return view('frontend.intro', compact(
            'intro', 'setting',
            'bannerMedia', 'storyMedia',
            'videoEmbed', 'videoThumbnail',
            'teams', 'partners'
        ));
    }

    /**
     * Chuyển YouTube/Vimeo watch URL sang embed URL.
     */
    private function toEmbedUrl(?string $url): ?string
    {
        if (!$url) return null;

        // YouTube: youtube.com/watch?v=ID hoặc youtu.be/ID
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([A-Za-z0-9_-]{11})/', $url, $m)) {
            return "https://www.youtube.com/embed/{$m[1]}?autoplay=1&rel=0";
        }

        // Vimeo: vimeo.com/ID
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $m)) {
            return "https://player.vimeo.com/video/{$m[1]}?autoplay=1";
        }

        return $url;
    }
}
