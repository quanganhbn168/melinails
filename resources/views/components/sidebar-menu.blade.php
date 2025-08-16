<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    @foreach ($menu as $item)
        @php
            $hasSub = !empty($item['submenu']);
            
            // SỬA Ở ĐÂY: Đổi tên biến từ "$isOpen" thành "$isMenuOpen"
            $isMenuOpen = $hasSub ? $isOpen($item) : false;
            
            // SỬA Ở ĐÂY: Đổi tên biến từ "$isMenuActive" thành "$isItemActive" cho rõ ràng
            $isItemActive = $isMenuOpen || (!$hasSub && $isActive($item));
            
            $url = $hasSub ? '#' : (isset($item['route']) ? route($item['route'], $item['params'] ?? []) : '#');
        @endphp

        {{-- SỬA Ở ĐÂY: Dùng biến "$isMenuOpen" --}}
        <li class="nav-item {{ $isMenuOpen ? 'menu-is-opening menu-open' : '' }}">
            {{-- SỬA Ở ĐÂY: Dùng biến "$isItemActive" --}}
            <a href="{{ $url }}" class="nav-link {{ $isItemActive ? 'active' : '' }}">
                <i class="nav-icon {{ $item['icon'] }}"></i>
                <p>
                    {{ $item['title'] }}
                    @if ($hasSub)
                        <i class="right fas fa-angle-left"></i>
                    @endif
                </p>
            </a>

            @if ($hasSub)
                <ul class="nav nav-treeview">
                    @foreach ($item['submenu'] as $sub)
                        <li class="nav-item">
                            <a href="{{ isset($sub['route']) ? route($sub['route'], $sub['params'] ?? []) : '#' }}"
                               class="nav-link {{ $isActive($sub) ? 'active' : '' }}">
                                <i class="{{ $sub['icon'] ?? 'far fa-circle' }} nav-icon"></i>
                                <p>{{ $sub['title'] }}</p>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>