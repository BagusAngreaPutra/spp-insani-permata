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

    /* Preview Card */
    .preview-card {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 2px dashed var(--border-color);
        border-radius: var(--radius-lg);
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .preview-header {
        margin-bottom: 1.5rem;
    }

    .preview-header h5 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.5rem 0;
    }

    .preview-kode {
        display: inline-block;
        padding: 0.375rem 1rem;
        border-radius: var(--radius-sm);
        font-size: 0.8125rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-primary {
        background: var(--info-bg);
        color: var(--info-text);
        border: 1px solid #93c5fd;
    }

    .preview-content {
        text-align: left;
    }

    .preview-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 1rem;
        padding: 0.75rem;
        background: white;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-color);
    }

    .preview-item:last-child {
        margin-bottom: 0;
    }

    .preview-item i {
        color: var(--primary-color);
        width: 16px;
        flex-shrink: 0;
        margin-top: 2px;
    }

    /* Action Buttons */
    .form-actions {
        padding: 2rem;
        background: var(--surface-alt);
        border-top: 1px solid var(--border-color);
        display: flex;
        gap: 1rem;
        justify-content: flex-start;
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

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6b7280, #4b5563);
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

    /* Help Card */
    .help-card {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border-radius: var(--radius-lg);
        padding: 2rem;
        box-shadow: var(--shadow-md);
        border: 2px solid rgba(34, 197, 94, 0.1);
    }

    .help-card h4 {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0 0 1.5rem 0;
        font-weight: 600;
        color: var(--text-primary);
    }

    .help-content {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .help-item {
        padding: 1rem;
        background: var(--surface-alt);
        border-radius: var(--radius-sm);
        border-left: 4px solid var(--primary-color);
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .help-item strong {
        color: var(--text-primary);
    }

    .help-item code {
        background: #e5e7eb;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-family: 'Courier New', monospace;
        font-size: 0.8125rem;
        color: #374151;
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
    }

    .alert-info {
        background: var(--info-bg);
        color: var(--info-text);
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
    }

    /* Animation */
    .form-card {
        animation: slideUp 0.5s ease-out;
    }

    .help-card {
        animation: slideUp 0.5s ease-out 0.1s both;
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
    .form-card:hover {
        box-shadow: 0 30px 60px rgba(22, 163, 74, 0.2);
    }

    .help-card:hover {
        box-shadow: 0 15px 35px rgba(34, 197, 94, 0.15);
    }
</style>

<div class="main-content">
    @include('layouts.header')
    <div class="content-area">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h2><i class="fas fa-plus-circle"></i> Tambah Sekolah Baru</h2>
                <p>Tambahkan informasi sekolah baru ke dalam sistem</p>
            </div>
            <div class="page-actions">
                <a href="{{ route('sekolah.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <div class="form-header">
                <h3><i class="fas fa-school"></i> Informasi Sekolah</h3>
                <p>Lengkapi semua field yang diperlukan dengan benar</p>
            </div>

            <form action="{{ route('sekolah.store') }}" method="POST" class="school-form" id="schoolForm">
                @csrf

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
                                   value="{{ old('nama_sekolah') }}" 
                                   placeholder="Masukkan nama sekolah lengkap"
                                   required>
                            @error('nama_sekolah')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text">
                                <i class="fas fa-info-circle"></i> Contoh: SD Islam Terpadu Al-Hikmah
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
                                   value="{{ old('kode_sekolah') }}" 
                                   maxlength="10"
                                   placeholder="SDIT, SMPIT, dll"
                                   style="text-transform: uppercase;"
                                   required>
                            @error('kode_sekolah')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                                </div>
                            @enderror
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
                                  required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                            </div>
                        @enderror
                        <small class="form-text">
                            <i class="fas fa-info-circle"></i> Masukkan alamat selengkap mungkin
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
                               value="{{ old('durasi_pendidikan') }}"
                               min="1"
                               max="12"
                               placeholder="6">
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
                                   value="{{ old('telepon') }}"
                                   placeholder="021-12345678 atau 081234567890">
                            @error('telepon')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text">
                                <i class="fas fa-info-circle"></i> Format: 021-12345678 atau 081234567890
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
                                   value="{{ old('email') }}"
                                   placeholder="sekolah@domain.com">
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

                <!-- Preview Card -->
                <div class="form-section">
                    <div class="section-header">
                        <h4><i class="fas fa-eye"></i> Preview</h4>
                    </div>

                    <div class="preview-card" id="previewCard">
                        <div class="preview-header">
                            <h5 id="preview-nama">-</h5>
                            <span class="preview-kode badge badge-primary" id="preview-kode">-</span>
                        </div>
                        <div class="preview-content">
                            <div class="preview-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span id="preview-alamat">-</span>
                            </div>
                            <div class="preview-item">
                                <i class="fas fa-phone"></i>
                                <span id="preview-telepon">-</span>
                            </div>
                            <div class="preview-item">
                                <i class="fas fa-envelope"></i>
                                <span id="preview-email">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                        <i class="fas fa-save"></i> Simpan Sekolah
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

        <!-- Help Card -->
        <div class="help-card">
            <h4><i class="fas fa-question-circle"></i> Bantuan</h4>
            <div class="help-content">
                <div class="help-item">
                    <strong>Kode Sekolah:</strong> Digunakan untuk format nomor kwitansi. 
                    Maksimal 10 karakter, otomatis akan diubah menjadi uppercase (huruf kapital).
                </div>
                <div class="help-item">
                    <strong>Format Kwitansi:</strong> <code>000001/KODE/2025</code> - 
                    Nomor urut / Kode Sekolah / Tahun
                </div>
                <div class="help-item">
                    <strong>Field Wajib:</strong> Nama sekolah, kode sekolah, dan alamat 
                    wajib diisi untuk melanjutkan. Field lainnya bersifat opsional.
                </div>
                <div class="help-item">
                    <strong>Preview:</strong> Lihat pratinjau data sekolah secara real-time 
                    saat Anda mengetik di form di atas.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const namaSekolahInput = document.getElementById('nama_sekolah');
    const kodeSekolahInput = document.getElementById('kode_sekolah');
    const alamatInput = document.getElementById('alamat');
    const teleponInput = document.getElementById('telepon');
    const emailInput = document.getElementById('email');
    const form = document.getElementById('schoolForm');
    const submitBtn = document.getElementById('submitBtn');
    const resetBtn = document.getElementById('resetBtn');

    // Preview elements
    const previewNama = document.getElementById('preview-nama');
    const previewKode = document.getElementById('preview-kode');
    const previewAlamat = document.getElementById('preview-alamat');
    const previewTelepon = document.getElementById('preview-telepon');
    const previewEmail = document.getElementById('preview-email');

    // Real-time preview update
    function updatePreview() {
        previewNama.textContent = namaSekolahInput.value || '-';
        previewKode.textContent = kodeSekolahInput.value || '-';
        previewAlamat.textContent = alamatInput.value || '-';
        previewTelepon.textContent = teleponInput.value || '-';
        previewEmail.textContent = emailInput.value || '-';
    }

    // Auto uppercase for kode sekolah
    kodeSekolahInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
        updatePreview();
    });

    // Event listeners for preview
    namaSekolahInput.addEventListener('input', updatePreview);
    alamatInput.addEventListener('input', updatePreview);
    teleponInput.addEventListener('input', updatePreview);
    emailInput.addEventListener('input', updatePreview);

    // Form submission handling
    form.addEventListener('submit', function(e) {
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    });

    // Reset form handling
    resetBtn.addEventListener('click', function() {
        setTimeout(updatePreview, 100); // Delay to ensure form is reset
    });

    // Form validation
    function validateForm() {
        let isValid = true;
        const requiredFields = [namaSekolahInput, kodeSekolahInput, alamatInput];

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Email validation
        if (emailInput.value && !isValidEmail(emailInput.value)) {
            emailInput.classList.add('is-invalid');
            isValid = false;
        } else {
            emailInput.classList.remove('is-invalid');
        }

        return isValid;
    }

    // Email validation helper
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Real-time validation
    [namaSekolahInput, kodeSekolahInput, alamatInput, emailInput].forEach(field => {
        field.addEventListener('blur', validateForm);
        field.addEventListener('input', function() {
            if (this.classList.contains('is-invalid') && this.value.trim()) {
                this.classList.remove('is-invalid');
            }
        });
    });

    // Kode sekolah suggestions
    const kodeSuggestions = ['SDIT', 'SMPIT', 'SMAIT', 'SMPN', 'SMAN', 'SDN', 'TK'];

    kodeSekolahInput.addEventListener('focus', function() {
        if (!this.value) {
            // You can implement autocomplete here if needed
        }
    });

    // Auto-focus on first input
    namaSekolahInput.focus();

    // Initialize preview
    updatePreview();

    // Prevent form submission if validation fails
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Sekolah';

            // Show error message
            const firstInvalidField = form.querySelector('.is-invalid');
            if (firstInvalidField) {
                firstInvalidField.focus();
                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    // Success animation for preview
    function animatePreview() {
        const previewCard = document.getElementById('previewCard');
        previewCard.style.transform = 'scale(1.02)';
        previewCard.style.borderColor = 'var(--primary-color)';

        setTimeout(() => {
            previewCard.style.transform = 'scale(1)';
            previewCard.style.borderColor = 'var(--border-color)';
        }, 200);
    }

    // Animate preview on significant changes
    [namaSekolahInput, kodeSekolahInput].forEach(field => {
        field.addEventListener('input', function() {
            if (this.value.length > 3) {
                animatePreview();
            }
        });
    });

    // Loading state timeout protection
    setTimeout(function() {
        if (submitBtn.classList.contains('loading')) {
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Sekolah';
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
