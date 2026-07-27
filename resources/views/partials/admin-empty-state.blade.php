@once
<style>
    .guided-empty-state {
        align-items: center;
        color: #475569;
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
        padding: 3rem 1.5rem;
        text-align: center;
    }
    .guided-empty-state i {
        color: #22c55e;
        font-size: 2.5rem;
    }
    .guided-empty-state h3 {
        color: #0f172a;
        font-size: 1.25rem;
        font-weight: 800;
        margin: 0;
    }
    .guided-empty-state p {
        line-height: 1.55;
        margin: 0;
        max-width: 560px;
    }
    .guided-empty-state a {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border-radius: 12px;
        color: #fff;
        font-weight: 700;
        margin-top: 0.35rem;
        padding: 0.75rem 1.15rem;
        text-decoration: none;
    }
</style>
@endonce

<div class="guided-empty-state">
    <i class="{{ $icon ?? 'fas fa-inbox' }}"></i>
    <h3>{{ $title ?? 'Belum Ada Data' }}</h3>
    <p>{{ $message ?? 'Tambahkan data pertama agar halaman ini dapat digunakan.' }}</p>
    @if(!empty($actionRoute ?? null) && !empty($actionText ?? null))
        <a href="{{ $actionRoute }}">{{ $actionText }}</a>
    @endif
</div>
