@extends('layouts.app')
@include('layouts.sidebar')

@section('content')
@push('page-styles')
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

    .page-header,
    .filter-form,
    .table-container {
        background: rgba(255,255,255,0.94);
        border: 1px solid rgba(34,197,94,0.12);
        box-shadow: 0 18px 38px rgba(15,23,42,0.09);
    }

    .page-header {
        align-items: center;
        border-radius: 22px;
        display: flex;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        padding: 2rem;
    }

    .page-title {
        align-items: center;
        color: #14532d;
        display: flex;
        font-size: 2rem;
        font-weight: 800;
        gap: 0.75rem;
        margin: 0;
    }

    .btn-primary {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border-radius: 12px;
        color: #fff;
        display: inline-flex;
        font-weight: 700;
        gap: 0.5rem;
        padding: 0.75rem 1.2rem;
        text-decoration: none;
    }

    .filter-form {
        align-items: flex-end;
        border-radius: 18px;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        min-width: 190px;
    }

    .form-group label {
        color: #166534;
        font-size: 0.9rem;
        font-weight: 700;
        margin-bottom: 0.45rem;
    }

    .form-group input,
    .form-group select {
        border: 2px solid rgba(34,197,94,0.18);
        border-radius: 12px;
        padding: 0.7rem 0.9rem;
    }

    .filter-btn,
    .reset-btn {
        border: none;
        border-radius: 12px;
        color: #fff;
        font-weight: 700;
        height: 44px;
        padding: 0 1rem;
        text-decoration: none;
    }

    .filter-btn {
        background: linear-gradient(135deg, #22c55e, #16a34a);
    }

    .reset-btn {
        align-items: center;
        background: linear-gradient(135deg, #64748b, #475569);
        display: inline-flex;
    }

    .table-container {
        border-radius: 22px;
        overflow-x: auto;
        overflow-y: hidden;
    }

    .modern-table {
        border-collapse: collapse;
        font-size: 0.93rem;
        table-layout: fixed;
        width: 100%;
        min-width: 980px;
    }

    /* Kolom No dibuat kecil */
    .modern-table th:nth-child(1),
    .modern-table td:nth-child(1) {
        width: 42px;
        max-width: 42px;
        text-align: center;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }

    .modern-table th:nth-child(2),
    .modern-table td:nth-child(2) {
        width: 22%;
    }

    .modern-table th:nth-child(3),
    .modern-table td:nth-child(3) {
        width: 13%;
    }

    .modern-table th:nth-child(4),
    .modern-table td:nth-child(4) {
        width: 17%;
    }

    .modern-table th:nth-child(5),
    .modern-table td:nth-child(5) {
        width: 13%;
    }

    .modern-table th:nth-child(6),
    .modern-table td:nth-child(6) {
        width: 13%;
    }

    /* Kolom Aksi dibuat lebih besar */
    .modern-table th:nth-child(7),
    .modern-table td:nth-child(7) {
        width: 220px;
        min-width: 220px;
    }

    .modern-table th,
    .modern-table td {
        border-bottom: 1px solid rgba(220,252,231,0.8);
        padding: 1rem 1.1rem;
        text-align: left;
        vertical-align: top;
    }

    .modern-table th {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        color: #166534;
        font-size: 0.82rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    .badge {
        border-radius: 999px;
        display: inline-flex;
        font-size: 0.78rem;
        font-weight: 800;
        padding: 0.35rem 0.65rem;
    }

    .badge-aman {
        background: #dcfce7;
        color: #166534;
    }

    .badge-menipis {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-habis {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-nonaktif {
        background: #e2e8f0;
        color: #475569;
    }

    .btn-edit,
    .btn-delete {
        border: none;
        border-radius: 8px;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        line-height: 1;
        min-height: 32px;
        min-width: 58px;
        padding: 0.48rem 0.65rem;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-edit {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .btn-delete {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        cursor: pointer;
    }

    .koperasi-actions {
        align-items: center;
        display: inline-flex;
        flex-wrap: nowrap;
        gap: 0.45rem;
        max-width: 100%;
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
    }

    .koperasi-actions > * {
        flex: 0 0 auto;
    }

    .koperasi-actions form {
        display: inline-flex !important;
        margin: 0;
    }

    .alert-success {
        background: #dcfce7;
        border-radius: 14px;
        color: #166534;
        margin-bottom: 1rem;
        padding: 1rem 1.25rem;
    }

    .pagination-wrapper {
        display: flex;
        justify-content: flex-end;
        margin-top: 1.5rem;
    }
</style>
@endpush

<div class="main-content">
    @include('layouts.header')

    <div class="content-area">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @include('partials.admin-page-context', [
            'section' => 'Koperasi',
            'current' => 'Stok Barang',
            'title' => 'Kelola stok barang koperasi sekolah.',
            'description' => 'Gunakan halaman ini untuk menambah, mengurangi, mengedit data barang, dan memantau stok sebelum dicatat di penjualan.',
            'steps' => ['Tambah barang', 'Sesuaikan stok', 'Catat penjualan']
        ])

        <div class="page-header">
            <h2 class="page-title">
                <i class="fas fa-boxes-stacked"></i> Stok Barang Koperasi
            </h2>

            <div class="page-actions">
                <a href="{{ route('koperasi.penjualan.index') }}" class="btn btn-info">
                    <i class="fas fa-cash-register"></i> Penjualan
                </a>

                <a href="{{ route('koperasi.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Barang
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('koperasi.index') }}" class="filter-form">
            <div class="form-group">
                <label for="sekolah_id">Sekolah</label>
                <select name="sekolah_id" id="sekolah_id">
                    <option value="">Semua Sekolah</option>
                    @foreach($sekolah as $item)
                        <option value="{{ $item->id }}" {{ $selectedSekolah == $item->id ? 'selected' : '' }}>
                            {{ $item->nama_sekolah }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="kategori">Kategori</label>
                <select name="kategori" id="kategori">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $key => $label)
                        <option value="{{ $key }}" {{ $selectedKategori == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ $selectedStatus == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ $selectedStatus == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="form-group">
                <label for="search">Cari Barang</label>
                <input
                    type="text"
                    name="search"
                    id="search"
                    value="{{ $search }}"
                    placeholder="Kode / nama barang..."
                >
            </div>

            <button type="submit" class="filter-btn">
                <i class="fas fa-filter"></i> Filter
            </button>

            <a href="{{ route('koperasi.index') }}" class="reset-btn">
                <i class="fas fa-undo"></i> Reset
            </a>
        </form>

        <div class="table-container">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Barang</th>
                        <th>Kategori</th>
                        <th>Sekolah</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($koperasi as $item)
                        <tr>
                            <td>
                                {{ $loop->iteration + ($koperasi->currentPage() - 1) * $koperasi->perPage() }}
                            </td>

                            <td>
                                <strong>{{ $item->nama_barang }}</strong><br>
                                <small>{{ $item->kode_barang ?: '-' }}</small>
                            </td>

                            <td>
                                {{ $kategoriList[$item->kategori] ?? ucfirst($item->kategori) }}
                            </td>

                            <td>
                                {{ $item->sekolah->nama_sekolah ?? '-' }}
                            </td>

                            <td>
                                Rp {{ number_format($item->harga_jual, 0, ',', '.') }}
                            </td>

                            <td>
                                {{ $item->stok }} {{ $item->satuan }}
                                <br>
                                <span class="badge badge-{{ $item->status_stok }}">
                                    {{ ucfirst($item->status_stok) }}
                                </span>
                            </td>

                            <td>
                                <div class="koperasi-actions">
                                    <a
                                        href="{{ route('koperasi.stok.edit', $item->id) }}"
                                        class="btn-edit"
                                        style="background: linear-gradient(135deg, #25845d, #1d6b4c);"
                                    >
                                        Stok
                                    </a>

                                    <a href="{{ route('koperasi.edit', $item->id) }}" class="btn-edit">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('koperasi.destroy', $item->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Hapus barang ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn-delete">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                @include('partials.admin-empty-state', [
                                    'icon' => 'fas fa-store',
                                    'title' => 'Belum Ada Barang Koperasi',
                                    'message' => 'Tambahkan buku, seragam, alat tulis, atau barang koperasi lain agar tidak tercampur dengan jenis pembayaran sekolah.',
                                    'actionRoute' => route('koperasi.create'),
                                    'actionText' => 'Tambah Barang Pertama'
                                ])
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            {{ $koperasi->links() }}
        </div>
    </div>
</div>
@endsection
