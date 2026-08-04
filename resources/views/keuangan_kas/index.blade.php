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

    .table-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95rem;
    }

    .modern-table th,
    .modern-table td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid rgba(220, 252, 231, 0.8);
        text-align: left;
    }

    .modern-table th {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        font-weight: 700;
        color: #166534;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modern-table tbody tr:hover {
        background: rgba(34, 197, 94, 0.05);
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        padding: 2rem;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .page-title {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, #2d3748, #4a5568);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
</style>
@endpush

<div class="main-content">
    @include('layouts.header')

    <div class="content-area">
        <div class="page-header">
            <div>
                <span class="page-eyebrow">Ringkasan keuangan</span>
                <h2 class="page-title">
                    <i class="fas fa-cash-register"></i> Keuangan Kas
                </h2>
                <p class="page-subtitle">Pantau pemasukan, pengeluaran, dan saldo kas setiap unit sekolah.</p>
            </div>
            <div class="page-actions">
                @if(Auth::user()->hasPermission('pemasukan.manage'))
                    <a href="{{ route('pemasukan.create') }}" class="btn btn-success">
                        <i class="fas fa-arrow-trend-up"></i> Catat Pemasukan
                    </a>
                @endif
                @if(Auth::user()->hasPermission('pengeluaran.manage'))
                    <a href="{{ route('pengeluaran.create') }}" class="btn btn-danger">
                        <i class="fas fa-arrow-trend-down"></i> Catat Pengeluaran
                    </a>
                @endif
            </div>
        </div>

        <div class="table-container">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> No</th>
                        <th><i class="fas fa-school"></i> Sekolah</th>
                        <th><i class="fas fa-arrow-trend-up"></i> Total Pemasukan</th>
                        <th><i class="fas fa-arrow-trend-down"></i> Total Pengeluaran</th>
                        <th><i class="fas fa-wallet"></i> Saldo Kas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="table-entity">
                                    <span class="table-entity-icon"><i class="fas fa-school"></i></span>
                                    <strong>{{ $row['sekolah']->nama_sekolah }}</strong>
                                </span>
                            </td>
                            <td><span class="money-value is-income">Rp {{ number_format($row['total_pemasukan'], 0, ',', '.') }}</span></td>
                            <td><span class="money-value is-expense">Rp {{ number_format($row['total_pengeluaran'], 0, ',', '.') }}</span></td>
                            <td>
                                <span class="money-value {{ $row['saldo'] > 0 ? 'is-positive' : ($row['saldo'] < 0 ? 'is-negative' : 'is-neutral') }}">
                                    Rp {{ number_format($row['saldo'], 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                    @if($data->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center py-4">Belum ada data sekolah.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
