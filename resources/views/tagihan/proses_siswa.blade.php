@extends('layouts.app')
@include('layouts.sidebar')

@section('content')
<style>
    /* Modern CSS Variables for consistent theming */
    :root {
        --primary-color: #10b981;
        --primary-light: #34d399;
        --primary-dark: #059669;
        --secondary-color: #6b7280;
        --surface-color: #ffffff;
        --surface-alt: #f8fafc;
        --border-color: #e5e7eb;
        --text-primary: #111827;
        --text-secondary: #6b7280;
        --success-bg: #d1fae5;
        --success-text: #065f46;
        --error-bg: #fee2e2;
        --error-text: #991b1b;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
        --shadow-md: 0 4px 12px rgba(0,0,0,0.1);
        --shadow-lg: 0 10px 25px rgba(0,0,0,0.1);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
    }

    /* Main Content Layout - TIDAK DIUBAH, menggunakan struktur dari layout.php */
    .main-content {
        margin-left: 280px;
        min-height: 100vh;
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 50%, #a7f3d0 100%);
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

    /* Content Area */
    .content-area { 
        padding: 2rem 1.5rem; 
    }

    /* Page Header */
    .page-header { 
        display: flex; 
        justify-content: space-between; 
        align-items: center;
        padding: 2.5rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(34, 197, 94, 0.1);
        margin-bottom: 2rem;
    }

    .page-title { 
        font-size: 2rem; 
        font-weight: 800; 
        background: linear-gradient(135deg, #14532d, #166534, #2e7247ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: flex; 
        align-items: center; 
        gap: 0.75rem;
    }

    .btn-generate {
        background: linear-gradient(135deg, #719e73ff, #119131ff);
        color: #fff;
        padding: 0.75rem 1.25rem;
        border-radius: 16px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.4s ease;
        display: inline-block;
        box-shadow: 0 8px 20px rgba(52, 102, 39, 0.3);
        border: none;
        cursor: pointer;
    }

    .btn-generate:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 12px 30px rgba(18, 150, 40, 0.4);
    }

    /* Student Info Card */
    .student-info-card { 
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 15px 35px rgba(34, 197, 94, 0.1);
        border: 2px solid rgba(34, 197, 94, 0.1);
    }

    .info-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
        gap: 1.5rem;
    }

    .info-item { display: flex; flex-direction: column; gap: 0.25rem; }
    .info-label { font-size: 0.875rem; color: #6b7280; font-weight: 500; }
    .info-value { font-size: 1.1rem; font-weight: 600; color: #1f2937; }

    /* Tagihan Container */
    .tagihan-container { 
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(22, 163, 74, 0.15);
        border: 2px solid rgba(34, 197, 94, 0.2);
    }

    .tagihan-header { 
        background: linear-gradient(135deg, #f0fdf4, #dcfce7, #bbf7d0);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(34, 197, 94, 0.1);
        font-weight: 600;
        color: #14532d;
    }

    .tagihan-header h3 {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
    }

    /* Table Styles */
    .tagihan-table { 
        width: 100%; 
        border-collapse: collapse;
    }

    .tagihan-table th, 
    .tagihan-table td { 
        padding: 1rem; 
        text-align: left; 
        border-bottom: 1px solid rgba(34, 197, 94, 0.1);
    }

    .tagihan-table th { 
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        font-weight: 600; 
        color: #14532d;
        font-size: 0.875rem; 
        text-transform: uppercase;
    }

    .tagihan-table tbody tr { 
        transition: background-color 0.2s ease;
    }

    .tagihan-table tbody tr:hover { 
        background: rgba(34, 197, 94, 0.05);
    }

    .checkbox-cell { width: 50px; text-align: center; }

    .checkbox-custom { 
        width: 20px; 
        height: 20px; 
        cursor: pointer; 
        accent-color: #22c55e;
    }

    /* Status Badges */
    .status-badge { 
        display: inline-block;
        padding: 0.25rem 0.75rem; 
        border-radius: 12px;
        font-size: 0.75rem; 
        font-weight: 600;
    }

    .status-lunas { 
        background: #d1fae5; 
        color: #14532d; 
        border: 1px solid #86efac;
    }

    .status-belum { 
        background: #fee2e2; 
        color: #7f1d1d; 
        border: 1px solid #fecaca;
    }

    .badge { 
        display: inline-block; 
        padding: 0.25rem 0.75rem; 
        border-radius: 12px;
        font-size: 0.75rem; 
        font-weight: 600; 
        background: #e5e7eb;
        color: #374151;
    }

    /* Grouped Rows */
    .grouped-row td { 
        font-weight: 600; 
        background-color: #f8fafc;
    }

    .details-row { display: none; }
    .details-row.show { display: table-row; }

    .details-table { 
        width: 100%; 
        background: #ffffff;
        border-spacing: 0;
    }

    .details-table th, 
    .details-table td { 
        padding: 0.75rem 1.5rem; 
        border-bottom: 1px solid #e5e7eb;
    }

    .details-table th { 
        background: #f9fafb;
        font-weight: 500;
        text-align: left;
    }

    .toggle-details-btn { 
        cursor: pointer; 
        color: #22c55e;
        font-size: 1.2rem;
        transition: all 0.2s ease;
        padding: 0.25rem;
    }

    .toggle-details-btn:hover {
        background: rgba(34, 197, 94, 0.1);
        border-radius: 4px;
    }

    /* Action Buttons Area */
    #action-buttons {
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        padding: 1rem 1.5rem; 
        border-top: 1px solid var(--border-color);
        background: var(--surface-alt);
    }

    #action-buttons span {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    #action-buttons strong {
        color: var(--text-primary);
        font-weight: 600;
    }

    /* Payment Form */
    .payment-form { 
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        margin-top: 2rem;
        box-shadow: 0 15px 35px rgba(34, 197, 94, 0.1);
        border: 2px solid rgba(34, 197, 94, 0.1);
        display: none;
    }

    .payment-form.active { display: block; }

    .payment-form h3 {
        margin-bottom: 1.5rem;
        color: #14532d;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Form Elements */
    .form-group { margin-bottom: 1.5rem; }

    .form-label { 
        display: block; 
        font-weight: 600; 
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-control { 
        width: 100%; 
        padding: 0.75rem 1rem; 
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .form-control:focus { 
        outline: none; 
        border-color: #22c55e;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    }

    /* Payment Details Table */
    #payment-details-table { 
        width: 100%; 
        border-collapse: collapse; 
        margin-bottom: 1.5rem;
    }

    #payment-details-table th, 
    #payment-details-table td { 
        padding: 0.75rem; 
        text-align: left; 
        border-bottom: 1px solid #e5e7eb;
    }

    #payment-details-table th { 
        background-color: #f9fafb;
        font-weight: 600;
    }

    .payment-amount-input { 
        width: 150px; 
        text-align: right;
    }

    /* Error States */
    .input-error { 
        border-color: #ef4444 !important;
    }

    .error-message { 
        color: #ef4444;
        font-size: 0.875rem; 
        margin-top: 0.25rem;
    }

    .discount-info { 
        color: #16a34a;
        font-size: 0.875rem; 
        font-weight: 600;
    }

    /* Buttons */
    .btn { 
        display: inline-flex; 
        align-items: center; 
        gap: 0.5rem; 
        padding: 0.75rem 1.5rem; 
        border-radius: 12px;
        font-weight: 600; 
        text-decoration: none; 
        transition: all 0.3s ease;
        border: none; 
        cursor: pointer;
    }

    .btn-primary { 
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
    }

    .btn-success { 
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
    }

    .btn-secondary { 
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
    }

    .btn:hover { 
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .button-group { 
        display: flex; 
        gap: 1rem; 
        justify-content: flex-start;
    }

    /* Empty State */
    .empty-state { 
        text-align: center; 
        padding: 3rem;
        color: #6b7280;
    }

    .empty-state i { 
        font-size: 3rem; 
        margin-bottom: 1rem;
    }

    /* Responsive Design - hanya untuk konten internal */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
            padding: 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .student-info-card {
            padding: 1.5rem;
        }

        .info-grid { 
            grid-template-columns: 1fr; 
            gap: 1rem;
        }
        
        .tagihan-table {
            font-size: 0.875rem;
        }

        .tagihan-table th,
        .tagihan-table td {
            padding: 0.75rem;
        }

        #action-buttons {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .button-group {
            justify-content: center;
        }

        .btn {
            justify-content: center;
        }

        .payment-form {
            padding: 1.5rem;
        }
    }

    /* Smooth animations untuk user experience yang lebih baik */
    .payment-form {
        animation: slideDown 0.3s ease-out;
    }

    .details-row.show {
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Hover effects yang subtle untuk pengalaman yang lebih interaktif */
    .student-info-card,
    .tagihan-container {
        transition: box-shadow 0.3s ease;
    }

    .student-info-card:hover {
        box-shadow: 0 20px 40px rgba(34, 197, 94, 0.15);
    }

    .tagihan-container:hover {
        box-shadow: 0 30px 60px rgba(22, 163, 74, 0.2);
    }

    /* Improved accessibility dan focus states */
    .checkbox-custom:focus,
    .btn:focus,
    .form-control:focus {
        outline: 2px solid #22c55e;
        outline-offset: 2px;
    }

    /* Loading state untuk feedback visual saat submit */
    .btn.loading {
        opacity: 0.8;
        cursor: not-allowed;
        transform: none;
    }

    /* Micro interactions untuk UX yang lebih smooth */
    .toggle-details-btn {
        transform-origin: center;
    }

    .fa-chevron-up {
        transform: rotate(180deg);
    }

    /* Better visual feedback untuk selected items */
    .tagihan-checkbox:checked + td {
        background: rgba(34, 197, 94, 0.05);
    }
    .focus,
    .btn:focus,
    .form-control:focus {
        outline: 2px solid var(--primary-color);
        outline-offset: 2px;
    }

    /* Subtle hover effects */
    .student-info-card:hover,
    .tagihan-container:hover {
        box-shadow: var(--shadow-md);
    }
</style>

<div class="main-content">
    @include('layouts.header')
    <div class="content-area">
        <div class="page-header">
            <h2 class="page-title"><i class="fas fa-user-graduate"></i> Proses Tagihan Siswa</h2>
            <div>
                <button type="button" class="btn-generate" data-bs-toggle="modal" data-bs-target="#generateModal">
                    <i class="fas fa-cogs"></i> Generate Tagihan Siswa
                </button>
                <a href="{{ route('tagihan.index.grouped') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </div>

        <!-- Generate Tagihan Modal -->
        <div class="modal fade" id="generateModal" tabindex="-1" aria-labelledby="generateModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="generateModalLabel">Generate Tagihan untuk {{ $siswa->nama }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin mengenerate tagihan khusus untuk siswa ini?</p>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> 
                            Proses ini hanya akan membuat tagihan baru untuk siswa ini jika memenuhi syarat.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('tagihan.generate.manual.siswa', $siswa->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-cogs"></i> Generate Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="student-info-card">
            <div class="info-grid">
                <div class="info-item"><span class="info-label">Nama Siswa</span><span class="info-value">{{ $siswa->nama }}</span></div>
                <div class="info-item"><span class="info-label">NIS</span><span class="info-value">{{ $siswa->nis }}</span></div>
                <div class="info-item"><span class="info-label">Kelas</span><span class="info-value">{{ $siswa->kelas->tingkat }} - {{ $siswa->kelas->nama_kelas }}</span></div>
                <div class="info-item"><span class="info-label">Sekolah</span><span class="info-value">{{ $siswa->sekolah->nama_sekolah }}</span></div>
            </div>
        </div>

        <div class="tagihan-container">
            <div class="tagihan-header">
                <i class="fas fa-file-invoice"></i> <h3>Daftar Tagihan</h3>
            </div>

            @if(count($tagihanList) > 0)
                <table class="tagihan-table">
                    <thead>
                        <tr>
                            <th class="checkbox-cell"><input type="checkbox" id="select-all-parent" class="checkbox-custom"></th>
                            <th>Nama Tagihan</th>
                            <th>Tipe</th>
                            <th>Periode</th>
                            <th>Nominal</th>
                            <th>Sudah Dibayar</th>
                            <th>Sisa</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tagihanList as $tagihan)
                            @if(isset($tagihan['is_grouped']) && $tagihan['is_grouped'])
                                {{-- Baris untuk tagihan bulanan yang digrupkan --}}
                                <tr class="grouped-row">
                                    <td><i class="fas fa-chevron-down toggle-details-btn" data-target="details-{{ $tagihan['id'] }}"></i></td>
                                    <td>{{ $tagihan['nama_tagihan'] }}</td>
                                    <td><span class="badge">{{ ucfirst($tagihan['tipe']) }}</span></td>
                                    <td>{{ $tagihan['periode'] }}</td>
                                    <td>Rp {{ number_format($tagihan['nominal'], 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($tagihan['total_bayar'], 0, ',', '.') }}</td>
                                    <td><strong>Rp {{ number_format($tagihan['sisa_bayar'], 0, ',', '.') }}</strong></td>
                                    <td>
                                        <span class="status-badge {{ $tagihan['status'] === 'lunas' ? 'status-lunas' : 'status-belum' }}">
                                        @if($tagihan['status'] === 'lunas')
                                            ✅ Lunas
                                        @else
                                            🕐 Belum Lunas
                                        @endif
                                        </span>
                                    </td>
                                    <td></td>
                                </tr>
                                {{-- Baris detail untuk setiap bulan (dropdown) --}}
                                <tr class="details-row" id="details-{{ $tagihan['id'] }}">
                                    <td colspan="9" style="padding: 0;">
                                        <table class="details-table">
                                            <thead>
                                                <tr>
                                                    <th class="checkbox-cell"><input type="checkbox" class="select-all-child checkbox-custom" data-parent-group="details-{{ $tagihan['id'] }}"></th>
                                                    <th>Periode Bulan</th>
                                                    <th>Nominal</th>
                                                    <th>Sisa Bayar</th>
                                                    <th>Status</th>
                                                    <th>Jatuh Tempo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($tagihan['bulan_tagihan'] as $bulan)
                                                    <tr>
                                                        <td class="checkbox-cell">
                                                            @if($bulan['status'] !== 'lunas' && $bulan['sisa_bayar'] > 0)
                                                                <input type="checkbox"
                                                                       class="tagihan-checkbox checkbox-custom"
                                                                       value="{{ $bulan['id'] }}"
                                                                       data-nominal="{{ $bulan['sisa_bayar'] }}"
                                                                       data-periode="{{ \Carbon\Carbon::parse($bulan['tanggal_jatuh_tempo'])->format('Y-m') }}"
                                                                       data-is-spp="{{ str_contains(strtolower($tagihan['nama_tagihan']), 'spp') ? 'true' : 'false' }}">
                                                            @endif
                                                        </td>
                                                        {{-- ✅ PERBAIKAN: Gunakan periode_display untuk nama bulan Indonesia --}}
                                                        <td>{{ $bulan['periode_display'] ?? $bulan['periode'] }}</td>
                                                        <td>Rp {{ number_format($bulan['nominal'], 0, ',', '.') }}</td>
                                                        <td><strong>Rp {{ number_format($bulan['sisa_bayar'], 0, ',', '.') }}</strong></td>
                                                        <td>
                                                            <span class="status-badge {{ $bulan['status'] === 'lunas' ? 'status-lunas' : 'status-belum' }}">
                                                                @if($bulan['status'] === 'lunas')
                                                                    <i class="fas fa-check-circle"></i> Lunas
                                                                @else
                                                                    <i class="fas fa-clock"></i> Belum Lunas
                                                                @endif
                                                            </span>
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($bulan['tanggal_jatuh_tempo'])->format('d M Y') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @else
                                {{-- Baris untuk tagihan non-bulanan --}}
                                <tr>
                                    <td class="checkbox-cell">
                                        @if($tagihan['status'] !== 'lunas' && $tagihan['sisa_bayar'] > 0)
                                            <input type="checkbox" class="tagihan-checkbox checkbox-custom" value="{{ $tagihan['id'] }}" data-nominal="{{ $tagihan['sisa_bayar'] }}">
                                        @endif
                                    </td>
                                    <td>{{ $tagihan['nama_tagihan'] }}</td>
                                    <td><span class="badge">{{ ucfirst($tagihan['tipe']) }}</span></td>
                                    <td>{{ $tagihan['periode'] ?? '-' }}</td>
                                    <td>Rp {{ number_format($tagihan['nominal'], 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($tagihan['total_bayar'], 0, ',', '.') }}</td>
                                    <td><strong>Rp {{ number_format($tagihan['sisa_bayar'], 0, ',', '.') }}</strong></td>
                                    <td>
                                        <span class="status-badge {{ $tagihan['status'] === 'lunas' ? 'status-lunas' : 'status-belum' }}">
                                        @if($tagihan['status'] === 'lunas')
                                            ✅ Lunas
                                        @else
                                            🕐 Belum Lunas
                                        @endif
                                        </span>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>

                <div id="action-buttons" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; border-top: 1px solid var(--border-color); background: var(--surface-alt);">
                   <div>
                       <span style="font-weight: 500;">Tagihan Dipilih: <strong id="selected-count">0</strong></span>
                       <span style="margin-left: 1rem; font-weight: 500;">Total Pembayaran: <strong id="total-payment">Rp 0</strong></span>
                   </div>
                   <button id="multi-payment-btn" class="btn btn-success" onclick="processMultiPayment()">
                       <i class="fas fa-check-circle"></i> Bayar Tagihan Terpilih
                   </button>
               </div>

                <div id="payment-form" class="payment-form">
                    <h3><i class="fas fa-credit-card"></i> Pembayaran Multi-Tagihan</h3>
                    <p style="margin-top: -0.5rem; margin-bottom: 1.5rem; font-size: 0.875rem; color: var(--text-secondary);">
                       Jumlah bayar di bawah ini dapat Anda sesuaikan untuk pembayaran angsuran (parsial).
                    </p>

                    <form action="{{ route('tagihan.proses.multi') }}" method="POST" id="multi-payment-form">
                        @csrf
                        <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">

                        {{-- Container for dynamic payment inputs --}}
                        <div id="selected-tagihan-container">
                           {{-- JS will populate this area --}}
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tanggal Bayar</label>
                            <input type="date" name="tanggal_bayar" id="tanggal-bayar" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Metode Pembayaran</label>
                            <select name="metode_bayar" class="form-control" required>
                                <option value="tunai">💵 Tunai</option>
                                <option value="transfer">🏦 Transfer Bank</option>
                                <option value="kjc">🏢 KJC</option>
                                <option value="tabungan">💰 Potongan Dari Tabungan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Keterangan (Opsional)</label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Tambahkan keterangan jika diperlukan..."></textarea>
                        </div>

                        <div class="button-group">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check-circle"></i> Proses Pembayaran
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="cancelMultiPayment()">
                                <i class="fas fa-times"></i> Batal
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-file-invoice"></i>
                    <p>Tidak ada tagihan untuk siswa ini</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// ✅ PERBAIKAN LENGKAP JAVASCRIPT UNTUK SISTEM DISKON
document.addEventListener('DOMContentLoaded', function() {
    const selectAllParent = document.getElementById('select-all-parent');
    const tagihanCheckboxes = document.querySelectorAll('.tagihan-checkbox');
    const toggleButtons = document.querySelectorAll('.toggle-details-btn');
    const selectAllChildren = document.querySelectorAll('.select-all-child');
    const tanggalBayarEl = document.getElementById('tanggal-bayar');

    // ✅ Event listener untuk perubahan tanggal bayar
    if (tanggalBayarEl) {
        tanggalBayarEl.addEventListener('change', function() {
            recalculateDiscounts();
        });
    }

    // ✅ PERBAIKAN: Event listener untuk input jumlah bayar agar diskon langsung terpotong
    document.addEventListener('input', function(e) {
        if (e.target && e.target.classList.contains('payment-amount-input')) {
            const row = e.target.closest('tr');
            const tagihanId = row.dataset.tagihanId;
            const sisaBayarAsli = parseInt(row.dataset.sisaBayarAsli, 10);
            const amountInput = e.target;
            const errorDiv = document.getElementById(`error-${tagihanId}`);
            const maxAmount = parseInt(amountInput.max) || sisaBayarAsli;

            // Bersihkan error sebelumnya
            if (errorDiv) errorDiv.style.display = 'none';

            // ✅ TRIGGER: Recalculate discount ketika value valid
            // Delay sedikit untuk memastikan DOM terupdate
            setTimeout(recalculateDiscounts, 50);
        }
    });

    function updateSelectedCount() {
        const selectedCheckboxes = document.querySelectorAll('.tagihan-checkbox:checked');
        const multiPaymentBtn = document.getElementById('multi-payment-btn');
        const actionButtons = document.getElementById('action-buttons');
        const count = selectedCheckboxes.length;
        let total = 0;

        selectedCheckboxes.forEach(checkbox => {
            total += parseInt(checkbox.dataset.nominal) || 0;
        });

        const selectedCountEl = document.getElementById('selected-count');
        const totalPaymentEl = document.getElementById('total-payment');
        if (selectedCountEl) selectedCountEl.textContent = count;
        if (totalPaymentEl) totalPaymentEl.textContent = 'Rp ' + total.toLocaleString('id-ID');

        if (actionButtons) {
            actionButtons.style.display = count > 0 ? 'flex' : 'none';
        }

        if (selectAllParent) {
            const totalCheckboxes = document.querySelectorAll('.tagihan-checkbox').length;
            if (count === 0) {
                selectAllParent.checked = false;
                selectAllParent.indeterminate = false;
            } else if (count === totalCheckboxes) {
                selectAllParent.checked = true;
                selectAllParent.indeterminate = false;
            } else {
                selectAllParent.checked = false;
                selectAllParent.indeterminate = true;
            }
        }
    }

    selectAllParent?.addEventListener('change', function() {
        tagihanCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });

    tagihanCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const targetRow = document.getElementById(targetId);
            if (targetRow) {
                targetRow.classList.toggle('show');
                this.classList.toggle('fa-chevron-down');
                this.classList.toggle('fa-chevron-up');
            }
        });
    });

    selectAllChildren.forEach(parentCheckbox => {
        parentCheckbox.addEventListener('change', function() {
            const parentGroupId = this.dataset.parentGroup;
            const parentGroup = document.getElementById(parentGroupId);
            if (parentGroup) {
                const childCheckboxes = parentGroup.querySelectorAll('.tagihan-checkbox');
                childCheckboxes.forEach(child => {
                    child.checked = this.checked;
                });
                updateSelectedCount();
            }
        });
    });

    updateSelectedCount();
});

// ✅ PERBAIKAN: Update fungsi processMultiPayment dengan hidden inputs yang benar
function processMultiPayment() {
    const selectedCheckboxes = document.querySelectorAll('.tagihan-checkbox:checked');
    if (selectedCheckboxes.length === 0) {
        alert('Pilih minimal satu tagihan untuk dibayar!');
        return;
    }

    const paymentForm = document.getElementById('payment-form');
    const actionButtons = document.getElementById('action-buttons');
    const container = document.getElementById('selected-tagihan-container');

    let tableHTML = `
        <table id="payment-details-table">
            <thead>
                <tr>
                    <th>Nama Tagihan</th>
                    <th>Sisa Tagihan</th>
                    <th>Jumlah Bayar</th>
                </tr>
            </thead>
            <tbody>`;

    selectedCheckboxes.forEach(checkbox => {
        const tableRow = checkbox.closest('tr');
        let tagName = 'Tagihan';
        if (tableRow) {
            let nameCell = tableRow.querySelector('td:nth-child(2)');
            if (nameCell) tagName = nameCell.textContent.trim();
        }

        const tagihanId = checkbox.value;
        const sisaBayar = parseInt(checkbox.dataset.nominal) || 0;
        const isSpp = checkbox.dataset.isSpp === 'true';
        const periode = checkbox.dataset.periode;

        tableHTML += `
            <tr data-tagihan-id="${tagihanId}" 
                data-is-spp="${isSpp}" 
                data-periode="${periode}" 
                data-sisa-bayar-asli="${sisaBayar}">
                <td>
                   ${tagName}
                   <div class="discount-info" style="display: none;" id="diskon-info-${tagihanId}"></div>
                </td>
                <td id="sisa-bayar-${tagihanId}">Rp ${sisaBayar.toLocaleString('id-ID')}</td>
                <td>
                    <input type="number" 
                           name="pembayaran[${tagihanId}]"
                           class="form-control payment-amount-input"
                           value="${sisaBayar}"
                           data-original-max="${sisaBayar}"
                           min="0"
                           oninput="validateAngsuran(this)"
                           required>
                    <div class="error-message" style="display: none;">Jumlah melebihi sisa tagihan!</div>

                    <!-- ✅ PERBAIKAN: Hidden inputs untuk mengirim data diskon ke backend -->
                    <input type="hidden" name="original_amount[${tagihanId}]" value="${sisaBayar}" id="original-amount-${tagihanId}">
                    <input type="hidden" name="discount_amount[${tagihanId}]" value="0" id="discount-amount-${tagihanId}">
                    <input type="hidden" name="has_discount[${tagihanId}]" value="false" id="has-discount-${tagihanId}">
                </td>
            </tr>`;
    });

    tableHTML += `</tbody></table>`;
    container.innerHTML = tableHTML;

    // ✅ PENTING: Hitung diskon setelah tabel dibuat
    recalculateDiscounts();

    paymentForm.classList.add('active');
    if(actionButtons) actionButtons.style.display = 'none';
    paymentForm.scrollIntoView({ behavior: 'smooth' });
}

// ✅ PERBAIKAN UTAMA: Fungsi recalculateDiscounts dengan logika yang benar
function recalculateDiscounts() {
    const tanggalBayarEl = document.getElementById('tanggal-bayar');
    if (!tanggalBayarEl || !tanggalBayarEl.value) return;

    const tanggalBayar = new Date(tanggalBayarEl.value + 'T00:00:00');
    const paymentRows = document.querySelectorAll('#payment-details-table tbody tr');

    paymentRows.forEach(row => {
        const isSpp = row.dataset.isSpp === 'true';
        const tagihanId = row.dataset.tagihanId;
        const periode = row.dataset.periode;
        const sisaBayarAsli = parseInt(row.dataset.sisaBayarAsli, 10);

        // Ambil elemen-elemen yang dibutuhkan
        const diskonInfo = document.getElementById(`diskon-info-${tagihanId}`);
        const amountInput = row.querySelector('input[type="number"]');
        const sisaBayarDisplay = document.getElementById(`sisa-bayar-${tagihanId}`);

        // Hidden inputs
        const discountAmountInput = document.getElementById(`discount-amount-${tagihanId}`);
        const hasDiscountInput = document.getElementById(`has-discount-${tagihanId}`);
        const originalAmountInput = document.getElementById(`original-amount-${tagihanId}`);

        if (!isSpp || !periode) {
            // For non-SPP payments, ensure the input is editable and no discount logic is applied
            resetDiskonInfoNonSpp(diskonInfo, amountInput, discountAmountInput, hasDiscountInput, originalAmountInput, sisaBayarAsli);
            
            // Make sure the input is properly configured for non-SPP payments
            if (amountInput) {
                amountInput.max = sisaBayarAsli;
                amountInput.dataset.originalMax = sisaBayarAsli;
                amountInput.readOnly = false; // Ensure it's not readonly
                // Jangan mengatur ulang nilai - biarkan pengguna mengedit
            }
            return;
        }

        // Untuk tagihan SPP, cek apakah dapat diskon
        const periodeDate = new Date(periode + '-01T00:00:00');
        const batasDiskon = new Date(periodeDate.getFullYear(), periodeDate.getMonth(), 10); // Tanggal 10

        const dapatDiskon = tanggalBayar <= batasDiskon;
        const jumlahBayar = parseInt(amountInput.value) || 0;

        if (dapatDiskon && jumlahBayar > 0) {
            // ✅ LOGIKA BARU: Diskon hanya berlaku jika pembayaran LUNAS
            const diskonAmount = 25000;
            const jumlahBayarSetelahDiskon = Math.max(0, sisaBayarAsli - diskonAmount);

            // ✅ CEK: Apakah user akan membayar LUNAS?
            const akanLunas = jumlahBayar >= sisaBayarAsli;

            if (akanLunas) {
                // ✅ DAPAT DISKON - Pembayaran Lunas
                if (diskonInfo) {
                    diskonInfo.style.display = 'block';
                    diskonInfo.innerHTML = `
                        <i class="fas fa-tag" style="color: #16a34a;"></i> 
                        <span style="color: #16a34a; font-weight: 600;">
                            DISKON Rp ${diskonAmount.toLocaleString('id-ID')} - 
                            Bayar Rp ${jumlahBayarSetelahDiskon.toLocaleString('id-ID')} = LUNAS!
                        </span>
                    `;
                }

                // Set nilai otomatis untuk mendapatkan diskon
                amountInput.value = jumlahBayarSetelahDiskon;

                // Update hidden inputs
                if (discountAmountInput) discountAmountInput.value = diskonAmount.toString();
                if (hasDiscountInput) hasDiscountInput.value = 'true';
                if (originalAmountInput) originalAmountInput.value = sisaBayarAsli.toString();

            } else {
                // ✅ TIDAK DAPAT DISKON - Pembayaran Angsuran
                if (diskonInfo) {
                    diskonInfo.style.display = 'block';
                    diskonInfo.innerHTML = `
                        <i class="fas fa-info-circle" style="color: #f59e0b;"></i> 
                        <span style="color: #f59e0b; font-weight: 600;">
                            Diskon Rp ${diskonAmount.toLocaleString('id-ID')} hanya berlaku untuk pembayaran LUNAS.
                            <br>Bayar Rp ${sisaBayarAsli.toLocaleString('id-ID')} untuk mendapat diskon.
                        </span>
                    `;
                }

                // Reset diskon
                resetDiskonValue(discountAmountInput, hasDiscountInput, originalAmountInput, sisaBayarAsli);
            }

        } else if (dapatDiskon) {
            // Masih dalam periode diskon tapi belum input jumlah atau jumlah = 0
            if (diskonInfo) {
                diskonInfo.style.display = 'block';
                const jumlahBayarOptimal = Math.max(0, sisaBayarAsli - 25000);
                diskonInfo.innerHTML = `
                    <i class="fas fa-tag" style="color: #3b82f6;"></i> 
                    <span style="color: #3b82f6; font-weight: 600;">
                        Tersedia diskon Rp ${(25000).toLocaleString('id-ID')} untuk pembayaran LUNAS.
                        <br>Bayar Rp ${sisaBayarAsli.toLocaleString('id-ID')} untuk mendapat diskon.
                    </span>
                `;
            }
            resetDiskonValue(discountAmountInput, hasDiscountInput, originalAmountInput, sisaBayarAsli);
        } else {
            // ✅ TIDAK DAPAT DISKON - Lewat tanggal
            if (diskonInfo) {
                diskonInfo.style.display = 'block';
                diskonInfo.innerHTML = `
                    <i class="fas fa-exclamation-triangle" style="color: #ef4444;"></i> 
                    <span style="color: #ef4444; font-weight: 600;">
                        Periode diskon sudah berakhir (batas tanggal 10 setiap bulan).
                    </span>
                `;
            }
            resetDiskonValue(discountAmountInput, hasDiscountInput, originalAmountInput, sisaBayarAsli);
        }

        // Update max input
        if (amountInput) {
            amountInput.max = sisaBayarAsli;
            amountInput.dataset.originalMax = sisaBayarAsli;
            amountInput.readOnly = false; // Ensure it's not readonly
            // Jangan mengatur ulang nilai - biarkan pengguna mengedit
        }

        // Validasi input setelah perubahan
        if (amountInput) {
            validateAngsuran(amountInput);
        }
    });
}

// ✅ Helper functions
function resetDiskonInfoNonSpp(diskonInfo, amountInput, discountAmountInput, hasDiscountInput, originalAmountInput, sisaBayarAsli) {
    if (diskonInfo) diskonInfo.style.display = 'none';
    // Jangan mengatur ulang nilai amountInput untuk pembayaran non-SPP
    if (amountInput) {
        amountInput.max = sisaBayarAsli;
        amountInput.dataset.originalMax = sisaBayarAsli;
        amountInput.readOnly = false; // Ensure it's not readonly
    }
    resetDiskonValue(discountAmountInput, hasDiscountInput, originalAmountInput, sisaBayarAsli);
}

function resetDiskonInfo(diskonInfo, amountInput, discountAmountInput, hasDiscountInput, originalAmountInput, sisaBayarAsli) {
    if (diskonInfo) diskonInfo.style.display = 'none';
    if (amountInput) {
        // Hapus nilai amountInput agar tidak mengatur ulang ke sisaBayarAsli
        // Biarkan pengguna mengedit nilai sesuai keinginan mereka
        amountInput.max = sisaBayarAsli;
        amountInput.dataset.originalMax = sisaBayarAsli;
        amountInput.readOnly = false; // Ensure it's not readonly
    }
    resetDiskonValue(discountAmountInput, hasDiscountInput, originalAmountInput, sisaBayarAsli);
}

function resetDiskonValue(discountAmountInput, hasDiscountInput, originalAmountInput, sisaBayarAsli) {
    if (discountAmountInput) discountAmountInput.value = '0';
    if (hasDiscountInput) hasDiscountInput.value = 'false';
    if (originalAmountInput) originalAmountInput.value = sisaBayarAsli.toString();
}

function cancelMultiPayment() {
    document.getElementById('payment-form').classList.remove('active');
    document.getElementById('action-buttons').style.display = 'flex';
}

// ✅ PERBAIKAN: Fungsi validasi yang lebih akurat
function validateAngsuran(input) {
    const value = parseInt(input.value, 10) || 0;
    const originalMax = parseInt(input.dataset.originalMax, 10) || parseInt(input.max, 10) || 0;
    const errorDiv = input.parentElement.querySelector('.error-message');

    if (value > originalMax) {
        input.classList.add('input-error');
        if (errorDiv) {
            errorDiv.style.display = 'block';
            errorDiv.textContent = `Jumlah tidak boleh melebihi Rp ${originalMax.toLocaleString('id-ID')}`;
        }
    } else if (value < 0) {
        input.classList.add('input-error');
        if (errorDiv) {
            errorDiv.style.display = 'block';
            errorDiv.textContent = 'Jumlah tidak boleh kurang dari 0';
        }
    } else {
        input.classList.remove('input-error');
        if (errorDiv) errorDiv.style.display = 'none';
        
        // ✅ HAPUS: Pemaksaan nilai yang mengganggu pengeditan pengguna
        // Tidak perlu memaksa nilai kembali ke maksimum - biarkan pengguna mengedit
    }
}

// ✅ TAMBAHAN: Validasi form sebelum submit
document.addEventListener('DOMContentLoaded', function() {
    const multiPaymentForm = document.getElementById('multi-payment-form');
    if (multiPaymentForm) {
        multiPaymentForm.addEventListener('submit', function(e) {
            const inputs = this.querySelectorAll('.payment-amount-input');
            let hasError = false;
            let totalBayar = 0;

            inputs.forEach(input => {
                validateAngsuran(input);
                if (input.classList.contains('input-error')) {
                    hasError = true;
                } else {
                    totalBayar += parseInt(input.value) || 0;
                }
            });

            if (hasError) {
                e.preventDefault();
                alert('Harap perbaiki jumlah pembayaran yang tidak valid sebelum melanjutkan.');
                return false;
            }

            if (totalBayar <= 0) {
                e.preventDefault();
                alert('Minimal satu tagihan harus memiliki jumlah pembayaran > 0.');
                return false;
            }

            // Konfirmasi sebelum submit
            const confirmMsg = `Anda akan memproses pembayaran dengan total Rp ${totalBayar.toLocaleString('id-ID')}. Lanjutkan?`;
            if (!confirm(confirmMsg)) {
                e.preventDefault();
                return false;
            }
        });
    }
});

// ✅ TAMBAHAN: Auto-suggest optimal payment amount
function suggestOptimalPayment(tagihanId) {
    const row = document.querySelector(`tr[data-tagihan-id="${tagihanId}"]`);
    if (!row) return;

    const isSpp = row.dataset.isSpp === 'true';
    const periode = row.dataset.periode;
    const sisaBayarAsli = parseInt(row.dataset.sisaBayarAsli, 10);
    const amountInput = row.querySelector('input[type="number"]');

    if (!isSpp || !periode || !amountInput) return;

    const tanggalBayarEl = document.getElementById('tanggal-bayar');
    if (!tanggalBayarEl || !tanggalBayarEl.value) return;

    const tanggalBayar = new Date(tanggalBayarEl.value + 'T00:00:00');
    const periodeDate = new Date(periode + '-01T00:00:00');
    const batasDiskon = new Date(periodeDate.getFullYear(), periodeDate.getMonth(), 10);

    if (tanggalBayar <= batasDiskon) {
        const jumlahOptimal = Math.max(0, sisaBayarAsli - 25000);
        if (confirm(`Saran: Bayar Rp ${jumlahOptimal.toLocaleString('id-ID')} untuk mendapat diskon Rp 25.000. Setuju?`)) {
            amountInput.value = jumlahOptimal;
            recalculateDiscounts();
        }
    }
}

// ✅ TAMBAHAN: Quick action buttons
function quickPayLunas(tagihanId) {
    const row = document.querySelector(`tr[data-tagihan-id="${tagihanId}"]`);
    if (!row) return;

    const sisaBayarAsli = parseInt(row.dataset.sisaBayarAsli, 10);
    const amountInput = row.querySelector('input[type="number"]');

    if (amountInput) {
        amountInput.value = sisaBayarAsli;
        recalculateDiscounts();
    }
}

function quickPayOptimal(tagihanId) {
    suggestOptimalPayment(tagihanId);
}

// ✅ Console log untuk debugging
console.log('✅ SPP Discount System Loaded Successfully');
console.log('📋 Features:');
console.log('   - Discount only for FULL payment (not installment)');
console.log('   - Discount valid until 10th of each month');
console.log('   - Real-time discount calculation');
console.log('   - Input validation with discount consideration');
</script>
@endsection