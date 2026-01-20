@props(['url' => url()->current(), 'title' => ''])

<div class="social-share-box mb-3">
    <span class="text-muted mr-2" style="font-size: 14px;">Chia sẻ:</span>
    
    {{-- 1. Facebook --}}
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($url) }}" 
       target="_blank" class="btn-share btn-fb" title="Chia sẻ lên Facebook">
        <i class="fa-brands fa-facebook-f"></i>
    </a>

    {{-- 2. X (Twitter) --}}
    <a href="https://twitter.com/intent/tweet?url={{ urlencode($url) }}&text={{ urlencode($title) }}" 
       target="_blank" class="btn-share btn-x" title="Chia sẻ lên X">
        <i class="fa-brands fa-x-twitter"></i>
    </a>

    {{-- 3. Zalo (Dùng Link Copy hoặc Redirect qua Zalo Web) --}}
    {{-- Zalo Web Share API --}}
    <a href="https://zalo.me/share/?url={{ urlencode($url) }}" 
       target="_blank" class="btn-share btn-zalo" title="Chia sẻ qua Zalo">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/91/Icon_of_Zalo.svg/1200px-Icon_of_Zalo.svg.png" alt="Zalo" style="width: 18px; height: 18px; margin-top: -3px;">
    </a>

    {{-- 4. Copy Link --}}
    <button type="button" class="btn-share btn-copy" onclick="copyToClipboard('{{ $url }}')" title="Sao chép liên kết">
        <i class="fa-solid fa-link"></i>
    </button>
</div>



@push('js')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Đã sao chép liên kết vào bộ nhớ tạm!');
        }, function(err) {
            console.error('Lỗi sao chép: ', err);
        });
    }
</script>
@endpush