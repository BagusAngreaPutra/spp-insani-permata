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
        overflow-x: hidden;
    }

    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
            width: 100%;
            position: relative;
        }
    }

    .content-area { 
        padding: 1.5rem;
        max-width: 100%;
        box-sizing: border-box;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #ffffff, #f8fafc);
        padding: 1.5rem;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(34, 197, 94, 0.1);
        border: 2px solid rgba(34, 197, 94, 0.1);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        width: 100%;
        box-sizing: border-box;
    }

    .dashboard-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #22c55e, #16a34a, #15803d);
    }

    .dashboard-title {
        font-size: clamp(1.2rem, 4vw, 2rem);
        font-weight: 800;
        background: linear-gradient(135deg, #14532d, #166534, #22c55e);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
        line-height: 1.2;
        word-break: break-word;
    }

    .dashboard-subtitle {
        color: #4b5563;
        font-size: clamp(0.8rem, 2.5vw, 1rem);
        margin-top: 0.5rem;
        line-height: 1.4;
    }

    /* Filter Section */
    .filter-section {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 8px 25px rgba(34, 197, 94, 0.1);
        border: 2px solid rgba(34, 197, 94, 0.1);
        margin-bottom: 2rem;
        width: 100%;
        box-sizing: border-box;
    }

    .filter-title {
        font-size: clamp(1rem, 3vw, 1.25rem);
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: #4b5563;
    }

    .filter-input {
        padding: 0.75rem;
        border: 2px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        width: 100%;
        box-sizing: border-box;
    }

    .filter-input:focus {
        outline: none;
        border-color: #22c55e;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.2);
    }

    .filter-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn-filter {
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
    }

    .btn-reset {
        padding: 0.75rem 1.5rem;
        background: #f3f4f6;
        color: #4b5563;
        border: 2px solid #d1d5db;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-reset:hover {
        background: #e5e7eb;
    }

    /* Summary Cards */
    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(280px, 100%), 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
        width: 100%;
    }

    .summary-card {
        background: linear-gradient(135deg, #ffffff, #f8fafc);
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 8px 25px rgba(34, 197, 94, 0.1);
        border: 2px solid rgba(34, 197, 94, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        min-width: 0;
        width: 100%;
        box-sizing: border-box;
    }

    .summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(34, 197, 94, 0.15);
        border-color: rgba(34, 197, 94, 0.2);
    }

    .summary-card.income {
        border-left: 4px solid #22c55e;
    }

    .summary-card.expense {
        border-left: 4px solid #ef4444;
    }

    .summary-card.balance {
        border-left: 4px solid #3b82f6;
    }

    .summary-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        gap: 0.5rem;
    }

    .summary-card-title {
        font-size: clamp(0.75rem, 2vw, 0.9rem);
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        word-break: break-word;
        flex: 1;
        min-width: 0;
    }

    .summary-card-icon {
        width: clamp(32px, 5vw, 40px);
        height: clamp(32px, 5vw, 40px);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: clamp(1rem, 2.5vw, 1.2rem);
        flex-shrink: 0;
    }

    .income .summary-card-icon {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
    }

    .expense .summary-card-icon {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .balance .summary-card-icon {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }

    .summary-card-value {
        font-size: clamp(1.2rem, 4vw, 1.8rem);
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 0.5rem;
        word-break: break-word;
        line-height: 1.2;
        overflow-wrap: break-word;
        hyphens: auto;
    }

    .summary-card-description {
        font-size: clamp(0.7rem, 2vw, 0.85rem);
        color: #6b7280;
        line-height: 1.3;
    }

    /* Charts Section */
    .charts-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
        width: 100%;
    }

    .chart-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 8px 25px rgba(34, 197, 94, 0.08);
        border: 2px solid rgba(34, 197, 94, 0.05);
        min-width: 0;
        width: 100%;
        box-sizing: border-box;
        overflow: hidden;
    }

    .chart-title {
        font-size: clamp(1rem, 3vw, 1.2rem);
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        word-break: break-word;
        line-height: 1.3;
    }

    .chart-container {
        height: clamp(250px, 40vw, 300px);
        position: relative;
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }

    /* School Cards */
    .schools-section {
        margin-top: 2rem;
        width: 100%;
    }

    .section-title {
        font-size: clamp(1.1rem, 3.5vw, 1.4rem);
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        word-break: break-word;
        line-height: 1.3;
    }

    .schools-table-card {
        background: white;
        overflow: hidden;
        width: 100%;
    }

    .schools-table-wrap {
        overflow-x: auto;
        width: 100%;
    }

    .schools-table {
        border-collapse: collapse;
        min-width: 760px;
        width: 100%;
    }

    .schools-table th {
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        color: #475569;
        font-size: 0.78rem;
        font-weight: 800;
        padding: 0.85rem 1rem;
        text-align: left;
        text-transform: uppercase;
    }

    .schools-table td {
        border-bottom: 1px solid #edf2f7;
        color: #0f172a;
        font-size: 0.92rem;
        padding: 0.9rem 1rem;
        vertical-align: middle;
    }

    .schools-table tr:last-child td {
        border-bottom: 0;
    }

    .schools-table tbody tr:hover {
        background: #f8fafc;
    }

    .school-cell {
        align-items: center;
        display: flex;
        gap: 0.75rem;
        min-width: 240px;
    }

    .school-cell-icon {
        align-items: center;
        background: #dcfce7;
        border-radius: 8px;
        color: #15803d;
        display: inline-flex;
        flex: 0 0 36px;
        height: 36px;
        justify-content: center;
        width: 36px;
    }

    .school-cell-name {
        color: #0f172a;
        display: block;
        font-weight: 800;
        line-height: 1.25;
    }

    .school-cell-subtitle {
        color: #64748b;
        display: block;
        font-size: 0.8rem;
        margin-top: 0.15rem;
    }

    .table-number {
        font-weight: 800;
        text-align: right;
        white-space: nowrap;
    }

    .table-money {
        font-variant-numeric: tabular-nums;
        font-weight: 800;
        text-align: right;
        white-space: nowrap;
    }

    .table-money.income { color: #15803d; }
    .table-money.expense { color: #dc2626; }
    .table-money.balance { color: #2563eb; }

    .schools-table-summary {
        align-items: center;
        background: #f8fafc;
        border-top: 1px solid #e5e7eb;
        color: #64748b;
        display: flex;
        font-size: 0.86rem;
        justify-content: space-between;
        padding: 0.85rem 1rem;
    }

    .empty-state {
        text-align: center;
        padding: 2.5rem 1.5rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(34, 197, 94, 0.1);
        border: 2px dashed rgba(34, 197, 94, 0.2);
        width: 100%;
        box-sizing: border-box;
    }

    .empty-state-icon {
        font-size: clamp(2rem, 6vw, 3rem);
        color: #a7f3d0;
        margin-bottom: 1rem;
    }

    .empty-state-text {
        font-size: clamp(0.9rem, 3vw, 1.1rem);
        color: #6b7280;
        font-weight: 600;
        line-height: 1.4;
    }

    .admin-workflow {
        background: #ffffff;
        border: 2px solid rgba(34, 197, 94, 0.1);
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(34, 197, 94, 0.1);
        margin-bottom: 2rem;
        padding: 1.5rem;
    }

    .workflow-header {
        align-items: flex-start;
        display: flex;
        gap: 1rem;
        justify-content: space-between;
        margin-bottom: 1.25rem;
    }

    .workflow-title {
        color: #0f172a;
        font-size: 1.25rem;
        font-weight: 800;
        margin: 0 0 0.35rem;
    }

    .workflow-subtitle {
        color: #64748b;
        line-height: 1.5;
        margin: 0;
    }

    .workflow-progress {
        background: #f0fdf4;
        border: 1px solid rgba(34, 197, 94, 0.18);
        border-radius: 12px;
        color: #166534;
        font-weight: 800;
        padding: 0.7rem 0.9rem;
        white-space: nowrap;
    }

    .workflow-steps {
        display: grid;
        gap: 0.85rem;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    }

    .workflow-step {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        display: flex;
        gap: 0.8rem;
        padding: 1rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .workflow-step:hover {
        border-color: rgba(34, 197, 94, 0.45);
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.08);
        transform: translateY(-2px);
    }

    .workflow-step.is-done {
        background: #f8fafc;
    }

    .workflow-number {
        align-items: center;
        background: #e2e8f0;
        border-radius: 999px;
        color: #334155;
        display: inline-flex;
        flex: 0 0 2rem;
        font-weight: 800;
        height: 2rem;
        justify-content: center;
        width: 2rem;
    }

    .workflow-step.is-done .workflow-number {
        background: #dcfce7;
        color: #166534;
    }

    .workflow-step-title {
        color: #0f172a;
        font-weight: 800;
        margin-bottom: 0.2rem;
    }

    .workflow-step-text {
        color: #64748b;
        font-size: 0.88rem;
        line-height: 1.45;
    }

    /* Professional dashboard refresh */
    .main-content {
        background: #f6f8fb;
    }

    .main-content > .header {
        padding: 0.75rem 1rem;
    }

    .main-content > .header .header-glass {
        background: #ffffff;
        border-bottom: 1px solid #e5e7eb;
        box-shadow: none;
    }

    .main-content > .header .header-content {
        max-width: none;
        padding: 0.6rem 1.25rem;
    }

    .main-content > .header .greeting {
        font-size: 1.25rem;
        line-height: 1.25;
        margin-bottom: 0.25rem;
    }

    .main-content > .header .additional-info {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem 1rem;
    }

    .main-content > .header .subtitle,
    .main-content > .header .date-info {
        font-size: 0.82rem;
        margin: 0;
    }

    .main-content > .header .user-avatar {
        height: 42px;
        width: 42px;
        box-shadow: none;
    }

    .main-content > .header .avatar-ring,
    .main-content > .header .header-decoration {
        display: none;
    }

    .main-content > .header .user-section {
        gap: 0.9rem;
    }

    .main-content > .header .action-btn {
        border-radius: 8px;
        min-height: 38px;
        padding: 0.55rem 0.8rem;
    }

    .content-area {
        padding: 1.25rem 1.75rem 2rem 2.5rem;
    }

    .dashboard-header,
    .filter-section,
    .summary-card,
    .chart-card,
    .schools-table-card,
    .admin-workflow,
    .empty-state {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
    }

    .dashboard-header {
        background: #ffffff;
        margin-bottom: 1rem;
        padding: 1.35rem 1.5rem;
    }

    .dashboard-header::before {
        background: #16a34a;
        height: 3px;
    }

    .dashboard-title {
        background: none;
        color: #0f172a;
        font-size: 1.55rem;
        letter-spacing: 0;
        -webkit-text-fill-color: currentColor;
    }

    .dashboard-title i,
    .filter-title i,
    .chart-title i,
    .section-title i {
        color: #16a34a;
        font-size: 0.95em;
    }

    .dashboard-subtitle {
        color: #64748b;
        font-size: 0.95rem;
        margin: 0.35rem 0 0;
    }

    .admin-workflow,
    .filter-section,
    .charts-section,
    .summary-cards {
        margin-bottom: 1rem;
    }

    .admin-workflow,
    .filter-section,
    .chart-card {
        background: #ffffff;
        padding: 1.25rem;
    }

    .workflow-title,
    .filter-title,
    .chart-title,
    .section-title {
        color: #0f172a;
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .workflow-subtitle {
        font-size: 0.9rem;
    }

    .workflow-progress {
        background: #eefdf3;
        border-color: #bbf7d0;
        border-radius: 8px;
        color: #15803d;
        font-size: 0.86rem;
        padding: 0.55rem 0.75rem;
    }

    .workflow-steps {
        gap: 0.7rem;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    }

    .workflow-step {
        align-items: flex-start;
        background: #ffffff;
        border-color: #e5e7eb;
        border-radius: 8px;
        padding: 0.85rem;
    }

    .workflow-step:hover {
        border-color: #86efac;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.07);
        transform: translateY(-1px);
    }

    .workflow-step.is-done {
        background: #f8fafc;
    }

    .workflow-number {
        background: #f1f5f9;
        border-radius: 8px;
        color: #475569;
        flex-basis: 1.9rem;
        font-size: 0.8rem;
        height: 1.9rem;
        width: 1.9rem;
    }

    .workflow-step.is-done .workflow-number {
        background: #dcfce7;
        color: #15803d;
    }

    .workflow-step-title,
    .workflow-step-text {
        display: block;
    }

    .filter-grid {
        gap: 0.85rem;
        margin-bottom: 1rem;
    }

    .filter-label,
    .summary-card-title,
    .stat-label,
    .financial-label {
        letter-spacing: 0;
        text-transform: none;
    }

    .filter-input {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        color: #0f172a;
        min-height: 42px;
        padding: 0.65rem 0.75rem;
    }

    .filter-input:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.12);
    }

    .btn-filter,
    .btn-reset {
        border-radius: 8px;
        min-height: 40px;
        padding: 0.6rem 1rem;
    }

    .btn-filter {
        background: #16a34a;
    }

    .btn-filter:hover,
    .btn-reset:hover {
        box-shadow: none;
        transform: none;
    }

    .summary-cards {
        gap: 0.85rem;
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .summary-card {
        background: #ffffff;
        padding: 1rem;
    }

    .summary-card:hover {
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
        transform: none;
    }

    .summary-card.income,
    .summary-card.expense,
    .summary-card.balance {
        border-left-width: 3px;
    }

    .summary-card-icon {
        border-radius: 8px;
        height: 36px;
        width: 36px;
    }

    .summary-card-value {
        color: #0f172a;
        font-size: 1.45rem;
        margin-bottom: 0.25rem;
    }

    .summary-card-description {
        color: #64748b;
        font-size: 0.82rem;
    }

    .charts-section {
        gap: 1rem;
    }

    .chart-container {
        height: 280px;
    }

    .schools-section {
        margin-top: 1rem;
    }

    /* Responsive breakpoints */
    @media (max-width: 1200px) {
        .charts-section {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .main-content {
            width: 100%;
            margin-left: 0;
            position: relative;
        }
        
        .content-area {
            padding: 1rem;
        }
        
        .dashboard-header {
            padding: 1.25rem;
        }
        
        .summary-cards {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .main-content > .header .header-content {
            align-items: flex-start;
            flex-direction: column;
            gap: 0.9rem;
        }

        .main-content > .header .user-section {
            flex-wrap: wrap;
            width: 100%;
        }
        
        .filter-grid {
            grid-template-columns: 1fr;
        }

        .workflow-header {
            flex-direction: column;
        }

        .workflow-progress {
            white-space: normal;
        }
    }

    @media (max-width: 480px) {
        .content-area {
            padding: 0.75rem;
        }
        
        .dashboard-header {
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .summary-card {
            padding: 1rem;
        }
        
        .chart-card {
            padding: 1rem;
        }
        
        .chart-container {
            height: 200px;
        }
        
        .filter-buttons {
            flex-direction: column;
        }
    }

    /* Utility classes for better text handling */
    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .break-words {
        word-break: break-word;
        overflow-wrap: break-word;
        hyphens: auto;
    }

    /* Print styles */
    @media print {
@page{
            size: landscape;
        }
        .main-content {
            margin-left: 0;
            width: 100%;
            position: static;
        }
        
        .summary-cards {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .charts-section {
            grid-template-columns: 1fr 1fr;
        }
        
        .filter-section {
            display: none;
        }
    }
</style>

<div class="main-content">
    @include('layouts.header')

    <div class="content-area">
        <div class="dashboard-header">
            <h1 class="dashboard-title">
                <i class="fas fa-chart-line"></i>
                <span class="break-words">Dashboard Keuangan Sekolah</span>
            </h1>
            <p class="dashboard-subtitle break-words">Monitoring pemasukan, pengeluaran, dan saldo kas seluruh sekolah</p>
        </div>

        @php
            $workflowSteps = [
                [
                    'label' => 'Sekolah',
                    'route' => route('sekolah.index'),
                    'permission' => 'sekolah.manage',
                    'done' => ($setupStats['sekolah'] ?? 0) > 0,
                    'text' => 'Daftarkan unit sekolah sebagai dasar semua data.',
                ],
                [
                    'label' => 'Tahun Ajaran',
                    'route' => route('tahun_ajaran.index'),
                    'permission' => 'tahun_ajaran.manage',
                    'done' => ($setupStats['tahun_ajaran'] ?? 0) > 0,
                    'text' => 'Tentukan periode aktif untuk siswa dan kelas.',
                ],
                [
                    'label' => 'Kelas',
                    'route' => route('kelas.index'),
                    'permission' => 'kelas.manage',
                    'done' => ($setupStats['kelas'] ?? 0) > 0,
                    'text' => 'Buat kelas sesuai sekolah dan tahun ajaran.',
                ],
                [
                    'label' => 'Siswa',
                    'route' => route('siswa.index'),
                    'permission' => 'siswa.manage',
                    'done' => ($setupStats['siswa'] ?? 0) > 0,
                    'text' => 'Masukkan siswa setelah sekolah dan kelas siap.',
                ],
                [
                    'label' => 'Jenis Pembayaran',
                    'route' => route('jenis_pembayaran.index'),
                    'permission' => 'jenis_pembayaran.manage',
                    'done' => ($setupStats['jenis_pembayaran'] ?? 0) > 0,
                    'text' => 'Atur SPP, biaya tahunan, atau pembayaran sekali.',
                ],
                [
                    'label' => 'Tagihan',
                    'route' => route('tagihan.index.grouped'),
                    'permission' => 'tagihan.manage',
                    'done' => ($setupStats['tagihan'] ?? 0) > 0,
                    'text' => 'Generate tagihan lalu proses pembayaran siswa.',
                ],
            ];
            $workflowSteps = collect($workflowSteps)
                ->filter(fn ($step) => Auth::user()->hasPermission($step['permission']))
                ->values()
                ->all();
            $completedSteps = collect($workflowSteps)->where('done', true)->count();
        @endphp

        @if(count($workflowSteps) > 0)
            <div class="admin-workflow">
                <div class="workflow-header">
                    <div>
                        <h2 class="workflow-title">Langkah Awal Penggunaan Sistem</h2>
                        <p class="workflow-subtitle">Ikuti urutan fitur yang sesuai dengan hak akses admin ini.</p>
                    </div>
                    <div class="workflow-progress">{{ $completedSteps }}/{{ count($workflowSteps) }} langkah siap</div>
                </div>
                <div class="workflow-steps">
                    @foreach($workflowSteps as $index => $step)
                        <a href="{{ $step['route'] }}" class="workflow-step {{ $step['done'] ? 'is-done' : '' }}">
                            <span class="workflow-number">
                                @if($step['done'])
                                    <i class="fas fa-check"></i>
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </span>
                            <span>
                                <span class="workflow-step-title">{{ $step['label'] }}</span>
                                <span class="workflow-step-text">{{ $step['text'] }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="filter-section">
            <h2 class="filter-title">
                <i class="fas fa-filter"></i>
                <span class="break-words">Filter Data</span>
            </h2>
            
            <form method="GET" action="{{ route('dashboard') }}">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label class="filter-label">Tahun</label>
                        <input type="number" name="tahun" class="filter-input" placeholder="2025" value="{{ request('tahun') }}" min="2000" max="2100">
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="filter-input" value="{{ request('start_date') }}">
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Tanggal Akhir</label>
                        <input type="date" name="end_date" class="filter-input" value="{{ request('end_date') }}">
                    </div>
                </div>
                
                <div class="filter-buttons">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter"></i>
                        <span>Terapkan Filter</span>
                    </button>
                    
                    <a href="{{ route('dashboard') }}" class="btn-reset">
                        <i class="fas fa-sync-alt"></i>
                        <span>Reset Filter</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="summary-card income">
                <div class="summary-card-header">
                    <div class="summary-card-title">Total Pemasukan</div>
                    <div class="summary-card-icon">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                </div>
                <div class="summary-card-value break-words">Rp{{ number_format(collect($data)->sum('pemasukan'), 0, ',', '.') }}</div>
                <div class="summary-card-description">Seluruh pendapatan sekolah</div>
            </div>

            <div class="summary-card expense">
                <div class="summary-card-header">
                    <div class="summary-card-title">Total Pengeluaran</div>
                    <div class="summary-card-icon">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                </div>
                <div class="summary-card-value break-words">Rp{{ number_format(collect($data)->sum('pengeluaran'), 0, ',', '.') }}</div>
                <div class="summary-card-description">Seluruh biaya operasional</div>
            </div>

            <div class="summary-card balance">
                <div class="summary-card-header">
                    <div class="summary-card-title">Saldo Kas</div>
                    <div class="summary-card-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
                <div class="summary-card-value break-words">Rp{{ number_format(collect($data)->sum('pemasukan') - collect($data)->sum('pengeluaran'), 0, ',', '.') }}</div>
                <div class="summary-card-description">Total dana tersedia</div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <div class="chart-card">
                <div class="chart-title">
                    <i class="fas fa-chart-bar"></i>
                    <span class="break-words">Grafik Keuangan Per Sekolah</span>
                </div>
                <div class="chart-container">
                    <canvas id="financialChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-title">
                    <i class="fas fa-chart-pie"></i>
                    <span class="break-words">Distribusi Pemasukan</span>
                </div>
                <div class="chart-container">
                    <canvas id="incomeDistributionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Schools Section -->
        @if(count($data) > 0)
            <div class="schools-section">
                <h2 class="section-title">
                    <i class="fas fa-school"></i>
                    <span class="break-words">Detail Per Sekolah</span>
                </h2>

                <div class="schools-table-card">
                    <div class="schools-table-wrap">
                        <table class="schools-table">
                            <thead>
                                <tr>
                                    <th>Sekolah</th>
                                    <th class="table-number">Siswa</th>
                                    <th class="table-number">Kelas</th>
                                    <th class="table-number">Pemasukan</th>
                                    <th class="table-number">Pengeluaran</th>
                                    <th class="table-number">Saldo Kas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $item)
                                    <tr>
                                        <td>
                                            <div class="school-cell">
                                                <span class="school-cell-icon">
                                                    <i class="fas fa-graduation-cap"></i>
                                                </span>
                                                <span>
                                                    <span class="school-cell-name">{{ $item['nama'] }}</span>
                                                    <span class="school-cell-subtitle">Ringkasan data keuangan sekolah</span>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="table-number">{{ number_format($item['jumlah_siswa']) }}</td>
                                        <td class="table-number">{{ number_format($item['jumlah_kelas']) }}</td>
                                        <td class="table-money income">Rp{{ number_format($item['pemasukan'], 0, ',', '.') }}</td>
                                        <td class="table-money expense">Rp{{ number_format($item['pengeluaran'], 0, ',', '.') }}</td>
                                        <td class="table-money balance">Rp{{ number_format($item['saldo_kas'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="schools-table-summary">
                        <span>{{ count($data) }} sekolah ditampilkan</span>
                        <span>Total saldo: Rp{{ number_format(collect($data)->sum('saldo_kas'), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="empty-state-text break-words">
                    Belum ada data keuangan sekolah yang tersedia
                </div>
            </div>
        @endif
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Data dari server
    const schoolData = @json($data);

    // ====== PALET WARNA ======
    const colorPalette = [
        '#22c55e', '#10b981', '#3b82f6', '#f59e0b', '#ef4444',
        '#a855f7', '#0ea5e9', '#f43f5e', '#8b5cf6', '#14b8a6',
        '#ec4899', '#6366f1', '#eab308', '#84cc16', '#06b6d4'
    ];

    const backgroundColors = schoolData.map((_, index) => {
        return colorPalette[index % colorPalette.length] + 'cc'; // Tambahkan transparansi 80%
    });

    const borderColors = schoolData.map((_, index) => {
        return colorPalette[index % colorPalette.length];
    });

    // ====== FINANCIAL BAR CHART ======
    const ctxFinancial = document.getElementById('financialChart').getContext('2d');
    const financialChart = new Chart(ctxFinancial, {
        type: 'bar',
        data: {
            labels: schoolData.map(item => {
                // Truncate long school names for better display
                return item.nama.length > 15 ? item.nama.substring(0, 15) + '...' : item.nama;
            }),
            datasets: [
                {
                    label: 'Pemasukan',
                    data: schoolData.map(item => item.pemasukan),
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderColor: 'rgba(34, 197, 94, 1)',
                    borderWidth: 2
                },
                {
                    label: 'Pengeluaran',
                    data: schoolData.map(item => item.pengeluaran),
                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                    borderColor: 'rgba(239, 68, 68, 1)',
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        padding: 15,
                        font: {
                            size: window.innerWidth < 768 ? 10 : 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        title: function(context) {
                            // Show full name in tooltip
                            return schoolData[context[0].dataIndex].nama;
                        },
                        label: function (context) {
                            return context.dataset.label + ': Rp' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 0,
                        font: {
                            size: window.innerWidth < 768 ? 9 : 11
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            // Format large numbers more compactly
                            if (value >= 1000000000) {
                                return 'Rp' + (value / 1000000000).toFixed(1) + 'M';
                            } else if (value >= 1000000) {
                                return 'Rp' + (value / 1000000).toFixed(1) + 'Jt';
                            } else if (value >= 1000) {
                                return 'Rp' + (value / 1000).toFixed(0) + 'rb';
                            }
                            return 'Rp' + value.toLocaleString('id-ID');
                        },
                        font: {
                            size: window.innerWidth < 768 ? 9 : 11
                        }
                    }
                }
            }
        }
    });

    // ====== INCOME DISTRIBUTION DOUGHNUT CHART ======
    const ctxIncome = document.getElementById('incomeDistributionChart').getContext('2d');
    const incomeChart = new Chart(ctxIncome, {
        type: 'doughnut',
        data: {
            labels: schoolData.map(item => {
                // Truncate long school names for legend
                return item.nama.length > 12 ? item.nama.substring(0, 12) + '...' : item.nama;
            }),
            datasets: [{
                data: schoolData.map(item => item.pemasukan),
                backgroundColor: backgroundColors,
                borderColor: borderColors,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 10,
                        font: {
                            size: window.innerWidth < 768 ? 9 : 11
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        title: function(context) {
                            // Show full name in tooltip
                            return schoolData[context[0].dataIndex].nama;
                        },
                        label: function (context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            let value = context.parsed;
                            let formattedValue;
                            
                            // Format large numbers more compactly in tooltip
                            if (value >= 1000000000) {
                                formattedValue = 'Rp' + (value / 1000000000).toFixed(1) + 'M';
                            } else if (value >= 1000000) {
                                formattedValue = 'Rp' + (value / 1000000).toFixed(1) + 'Jt';
                            } else {
                                formattedValue = 'Rp' + value.toLocaleString('id-ID');
                            }
                            
                            return formattedValue + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Handle window resize for responsive charts
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            financialChart.resize();
            incomeChart.resize();
        }, 250);
    });
});
</script>

@endsection
