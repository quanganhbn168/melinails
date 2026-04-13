# CNETPos — Hệ thống quản lý nội dung & bán hàng

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-red?style=for-the-badge&logo=laravel" alt="Laravel 11">
  <img src="https://img.shields.io/badge/Filament-5.x-orange?style=for-the-badge&logo=filament" alt="Filament 5">
  <img src="https://img.shields.io/badge/Livewire-4.x-blue?style=for-the-badge&logo=livewire" alt="Livewire 4">
  <img src="https://img.shields.io/badge/PHP-8.2+-purple?style=for-the-badge&logo=php" alt="PHP 8.2+">
</p>

## Giới thiệu

**CNETPos** là hệ thống CMS + frontend thương mại điện tử được xây dựng trên nền tảng **Laravel 11**, tích hợp bảng quản trị hiện đại bằng **Filament v5**. Hệ thống hỗ trợ quản lý sản phẩm, dịch vụ, dự án, bài viết, banner quảng cáo, menu động, và nhiều module khác phục vụ vận hành website doanh nghiệp chuyên nghiệp.

---

## Tech Stack

| Layer | Công nghệ |
|---|---|
| Backend Framework | Laravel 11 |
| Admin Panel | Filament v5 |
| Frontend Reactive | Livewire v4 |
| Media Management | Curator (awcodes/filament-curator) |
| Permission & Shield | Spatie Permission + Filament Shield |
| Settings | Spatie Laravel Settings |
| Sitemap | Spatie Laravel Sitemap |
| Rich Text Editor | awcodes/richer-editor |
| Database | MySQL |
| Frontend Build | Vite + Tailwind CSS |

---

## Modules quản trị (Filament Resources)

| Module | Mô tả |
|---|---|
| **Slides** | Quản lý banner/slider trang chủ (Hero, Popup, Brand...) |
| **Products** | Sản phẩm, danh mục, thuộc tính |
| **Services** | Dịch vụ, danh mục dịch vụ |
| **Projects** | Dự án, danh mục dự án |
| **Posts** | Bài viết, danh mục bài viết |
| **Fields** | Lĩnh vực hoạt động |
| **Brands** | Thương hiệu đối tác |
| **Teams** | Thành viên nội bộ |
| **Testimonials** | Đánh giá & cảm nhận khách hàng |
| **Sample Reviews** | Phản hồi mẫu |
| **Videos** | Quản lý video giới thiệu |
| **Partners** | Đối tác |
| **Menus** | Menu động + Menu Builder (Livewire) |
| **Contacts** | Liên hệ, tư vấn, đại lý |
| **Tags / Attributes** | Nhãn dán & thuộc tính sản phẩm |
| **Careers** | Tuyển dụng |
| **Intros** | Giới thiệu công ty |
| **Users** | Quản lý người dùng & phân quyền |
| **Settings** | Cài đặt website (Spatie Settings) |

---

## Cài đặt

### Yêu cầu hệ thống

- PHP >= 8.2
- Composer >= 2.x
- Node.js >= 18.x
- MySQL >= 8.0

### Các bước cài đặt

```bash
# 1. Clone repository
git clone https://github.com/quanganhbn168/cnetpos.git
cd cnetpos

# 2. Cài đặt dependencies PHP
composer install

# 3. Cài đặt dependencies Node
npm install

# 4. Tạo file .env
cp .env.example .env
php artisan key:generate

# 5. Cấu hình database trong .env rồi chạy migration
php artisan migrate --seed

# 6. Tạo storage link
php artisan storage:link

# 7. Publish Filament Shield
php artisan shield:install --fresh

# 8. Build frontend assets
npm run build
```

### Chạy môi trường dev

```bash
# Terminal 1 — Laravel
php artisan serve

# Terminal 2 — Vite
npm run dev
```

---

## Cấu trúc thư mục quan trọng

```
app/
├── Filament/
│   ├── Pages/          # ManageSettings, Dashboard
│   └── Resources/      # 27 Filament Resources
├── Models/             # Eloquent Models
├── Enums/              # Enums (SliderType, ...)
├── Settings/           # Spatie Settings classes
├── Support/            # Helper classes
└── Traits/             # HasSeo, HasSlug, HasCategoryTree

resources/
├── css/filament/       # Filament theme override
├── views/
│   ├── frontend/       # Frontend Blade templates
│   ├── components/     # Blade Components
│   ├── partials/       # Layout partials
│   └── livewire/       # Livewire views
```

---

## Phân quyền

Hệ thống sử dụng **Spatie Laravel Permission** kết hợp **Filament Shield** để quản lý vai trò và quyền truy cập chi tiết cho từng Resource trong bảng quản trị.

```bash
# Tạo tài khoản Super Admin
php artisan make:filament-user

# Sync lại toàn bộ quyền
php artisan shield:generate --all
```

---

## License

Dự án nội bộ — All rights reserved © CNETPos.
