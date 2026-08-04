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
            top: 0;
            right: auto;
        }
    }
    .btn-default {
        padding: 0.85rem 1.2rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
        margin-right: 1rem;
    }
    .btn-default.active {
        background: linear-gradient(135deg,#22c55e,#16a34a);
        color: white;
        box-shadow: 0 4px 12px rgba(34,197,94,0.3);
        transform: scale(1.02);
    }
    .btn-default.inactive {
        background: #d1d5db;
        color: #374151;
        box-shadow: none;
        transform: scale(1);
    }
    .nominal-input:disabled {
        background-color: #f3f4f6 !important;
        opacity: 0.6;
        cursor: not-allowed;
        border-color: #d1d5db;
    }
    .nominal-input:enabled {
        background-color: white;
        opacity: 1;
        cursor: text;
        border-color: #22c55e;
    }
    .content-area {
        padding: 2rem;
    }
    .page-header {
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        padding: 2rem;
        margin-bottom: 2rem;
        text-align: center;
    }
    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #16a34a, #15803d);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }
    .cards-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }
    .form-card {
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        padding: 2rem;
        transition: all 0.3s ease;
    }
    .form-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    .form-card h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #16a34a;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f0fdf4;
    }
    .form-card h3 i {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
        padding: 0.5rem;
        border-radius: 12px;
        font-size: 1rem;
    }
    .form-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.25rem;
    }
    .form-grid.two-columns {
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    .form-grid.three-columns {
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
    .form-group {
        display: flex;
        flex-direction: column;
    }
    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }
    .form-control {
        width: 100%;
        padding: 0.85rem 1rem;
        border-radius: 12px;
        border: 1px solid #d1d5db;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: white;
    }
    .form-control:focus {
        border-color: #22c55e;
        outline: none;
        box-shadow: 0 0 0 3px rgba(34,197,94,0.2);
        transform: translateY(-1px);
    }
    .form-control.error {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239,68,68,0.2);
    }
    .alert-danger {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }
    .alert-warning {
        background: #fef3c7;
        color: #d97706;
        border: 1px solid #fde68a;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: none;
    }
    .alert-warning.show {
        display: block;
    }
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 2.5rem;
        border-radius: 16px;
        font-weight: 600;
        text-decoration: none;
        transition: all 300ms ease;
        border: none;
        cursor: pointer;
        font-size: 1rem;
    }
    .btn-primary {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
        box-shadow: 0 8px 25px rgba(34,197,94,0.3);
    }
    .btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(34,197,94,0.4);
    }
    .btn-primary:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
    }
    .btn-secondary {
        background: linear-gradient(135deg, #9ca3af, #6b7280);
        color: white;
        box-shadow: 0 4px 12px rgba(107,114,128,0.3);
        margin-left: 1rem;
    }
    .btn-secondary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(107,114,128,0.4);
    }
    .spp-container {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .validation-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: none;
    }
    .validation-message.show {
        display: block;
    }
    .action-buttons {
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        padding: 2rem;
        text-align: center;
    }
    .full-width-card {
        grid-column: 1 / -1;
    }
    @media (max-width: 768px) {
        .cards-container {
            grid-template-columns: 1fr;
        }
        .form-grid.two-columns,
        .form-grid.three-columns {
            grid-template-columns: 1fr;
        }
        .spp-container {
            flex-direction: column;
            align-items: stretch;
        }
        .btn-default {
            margin-right: 0;
            margin-bottom: 0.5rem;
        }
        .btn-secondary {
            margin-left: 0;
            margin-top: 1rem;
        }
    }
</style>
@endpush

<div class="main-content">
    @include('layouts.header')

    <div class="content-area">
        {{-- Page Header --}}
        <div class="page-header">
            <h1>Tambah siswa</h1>
        </div>

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="alert-danger">
                <strong><i class="fas fa-exclamation-circle"></i> Terdapat kesalahan:</strong>
                <ul style="margin: 0.5rem 0 0 1rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('siswa.store') }}" method="POST" id="student-form">
            @csrf

            <div class="cards-container">
                {{-- Card 1: Data Sekolah & Kelas --}}
                <div class="form-card">
                    <h3>Sekolah dan kelas</h3>
                    <div class="form-grid three-columns">
                        <!-- Sekolah -->
                        <div class="form-group">
                            <label class="form-label">Sekolah *</label>
                            <select name="id_sekolah" id="id_sekolah" class="form-control" required>
                                <option value="">-- Pilih Sekolah --</option>
                                @foreach($sekolah as $sk)
                                    <option value="{{ $sk->id }}" {{ (old('id_sekolah') ?? ($selectedSekolah ?? null)) == $sk->id ? 'selected' : '' }}>
                                        {{ $sk->nama_sekolah }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Kelas -->
                        <div class="form-group">
                            <label class="form-label">Kelas *</label>
                            <select name="kelas_id" id="kelas_id" class="form-control" required>
                                <option value="">-- Pilih Kelas --</option>
                                @if(!empty($kelas))
                                    @foreach($kelas as $k)
                                        @php
                                            $labelKelas = 'Tingkat ' . $k->tingkat;
                                            if (!empty($k->nama_kelas)) $labelKelas .= ' ' . $k->nama_kelas;
                                        @endphp
                                        <option value="{{ $k->id }}" {{ (old('kelas_id') ?? ($selectedKelas ?? null)) == $k->id ? 'selected' : '' }}>
                                            {{ $labelKelas }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- jQuery AJAX -->
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script>
                            $('#id_sekolah').on('change', function () {
                                var sekolahID = $(this).val();
                                $('#kelas_id').html('<option value="">-- Memuat data kelas... --</option>');
                                if (sekolahID) {
                                    $.ajax({
                                        url: '{{ url("/get-kelas-by-sekolah") }}/' + sekolahID,
                                        type: 'GET',
                                        success: function (data) {
                                            $('#kelas_id').empty().append('<option value="">-- Pilih Kelas --</option>');
                                            data.forEach(function (k) {
                                                let label = 'Tingkat ' + k.tingkat;
                                                if (k.nama_kelas) label += ' ' + k.nama_kelas;
                                                $('#kelas_id').append(`<option value="${k.id}">${label}</option>`);
                                            });
                                        },
                                        error: function () {
                                            $('#kelas_id').html('<option value="">-- Gagal memuat kelas --</option>');
                                        }
                                    });
                                } else {
                                    $('#kelas_id').html('<option value="">-- Pilih Sekolah Dulu --</option>');
                                }
                            });
                        </script>

                        <!-- Status -->
                        <div class="form-group">
                            <label>Status Siswa</label>
                            <select name="status" class="form-control" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="lulus" {{ old('status') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                                <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tahun Ajaran *</label>
                            <select name="tahun_ajaran_id" class="form-control" required>
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                @foreach($tahunAjaran as $ta)
                                    <option value="{{ $ta->id }}" {{ old('tahun_ajaran_id') == $ta->id ? 'selected' : '' }}>
                                        {{ $ta->nama_tahun }} @if($ta->aktif) (Aktif) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                {{-- Card 2: Data Login --}}
                <div class="form-card">
                    <h3>Akun siswa</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control"
                                value="{{ old('username') }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Password *
                            </label>
                            <div class="input-group" style="display:flex; gap:8px;">
                                <input type="text" name="password" id="password-field"
                                    class="form-control"
                                    placeholder="••••••••" required>
                                <button type="button" class="btn btn-success" id="generate-password">
                                    <i class="fas fa-random"></i> Buat acak
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Data Utama Siswa --}}
                <div class="form-card full-width-card">
                    <h3>Data utama</h3>
                    <div class="form-grid two-columns">
                        <div class="form-group">
                            <label class="form-label">NIS *</label>
                            <input type="text" name="nis" class="form-control" value="{{ old('nis') }}" required placeholder="Nomor Induk Siswa">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nama Lengkap *</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required placeholder="Nama lengkap siswa">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tanggal Lahir *</label>
                            <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Jenis Kelamin *</label>
                            <select name="jenis_kelamin" class="form-control" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label class="form-label">Alamat Lengkap *</label>
                            <textarea name="alamat" class="form-control" rows="3" required placeholder="Alamat lengkap tempat tinggal siswa">{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Card 4: Data Pribadi Tambahan --}}
                <div class="form-card">
                    <h3>Data tambahan</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Agama</label>
                            <input type="text" name="agama" class="form-control" value="{{ old('agama') }}" placeholder="Agama yang dianut">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tempat Tinggal</label>
                            <input type="text" name="tempat_tinggal" class="form-control" value="{{ old('tempat_tinggal') }}" placeholder="Status tempat tinggal (Rumah sendiri, Kost, dll)">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Moda Transportasi</label>
                            <input type="text" name="moda_transportasi" class="form-control" value="{{ old('moda_transportasi') }}" placeholder="Contoh: Sepeda Motor, Angkutan Umum, Jalan Kaki">
                        </div>
                    </div>
                </div>

                {{-- Card 5: Data Ayah --}}
                <div class="form-card">
                    <h3>Data ayah</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Nama Ayah</label>
                            <input type="text" name="nama_ayah" class="form-control" value="{{ old('nama_ayah') }}" placeholder="Nama lengkap ayah">
                        </div>

                        <div class="form-group">
                            <label class="form-label">NIK Ayah</label>
                            <input type="text" name="nik_ayah" class="form-control" value="{{ old('nik_ayah') }}" placeholder="Nomor Induk Kependudukan ayah">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Pekerjaan Ayah</label>
                            <input type="text" name="pekerjaan_ayah" class="form-control" value="{{ old('pekerjaan_ayah') }}" placeholder="Pekerjaan utama ayah">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Penghasilan Ayah (Rp)</label>
                            <input type="number" step="any" name="penghasilan_ayah" id="penghasilan-ayah" class="form-control" 
                                   value="{{ old('penghasilan_ayah') }}" placeholder="0" min="0">
                            <div id="penghasilan-ayah-warning" class="validation-message">
                                <i class="fas fa-exclamation-triangle"></i> Nilai penghasilan terlalu besar. Maksimal 2.147.483.647
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 6: Data Ibu --}}
                <div class="form-card">
                    <h3>Data ibu</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Nama Ibu</label>
                            <input type="text" name="nama_ibu" class="form-control" value="{{ old('nama_ibu') }}" placeholder="Nama lengkap ibu">
                        </div>

                        <div class="form-group">
                            <label class="form-label">NIK Ibu</label>
                            <input type="text" name="nik_ibu" class="form-control" value="{{ old('nik_ibu') }}" placeholder="Nomor Induk Kependudukan ibu">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Pekerjaan Ibu</label>
                            <input type="text" name="pekerjaan_ibu" class="form-control" value="{{ old('pekerjaan_ibu') }}" placeholder="Pekerjaan utama ibu">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Penghasilan Ibu (Rp)</label>
                            <input type="number" step="any" name="penghasilan_ibu" id="penghasilan-ibu" class="form-control" 
                                   value="{{ old('penghasilan_ibu') }}" placeholder="0" min="0">
                            <div id="penghasilan-ibu-warning" class="validation-message">
                                <i class="fas fa-exclamation-triangle"></i> Nilai penghasilan terlalu besar. Maksimal 2.147.483.647
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 7: Informasi Kontak --}}
                <div class="form-card">
                    <h3>Kontak</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">No. Telepon Rumah</label>
                            <input type="text" name="no_telp_rumah" class="form-control" value="{{ old('no_telp_rumah') }}" placeholder="Nomor telepon rumah">
                        </div>

                        <div class="form-group">
                            <label class="form-label">No. HP</label>
                            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}" placeholder="Nomor handphone yang aktif">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="alamat@email.com">
                        </div>
                    </div>
                </div>

                {{-- Card 8: Pengaturan SPP --}}
                <div class="form-card">
                    <h3>Nominal SPP</h3>

                    <div id="validation-alert" class="alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Perhatian!</strong> Anda memilih input manual, silakan masukkan nominal SPP terlebih dahulu.
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Mode Pengaturan SPP</label>
                            <div class="spp-container">
                                <button type="button" id="btn-default-spp" class="btn-default active">
                                    <i class="fas fa-check-circle"></i> Gunakan Default (Rp 325.000)
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nominal SPP (Rupiah)</label>
                            <input type="number" id="input-nominal-spp" name="nominal_spp"
                                   class="form-control nominal-input"
                                   value="{{ old('nominal_spp') }}"
                                   min="0" max="100000000" step="any"
                                   placeholder="Masukkan nominal SPP khusus" disabled>
                            <div id="nominal-validation" class="validation-message">
                                <i class="fas fa-times-circle"></i> Nominal SPP harus diisi saat menggunakan input manual.
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="use_default_spp" id="use-default-spp" value="1">
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="action-buttons">
                <button type="submit" class="btn btn-primary" id="submit-btn">
                    <i class="fas fa-save"></i> Simpan Data Siswa
                </button>
                <a href="{{ route('siswa.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnDefault = document.getElementById('btn-default-spp');
    const inputNominal = document.getElementById('input-nominal-spp');
    const useDefaultInput = document.getElementById('use-default-spp');
    const validationAlert = document.getElementById('validation-alert');
    const nominalValidation = document.getElementById('nominal-validation');
    const submitBtn = document.getElementById('submit-btn');
    const studentForm = document.getElementById('student-form');
    
    function setModeDefault() {
        btnDefault.classList.remove('inactive');
        btnDefault.classList.add('active');
        btnDefault.innerHTML = '<i class="fas fa-check-circle"></i> Gunakan Default (Rp 325.000)';
        inputNominal.disabled = true;
        inputNominal.value = '';
        inputNominal.classList.remove('error');
        useDefaultInput.value = '1';
        validationAlert.classList.remove('show');
        nominalValidation.classList.remove('show');
        submitBtn.disabled = false;
    }

    function setModeManual() {
        btnDefault.classList.remove('active');
        btnDefault.classList.add('inactive');
        btnDefault.innerHTML = '<i class="fas fa-edit"></i> Input Manual';
        inputNominal.disabled = false;
        useDefaultInput.value = '0';
        
        // Cek apakah ada nilai di input
        if (!inputNominal.value || inputNominal.value.trim() === '') {
            showValidationWarning();
        }
    }

    function showValidationWarning() {
        validationAlert.classList.add('show');
        nominalValidation.classList.add('show');
        inputNominal.classList.add('error');
        inputNominal.focus();
    }

    function hideValidationWarning() {
        validationAlert.classList.remove('show');
        nominalValidation.classList.remove('show');
        inputNominal.classList.remove('error');
    }

    function validateForm() {
        // Jika mode manual dan input kosong
        if (useDefaultInput.value === '0') {
            if (!inputNominal.value || inputNominal.value.trim() === '' || parseInt(inputNominal.value) <= 0) {
                showValidationWarning();
                return false;
            } else {
                hideValidationWarning();
                return true;
            }
        }
        return true;
    }
    
    // Event listener untuk button default
    btnDefault.addEventListener('click', function() {
        if (btnDefault.classList.contains('active')) {
            // Jika sedang aktif, ubah ke mode input manual
            setModeManual();
            inputNominal.focus();
            inputNominal.select();
        } else {
            // Jika tidak aktif, kembali ke mode default
            setModeDefault();
        }
    });

    // Event listener untuk input nominal
    inputNominal.addEventListener('input', function() {
        if (this.value && this.value.trim() !== '' && parseInt(this.value) > 0) {
            // Jika ada input valid, pastikan button dalam mode inactive
            if (btnDefault.classList.contains('active')) {
                setModeManual();
            }
            hideValidationWarning();
        } else if (useDefaultInput.value === '0') {
            showValidationWarning();
        }
    });

    // Event listener untuk klik pada input (untuk mengaktifkan mode manual)
    inputNominal.addEventListener('click', function() {
        if (this.disabled) {
            btnDefault.click(); // Trigger button click untuk mengaktifkan mode manual
        }
    });

    // Event saat submit form
    studentForm.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            
            // Scroll ke area nominal SPP
            inputNominal.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
            
            return false;
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const sekolahSelect = document.getElementById('sekolah-select');
        const kelasSelect = document.getElementById('kelas-select');

        sekolahSelect.addEventListener('change', function () {
            const sekolahId = this.value;

            // Kosongkan dulu
            kelasSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>';

            if (!sekolahId) return;

            fetch(`/kelas-by-sekolah/${sekolahId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        kelasSelect.innerHTML += '<option disabled>(Tidak ada kelas)</option>';
                    } else {
                        data.forEach(kelas => {
                            const option = document.createElement('option');
                            option.value = kelas.id;
                            option.text = kelas.nama;
                            kelasSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Gagal mengambil data kelas:', error);
                    kelasSelect.innerHTML += '<option disabled>(Gagal memuat kelas)</option>';
                });
        });
    });

    // Event saat tombol submit diklik
    submitBtn.addEventListener('click', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            
            // Fokus ke input nominal
            setTimeout(() => {
                inputNominal.focus();
                inputNominal.select();
            }, 500);
        }
    });
    document.getElementById('generate-password').addEventListener('click', function() {
        // Fungsi untuk generate password random
        const length = 10; // panjang password
        const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
        let password = "";
        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * charset.length);
            password += charset[randomIndex];
        }
        // Set hasilnya ke input password
        const passwordField = document.getElementById('password-field');
        passwordField.value = password;
    });

    // Jika ada old value, set ke mode yang sesuai
    @if(old('nominal_spp'))
        // Jika ada old value, aktifkan mode manual
        setModeManual();
    @else
        // Default ke mode default
        setModeDefault();
    @endif

    // Validation for parent income
    const MAX_INTEGER = 2147483647;

    function validateIncome(input, warningId) {
        const warning = document.getElementById(warningId);
        const value = parseFloat(input.value);
        
        if (value > MAX_INTEGER) {
            warning.classList.add('show');
            input.classList.add('error');
            submitBtn.disabled = true;
            return false;
        } else {
            warning.classList.remove('show');
            input.classList.remove('error');
            submitBtn.disabled = false;
            return true;
        }
    }

    // Event listeners for income inputs
    document.getElementById('penghasilan-ayah').addEventListener('input', function() {
        validateIncome(this, 'penghasilan-ayah-warning');
    });

    document.getElementById('penghasilan-ibu').addEventListener('input', function() {
        validateIncome(this, 'penghasilan-ibu-warning');
    });

    // Add income validation to form submission
    studentForm.addEventListener('submit', function(e) {
        const ayahValid = validateIncome(
            document.getElementById('penghasilan-ayah'), 
            'penghasilan-ayah-warning'
        );
        const ibuValid = validateIncome(
            document.getElementById('penghasilan-ibu'), 
            'penghasilan-ibu-warning'
        );

        if (!ayahValid || !ibuValid) {
            e.preventDefault();
            alert('Mohon periksa kembali nilai penghasilan orang tua!');
            return false;
        }
    });
});
</script>
@endsection
