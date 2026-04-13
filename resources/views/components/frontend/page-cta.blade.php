@props([
    'title' => null,
    'description' => null,
    'link' => null,
])

@php
    $defaultTitle = 'Bạn cần tư vấn giải pháp chuyển đổi số?';
    $defaultDescription = 'Hệ thống CNETPos sở hữu lõi công nghệ linh hoạt <strong class="text-accent-400">Low-code/No-code</strong> cho phép Customize và thiết kế lại quy trình để hoàn toàn vừa vặn với bất kỳ mô hình kinh doanh nào.';
    
    $finalTitle = !empty($title) ? $title : $defaultTitle;
    $finalDescription = !empty($description) ? $description : $defaultDescription;
    $finalLink = !empty($link) ? $link : route('contact.show');
@endphp

<div class="max-w-screen-xl mx-auto px-4 pb-16">
    <div class="mt-20 bg-gradient-to-br from-brand-900 via-brand-800 to-brand-900 rounded-sm p-10 md:p-16 text-center shadow-2xl relative overflow-hidden border-t-4 border-accent-500">
        <div class="absolute inset-0 w-full h-full opacity-10">
            <svg class="absolute w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="cta-dots" width="20" height="20" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="2" fill="#ffffff"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#cta-dots)"/>
            </svg>
        </div>
        <div class="relative z-10">
            <h3 class="text-2xl md:text-3xl lg:text-4xl font-black text-white mb-6 uppercase tracking-tight">
                {{ $finalTitle }}
            </h3>
            <p class="text-brand-100 text-lg mb-10 max-w-3xl mx-auto font-medium leading-relaxed">
                {!! $finalDescription !!}
            </p>
            <a href="{{ $finalLink }}" class="inline-flex items-center px-10 py-4 bg-white text-brand-900 font-black uppercase tracking-wider text-sm rounded shadow-xl hover:shadow-2xl hover:bg-gray-50 hover:-translate-y-1 transition-all">
                Liên hệ chuyên gia tư vấn <i class="fas fa-paper-plane ml-2 text-accent-500"></i>
            </a>
        </div>
    </div>
</div>