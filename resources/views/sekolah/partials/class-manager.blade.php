@php
    $classRows = old('kelas', $kelasRows ?? []);
    $classRows = is_array($classRows) ? $classRows : [];
    $defaultAcademicYearId = old('kelas.0.tahun_ajaran_id', $tahunAjaranAktifId ?? '');
@endphp

<div class="form-section school-class-section">
    <div class="school-form-section-heading school-class-section-heading">
        <span class="school-form-section-icon school-form-section-icon--orange">
            <i class="fas fa-chalkboard" aria-hidden="true"></i>
        </span>
        <div>
            <h4>Data Kelas</h4>
            <p>Kelola tingkat, nama rombel, dan tahun ajaran langsung dari sekolah ini.</p>
        </div>
        <button type="button" class="btn btn-primary school-class-add" id="addClassRow">
            <i class="fas fa-plus" aria-hidden="true"></i>
            Tambah Kelas
        </button>
    </div>

    @if($errors->has('kelas') || count($errors->get('kelas.*')) > 0)
        <div class="school-class-validation" role="alert">
            <i class="fas fa-circle-exclamation" aria-hidden="true"></i>
            <div>
                <strong>Periksa kembali data kelas.</strong>
                <span>Ada baris yang belum lengkap atau memiliki data yang sama.</span>
            </div>
        </div>
    @endif

    <div class="school-class-manager" id="schoolClassManager" data-default-year="{{ $defaultAcademicYearId }}">
        <div class="school-class-list" id="classRows">
            @foreach($classRows as $index => $row)
                @php
                    $classId = filled($row['id'] ?? null) ? (int) $row['id'] : null;
                    $studentCount = $classId ? (int) ($classStudentCounts[$classId] ?? 0) : 0;
                    $pendingDelete = filter_var($row['hapus'] ?? false, FILTER_VALIDATE_BOOLEAN);
                @endphp
                <article class="school-class-row {{ $pendingDelete ? 'is-pending-delete' : '' }}"
                         data-class-row
                         data-existing="{{ $classId ? 'true' : 'false' }}"
                         data-student-count="{{ $studentCount }}">
                    <input type="hidden" name="kelas[{{ $index }}][id]" value="{{ $classId }}" data-class-id>
                    <input type="hidden" name="kelas[{{ $index }}][hapus]" value="{{ $pendingDelete ? 1 : 0 }}" data-class-delete>

                    <div class="school-class-row-head">
                        <div class="school-class-row-title">
                            <span class="school-class-number" data-class-number>{{ $loop->iteration }}</span>
                            <div>
                                <strong data-class-title>
                                    {{ filled($row['tingkat'] ?? null) ? 'Tingkat '.$row['tingkat'].' '.($row['nama_kelas'] ?? '') : 'Kelas baru' }}
                                </strong>
                                <span>{{ $classId ? 'Kelas tersimpan' : 'Kelas baru' }}</span>
                            </div>
                        </div>
                        <div class="school-class-row-meta">
                            @if($studentCount > 0)
                                <span class="school-class-student-badge">
                                    <i class="fas fa-user-graduate" aria-hidden="true"></i>
                                    {{ $studentCount }} siswa
                                </span>
                            @endif
                            <span class="school-class-delete-badge">
                                <i class="fas fa-trash" aria-hidden="true"></i>
                                Akan dihapus
                            </span>
                            <button type="button"
                                    class="school-class-remove"
                                    data-remove-class
                                    @if($studentCount > 0) disabled aria-disabled="true" @endif
                                    title="{{ $studentCount > 0 ? 'Pindahkan siswa terlebih dahulu sebelum menghapus kelas' : 'Hapus kelas' }}">
                                <i class="fas {{ $pendingDelete ? 'fa-rotate-left' : 'fa-trash' }}" aria-hidden="true"></i>
                                <span>{{ $pendingDelete ? 'Urungkan' : 'Hapus' }}</span>
                            </button>
                        </div>
                    </div>

                    <div class="school-class-fields">
                        <div class="form-group">
                            <label for="kelas_{{ $index }}_tingkat" class="form-label">
                                <i class="fas fa-layer-group" aria-hidden="true"></i> Tingkat
                            </label>
                            <select name="kelas[{{ $index }}][tingkat]"
                                    id="kelas_{{ $index }}_tingkat"
                                    class="form-control @error('kelas.'.$index.'.tingkat') is-invalid @enderror"
                                    data-class-level>
                                <option value="">Pilih tingkat</option>
                                @for($level = 1; $level <= 12; $level++)
                                    <option value="{{ $level }}" @selected((string) ($row['tingkat'] ?? '') === (string) $level)>
                                        Tingkat {{ $level }}
                                    </option>
                                @endfor
                            </select>
                            @error('kelas.'.$index.'.tingkat')
                                <div class="invalid-feedback"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="kelas_{{ $index }}_nama" class="form-label">
                                <i class="fas fa-font" aria-hidden="true"></i> Nama / Rombel
                            </label>
                            <input type="text"
                                   name="kelas[{{ $index }}][nama_kelas]"
                                   id="kelas_{{ $index }}_nama"
                                   class="form-control @error('kelas.'.$index.'.nama_kelas') is-invalid @enderror"
                                   value="{{ $row['nama_kelas'] ?? '' }}"
                                   maxlength="100"
                                   placeholder="Contoh: A atau Kelas A"
                                   data-class-name>
                            @error('kelas.'.$index.'.nama_kelas')
                                <div class="invalid-feedback"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</div>
                            @enderror
                            <small class="form-text">Boleh dikosongkan jika hanya menggunakan tingkat.</small>
                        </div>

                        <div class="form-group">
                            <label for="kelas_{{ $index }}_tahun" class="form-label">
                                <i class="fas fa-calendar-days" aria-hidden="true"></i> Tahun Ajaran
                            </label>
                            <select name="kelas[{{ $index }}][tahun_ajaran_id]"
                                    id="kelas_{{ $index }}_tahun"
                                    class="form-control @error('kelas.'.$index.'.tahun_ajaran_id') is-invalid @enderror"
                                    data-class-year>
                                <option value="">Belum ditentukan</option>
                                @foreach($tahunAjaran as $year)
                                    <option value="{{ $year->id }}" @selected((string) ($row['tahun_ajaran_id'] ?? '') === (string) $year->id)>
                                        {{ $year->nama_tahun }}{{ $year->aktif ? ' — Aktif' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas.'.$index.'.tahun_ajaran_id')
                                <div class="invalid-feedback"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="school-class-empty" id="classEmptyState" @if(count($classRows) > 0) hidden @endif>
            <span><i class="fas fa-chalkboard" aria-hidden="true"></i></span>
            <div>
                <strong>Belum ada kelas</strong>
                <p>Gunakan tombol Tambah Kelas untuk membuat tingkat atau rombel baru.</p>
            </div>
        </div>

        <div class="school-class-summary">
            <span><i class="fas fa-circle-info" aria-hidden="true"></i> Perubahan kelas ikut tersimpan bersama sekolah.</span>
            <strong><span id="activeClassCount">0</span> kelas terdata</strong>
        </div>
    </div>
</div>

<template id="classRowTemplate">
    <article class="school-class-row" data-class-row data-existing="false" data-student-count="0">
        <input type="hidden" name="kelas[__INDEX__][id]" value="" data-class-id>
        <input type="hidden" name="kelas[__INDEX__][hapus]" value="0" data-class-delete>

        <div class="school-class-row-head">
            <div class="school-class-row-title">
                <span class="school-class-number" data-class-number>1</span>
                <div>
                    <strong data-class-title>Kelas baru</strong>
                    <span>Kelas baru</span>
                </div>
            </div>
            <div class="school-class-row-meta">
                <span class="school-class-delete-badge">
                    <i class="fas fa-trash" aria-hidden="true"></i>
                    Akan dihapus
                </span>
                <button type="button" class="school-class-remove" data-remove-class title="Hapus kelas">
                    <i class="fas fa-trash" aria-hidden="true"></i>
                    <span>Hapus</span>
                </button>
            </div>
        </div>

        <div class="school-class-fields">
            <div class="form-group">
                <label for="kelas___INDEX___tingkat" class="form-label">
                    <i class="fas fa-layer-group" aria-hidden="true"></i> Tingkat
                </label>
                <select name="kelas[__INDEX__][tingkat]" id="kelas___INDEX___tingkat" class="form-control" data-class-level>
                    <option value="">Pilih tingkat</option>
                    @for($level = 1; $level <= 12; $level++)
                        <option value="{{ $level }}">Tingkat {{ $level }}</option>
                    @endfor
                </select>
            </div>

            <div class="form-group">
                <label for="kelas___INDEX___nama" class="form-label">
                    <i class="fas fa-font" aria-hidden="true"></i> Nama / Rombel
                </label>
                <input type="text"
                       name="kelas[__INDEX__][nama_kelas]"
                       id="kelas___INDEX___nama"
                       class="form-control"
                       maxlength="100"
                       placeholder="Contoh: A atau Kelas A"
                       data-class-name>
                <small class="form-text">Boleh dikosongkan jika hanya menggunakan tingkat.</small>
            </div>

            <div class="form-group">
                <label for="kelas___INDEX___tahun" class="form-label">
                    <i class="fas fa-calendar-days" aria-hidden="true"></i> Tahun Ajaran
                </label>
                <select name="kelas[__INDEX__][tahun_ajaran_id]" id="kelas___INDEX___tahun" class="form-control" data-class-year>
                    <option value="">Belum ditentukan</option>
                    @foreach($tahunAjaran as $year)
                        <option value="{{ $year->id }}">{{ $year->nama_tahun }}{{ $year->aktif ? ' — Aktif' : '' }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </article>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const manager = document.getElementById('schoolClassManager');
    const rowsContainer = document.getElementById('classRows');
    const template = document.getElementById('classRowTemplate');
    const addButton = document.getElementById('addClassRow');
    const emptyState = document.getElementById('classEmptyState');
    const activeCount = document.getElementById('activeClassCount');

    if (!manager || !rowsContainer || !template || !addButton) return;

    let nextIndex = Array.from(rowsContainer.querySelectorAll('[data-class-row]')).reduce(function (highest, row) {
        const name = row.querySelector('[data-class-level]')?.name || '';
        const match = name.match(/kelas\[(\d+)\]/);
        return match ? Math.max(highest, Number(match[1]) + 1) : highest;
    }, 0);

    const notifyChanged = function () {
        manager.closest('form')?.dispatchEvent(new CustomEvent('school-classes:changed', { bubbles: true }));
    };

    const updateTitle = function (row) {
        const level = row.querySelector('[data-class-level]')?.value || '';
        const name = row.querySelector('[data-class-name]')?.value.trim() || '';
        const title = row.querySelector('[data-class-title]');
        if (title) title.textContent = level ? `Tingkat ${level}${name ? ` ${name}` : ''}` : 'Kelas baru';
    };

    const refresh = function () {
        const rows = Array.from(rowsContainer.querySelectorAll('[data-class-row]'));
        let visibleNumber = 0;
        let count = 0;

        rows.forEach(function (row) {
            const pendingDelete = row.classList.contains('is-pending-delete');
            if (!pendingDelete) {
                visibleNumber += 1;
                if (row.querySelector('[data-class-level]')?.value) count += 1;
            }
            const number = row.querySelector('[data-class-number]');
            if (number) number.textContent = pendingDelete ? '—' : visibleNumber;
            updateTitle(row);
        });

        if (activeCount) activeCount.textContent = count;
        if (emptyState) emptyState.hidden = rows.length > 0;
    };

    const bindRow = function (row) {
        row.querySelectorAll('[data-class-level], [data-class-name], [data-class-year]').forEach(function (field) {
            field.addEventListener('input', function () {
                updateTitle(row);
                notifyChanged();
            });
            field.addEventListener('change', function () {
                updateTitle(row);
                notifyChanged();
            });
        });

        const removeButton = row.querySelector('[data-remove-class]');
        removeButton?.addEventListener('click', function () {
            if (removeButton.disabled) return;

            const isExisting = row.dataset.existing === 'true';
            if (!isExisting) {
                row.remove();
            } else {
                const pendingDelete = row.classList.toggle('is-pending-delete');
                const deleteInput = row.querySelector('[data-class-delete]');
                if (deleteInput) deleteInput.value = pendingDelete ? '1' : '0';
                removeButton.querySelector('i')?.classList.toggle('fa-trash', !pendingDelete);
                removeButton.querySelector('i')?.classList.toggle('fa-rotate-left', pendingDelete);
                const label = removeButton.querySelector('span');
                if (label) label.textContent = pendingDelete ? 'Urungkan' : 'Hapus';
            }

            refresh();
            notifyChanged();
        });
    };

    rowsContainer.querySelectorAll('[data-class-row]').forEach(bindRow);

    addButton.addEventListener('click', function () {
        const html = template.innerHTML.replaceAll('__INDEX__', String(nextIndex));
        const wrapper = document.createElement('div');
        wrapper.innerHTML = html.trim();
        const row = wrapper.firstElementChild;
        const defaultYear = manager.dataset.defaultYear || '';
        const yearSelect = row.querySelector('[data-class-year]');

        if (yearSelect && defaultYear) yearSelect.value = defaultYear;
        rowsContainer.appendChild(row);
        bindRow(row);
        nextIndex += 1;
        refresh();
        notifyChanged();
        row.querySelector('[data-class-level]')?.focus();
    });

    refresh();
});
</script>
