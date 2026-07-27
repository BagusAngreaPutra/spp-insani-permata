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
        .main-content { margin-left:0; width:100%; position:relative; }
    }
    .content-area { padding: 3rem 2.5rem; }

    .page-header {
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:2rem;
        background:rgba(255,255,255,0.9);
        padding:2rem;
        border-radius:24px;
        box-shadow:0 20px 40px rgba(0,0,0,0.1);
    }
    .page-title {
        font-size:2rem;
        font-weight:800;
        background:linear-gradient(135deg,#2d3748,#4a5568);
        -webkit-background-clip:text;
        -webkit-text-fill-color:transparent;
        display:flex;
        align-items:center;
        gap:0.75rem;
    }
    .alert-success {
        background:linear-gradient(135deg,#d1fae5,#bbf7d0);
        border:1px solid rgba(34,197,94,0.2);
        color:#166534;
        padding:1.25rem 1.5rem;
        border-radius:16px;
        margin-bottom:1.5rem;
    }
    .alert-error {
        background:linear-gradient(135deg,#fee2e2,#fecaca);
        border:1px solid rgba(239,68,68,0.3);
        color:#991b1b;
        padding:1.25rem 1.5rem;
        border-radius:16px;
        margin-bottom:1.5rem;
    }
    .import-card {
        background:rgba(255,255,255,0.95);
        padding:2rem;
        border-radius:24px;
        box-shadow:0 25px 50px rgba(0,0,0,0.15);
        border:1px solid rgba(255,255,255,0.2);
        margin-bottom: 2rem;
    }
    .btn-primary {
        background:linear-gradient(135deg,#22c55e,#16a34a);
        color:#fff;
        padding:0.75rem 1.25rem;
        border-radius:12px;
        font-weight:600;
        text-decoration:none;
        transition:all 0.3s ease;
        display:inline-block;
        box-shadow:0 4px 12px rgba(37,99,235,0.3);
    }
    .btn-primary:hover {
        transform:translateY(-2px);
        box-shadow:0 8px 20px rgba(37,99,235,0.4);
    }
    .btn-secondary {
        background:linear-gradient(135deg,#f59e0b,#d97706);
        color:#fff;
        padding:0.75rem 1.25rem;
        border-radius:12px;
        font-weight:600;
        text-decoration:none;
        transition:all 0.3s ease;
        display:inline-block;
        box-shadow:0 4px 12px rgba(251,191,36,0.3);
    }
    .btn-secondary:hover {
        transform:translateY(-2px);
        box-shadow:0 8px 20px rgba(251,191,36,0.4);
    }
    .form-group { margin-bottom:1.5rem; }
    label { font-weight:600; margin-bottom:0.5rem; display:block; }
    input[type="file"] {
        padding:0.75rem 1rem;
        border-radius:12px;
        border:2px solid rgba(34,197,94,0.2);
        background:white;
        font-size:0.9rem;
        transition:all 0.3s ease;
    }
    input[type="file"]:focus {
        outline:none;
        border-color:#22c55e;
        box-shadow:0 0 0 3px rgba(34,197,94,0.1);
        transform:translateY(-1px);
    }
    
    .info-box {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border: 1px solid rgba(59, 130, 246, 0.2);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .info-title {
        font-weight: 700;
        color: #1e40af;
        margin-bottom: 1rem;
        font-size: 1.2rem;
    }
    
    .info-list {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }
    
    .info-list li {
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
    }
    
    .info-list li:last-child {
        border-bottom: none;
    }
    
    .info-list .label {
        font-weight: 600;
        min-width: 180px;
        color: #1e3a8a;
    }
    
    .example-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .example-item {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #e5e7eb;
    }
    
    .example-header {
        font-weight: 600;
        color: #3b82f6;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }
    
    .example-details {
        font-family: monospace;
        font-size: 0.9rem;
    }
    
    .example-details div {
        margin-bottom: 0.25rem;
    }
    
    .example-details span {
        display: inline-block;
        min-width: 140px;
        font-weight: 500;
    }
</style>

<div class="main-content">
    @include('layouts.header')

    <div class="content-area">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <div class="page-header">
            <h2 class="page-title">
                <i class="fas fa-upload"></i> Import Data Siswa
            </h2>
            {{-- ✅ Sesuaikan ke route('import.template') --}}
            <a href="{{ route('import.template') }}" class="btn-secondary">
                <i class="fas fa-download"></i> Download Template
            </a>
        </div>

        <div class="import-card">
            {{-- ✅ Sesuaikan ke route('import') --}}
            <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="file_excel">Pilih File Excel (.xlsx / .xls)</label>
                    <input type="file" name="file_excel" id="file_excel" required>
                    @error('file_excel')
                        <div class="text-red-600 mt-1 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-primary">
                    <i class="fas fa-file-import"></i> Import Sekarang
                </button>
            </form>
        </div>

        <div class="info-box">
            <div class="info-title">
                <i class="fas fa-info-circle"></i> Informasi Referensi untuk Import
            </div>
            <ul class="info-list">
                <li>
                    <span class="label">ID Sekolah:</span>
                    <span>
                        @foreach(App\Models\Sekolah::all() as $sekolah)
                            {{ $sekolah->id }} = {{ $sekolah->nama_sekolah }}<br>
                        @endforeach
                    </span>
                </li>
                <li>
                    <span class="label">ID Tahun Ajaran:</span>
                    <span>
                        @foreach(App\Models\TahunAjaran::all() as $tahun)
                            {{ $tahun->id }} = {{ $tahun->nama_tahun }}<br>
                        @endforeach
                    </span>
                </li>
                <li>
                    <span class="label">ID Kelas:</span>
                    <span>
                        @foreach(App\Models\Kelas::with(['sekolah', 'tahunAjaran'])->get() as $kelas)
                            {{ $kelas->id }} = {{ $kelas->sekolah->nama_sekolah ?? 'Sekolah tidak ditemukan' }} - {{ $kelas->tahunAjaran->nama_tahun ?? 'Tahun ajaran tidak ditemukan' }} - {{ $kelas->nama_kelas }}<br>
                        @endforeach
                    </span>
                </li>
            </ul>
            
            <div class="info-title" style="margin-top: 1.5rem;">
                <i class="fas fa-table"></i> Contoh Pengisian yang Benar
            </div>
            <p>Berikut adalah contoh pengisian data di file Excel:</p>
            
            <div class="example-container">
                <div class="example-item">
                    <div class="example-header">Contoh Data Siswa 1</div>
                    <div class="example-details">
                        <div><span>id_sekolah:</span> 1</div>
                        <div><span>tahun_ajaran_id:</span> 1</div>
                        <div><span>kelas_id:</span> 9</div>
                        <div><span>nis:</span> 12312312</div>
                        <div><span>nama:</span> Rendi</div>
                        <div><span>jenis_kelamin:</span> L</div>
                        <div><span>tempat_lahir:</span> batu</div>
                        <div><span>tanggal_lahir:</span> 2022-02-25</div>
                        <div><span>alamat:</span> Alamat A</div>
                        <div><span>no_telepon:</span> 8123</div>
                        <div><span>email:</span> rendi@test.com</div>
                        <div><span>nama_ayah:</span> Juned</div>
                        <div><span>nik_ayah:</span> 12312</div>
                        <div><span>pekerjaan_ayah:</span> 12312</div>
                        <div><span>penghasilan_ayah:</span> 12312</div>
                        <div><span>nama_ibu:</span> Siti</div>
                        <div><span>nik_ibu:</span> 2131</div>
                        <div><span>pekerjaan_ibu:</span> 312312</div>
                        <div><span>penghasilan_ibu:</span> 12312</div>
                        <div><span>no_telp_rumah:</span> 2131</div>
                        <div><span>no_hp:</span> 8123</div>
                        <div><span>username:</span> rendi123</div>
                        <div><span>password:</span> Rendi123</div>
                        <div><span>agama:</span> Islam</div>
                        <div><span>tempat_tinggal:</span> Kambing</div>
                        <div><span>moda_transportasi: Mobil</span></div>
                        <div><span>status:</span> aktif</div>
                        <div><span>nominal_spp:</span> 325000</div>
                    </div>
                </div>
                
                <div class="example-item">
                    <div class="example-header">Contoh Data Siswa 2</div>
                    <div class="example-details">
                        <div><span>id_sekolah:</span> 1</div>
                        <div><span>tahun_ajaran_id:</span> 1</div>
                        <div><span>kelas_id:</span> 2</div>
                        <div><span>nis:</span> 123457</div>
                        <div><span>nama:</span> Ani Rahmawati</div>
                        <div><span>jenis_kelamin:</span> P</div>
                        <div><span>tempat_lahir:</span> Bandung</div>
                        <div><span>tanggal_lahir:</span> 2005-08-20</div>
                        <div><span>alamat:</span> Jl. Sudirman No. 45</div>
                        <div><span>no_telepon:</span> 081234567892</div>
                        <div><span>email:</span> ani@email.com</div>
                        <div><span>nama_ayah:</span> Ani Ayah</div>
                        <div><span>nik_ayah:</span> 1234567890123456</div>
                        <div><span>pekerjaan_ayah:</span> Dokter</div>
                        <div><span>penghasilan_ayah:</span> 15000000</div>
                        <div><span>nama_ibu:</span> Ani Ibu</div>
                        <div><span>nik_ibu:</span> 1234567890123457</div>
                        <div><span>pekerjaan_ibu:</span> Perawat</div>
                        <div><span>penghasilan_ibu:</span> 8000000</div>
                        <div><span>no_telp_rumah:</span> 021123456</div>
                        <div><span>no_hp:</span> 081234567892</div>
                        <div><span>username:</span> ani</div>
                        <div><span>password:</span> ani123</div>
                        <div><span>agama:</span> Islam</div>
                        <div><span>tempat_tinggal:</span> Rumah Sendiri</div>
                        <div><span>moda_transportasi:</span> Motor</div>
                        <div><span>status:</span> aktif</div>
                        <div><span>nominal_spp:</span> 450000</div>
                    </div>
                </div>
            </div>
            
            <p style="margin-top: 1rem;"><small><i>* Kolom lainnya dapat diisi sesuai dengan template yang telah disediakan</i></small></p>
        </div>
    </div>
</div>
@endsection