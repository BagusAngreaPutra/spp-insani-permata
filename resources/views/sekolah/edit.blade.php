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
        --warning-bg: #fef3c7;
        --warning-text: #92400e;
        --info-bg: #dbeafe;
        --info-text: #1e40af;
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
        align-items: flex-start;
        padding: 2.5rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(34, 197, 94, 0.1);
        margin-bottom: 2rem;
    }

    .page-title h2 {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, #14532d, #166534, #22c55e);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0 0 0.5rem 0;
    }

    .page-title p {
        color: var(--text-secondary);
        font-size: 1rem;
        margin: 0;
    }

    .page-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    /* Current Info Card */
    .current-info-card {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 15px 35px rgba(34, 197, 94, 0.1);
        border: 2px solid rgba(34, 197, 94, 0.1);
    }

    .current-info-header {
        margin-bottom: 1.5rem;
    }

    .current-info-header h4 {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
        font-weight: 600;
        color: var(--text-primary);
        font-size: 1.25rem;
    }

    .current-info-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .info-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    /* Badges */
    .badge {
        display: inline-block;
        padding: 0.375rem 0.75rem;
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .badge-primary {
        background: var(--info-bg);
        color: var(--info-text);
        border: 1px solid #93c5fd;
    }

    .badge-warning {
        background: var(--warning-bg);
        color: var(--warning-text);
        border: 1px solid #fcd34d;
    }

    .badge-success {
        background: var(--success-bg);
        color: var(--success-text);
        border: 1px solid #86efac;
    }

    /* Form Card */
    .form-card {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(22, 163, 74, 0.15);
        border: 2px solid rgba(34, 197, 94, 0.2);
        margin-bottom: 2rem;
    }

    .form-header {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7, #bbf7d0);
        padding: 2rem;
        border-bottom: 1px solid rgba(34, 197, 94, 0.1);
    }

    .form-header h3 {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0 0 0.5rem 0;
        font-weight: 700;
        color: #14532d;
        font-size: 1.5rem;
    }

    .form-header p {
        margin: 0;
        color: var(--text-secondary);
        font-size: 0.95rem;
    }

    /* Form Sections */
    .school-form {
        padding: 0;
    }

    .form-section {
        padding: 2rem;
        border-bottom: 1px solid var(--border-color);
    }

    .form-section:last-of-type {
        border-bottom: none;
    }

    .section-header {
        margin-bottom: 2rem;
    }

    .section-header h4 {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0 0 0.5rem 0;
        font-weight: 600;
        color: var(--text-primary);
        font-size: 1.25rem;
    }

    /* Form Elements */
    .form-row {
        display: flex;
        gap: 1.5rem;
        margin: 0 -0.75rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
        padding: 0 0.75rem;
    }

    .col-md-4 { flex: 0 0 33.333333%; max-width: 33.333333%; }
    .col-md-6 { flex: 0 0 50%; max-width: 50%; }
    .col-md-8 { flex: 0 0 66.666667%; max-width: 66.666667%; }

    @media (max-width: 768px) {
        .form-row { flex-direction: column; }
        .col-md-4, .col-md-6, .col-md-8 { flex: 1; max-width: 100%; }
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
    }

    .form-label.required::after {
        content: ' *';
        color: #ef4444;
        font-weight: 700;
    }

    .form-control {
        width: 100%;
        padding: 0.875rem 1rem;
        border-radius: var(--radius-md);
        border: 2px solid var(--border-color);
        transition: all 0.3s ease;
        font-size: 0.9rem;
        background: white;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    }

    .form-control.is-invalid {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .form-control.changed {
        border-color: #f59e0b;
        background-color: #fffbeb;
    }

    .invalid-feedback {
        display: block;
        color: #ef4444;
        font-size: 0.8125rem;
        font-weight: 500;
        margin-top: 0.5rem;
    }

    .form-text {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    /* Textarea */
    textarea.form-control {
        resize: vertical;
        min-height: 100px;
        line-height: 1.5;
    }

    /* Change Summary */
    .change-summary {
        background: var(--surface-alt);
        border: 2px dashed var(--border-color);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        margin-top: 1rem;
    }

    .change-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        background: white;
        border-radius: var(--radius-sm);
        margin-bottom: 0.75rem;
        border-left: 4px solid #f59e0b;
    }

    .change-item:last-child {
        margin-bottom: 0;
    }

    .change-field {
        font-weight: 600;
        color: var(--text-primary);
    }

    .change-values {
        display: flex;
        gap: 1rem;
        font-size: 0.875rem;
    }

    .old-value {
        color: var(--text-secondary);
        text-decoration: line-through;
    }

    .new-value {
        color: #f59e0b;
        font-weight: 600;
    }

    /* Action Buttons */
    .form-actions {
        padding: 2rem;
        background: var(--surface-alt);
        border-top: 1px solid var(--border-color);
        display: flex;
        gap: 1rem;
        justify-content: flex-start;
        flex-wrap: wrap;
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-md);
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 0.875rem;
        white-space: nowrap;
    }

    .btn-lg {
        padding: 1rem 2rem;
        font-size: 1rem;
    }

    .btn-success {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
    }

    .btn-info {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
        color: white;
    }

    .btn-outline-secondary {
        background: transparent;
        color: var(--secondary-color);
        border: 2px solid var(--secondary-color);
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-outline-secondary:hover {
        background: var(--secondary-color);
        color: white;
    }

    .btn.loading {
        opacity: 0.8;
        cursor: not-allowed;
        transform: none;
    }

    /* Warning Card */
    .warning-card {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border-radius: var(--radius-lg);
        padding: 2rem;
        box-shadow: var(--shadow-md);
        border: 2px solid rgba(239, 68, 68, 0.2);
        border-left: 6px solid #ef4444;
    }

    .warning-card h4 {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0 0 1.5rem 0;
        font-weight: 600;
        color: #dc2626;
    }

    .warning-content p {
        margin-bottom: 1rem;
        color: var(--text-primary);
        line-height: 1.6;
    }

    /* Alerts */
    .alert {
        padding: 1rem 1.5rem;
        border-radius: var(--radius-md);
        margin: 1rem 0;
        border: none;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .alert-warning {
        background: var(--warning-bg);
        color: var(--warning-text);
        border-left: 4px solid #f59e0b;
    }

    .alert-info {
        background: var(--info-bg);
        color: var(--info-text);
        border-left: 4px solid #3b82f6;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
            padding: 1.5rem;
        }

        .page-actions {
            width: 100%;
        }

        .page-actions .btn {
            flex: 1;
            justify-content: center;
        }

        .current-info-content {
            grid-template-columns: 1fr;
        }

        .form-section {
            padding: 1.5rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .form-actions .btn {
            width: 100%;
            justify-content: center;
        }

        .change-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }

    /* Animation */
    .current-info-card {
        animation: slideUp 0.4s ease-out;
    }

    .form-card {
        animation: slideUp 0.5s ease-out 0.1s both;
    }

    .warning-card {
        animation: slideUp 0.5s ease-out 0.2s both;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Focus states */
    .btn:focus,
    .form-control:focus {
        outline: 2px solid var(--primary-color);
        outline-offset: 2px;
    }

    /* Hover effects */
    .current-info-card:hover,
    .form-card:hover {
        box-shadow: 0 30px 60px rgba(22, 163, 74, 0.2);
    }

    .warning-card:hover {
        box-shadow: 0 15px 35px rgba(239, 68, 68, 0.15);
    }
</style>

<div class="main-content">
    @include('layouts.header')
    <div class="content-area">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h2><i class="fas fa-edit"></i> Edit Sekolah</h2>
                <p>Perbarui informasi sekolah: <strong>{{ $sekolah->nama_sekolah }}</strong></p>
            </div>
            <div class="page-actions">
                @if(Route::has('sekolah.show'))
                    <a href="{{ route('sekolah.show', $sekolah->id) }}" class="btn btn-info">
                        <i class="fas fa-eye"></i> Detail
                    </a>
                @endif
                <a href="{{ route('sekolah.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Current Info Card -->
        <div class="current-info-card">
            <div class="current-info-header">
                <h4><i class="fas fa-info-circle"></i> Informasi Saat Ini</h4>
            </div>
            <div class="current-info-content">
                <div class="info-item">
                    <span class="info-label">Nama Sekolah</span>
                    <span class="info-value">{{ $sekolah->nama_sekolah }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Kode Sekolah</span>
                    <span class="info-value">
                        @if($sekolah->kode_sekolah)
                            <span class="badge badge-primary">{{ $sekolah->kode_sekolah }}</span>
                        @else
                            <span class="badge badge-warning">Belum Ada</span>
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Total Siswa</span>
                    <span class="info-value">
                        <span class="badge badge-success">{{ $sekolah->siswa->count() }} siswa</span>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Terakhir Diupdate</span>
                    <span class="info-value">{{ $sekolah->updated_at->format('d M Y, H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <div class="form-header">
                <h3><i class="fas fa-school"></i> Form Edit Sekolah</h3>
                <p>Perbarui informasi sekolah sesuai kebutuhan</p>
            </div>

            <form action="{{ route('sekolah.update', $sekolah->id) }}" method="POST" class="school-form" id="editForm">
                @csrf
                @method('PUT')

                <div class="form-section">
                    <div class="section-header">
                        <h4><i class="fas fa-info-circle"></i> Data Umum</h4>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label for="nama_sekolah" class="form-label required">
                                <i class="fas fa-school"></i> Nama Sekolah
                            </label>
                            <input type="text" 
                                   name="nama_sekolah" 
                                   id="nama_sekolah" 
                                   class="form-control @error('nama_sekolah') is-invalid @enderror" 
                                   value="{{ old('nama_sekolah', $sekolah->nama_sekolah) }}" 
                                   placeholder="Masukkan nama sekolah lengkap"
                                   data-original="{{ $sekolah->nama_sekolah }}"
                                   required>
                            @error('nama_sekolah')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text">
                                <i class="fas fa-info-circle"></i> Nama lengkap sekolah
                            </small>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="kode_sekolah" class="form-label required">
                                <i class="fas fa-code"></i> Kode Sekolah
                            </label>
                            <input type="text" 
                                   name="kode_sekolah" 
                                   id="kode_sekolah" 
                                   class="form-control @error('kode_sekolah') is-invalid @enderror" 
                                   value="{{ old('kode_sekolah', $sekolah->kode_sekolah) }}" 
                                   maxlength="10"
                                   placeholder="SDIT, SMPIT, dll"
                                   style="text-transform: uppercase;"
                                   data-original="{{ $sekolah->kode_sekolah }}"
                                   required>
                            @error('kode_sekolah')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                                </div>
                            @enderror

                            @if(!$sekolah->kode_sekolah)
                                <div class="alert alert-warning mt-2">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>Perhatian:</strong> Kode sekolah diperlukan untuk generate nomor kwitansi.
                                </div>
                            @endif

                            <small class="form-text">
                                <i class="fas fa-info-circle"></i> 
                                Format kwitansi: <code>000001/KODE_INI/2025</code>
                            </small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="alamat" class="form-label required">
                            <i class="fas fa-map-marker-alt"></i> Alamat Lengkap
                        </label>
                        <textarea name="alamat" 
                                  id="alamat" 
                                  class="form-control @error('alamat') is-invalid @enderror" 
                                  rows="3"
                                  placeholder="Masukkan alamat lengkap sekolah"
                                  data-original="{{ $sekolah->alamat }}"
                                  required>{{ old('alamat', $sekolah->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                            </div>
                        @enderror
                        <small class="form-text">
                            <i class="fas fa-info-circle"></i> Alamat selengkap mungkin
                        </small>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="durasi_pendidikan" class="form-label">
                            <i class="fas fa-calendar-alt"></i> Durasi Pendidikan (Tahun)
                        </label>
                        <input type="number" 
                               name="durasi_pendidikan" 
                               id="durasi_pendidikan" 
                               class="form-control @error('durasi_pendidikan') is-invalid @enderror" 
                               value="{{ old('durasi_pendidikan', $sekolah->durasi_pendidikan) }}"
                               min="1"
                               max="12"
                               placeholder="6"
                               data-original="{{ $sekolah->durasi_pendidikan }}">
                        @error('durasi_pendidikan')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                            </div>
                        @enderror
                        <small class="form-text">
                            <i class="fas fa-info-circle"></i> Jumlah tahun untuk menyelesaikan pendidikan di sekolah ini
                        </small>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-header">
                        <h4><i class="fas fa-address-book"></i> Kontak & Komunikasi</h4>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="telepon" class="form-label">
                                <i class="fas fa-phone"></i> Telepon
                            </label>
                            <input type="text" 
                                   name="telepon" 
                                   id="telepon" 
                                   class="form-control @error('telepon') is-invalid @enderror" 
                                   value="{{ old('telepon', $sekolah->telepon) }}"
                                   placeholder="021-12345678 atau 081234567890"
                                   data-original="{{ $sekolah->telepon }}">
                            @error('telepon')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text">
                                <i class="fas fa-info-circle"></i> Nomor telepon sekolah
                            </small>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $sekolah->email) }}"
                                   placeholder="sekolah@domain.com"
                                   data-original="{{ $sekolah->email }}">
                            @error('email')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text">
                                <i class="fas fa-info-circle"></i> Email resmi sekolah
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Change Summary -->
                <div class="form-section">
                    <div class="section-header">
                        <h4><i class="fas fa-history"></i> Ringkasan Perubahan</h4>
                    </div>

                    <div class="change-summary" id="changeSummary">
                        <p style="text-align: center; color: var(--text-secondary); margin: 0;">
                            <i class="fas fa-info-circle"></i> Mulai edit untuk melihat perubahan...
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <button type="reset" class="btn btn-secondary btn-lg" id="resetBtn">
                        <i class="fas fa-undo"></i> Reset Form
                    </button>
                    <a href="{{ route('sekolah.index') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- Warning Card -->
        @if($sekolah->siswa->count() > 0)
        <div class="warning-card">
            <h4><i class="fas fa-exclamation-triangle"></i> Perhatian</h4>
            <div class="warning-content">
                <p>
                    <strong>Sekolah ini memiliki {{ $sekolah->siswa->count() }} siswa aktif.</strong> 
                    Perubahan kode sekolah akan mempengaruhi format nomor kwitansi untuk pembayaran selanjutnya.
                </p>
                @if(!$sekolah->kode_sekolah)
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Menambahkan kode sekolah akan mengaktifkan fitur auto-generate nomor kwitansi.
                    </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const form = document.getElementById('editForm');
    const submitBtn = document.getElementById('submitBtn');
    const resetBtn = document.getElementById('resetBtn');
    const changeSummary = document.getElementById('changeSummary');

    const formFields = [
        'nama_sekolah',
        'kode_sekolah',
        'alamat',
        'telepon',
        'email'
    ];

    const fieldLabels = {
        'nama_sekolah': 'Nama Sekolah',
        'kode_sekolah': 'Kode Sekolah',
        'alamat': 'Alamat',
        'telepon': 'Telepon',
        'email': 'Email'
    };

    let originalValues = {};
    let hasChanges = false;

    // Store original values
    formFields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (field) {
            originalValues[fieldName] = field.dataset.original || '';
        }
    });

    // Auto uppercase for kode sekolah
    const kodeSekolahInput = document.getElementById('kode_sekolah');
    if (kodeSekolahInput) {
        kodeSekolahInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
            trackChanges();
        });
    }

    // Track changes
    function trackChanges() {
        const changes = [];
        hasChanges = false;

        formFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (field) {
                const currentValue = field.value.trim();
                const originalValue = originalValues[fieldName] || '';

                if (currentValue !== originalValue) {
                    hasChanges = true;
                    field.classList.add('changed');

                    changes.push({
                        field: fieldLabels[fieldName],
                        oldValue: originalValue || '(kosong)',
                        newValue: currentValue || '(kosong)'
                    });
                } else {
                    field.classList.remove('changed');
                }
            }
        });

        updateChangeSummary(changes);
        updateSubmitButton();
    }

    // Update change summary
    function updateChangeSummary(changes) {
        if (changes.length === 0) {
            changeSummary.innerHTML = `
                <p style="text-align: center; color: var(--text-secondary); margin: 0;">
                    <i class="fas fa-info-circle"></i> Tidak ada perubahan yang terdeteksi
                </p>
            `;
        } else {
            let summaryHTML = '';
            changes.forEach(change => {
                summaryHTML += `
                    <div class="change-item">
                        <span class="change-field">${change.field}</span>
                        <div class="change-values">
                            <span class="old-value">${change.oldValue}</span>
                            <i class="fas fa-arrow-right" style="color: var(--text-secondary);"></i>
                            <span class="new-value">${change.newValue}</span>
                        </div>
                    </div>
                `;
            });
            changeSummary.innerHTML = summaryHTML;
        }
    }

    // Update submit button state
    function updateSubmitButton() {
        if (hasChanges) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan';
            submitBtn.classList.remove('btn-secondary');
            submitBtn.classList.add('btn-success');
        } else {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-check"></i> Tidak Ada Perubahan';
            submitBtn.classList.remove('btn-success');
            submitBtn.classList.add('btn-secondary');
        }
    }

    // Add event listeners to track changes
    formFields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (field) {
            field.addEventListener('input', trackChanges);
            field.addEventListener('blur', trackChanges);
        }
    });

    // Form validation
    function validateForm() {
        let isValid = true;
        const requiredFields = ['nama_sekolah', 'kode_sekolah', 'alamat'];

        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (field && !field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else if (field) {
                field.classList.remove('is-invalid');
            }
        });

        // Email validation
        const emailField = document.getElementById('email');
        if (emailField && emailField.value && !isValidEmail(emailField.value)) {
            emailField.classList.add('is-invalid');
            isValid = false;
        } else if (emailField) {
            emailField.classList.remove('is-invalid');
        }

        return isValid;
    }

    // Email validation helper
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Form submission handling
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            const firstInvalidField = form.querySelector('.is-invalid');
            if (firstInvalidField) {
                firstInvalidField.focus();
                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return;
        }

        if (!hasChanges) {
            e.preventDefault();
            alert('Tidak ada perubahan yang perlu disimpan.');
            return;
        }

        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    });

    // Reset form handling
    resetBtn.addEventListener('click', function(e) {
        e.preventDefault();

        if (confirm('Apakah Anda yakin ingin mereset semua perubahan?')) {
            formFields.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (field) {
                    field.value = originalValues[fieldName] || '';
                    field.classList.remove('changed', 'is-invalid');
                }
            });

            setTimeout(trackChanges, 100);
        }
    });

    // Real-time validation
    formFields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (field) {
            field.addEventListener('blur', validateForm);
            field.addEventListener('input', function() {
                if (this.classList.contains('is-invalid') && this.value.trim()) {
                    this.classList.remove('is-invalid');
                }
            });
        }
    });

    // Initialize
    trackChanges();

    // Confirmation dialog for navigation
    window.addEventListener('beforeunload', function(e) {
        if (hasChanges) {
            e.preventDefault();
            e.returnValue = '';
            return 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman?';
        }
    });

    // Remove beforeunload listener when form is submitted
    form.addEventListener('submit', function() {
        window.removeEventListener('beforeunload', arguments.callee);
    });

    // Loading state timeout protection
    setTimeout(function() {
        if (submitBtn.classList.contains('loading')) {
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;
            updateSubmitButton();
        }
    }, 10000); // 10 seconds timeout
});

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert:not(.alert-warning):not(.alert-info)');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(function() {
                alert.remove();
            }, 300);
        }, 5000);
    });
});
</script>
@endsection
