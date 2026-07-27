@once
<style>
    .admin-context {
        background: rgba(255, 255, 255, 0.94);
        border: 1px solid rgba(34, 197, 94, 0.14);
        border-left: 5px solid #22c55e;
        border-radius: 16px;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
        margin-bottom: 1.25rem;
        padding: 1.25rem 1.5rem;
    }
    .admin-breadcrumb {
        color: #64748b;
        display: flex;
        flex-wrap: wrap;
        font-size: 0.86rem;
        font-weight: 700;
        gap: 0.45rem;
        margin-bottom: 0.55rem;
        text-transform: uppercase;
    }
    .admin-breadcrumb span:last-child {
        color: #166534;
    }
    .admin-context-title {
        color: #0f172a;
        font-size: 1.05rem;
        font-weight: 800;
        margin-bottom: 0.35rem;
    }
    .admin-context-description {
        color: #475569;
        line-height: 1.55;
        margin: 0;
    }
    .admin-next-steps {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.9rem;
    }
    .admin-step-pill {
        background: #f0fdf4;
        border: 1px solid rgba(34, 197, 94, 0.18);
        border-radius: 999px;
        color: #166534;
        font-size: 0.82rem;
        font-weight: 700;
        padding: 0.4rem 0.7rem;
    }
</style>
@endonce

<div class="admin-context">
    <div class="admin-breadcrumb">
        <span>{{ $section ?? 'Admin' }}</span>
        <span>/</span>
        <span>{{ $current ?? 'Halaman' }}</span>
    </div>
    <div class="admin-context-title">{{ $title ?? ($current ?? 'Konteks Halaman') }}</div>
    <p class="admin-context-description">{{ $description ?? 'Gunakan halaman ini untuk mengelola data sistem.' }}</p>

    @if(!empty($steps ?? []))
        <div class="admin-next-steps">
            @foreach($steps as $step)
                <span class="admin-step-pill">{{ $step }}</span>
            @endforeach
        </div>
    @endif
</div>
