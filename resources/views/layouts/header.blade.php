<header class="app-topbar">
    <button class="app-mobile-trigger" type="button" onclick="toggleSidebarMenu()" aria-label="Buka menu">
        <i class="fas fa-bars"></i>
    </button>

    <div class="app-topbar-title">
        <span class="app-topbar-context">Sistem Informasi</span>
        <strong>Pembayaran SPP</strong>
    </div>

    <div class="app-topbar-meta">
        <span class="app-system-status"><i></i>Sistem aktif</span>
        <span class="app-topbar-date">
            <i class="far fa-calendar"></i>
            {{ now()->translatedFormat('d M Y') }}
        </span>
    </div>
</header>
