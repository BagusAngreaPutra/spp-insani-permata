@extends('layouts.app')
@include('layouts.sidebar-siswa')

@section('content')
<style>
    .main-content {
        margin-left: 280px;
        min-height: 100vh;
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        position: absolute;
        right: 0;
        top: 0;
        width: calc(100% - 280px);
    }

    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
            width: 100%;
            position: relative;
        }
    }

    .content-area {
        padding: 3rem 2.5rem;
    }

    .page-header {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        padding: 2rem;
        border-radius: 24px;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, #166534, #14532d);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .table-container {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 24px;
        overflow-x: auto;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95rem;
    }

    .modern-table th,
    .modern-table td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid rgba(220, 252, 231, 0.8);
        text-align: left;
    }

    .modern-table th {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        font-weight: 700;
        color: #166534;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modern-table tbody tr:hover {
        background: rgba(34, 197, 94, 0.05);
    }

    .alert-success {
        background: linear-gradient(135deg, #d1fae5, #bbf7d0);
        border: 1px solid rgba(34, 197, 94, 0.2);
        color: #166534;
        padding: 1.25rem 1.5rem;
        border-radius: 16px;
        margin-bottom: 1.5rem;
    }

    /* Filter Styles */
    .filter-container {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 24px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #166534;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #d1fae5;
        border-radius: 12px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    }

    .filter-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .btn-filter {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-apply {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
    }

    .btn-apply:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-reset {
        background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        color: #4b5563;
        border: none;
    }

    .btn-reset:hover {
        background: linear-gradient(135deg, #e5e7eb, #d1d5db);
        transform: translateY(-2px);
    }
</style>

<div class="main-content">
    @include('layouts.header-siswa')

    <div class="content-area">
        <div class="page-header">
            <h2 class="page-title">
                <i class="fas fa-money-check-alt"></i> Riwayat Pembayaran
            </h2>
        </div>

        <!-- Filter Section -->
        <div class="filter-container">
            <h3><i class="fas fa-filter"></i> Filter Pencarian</h3>
            <form method="GET" action="{{ route('siswa.riwayat.index') }}">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="search"><i class="fas fa-search"></i> Nama atau NIS</label>
                        <input type="text" id="search" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama atau NIS...">
                    </div>

                    <div class="filter-group">
                        <label for="jenis_pembayaran"><i class="fas fa-receipt"></i> Jenis Pembayaran</label>
                        <select id="jenis_pembayaran" name="jenis_pembayaran">
                            <option value="">Semua Pembayaran</option>
                            <option value="sekolah" {{ (isset($jenisPembayaran) && $jenisPembayaran === 'sekolah') ? 'selected' : '' }}>Pembayaran Sekolah</option>
                            <option value="koperasi" {{ (isset($jenisPembayaran) && $jenisPembayaran === 'koperasi') ? 'selected' : '' }}>Koperasi</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="sekolah_id"><i class="fas fa-school"></i> Sekolah</label>
                        <select id="sekolah_id" name="sekolah_id">
                            <option value="">Semua Sekolah</option>
                            @foreach($sekolahList as $sekolah)
                                <option value="{{ $sekolah->id }}" {{ (isset($sekolahId) && $sekolahId == $sekolah->id) ? 'selected' : '' }}>
                                    {{ $sekolah->nama_sekolah }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="kelas_id"><i class="fas fa-users"></i> Kelas</label>
                        <select id="kelas_id" name="kelas_id">
                            <option value="">Semua Kelas</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ (isset($kelasId) && $kelasId == $kelas->id) ? 'selected' : '' }}>
                                    {{ $kelas->tingkat }} - {{ $kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="start_date"><i class="fas fa-calendar"></i> Tanggal Mulai</label>
                        <input type="date" id="start_date" name="start_date" value="{{ $startDate ?? '' }}">
                    </div>
                    
                    <div class="filter-group">
                        <label for="end_date"><i class="fas fa-calendar"></i> Tanggal Selesai</label>
                        <input type="date" id="end_date" name="end_date" value="{{ $endDate ?? '' }}">
                    </div>
                </div>
                
                <div class="filter-buttons">
                    <button type="submit" class="btn-filter btn-apply">
                        <i class="fas fa-filter"></i> Terapkan Filter
                    </button>
                    <a href="{{ route('siswa.riwayat.index') }}" class="btn-filter btn-reset">
                        <i class="fas fa-sync"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="table-container">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Jenis</th>
                        <th>Nama Tagihan</th>
                        <th>Jumlah Bayar</th>
                        <th>Tanggal Bayar</th>
                        <th>Metode Bayar</th>
                        <th>Cicilan ke</th>
                        <th>Sisa Cicilan</th>
                        <th>Sekolah</th>
                        <th>Kelas</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatPembayaran as $index => $bayar)
                        <tr>
                            <td>{{ $riwayatPembayaran->firstItem() + $index }}</td>
                            <td>{{ $bayar['source_label'] ?? 'Pembayaran Sekolah' }}</td>
                            <td>{{ $bayar['nama'] }}</td>
                            <td class="text-green-700 font-semibold">
                                Rp {{ number_format($bayar['jumlah_bayar'], 0, ',', '.') }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($bayar['tanggal_bayar'])->format('d/m/Y') }}</td>
                            <td>{{ $bayar['metode_bayar'] }}</td>
                            <td>
                                @if($bayar['cicilan_ke'])
                                    {{ $bayar['cicilan_ke'] }} / {{ $bayar['total_cicilan'] }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($bayar['total_cicilan'])
                                    {{ $bayar['sisa_cicilan'] }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $bayar['siswa']->sekolah->nama_sekolah ?? '-' }}</td>
                            <td>
                                @if($bayar['siswa']->kelas)
                                    {{ $bayar['siswa']->kelas->tingkat }} - {{ $bayar['siswa']->kelas->nama_kelas }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $bayar['keterangan'] ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-4 text-gray-500">
                                Belum ada riwayat pembayaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $riwayatPembayaran->links() }}
        </div>
    </div>
</div>
@endsection
