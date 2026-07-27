@extends('layouts.app')
@include('layouts.sidebar')

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
            top: 0;
            right: auto;
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
    }
    .page-title {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, #2d3748, #4a5568);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
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
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
    }
    .modern-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95rem;
    }
    .modern-table th, .modern-table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid rgba(220,252,231,0.8);
        text-align: left;
        vertical-align: top;
    }
    .modern-table th {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        font-weight: 700;
        color: #166534;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .modern-table tbody tr:hover { background: rgba(34,197,94,0.05); }
    .alert-success {
        background: linear-gradient(135deg, #d1fae5, #bbf7d0);
        border: 1px solid rgba(34,197,94,0.2);
        color: #166534;
        padding: 1.25rem 1.5rem;
        border-radius: 16px;
        margin-bottom: 1.5rem;
    }
    .alert-error {
        background: #fee2e2;
        border: 1px solid #fecaca;
        color: #b91c1c;
        padding: 1.25rem 1.5rem;
        border-radius: 16px;
        margin-bottom: 1.5rem;
    }
    .input-date {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        border: 1px solid #ccc;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }
    .error-text {
        font-size: 0.8rem;
        color: #b91c1c;
        margin-top: -0.25rem;
        margin-bottom: 0.5rem;
    }
    .date-input-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .today-btn {
        padding: 0.5rem 0.75rem;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .today-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(34,197,94,0.3);
    }

    .payment-status-cell {
        display: flex;
        align-items: center;
        gap: 1rem;
        min-width: 150px;
    }

    .paid-checkbox {
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 6px;
        border: 2px solid #22c55e;
        position: relative;
        /* pointer-events: none; */ /* Removed to allow clicking */
        flex-shrink: 0;
    }

    .paid-checkbox:checked {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border-color: transparent;
    }

    .paid-checkbox:checked::after {
        content: '✓';
        position: absolute;
        color: white;
        font-size: 1rem;
        font-weight: bold;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .paid-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.4rem 0.8rem;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        gap: 0.375rem;
        box-shadow: 0 2px 4px rgba(34, 197, 94, 0.2);
    }

    .paid-badge svg {
        width: 0.875rem;
        height: 0.875rem;
    }

    .paid-row {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7) !important;
    }

    .paid-row td {
        border-bottom-color: rgba(34, 197, 94, 0.1);
    }
</style>

<div class="main-content">
    @include('layouts.header')
    <div class="content-area">

        {{-- ✅ Notifikasi sukses --}}
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        {{-- ✅ Notifikasi error global --}}
        @if ($errors->any())
            <div class="alert-error">
                <strong>⚠️ Terjadi kesalahan:</strong>
                <ul class="list-disc list-inside mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

       @php
            // Hitung total pembayaran (nominal per bulan x 12 bulan)
            $totalPembayaran = $tagihan->nominal * 12;

            // Hitung sisa bayar
            $sisaBayar = $totalPembayaran - $totalBayar;

            // Hitung berapa bulan sudah dibayar
            $bulanTerbayar = 0;
            if($tagihan->nominal > 0){
                $bulanTerbayar = floor($totalBayar / $tagihan->nominal);
            }

            // Hitung sisa bulan
            $sisaBulan = max(0, 12 - $bulanTerbayar);
        @endphp

        <div class="card-box">
            <div class="flex justify-between items-center mb-4">
                <h2 class="page-title">💳 Pembayaran Tagihan</h2>
                <a href="{{ route('tagihan.index') }}" class="btn-primary">
                    ← Kembali ke Menu Tagihan
                </a>
            </div>

            <p><strong>Siswa:</strong> {{ $tagihan->siswa->nama ?? '-' }}</p>

            {{-- Cek jenis pembayaran --}}
            <p><strong>Jenis Pembayaran:</strong>
                @if($tagihan->jenis_pembayaran_id === null)
                    SPP
                @else
                    {{ $tagihan->jenisPembayaran->nama_pembayaran ?? '-' }}
                @endif
            </p>

            <p><strong>Nominal Per Bulan:</strong> Rp {{ number_format($tagihan->nominal,0,',','.') }}</p>
            <p><strong>Total Pembayaran (12 bulan):</strong> Rp {{ number_format($totalPembayaran,0,',','.') }}</p>
            <p><strong>Total Bayar:</strong> Rp {{ number_format($totalBayar,0,',','.') }}</p>
            <p><strong>Sisa Bayar:</strong> Rp {{ number_format($sisaBayar,0,',','.') }}</p>
            <p><strong>Sisa Cicilan:</strong> {{ $sisaBulan }} bulan</p>
            
        </div>


        @if($tagihan->tipe === 'bulanan')
            @php
                $bulanIndo = [
                    1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
                    7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
                ];
                $tahunSekarang = date('Y');
                $periodeSudahBayar = $pembayaranHistory->pluck('periode')->toArray();
            @endphp

            <div class="card-box">
                <h3 class="text-lg font-bold mb-3">➕ Bayar Per Bulan</h3>
            <form action="{{ route('tagihan.storePembayaran', $tagihan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Pilih</th>
                            <th>Bulan</th>
                            <th>Nominal</th>
                            <th>Tanggal Bayar <span class="text-red-600">*</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i=1;$i<=12;$i++)
                            @php
                                $periode = $tahunSekarang.'-'.str_pad($i,2,'0',STR_PAD_LEFT);
                                $pembayaranPeriode = $pembayaranHistory->firstWhere('periode', $periode);
                                $sudahBayar = !is_null($pembayaranPeriode);
                                $tanggalBayar = $sudahBayar ? \Carbon\Carbon::parse($pembayaranPeriode->tanggal_bayar)->format('Y-m-d') : '';
                            @endphp
                            <tr class="{{ $sudahBayar ? 'paid-row' : '' }}">
                                <td>
                                    <div class="payment-status-cell">
                                        <input type="checkbox"
                                            class="paid-checkbox"
                                            name="bulan[{{ $i }}][periode]"
                                            value="{{ $periode }}"
                                            {{ $sudahBayar ? 'checked disabled' : '' }}>
                                        @if($sudahBayar)
                                            <span class="paid-badge">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                                Lunas
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $bulanIndo[$i] }}</td>
                                <td>Rp {{ number_format($tagihan->nominal,0,',','.') }}</td>
                                <td>
                                    <div class="date-input-group">
                                        <input type="date"
                                            name="bulan[{{ $i }}][tanggal_bayar]"
                                            class="input-date {{ $sudahBayar ? 'bg-green-50 border-green-200' : '' }}"
                                            id="date-input-{{ $i }}"
                                            value="{{ old("bulan.$i.tanggal_bayar", $tanggalBayar) }}"
                                            {{ $sudahBayar ? 'disabled' : '' }}>
                                        @if(!$sudahBayar)
                                            <button type="button" 
                                                    class="today-btn"
                                                    onclick="setToday({{ $i }})">
                                                Tanggal Hari Ini
                                            </button>
                                        @endif
                                    </div>
                                    @error("bulan.$i.tanggal_bayar")
                                        <div class="error-text">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>

                {{-- Tambahan opsional --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">

                    {{-- Metode Pembayaran --}}
                    <div>
                        <label class="block font-semibold mb-1">Metode Pembayaran <span class="text-red-500">*</span></label>
                        <select name="metode_bayar" class="w-full border border-gray-300 rounded" required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="tunai">Tunai</option>
                            <option value="transfer">Transfer</option>
                            <option value="kjc">KJC</option>
                            <option value="tabungan">Tabungan</option>
                        </select>
                    </div>

                    {{-- Keterangan (Opsional) --}}
                    <div>
                        <label class="block font-semibold mb-1">Keterangan</label>
                        <input type="text" name="keterangan" class="w-full border border-gray-300 rounded" placeholder="Opsional">
                    </div>

                    {{-- Bukti Bayar (Opsional) --}}
                    <div class="md:col-span-2">
                        <label class="block font-semibold mb-1">Upload Bukti Bayar (jpg/png/pdf)</label>
                        <input type="file" name="bukti_bayar" class="w-full border border-gray-300 rounded">
                    </div>
                </div>

                <button class="btn-primary mt-6" type="submit">💾 Simpan Pembayaran Bulanan</button>
            </form>

            </div>
        @endif

        <div class="card-box">
            <h3 class="text-lg font-bold mb-3">📄 Riwayat Pembayaran</h3>
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Jenis Pembayaran</th>
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
                        <td>
                            @if($tagihan->jenis_pembayaran_id === null)
                                SPP
                            @else
                                {{ $tagihan->jenisPembayaran->nama_pembayaran ?? '-' }}
                            @endif
                        </td>
                        <td>Pembayaran Ke {{ $loop->iteration }}</td>
                        <td>{{ $p->tanggal_bayar->format('d-m-Y') }}</td>
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
                        <td colspan="6" class="text-center py-4">Belum ada pembayaran.</td>
                    </tr>
                @endforelse
            </tbody>

            </table>
        </div>


    </div>
</div>
<script>
function setToday(index) {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    const formattedDate = `${year}-${month}-${day}`;
    
    document.getElementById(`date-input-${index}`).value = formattedDate;
}
</script>
@endsection
