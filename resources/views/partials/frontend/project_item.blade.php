{{-- resources/views/partials/frontend/project_item.blade.php --}}
<div class="swiper-slide">
    <div class="project-item">
        {{-- Phần ảnh nền --}}
        <div class="project-image">
            <img src="{{ !empty($project->image) ? asset($project->image) : (optional($project->mainImage())->url() ?: asset('images/setting/no-image.png')) }}" alt="{{ $project->name }}" loading="lazy">
        </div>

        {{-- Lớp overlay sẽ hiện ra khi hover --}}
        <a href="{{ route('frontend.slug.handle', $project->slug ?? '#') }}" class="project-overlay">
            <div class="project-info">
                <h3 class="project-name">{{ $project->name }}</h3>
                <div class="project-owner">
                    <p>
                        <i class="fa-regular fa-building"></i>
                        <strong>Chủ đầu tư:</strong> {{ $project->owner }}
                    </p>
                    <p>
                        <i class="fa-solid fa-tag"></i>
                        <strong>Giá thầu:</strong> {{ $project->bid_price ?? 'Đang cập nhật' }}
                    </p>
                </div>
            </div>
        </a>
    </div>
</div>