<div class="bottom-nav" wire:poll.30s="checkNotifications">
    {{-- 1. Current Jobs (Active) --}}
    <a href="{{ route('admin.my-work-orders.index', ['filter' => 'active']) }}" 
       class="nav-item-mobile {{ request()->routeIs('admin.my-work-orders.index') && request()->input('filter', 'active') == 'active' ? 'active' : '' }}">
        <i class="fas fa-clipboard-list"></i>
        Việc hiện tại
    </a>
    
    {{-- 2. All Jobs (History) --}}
    <a href="{{ route('admin.my-work-orders.index', ['filter' => 'all']) }}" 
       class="nav-item-mobile {{ request()->routeIs('admin.my-work-orders.index') && request()->input('filter') == 'all' ? 'active' : '' }}">
        <i class="fas fa-history"></i>
        Tất cả việc
    </a>

    {{-- 3. Warranty Check --}}
    <a href="{{ route('admin.warranty.check') }}" 
       class="nav-item-mobile {{ request()->routeIs('admin.warranty.check') ? 'active' : '' }}">
        <i class="fas fa-shield-alt"></i>
        Bảo hành
    </a>

    {{-- 4. Notifications --}}
    <a href="{{ route('admin.notifications.index') }}" 
       class="nav-item-mobile {{ request()->routeIs('admin.notifications.index') ? 'active' : '' }}"
       style="position: relative;">
        <i class="fas fa-bell"></i>
        @if($unreadCount > 0)
            <span class="badge badge-danger navbar-badge" style="position: absolute; top: 5px; right: 25%; font-size: 0.6rem;">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
        Thông báo
    </a>

    {{-- 5. Account --}}
    <a href="{{ route('admin.profile') }}" 
       class="nav-item-mobile {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
        <i class="fas fa-user"></i>
        Tài khoản
    </a>
</div>
