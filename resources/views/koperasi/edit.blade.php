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
        .main-content { margin-left: 0; width: 100%; position: relative; }
    }
    .content-area { padding: 3rem 2.5rem; }
    .form-card {
        background: rgba(255,255,255,0.95);
        border: 1px solid rgba(34,197,94,0.12);
        border-radius: 22px;
        box-shadow: 0 18px 38px rgba(15,23,42,0.09);
        padding: 2rem;
    }
    .page-title {
        color: #14532d;
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
    }
    .form-grid {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    .form-group { display: flex; flex-direction: column; }
    .form-group.full { grid-column: 1 / -1; }
    .form-group label {
        color: #166534;
        font-weight: 700;
        margin-bottom: 0.45rem;
    }
    .form-group input, .form-group select, .form-group textarea {
        border: 2px solid rgba(34,197,94,0.18);
        border-radius: 12px;
        padding: 0.75rem 0.9rem;
    }
    .form-group small { color: #dc2626; margin-top: 0.35rem; }
    .form-actions {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
        margin-top: 1.5rem;
    }
    .btn-submit, .btn-cancel {
        border: none;
        border-radius: 12px;
        color: #fff;
        font-weight: 800;
        padding: 0.75rem 1.2rem;
        text-decoration: none;
    }
    .btn-submit { background: linear-gradient(135deg, #22c55e, #16a34a); }
    .btn-cancel { background: linear-gradient(135deg, #64748b, #475569); }
    .alert-error {
        background: #fee2e2;
        border-radius: 12px;
        color: #991b1b;
        margin-bottom: 1rem;
        padding: 1rem;
    }
    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

<div class="main-content">
    @include('layouts.header')

    <div class="content-area">
        @include('partials.admin-page-context', [
            'section' => 'Koperasi',
            'current' => 'Edit Barang',
            'title' => 'Perbarui data barang koperasi.',
            'description' => 'Gunakan halaman ini untuk memperbarui harga, stok, kategori, atau status barang.'
        ])

        <div class="form-card">
            <h2 class="page-title"><i class="fas fa-edit"></i> Edit Barang Koperasi</h2>

            @if($errors->any())
                <div class="alert-error">Periksa kembali data yang dimasukkan.</div>
            @endif

            <form action="{{ route('koperasi.update', $koperasi->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('koperasi._form')

                <div class="form-actions">
                    <a href="{{ route('koperasi.index') }}" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
