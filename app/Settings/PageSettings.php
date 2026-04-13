<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class PageSettings extends Settings
{
    // Trang Sản phẩm
    public ?string $products_title;
    public ?string $products_headline;
    public ?string $products_description;
    public ?string $products_content;
    public ?string $products_banner;
    public ?string $products_cta_title;
    public ?string $products_cta_description;
    public ?string $products_cta_link;

    // Trang Dự án
    public ?string $projects_title;
    public ?string $projects_headline;
    public ?string $projects_description;
    public ?string $projects_content;
    public ?string $projects_banner;
    public ?string $projects_cta_title;
    public ?string $projects_cta_description;
    public ?string $projects_cta_link;

    // Trang Dịch vụ
    public ?string $services_title;
    public ?string $services_headline;
    public ?string $services_description;
    public ?string $services_content;
    public ?string $services_banner;
    public ?string $services_cta_title;
    public ?string $services_cta_description;
    public ?string $services_cta_link;

    // Trang Lĩnh vực
    public ?string $fields_title;
    public ?string $fields_headline;
    public ?string $fields_description;
    public ?string $fields_content;
    public ?string $fields_banner;
    public ?string $fields_cta_title;
    public ?string $fields_cta_description;
    public ?string $fields_cta_link;

    // Trang Tin tức
    public ?string $posts_title;
    public ?string $posts_headline;
    public ?string $posts_description;
    public ?string $posts_content;
    public ?string $posts_banner;
    public ?string $posts_cta_title;
    public ?string $posts_cta_description;
    public ?string $posts_cta_link;

    // Trang Giới thiệu
    public ?string $intro_title;
    public ?string $intro_headline;
    public ?string $intro_description;
    public ?string $intro_content;
    public ?string $intro_banner;
    public ?string $intro_cta_title;
    public ?string $intro_cta_description;
    public ?string $intro_cta_link;

    // Trang Tuyển dụng
    public ?string $careers_title;
    public ?string $careers_headline;
    public ?string $careers_description;
    public ?string $careers_content;
    public ?string $careers_banner;
    public ?string $careers_cta_title;
    public ?string $careers_cta_description;
    public ?string $careers_cta_link;

    // Trang Liên hệ
    public ?string $contact_title;
    public ?string $contact_headline;
    public ?string $contact_description;
    public ?string $contact_content;
    public ?string $contact_banner;
    public ?string $contact_cta_title;
    public ?string $contact_cta_description;
    public ?string $contact_cta_link;

    public static function group(): string
    {
        return 'page';
    }
}
