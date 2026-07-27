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

    /* Main Content Layout */
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
        background: linear-gradient(135deg, #14532d, #166534, #22c55e);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
    }

    /* Filter Card */
    .filter-card {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 15px 35px rgba(34, 197, 94, 0.1);
        border: 2px solid rgba(34, 197, 94, 0.1);
    }

    .filter-form .filter-row {
        display: flex;
        gap: 1.5rem;
        align-items: end;
        flex-wrap: wrap;
    }

    .filter-group {
        flex: 1;
        min-width: 250px;
    }

    .filter-label {
        display: block;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .filter-actions {
        display: flex;
        gap: 0.75rem;
    }

    /* Statistics Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 15px 35px rgba(34, 197, 94, 0.1);
        border: 2px solid rgba(34, 197, 94, 0.1);
        display: flex;
        align-items: center;
        gap: 1.5rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(34, 197, 94, 0.15);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: white;
    }

    .stat-primary .stat-icon { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
    .stat-success .stat-icon { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-info .stat-icon { background: linear-gradient(135deg, #06b6d4, #0891b2); }
    .stat-warning .stat-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        font-weight: 600;
    }

    /* Table Card */
    .table-card {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(22, 163, 74, 0.15);
        border: 2px solid rgba(34, 197, 94, 0.2);
    }

    .table-header {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7, #bbf7d0);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(34, 197, 94, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-header h3 {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
        font-weight: 600;
        color: #14532d;
    }

    .table-actions {
        display: flex;
        gap: 0.5rem;
    }

    /* Data Table */
    .table-responsive {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th,
    .data-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(34, 197, 94, 0.1);
    }

    .data-table th {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        font-weight: 600;
        color: #14532d;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .data-table tbody tr {
        transition: background-color 0.2s ease;
    }

    .data-table tbody tr:hover {
        background: rgba(34, 197, 94, 0.05);
    }

    /* Table Content Styling */
    .school-info .school-name {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .address-info {
        color: var(--text-secondary);
        font-size: 0.875rem;
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .email-info {
        color: var(--text-secondary);
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
        margin-top: 0.25rem;
    }

    .phone-number {
        color: var(--text-secondary);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .student-count {
        text-align: center;
    }

    .count-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        display: block;
    }

    .student-count small {
        font-size: 0.75rem;
        color: var(--text-secondary);
        text-transform: uppercase;
    }

    /* Badges */
    .badge {
        display: inline-block;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .badge-code {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #93c5fd;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fcd34d;
    }

    .badge-primary {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #93c5fd;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
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
        font-size: 0.875rem;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.8125rem;
    }

    .btn-lg {
        padding: 1rem 2rem;
        font-size: 1rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
    }

    .btn-success {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
    }

    .btn-info {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
        color: white;
    }

    .btn-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .btn-outline-primary {
        background: transparent;
        color: #3b82f6;
        border: 2px solid #3b82f6;
    }

    .btn-outline-secondary {
        background: transparent;
        color: #6b7280;
        border: 2px solid #6b7280;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .btn-outline-primary:hover {
        background: #3b82f6;
        color: white;
    }

    .btn-outline-secondary:hover {
        background: #6b7280;
        color: white;
    }

    /* Form Controls */
    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
        font-size: 0.875rem;
        background: white;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 2rem;
        border-top: 1px solid rgba(34, 197, 94, 0.1);
        background: rgba(248, 250, 252, 0.5);
    }

    .pagination-info {
        font-size: 0.875rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .pagination-links .pagination {
        margin: 0;
    }

    .pagination-links .page-link {
        border-radius: 8px;
        margin: 0 2px;
        border: none;
        color: var(--primary-color);
        font-weight: 500;
    }

    .pagination-links .page-item.active .page-link {
        background: var(--primary-color);
        border-color: var(--primary-color);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-secondary);
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }

    .empty-state h3 {
        margin: 0 0 1rem 0;
        color: var(--text-primary);
        font-weight: 600;
    }

    .empty-state p {
        margin: 0 0 2rem 0;
        font-size: 1.1rem;
    }

    /* Modal */
    .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 25px 50px rgba(0,0,0,0.25);
    }

    .modal-header {
        border-bottom: 1px solid var(--border-color);
        padding: 1.5rem 2rem;
    }

    .modal-body {
        padding: 1.5rem 2rem;
    }

    .modal-footer {
        border-top: 1px solid var(--border-color);
        padding: 1.5rem 2rem;
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin: 1rem 0;
        border: none;
    }

    .alert-warning {
        background: var(--error-bg);
        color: var(--error-text);
    }

    /* Responsive */
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

        .filter-form .filter-row {
            flex-direction: column;
            gap: 1rem;
        }

        .filter-group {
            min-width: auto;
        }

        .filter-actions {
            width: 100%;
            justify-content: stretch;
        }

        .filter-actions .btn {
            flex: 1;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .table-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .data-table {
            font-size: 0.8125rem;
        }

        .data-table th,
        .data-table td {
            padding: 0.75rem 0.5rem;
        }

        .pagination-wrapper {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .action-buttons {
            flex-direction: column;
        }

        .action-buttons .btn {
            width: 100%;
        }
    }

    /* Hover effects */
    .table-card:hover {
        box-shadow: 0 30px 60px rgba(22, 163, 74, 0.2);
    }

    .filter-card:hover {
        box-shadow: 0 20px 40px rgba(34, 197, 94, 0.15);
    }

    /* Loading states */
    .btn.loading {
        opacity: 0.8;
        cursor: not-allowed;
        transform: none;
    }
</style>

<div class="main-content">
    @include('layouts.header')
    <div class="content-area">
        @include('partials.admin-page-context', [
            'section' => 'Master Data',
            'current' => 'Sekolah',
            'title' => 'Sekolah adalah data pertama yang perlu disiapkan.',
            'description' => 'Setiap kelas, siswa, pembayaran, dan laporan akan terhubung ke sekolah. Mulai dari sini jika sistem masih kosong.',
            'steps' => ['Sekolah', 'Tahun Ajaran', 'Kelas', 'Siswa']
        ])

        <!-- Page Header -->
        <div class="page-header">
            <h2 class="page-title">
                <i class="fas fa-school"></i> Sekolah
            </h2>
            <a href="{{ route('sekolah.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Sekolah
            </a>
        </div>

        <!-- Search Card -->
        <div class="filter-card">
            <form method="GET" action="{{ route('sekolah.index') }}" class="filter-form">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-search"></i> Pencarian
                        </label>
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               value="{{ request('search') }}"
                               placeholder="Cari nama sekolah atau kode...">
                    </div>
                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Cari
                        </button>
                        <a href="{{ route('sekolah.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card stat-primary">
                <div class="stat-icon">
                    <i class="fas fa-school"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $totalSekolah }}</div>
                    <div class="stat-label">Total Sekolah</div>
                </div>
            </div>
            <div class="stat-card stat-info">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $totalSiswa }}</div>
                    <div class="stat-label">Total Siswa</div>
                </div>
            </div>
            <div class="stat-card stat-warning">
                <div class="stat-icon">
                    <i class="fas fa-code"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $sekolahDenganKode }}</div>
                    <div class="stat-label">Dengan Kode</div>
                </div>
            </div>
        </div>

        <!-- Main Table Card -->
        <div class="table-card">
            <div class="table-header">
                <h3><i class="fas fa-list"></i> Daftar Sekolah</h3>
                <div class="table-actions">
                    <button class="btn btn-sm btn-outline-secondary" onclick="window.location.reload()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>

            @if($sekolah->count() > 0)
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="25%">Nama Sekolah</th>
                                <th width="12%">Kode Sekolah</th>
                                <th width="30%">Alamat</th>
                                <th width="15%">Kontak</th>
                                <th width="8%">Total Siswa</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sekolah as $index => $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="school-info">
                                            <div class="school-name">{{ $item->nama_sekolah }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->kode_sekolah)
                                            <span class="badge badge-code">{{ $item->kode_sekolah }}</span>
                                        @else
                                            <span class="badge badge-warning">Belum Ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="address-info">
                                            <i class="fas fa-map-marker-alt"></i> 
                                            <span>{{ Str::limit($item->alamat, 60) }}</span>
                                        </div>
                                        @if($item->email)
                                            <div class="email-info">
                                                <i class="fas fa-envelope"></i> {{ $item->email }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->telepon)
                                            <span class="phone-number">
                                                <i class="fas fa-phone"></i> {{ $item->telepon }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="student-count">
                                            <span class="count-number">{{ $item->siswa->count() }}</span>
                                            <small>siswa</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('sekolah.edit', $item->id) }}" 
                                               class="btn btn-sm btn-warning" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger" 
                                                    onclick="confirmDelete({{ $item->id }}, '{{ addslashes($item->nama_sekolah) }}')"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(method_exists($sekolah, 'links'))
                    <div class="pagination-wrapper">
                        <div class="pagination-info">
                            Menampilkan {{ $sekolah->firstItem() }} - {{ $sekolah->lastItem() }} 
                            dari {{ $sekolah->total() }} sekolah
                        </div>
                        <div class="pagination-links">
                            {{ $sekolah->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="pagination-wrapper">
                        <div class="pagination-info">
                            Menampilkan {{ $sekolah->count() }} sekolah
                        </div>
                    </div>
                @endif
            @else
                @include('partials.admin-empty-state', [
                    'icon' => 'fas fa-school',
                    'title' => 'Belum Ada Data Sekolah',
                    'message' => 'Tambahkan sekolah pertama lebih dulu. Setelah itu admin bisa membuat tahun ajaran, kelas, dan data siswa.',
                    'actionRoute' => route('sekolah.create'),
                    'actionText' => 'Tambah Sekolah Pertama'
                ])
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle text-warning"></i>
                        Konfirmasi Hapus
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus sekolah <strong id="schoolName"></strong>?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Perhatian:</strong> Menghapus sekolah akan menghapus semua data terkait termasuk siswa dan tagihan.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <form method="POST" id="deleteForm" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Hapus Sekolah
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    document.getElementById('schoolName').textContent = name;
    document.getElementById('deleteForm').action = '{{ url("sekolah") }}/' + id;
    $('#deleteModal').modal('show');
}

function exportData() {
    // Implementasi export data
    alert('Fitur export akan segera tersedia!');
}

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        if (!alert.classList.contains('alert-warning')) {
            setTimeout(function() {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            }, 5000);
        }
    });
});

// Loading state for buttons
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';

                // Reset after 5 seconds if still loading
                setTimeout(function() {
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }, 5000);
            }
        });
    });
});
</script>
@endsection
