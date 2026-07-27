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
@media(max-width:768px){
    .main-content{margin-left:0;width:100%;position:relative;}
}
.content-area{padding:2rem;}
.page-header {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    text-align:center;
}
.page-header h1 {
    font-size:2rem;
    font-weight:700;
    background: linear-gradient(135deg,#16a34a,#15803d);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin:0;
    display:flex;
    justify-content:center;
    align-items:center;
    gap:.5rem;
}
.report-list {
    background:white;
    border-radius:12px;
    box-shadow:0 4px 15px rgba(0,0,0,0.08);
    overflow:hidden;
    margin-bottom:2rem;
    border:1px solid #e5e7eb;
}
.report-list table {
    width:100%;
    border-collapse:collapse;
}
.report-list th {
    background:#f0fdf4;
    text-align:left;
    padding:0.75rem 1rem;
    font-size:0.9rem;
    color:#166534;
    width:30%;
    border-bottom:1px solid #e5e7eb;
}
.report-list td {
    padding:0.75rem 1rem;
    border-bottom:1px solid #f3f4f6;
    color:#374151;
}
.report-list tr:last-child td,
.report-list tr:last-child th {
    border-bottom:none;
}
.badge {
    display:inline-block;
    padding:0.25rem 0.6rem;
    border-radius:9999px;
    font-size:0.75rem;
    font-weight:600;
}
.badge.bg-success {
    background: linear-gradient(135deg,#22c55e,#16a34a);
    color:#fff;
}
.badge.bg-info {
    background: linear-gradient(135deg,#3b82f6,#1d4ed8);
    color:#fff;
}
.action-buttons {
    display:flex;
    justify-content:center;
    gap:1rem;
    margin-top:2rem;
}
.btn {
    display:inline-flex;
    align-items:center;
    gap:.5rem;
    padding:.75rem 1.5rem;
    border-radius:8px;
    font-weight:600;
    text-decoration:none;
    border:none;
    cursor:pointer;
    font-size:.95rem;
}
.btn-primary {
    background:linear-gradient(135deg,#22c55e,#16a34a);
    color:#fff;
}
.btn-primary:hover { box-shadow:0 4px 10px rgba(34,197,94,0.3); }
.btn-secondary {
    background:linear-gradient(135deg,#9ca3af,#6b7280);
    color:#fff;
}
.btn-secondary:hover { box-shadow:0 4px 10px rgba(107,114,128,0.3); }
.action-card {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.08);
    padding: 1.5rem;
    margin-top: 2rem;
    display: flex;
    justify-content: left;
    gap: 1rem;
    border: 1px solid #e5e7eb;
}
.action-card .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    font-size: 0.95rem;
}
.action-card .btn-primary {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: #fff;
}
.action-card .btn-primary:hover {
    box-shadow: 0 4px 10px rgba(34,197,94,0.3);
}
.action-card .btn-secondary {
    background: linear-gradient(135deg, #9ca3af, #6b7280);
    color: #fff;
}
.action-card .btn-secondary:hover {
    box-shadow: 0 4px 10px rgba(107,114,128,0.3);
}
</style>

<div class="main-content">
    @include('layouts.header')

    <div class="content-area">
        <div class="page-header">
            <h1><i class="fas fa-user"></i> Detail Data Siswa</h1>
        </div>

        <div class="report-list">
            <table>
                <tr><th>Sekolah</th><td>{{ $siswa->sekolah->nama_sekolah ?? '-' }}</td></tr>
                <tr><th>Kelas</th>
                    <td>
                        @if($siswa->kelas)
                            Tingkat {{ $siswa->kelas->tingkat }} {{ $siswa->kelas->nama_kelas }}
                        @else - @endif
                    </td>
                </tr>
                <tr><th>Tahun Ajaran</th>
                    <td>
                        {{ $siswa->tahunAjaran->nama_tahun ?? '-' }}
                        @if($siswa->tahunAjaran && $siswa->tahunAjaran->aktif)
                            <span class="badge bg-success">Aktif</span>
                        @endif
                    </td>
                </tr>
                <tr><th>Username</th><td>{{ $siswa->username }}</td></tr>
                <tr><th>NIS</th><td>{{ $siswa->nis }}</td></tr>
                <tr><th>Nama Lengkap</th><td>{{ $siswa->nama }}</td></tr>
                <tr><th>Tanggal Lahir</th>
                    <td>{{ $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d-m-Y') : '-' }}</td>
                </tr>
                <tr><th>Jenis Kelamin</th>
                    <td>
                        @if($siswa->jenis_kelamin=='L') Laki-laki
                        @elseif($siswa->jenis_kelamin=='P') Perempuan
                        @else - @endif
                    </td>
                </tr>
                <tr><th>Alamat</th><td>{{ $siswa->alamat ?: '-' }}</td></tr>
                <tr><th>Agama</th><td>{{ $siswa->agama ?: '-' }}</td></tr>
                <tr><th>Tempat Tinggal</th><td>{{ $siswa->tempat_tinggal ?: '-' }}</td></tr>
                <tr><th>Moda Transportasi</th><td>{{ $siswa->moda_transportasi ?: '-' }}</td></tr>
                <tr><th>Nama Ayah</th><td>{{ $siswa->nama_ayah ?: '-' }}</td></tr>
                <tr><th>NIK Ayah</th><td>{{ $siswa->nik_ayah ?: '-' }}</td></tr>
                <tr><th>Pekerjaan Ayah</th><td>{{ $siswa->pekerjaan_ayah ?: '-' }}</td></tr>
                <tr><th>Penghasilan Ayah</th>
                    <td>
                        @if($siswa->penghasilan_ayah)
                            Rp {{ number_format($siswa->penghasilan_ayah,0,',','.') }}
                        @else - @endif
                    </td>
                </tr>
                <tr><th>Nama Ibu</th><td>{{ $siswa->nama_ibu ?: '-' }}</td></tr>
                <tr><th>NIK Ibu</th><td>{{ $siswa->nik_ibu ?: '-' }}</td></tr>
                <tr><th>Pekerjaan Ibu</th><td>{{ $siswa->pekerjaan_ibu ?: '-' }}</td></tr>
                <tr><th>Penghasilan Ibu</th>
                    <td>
                        @if($siswa->penghasilan_ibu)
                            Rp {{ number_format($siswa->penghasilan_ibu,0,',','.') }}
                        @else - @endif
                    </td>
                </tr>
                <tr><th>No. Telepon Rumah</th><td>{{ $siswa->no_telp_rumah ?: '-' }}</td></tr>
                <tr><th>No. HP</th><td>{{ $siswa->no_hp ?: '-' }}</td></tr>
                <tr><th>Email</th><td>{{ $siswa->email ?: '-' }}</td></tr>
                <tr><th>Nominal SPP</th>
                    <td>
                        @if($siswa->nominal_spp)
                            Rp {{ number_format($siswa->nominal_spp,0,',','.') }}
                            @if($siswa->use_default_spp)
                                <span class="badge bg-info">Menggunakan Default</span>
                            @endif
                        @else - @endif
                    </td>
                </tr>
            </table>
        </div>

        <div class="action-card">
            <a href="{{ route('siswa.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('siswa.edit',$siswa->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>

    </div>
</div>
@endsection
