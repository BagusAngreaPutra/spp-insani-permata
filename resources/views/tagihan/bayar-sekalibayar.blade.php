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
    .content-area { padding: 3rem 2.5rem; }
    .card-box {
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.2);
    }
    .page-title {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, #2d3748, #4a5568);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .btn-primary {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: #fff;
        padding: 0.75rem 1.25rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
        box-shadow: 0 4px 12px rgba(37,99,235,0.3);
    }
    .btn-primary:hover { transform: translateY(-2px); }
    .modern-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95rem;
    }
    .modern-table th, .modern-table td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid rgba(220,252,231,0.8);
        text-align: left;
    }
    .modern-table th {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        font-weight: 700;
        color: #166534;
        font-size: 0.85rem;
        text-transform: uppercase;
    }

    .date-input-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .today-btn {
        padding: 0.5rem 0.75rem;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .today-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(34,197,94,0.3);
    }

    .status-card {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        border: 2px solid rgba(34, 197, 94, 0.3);
        border-radius: 24px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px rgba(34, 197, 94, 0.15);
    }

    .status-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: #16a34a;
    }

    .status-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #15803d;
        margin-bottom: 0.5rem;
    }

    .status-text {
        color: #166534;
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }
</style>
@endpush

<div class="main-content">
    @include('layouts.header')

    <div class="content-area">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="card-box">
            <div class="flex justify-between items-center mb-4">
                <h2 class="page-title"> Pembayaran Tagihan Sekali Bayar</h2>
                <a href="{{ route('tagihan.index') }}" class="btn-primary">
                    ← Kembali ke Menu Tagihan
                </a>
            </div>

            <p><strong>Siswa:</strong> {{ $tagihan->siswa->nama ?? '-' }}</p>

            {{-- cek tipe --}}
            <p><strong>Jenis Pembayaran:</strong>
                @if($tagihan->jenis_pembayaran_id === null)
                    SPP
                @else
                    {{ $tagihan->jenisPembayaran->nama_dengan_tahun ?? '-' }}
                @endif
            </p>

            <p><strong>Nominal Tagihan (sekali):</strong> Rp {{ number_format($tagihan->nominal,0,',','.') }}</p>
            <p><strong>Total Bayar:</strong> Rp {{ number_format($totalBayar,0,',','.') }}</p>
            <p><strong>Sisa Bayar:</strong> Rp {{ number_format($sisaBayar,0,',','.') }}</p>
        </div>

        {{-- Status Pembayaran --}}
        @if($tagihan->status === 'lunas')
            <div class="status-card">
                <div class="status-icon">✅</div>
                <h3 class="status-title">Pembayaran Telah Lunas</h3>
                <p class="status-text">
                    <strong>Total Pembayaran:</strong> Rp {{ number_format($tagihan->nominal,0,',','.') }}
                </p>
                <p class="status-text">
                    <strong>Status:</strong> Lunas
                </p>
                <p class="status-text">
                    <strong>Tanggal Pelunasan:</strong> 
                    {{ \Carbon\Carbon::parse($pembayaranHistory->last()->tanggal_bayar)->format('d F Y') }}
                </p>
            </div>
        @else
            {{-- Form Pembayaran --}}
            <div class="card-box">
                <h3 class="text-lg font-bold mb-3">💰 Tambah Pembayaran</h3>
                <form action="{{ route('tagihan.storePembayaranSekali', $tagihan->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="text-sm text-gray-600">Tanggal Bayar</label>
                        <div class="date-input-group">
                            <input type="date" 
                                name="tanggal_bayar" 
                                id="tanggal_bayar"
                                class="border rounded p-2 w-full" 
                                required>
                            <button type="button" 
                                    class="today-btn"
                                    onclick="setToday()">
                                Tanggal Hari Ini
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="text-sm text-gray-600">Jumlah Bayar</label>
                        {{-- otomatis ambil dari nominal tagihan --}}
                        <input type="number" name="jumlah_bayar" class="border rounded p-2 w-full bg-gray-100" data-rupiah
                            value="{{ $tagihan->nominal }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="text-sm text-gray-600">Keterangan (opsional)</label>
                        <input type="text" name="keterangan" class="border rounded p-2 w-full">
                    </div>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Simpan Pembayaran
                    </button>
                </form>
            </div>
        @endif

        {{-- Riwayat Pembayaran --}}
        <div class="card-box">
            <h3 class="text-lg font-bold mb-3">📄 Riwayat Pembayaran</h3>
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Pembayaran Ke -</th>
                        <th>Tanggal</th>
                        <th>Jumlah Bayar</th>
                        <th>Keterangan</th>
                        <th>Cetak Kwitasni</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayaranHistory as $p)
                        <tr>
                            <td>Pembayaran Ke {{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d-m-Y') }}</td>
                            <td>Rp {{ number_format($p->jumlah_bayar,0,',','.') }}</td>
                            <td>{{ $p->keterangan ?? '-' }}</td>
                            <td>
                                <div style="text-align: center;">
                                    <a href="{{ route('pembayaran.kwitansi', ['id' => $p->id]) }}" target="_blank" title="Cetak Kuitansi" class="text-green-600 hover:text-green-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M6 2a1 1 0 00-1 1v2h10V3a1 1 0 00-1-1H6zM4 6a2 2 0 00-2 2v4a2 2 0 002 2h1v3a1 1 0 001 1h8a1 1 0 001-1v-3h1a2 2 0 002-2V8a2 2 0 00-2-2H4zm2 9v-3h8v3H6z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">Belum ada pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function setToday() {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    const formattedDate = `${year}-${month}-${day}`;
    
    document.getElementById('tanggal_bayar').value = formattedDate;
}
</script>
@endsection
