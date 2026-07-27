@extends('layouts.app')
@include('layouts.sidebar')

@section('title', 'Proses Tagihan Siswa')

@section('content')
@php
    $adminUser = Auth::guard('web')->user();
    $canGenerate = $adminUser?->hasPermission('tagihan.manage') ?? false;
    $canPay = $adminUser?->hasPermission('pembayaran.process') ?? false;
    $formatRupiah = static fn ($amount) => 'Rp' . number_format((float) $amount, 0, ',', '.');
    $className = trim((string) ($siswa->kelas?->nama_kelas ?? ''));
    $classLabel = !$siswa->kelas
        ? 'Kelas belum diatur'
        : (in_array($className, ['', '-', '–'], true)
            ? 'Tingkat ' . $siswa->kelas->tingkat
            : 'Tingkat ' . $siswa->kelas->tingkat . ' · ' . $className);
    $studentInitial = strtoupper(substr($siswa->nama, 0, 1));
    $defaultOpenGroup = collect($tagihanList)->search(
        fn ($item) => $item['is_grouped'] && (float) $item['sisa_bayar'] > 0
    );
    if ($defaultOpenGroup === false) {
        $defaultOpenGroup = collect($tagihanList)->search(fn ($item) => $item['is_grouped']);
    }
@endphp

<style>
    .payment-page {
        display: grid;
        gap: 18px;
    }

    .payment-heading,
    .payment-heading-actions,
    .payment-student-card,
    .payment-student-profile,
    .payment-student-details,
    .payment-panel-heading,
    .payment-step-title,
    .payment-selection-toolbar,
    .payment-group-header,
    .payment-group-title,
    .payment-group-meta,
    .payment-group-select,
    .payment-summary-bar,
    .payment-summary-copy,
    .payment-selected-item,
    .payment-selected-main,
    .payment-selected-actions,
    .payment-form-actions,
    .payment-discount-info,
    .payment-alert {
        display: flex;
        align-items: center;
    }

    .payment-heading {
        align-items: flex-end;
        justify-content: space-between;
        gap: 24px;
    }

    .payment-eyebrow {
        margin: 0 0 3px;
        color: #98a2b3;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: .03em;
    }

    .payment-title {
        margin: 0;
        color: #101828;
        font-size: clamp(25px, 2.6vw, 32px);
        font-weight: 700;
        letter-spacing: -.04em;
        line-height: 1.16;
    }

    .payment-subtitle {
        margin: 7px 0 0;
        color: #667085;
        font-size: 12px;
    }

    .payment-heading-actions {
        justify-content: flex-end;
        gap: 8px;
    }

    .payment-button,
    .payment-group-toggle,
    .payment-quick-button {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 7px !important;
        margin: 0 !important;
        color: #344054 !important;
        background: #fff !important;
        border: 1px solid #d0d5dd !important;
        border-radius: 7px !important;
        box-shadow: none !important;
        font-family: inherit !important;
        font-weight: 600 !important;
        line-height: 1 !important;
        text-decoration: none !important;
        cursor: pointer !important;
    }

    .payment-button {
        min-height: 38px !important;
        padding: 0 13px !important;
        font-size: 11px !important;
    }

    .payment-button.is-primary {
        color: #fff !important;
        background: #2878f0 !important;
        border-color: #2878f0 !important;
    }

    .payment-button:hover,
    .payment-quick-button:hover {
        color: #101828 !important;
        background: #f9fafb !important;
    }

    .payment-button.is-primary:hover {
        color: #fff !important;
        background: #1768dc !important;
        border-color: #1768dc !important;
    }

    .payment-button:disabled {
        color: #98a2b3 !important;
        background: #f2f4f7 !important;
        border-color: #e4e7ec !important;
        cursor: not-allowed !important;
    }

    .payment-alert {
        gap: 10px;
        padding: 11px 13px;
        color: #b42318;
        background: #fef3f2;
        border: 1px solid #fecdca;
        border-radius: 8px;
        font-size: 10.5px;
    }

    .payment-alert i {
        display: grid;
        place-items: center;
        flex: 0 0 30px;
        width: 30px;
        height: 30px;
        background: rgba(255, 255, 255, .7);
        border-radius: 7px;
    }

    .payment-student-card {
        justify-content: space-between;
        gap: 20px;
        padding: 15px 18px;
        background: #fff;
        border: 1px solid #e4e7ec;
        border-radius: 10px;
    }

    .payment-student-profile {
        min-width: 0;
        gap: 11px;
    }

    .payment-student-avatar {
        display: grid;
        place-items: center;
        flex: 0 0 40px;
        width: 40px;
        height: 40px;
        color: #2878f0;
        background: #eef5ff;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 700;
    }

    .payment-student-copy {
        min-width: 0;
    }

    .payment-student-copy strong,
    .payment-student-copy span {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .payment-student-copy strong {
        color: #101828;
        font-size: 12px;
        font-weight: 650;
    }

    .payment-student-copy span {
        margin-top: 3px;
        color: #667085;
        font-size: 9.5px;
    }

    .payment-student-details {
        justify-content: flex-end;
        gap: 26px;
    }

    .payment-student-detail {
        min-width: 90px;
    }

    .payment-student-detail span,
    .payment-student-detail strong {
        display: block;
    }

    .payment-student-detail span {
        color: #98a2b3;
        font-size: 8.5px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .payment-student-detail strong {
        margin-top: 3px;
        max-width: 210px;
        overflow: hidden;
        color: #344054;
        font-size: 10.5px;
        font-weight: 600;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .payment-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }

    .payment-stat {
        padding: 15px 16px;
        background: #fff;
        border: 1px solid #e4e7ec;
        border-radius: 9px;
    }

    .payment-stat span,
    .payment-stat strong {
        display: block;
    }

    .payment-stat span {
        color: #667085;
        font-size: 9.5px;
    }

    .payment-stat strong {
        margin-top: 5px;
        overflow: hidden;
        color: #101828;
        font-size: clamp(18px, 1.8vw, 23px);
        font-weight: 700;
        letter-spacing: -.035em;
        line-height: 1.15;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .payment-stat.is-success strong {
        color: #087451;
    }

    .payment-stat.is-danger strong {
        color: #b42318;
    }

    .payment-panel {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e4e7ec;
        border-radius: 10px;
    }

    .payment-panel[hidden] {
        display: none !important;
    }

    .payment-panel-heading {
        justify-content: space-between;
        gap: 18px;
        min-height: 60px;
        padding: 13px 18px;
        border-bottom: 1px solid #e4e7ec;
    }

    .payment-step-title {
        gap: 9px;
    }

    .payment-step-number {
        display: grid;
        place-items: center;
        width: 25px;
        height: 25px;
        color: #2878f0;
        background: #eef5ff;
        border-radius: 7px;
        font-size: 10px;
        font-weight: 700;
    }

    .payment-panel-title,
    .payment-panel-context {
        margin: 0;
    }

    .payment-panel-title {
        color: #101828;
        font-size: 13px;
        font-weight: 650;
    }

    .payment-panel-context {
        margin-top: 3px;
        color: #667085;
        font-size: 9.5px;
    }

    .payment-selection-toolbar {
        justify-content: space-between;
        gap: 16px;
        min-height: 48px;
        padding: 10px 18px;
        background: #fafbfc;
        border-bottom: 1px solid #e4e7ec;
    }

    .payment-check-label,
    .payment-group-select {
        margin: 0 !important;
        color: #475467 !important;
        font-size: 9.5px !important;
        font-weight: 600 !important;
        cursor: pointer;
    }

    .payment-check-label {
        display: inline-flex !important;
        align-items: center;
        gap: 7px;
    }

    .payment-check,
    .payment-group-check {
        width: 15px !important;
        height: 15px !important;
        margin: 0 !important;
        padding: 0 !important;
        accent-color: #2878f0;
        cursor: pointer;
    }

    .payment-selection-hint {
        color: #98a2b3;
        font-size: 9px;
    }

    .payment-bill-list {
        display: grid;
        gap: 10px;
        padding: 12px;
        background: #f9fafb;
    }

    .payment-group,
    .payment-single {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e4e7ec;
        border-radius: 8px;
    }

    .payment-group-header {
        justify-content: space-between;
        gap: 14px;
        min-height: 58px;
        padding: 10px 12px;
    }

    .payment-group-title {
        min-width: 0;
        gap: 9px;
    }

    .payment-group-toggle {
        flex: 0 0 28px;
        width: 28px !important;
        height: 28px !important;
        min-height: 0 !important;
        padding: 0 !important;
        color: #667085 !important;
        border: 0 !important;
        background: #f2f4f7 !important;
        font-size: 9px !important;
    }

    .payment-group.is-open .payment-group-toggle i {
        transform: rotate(180deg);
    }

    .payment-group-toggle i {
        transition: transform .15s ease;
    }

    .payment-group-copy {
        min-width: 0;
    }

    .payment-group-copy strong,
    .payment-group-copy span {
        display: block;
    }

    .payment-group-copy strong {
        overflow: hidden;
        color: #101828;
        font-size: 10.5px;
        font-weight: 650;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .payment-group-copy span {
        margin-top: 2px;
        color: #98a2b3;
        font-size: 8.5px;
    }

    .payment-group-meta {
        justify-content: flex-end;
        gap: 18px;
    }

    .payment-group-amount {
        min-width: 110px;
        text-align: right;
    }

    .payment-group-amount span,
    .payment-group-amount strong {
        display: block;
    }

    .payment-group-amount span {
        color: #98a2b3;
        font-size: 8px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .payment-group-amount strong {
        margin-top: 2px;
        color: #b42318;
        font-size: 10.5px;
        font-weight: 650;
    }

    .payment-group-select {
        gap: 7px;
        min-height: 30px;
        padding: 0 9px;
        background: #f9fafb;
        border: 1px solid #e4e7ec;
        border-radius: 6px;
        white-space: nowrap;
    }

    .payment-group-body[hidden] {
        display: none !important;
    }

    .payment-bill-table {
        width: 100% !important;
        min-width: 760px !important;
        border-collapse: collapse !important;
    }

    .payment-bill-table th {
        height: 34px !important;
        padding: 7px 10px !important;
        color: #667085 !important;
        background: #f9fafb !important;
        border-top: 1px solid #e4e7ec !important;
        border-bottom: 1px solid #e4e7ec !important;
        font-size: 8.5px !important;
        font-weight: 600 !important;
    }

    .payment-bill-table td {
        height: 47px !important;
        padding: 8px 10px !important;
        color: #344054 !important;
        background: #fff !important;
        border-bottom: 1px solid #eef0f3 !important;
        font-size: 9.5px !important;
    }

    .payment-bill-table tbody tr:last-child td {
        border-bottom: 0 !important;
    }

    .payment-bill-table tbody tr:hover td {
        background: #fbfcfe !important;
    }

    .payment-bill-check-cell {
        width: 37px;
        text-align: center !important;
    }

    .payment-money {
        font-variant-numeric: tabular-nums;
        white-space: nowrap;
    }

    .payment-money.is-remaining {
        color: #b42318;
        font-weight: 650;
    }

    .payment-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        min-height: 22px;
        padding: 0 7px;
        color: #b42318;
        background: #fef3f2;
        border-radius: 999px;
        font-size: 8px;
        font-weight: 650;
        white-space: nowrap;
    }

    .payment-status::before {
        width: 5px;
        height: 5px;
        background: #f04438;
        border-radius: 50%;
        content: "";
    }

    .payment-status.is-paid {
        color: #087451;
        background: #ecfdf3;
    }

    .payment-status.is-paid::before {
        background: #12b76a;
    }

    .payment-single {
        display: grid;
        grid-template-columns: 36px minmax(180px, 1.4fr) 100px 120px 120px 105px;
        align-items: center;
        min-height: 58px;
        padding: 8px 12px;
        gap: 10px;
    }

    .payment-single > div {
        min-width: 0;
    }

    .payment-single-label {
        display: none;
        color: #98a2b3;
        font-size: 8px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .payment-single-name strong,
    .payment-single-name span,
    .payment-single-value strong,
    .payment-single-value span {
        display: block;
    }

    .payment-single-name strong {
        overflow: hidden;
        color: #101828;
        font-size: 10.5px;
        font-weight: 650;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .payment-single-name span,
    .payment-single-value span {
        margin-top: 2px;
        color: #98a2b3;
        font-size: 8.5px;
    }

    .payment-single-value strong {
        color: #344054;
        font-size: 9.5px;
        font-weight: 600;
    }

    .payment-summary-bar {
        position: sticky;
        top: 68px;
        z-index: 20;
        justify-content: space-between;
        gap: 18px;
        min-height: 64px;
        padding: 11px 18px;
        background: rgba(255, 255, 255, .97);
        border-top: 1px solid #e4e7ec;
        backdrop-filter: blur(10px);
    }

    .payment-summary-copy {
        gap: 22px;
    }

    .payment-summary-item span,
    .payment-summary-item strong {
        display: block;
    }

    .payment-summary-item span {
        color: #98a2b3;
        font-size: 8.5px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .payment-summary-item strong {
        margin-top: 3px;
        color: #101828;
        font-size: 11px;
        font-weight: 650;
    }

    .payment-form-body {
        display: grid;
        gap: 16px;
        padding: 16px 18px 18px;
    }

    .payment-selected-list {
        display: grid;
        gap: 8px;
    }

    .payment-selected-item {
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        padding: 11px 12px;
        background: #f9fafb;
        border: 1px solid #e4e7ec;
        border-radius: 8px;
    }

    .payment-selected-main {
        align-items: flex-start;
        min-width: 0;
        gap: 9px;
    }

    .payment-selected-icon {
        display: grid;
        place-items: center;
        flex: 0 0 30px;
        width: 30px;
        height: 30px;
        color: #2878f0;
        background: #eef5ff;
        border-radius: 7px;
        font-size: 10px;
    }

    .payment-selected-copy {
        min-width: 0;
    }

    .payment-selected-copy strong,
    .payment-selected-copy span {
        display: block;
    }

    .payment-selected-copy strong {
        overflow: hidden;
        color: #101828;
        font-size: 10.5px;
        font-weight: 650;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .payment-selected-copy span {
        margin-top: 3px;
        color: #667085;
        font-size: 9px;
    }

    .payment-selected-actions {
        align-items: flex-end;
        gap: 7px;
    }

    .payment-amount-field {
        min-width: 180px;
    }

    .payment-amount-field label {
        margin-bottom: 4px !important;
        color: #667085 !important;
        font-size: 8.5px !important;
    }

    .payment-amount-field input {
        width: 180px !important;
        height: 34px !important;
        margin: 0 !important;
        padding: 0 10px !important;
        color: #101828 !important;
        background: #fff !important;
        border: 1px solid #d0d5dd !important;
        border-radius: 6px !important;
        box-shadow: none !important;
        font-size: 10.5px !important;
        font-weight: 600 !important;
    }

    .payment-amount-field input.is-invalid {
        border-color: #f04438 !important;
    }

    .payment-input-error {
        display: none !important;
        margin-top: 4px;
        color: #b42318;
        font-size: 8.5px;
    }

    .payment-input-error.is-visible {
        display: block !important;
    }

    .payment-quick-button {
        min-height: 34px !important;
        padding: 0 9px !important;
        font-size: 9px !important;
        white-space: nowrap;
    }

    .payment-discount-info {
        display: none;
        grid-column: 1 / -1;
        gap: 7px;
        width: 100%;
        margin-top: 7px;
        padding: 8px 9px;
        color: #087451;
        background: #ecfdf3;
        border-radius: 6px;
        font-size: 8.5px;
    }

    .payment-discount-info.is-visible {
        display: flex;
    }

    .payment-discount-info.is-warning {
        color: #b54708;
        background: #fffaeb;
    }

    .payment-details-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 11px;
        padding-top: 2px;
    }

    .payment-field.is-full {
        grid-column: 1 / -1;
    }

    .payment-field label {
        margin-bottom: 5px !important;
        color: #475467 !important;
        font-size: 9.5px !important;
    }

    .payment-field input,
    .payment-field select,
    .payment-field textarea {
        width: 100% !important;
        margin: 0 !important;
        color: #344054 !important;
        background: #fff !important;
        border: 1px solid #d0d5dd !important;
        border-radius: 7px !important;
        box-shadow: none !important;
        font-size: 10.5px !important;
    }

    .payment-field input,
    .payment-field select {
        height: 37px !important;
    }

    .payment-field textarea {
        min-height: 72px !important;
        padding: 9px 10px !important;
        resize: vertical;
    }

    .payment-form-footer {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        align-items: end;
        gap: 16px;
        padding-top: 4px;
    }

    .payment-form-total span,
    .payment-form-total strong {
        display: block;
    }

    .payment-form-total span {
        color: #667085;
        font-size: 9px;
    }

    .payment-form-total strong {
        margin-top: 3px;
        color: #101828;
        font-size: 20px;
        font-weight: 700;
        letter-spacing: -.035em;
    }

    .payment-form-actions {
        justify-content: flex-end;
        gap: 8px;
    }

    .payment-empty {
        display: grid;
        place-items: center;
        min-height: 250px;
        padding: 30px;
        color: #667085;
        text-align: center;
    }

    .payment-empty i {
        display: grid;
        place-items: center;
        width: 42px;
        height: 42px;
        margin: 0 auto 10px;
        color: #667085;
        background: #f2f4f7;
        border-radius: 9px;
        font-size: 14px;
    }

    .payment-empty strong,
    .payment-empty span {
        display: block;
    }

    .payment-empty strong {
        color: #344054;
        font-size: 12px;
    }

    .payment-empty span {
        max-width: 350px;
        margin-top: 4px;
        font-size: 9.5px;
    }

    .payment-modal-list {
        display: grid;
        gap: 7px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .payment-modal-list li {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #344054;
        font-size: 10.5px;
    }

    .payment-modal-list i {
        color: #12a06a;
        font-size: 9px;
    }

    .payment-modal-note {
        display: flex;
        gap: 8px;
        margin-top: 14px;
        padding: 9px 10px;
        color: #854a0e;
        background: #fffaeb;
        border: 1px solid #fef0c7;
        border-radius: 7px;
        font-size: 9.5px;
    }

    @media (max-width: 1050px) {
        .payment-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .payment-heading-actions {
            width: 100%;
            justify-content: flex-start;
        }

        .payment-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .payment-group-meta {
            gap: 10px;
        }

        .payment-single {
            grid-template-columns: 34px minmax(170px, 1fr) 90px 110px 110px;
        }

        .payment-single .payment-single-period {
            display: none;
        }
    }

    @media (max-width: 760px) {
        .payment-page {
            gap: 14px;
        }

        .payment-heading-actions {
            display: grid;
            grid-template-columns: 1fr;
        }

        .payment-button {
            width: 100% !important;
        }

        .payment-student-card,
        .payment-panel-heading,
        .payment-selection-toolbar,
        .payment-summary-bar,
        .payment-selected-item,
        .payment-form-footer {
            align-items: stretch;
            flex-direction: column;
        }

        .payment-summary-bar {
            top: 62px;
        }

        .payment-student-details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            width: 100%;
            gap: 10px;
            padding-top: 12px;
            border-top: 1px solid #eef0f3;
        }

        .payment-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .payment-stat {
            padding: 12px;
        }

        .payment-stat strong {
            font-size: 17px;
        }

        .payment-selection-hint {
            display: none;
        }

        .payment-group-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .payment-group-title {
            width: 100%;
        }

        .payment-group-meta {
            justify-content: space-between;
            width: 100%;
            padding-left: 37px;
        }

        .payment-group-amount {
            min-width: 0;
            text-align: left;
        }

        .payment-group-select {
            margin-left: auto !important;
        }

        .payment-group-body {
            padding: 8px;
            background: #f9fafb;
            border-top: 1px solid #e4e7ec;
        }

        body:has(.app-sidebar) .payment-page .payment-bill-table {
            display: block !important;
            min-width: 0 !important;
            background: transparent !important;
        }

        .payment-bill-table thead {
            display: none !important;
        }

        .payment-bill-table tbody,
        .payment-bill-table tr,
        .payment-bill-table td {
            display: block !important;
            width: 100% !important;
        }

        .payment-bill-table tr {
            display: grid !important;
            grid-template-columns: 34px minmax(0, 1fr) !important;
            margin-bottom: 7px;
            overflow: hidden;
            background: #fff;
            border: 1px solid #e4e7ec;
            border-radius: 7px;
        }

        .payment-bill-table tr:last-child {
            margin-bottom: 0;
        }

        .payment-bill-table td {
            display: grid !important;
            grid-template-columns: 92px minmax(0, 1fr) !important;
            align-items: center !important;
            min-height: 34px !important;
            height: auto !important;
            padding: 7px 9px !important;
            text-align: right !important;
            border-bottom: 1px solid #eef0f3 !important;
        }

        .payment-bill-table td::before {
            color: #98a2b3;
            font-size: 8px;
            font-weight: 600;
            text-align: left;
            text-transform: uppercase;
            content: attr(data-label);
        }

        .payment-bill-table td.payment-bill-check-cell {
            display: grid !important;
            grid-column: 1;
            grid-row: 1 / span 6;
            place-items: center !important;
            padding: 0 !important;
            border-right: 1px solid #eef0f3 !important;
            border-bottom: 0 !important;
        }

        .payment-bill-table td.payment-bill-check-cell::before {
            display: none;
        }

        .payment-bill-table td:not(.payment-bill-check-cell) {
            grid-column: 2;
        }

        .payment-single {
            grid-template-columns: 30px minmax(0, 1fr);
            padding: 10px;
        }

        .payment-single > div:not(.payment-single-check):not(.payment-single-name) {
            display: grid;
            grid-column: 2;
            grid-template-columns: 92px minmax(0, 1fr);
            align-items: center;
            min-height: 28px;
            text-align: right;
        }

        .payment-single-label {
            display: block;
            text-align: left;
        }

        .payment-single-name {
            grid-column: 2;
        }

        .payment-single-check {
            grid-row: 1 / span 5;
            align-self: stretch;
            display: grid;
            place-items: center;
            border-right: 1px solid #eef0f3;
        }

        .payment-summary-copy {
            justify-content: space-between;
        }

        .payment-summary-bar .payment-button {
            width: 100% !important;
        }

        .payment-selected-item {
            display: grid;
        }

        .payment-selected-actions {
            align-items: flex-end;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            width: 100%;
        }

        .payment-amount-field,
        .payment-amount-field input {
            width: 100% !important;
            min-width: 0;
        }

        .payment-details-grid,
        .payment-form-footer {
            grid-template-columns: 1fr;
        }

        .payment-field.is-full {
            grid-column: auto;
        }

        .payment-form-actions {
            display: grid;
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 360px) {
        .payment-stats,
        .payment-student-details {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="main-content">
    @include('layouts.header')

    <div class="content-area">
        <div class="payment-page">
            @if($errors->any())
                <div class="payment-alert" role="alert">
                    <i class="fas fa-circle-exclamation"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <header class="payment-heading">
                <div>
                    <p class="payment-eyebrow">Tagihan · Proses pembayaran</p>
                    <h1 class="payment-title" data-page-title>{{ $siswa->nama }}</h1>
                    <p class="payment-subtitle">Pilih satu atau beberapa tagihan, lalu atur jumlah pembayarannya.</p>
                </div>

                <div class="payment-heading-actions">
                    <a class="payment-button" href="{{ route('tagihan.index.grouped', ['sekolah' => $siswa->id_sekolah, 'kelas' => $siswa->kelas_id]) }}">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali ke tagihan</span>
                    </a>
                    @if($canGenerate)
                        <button class="payment-button is-primary" type="button" data-bs-toggle="modal" data-bs-target="#generateStudentModal">
                            <i class="fas fa-file-circle-plus"></i>
                            <span>Buat tagihan siswa</span>
                        </button>
                    @endif
                </div>
            </header>

            <section class="payment-student-card" aria-label="Informasi siswa">
                <div class="payment-student-profile">
                    <span class="payment-student-avatar">{{ $studentInitial }}</span>
                    <span class="payment-student-copy">
                        <strong>{{ $siswa->nama }}</strong>
                        <span>NIS {{ $siswa->nis }}</span>
                    </span>
                </div>
                <div class="payment-student-details">
                    <div class="payment-student-detail">
                        <span>Kelas</span>
                        <strong>{{ $classLabel }}</strong>
                    </div>
                    <div class="payment-student-detail">
                        <span>Sekolah</span>
                        <strong>{{ $siswa->sekolah?->nama_sekolah ?? '-' }}</strong>
                    </div>
                </div>
            </section>

            <section class="payment-stats" aria-label="Ringkasan tagihan siswa">
                <article class="payment-stat">
                    <span>Total tagihan</span>
                    <strong>{{ number_format($studentSummary['total_tagihan'], 0, ',', '.') }}</strong>
                </article>
                <article class="payment-stat">
                    <span>Total nominal</span>
                    <strong>{{ $formatRupiah($studentSummary['total_nominal']) }}</strong>
                </article>
                <article class="payment-stat is-success">
                    <span>Sudah dibayar</span>
                    <strong>{{ $formatRupiah($studentSummary['total_dibayar']) }}</strong>
                </article>
                <article class="payment-stat is-danger">
                    <span>Sisa tagihan</span>
                    <strong>{{ $formatRupiah($studentSummary['sisa_bayar']) }}</strong>
                </article>
            </section>

            <section class="payment-panel" id="billSelectionPanel">
                <div class="payment-panel-heading">
                    <div class="payment-step-title">
                        <span class="payment-step-number">1</span>
                        <div>
                            <h2 class="payment-panel-title">Pilih tagihan</h2>
                            <p class="payment-panel-context">{{ $studentSummary['belum_lunas'] }} tagihan masih perlu dibayar</p>
                        </div>
                    </div>
                </div>

                @if(count($tagihanList) > 0)
                    <div class="payment-selection-toolbar">
                        <label class="payment-check-label" for="selectAllBills">
                            <input class="payment-check" id="selectAllBills" type="checkbox">
                            <span>Pilih semua tagihan belum lunas</span>
                        </label>
                        <span class="payment-selection-hint">Tagihan lunas tidak dapat dipilih kembali.</span>
                    </div>

                    <div class="payment-summary-bar">
                        <div class="payment-summary-copy">
                            <div class="payment-summary-item">
                                <span>Dipilih</span>
                                <strong><span id="selectedBillCount">0</span> tagihan</strong>
                            </div>
                            <div class="payment-summary-item">
                                <span>Total sisa</span>
                                <strong id="selectedBillTotal">Rp0</strong>
                            </div>
                        </div>
                        @if($canPay)
                            <button class="payment-button is-primary" id="continuePaymentButton" type="button" disabled>
                                <i class="fas fa-arrow-right"></i>
                                <span>Lanjut pembayaran</span>
                            </button>
                        @endif
                    </div>

                    <div class="payment-bill-list">
                        @foreach($tagihanList as $tagihan)
                            @if($tagihan['is_grouped'])
                                @php
                                    $groupId = 'payment-group-' . $loop->index;
                                    $groupOpen = $loop->index === $defaultOpenGroup;
                                    $outstandingMonths = collect($tagihan['bulan_tagihan'])
                                        ->filter(fn ($bulan) => (float) $bulan['sisa_bayar'] > 0);
                                @endphp
                                <article class="payment-group {{ $groupOpen ? 'is-open' : '' }}" data-payment-group>
                                    <header class="payment-group-header">
                                        <div class="payment-group-title">
                                            <button class="payment-group-toggle" type="button" data-group-toggle="{{ $groupId }}" aria-expanded="{{ $groupOpen ? 'true' : 'false' }}">
                                                <i class="fas fa-chevron-down"></i>
                                            </button>
                                            <span class="payment-group-copy">
                                                <strong>{{ $tagihan['nama_tagihan'] }}</strong>
                                                <span>{{ count($tagihan['bulan_tagihan']) }} periode · {{ ucfirst($tagihan['tipe']) }}</span>
                                            </span>
                                        </div>

                                        <div class="payment-group-meta">
                                            <span class="payment-group-amount">
                                                <span>Sisa</span>
                                                <strong>{{ $formatRupiah(max(0, $tagihan['sisa_bayar'])) }}</strong>
                                            </span>
                                            @if($outstandingMonths->isNotEmpty())
                                                <label class="payment-group-select">
                                                    <input class="payment-group-check" type="checkbox" data-select-group="{{ $groupId }}">
                                                    <span>Pilih grup</span>
                                                </label>
                                            @else
                                                <span class="payment-status is-paid">Lunas</span>
                                            @endif
                                        </div>
                                    </header>

                                    <div class="payment-group-body" id="{{ $groupId }}" {{ $groupOpen ? '' : 'hidden' }}>
                                        <table class="payment-bill-table">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Periode</th>
                                                    <th>Jatuh tempo</th>
                                                    <th>Nominal</th>
                                                    <th>Dibayar</th>
                                                    <th>Sisa</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($tagihan['bulan_tagihan'] as $bulan)
                                                    @php
                                                        $monthOutstanding = (float) $bulan['sisa_bayar'] > 0;
                                                        $dueDate = $bulan['tanggal_jatuh_tempo']
                                                            ? \Carbon\Carbon::parse($bulan['tanggal_jatuh_tempo'])
                                                            : null;
                                                    @endphp
                                                    <tr>
                                                        <td class="payment-bill-check-cell">
                                                            @if($monthOutstanding)
                                                                <input
                                                                    class="payment-check bill-checkbox"
                                                                    type="checkbox"
                                                                    value="{{ $bulan['id'] }}"
                                                                    data-group="{{ $groupId }}"
                                                                    data-name="{{ $tagihan['nama_tagihan'] }} · {{ $bulan['periode_display'] ?? $bulan['periode'] }}"
                                                                    data-nominal="{{ (float) $bulan['sisa_bayar'] }}"
                                                                    data-periode="{{ $dueDate?->format('Y-m') }}"
                                                                    data-is-spp="{{ str_contains(strtolower($tagihan['nama_tagihan']), 'spp') ? 'true' : 'false' }}"
                                                                >
                                                            @else
                                                                <i class="fas fa-check" style="color:#12a06a;font-size:9px"></i>
                                                            @endif
                                                        </td>
                                                        <td data-label="Periode">{{ $bulan['periode_display'] ?? $bulan['periode'] }}</td>
                                                        <td data-label="Jatuh tempo">{{ $dueDate?->translatedFormat('d M Y') ?? '-' }}</td>
                                                        <td data-label="Nominal"><span class="payment-money">{{ $formatRupiah($bulan['nominal']) }}</span></td>
                                                        <td data-label="Dibayar"><span class="payment-money">{{ $formatRupiah($bulan['total_bayar']) }}</span></td>
                                                        <td data-label="Sisa"><span class="payment-money {{ $monthOutstanding ? 'is-remaining' : '' }}">{{ $formatRupiah(max(0, $bulan['sisa_bayar'])) }}</span></td>
                                                        <td data-label="Status">
                                                            <span class="payment-status {{ $monthOutstanding ? '' : 'is-paid' }}">{{ $monthOutstanding ? 'Belum lunas' : 'Lunas' }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </article>
                            @else
                                @php
                                    $billOutstanding = (float) $tagihan['sisa_bayar'] > 0;
                                @endphp
                                <article class="payment-single">
                                    <div class="payment-single-check">
                                        @if($billOutstanding)
                                            <input
                                                class="payment-check bill-checkbox"
                                                type="checkbox"
                                                value="{{ $tagihan['id'] }}"
                                                data-name="{{ $tagihan['nama_tagihan'] }}"
                                                data-nominal="{{ (float) $tagihan['sisa_bayar'] }}"
                                                data-periode=""
                                                data-is-spp="false"
                                            >
                                        @else
                                            <i class="fas fa-check" style="color:#12a06a;font-size:9px"></i>
                                        @endif
                                    </div>
                                    <div class="payment-single-name">
                                        <strong>{{ $tagihan['nama_tagihan'] }}</strong>
                                        <span>{{ ucfirst($tagihan['tipe']) }}</span>
                                    </div>
                                    <div class="payment-single-period">
                                        <span class="payment-single-label">Periode</span>
                                        <div class="payment-single-value"><strong>{{ $tagihan['periode'] ?? '-' }}</strong></div>
                                    </div>
                                    <div>
                                        <span class="payment-single-label">Nominal</span>
                                        <div class="payment-single-value"><strong>{{ $formatRupiah($tagihan['nominal']) }}</strong></div>
                                    </div>
                                    <div>
                                        <span class="payment-single-label">Sisa</span>
                                        <div class="payment-single-value"><strong class="payment-money {{ $billOutstanding ? 'is-remaining' : '' }}">{{ $formatRupiah(max(0, $tagihan['sisa_bayar'])) }}</strong></div>
                                    </div>
                                    <div>
                                        <span class="payment-single-label">Status</span>
                                        <span class="payment-status {{ $billOutstanding ? '' : 'is-paid' }}">{{ $billOutstanding ? 'Belum lunas' : 'Lunas' }}</span>
                                    </div>
                                </article>
                            @endif
                        @endforeach
                    </div>

                @else
                    <div class="payment-empty">
                        <div>
                            <i class="fas fa-file-invoice"></i>
                            <strong>Belum ada tagihan</strong>
                            <span>Buat tagihan siswa agar pembayaran dapat diproses.</span>
                        </div>
                    </div>
                @endif
            </section>

            @if($canPay && count($tagihanList) > 0)
                <section class="payment-panel" id="paymentFormPanel" hidden>
                    <div class="payment-panel-heading">
                        <div class="payment-step-title">
                            <span class="payment-step-number">2</span>
                            <div>
                                <h2 class="payment-panel-title">Atur pembayaran</h2>
                                <p class="payment-panel-context">Jumlah dapat disesuaikan untuk pembayaran sebagian.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('tagihan.proses.multi') }}" method="POST" id="multiPaymentForm">
                        @csrf
                        <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">

                        <div class="payment-form-body">
                            <div class="payment-selected-list" id="selectedBillsContainer"></div>

                            <div class="payment-details-grid">
                                <div class="payment-field">
                                    <label for="paymentDate">Tanggal bayar</label>
                                    <input id="paymentDate" type="date" name="tanggal_bayar" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="payment-field">
                                    <label for="paymentMethod">Metode pembayaran</label>
                                    <select id="paymentMethod" name="metode_bayar" required>
                                        <option value="tunai">Tunai</option>
                                        <option value="transfer">Transfer bank</option>
                                        <option value="kjc">KJC</option>
                                        <option value="tabungan">Potongan dari tabungan</option>
                                    </select>
                                </div>
                                <div class="payment-field is-full">
                                    <label for="paymentNote">Keterangan <span style="color:#98a2b3;font-weight:500">(opsional)</span></label>
                                    <textarea id="paymentNote" name="keterangan" placeholder="Tambahkan catatan pembayaran jika diperlukan..."></textarea>
                                </div>
                            </div>

                            <div class="payment-form-footer">
                                <div class="payment-form-total">
                                    <span>Total uang diterima</span>
                                    <strong id="paymentCashTotal">Rp0</strong>
                                </div>
                                <div class="payment-form-actions">
                                    <button class="payment-button" id="cancelPaymentButton" type="button">
                                        <i class="fas fa-xmark"></i>
                                        <span>Batal</span>
                                    </button>
                                    <button class="payment-button is-primary" id="submitPaymentButton" type="submit">
                                        <i class="fas fa-circle-check"></i>
                                        <span>Konfirmasi pembayaran</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </section>
            @endif
        </div>
    </div>
</div>

@if($canGenerate)
    <div class="modal fade" id="generateStudentModal" tabindex="-1" aria-labelledby="generateStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h2 class="modal-title" id="generateStudentModalLabel">Buat tagihan siswa</h2>
                        <p class="payment-panel-context">{{ $siswa->nama }}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <ul class="payment-modal-list">
                        <li><i class="fas fa-circle-check"></i><span>Membuat SPP untuk tahun berjalan.</span></li>
                        <li><i class="fas fa-circle-check"></i><span>Membuat jenis pembayaran yang sesuai sekolah dan kelas.</span></li>
                        <li><i class="fas fa-circle-check"></i><span>Tagihan yang sudah ada tidak akan digandakan.</span></li>
                    </ul>
                    <div class="payment-modal-note">
                        <i class="fas fa-circle-info"></i>
                        <span>Pastikan nominal SPP dan penempatan kelas siswa sudah benar.</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="payment-button" type="button" data-bs-dismiss="modal">Batal</button>
                    <form id="generateStudentForm" action="{{ route('tagihan.generate.manual.siswa', $siswa->id) }}" method="POST">
                        @csrf
                        <button class="payment-button is-primary" id="generateStudentButton" type="submit">
                            <i class="fas fa-file-circle-plus"></i>
                            <span>Buat tagihan sekarang</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', () => {
    const billCheckboxes = [...document.querySelectorAll('.bill-checkbox')];
    const selectAll = document.getElementById('selectAllBills');
    const groupChecks = [...document.querySelectorAll('[data-select-group]')];
    const selectedCount = document.getElementById('selectedBillCount');
    const selectedTotal = document.getElementById('selectedBillTotal');
    const continueButton = document.getElementById('continuePaymentButton');
    const selectionPanel = document.getElementById('billSelectionPanel');
    const formPanel = document.getElementById('paymentFormPanel');
    const selectedContainer = document.getElementById('selectedBillsContainer');
    const paymentDate = document.getElementById('paymentDate');
    const paymentForm = document.getElementById('multiPaymentForm');
    const submitButton = document.getElementById('submitPaymentButton');

    const currency = (amount) => 'Rp' + Number(amount || 0).toLocaleString('id-ID');
    const escapeHtml = (value) => String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');

    document.querySelectorAll('[data-group-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const panel = document.getElementById(button.dataset.groupToggle);
            const group = button.closest('[data-payment-group]');
            const willOpen = panel.hidden;

            panel.hidden = !willOpen;
            group?.classList.toggle('is-open', willOpen);
            button.setAttribute('aria-expanded', String(willOpen));
        });
    });

    const updateGroupChecks = () => {
        groupChecks.forEach((groupCheck) => {
            const children = billCheckboxes.filter((checkbox) => checkbox.dataset.group === groupCheck.dataset.selectGroup);
            const checked = children.filter((checkbox) => checkbox.checked).length;

            groupCheck.checked = children.length > 0 && checked === children.length;
            groupCheck.indeterminate = checked > 0 && checked < children.length;
        });
    };

    const updateSelection = () => {
        const checked = billCheckboxes.filter((checkbox) => checkbox.checked);
        const total = checked.reduce((sum, checkbox) => sum + Number(checkbox.dataset.nominal || 0), 0);

        if (selectedCount) selectedCount.textContent = checked.length.toLocaleString('id-ID');
        if (selectedTotal) selectedTotal.textContent = currency(total);
        if (continueButton) continueButton.disabled = checked.length === 0;

        if (selectAll) {
            selectAll.checked = billCheckboxes.length > 0 && checked.length === billCheckboxes.length;
            selectAll.indeterminate = checked.length > 0 && checked.length < billCheckboxes.length;
        }

        updateGroupChecks();
    };

    selectAll?.addEventListener('change', () => {
        billCheckboxes.forEach((checkbox) => {
            checkbox.checked = selectAll.checked;
        });
        updateSelection();
    });

    groupChecks.forEach((groupCheck) => {
        groupCheck.addEventListener('change', () => {
            billCheckboxes
                .filter((checkbox) => checkbox.dataset.group === groupCheck.dataset.selectGroup)
                .forEach((checkbox) => {
                    checkbox.checked = groupCheck.checked;
                });
            updateSelection();
        });
    });

    billCheckboxes.forEach((checkbox) => checkbox.addEventListener('change', updateSelection));

    const resetDiscount = (row) => {
        row.querySelector('[data-discount-amount]').value = '0';
        row.querySelector('[data-has-discount]').value = 'false';
    };

    const validateAmount = (input) => {
        const max = Number(input.dataset.maxAmount || 0);
        const value = Number(input.value || 0);
        const error = input.closest('[data-payment-row]').querySelector('[data-payment-error]');
        const invalid = value < 0 || value > max;

        input.classList.toggle('is-invalid', invalid);
        error.classList.toggle('is-visible', invalid);
        error.textContent = value > max
            ? `Maksimal ${currency(max)}`
            : 'Jumlah tidak boleh kurang dari 0';

        return !invalid;
    };

    const updateCashTotal = () => {
        const total = [...document.querySelectorAll('.payment-amount-input')]
            .reduce((sum, input) => sum + Number(input.value || 0), 0);
        const output = document.getElementById('paymentCashTotal');
        if (output) output.textContent = currency(total);
    };

    const recalculateRowDiscount = (row) => {
        const input = row.querySelector('.payment-amount-input');
        const info = row.querySelector('[data-discount-info]');
        const isSpp = row.dataset.isSpp === 'true';
        const period = row.dataset.period;
        const original = Number(row.dataset.originalAmount || 0);

        resetDiscount(row);
        info.className = 'payment-discount-info';
        info.innerHTML = '';

        if (!isSpp || !period || !paymentDate?.value) {
            validateAmount(input);
            updateCashTotal();
            return;
        }

        const paidAt = new Date(paymentDate.value + 'T00:00:00');
        const periodDate = new Date(period + '-01T00:00:00');
        const deadline = new Date(periodDate.getFullYear(), periodDate.getMonth(), 10);
        const discount = Math.min(25000, original);
        const discountedAmount = Math.max(0, original - discount);
        const amount = Number(input.value || 0);

        if (paidAt <= deadline && (amount === original || amount === discountedAmount)) {
            input.value = discountedAmount;
            row.querySelector('[data-discount-amount]').value = discount;
            row.querySelector('[data-has-discount]').value = 'true';
            info.classList.add('is-visible');
            info.innerHTML = `<i class="fas fa-tag"></i><span>Diskon ${currency(discount)} diterapkan. Tagihan lunas dengan pembayaran ${currency(discountedAmount)}.</span>`;
        } else if (paidAt <= deadline) {
            info.classList.add('is-visible', 'is-warning');
            info.innerHTML = `<i class="fas fa-circle-info"></i><span>Diskon ${currency(discount)} tersedia hanya untuk pembayaran lunas sebelum tanggal 10.</span>`;
        }

        validateAmount(input);
        updateCashTotal();
    };

    const recalculateAllDiscounts = () => {
        document.querySelectorAll('[data-payment-row]').forEach(recalculateRowDiscount);
    };

    const renderSelectedBills = () => {
        const checked = billCheckboxes.filter((checkbox) => checkbox.checked);

        selectedContainer.innerHTML = checked.map((checkbox) => {
            const id = checkbox.value;
            const amount = Number(checkbox.dataset.nominal || 0);

            return `
                <article class="payment-selected-item" data-payment-row data-bill-id="${id}" data-is-spp="${checkbox.dataset.isSpp}" data-period="${escapeHtml(checkbox.dataset.periode || '')}" data-original-amount="${amount}">
                    <div class="payment-selected-main">
                        <span class="payment-selected-icon"><i class="fas fa-file-invoice"></i></span>
                        <span class="payment-selected-copy">
                            <strong>${escapeHtml(checkbox.dataset.name)}</strong>
                            <span>Sisa tagihan ${currency(amount)}</span>
                        </span>
                    </div>
                    <div class="payment-selected-actions">
                        <div class="payment-amount-field">
                            <label for="payment-amount-${id}">Jumlah bayar</label>
                            <input id="payment-amount-${id}" class="payment-amount-input" type="number" name="pembayaran[${id}]" value="${amount}" min="0" max="${amount}" data-max-amount="${amount}" required>
                            <span class="payment-input-error" data-payment-error></span>
                        </div>
                        <button class="payment-quick-button" type="button" data-pay-full>
                            <i class="fas fa-check"></i><span>Lunasi</span>
                        </button>
                    </div>
                    <div class="payment-discount-info" data-discount-info></div>
                    <input type="hidden" name="original_amount[${id}]" value="${amount}">
                    <input type="hidden" name="discount_amount[${id}]" value="0" data-discount-amount>
                    <input type="hidden" name="has_discount[${id}]" value="false" data-has-discount>
                </article>
            `;
        }).join('');

        selectedContainer.querySelectorAll('.payment-amount-input').forEach((input) => {
            input.addEventListener('input', () => recalculateRowDiscount(input.closest('[data-payment-row]')));
        });

        selectedContainer.querySelectorAll('[data-pay-full]').forEach((button) => {
            button.addEventListener('click', () => {
                const row = button.closest('[data-payment-row]');
                const input = row.querySelector('.payment-amount-input');
                input.value = row.dataset.originalAmount;
                recalculateRowDiscount(row);
            });
        });

        recalculateAllDiscounts();
    };

    continueButton?.addEventListener('click', () => {
        renderSelectedBills();
        formPanel.hidden = false;
        formPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    document.getElementById('cancelPaymentButton')?.addEventListener('click', () => {
        formPanel.hidden = true;
        selectionPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    paymentDate?.addEventListener('change', recalculateAllDiscounts);

    paymentForm?.addEventListener('submit', (event) => {
        const inputs = [...paymentForm.querySelectorAll('.payment-amount-input')];
        const valid = inputs.every(validateAmount);
        const total = inputs.reduce((sum, input) => sum + Number(input.value || 0), 0);

        if (!valid || total <= 0) {
            event.preventDefault();
            window.alert(valid ? 'Jumlah pembayaran harus lebih dari Rp0.' : 'Periksa kembali jumlah pembayaran.');
            return;
        }

        if (!window.confirm(`Konfirmasi pembayaran sebesar ${currency(total)}?`)) {
            event.preventDefault();
            return;
        }

        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Memproses...</span>';
    });

    const generateForm = document.getElementById('generateStudentForm');
    const generateButton = document.getElementById('generateStudentButton');
    generateForm?.addEventListener('submit', () => {
        generateButton.disabled = true;
        generateButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Sedang membuat...</span>';
    });

    updateSelection();
});
</script>
@endsection
