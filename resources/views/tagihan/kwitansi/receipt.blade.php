@extends('layouts.app')
@include('layouts.sidebar')

@section('title', 'Kwitansi Pembayaran')

@section('content')
@php
    $student = $receipt['student'];
    $school = $student?->sekolah;
    $items = collect($receipt['items']);
    $paidTotal = (float) $receipt['total_paid'];
    $discountTotal = (float) $receipt['total_discount'];
    $billTotal = $paidTotal + $discountTotal;
    $paidAt = $receipt['date'] ? \Carbon\Carbon::parse($receipt['date']) : now();
    $formatRupiah = static fn ($amount) => 'Rp' . number_format((float) $amount, 0, ',', '.');
    $className = trim((string) ($student?->kelas?->nama_kelas ?? ''));
    $classLabel = !$student?->kelas
        ? '-'
        : (in_array($className, ['', '-', '–'], true)
            ? 'Tingkat ' . $student->kelas->tingkat
            : 'Tingkat ' . $student->kelas->tingkat . ' · ' . $className);
@endphp

<style>
    .receipt-page {
        display: grid;
        gap: 18px;
    }

    .receipt-heading,
    .receipt-actions,
    .receipt-brand,
    .receipt-document-header,
    .receipt-meta,
    .receipt-party,
    .receipt-summary-row,
    .receipt-signature-grid {
        display: flex;
        align-items: center;
    }

    .receipt-heading {
        justify-content: space-between;
        gap: 22px;
    }

    .receipt-eyebrow {
        margin: 0 0 3px;
        color: #98a2b3;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: .03em;
    }

    .receipt-title {
        margin: 0;
        color: #101828;
        font-size: clamp(25px, 2.6vw, 32px);
        font-weight: 700;
        letter-spacing: -.04em;
        line-height: 1.16;
    }

    .receipt-subtitle {
        margin: 7px 0 0;
        color: #667085;
        font-size: 12px;
    }

    .receipt-actions {
        justify-content: flex-end;
        gap: 8px;
    }

    .receipt-button {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 7px !important;
        min-height: 38px !important;
        margin: 0 !important;
        padding: 0 13px !important;
        color: #344054 !important;
        background: #fff !important;
        border: 1px solid #d0d5dd !important;
        border-radius: 7px !important;
        box-shadow: none !important;
        font-family: inherit !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        line-height: 1 !important;
        text-decoration: none !important;
        cursor: pointer !important;
    }

    .receipt-button.is-primary {
        color: #fff !important;
        background: #2878f0 !important;
        border-color: #2878f0 !important;
    }

    .receipt-button:hover {
        color: #101828 !important;
        background: #f9fafb !important;
    }

    .receipt-button.is-primary:hover {
        color: #fff !important;
        background: #1768dc !important;
        border-color: #1768dc !important;
    }

    .receipt-paper {
        width: 100%;
        max-width: 940px;
        margin: 0 auto;
        padding: 34px 38px 38px;
        color: #101828;
        background: #fff;
        border: 1px solid #e4e7ec;
        border-radius: 10px;
    }

    .receipt-document-header {
        align-items: flex-start;
        justify-content: space-between;
        gap: 26px;
        padding-bottom: 25px;
        border-bottom: 1px solid #e4e7ec;
    }

    .receipt-brand {
        align-items: flex-start;
        gap: 13px;
    }

    .receipt-brand img {
        flex: 0 0 54px;
        width: 54px;
        height: 54px;
        object-fit: cover;
        border-radius: 50%;
    }

    .receipt-brand strong,
    .receipt-brand span {
        display: block;
    }

    .receipt-brand strong {
        color: #101828;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: -.02em;
    }

    .receipt-brand span {
        max-width: 430px;
        margin-top: 4px;
        color: #667085;
        font-size: 9.5px;
        line-height: 1.55;
    }

    .receipt-number {
        min-width: 210px;
        text-align: right;
    }

    .receipt-number span,
    .receipt-number strong {
        display: block;
    }

    .receipt-number span {
        color: #98a2b3;
        font-size: 8.5px;
        font-weight: 650;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .receipt-number strong {
        margin-top: 5px;
        color: #101828;
        font-size: 16px;
        font-weight: 700;
        letter-spacing: -.02em;
    }

    .receipt-meta {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
        margin: 22px 0;
    }

    .receipt-meta-item {
        padding: 11px 12px;
        background: #f9fafb;
        border-radius: 7px;
    }

    .receipt-meta-item span,
    .receipt-meta-item strong {
        display: block;
    }

    .receipt-meta-item span {
        color: #98a2b3;
        font-size: 8px;
        font-weight: 650;
        text-transform: uppercase;
    }

    .receipt-meta-item strong {
        margin-top: 4px;
        overflow: hidden;
        color: #344054;
        font-size: 10.5px;
        font-weight: 650;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .receipt-party {
        align-items: flex-start;
        justify-content: space-between;
        gap: 28px;
        margin-bottom: 22px;
    }

    .receipt-party-block {
        min-width: 0;
    }

    .receipt-party-block > span {
        display: block;
        color: #98a2b3;
        font-size: 8px;
        font-weight: 650;
        letter-spacing: .06em;
        text-transform: uppercase;
    }

    .receipt-party-block > strong {
        display: block;
        margin-top: 5px;
        color: #101828;
        font-size: 12px;
        font-weight: 650;
    }

    .receipt-party-block > small {
        display: block;
        margin-top: 3px;
        color: #667085;
        font-size: 9.5px;
    }

    .receipt-table-wrap {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        border: 1px solid #e4e7ec;
        border-radius: 8px;
    }

    .receipt-table {
        width: 100% !important;
        min-width: 620px !important;
        border-collapse: collapse !important;
    }

    .receipt-table th {
        height: 36px !important;
        padding: 8px 11px !important;
        color: #667085 !important;
        background: #f9fafb !important;
        border-bottom: 1px solid #e4e7ec !important;
        font-size: 8.5px !important;
        font-weight: 650 !important;
    }

    .receipt-table td {
        height: 46px !important;
        padding: 9px 11px !important;
        color: #344054 !important;
        background: #fff !important;
        border-bottom: 1px solid #eef0f3 !important;
        font-size: 9.5px !important;
    }

    .receipt-table tbody tr:last-child td {
        border-bottom: 0 !important;
    }

    .receipt-table-number {
        width: 42px;
        color: #98a2b3 !important;
        text-align: center;
    }

    .receipt-money {
        font-variant-numeric: tabular-nums;
        text-align: right;
        white-space: nowrap;
    }

    .receipt-summary {
        width: min(100%, 360px);
        margin: 18px 0 0 auto;
        padding: 13px 14px;
        background: #f9fafb;
        border-radius: 8px;
    }

    .receipt-summary-row {
        justify-content: space-between;
        gap: 18px;
        min-height: 27px;
        color: #667085;
        font-size: 9.5px;
    }

    .receipt-summary-row strong {
        color: #344054;
        font-weight: 650;
        font-variant-numeric: tabular-nums;
    }

    .receipt-summary-row.is-total {
        margin-top: 6px;
        padding-top: 10px;
        color: #101828;
        border-top: 1px solid #d0d5dd;
        font-size: 11px;
        font-weight: 650;
    }

    .receipt-summary-row.is-total strong {
        color: #101828;
        font-size: 14px;
    }

    .receipt-spelled {
        margin-top: 18px;
        padding: 11px 12px;
        color: #475467;
        background: #eef5ff;
        border-radius: 7px;
        font-size: 9.5px;
        line-height: 1.5;
    }

    .receipt-spelled strong {
        color: #2878f0;
        font-weight: 650;
    }

    .receipt-note {
        margin-top: 13px;
        color: #667085;
        font-size: 9.5px;
    }

    .receipt-note strong {
        color: #344054;
        font-weight: 650;
    }

    .receipt-signature-grid {
        align-items: flex-end;
        justify-content: space-between;
        gap: 30px;
        margin-top: 36px;
    }

    .receipt-thanks {
        max-width: 360px;
        color: #98a2b3;
        font-size: 9px;
        line-height: 1.6;
    }

    .receipt-signature {
        min-width: 210px;
        color: #475467;
        font-size: 9.5px;
        text-align: center;
    }

    .receipt-signature-line {
        margin-top: 54px;
        padding-top: 6px;
        color: #101828;
        border-top: 1px solid #667085;
        font-weight: 600;
    }

    @media (max-width: 760px) {
        .receipt-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .receipt-actions {
            display: grid;
            grid-template-columns: 1fr;
            width: 100%;
        }

        .receipt-button {
            width: 100% !important;
        }

        .receipt-paper {
            padding: 22px 18px 26px;
        }

        .receipt-document-header,
        .receipt-party,
        .receipt-signature-grid {
            align-items: flex-start;
            flex-direction: column;
        }

        .receipt-number {
            min-width: 0;
            text-align: left;
        }

        .receipt-meta {
            grid-template-columns: 1fr;
        }

        .receipt-table-wrap {
            overflow: visible;
            border: 0;
        }

        body:has(.app-sidebar) .receipt-page .receipt-table {
            display: block !important;
            min-width: 0 !important;
            background: transparent !important;
        }

        .receipt-table thead {
            display: none !important;
        }

        .receipt-table tbody,
        .receipt-table tr,
        .receipt-table td {
            display: block !important;
            width: 100% !important;
        }

        .receipt-table tr {
            margin-bottom: 8px;
            overflow: hidden;
            border: 1px solid #e4e7ec;
            border-radius: 7px;
        }

        .receipt-table tr:last-child {
            margin-bottom: 0;
        }

        .receipt-table td {
            display: grid !important;
            grid-template-columns: 110px minmax(0, 1fr) !important;
            align-items: center !important;
            min-height: 34px !important;
            height: auto !important;
            padding: 7px 9px !important;
            text-align: right !important;
            border-bottom: 1px solid #eef0f3 !important;
        }

        .receipt-table td::before {
            color: #98a2b3;
            font-size: 8px;
            font-weight: 650;
            text-align: left;
            text-transform: uppercase;
            content: attr(data-label);
        }

        .receipt-table td:last-child {
            border-bottom: 0 !important;
        }

        .receipt-table-number {
            display: none !important;
        }

        .receipt-summary {
            width: 100%;
        }

        .receipt-signature {
            align-self: flex-end;
            width: 210px;
        }
    }

    @media print {
        @page {
            size: A4 portrait;
            margin: 12mm;
        }

        body {
            color: #000 !important;
            background: #fff !important;
        }

        .no-print,
        .app-sidebar,
        .app-topbar,
        .sidebar-overlay-bg {
            display: none !important;
        }

        body:has(.app-sidebar) .main-content {
            width: 100% !important;
            max-width: 100% !important;
            min-height: auto !important;
            margin: 0 !important;
        }

        body:has(.app-sidebar) .content-area {
            max-width: none !important;
            padding: 0 !important;
        }

        .receipt-page {
            display: block !important;
        }

        .receipt-paper {
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
            border: 0 !important;
            border-radius: 0 !important;
        }

        .receipt-table-wrap {
            overflow: visible !important;
        }

        .receipt-table {
            display: table !important;
            min-width: 0 !important;
        }

        .receipt-table thead {
            display: table-header-group !important;
        }

        .receipt-table tbody {
            display: table-row-group !important;
        }

        .receipt-table tr {
            display: table-row !important;
            border: 0 !important;
        }

        .receipt-table th,
        .receipt-table td {
            display: table-cell !important;
            width: auto !important;
            text-align: inherit !important;
        }

        .receipt-table td::before {
            display: none !important;
        }

        .receipt-table .receipt-table-number {
            display: table-cell !important;
        }
    }
</style>

<div class="main-content">
    @include('layouts.header')

    <div class="content-area">
        <div class="receipt-page">
            <header class="receipt-heading no-print">
                <div>
                    <p class="receipt-eyebrow">Pembayaran selesai</p>
                    <h1 class="receipt-title" data-page-title>Kwitansi pembayaran</h1>
                    <p class="receipt-subtitle">Periksa rincian transaksi sebelum mencetak atau menyerahkan kwitansi.</p>
                </div>
                <div class="receipt-actions">
                    <a class="receipt-button" href="{{ route('tagihan.proses.siswa', $student->id) }}">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali ke siswa</span>
                    </a>
                    <button class="receipt-button is-primary" type="button" onclick="window.print()">
                        <i class="fas fa-print"></i>
                        <span>Cetak kwitansi</span>
                    </button>
                </div>
            </header>

            <article class="receipt-paper">
                <header class="receipt-document-header">
                    <div class="receipt-brand">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo Permata Insani">
                        <div>
                            <strong>Yayasan Kemilau Permata Insani</strong>
                            <span>{{ $school?->nama_sekolah ?? 'Permata Insani Islamic School' }}</span>
                            <span>{{ $school?->alamat ?? 'Jl. Abdul Muis RT 09, Lingkar Selatan, Paal Merah, Jambi' }}</span>
                        </div>
                    </div>
                    <div class="receipt-number">
                        <span>Kwitansi pembayaran</span>
                        <strong>{{ $receipt['number'] }}</strong>
                    </div>
                </header>

                <section class="receipt-meta">
                    <div class="receipt-meta-item">
                        <span>Tanggal bayar</span>
                        <strong>{{ $paidAt->translatedFormat('d F Y') }}</strong>
                    </div>
                    <div class="receipt-meta-item">
                        <span>Metode</span>
                        <strong>{{ $receipt['method'] }}</strong>
                    </div>
                    <div class="receipt-meta-item">
                        <span>Jumlah item</span>
                        <strong>{{ $items->count() }} tagihan</strong>
                    </div>
                </section>

                <section class="receipt-party">
                    <div class="receipt-party-block">
                        <span>Diterima dari</span>
                        <strong>{{ $student?->nama ?? 'Nama siswa tidak tersedia' }}</strong>
                        <small>NIS {{ $student?->nis ?? '-' }} · {{ $classLabel }}</small>
                    </div>
                    <div class="receipt-party-block">
                        <span>Sekolah</span>
                        <strong>{{ $school?->nama_sekolah ?? '-' }}</strong>
                        <small>{{ $school?->kode_sekolah ?? '' }}</small>
                    </div>
                </section>

                <div class="receipt-table-wrap">
                    <table class="receipt-table">
                        <thead>
                            <tr>
                                <th class="receipt-table-number">No.</th>
                                <th>Rincian pembayaran</th>
                                <th class="receipt-money">Nilai tagihan</th>
                                <th class="receipt-money">Potongan</th>
                                <th class="receipt-money">Diterima</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td class="receipt-table-number" data-label="No.">{{ $loop->iteration }}</td>
                                    <td data-label="Pembayaran">{{ $item['name'] }}</td>
                                    <td class="receipt-money" data-label="Nilai tagihan">{{ $formatRupiah($item['amount'] + $item['discount']) }}</td>
                                    <td class="receipt-money" data-label="Potongan">{{ $item['discount'] > 0 ? $formatRupiah($item['discount']) : '-' }}</td>
                                    <td class="receipt-money" data-label="Diterima"><strong>{{ $formatRupiah($item['amount']) }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <section class="receipt-summary">
                    <div class="receipt-summary-row">
                        <span>Total tagihan</span>
                        <strong>{{ $formatRupiah($billTotal) }}</strong>
                    </div>
                    @if($discountTotal > 0)
                        <div class="receipt-summary-row">
                            <span>Total potongan</span>
                            <strong>- {{ $formatRupiah($discountTotal) }}</strong>
                        </div>
                    @endif
                    <div class="receipt-summary-row is-total">
                        <span>Jumlah diterima</span>
                        <strong>{{ $formatRupiah($paidTotal) }}</strong>
                    </div>
                </section>

                <div class="receipt-spelled">
                    <strong>Terbilang:</strong>
                    {{ ucwords(trim(terbilang((int) $paidTotal))) }} rupiah
                </div>

                @if(!empty($receipt['note']))
                    <p class="receipt-note"><strong>Keterangan:</strong> {{ $receipt['note'] }}</p>
                @endif

                <footer class="receipt-signature-grid">
                    <p class="receipt-thanks">
                        Terima kasih. Kwitansi ini merupakan bukti pembayaran yang sah dan dibuat oleh Sistem Pembayaran Permata Insani.
                    </p>
                    <div class="receipt-signature">
                        <span>Jambi, {{ $paidAt->translatedFormat('d F Y') }}</span>
                        <div class="receipt-signature-line">Petugas administrasi</div>
                    </div>
                </footer>
            </article>
        </div>
    </div>
</div>
@endsection
