@extends('layouts.master')
@section('title', $service->name)
@section('meta_description', Str::limit(strip_tags($service->content), 155))
@section('meta_image', optional($service->image) ? $service->image?->url : '')
@section('content')

<x-frontend.leaderboard
    :image="$bannerUrl ?: ($service->image?->url ?? $pageSettings->services_banner)"
    :title="$service->name"
    :subline="$service->category?->name ?? 'Dịch vụ'"
    :description="$service->description ?: Str::limit(strip_tags((string) $service->content), 180)"
    :breadcrumb="$breadcrumbItems"
    :actions="[
        ['label' => 'Yêu cầu báo giá', 'url' => '#apply-form-section', 'icon' => 'fas fa-file-invoice', 'style' => 'primary'],
        ['label' => 'Liên hệ tư vấn', 'url' => route('contact.show'), 'icon' => 'fas fa-phone-alt', 'style' => 'secondary'],
    ]"
/>

<div class="bg-white dark:bg-gray-900 py-12 md:py-20 border-b border-gray-100 dark:border-gray-800">
    <div class="max-w-screen-xl mx-auto px-4">
        
        {{-- Phần giới thiệu dịch vụ (Top Hero Section) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 mb-16 items-start">
            {{-- Cột hình ảnh --}}
            <div class="rounded-sm overflow-hidden shadow-2xl border border-gray-100 dark:border-gray-800">
                <img src="{{ $service->image ? asset($service->image->url) : asset('images/setting/no-image.png') }}" 
                     alt="{{ $service->name }}" 
                     class="w-full h-auto object-cover aspect-[4/3] @if(!$service->image) grayscale @endif">
            </div>

            {{-- Cột thông tin --}}
            <div class="flex flex-col justify-center h-full">
                <h1 class="text-3xl md:text-5xl font-black text-gray-900 dark:text-white leading-tight mb-4">
                    {{ $service->name }}
                </h1>
                
                @if(!empty($service->description))
                <div class="text-lg md:text-xl font-bold text-gray-800 dark:text-gray-300 mb-6 leading-snug">
                    {!! nl2br(e($service->description)) !!}
                </div>
                @endif
                
                <div class="flex flex-wrap gap-4 mt-auto pt-6 border-t border-gray-100 dark:border-gray-800">
                    <a href="{{ route('contact.show') }}" class="inline-flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold uppercase tracking-wider text-sm px-8 py-4 rounded-sm transition-colors shadow-md">
                        <i class="fas fa-file-invoice"></i> Yêu cầu báo giá
                    </a>
                    <a href="tel:{{ $setting->phone ?? '' }}" class="inline-flex items-center justify-center gap-2 bg-brand-800 hover:bg-brand-900 text-white font-bold uppercase tracking-wider text-sm px-8 py-4 rounded-sm transition-colors shadow-md">
                        <i class="fas fa-phone-alt"></i> Gọi tư vấn
                    </a>
                </div>

                {{-- Chia sẻ --}}
                <div class="mt-8 pt-6 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <span class="font-bold text-gray-900 dark:text-white uppercase text-sm tracking-wider">Chia sẻ dịch vụ:</span>
                    <x-social-share :title="$service->name" />
                </div>
            </div>
        </div>

        {{-- NỘI DUNG CHÍNH BÀI VIẾT (Full-width) --}}
        <div class="max-w-4xl mx-auto">
            <article class="prose prose-lg md:prose-xl max-w-none prose-blue dark:prose-invert">
                @if(empty($service->content) || trim(strip_tags($service->content)) == '')
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-sm p-10 text-center border border-dashed border-gray-300 dark:border-gray-600 my-12">
                        <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-sm flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-file-pen text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Nội dung đang cập nhật</h3>
                        <p class="text-gray-500 dark:text-gray-400">Thông tin chi tiết về bài viết/dịch vụ này đang được chúng tôi hoàn thiện.</p>
                    </div>
                @else
                    {!! $service->content !!}
                @endif
            </article>
        </div>
    </div>
</div>

{{-- LANDING PAGE SECTIONS (Trải Full-Width) --}}

{{-- DỰ ÁN LIÊN QUAN ĐẾN DỊCH VỤ --}}
@if(isset($service->projects) && $service->projects->count() > 0)
<section class="py-12 md:py-16 bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="mb-10 text-center">
            <h2 class="text-2xl md:text-3xl font-black uppercase text-gray-900 dark:text-white tracking-tight">Dự Án Tiêu Biểu</h2>
            <div class="w-16 h-1 bg-brand-600 mx-auto mt-4 mb-4"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($service->projects as $project)
                <x-frontend.card 
                    :href="$project->slug_url"
                    :image="$project->image ? $project->image->url : null"
                    :title="$project->name"
                    :description="$project->description"
                />
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- GÓI SẢN PHẨM / PHÂN HỆ --}}
@if(isset($service->products) && $service->products->count() > 0)
<section class="py-12 md:py-16 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="mb-10 text-center">
            <h2 class="text-2xl md:text-3xl font-black uppercase text-gray-900 dark:text-white tracking-tight">Gói Giải Pháp & Phân Hệ</h2>
            <div class="w-16 h-1 bg-brand-600 mx-auto mt-4 mb-4"></div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($service->products as $product)
                <a href="{{ $product->slug_url }}" class="bg-white dark:bg-gray-900 p-6 rounded-sm shadow-sm hover:shadow-md hover:-translate-y-1 transition-all border border-gray-100 dark:border-gray-700 flex flex-col h-full group">
                    <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-2 group-hover:text-brand-600 transition-colors">{{ $product->name }}</h3>
                    <div class="text-orange-600 font-bold mb-4">{{ $product->price > 0 ? number_format($product->price) . ' đ' : 'Báo giá linh hoạt' }}</div>
                    <div class="mt-auto text-brand-600 dark:text-brand-400 font-bold text-sm">Xem chi tiết &rarr;</div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- KIẾN THỨC VÀ HƯỚNG DẪN --}}
@if(isset($service->posts) && $service->posts->count() > 0)
<section class="py-12 md:py-16 bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="mb-10 text-center">
            <h2 class="text-2xl md:text-3xl font-black uppercase text-gray-900 dark:text-white tracking-tight">Kiến Thức & Tài Liệu</h2>
            <div class="w-16 h-1 bg-brand-600 mx-auto mt-4 mb-4"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($service->posts as $post)
                <a href="{{ $post->slug_url }}" class="flex bg-gray-50 dark:bg-gray-800 rounded-sm overflow-hidden border border-gray-100 dark:border-gray-700 hover:border-brand-300 transition-all group">
                    @if($post->image)
                        <img src="{{ asset($post->image->url) }}" alt="{{ $post->title }}" class="w-1/3 object-cover">
                    @endif
                    <div class="w-2/3 p-4 flex flex-col justify-center">
                        <h4 class="font-bold text-gray-900 dark:text-white line-clamp-2 text-sm leading-snug group-hover:text-brand-600 transition-colors">{{ $post->title }}</h4>
                        <time class="text-xs text-gray-500 mt-2">{{ $post->created_at->format('d/m/Y') }}</time>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- DỊCH VỤ CÙNG DANH MỤC --}}
@if(isset($relatedServices) && $relatedServices->count() > 0)
<section class="py-12 bg-gray-50 dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700">
    <div class="max-w-screen-xl mx-auto px-4">
        <h2 class="text-xl font-bold uppercase text-gray-900 dark:text-white mb-6 border-l-4 border-brand-600 pl-3">Các dịch vụ khác</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($relatedServices as $related)
                <a href="{{ $related->slug_url }}" class="group block bg-white dark:bg-gray-900 rounded-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:border-brand-500 transition-colors">
                    <div class="aspect-video relative overflow-hidden bg-gray-200 dark:bg-gray-700">
                        <img src="{{ asset($related->image->url ?? 'images/setting/no-image.png') }}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-3">
                        <h4 class="font-bold text-sm uppercase text-gray-800 dark:text-gray-200 group-hover:text-brand-600 transition-colors">
                            {{ $related->name }}
                        </h4>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- FORM BÁO GIÁ THEO DỊCH VỤ --}}
<section id="apply-form-section" class="py-12 bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 scroll-mt-24">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="mb-10 text-center">
            <h2 class="text-2xl font-black uppercase text-gray-900 dark:text-white tracking-tight">Yêu cầu báo giá</h2>
            <div class="w-16 h-1 bg-brand-600 mx-auto mt-4"></div>
            <p class="mt-4 text-gray-600 dark:text-gray-300">
                Để lại thông tin cho dịch vụ <strong>{{ $service->name }}</strong>, đội ngũ CNETPos sẽ liên hệ báo giá sớm nhất.
            </p>
        </div>

        <div class="max-w-4xl mx-auto bg-gray-50 dark:bg-gray-800 p-6 md:p-8 rounded-sm border border-gray-100 dark:border-gray-700">
            <form
                action="{{ route('consulting.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-5"
                x-ref="quoteForm"
                @submit.prevent="submitQuote($event)"
                x-data="{
                    budget: @js(old('budget')),
                    submitting: false,
                    formatBudget(value) {
                        const digits = String(value || '').replace(/\D/g, '');
                        return digits ? Number(digits).toLocaleString('vi-VN') : '';
                    },
                    notify(options) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire(options);
                            return;
                        }
                        alert(options.text || options.title || 'Đã xử lý.');
                    },
                    async submitQuote(event) {
                        if (this.submitting) return;

                        this.submitting = true;

                        const form = event.target;
                        const formData = new FormData(form);

                        try {
                            const response = await fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || '',
                                },
                                body: formData,
                            });

                            const data = await response.json().catch(() => ({}));

                            if (!response.ok) {
                                const firstError = data?.errors ? Object.values(data.errors)[0]?.[0] : null;
                                throw new Error(firstError || data?.message || 'Không thể gửi yêu cầu. Vui lòng thử lại.');
                            }

                            this.notify({
                                icon: 'success',
                                title: 'Gửi thành công',
                                text: data?.message || 'Yêu cầu báo giá đã được gửi.',
                                confirmButtonText: 'Đóng',
                            });

                            form.reset();
                            this.budget = '';

                            const details = form.querySelector('textarea[name=details]');
                            if (details) {
                                details.value = `Dịch vụ quan tâm: {{ $service->name }}\n`;
                            }
                        } catch (error) {
                            this.notify({
                                icon: 'error',
                                title: 'Có lỗi xảy ra',
                                text: error?.message || 'Không thể gửi yêu cầu. Vui lòng thử lại.',
                                confirmButtonText: 'Đóng',
                            });
                        } finally {
                            this.submitting = false;
                        }
                    },
                    init() {
                        this.budget = this.formatBudget(this.budget);
                    }
                }"
            >
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wide">
                            Họ và tên <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            required
                            value="{{ old('name') }}"
                            placeholder="Ví dụ: Nguyễn Văn A"
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-brand-500 focus:border-brand-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        >
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wide">
                            Số điện thoại <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="tel"
                            name="phone"
                            required
                            value="{{ old('phone') }}"
                            placeholder="Ví dụ: 0987654321"
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-brand-500 focus:border-brand-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wide">Email</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Ví dụ: abc@domain.com"
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-brand-500 focus:border-brand-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        >
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wide">Công ty / Cửa hàng</label>
                        <input
                            type="text"
                            name="company"
                            value="{{ old('company') }}"
                            placeholder="Ví dụ: Công ty TNHH ABC"
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-brand-500 focus:border-brand-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        >
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wide">Địa chỉ triển khai</label>
                    <input
                        type="text"
                        name="address"
                        value="{{ old('address') }}"
                        placeholder="Số nhà, Đường, Quận/Huyện..."
                        class="bg-white border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-brand-500 focus:border-brand-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    >
                </div>

                <div>
                    <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wide">Nội dung yêu cầu</label>
                    <textarea
                        name="details"
                        rows="5"
                        class="bg-white border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-brand-500 focus:border-brand-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white resize-none"
                        placeholder="Mô tả nhu cầu, quy mô triển khai, số lượng người dùng..."
                    >{{ old('details', "Dịch vụ quan tâm: {$service->name}\n") }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wide">Ngân sách dự kiến</label>
                        <input
                            type="text"
                            name="budget"
                            x-model="budget"
                            @input="budget = formatBudget($event.target.value)"
                            placeholder="Ví dụ: 100.000.000"
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-brand-500 focus:border-brand-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        >
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wide">File đính kèm (tùy chọn)</label>
                        <input
                            type="file"
                            name="file"
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-sm focus:ring-brand-500 focus:border-brand-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        >
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Hỗ trợ: jpeg, png, pdf, doc, docx, zip, cad, dwg (tối đa 10MB).</p>
                    </div>
                </div>

                <div class="pt-2">
                    <button
                        type="submit"
                        :disabled="submitting"
                        :class="{ 'opacity-70 cursor-not-allowed': submitting }"
                        class="inline-flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold uppercase tracking-wider text-sm px-8 py-3 rounded-sm transition-colors"
                    >
                        <i class="fas fa-paper-plane"></i>
                        <span x-text="submitting ? 'Đang gửi...' : 'Gửi yêu cầu báo giá'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

{{-- BÌNH LUẬN & ĐÁNH GIÁ DỊCH VỤ --}}
<section class="py-12 bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="mb-10 text-center">
            <h2 class="text-2xl font-black uppercase text-gray-900 dark:text-white tracking-tight">Đánh giá khách hàng</h2>
            <div class="w-16 h-1 bg-brand-600 mx-auto mt-4"></div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-800 p-8 md:p-12 rounded-sm shadow-sm border border-gray-100 dark:border-gray-700">
            <x-comment-list :comments="$service->approvedComments" />
            <x-comment-form :commentable="$service" type="service" />
        </div>
    </div>
</section>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@endsection
