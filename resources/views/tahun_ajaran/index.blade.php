@extends('layouts.app')
@include('layouts.sidebar')

@section('content')
<div class="main-content">
    @include('layouts.header')

    <div class="content-area academic-year-page">
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                <i class="fas fa-circle-check" aria-hidden="true"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-circle-exclamation" aria-hidden="true"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="page-header">
            <div>
                <p class="page-eyebrow">Master Data</p>
                <h2 class="page-title">
                    <i class="fas fa-calendar-days"></i> Tahun Ajaran
                </h2>
                <p class="page-subtitle">Kelola periode akademik yang digunakan pada kelas, siswa, dan tagihan.</p>
            </div>
        </div>

        <div class="table-card academic-year-card">
            <div class="table-header">
                <div class="academic-year-table-heading">
                    <h3><i class="fas fa-list"></i> Daftar Tahun Ajaran</h3>
                    <p>{{ $tahunAjaran->count() }} periode akademik tersimpan</p>
                </div>
                <div class="table-actions">
                    <a href="{{ route('tahun_ajaran.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Tahun Ajaran
                    </a>
                </div>
            </div>

            @if($tahunAjaran->isNotEmpty())
                <div class="table-responsive">
                    <table class="modern-table academic-year-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tahun Ajaran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tahunAjaran as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="table-entity">
                                            <span class="table-entity-icon academic-year-entity-icon" aria-hidden="true">
                                                <i class="fas fa-calendar-days"></i>
                                            </span>
                                            <div class="academic-year-info">
                                                <strong class="academic-year-name">{{ $item->label }}</strong>
                                                <small>{{ $item->hasValidPeriod() ? 'Juli–Juni · Periode akademik' : 'Format lama · Perlu diperbaiki' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge status-badge academic-year-status {{ !$item->hasValidPeriod() ? 'badge-danger' : ($item->aktif ? 'badge-success' : 'badge-secondary') }}">
                                            <i class="fas fa-circle" aria-hidden="true"></i>
                                            {{ !$item->hasValidPeriod() ? 'Perlu diperbaiki' : ($item->aktif ? 'Aktif' : 'Nonaktif') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('tahun_ajaran.edit', $item->id) }}"
                                               class="btn btn-sm btn-warning"
                                               title="Edit Tahun Ajaran">
                                                <i class="fas fa-pen"></i>
                                                <span>Edit</span>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-sm btn-danger academic-year-delete-button"
                                                    data-academic-year-id="{{ $item->id }}"
                                                    data-academic-year-name="{{ $item->nama_tahun }}"
                                                    title="Hapus Tahun Ajaran">
                                                <i class="fas fa-trash"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        Menampilkan {{ $tahunAjaran->count() }} tahun ajaran
                    </div>
                </div>
            @else
                <div class="academic-year-empty-state">
                    @include('partials.admin-empty-state', [
                        'icon' => 'fas fa-calendar-alt',
                        'title' => 'Belum Ada Tahun Ajaran',
                        'message' => 'Tambahkan tahun ajaran sebelum membuat kelas dan siswa, lalu tandai periode yang sedang aktif.',
                        'actionRoute' => route('tahun_ajaran.create'),
                        'actionText' => 'Tambah Tahun Ajaran'
                    ])
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="academicYearDeleteModal" tabindex="-1" aria-labelledby="academicYearDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="academicYearDeleteModalLabel">
                        <i class="fas fa-triangle-exclamation text-warning"></i>
                        Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus tahun ajaran <strong id="academicYearDeleteName"></strong>?</p>
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-circle-exclamation" aria-hidden="true"></i>
                        <span>Data yang sudah dihapus tidak dapat dipulihkan dan data terkait dapat ikut terpengaruh.</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <form method="POST" id="academicYearDeleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Hapus Tahun Ajaran
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalElement = document.getElementById('academicYearDeleteModal');
    const deleteForm = document.getElementById('academicYearDeleteForm');
    const deleteName = document.getElementById('academicYearDeleteName');

    if (!modalElement || !deleteForm || !deleteName || !window.bootstrap?.Modal) return;

    const deleteModal = bootstrap.Modal.getOrCreateInstance(modalElement);

    document.querySelectorAll('.academic-year-delete-button').forEach(function(button) {
        button.addEventListener('click', function() {
            deleteName.textContent = this.dataset.academicYearName || '';
            deleteForm.action = '{{ url('tahun_ajaran') }}/' + this.dataset.academicYearId;
            deleteModal.show();
        });
    });
});
</script>
@endsection
