@php
    $selected = old('permissions', $selectedPermissions ?? []);
    $roleValue = old('role', $role ?? ($admin->role ?? 'admin'));
    $permissionVisuals = [
        'Master Data Sekolah' => ['icon' => 'fa-school', 'tone' => 'blue'],
        'Pembayaran SPP & Tagihan' => ['icon' => 'fa-file-invoice-dollar', 'tone' => 'green'],
        'Koperasi' => ['icon' => 'fa-store', 'tone' => 'orange'],
        'Keuangan Kas' => ['icon' => 'fa-wallet', 'tone' => 'purple'],
        'Kenaikan & Kelulusan' => ['icon' => 'fa-graduation-cap', 'tone' => 'pink'],
        'Import & Export Data' => ['icon' => 'fa-file-arrow-up', 'tone' => 'cyan'],
        'Laporan' => ['icon' => 'fa-chart-column', 'tone' => 'indigo'],
        'Admin & Sistem' => ['icon' => 'fa-shield-halved', 'tone' => 'red'],
    ];
@endphp

@pushOnce('page-styles')
<style>
    .role-grid {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        margin-bottom: 1.5rem;
    }

    .permission-note {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        color: #166534;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding: 1rem 1.25rem;
    }

    .permission-grid {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        margin-bottom: 1.75rem;
    }

    .permission-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 1rem;
    }

    .permission-card-title {
        color: #111827;
        font-size: 0.95rem;
        font-weight: 800;
        margin-bottom: 0.8rem;
    }

    .permission-option {
        align-items: flex-start;
        color: #374151;
        display: flex;
        gap: 0.6rem;
        line-height: 1.35;
        margin-bottom: 0.65rem;
    }

    .permission-option:last-child {
        margin-bottom: 0;
    }

    .permission-option input {
        accent-color: #16a34a;
        flex: 0 0 auto;
        margin-top: 0.2rem;
    }

    .permissions-wrapper.is-disabled {
        opacity: 0.55;
    }
</style>
@endPushOnce

<div class="role-grid">
    <div class="role-field">
        <label for="role" class="form-label"><i class="fas fa-user-shield"></i> Role Admin</label>
        <select id="role" name="role" class="form-control" required>
            <option value="admin" {{ $roleValue === 'admin' || $roleValue === 'staff' ? 'selected' : '' }}>Admin</option>
            <option value="super_admin" {{ $roleValue === 'super_admin' || $roleValue === 'administrator' ? 'selected' : '' }}>Super Admin</option>
        </select>
    </div>
</div>

<div class="permission-note">
    <i class="fas fa-circle-info" aria-hidden="true"></i>
    <span>Dashboard dan monitoring otomatis dimiliki semua admin. Hak di bawah ini hanya mengatur akses fitur selain dashboard.</span>
</div>

<div class="permissions-wrapper" id="permissionsWrapper">
    <div class="permissions-heading">
        <div>
            <span class="permissions-eyebrow">Kontrol akses</span>
            <h3>Hak Akses Fitur</h3>
        </div>
        <span class="permissions-count"><i class="fas fa-layer-group"></i> {{ count($permissionGroups) }} kategori</span>
    </div>
    <div class="permission-grid">
        @foreach($permissionGroups as $groupName => $permissions)
            @php
                $visual = $permissionVisuals[$groupName] ?? ['icon' => 'fa-grid-2', 'tone' => 'blue'];
            @endphp
            <div class="permission-card" data-tone="{{ $visual['tone'] }}">
                <div class="permission-card-title">
                    <span class="permission-card-icon"><i class="fas {{ $visual['icon'] }}" aria-hidden="true"></i></span>
                    <span class="permission-card-copy">
                        <strong>{{ $groupName }}</strong>
                        <small>{{ count($permissions) }} hak akses</small>
                    </span>
                </div>
                <div class="permission-card-options">
                @foreach($permissions as $permission => $label)
                    <label class="permission-option">
                        <input
                            type="checkbox"
                            name="permissions[]"
                            value="{{ $permission }}"
                            {{ in_array($permission, $selected, true) ? 'checked' : '' }}
                        >
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const role = document.getElementById('role');
    const wrapper = document.getElementById('permissionsWrapper');
    const checkboxes = wrapper ? wrapper.querySelectorAll('input[type="checkbox"]') : [];

    function syncPermissionState() {
        const isSuperAdmin = role && role.value === 'super_admin';
        if (!wrapper) return;

        wrapper.classList.toggle('is-disabled', isSuperAdmin);
        checkboxes.forEach((checkbox) => {
            checkbox.disabled = isSuperAdmin;
        });
    }

    if (role) {
        role.addEventListener('change', syncPermissionState);
        syncPermissionState();
    }
});
</script>
