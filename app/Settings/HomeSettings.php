<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class HomeSettings extends Settings
{
    // Khối Giới thiệu trên trang chủ
    public ?string $intro_title;
    public ?string $intro_description;
    public ?string $intro_image;

    // Điểm nổi bật giới thiệu (Features)
    // [{icon, title, description}]
    public ?array $intro_features;

    // Video giới thiệu
    public ?string $video_url;
    public ?string $video_title;
    public ?string $video_file;

    // Bộ đếm thống kê (Counters)
    public ?array $counters;

    // Tiêu đề & Mô tả các khối trên trang chủ
    public ?string $services_title;
    public ?string $services_description;
    public ?string $fields_title;
    public ?string $fields_description;
    public ?string $projects_title;
    public ?string $projects_description;
    public ?string $products_title;
    public ?string $products_description;
    public ?string $posts_title;
    public ?string $posts_description;

    public static function group(): string
    {
        return 'home';
    }
}
