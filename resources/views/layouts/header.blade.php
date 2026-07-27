<header class="app-topbar">
    <button class="app-mobile-trigger" type="button" onclick="toggleSidebarMenu()" aria-label="Buka menu">
        <i class="fas fa-bars"></i>
    </button>

    <div class="app-topbar-title">
        <span class="app-topbar-context">Sistem Pembayaran</span>
        <strong>Permata Insani</strong>
    </div>

    <span class="app-topbar-date">
        <i class="far fa-calendar"></i>
        {{ now()->translatedFormat('d M Y') }}
    </span>
</header>
