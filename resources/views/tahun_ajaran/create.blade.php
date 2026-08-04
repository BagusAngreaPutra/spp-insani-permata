@extends('layouts.app')
@include('layouts.sidebar')

@section('content')
@php
    $isEdit = isset($tahunAjaran) && $tahunAjaran;
    $selectedPeriod = old('nama_tahun', $tahunAjaran?->label ?? '');
    $isActive = (bool) old('aktif', $tahunAjaran?->aktif ?? false);
@endphp
<div class="main-content">
    @include('layouts.header')

    <div class="content-area school-form-page academic-year-form-page">
        <div class="page-header">
            <div>
                <p class="page-eyebrow">Master Data</p>
                <h2 class="page-title">
                    <i class="fas {{ $isEdit ? 'fa-calendar-check' : 'fa-calendar-plus' }}"></i>
                    {{ $isEdit ? 'Edit Tahun Ajaran' : 'Tambah Tahun Ajaran' }}
                </h2>
                <p class="page-subtitle">Pilih periode akademik terstruktur untuk digunakan pada kelas, siswa, dan tanggal tagihan.</p>
            </div>
            <div class="page-actions">
                <a href="{{ route('tahun_ajaran.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="form-card">
            <div class="form-header">
                <div class="school-form-header-copy">
                    <h3><i class="fas fa-calendar-days"></i> Informasi Tahun Ajaran</h3>
                    <p>Periode tersedia sampai 20 tahun ke depan dan tidak perlu diketik manual.</p>
                </div>
            </div>

            <form action="{{ $isEdit ? route('tahun_ajaran.update', $tahunAjaran) : route('tahun_ajaran.store') }}" method="POST" class="academic-year-form">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="form-section">
                    <div class="school-form-section-heading">
                        <span class="school-form-section-icon school-form-section-icon--blue">
                            <i class="fas fa-calendar-days" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h4>Data Periode</h4>
                            <p>Tentukan nama periode dan status penggunaannya.</p>
                        </div>
                    </div>

                    <div class="academic-year-form-grid">
                        <div class="form-group">
                            <label for="nama_tahun" class="form-label required">
                                <i class="fas fa-calendar"></i> Nama Tahun Ajaran
                            </label>
                            <select name="nama_tahun"
                                    id="nama_tahun"
                                    class="form-control @error('nama_tahun') is-invalid @enderror"
                                    @disabled($periodLocked)
                                    required>
                                <option value="">Pilih tahun ajaran</option>
                                @foreach($academicYearOptions as $period)
                                    @php($alreadyUsed = in_array($period, $usedAcademicYears, true))
                                    <option value="{{ $period }}"
                                            @selected($selectedPeriod === $period)
                                            @disabled($alreadyUsed)>
                                        {{ $period }}{{ $alreadyUsed ? ' — Sudah ditambahkan' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @if($periodLocked)
                                <input type="hidden" name="nama_tahun" value="{{ $selectedPeriod }}">
                            @endif
                            @error('nama_tahun')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text">
                                {{ $periodLocked
                                    ? 'Periode dikunci karena sudah digunakan oleh kelas, siswa, jenis pembayaran, atau tagihan.'
                                    : 'Setiap periode berlangsung dari Juli tahun pertama sampai Juni tahun berikutnya.' }}
                            </small>
                        </div>

                        <div class="form-group">
                            <span class="form-label">
                                <i class="fas fa-toggle-on"></i> Status Tahun Ajaran
                            </span>
                            <div class="academic-year-toggle-panel">
                                <input type="checkbox"
                                       name="aktif"
                                       id="aktif"
                                       value="1"
                                       class="academic-year-active-input"
                                       {{ $isActive ? 'checked' : '' }}>
                                <label for="aktif" class="academic-year-toggle-label">
                                    <span class="academic-year-toggle-track" aria-hidden="true">
                                        <span class="academic-year-toggle-knob"></span>
                                    </span>
                                    <span class="academic-year-toggle-copy">
                                        <strong>Jadikan periode aktif</strong>
                                        <small>Jika diaktifkan, periode aktif sebelumnya otomatis dinonaktifkan.</small>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('tahun_ajaran.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Tahun Ajaran' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
