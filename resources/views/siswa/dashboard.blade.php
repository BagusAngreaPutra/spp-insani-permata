<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ringkasan Siswa · Permata Insani</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    @include('layouts.sidebar-siswa')

    <div class="main-content">
        @include('layouts.header-siswa')

        <div class="dashboard-content">
            <div class="content-container">
                <div class="dashboard-header">
                    <h1 class="dashboard-title">Ringkasan</h1>
                    <p class="dashboard-subtitle">Halo, {{ Auth::guard('siswa')->user()->nama ?? 'Siswa' }}.</p>
                </div>

                <div class="stats-grid">
                    <article class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-label">Total tagihan</div>
                                <div class="stat-value">Rp{{ number_format($total_tagihan ?? 0, 0, ',', '.') }}</div>
                            </div>
                            <div class="stat-icon"><i class="fas fa-receipt"></i></div>
                        </div>
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('siswa.tagihan.index') }}">Lihat tagihan</a>
                    </article>

                    <article class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-label">Total pembayaran</div>
                                <div class="stat-value">Rp{{ number_format($total_pembayaran ?? 0, 0, ',', '.') }}</div>
                            </div>
                            <div class="stat-icon"><i class="fas fa-wallet"></i></div>
                        </div>
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('siswa.riwayat.index') }}">Lihat riwayat</a>
                    </article>
                </div>

                <section class="dashboard-card">
                    <div class="card-content">
                        <h2 class="section-title">Pembayaran terbaru</h2>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Tagihan</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($riwayat_pembayaran as $bayar)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($bayar->tanggal_bayar)->translatedFormat('d M Y') }}</td>
                                            <td>{{ $bayar->tagihan->nama_tagihan ?? '-' }}</td>
                                            <td>Rp{{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</td>
                                            <td><span class="badge bg-success-subtle text-success">{{ ucfirst($bayar->status) }}</span></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center">Belum ada pembayaran.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    @include('layouts.design-system')
</body>
</html>
