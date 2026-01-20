@props(['list' => []])

@if (!empty($list))
    {{-- 1. CSS Inline cho gọn (Bạn có thể đưa ra file css riêng) --}}


    {{-- 2. GIAO DIỆN DESKTOP --}}
    <div class="d-none d-lg-block toc-box sticky-top" style="top: 100px; z-index: 1;">
        <h4><i class="fa-solid fa-list"></i> Mục lục</h4>
        <ul class="toc-list">
            @foreach($list as $item)
                <li class="toc-indent-{{ $item['level'] }}">
                    <a href="#{{ $item['slug'] }}">{{ $item['text'] }}</a>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- 3. GIAO DIỆN MOBILE --}}
    <div class="d-block d-lg-none">
        <button class="toc-fab-btn" id="js-toc-btn">
            <i class="fa-solid fa-list-ul font-size-20"></i>
        </button>

        <div class="toc-overlay" id="js-toc-overlay">
            <div class="toc-modal">
                <div class="toc-modal-head">
                    <h3 class="m-0 font-weight-bold" style="font-size:18px">Mục lục</h3>
                    <button class="toc-close" id="js-toc-close">&times;</button>
                </div>
                <div class="toc-modal-body">
                    <ul class="toc-list">
                        @foreach($list as $item)
                            <li class="toc-indent-{{ $item['level'] }}">
                                <a href="#{{ $item['slug'] }}" class="js-toc-link">{{ $item['text'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. JAVASCRIPT --}}
    @push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const btn = document.getElementById('js-toc-btn');
            const overlay = document.getElementById('js-toc-overlay');
            const close = document.getElementById('js-toc-close');
            const links = document.querySelectorAll('.js-toc-link');

            if(btn && overlay && close) {
                function toggleToc(show) {
                    overlay.classList.toggle('active', show);
                    document.body.style.overflow = show ? 'hidden' : '';
                }

                btn.addEventListener('click', () => toggleToc(true));
                close.addEventListener('click', () => toggleToc(false));
                overlay.addEventListener('click', (e) => {
                    if(e.target === overlay) toggleToc(false);
                });
                links.forEach(link => {
                    link.addEventListener('click', () => toggleToc(false));
                });
            }
        });
    </script>
    @endpush
@endif