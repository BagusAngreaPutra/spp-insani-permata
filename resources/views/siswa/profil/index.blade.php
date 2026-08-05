@extends('layouts.app')
@include('layouts.sidebar-siswa')

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
        }
    }

    .content-area {
        padding: 3rem 2.5rem;
    }

    .page-header {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        padding: 2rem;
        border-radius: 24px;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, #166534, #14532d);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .box-profile {
        background: rgba(255, 255, 255, 0.95);
        padding: 2rem;
        border-radius: 24px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95rem;
    }

    .modern-table th,
    .modern-table td {
        padding: 1rem 1.25rem;
        text-align: left;
        border-bottom: 1px solid rgba(220, 252, 231, 0.8);
    }

    .alert-success {
        background: linear-gradient(135deg, #d1fae5, #bbf7d0);
        border: 1px solid rgba(34, 197, 94, 0.2);
        color: #166534;
        padding: 1.25rem 1.5rem;
        border-radius: 16px;
        margin-bottom: 1.5rem;
    }
</style>
@endpush

<div class="main-content">
    @include('layouts.header-siswa')

    <div class="content-area">
        <div class="page-header">
            <h2 class="page-title">
                <i class="fas fa-user-circle"></i> Profil Saya
            </h2>
        </div>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="box-profile">
            <table class="modern-table">
                <tr>
                    <th>Nama Lengkap</th>
                    <td>{{ $siswa->nama }}</td>
                </tr>
                <tr>
                    <th>NIS</th>
                    <td>{{ $siswa->nis }}</td>
                </tr>
                <tr>
                    <th>Kelas</th>
                     <td>{{ $siswa->kelas?->kelas ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $siswa->email ?? '-' }}</td>
                </tr>
            </table>

            <div class="mt-6">
                <a href="{{ route('siswa.profil.editPassword') }}"
                   class="bg-green-700 text-white px-5 py-2 rounded hover:bg-green-800 transition duration-200">
                    Ubah Password
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
