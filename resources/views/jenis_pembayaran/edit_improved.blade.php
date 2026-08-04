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

    .form-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        padding: 2.5rem 3rem;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        max-width: 100%;
    }

    .form-container h2 {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, #2d3748, #4a5568);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 2rem;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        display: block;
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.85rem 1rem;
        border-radius: 12px;
        border: 1px solid #d1d5db;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: #22c55e;
        outline: none;
        box-shadow: 0 0 0 3px rgba(34,197,94,0.2);
    }

    .alert-danger {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.85rem 2rem;
        border-radius: 16px;
        font-weight: 600;
        text-decoration: none;
        transition: all 300ms ease;
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(245, 158, 11, 0.4);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #9ca3af, #6b7280);
        color: white;
        box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
        margin-left: 1rem;
    }

    .btn-secondary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(107, 114, 128, 0.4);
    }

    .validation-message {
        display: none;
        color: #dc2626;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        padding: 0.75rem;
        border-radius: 8px;
        background-color: #fee2e2;
        border: 1px solid #fecaca;
    }

    .input-wrapper {
        position: relative;
    }

    .form-control.error {
        border-color: #dc2626 !important;
        background-color: #fff5f5 !important;
    }

    /* Target Type Styles */
    .target-type-container {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: #f9fafb;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }

    .radio-group {
        display: flex;
        gap: 2rem;
        margin-bottom: 1rem;
    }

    .radio-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        font-weight: 500;
        color: #4b5563;
    }

    .radio-label input[type="radio"] {
        cursor: pointer;
    }

    .multi-select-container {
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 1rem;
        background: white;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        margin-bottom: 0.5rem;
        border-radius: 6px;
        transition: background-color 0.2s;
    }

    .checkbox-item:hover {
        background-color: #f3f4f6;
    }

    .checkbox-item input[type="checkbox"] {
        cursor: pointer;
    }

    .checkbox-item label {
        cursor: pointer;
        flex: 1;
        margin: 0;
    }

    .select-all-btn {
        padding: 0.5rem 1rem;
        background: #e5e7eb;
        border: none;
        border-radius: 6px;
        font-size: 0.875rem;
        cursor: pointer;
        margin-bottom: 0.5rem;
    }

    .select-all-btn:hover {
        background: #d1d5db;
    }

    .text-gray-500 {
        color: #6b7280;
    }
</style>
@endpush
<div class="main-content">
    @include('layouts.header')

    <div class="content-area">
        <div class="form-container">
            <h2><i class="fas fa-edit"></i> Edit Jenis Pembayaran</h2>

            @if ($errors->any())
                <div class="alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('jenis_pembayaran.update', $jenis->id) }}" method="POST">
                @csrf
                @method('PUT')

                <label class="form-label">Sekolah</label>
                <select name="sekolah_id" id="sekolah_id" class="form-control" required>
                    @foreach($sekolah as $s)
                        <option value="{{ $s->id }}" {{ old('sekolah_id', $jenis->sekolah_id) == $s->id ? 'selected' : '' }}>
                            {{ $s->nama_sekolah }}
                        </option>
                    @endforeach
                </select>

                <label class="form-label">Nama Pembayaran</label>
                <input type="text" name="nama_pembayaran" class="form-control" 
                    value="{{ old('nama_pembayaran', $jenis->nama_pembayaran) }}" required>

                <label class="form-label">Tipe</label>
                <select name="tipe" class="form-control" required>
                    <option value="sekali" {{ old('tipe', $jenis->tipe) == 'sekali' ? 'selected' : '' }}>Sekali</option>
                    <option value="bulanan" {{ old('tipe', $jenis->tipe) == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                    <option value="setahun" {{ old('tipe', $jenis->tipe) == 'setahun' ? 'selected' : '' }}>Setahun</option>
                </select>

                {{-- SEKALI (date) --}}
                <div id="field-jatuh-tempo-sekali" class="jt-field" style="display:none;">
                    <label class="form-label">Jatuh Tempo</label>
                    <input type="date" name="jatuh_tempo" class="form-control"
                        value="{{ $jenis->tipe == 'sekali' ? $jenis->jatuh_tempo : '' }}" disabled>
                </div>

                {{-- BULANAN (dropdown) --}}
                <div id="field-jatuh-tempo-bulanan" class="jt-field" style="display:none;">
                    <label class="form-label">Tanggal Jatuh Tempo (Tiap Bulan Tanggal ...)</label>
                    <select name="jatuh_tempo" class="form-control" disabled>
                        <option value="">-- Pilih Tanggal --</option>
                        @for ($i = 1; $i <= 28; $i++)
                            <option value="{{ $i }}" 
                                {{ $jenis->tipe == 'bulanan' && $jenis->jatuh_tempo && \Carbon\Carbon::parse($jenis->jatuh_tempo)->day == $i ? 'selected' : '' }}>
                                Tanggal {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- SETAHUN (date) --}}
                <div id="field-jatuh-tempo-setahun" class="jt-field" style="display:none;">
                    <label class="form-label">Jatuh Tempo</label>
                    <input type="date" name="jatuh_tempo" class="form-control"
                        value="{{ $jenis->tipe == 'setahun' ? $jenis->jatuh_tempo : '' }}" disabled>
                </div>

                <label class="form-label">Nominal</label>
                <div class="input-wrapper">
                    <input 
                        type="number" 
                        name="nominal" 
                        id="nominal-pembayaran"
                        class="form-control"
                        value="{{ old('nominal', $jenis->nominal) }}"
                        min="0"
                        step="1"
                        required
                    >
                    <div id="nominal-warning" class="validation-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        Nilai nominal terlalu besar. Maksimal Rp 2.147.483.647
                    </div>
                </div>

                {{-- Target Type Selection --}}
                <div class="target-type-container">
                    <label class="form-label">Target Pembayaran</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="target_type" value="all" 
                                {{ old('target_type', $jenis->target_type ?? 'all') == 'all' ? 'checked' : '' }}>
                            Semua Siswa
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="target_type" value="specific_students" 
                                {{ old('target_type', $jenis->target_type ?? 'all') == 'specific_students' ? 'checked' : '' }}>
                            Siswa Tertentu
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="target_type" value="specific_classes" 
                                {{ old('target_type', $jenis->target_type ?? 'all') == 'specific_classes' ? 'checked' : '' }}>
                            Kelas Tertentu
                        </label>
                    </div>

                    {{-- Specific Students Selection --}}
                    <div id="specific-students-container" style="display: none;">
                        <label class="form-label">Pilih Siswa</label>
                        
                        {{-- Filter Controls --}}
                        <div style="display: flex; gap: 1rem; margin-bottom: 1rem; flex-wrap: wrap;">
                            <div style="flex: 1; min-width: 200px;">
                                <label class="form-label" style="margin-bottom: 0.25rem; font-size: 0.875rem;">Filter Kelas</label>
                                <select id="filter-kelas" class="form-control" style="margin-bottom: 0;">
                                    <option value="">Semua Kelas</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}">Kelas {{ $k->tingkat }} - {{ $k->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="flex: 1; min-width: 200px;">
                                <label class="form-label" style="margin-bottom: 0.25rem; font-size: 0.875rem;">Cari Nama Siswa</label>
                                <input type="text" id="search-siswa" class="form-control" placeholder="Ketik nama siswa..." style="margin-bottom: 0;">
                            </div>
                        </div>
                        
                        <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <button type="button" class="select-all-btn" onclick="toggleAllStudents()">
                                Pilih/Hapus Semua
                            </button>
                            <button type="button" class="select-all-btn" onclick="toggleVisibleStudents()">
                                Pilih/Hapus Terlihat
                            </button>
                        </div>
                        
                        <div class="multi-select-container" id="students-list">
                            @if($siswa->count() > 0)
                                @foreach($siswa as $s)
                                    <div class="checkbox-item" data-kelas-id="{{ $s->kelas_id }}" data-nama="{{ strtolower($s->nama) }}" data-nis="{{ strtolower($s->nis) }}">
                                        <input type="checkbox" name="siswa_ids[]" value="{{ $s->id }}" 
                                            id="siswa_{{ $s->id }}" onchange="updateSelectedCount()"
                                            {{ $jenis->siswa->contains($s->id) ? 'checked' : '' }}>
                                        <label for="siswa_{{ $s->id }}">{{ $s->nama }} ({{ $s->nis }}) - {{ $s->kelas ? "Kelas {$s->kelas->tingkat} - {$s->kelas->nama_kelas}" : 'Kelas tidak diketahui' }}</label>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-gray-500">Tidak ada siswa di sekolah ini</p>
                            @endif
                        </div>
                        
                        {{-- Selected Count --}}
                        <div id="selected-count" style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                            <span id="selected-students-count">{{ $jenis->siswa->count() }}</span> siswa dipilih
                        </div>
                    </div>

                    {{-- Specific Classes Selection --}}
                    <div id="specific-classes-container" style="display: none;">
                        <label class="form-label">Pilih Kelas</label>
                        <button type="button" class="select-all-btn" onclick="toggleAllClasses()">
                            Pilih/Hapus Semua
                        </button>
                        <div class="multi-select-container" id="classes-list">
                            @if($kelas->count() > 0)
                                @foreach($kelas as $k)
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="kelas_ids[]" value="{{ $k->id }}" 
                                            id="kelas_{{ $k->id }}"
                                            {{ $jenis->kelas->contains($k->id) ? 'checked' : '' }}>
                                        <label for="kelas_{{ $k->id }}">Kelas {{ $k->tingkat }} - {{ $k->nama_kelas }}</label>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-gray-500">Tidak ada kelas di sekolah ini</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 mt-6">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="{{ route('jenis_pembayaran.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Global variables for filtering
    let allStudents = [];
    let allClasses = [];

    // Jatuh tempo toggle logic
    const tipeSelect = document.querySelector('select[name="tipe"]');
    const fields = document.querySelectorAll('.jt-field');

    function toggleFields() {
        fields.forEach(f => {
            f.style.display = 'none';
            const input = f.querySelector('input, select');
            if (input) input.disabled = true;
        });

        const tipe = tipeSelect.value;
        const fieldId = `field-jatuh-tempo-${tipe}`;
        const target = document.getElementById(fieldId);
        if (target) {
            target.style.display = 'block';
            const input = target.querySelector('input, select');
            if (input) input.disabled = false;
        }
    }

    tipeSelect.addEventListener('change', toggleFields);
    toggleFields();

    // Nominal validation
    const MAX_INTEGER = 2147483647;
    const nominalInput = document.getElementById('nominal-pembayaran');
    const warning = document.getElementById('nominal-warning');
    const submitBtn = document.querySelector('button[type="submit"]');

    function validateNominal() {
        const value = parseInt(nominalInput.value);
        
        if (value > MAX_INTEGER) {
            warning.style.display = 'block';
            nominalInput.classList.add('error');
            submitBtn.disabled = true;
            return false;
        } else {
            warning.style.display = 'none';
            nominalInput.classList.remove('error');
            submitBtn.disabled = false;
            return true;
        }
    }

    nominalInput.addEventListener('input', validateNominal);
    validateNominal();

    document.querySelector('form').addEventListener('submit', function(e) {
        if (!validateNominal()) {
            e.preventDefault();
            alert('Nominal pembayaran melebihi batas maksimal sistem!');
            nominalInput.focus();
            return false;
        }
    });

    // Target type toggle logic
    const targetTypeRadios = document.querySelectorAll('input[name="target_type"]');
    const studentsContainer = document.getElementById('specific-students-container');
    const classesContainer = document.getElementById('specific-classes-container');

    function toggleTargetContainers() {
        const selectedType = document.querySelector('input[name="target_type"]:checked').value;
        
        studentsContainer.style.display = selectedType === 'specific_students' ? 'block' : 'none';
        classesContainer.style.display = selectedType === 'specific_classes' ? 'block' : 'none';
    }

    targetTypeRadios.forEach(radio => {
        radio.addEventListener('change', toggleTargetContainers);
    });

    toggleTargetContainers();

    // Filter students function
    function filterStudents() {
        const filterKelasId = document.getElementById('filter-kelas').value;
        const searchTerm = document.getElementById('search-siswa').value.toLowerCase();
        const studentItems = document.querySelectorAll('#students-list .checkbox-item');
        
        studentItems.forEach(item => {
            const kelasId = item.getAttribute('data-kelas-id');
            const nama = item.getAttribute('data-nama');
            const nis = item.getAttribute('data-nis');
            
            let showItem = true;
            
            // Filter by class
            if (filterKelasId && kelasId != filterKelasId) {
                showItem = false;
            }
            
            // Filter by search term
            if (searchTerm && !nama.includes(searchTerm) && !nis.includes(searchTerm)) {
                showItem = false;
            }
            
            item.style.display = showItem ? 'flex' : 'none';
        });
        
        updateSelectedCount();
    }

    // Update selected count
    function updateSelectedCount() {
        const checkedBoxes = document.querySelectorAll('#students-list input[type="checkbox"]:checked');
        document.getElementById('selected-students-count').textContent = checkedBoxes.length;
    }

    // Filter event listeners
    document.getElementById('filter-kelas').addEventListener('change', filterStudents);
    document.getElementById('search-siswa').addEventListener('input', filterStudents);

    // Load students and classes when school is changed
    const sekolahSelect = document.getElementById('sekolah_id');
    const originalSekolahId = sekolahSelect.value;
    
    sekolahSelect.addEventListener('change', function() {
        const sekolahId = this.value;
        
        if (sekolahId && sekolahId !== originalSekolahId) {
            fetch(`/jenis-pembayaran/get-data-by-sekolah/${sekolahId}`)
                .then(response => response.json())
                .then(data => {
                    // Store data globally for filtering
                    allStudents = data.siswa;
                    allClasses = data.kelas;
                    
                    // Update filter kelas dropdown
                    const filterKelas = document.getElementById('filter-kelas');
                    filterKelas.innerHTML = '<option value="">Semua Kelas</option>';
                    
                    if (data.kelas.length > 0) {
                        data.kelas.forEach(kelas => {
                            const option = document.createElement('option');
                            option.value = kelas.id;
                            option.textContent = `Kelas ${kelas.tingkat} - ${kelas.nama_kelas}`;
                            filterKelas.appendChild(option);
                        });
                    }
                    
                    // Populate students
                    const studentsList = document.getElementById('students-list');
                    studentsList.innerHTML = '';
                    
                    if (data.siswa.length > 0) {
                        data.siswa.forEach(siswa => {
                            const item = document.createElement('div');
                            item.className = 'checkbox-item';
                            item.setAttribute('data-kelas-id', siswa.kelas_id);
                            item.setAttribute('data-nama', siswa.nama.toLowerCase());
                            item.setAttribute('data-nis', siswa.nis.toLowerCase());
                            item.innerHTML = `
                                <input type="checkbox" name="siswa_ids[]" value="${siswa.id}" id="siswa_${siswa.id}" onchange="updateSelectedCount()">
                                <label for="siswa_${siswa.id}">${siswa.nama} (${siswa.nis}) - ${siswa.kelas_nama}</label>
                            `;
                            studentsList.appendChild(item);
                        });
                    } else {
                        studentsList.innerHTML = '<p class="text-gray-500">Tidak ada siswa di sekolah ini</p>';
                    }

                    // Populate classes
                    const classesList = document.getElementById('classes-list');
                    classesList.innerHTML = '';
                    
                    if (data.kelas.length > 0) {
                        data.kelas.forEach(kelas => {
                            const item = document.createElement('div');
                            item.className = 'checkbox-item';
                            item.innerHTML = `
                                <input type="checkbox" name="kelas_ids[]" value="${kelas.id}" id="kelas_${kelas.id}">
                                <label for="kelas_${kelas.id}">Kelas ${kelas.tingkat} - ${kelas.nama_kelas}</label>
                            `;
                            classesList.appendChild(item);
                        });
                    } else {
                        classesList.innerHTML = '<p class="text-gray-500">Tidak ada kelas di sekolah ini</p>';
                    }
                    
                    updateSelectedCount();
                })
                .catch(error => {
                    console.error('Error loading data:', error);
                });
        }
    });

    // Make functions global for onclick handlers
    window.filterStudents = filterStudents;
    window.updateSelectedCount = updateSelectedCount;
    
    // Initial count update
    updateSelectedCount();
});

// Toggle all students
function toggleAllStudents() {
    const checkboxes = document.querySelectorAll('#students-list input[type="checkbox"]');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(cb => {
        cb.checked = !allChecked;
    });
    
    updateSelectedCount();
}

// Toggle visible students only
function toggleVisibleStudents() {
    const visibleCheckboxes = document.querySelectorAll('#students-list .checkbox-item:not([style*="display: none"]) input[type="checkbox"]');
    const allVisibleChecked = Array.from(visibleCheckboxes).every(cb => cb.checked);
    
    visibleCheckboxes.forEach(cb => {
        cb.checked = !allVisibleChecked;
    });
    
    updateSelectedCount();
}

// Toggle all classes
function toggleAllClasses() {
    const checkboxes = document.querySelectorAll('#classes-list input[type="checkbox"]');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(cb => {
        cb.checked = !allChecked;
    });
}
</script>
@endsection
