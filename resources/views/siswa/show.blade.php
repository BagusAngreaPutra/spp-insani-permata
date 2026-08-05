@extends('layouts.app')
@include('layouts.sidebar')

@section('content')
@php
    $studentName = trim($siswa->nama ?: 'Siswa');
    $studentInitial = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($studentName, 0, 1));
    $className = $siswa->kelas
        ? $siswa->kelas->kelas
        : '-';
    $studentStatus = $siswa->status ?: 'Tidak tersedia';
    $isActive = in_array(strtolower((string) $siswa->status), ['aktif', 'active'], true);
    $gender = $siswa->jenis_kelamin === 'L'
        ? 'Laki-laki'
        : ($siswa->jenis_kelamin === 'P' ? 'Perempuan' : '-');
@endphp

<div class="main-content">
    @include('layouts.header')

    <div class="content-area">
        <div class="page-header">
            <div>
                <span class="page-eyebrow">Master data siswa</span>
                <h1 class="page-title"><i class="fas fa-address-card"></i> Detail Data Siswa</h1>
                <p class="page-subtitle">Informasi akademik, data pribadi, keluarga, dan pembayaran siswa dalam satu profil.</p>
            </div>
            <div class="page-actions">
                <a href="{{ route('siswa.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-warning">
                    <i class="fas fa-pen"></i> Edit Data
                </a>
            </div>
        </div>

        <div class="student-profile-shell">
            <aside class="student-profile-hero">
                <div class="student-avatar" aria-hidden="true">{{ $studentInitial }}</div>
                <h2>{{ $studentName }}</h2>
                <p>NIS {{ $siswa->nis ?: '-' }}</p>

                <div class="student-profile-tags">
                    <span class="badge {{ $isActive ? 'badge-success' : 'badge-warning' }}">
                        {{ ucfirst($studentStatus) }}
                    </span>
                    <span class="badge badge-info">{{ $gender }}</span>
                </div>

                <div class="student-school-summary">
                    <div>
                        <i class="fas fa-school" aria-hidden="true"></i>
                        <span>
                            <small>Sekolah</small>
                            <strong>{{ $siswa->sekolah->nama_sekolah ?? '-' }}</strong>
                        </span>
                    </div>
                    <div>
                        <i class="fas fa-users-rectangle" aria-hidden="true"></i>
                        <span>
                            <small>Kelas</small>
                            <strong>{{ $className }}</strong>
                        </span>
                    </div>
                    <div>
                        <i class="fas fa-calendar-days" aria-hidden="true"></i>
                        <span>
                            <small>Tahun Ajaran</small>
                            <strong>{{ $siswa->tahunAjaran->nama_tahun ?? '-' }}</strong>
                        </span>
                    </div>
                </div>
            </aside>

            <div class="student-detail-stack">
                <section class="student-detail-section">
                    <div class="student-detail-header">
                        <i class="fas fa-graduation-cap" aria-hidden="true"></i>
                        <div>
                            <h3>Akademik & Akun</h3>
                            <p>Identitas siswa pada sistem dan penempatan akademik.</p>
                        </div>
                    </div>
                    <div class="student-detail-grid">
                        <div class="student-detail-item">
                            <span class="student-detail-label">NIS</span>
                            <span class="student-detail-value">{{ $siswa->nis ?: '-' }}</span>
                        </div>
                        <div class="student-detail-item">
                            <span class="student-detail-label">Username</span>
                            <span class="student-detail-value">{{ $siswa->username ?: '-' }}</span>
                        </div>
                        <div class="student-detail-item">
                            <span class="student-detail-label">Status Siswa</span>
                            <span class="student-detail-value">
                                <span class="badge {{ $isActive ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($studentStatus) }}</span>
                            </span>
                        </div>
                        <div class="student-detail-item">
                            <span class="student-detail-label">Sekolah</span>
                            <span class="student-detail-value">{{ $siswa->sekolah->nama_sekolah ?? '-' }}</span>
                        </div>
                        <div class="student-detail-item">
                            <span class="student-detail-label">Kelas</span>
                            <span class="student-detail-value">{{ $className }}</span>
                        </div>
                        <div class="student-detail-item">
                            <span class="student-detail-label">Tahun Ajaran</span>
                            <span class="student-detail-value">
                                {{ $siswa->tahunAjaran->nama_tahun ?? '-' }}
                                @if($siswa->tahunAjaran && $siswa->tahunAjaran->aktif)
                                    <span class="badge badge-success">Aktif</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </section>

                <section class="student-detail-section" data-tone="green">
                    <div class="student-detail-header">
                        <i class="fas fa-user" aria-hidden="true"></i>
                        <div>
                            <h3>Informasi Pribadi</h3>
                            <p>Data dasar dan domisili siswa.</p>
                        </div>
                    </div>
                    <div class="student-detail-grid">
                        <div class="student-detail-item">
                            <span class="student-detail-label">Nama Lengkap</span>
                            <span class="student-detail-value">{{ $studentName }}</span>
                        </div>
                        <div class="student-detail-item">
                            <span class="student-detail-label">Tanggal Lahir</span>
                            <span class="student-detail-value">{{ $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d-m-Y') : '-' }}</span>
                        </div>
                        <div class="student-detail-item">
                            <span class="student-detail-label">Jenis Kelamin</span>
                            <span class="student-detail-value">{{ $gender }}</span>
                        </div>
                        <div class="student-detail-item">
                            <span class="student-detail-label">Agama</span>
                            <span class="student-detail-value">{{ $siswa->agama ?: '-' }}</span>
                        </div>
                        <div class="student-detail-item">
                            <span class="student-detail-label">Tempat Tinggal</span>
                            <span class="student-detail-value">{{ $siswa->tempat_tinggal ?: '-' }}</span>
                        </div>
                        <div class="student-detail-item">
                            <span class="student-detail-label">Moda Transportasi</span>
                            <span class="student-detail-value">{{ $siswa->moda_transportasi ?: '-' }}</span>
                        </div>
                        <div class="student-detail-item student-detail-wide">
                            <span class="student-detail-label">Alamat</span>
                            <span class="student-detail-value">{{ $siswa->alamat ?: '-' }}</span>
                        </div>
                    </div>
                </section>

                <section class="student-detail-section" data-tone="purple">
                    <div class="student-detail-header">
                        <i class="fas fa-people-roof" aria-hidden="true"></i>
                        <div>
                            <h3>Orang Tua / Wali</h3>
                            <p>Informasi keluarga yang tersimpan pada data siswa.</p>
                        </div>
                    </div>
                    <div class="student-detail-grid">
                        <div class="student-detail-item">
                            <span class="student-detail-label">Nama Ayah</span>
                            <span class="student-detail-value">{{ $siswa->nama_ayah ?: '-' }}</span>
                        </div>
                        <div class="student-detail-item">
                            <span class="student-detail-label">NIK Ayah</span>
                            <span class="student-detail-value">{{ $siswa->nik_ayah ?: '-' }}</span>
                        </div>
                        <div class="student-detail-item">
                            <span class="student-detail-label">Pekerjaan Ayah</span>
                            <span class="student-detail-value">{{ $siswa->pekerjaan_ayah ?: '-' }}</span>
                        </div>
                        <div class="student-detail-item">
                            <span class="student-detail-label">Penghasilan Ayah</span>
                            <span class="student-detail-value">{{ $siswa->penghasilan_ayah ? 'Rp '.number_format($siswa->penghasilan_ayah, 0, ',', '.') : '-' }}</span>
                        </div>
                        <div class="student-detail-item">
                            <span class="student-detail-label">Nama Ibu</span>
                            <span class="student-detail-value">{{ $siswa->nama_ibu ?: '-' }}</span>
                        </div>
                        <div class="student-detail-item">
                            <span class="student-detail-label">NIK Ibu</span>
                            <span class="student-detail-value">{{ $siswa->nik_ibu ?: '-' }}</span>
                        </div>
                        <div class="student-detail-item">
                            <span class="student-detail-label">Pekerjaan Ibu</span>
                            <span class="student-detail-value">{{ $siswa->pekerjaan_ibu ?: '-' }}</span>
                        </div>
                        <div class="student-detail-item">
                            <span class="student-detail-label">Penghasilan Ibu</span>
                            <span class="student-detail-value">{{ $siswa->penghasilan_ibu ? 'Rp '.number_format($siswa->penghasilan_ibu, 0, ',', '.') : '-' }}</span>
                        </div>
                    </div>
                </section>

                <section class="student-detail-section" data-tone="orange">
                    <div class="student-detail-header">
                        <i class="fas fa-wallet" aria-hidden="true"></i>
                        <div>
                            <h3>Kontak & Pembayaran</h3>
                            <p>Kontak siswa dan nominal SPP yang berlaku.</p>
                        </div>
                    </div>
                    <div class="student-detail-grid">
                        <div class="student-detail-item">
                            <span class="student-detail-label">Telepon Rumah</span>
                            <span class="student-detail-value">{{ $siswa->no_telp_rumah ?: '-' }}</span>
                        </div>
                        <div class="student-detail-item">
                            <span class="student-detail-label">Nomor HP</span>
                            <span class="student-detail-value">{{ $siswa->no_hp ?: '-' }}</span>
                        </div>
                        <div class="student-detail-item">
                            <span class="student-detail-label">Email</span>
                            <span class="student-detail-value">{{ $siswa->email ?: '-' }}</span>
                        </div>
                        <div class="student-detail-item">
                            <span class="student-detail-label">Nominal SPP</span>
                            <span class="student-detail-value is-money">{{ $siswa->nominal_spp ? 'Rp '.number_format($siswa->nominal_spp, 0, ',', '.') : '-' }}</span>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <div class="student-detail-actions">
            <a href="{{ route('siswa.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
            <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-primary">
                <i class="fas fa-pen"></i> Edit Data Siswa
            </a>
        </div>
    </div>
</div>
@endsection
