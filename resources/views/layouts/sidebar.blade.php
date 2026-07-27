<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Mobile Menu Button -->
    <button class="sidebar-mobile-menu-btn" onclick="toggleSidebarMenu()">
        <i class="fas fa-bars"></i>
        <span class="menu-text">Menu</span>
    </button>

    <!-- Sidebar Overlay for mobile -->
    <div class="sidebar-overlay-bg" id="sidebarOverlayBg" onclick="toggleSidebarMenu()"></div>

    <!-- Sidebar -->
    <nav class="app-sidebar" id="appSidebar">
        <div class="app-sidebar-header">
            <div class="app-logo-sidebar">
                <div class="app-logo-icon-sidebar">
                    <i class="fas fa-graduation-cap"></i>
                </div>
               <div class="app-logo-text-sidebar">
                    <span class="app-school-name">
                         Sistem Pembayaran 
                    </span>
                    <span class="app-school-subtitle">
                       Permata Insani Islamic School
                    </span>
                </div>

            </div>
        </div>

        @php
            $adminUser = Auth::guard('web')->user();
            $canMaster = $adminUser?->hasAnyPermission(['sekolah.manage', 'tahun_ajaran.manage', 'kelas.manage', 'siswa.manage', 'admin.manage']) ?? false;
            $canTagihan = $adminUser?->hasAnyPermission(['tagihan.manage', 'pembayaran.process']) ?? false;
            $canPembayaran = $adminUser?->hasAnyPermission(['jenis_pembayaran.manage', 'koperasi.barang.manage', 'koperasi.stok.manage', 'koperasi.penjualan.manage', 'riwayat.view']) ?? false;
            $canKenaikanKelulusan = $adminUser?->hasAnyPermission(['kenaikan.manage', 'kelulusan.manage']) ?? false;
            $canPengaturan = $adminUser?->hasAnyPermission(['log.view', 'import_excel.manage', 'export_excel.manage', 'backup.manage']) ?? false;
            $canKeuangan = $adminUser?->hasAnyPermission(['pemasukan.manage', 'pengeluaran.manage', 'keuangan_kas.view']) ?? false;
            $canLaporan = $adminUser?->hasPermission('laporan.view') ?? false;
        @endphp

        <div class="app-sidebar-menu">
            <div class="app-menu-item">
                <a href="{{ route('dashboard') }}" class="app-dropdown-item {{ request()->routeIs('dashboard') ? 'app-active' : '' }}">
                    <div class="app-menu-icon"><i class="fas fa-tachometer-alt"></i></div>
                    <span class="app-menu-text">Dashboard</span>
                    @if(request()->routeIs('dashboard'))<div class="app-active-indicator"></div>@endif
                </a>
            </div>

            @if($canTagihan)
                <div class="app-menu-item">
                    <a href="{{ route('tagihan.index.grouped') }}" class="app-dropdown-item {{ request()->routeIs('tagihan.*') ? 'app-active' : '' }}">
                        <div class="app-menu-icon"><i class="fas fa-money-check-alt"></i></div>
                        <span class="app-menu-text">Tagihan Siswa</span>
                        @if(request()->routeIs('tagihan.*'))<div class="app-active-indicator"></div>@endif
                    </a>
                </div>
            @endif

            @if($canMaster)
                <div class="app-menu-item app-menu-dropdown">
                    <div class="app-menu-link app-dropdown-toggle" onclick="toggleSidebarDropdown(this)">
                        <div class="app-menu-content">
                            <div class="app-menu-icon"><i class="fas fa-database"></i></div>
                            <span class="app-menu-text">Master Data</span>
                        </div>
                        <i class="fas fa-chevron-down app-dropdown-icon"></i>
                    </div>
                    <div class="app-dropdown-menu">
                        @if($adminUser->hasPermission('sekolah.manage'))
                            <a href="{{ route('sekolah.index') }}" class="app-dropdown-item {{ request()->routeIs('sekolah.*') ? 'app-dropdown-active' : '' }}"><i class="fas fa-building"></i><span>1. Sekolah</span></a>
                        @endif
                        @if($adminUser->hasPermission('tahun_ajaran.manage'))
                            <a href="{{ route('tahun_ajaran.index') }}" class="app-dropdown-item {{ request()->routeIs('tahun_ajaran.*') ? 'app-dropdown-active' : '' }}"><i class="fas fa-calendar-alt"></i><span>2. Tahun Ajaran</span></a>
                        @endif
                        @if($adminUser->hasPermission('kelas.manage'))
                            <a href="{{ route('kelas.index') }}" class="app-dropdown-item {{ request()->routeIs('kelas.*') ? 'app-dropdown-active' : '' }}"><i class="fas fa-chalkboard"></i><span>3. Kelas</span></a>
                        @endif
                        @if($adminUser->hasPermission('siswa.manage'))
                            <a href="{{ route('siswa.index') }}" class="app-dropdown-item {{ request()->routeIs('siswa.*') ? 'app-dropdown-active' : '' }}"><i class="fas fa-user-graduate"></i><span>4. Siswa</span></a>
                        @endif
                        @if($adminUser->hasPermission('admin.manage'))
                            <a href="{{ route('admin.index') }}" class="app-dropdown-item {{ request()->routeIs('admin.*') ? 'app-dropdown-active' : '' }}"><i class="fas fa-user-tie"></i><span>Data Admin</span></a>
                        @endif
                    </div>
                </div>
            @endif

            @if($canPembayaran)
                <div class="app-menu-item app-menu-dropdown">
                    <div class="app-menu-link app-dropdown-toggle" onclick="toggleSidebarDropdown(this)">
                        <div class="app-menu-content">
                            <div class="app-menu-icon"><i class="fas fa-credit-card"></i></div>
                            <span class="app-menu-text">Pembayaran</span>
                        </div>
                        <i class="fas fa-chevron-down app-dropdown-icon"></i>
                    </div>
                    <div class="app-dropdown-menu">
                        @if($adminUser->hasPermission('jenis_pembayaran.manage'))
                            <a href="{{ route('jenis_pembayaran.index') }}" class="app-dropdown-item {{ request()->routeIs('jenis_pembayaran.*') ? 'app-dropdown-active' : '' }}"><i class="fas fa-list-alt"></i><span>Jenis Pembayaran</span></a>
                        @endif
                        @if($adminUser->hasAnyPermission(['koperasi.barang.manage', 'koperasi.stok.manage']))
                            <a href="{{ route('koperasi.index') }}" class="app-dropdown-item {{ request()->routeIs('koperasi.index') || request()->routeIs('koperasi.create') || request()->routeIs('koperasi.edit') || request()->routeIs('koperasi.stok.*') ? 'app-dropdown-active' : '' }}"><i class="fas fa-store"></i><span>Koperasi</span></a>
                        @endif
                        @if($adminUser->hasPermission('koperasi.penjualan.manage'))
                            <a href="{{ route('koperasi.penjualan.index') }}" class="app-dropdown-item {{ request()->routeIs('koperasi.penjualan.*') ? 'app-dropdown-active' : '' }}"><i class="fas fa-cash-register"></i><span>Penjualan Koperasi</span></a>
                        @endif
                        @if($adminUser->hasPermission('riwayat.view'))
                            <a href="{{ route('riwayat.index') }}" class="app-dropdown-item {{ request()->routeIs('riwayat.*') ? 'app-dropdown-active' : '' }}"><i class="fas fa-history"></i><span>Riwayat Pembayaran</span></a>
                        @endif
                    </div>
                </div>
            @endif

            @if($canKenaikanKelulusan)
                <div class="app-menu-item app-menu-dropdown">
                    <div class="app-menu-link app-dropdown-toggle" onclick="toggleSidebarDropdown(this)">
                        <div class="app-menu-content">
                            <div class="app-menu-icon"><i class="fas fa-user-graduate"></i></div>
                            <span class="app-menu-text">Lulus & Kenaikan</span>
                        </div>
                        <i class="fas fa-chevron-down app-dropdown-icon"></i>
                    </div>
                    <div class="app-dropdown-menu">
                        @if($adminUser->hasPermission('kenaikan.manage'))
                            <a href="{{ route('kenaikan.index') }}" class="app-dropdown-item {{ request()->routeIs('kenaikan.*') ? 'app-dropdown-active' : '' }}"><i class="fas fa-level-up-alt"></i><span>Kenaikan Kelas</span></a>
                        @endif
                        @if($adminUser->hasPermission('kelulusan.manage'))
                            <a href="{{ route('kelulusan.index') }}" class="app-dropdown-item {{ request()->routeIs('kelulusan.*') ? 'app-dropdown-active' : '' }}"><i class="fas fa-medal"></i><span>Kelulusan</span></a>
                        @endif
                    </div>
                </div>
            @endif

            @if($canPengaturan)
                <div class="app-menu-item app-menu-dropdown">
                    <div class="app-menu-link app-dropdown-toggle" onclick="toggleSidebarDropdown(this)">
                        <div class="app-menu-content">
                            <div class="app-menu-icon"><i class="fas fa-cogs"></i></div>
                            <span class="app-menu-text">Pengaturan</span>
                        </div>
                        <i class="fas fa-chevron-down app-dropdown-icon"></i>
                    </div>
                    <div class="app-dropdown-menu">
                        @if($adminUser->hasPermission('log.view'))
                            <a href="{{ route('log_aktivitas.index') }}" class="app-dropdown-item {{ request()->routeIs('log_aktivitas.*') ? 'app-dropdown-active' : '' }}"><i class="fas fa-clock"></i><span>Riwayat Aktivitas</span></a>
                        @endif
                        @if($adminUser->hasPermission('import_excel.manage'))
                            <a href="{{ route('import.form') }}" class="app-dropdown-item {{ request()->routeIs('import.*') ? 'app-dropdown-active' : '' }}"><i class="fas fa-file-import"></i><span>Import Data Excel</span></a>
                        @endif
                        @if($adminUser->hasPermission('export_excel.manage'))
                            <a href="{{ route('export_excel.index') }}" class="app-dropdown-item {{ request()->routeIs('export_excel.*') ? 'app-dropdown-active' : '' }}"><i class="fas fa-file-export"></i><span>Export Data Excel</span></a>
                        @endif
                        @if($adminUser->hasPermission('backup.manage'))
                            <a href="{{ route('backup.index') }}" class="app-dropdown-item {{ request()->routeIs('backup.*') ? 'app-dropdown-active' : '' }}"><i class="fas fa-database"></i><span>Backup Database</span></a>
                        @endif
                    </div>
                </div>
            @endif

            @if($canKeuangan)
                <div class="app-menu-item app-menu-dropdown">
                    <div class="app-menu-link app-dropdown-toggle" onclick="toggleSidebarDropdown(this)">
                        <div class="app-menu-content">
                            <div class="app-menu-icon"><i class="fas fa-chart-line"></i></div>
                            <span class="app-menu-text">Keuangan</span>
                        </div>
                        <i class="fas fa-chevron-down app-dropdown-icon"></i>
                    </div>
                    <div class="app-dropdown-menu">
                        @if($adminUser->hasPermission('pemasukan.manage'))
                            <a href="{{ route('pemasukan.index') }}" class="app-dropdown-item {{ request()->routeIs('pemasukan.*') ? 'app-dropdown-active' : '' }}"><i class="fas fa-arrow-up app-text-green"></i><span>Data Pemasukan</span></a>
                        @endif
                        @if($adminUser->hasPermission('pengeluaran.manage'))
                            <a href="{{ route('pengeluaran.index') }}" class="app-dropdown-item {{ request()->routeIs('pengeluaran.*') ? 'app-dropdown-active' : '' }}"><i class="fas fa-arrow-down app-text-red"></i><span>Data Pengeluaran</span></a>
                        @endif
                        @if($adminUser->hasPermission('keuangan_kas.view'))
                            <a href="{{ route('keuangan.kas.index') }}" class="app-dropdown-item {{ request()->routeIs('keuangan.kas.*') ? 'app-dropdown-active' : '' }}"><i class="fas fa-wallet"></i><span>Keuangan Kas</span></a>
                        @endif
                    </div>
                </div>
            @endif

            @if($canLaporan)
                <div class="app-menu-item app-menu-dropdown">
                    <div class="app-menu-link app-dropdown-toggle" onclick="toggleSidebarDropdown(this)">
                        <div class="app-menu-content">
                            <div class="app-menu-icon"><i class="fas fa-file-alt"></i></div>
                            <span class="app-menu-text">Laporan</span>
                        </div>
                        <i class="fas fa-chevron-down app-dropdown-icon"></i>
                    </div>
                    <div class="app-dropdown-menu">
                        <a href="{{ route('laporan.admin') }}" class="app-dropdown-item"><i class="fas fa-user-shield"></i><span>Laporan Admin</span></a>
                        <a href="{{ route('laporan.jenis_pembayaran') }}" class="app-dropdown-item"><i class="fas fa-tags"></i><span>Laporan Jenis Pembayaran</span></a>
                        <a href="{{ route('laporan.kelas') }}" class="app-dropdown-item"><i class="fas fa-door-open"></i><span>Laporan Kelas</span></a>
                        <a href="{{ route('laporan.kelulusan') }}" class="app-dropdown-item"><i class="fas fa-trophy"></i><span>Laporan Kelulusan</span></a>
                        <a href="{{ route('laporan.kenaikan') }}" class="app-dropdown-item"><i class="fas fa-arrow-circle-up"></i><span>Laporan Kenaikan Kelas</span></a>
                        <a href="{{ route('laporan.pembayaran') }}" class="app-dropdown-item"><i class="fas fa-receipt"></i><span>Laporan Pembayaran</span></a>
                        <a href="{{ route('laporan.pengeluaran') }}" class="app-dropdown-item"><i class="fas fa-minus-circle app-text-red"></i><span>Laporan Pengeluaran</span></a>
                        <a href="{{ route('laporan.pemasukan') }}" class="app-dropdown-item"><i class="fas fa-plus-circle app-text-green"></i><span>Laporan Pemasukan</span></a>
                        <a href="{{ route('laporan.koperasi') }}" class="app-dropdown-item"><i class="fas fa-store"></i><span>Laporan Koperasi</span></a>
                        <a href="{{ route('laporan.sekolah') }}" class="app-dropdown-item"><i class="fas fa-building"></i><span>Laporan Sekolah</span></a>
                        <a href="{{ route('laporan.siswa') }}" class="app-dropdown-item"><i class="fas fa-users"></i><span>Laporan Siswa</span></a>
                        <a href="{{ route('laporan.tahun_ajaran') }}" class="app-dropdown-item"><i class="fas fa-calendar"></i><span>Laporan Tahun Ajaran</span></a>
                    </div>
                </div>
            @endif
        </div>
    </nav>


<style>
/* Reset dan base styles untuk sidebar saja */
.app-sidebar * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Sidebar Styles - GREEN THEME dengan prefix app- */
.app-sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 300px;
    height: 100vh;
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 50%, #15803d 100%);
    z-index: 1000;
    overflow-y: auto;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 20px 40px rgba(34, 197, 94, 0.3);
    backdrop-filter: blur(20px);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.app-sidebar::-webkit-scrollbar {
    width: 6px;
}

.app-sidebar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
}

.app-sidebar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 3px;
    transition: background 0.3s ease;
}

.app-sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
}

.app-sidebar-header {
    padding: 2.5rem 2rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    position: relative;
    background: rgba(21, 128, 61, 0.2);
}

.app-sidebar-header::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 50px;
    height: 2px;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
}

.app-logo-sidebar {
    display: flex;
    align-items: center;
    gap: 1rem;
    color: white;
}

.app-logo-icon-sidebar {
    width: 55px;
    height: 55px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
    animation: logoFloat 6s ease-in-out infinite;
}

@keyframes logoFloat {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-5px); }
}

.app-logo-text-sidebar {
    display: flex;
    flex-direction: column;
}

.app-school-name {
    font-size: 1.3rem;
    font-weight: 700;
    line-height: 1.2;
    background: linear-gradient(135deg, #ffffff 0%, #f0f9f4 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.app-school-subtitle {
    font-size: 1rem;
    font-weight: 500;
    opacity: 0.9;
    margin-top: 2px;
    color: rgba(255, 255, 255, 0.9);
}

.app-sidebar-menu {
    padding: 1.5rem 0;
}

.app-menu-item {
    margin-bottom: 0.5rem;
    position: relative;
    animation: fadeInUp 0.6s ease forwards;
    opacity: 0;
    transform: translateY(20px);
}

.app-menu-item:nth-child(1) { animation-delay: 0.1s; }
.app-menu-item:nth-child(2) { animation-delay: 0.2s; }
.app-menu-item:nth-child(3) { animation-delay: 0.3s; }
.app-menu-item:nth-child(4) { animation-delay: 0.4s; }
.app-menu-item:nth-child(5) { animation-delay: 0.5s; }
.app-menu-item:nth-child(6) { animation-delay: 0.6s; }
.app-menu-item:nth-child(7) { animation-delay: 0.7s; }
.app-menu-item:nth-child(8) { animation-delay: 0.8s; }
.app-menu-item:nth-child(9) { animation-delay: 0.9s; }

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.app-menu-link {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 2rem;
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-weight: 500;
    position: relative;
    border-radius: 0 25px 25px 0;
    margin-right: 1rem;
}

.app-menu-link::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    width: 3px;
    height: 100%;
    background: linear-gradient(135deg, #86efac 0%, #4ade80 100%);
    transform: scaleY(0);
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.app-menu-link:hover {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.app-menu-link:hover::before {
    transform: scaleY(1);
}

.app-menu-link.app-active {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    transform: translateX(8px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.app-menu-link.app-active::before {
    transform: scaleY(1);
}

.app-menu-icon {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.app-menu-icon i {
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.app-menu-link:hover .app-menu-icon i {
    transform: scale(1.1);
}

.app-menu-text {
    font-size: 0.95rem;
    font-weight: 500;
}

.app-active-indicator {
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 20px;
    background: white;
    border-radius: 2px 0 0 2px;
    opacity: 1;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from { transform: translateY(-50%) translateX(10px); opacity: 0; }
    to { transform: translateY(-50%) translateX(0); opacity: 1; }
}

.app-notification-badge {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: white;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    min-width: 20px;
    text-align: center;
    margin-left: auto;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.app-menu-dropdown {
    position: relative;
}

.app-dropdown-toggle {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    cursor: pointer;
}

.app-menu-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.app-dropdown-icon {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    font-size: 0.8rem;
    opacity: 0.7;
}

.app-dropdown-toggle.app-open .app-dropdown-icon {
    transform: rotate(180deg);
    opacity: 1;
}

.app-dropdown-menu {
    max-height: 0;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    background: rgba(0, 0, 0, 0.15);
    margin: 0.5rem 1rem 0 1rem;
    border-radius: 15px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.app-dropdown-menu.app-open {
    max-height: 600px;
    padding: 0.5rem 0;
}

.app-dropdown-item {
    padding: 0.75rem 1.5rem;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-size: 0.9rem;
    border-radius: 10px;
    margin: 0.25rem 0.5rem;
    position: relative;
    overflow: hidden;
}

.app-dropdown-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    width: 2px;
    height: 100%;
    background: rgba(134, 239, 172, 0.8);
    transform: scaleY(0);
    transition: transform 0.3s ease;
}

.app-dropdown-item:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.app-dropdown-item:hover::before {
    transform: scaleY(1);
}

.app-dropdown-item.app-dropdown-active {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    transform: translateX(5px);
}

.app-dropdown-item.app-dropdown-active::before {
    transform: scaleY(1);
}

.app-dropdown-item i {
    width: 16px;
    font-size: 0.9rem;
}

.app-text-green {
    color: #4ade80 !important;
}

.app-text-red {
    color: #f87171 !important;
}

/* Mobile Menu Button - GREEN THEME */
.sidebar-mobile-menu-btn {
    display: none;
    position: fixed;
    top: 1.5rem;
    left: 1.5rem;
    z-index: 1100;
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    color: white;
    border: none;
    border-radius: 15px;
    padding: 1rem;
    cursor: pointer;
    box-shadow: 0 8px 25px rgba(34, 197, 94, 0.4);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    align-items: center;
    gap: 0.5rem;
    backdrop-filter: blur(20px);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.sidebar-mobile-menu-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 35px rgba(34, 197, 94, 0.5);
}

.sidebar-overlay-bg {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    backdrop-filter: blur(5px);
}

.sidebar-overlay-bg.app-active {
    opacity: 1;
    visibility: visible;
}

/* Enhanced focus styles for accessibility */
.app-menu-link:focus,
.app-dropdown-item:focus {
    outline: 2px solid rgba(134, 239, 172, 0.5);
    outline-offset: 2px;
}

/* Responsive */
@media (max-width: 1024px) {
    .app-sidebar {
        width: 280px;
    }
}

@media (max-width: 768px) {
    .sidebar-mobile-menu-btn {
        display: flex;
    }

    .app-sidebar {
        transform: translateX(-100%);
        width: 300px;
    }
    
    .app-sidebar.app-mobile-open {
        transform: translateX(0);
    }
}

@media (max-width: 480px) {
    .app-sidebar {
        width: 280px;
    }
    
    .app-sidebar-header {
        padding: 2rem 1.5rem;
    }
    
    .app-menu-link {
        padding: 0.875rem 1.5rem;
        font-size: 0.9rem;
    }
    
    .app-dropdown-item {
        padding: 0.625rem 1.25rem;
        font-size: 0.85rem;
    }
}

/* Main content adjustment untuk kompabilitas */
body:has(.app-sidebar) .main-content {
    margin-left: 316px !important;
    max-width: calc(100% - 316px) !important;
    width: calc(100% - 316px) !important;
}

body:has(.app-sidebar) .main-content .content-area {
    padding-left: max(2rem, 24px) !important;
    padding-right: max(2rem, 24px) !important;
}

body:has(.app-sidebar) .main-content .content-area .action-buttons,
body:has(.app-sidebar) .main-content .content-area .table-actions,
body:has(.app-sidebar) .main-content .content-area .row-actions {
    align-items: center !important;
    display: inline-flex !important;
    flex-direction: row !important;
    flex-wrap: nowrap !important;
    gap: 0.5rem !important;
    justify-content: flex-start !important;
    max-width: 100%;
    overflow-x: auto;
    white-space: nowrap;
    -webkit-overflow-scrolling: touch;
}

body:has(.app-sidebar) .main-content .content-area .action-buttons > *,
body:has(.app-sidebar) .main-content .content-area .table-actions > *,
body:has(.app-sidebar) .main-content .content-area .row-actions > * {
    flex: 0 0 auto !important;
}

body:has(.app-sidebar) .main-content .content-area td[data-label="Aksi"] .action-buttons,
body:has(.app-sidebar) .main-content .content-area td:last-child .action-buttons {
    width: max-content !important;
}

body:has(.app-sidebar) .main-content .content-area td[data-label="Aksi"] .btn,
body:has(.app-sidebar) .main-content .content-area td:last-child .btn,
body:has(.app-sidebar) .main-content .content-area td[data-label="Aksi"] button,
body:has(.app-sidebar) .main-content .content-area td:last-child button {
    flex: 0 0 auto !important;
    white-space: nowrap !important;
}

@media (max-width: 1024px) {
    body:has(.app-sidebar) .main-content {
        margin-left: 300px !important;
        max-width: calc(100% - 300px) !important;
        width: calc(100% - 300px) !important;
    }
}

@media (max-width: 768px) {
    body:has(.app-sidebar) .main-content {
        margin-left: 0 !important;
        max-width: 100% !important;
        min-height: 100vh !important;
        position: relative !important;
        width: 100% !important;
        padding-top: 5rem !important;
    }

    body:has(.app-sidebar) .main-content .content-area {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
}

@media (max-width: 480px) {
    body:has(.app-sidebar) .main-content {
        padding-top: 4.5rem !important;
    }

    body:has(.app-sidebar) .main-content .content-area {
        padding-left: 0.75rem !important;
        padding-right: 0.75rem !important;
    }
}

/* Print styles */
@media print {
@page{
            size: landscape;
        }
    .app-sidebar,
    .sidebar-mobile-menu-btn,
    .sidebar-overlay-bg {
        display: none;
    }
}
</style>

<script>
// Fixed Sidebar JavaScript - Sesuai dengan class dan function yang digunakan di HTML

function toggleSidebarDropdown(element) {
    console.log('Sidebar dropdown clicked!'); // Debug log
    
    const dropdownMenu = element.nextElementSibling;
    const isOpen = dropdownMenu.classList.contains('app-open');
    
    // Close all other dropdowns with smooth animation
    document.querySelectorAll('.app-dropdown-menu.app-open').forEach(menu => {
        if (menu !== dropdownMenu) {
            menu.classList.remove('app-open');
        }
    });
    
    document.querySelectorAll('.app-dropdown-toggle.app-open').forEach(toggle => {
        if (toggle !== element) {
            toggle.classList.remove('app-open');
        }
    });
    
    // Toggle current dropdown
    if (!isOpen) {
        dropdownMenu.classList.add('app-open');
        element.classList.add('app-open');
        
        // Add stagger animation to dropdown items
        const items = dropdownMenu.querySelectorAll('.app-dropdown-item');
        items.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateX(-10px)';
            setTimeout(() => {
                item.style.transition = 'all 0.3s ease';
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            }, index * 50 + 100);
        });
    } else {
        dropdownMenu.classList.remove('app-open');
        element.classList.remove('app-open');
    }
}

function toggleSidebarMenu() {
    const sidebar = document.getElementById('appSidebar');
    const overlay = document.getElementById('sidebarOverlayBg');
    const isOpen = sidebar.classList.contains('app-mobile-open');
    
    if (!isOpen) {
        sidebar.classList.add('app-mobile-open');
        overlay.classList.add('app-active');
        document.body.style.overflow = 'hidden';
    } else {
        sidebar.classList.remove('app-mobile-open');
        overlay.classList.remove('app-active');
        document.body.style.overflow = '';
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const isDropdownClick = event.target.closest('.app-dropdown-toggle');
    
    if (!isDropdownClick) {
        // Close all dropdowns
        document.querySelectorAll('.app-dropdown-menu.app-open').forEach(menu => {
            menu.classList.remove('app-open');
        });
        
        document.querySelectorAll('.app-dropdown-toggle.app-open').forEach(toggle => {
            toggle.classList.remove('app-open');
        });
    }
});

// Close mobile sidebar when window is resized to desktop
window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
        const sidebar = document.getElementById('appSidebar');
        const overlay = document.getElementById('sidebarOverlayBg');
        
        sidebar.classList.remove('app-mobile-open');
        overlay.classList.remove('app-active');
        document.body.style.overflow = '';
    }
});

// Keyboard navigation support
document.addEventListener('keydown', function(event) {
    // Close dropdown/sidebar with Escape key
    if (event.key === 'Escape') {
        // Close dropdowns
        document.querySelectorAll('.app-dropdown-menu.app-open').forEach(menu => {
            menu.classList.remove('app-open');
        });
        
        document.querySelectorAll('.app-dropdown-toggle.app-open').forEach(toggle => {
            toggle.classList.remove('app-open');
        });
        
        // Close mobile sidebar
        const sidebar = document.getElementById('appSidebar');
        const overlay = document.getElementById('sidebarOverlayBg');
        
        if (sidebar.classList.contains('app-mobile-open')) {
            sidebar.classList.remove('app-mobile-open');
            overlay.classList.remove('app-active');
            document.body.style.overflow = '';
        }
    }
});

// Initialize sidebar on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Sidebar initialized successfully!');
    
    // Add smooth scrolling to sidebar
    const sidebar = document.getElementById('appSidebar');
    if (sidebar) {
        sidebar.style.scrollBehavior = 'smooth';
    }
});
</script>
