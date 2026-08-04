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

    .content-area {
        padding: 3rem 2.5rem;
    }

    .form-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        padding: 2.5rem 3rem;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        max-width: 100%;
    }

    .form-container h2 {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, #2d3748, #4a5568);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 2rem;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        display: block;
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.85rem 1rem;
        border-radius: 12px;
        border: 1px solid #d1d5db;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: #22c55e;
        outline: none;
        box-shadow: 0 0 0 3px rgba(34,197,94,0.2);
    }

    .alert-danger {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.85rem 2rem;
        border-radius: 16px;
        font-weight: 600;
        text-decoration: none;
        transition: all 300ms ease;
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(245, 158, 11, 0.4);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #9ca3af, #6b7280);
        color: white;
        box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
        margin-left: 1rem;
    }

    .btn-secondary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(107, 114, 128, 0.4);
    }
</style>
@endpush

<div class="main-content">
    @include('layouts.header')

    <div class="content-area">
        <div class="form-container">
            <h2><i class="fas fa-edit"></i> Edit Pengeluaran</h2>

            @if ($errors->any())
                <div class="alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pengeluaran.update', $pengeluaran->id) }}" method="POST">
                @csrf
                @method('PUT')
                <select name="sekolah_id" class="form-control" required>
                    @foreach($sekolah as $s)
                        <option value="{{ $s->id }}" {{ old('sekolah_id', $pengeluaran->sekolah_id) == $s->id ? 'selected' : '' }}>
                            {{ $s->nama_sekolah }}
                        </option>
                    @endforeach
                </select>
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" 
                       value="{{ old('tanggal', $pengeluaran->tanggal) }}" required>

                <label class="form-label">Jumlah</label>
                <input type="number" step="1" min="0" name="jumlah" class="form-control" data-rupiah
                       value="{{ old('jumlah', $pengeluaran->jumlah) }}" required>

                <label class="form-label">Keperluan</label>
                <input type="text" name="keperluan" class="form-control"
                       value="{{ old('keperluan', $pengeluaran->keperluan) }}">

                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $pengeluaran->keterangan) }}</textarea>

                <label class="form-label">Sekolah</label>
                

                <div class="mt-6 flex items-center gap-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="{{ route('pengeluaran.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
