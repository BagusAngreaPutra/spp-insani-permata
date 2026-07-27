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
    .btn-export {
        background:linear-gradient(135deg,#3b82f6,#2563eb);
        color:#fff;
        padding:0.75rem 1.25rem;
        border-radius:12px;
        font-weight:600;
        text-decoration:none;
        transition:all 0.3s ease;
        display:inline-block;
        box-shadow:0 4px 12px rgba(59,130,246,0.3);
    }
    .btn-export:hover {
        transform:translateY(-2px);
        box-shadow:0 8px 20px rgba(59,130,246,0.4);
    }
</style>

<div class="main-content">
    @include('layouts.header')

    <div class="content-area">
        <div class="page-header">
            <h2 class="page-title">
                <i class="fas fa-file-export"></i> Export Data Siswa
            </h2>
        </div>

        <div class="import-card" style="background:rgba(255,255,255,0.95);padding:2rem;border-radius:24px;box-shadow:0 25px 50px rgba(0,0,0,0.15);border:1px solid rgba(255,255,255,0.2);">
            <p style="margin-bottom:1.5rem;font-weight:500;">
                Klik tombol di bawah ini untuk mengekspor semua data siswa ke dalam file Excel (.xlsx).
            </p>
            <a href="{{ route('export_excel.download') }}" class="btn-export">
                <i class="fas fa-download"></i> Export Sekarang
            </a>
        </div>
    </div>
</div>
@endsection
