<aside class="sidebar" id="sidebar">
    <div class="sb-brand">
        <div class="sb-logo"><i class="bi bi-diagram-3-fill"></i></div>
        <div class="sb-title">
            <span class="name">PMS</span>
            <span class="sub">Project Management</span>
        </div>
    </div>
    <nav class="sb-nav">
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <span class="nav-icon"><i class="bi bi-grid-3x3-gap-fill"></i></span>
            <span class="nav-text">Dashboard</span>
        </a>


        @foreach (menus() as $category => $items)
            @php
                // Filter menu berdasarkan permission user
                $filtered = $items->filter(function ($menu) {
                    return user('can', 'menu ' . $menu->url);
                });
            @endphp

            @if ($filtered->count())
                {{-- Category Section --}}
                <div class="nav-section">{{ strtoupper($category) }}</div>

                {{-- Menu Items --}}
                @foreach ($filtered as $menu)
                    <a href="{{ url($menu->url) }}"
                        class="nav-link {{ request()->is($menu->url . '*') ? 'active' : '' }}"
                        data-tip="{{ $menu->name }}">

                        <span class="nav-icon">
                            <i class="{{ $menu->icon }}"></i>
                        </span>

                        <span class="nav-text">{{ $menu->name }}</span>

                        {{-- Jika ada hitungan (count), tampilkan badgenya --}}
                        {{-- @if ($menu->count)
                            <span class="nav-badge">{{ $menu->count }}</span>
                        @endif --}}


                        {{-- Logika Badge (Jika ada kolom badge_count di database, jika tidak abaikan baris ini) --}}
                        @if (isset($menu->badge_count) && $menu->badge_count > 0)
                            <span class="nav-badge">{{ $menu->badge_count }}</span>
                        @endif
                    </a>
                @endforeach
            @endif
        @endforeach

    </nav>
    <div class="sb-footer">
        <div class="sb-user">
            <div class="sb-av">{{ user('initial') }}</div>
            <div class="sb-user-info">
                <div class="uname">{{ user('name') }}</div>
                <div class="urole">{{ user('role') }}</div>
            </div>
            <i class="bi bi-chevron-right sb-user-chevron"></i>
        </div>
    </div>
</aside>
