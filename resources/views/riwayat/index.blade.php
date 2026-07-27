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

    .content-area {
        padding: 3rem 2.5rem;
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        padding: 2rem;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
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

    /* Alert Styles */
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

    .filter-container h3 {
        margin-top: 0;
        color: #2d3748;
        font-weight: 700;
        margin-bottom: 1.5rem;
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
        color: #4a5568;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #22c55e;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.2);
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
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
        border: none;
    }

    .btn-apply:hover {
        background: linear-gradient(135deg, #16a34a, #15803d);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
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

    /* Transaction Cards */
    .transaction-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .transaction-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 35px 65px rgba(0, 0, 0, 0.2);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(220, 252, 231, 0.8);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .card-header:hover {
        background: rgba(34, 197, 94, 0.02);
    }

    .header-main-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .header-main-info .student-name {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1f2937;
    }

    .header-main-info .transaction-date {
        font-size: 0.95rem;
        color: #6b7280;
        font-weight: 500;
    }

    .header-details {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-top: 0.5rem;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .detail-item i {
        color: #22c55e;
        font-size: 0.9rem;
    }

    .card-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.75rem;
    }

    .total-amount {
        font-weight: 800;
        font-size: 1.3rem;
        color: #166534;
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        padding: 0.75rem 1.25rem;
        border-radius: 12px;
        text-align: center;
        min-width: 150px;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .details-toggle {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: #fff;
        padding: 0.6rem 1rem;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
        font-size: 0.9rem;
    }

    .details-toggle:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(34, 197, 94, 0.4);
    }

    .print-btn {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
        padding: 0.6rem;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        font-size: 0.9rem;
    }

    .print-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);
        text-decoration: none;
        color: #fff;
    }

    .card-body {
        display: none;
        padding: 2rem;
        background: linear-gradient(135deg, #f9fafb, #f3f4f6);
    }

    /* Details Table */
    .details-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95rem;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
    }

    .details-table th,
    .details-table td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid rgba(220, 252, 231, 0.8);
        text-align: left;
    }

    .details-table th {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        font-weight: 700;
        color: #166534;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .details-table tbody tr {
        transition: all 0.3s ease;
    }

    .details-table tbody tr:hover {
        background: rgba(34, 197, 94, 0.05);
    }

    .details-table tr:last-child td {
        border-bottom: none;
    }

    /* Discount display styles */
    .discount-amount.has-discount {
        color: #dc2626;
        font-weight: 600;
    }

    .discount-amount.no-discount {
        color: #9ca3af;
        font-style: italic;
    }

    .source-badge {
        background: #ecfdf5;
        border: 1px solid #bbf7d0;
        border-radius: 999px;
        color: #166534;
        display: inline-flex;
        font-size: 0.78rem;
        font-weight: 800;
        padding: 0.25rem 0.65rem;
        text-transform: uppercase;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #6b7280;
    }

    .empty-state i {
        font-size: 3rem;
        color: #22c55e;
        margin-bottom: 1rem;
    }

    .empty-state p {
        font-size: 1.1rem;
        margin: 0;
        font-weight: 500;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .content-area {
            padding: 1.5rem 1rem;
        }
        
        .page-header {
            padding: 1.5rem;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
        
        .card-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
            padding: 1.25rem 1.5rem;
        }
        
        .header-main-info {
            align-items: center;
        }
        
        .card-actions {
            align-items: center;
            flex-direction: row;
            justify-content: center;
        }
        
        .total-amount {
            font-size: 1.1rem;
            padding: 0.5rem 1rem;
        }
        
        .details-table th,
        .details-table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .filter-row {
            flex-direction: column;
            gap: 1rem;
        }
        
        .filter-group {
            min-width: 100%;
        }
        
        .action-buttons {
            width: 100%;
            justify-content: center;
        }
    }

    /* Animation untuk slide down */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card-body {
        animation: slideDown 0.3s ease;
    }
    
    /* Loading state for class dropdown */
    .loading-option {
        color: #9ca3af;
        font-style: italic;
    }
</style>

<div class="main-content">
    @include('layouts.header')

    <div class="content-area">
        @if(session('success'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="page-header">
            <h2 class="page-title">
                <i class="fas fa-history"></i> Riwayat Transaksi
            </h2>
        </div>

        <!-- Filter Section -->
        <div class="filter-container">
            <h3><i class="fas fa-filter"></i> Filter Pencarian</h3>
            <form method="GET" action="{{ route('riwayat.index') }}" id="filterForm">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="search"><i class="fas fa-search"></i> Nama atau NIS</label>
                        <input type="text" id="search" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama atau NIS...">
                    </div>

                    <div class="filter-group">
                        <label for="jenis_pembayaran"><i class="fas fa-receipt"></i> Jenis Pembayaran</label>
                        <select id="jenis_pembayaran" name="jenis_pembayaran">
                            <option value="">Semua Pembayaran</option>
                            <option value="sekolah" {{ (isset($selectedJenisPembayaran) && $selectedJenisPembayaran === 'sekolah') ? 'selected' : '' }}>Pembayaran Sekolah</option>
                            <option value="koperasi" {{ (isset($selectedJenisPembayaran) && $selectedJenisPembayaran === 'koperasi') ? 'selected' : '' }}>Koperasi</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="sekolah_id"><i class="fas fa-school"></i> Sekolah</label>
                        <select id="sekolah_id" name="sekolah_id">
                            <option value="">Semua Sekolah</option>
                            @foreach($sekolahList as $sekolah)
                                <option value="{{ $sekolah->id }}" {{ (isset($selectedSekolah) && $selectedSekolah == $sekolah->id) ? 'selected' : '' }}>
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
                                <option value="{{ $kelas->id }}" {{ (isset($selectedKelas) && $selectedKelas == $kelas->id) ? 'selected' : '' }}>
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
                    <a href="{{ route('riwayat.index') }}" class="btn-filter btn-reset">
                        <i class="fas fa-sync"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="transactions-list">
            @forelse($transaksi as $groupKey => $tx)
                <div class="transaction-card">
                    <div class="card-header" onclick="toggleDetails(this, 'details-{{ $loop->index }}')">
                        <div class="header-main-info">
                            <div class="student-name">
                                <i class="fas fa-user"></i> {{ $tx['siswa']->nama }}
                            </div>
                            <span class="source-badge">{{ $tx['source_label'] ?? 'Pembayaran Sekolah' }}</span>
                            <div class="transaction-date">
                                <i class="fas fa-calendar-alt"></i> 
                                {{ \Carbon\Carbon::parse($tx['tanggal_bayar'])->format('d M Y, H:i') }}
                            </div>
                            <div class="header-details">
                                <div class="detail-item">
                                    <i class="fas fa-school"></i>
                                    {{ $tx['siswa']->sekolah->nama_sekolah ?? '-' }}
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-users"></i>
                                    @if($tx['siswa']->kelas)
                                        Kelas {{ $tx['siswa']->kelas->tingkat }} - {{ $tx['siswa']->kelas->nama_kelas }}
                                    @else
                                        -
                                    @endif
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-receipt"></i>
                                    {{ count($tx['items']) }} item
                                </div>
                                @if(!empty($tx['transaction_id']))
                                <div class="detail-item">
                                    <i class="fas fa-hashtag"></i>
                                    Transaksi ID: {{ substr($tx['transaction_id'], 0, 8) }}...
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-actions">
                            <div class="total-amount">
                                Rp {{ number_format($tx['total_bayar'], 0, ',', '.') }}
                            </div>
                            <div class="action-buttons">
                                <button class="details-toggle">Detail</button>
                                <a href="{{ ($tx['source_type'] ?? 'sekolah') === 'koperasi' ? route('koperasi.penjualan.kwitansi', $tx['ids']) : route('pembayaran.kwitansi.grup', ['ids' => $tx['ids']]) }}" target="_blank" class="print-btn" title="Cetak Kwitansi">
                                    <i class="fas fa-print"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="details-{{ $loop->index }}">
                        @if(($tx['source_type'] ?? 'sekolah') === 'koperasi')
                        <table class="details-table">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Harga Satuan</th>
                                    <th>Subtotal</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tx['items'] as $item)
                                <tr>
                                    <td>{{ $item->nama_barang }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    <td>{{ $tx['keterangan'] ?? '-' }}</td>
                                </tr>
                                @endforeach
                                <tr style="font-weight: bold; background: rgba(34, 197, 94, 0.1);">
                                    <td colspan="3">TOTAL</td>
                                    <td>Rp {{ number_format($tx['total_bayar'], 0, ',', '.') }}</td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                        @else
                        <table class="details-table">
                            <thead>
                                <tr>
                                    <th>Nama Tagihan</th>
                                    <th>Periode</th>
                                    <th>Nominal Asli</th>
                                    <th>Jumlah Bayar</th>
                                    <th>Diskon</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tx['items'] as $item)
                                <tr>
                                    <td>{{ optional($item->tagihan)->nama_tagihan ?? 'Tagihan Dihapus' }}</td>
                                    <td>{{ optional($item->tagihan)->periode ?? '-' }}</td>
                                    <td>Rp {{ number_format(optional($item->tagihan)->nominal ?? 0, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
                                    <td>
                                        @if(($item->diskon ?? 0) > 0)
                                            <span class="discount-amount has-discount">
                                                Rp {{ number_format($item->diskon, 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="discount-amount no-discount">
                                                -
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                {{-- TAMBAH ROW TOTAL --}}
                                <tr style="font-weight: bold; background: rgba(34, 197, 94, 0.1);">
                                    <td colspan="3">TOTAL</td>
                                    <td>Rp {{ number_format($tx['total_bayar'], 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($tx['total_diskon'], 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-history"></i>
                    <p>Tidak ada riwayat transaksi yang ditemukan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    function toggleDetails(headerElement, key) {
        const content = document.getElementById(key);
        const button = headerElement.querySelector('.details-toggle');
        
        if (content.style.display === 'block') {
            content.style.display = 'none';
            if(button) button.textContent = 'Detail';
        } else {
            content.style.display = 'block';
            if(button) button.textContent = 'Sembunyikan';
        }
    }

    // Auto-hide success alerts
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert-success');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                alert.style.transition = 'all 0.3s ease';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 300);
            }, 5000);
        });
    });
    
    // Dynamic class filtering based on selected school
    document.addEventListener('DOMContentLoaded', function() {
        const sekolahSelect = document.getElementById('sekolah_id');
        const kelasSelect = document.getElementById('kelas_id');
        const filterForm = document.getElementById('filterForm');
        
        if (sekolahSelect && kelasSelect) {
            sekolahSelect.addEventListener('change', function() {
                const sekolahId = this.value;
                
                // Clear current options
                kelasSelect.innerHTML = '<option value="">Memuat data kelas...</option>';
                kelasSelect.disabled = true;
                
                if (sekolahId) {
                    // Fetch classes based on selected school
                    fetch(`/get-kelas-by-sekolah/${sekolahId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Populate class dropdown
                            kelasSelect.innerHTML = '<option value="">Semua Kelas</option>';
                            
                            if (data.length > 0) {
                                data.forEach(kelas => {
                                    const option = document.createElement('option');
                                    option.value = kelas.id;
                                    option.textContent = `Kelas ${kelas.tingkat} - ${kelas.nama_kelas}`;
                                    kelasSelect.appendChild(option);
                                });
                            } else {
                                const option = document.createElement('option');
                                option.value = '';
                                option.textContent = 'Tidak ada kelas';
                                option.disabled = true;
                                kelasSelect.appendChild(option);
                            }
                            
                            kelasSelect.disabled = false;
                        })
                        .catch(error => {
                            console.error('Error fetching classes:', error);
                            kelasSelect.innerHTML = '<option value="">Gagal memuat kelas</option>';
                            kelasSelect.disabled = false;
                        });
                } else {
                    kelasSelect.innerHTML = '<option value="">Semua Kelas</option>';
                    kelasSelect.disabled = false;
                }
            });
        }
        
        // Submit form when pressing Enter in search field
        const searchInput = document.getElementById('search');
        if (searchInput && filterForm) {
            searchInput.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    filterForm.submit();
                }
            });
        }
    });
</script>

@endsection
