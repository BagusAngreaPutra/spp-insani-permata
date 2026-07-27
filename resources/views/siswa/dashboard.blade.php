{{-- resources/views/siswa/dashboard/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - SPP SD IT Permata Insani</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        /* gaya diambil dari template dashboard yang kamu kirim */
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Inter',sans-serif;background:#f8fafc;color:#1f2937;}
        .main-content{margin-left:280px;min-height:100vh;background:#f8fafc;transition:margin-left .3s ease;}
        .dashboard-content{padding:3rem 2rem;}
        .content-container{max-width:1200px;margin:0 auto;}
        .dashboard-card{background:white;overflow:hidden;box-shadow:0 1px 3px 0 rgba(0,0,0,0.1),0 1px 2px 0 rgba(0,0,0,0.06);border-radius:12px;margin-bottom:2rem;border:1px solid #f1f5f9;}
        .card-content{padding:2rem;color:#1f2937;}
        .welcome-message{font-size:1.25rem;font-weight:600;margin-bottom:1rem;color:#1f2937;}
        .welcome-description{color:#6b7280;font-size:1rem;line-height:1.5;}
        .stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.5rem;margin-top:2rem;}
        .stat-card{background:white;padding:2rem;border-radius:16px;box-shadow:0 4px 6px -1px rgba(0,0,0,0.05),0 2px 4px -1px rgba(0,0,0,0.03);border:1px solid #f1f5f9;transition:transform .2s ease,box-shadow .2s ease;position:relative;overflow:hidden;}
        .stat-card:hover{transform:translateY(-2px);box-shadow:0 10px 25px -3px rgba(0,0,0,0.1),0 4px 6px -2px rgba(0,0,0,0.05);}
        .stat-card::before{content:'';position:absolute;top:0;left:0;width:100%;height:4px;}
        .stat-card.tagihan::before{background:linear-gradient(135deg,#3b82f6,#1d4ed8);}
        .stat-card.pembayaran::before{background:linear-gradient(135deg,#10b981,#059669);}
        .stat-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;}
        .stat-value{font-size:2.5rem;font-weight:700;color:#1f2937;line-height:1;margin-bottom:.5rem;}
        .stat-label{color:#6b7280;font-size:1rem;font-weight:500;}
        .stat-icon{width:60px;height:60px;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:white;flex-shrink:0;}
        .stat-icon.tagihan{background:linear-gradient(135deg,#3b82f6,#1d4ed8);}
        .stat-icon.pembayaran{background:linear-gradient(135deg,#10b981,#059669);}
        @media(max-width:768px){.main-content{margin-left:0;padding-top:4rem}.dashboard-content{padding:2rem 1rem}.stats-grid{grid-template-columns:1fr;gap:1rem}.stat-card{padding:1.5rem}.stat-value{font-size:2rem}}
    </style>
</head>
<body>
    {{-- Sidebar khusus siswa --}}
    @include('layouts.sidebar-siswa')

    <div class="main-content">
        {{-- Header/Navbar khusus siswa --}}
        @include('layouts.header-siswa')

        <div class="dashboard-content">
            <div class="content-container">
                <div class="dashboard-card">
                    <div class="card-content">
                        <h2 class="welcome-message">Halo, {{ Auth::guard('siswa')->user()->nama ?? 'Siswa' }} 👋</h2>
                        <p class="welcome-description">
                            Selamat datang di dashboard SPP Anda. Di sini Anda bisa melihat ringkasan tagihan dan pembayaran.
                        </p>
                    </div>
                </div>

                {{-- ... kode awal tetap sama ... --}}

                <div class="stats-grid">
                    <div class="stat-card tagihan">
                        <div class="stat-header">
                            <div class="stat-content">
                                <div class="stat-value">{{ number_format($total_tagihan ?? 0,0,',','.') }}</div>
                                <div class="stat-label">Total Tagihan</div>
                            </div>
                            <div class="stat-icon tagihan">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card pembayaran">
                        <div class="stat-header">
                            <div class="stat-content">
                                <div class="stat-value">{{ number_format($total_pembayaran ?? 0,0,',','.') }}</div>
                                <div class="stat-label">Total Pembayaran</div>
                            </div>
                            <div class="stat-icon pembayaran">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- ✅ Tambahan: Riwayat Pembayaran Terakhir --}}
                <div class="dashboard-card" style="margin-top:2rem;">
                    <div class="card-content">
                        <h3 class="welcome-message" style="font-size:1.2rem;">💰 Riwayat Pembayaran Terakhir</h3>
                        <p class="welcome-description">Transaksi pembayaran terbaru yang sudah dilakukan.</p>
                        <div style="overflow-x:auto;margin-top:1rem;">
                            <table style="width:100%;border-collapse:collapse;">
                                <thead>
                                    <tr style="background:#f1f5f9;text-align:left;">
                                        <th style="padding:0.75rem;border-bottom:1px solid #e5e7eb;">Tanggal</th>
                                        <th style="padding:0.75rem;border-bottom:1px solid #e5e7eb;">Tagihan</th>
                                        <th style="padding:0.75rem;border-bottom:1px solid #e5e7eb;">Jumlah Bayar</th>
                                        <th style="padding:0.75rem;border-bottom:1px solid #e5e7eb;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($riwayat_pembayaran as $bayar)
                                        <tr>
                                            <td style="padding:0.75rem;border-bottom:1px solid #f1f5f9;">{{ \Carbon\Carbon::parse($bayar->tanggal_bayar)->translatedFormat('d M Y') }}</td>
                                            <td style="padding:0.75rem;border-bottom:1px solid #f1f5f9;">{{ $bayar->tagihan->nama_tagihan ?? '-' }}</td>
                                            <td style="padding:0.75rem;border-bottom:1px solid #f1f5f9;">Rp {{ number_format($bayar->jumlah_bayar,0,',','.') }}</td>
                                            <td style="padding:0.75rem;border-bottom:1px solid #f1f5f9;">
                                                <span style="color:#10b981;font-weight:600;">{{ ucfirst($bayar->status) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" style="padding:0.75rem;text-align:center;color:#6b7280;">Belum ada riwayat pembayaran.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div>
        </div>

    </div>
</body>
</html>
