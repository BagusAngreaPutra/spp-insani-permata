@extends('layouts.app')
@include('layouts.sidebar')

@section('content')
<style>
    .main-content {
        margin-left: 280px;
        min-height: 100vh;
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 50%, #a7f3d0 100%);
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
        padding: 2rem 1.5rem; 
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        padding: 2.5rem;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(34, 197, 94, 0.1);
        border: 2px solid rgba(34, 197, 94, 0.1);
    }

    .page-title {
        font-size: 2.2rem;
        font-weight: 800;
        background: linear-gradient(135deg, #14532d, #166534, #22c55e);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #22c55e, #16a34a, #15803d);
        color: #fff;
        padding: 0.75rem 1.25rem;
        border-radius: 16px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.4s ease;
        display: inline-block;
        box-shadow: 0 8px 20px rgba(34, 197, 94, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 12px 30px rgba(34, 197, 94, 0.4);
    }

    .school-section {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        margin-bottom: 2rem;
        box-shadow: 0 25px 50px rgba(22, 163, 74, 0.15);
        border: 2px solid rgba(34, 197, 94, 0.2);
        overflow: hidden;
    }

    .school-header {
        background: linear-gradient(135deg, #f0fdf4, #ffffffff, #ffffffff);
        padding: 1.5rem 2rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border-bottom: 1px solid rgba(34, 197, 94, 0.1);
    }

    .school-header:hover {
        background: linear-gradient(135deg, #ffffffff, #ffffffff, #ffffffff);
    }

    .school-header h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #14532d;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .school-content {
        display: none;
        padding: 0;
        color: #rgba(250, 255, 252, 1)
    }

    .school-content.active {
        display: block;
        color: #rgba(250, 255, 252, 1)
    }

    .class-item {
        border-bottom: 1px solid rgba(34, 197, 94, 0.1);
        transition: all 0.3s ease;
    }

    .class-item:last-child {
        border-bottom: none;
    }

    .class-header {
        padding: 1rem 2rem;
        cursor: pointer;
        background: rgba(34, 197, 94, 0.05);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .class-header:hover {
        background: rgba(34, 197, 94, 0.1);
    }

    .class-header h4 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #166534;
        margin: 0;
    }

    .class-stats {
        display: flex;
        gap: 1rem;
        font-size: 0.875rem;
        color: #6b7280;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .students-table-container {
        display: none;
        padding: 0;
        background: white;
    }

    .students-table-container.active {
        display: block;
    }

    .students-table {
        width: 100%;
        border-collapse: collapse;
    }

    .students-table th,
    .students-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(34, 197, 94, 0.1);
    }

    .students-table th {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        font-weight: 600;
        color: #14532d;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .students-table tbody tr:hover {
        background: rgba(34, 197, 94, 0.05);
    }

    .student-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .student-name {
        font-weight: 600;
        color: #1f2937;
    }

    .student-nis {
        font-size: 0.875rem;
        color: #6b7280;
    }

    /* Filter input styling */
    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid rgba(34, 197, 94, 0.2);
        border-radius: 12px;
        background: white;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .form-control:focus {
        outline: none;
        border-color: #22c55e;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    }

    .tagihan-summary {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.875rem;
    }

    .summary-label {
        color: #6b7280;
    }

    .summary-value {
        font-weight: 600;
    }

    .summary-value.total {
        color: #dc2626;
        font-size: 1rem;
    }

    .summary-value.paid {
        color: #16a34a;
    }

    .summary-value.remaining {
        color: #0cea0cff;
    }

    .btn-process {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-process:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-generate {
        background: linear-gradient(135deg, #42b121ff, #099210ff);
        color: #fff;
        padding: 0.75rem 1.25rem;
        border-radius: 16px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.4s ease;
        display: inline-block;
        box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3);
        border: none;
        cursor: pointer;
    }

    .btn-generate:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 12px 30px rgba(245, 158, 11, 0.4);
    }

    .toggle-icon {
        transition: transform 0.3s ease;
    }

    .toggle-icon.rotated {
        transform: rotate(180deg);
    }

    .alert-success {
        background: linear-gradient(135deg, #d1fae5, #bbf7d0, #a7f3d0);
        border: 2px solid rgba(34, 197, 94, 0.3);
        color: #14532d;
        padding: 1.5rem 2rem;
        border-radius: 20px;
        margin-bottom: 1.5rem;
        box-shadow: 0 10px 25px rgba(34, 197, 94, 0.15);
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6b7280;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #d1d5db;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            gap: 1rem;
            padding: 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .class-stats {
            flex-direction: column;
            gap: 0.5rem;
        }

        .students-table {
            font-size: 0.875rem;
        }

        .students-table th,
        .students-table td {
            padding: 0.75rem 0.5rem;
        }
    }
</style>

<div class="main-content">
    @include('layouts.header')

    <div class="content-area">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @include('partials.admin-page-context', [
            'section' => 'Pembayaran',
            'current' => 'Tagihan Siswa',
            'title' => 'Generate tagihan setelah data siswa dan jenis pembayaran siap.',
            'description' => 'Halaman ini mengelompokkan tagihan per sekolah dan kelas. Buka kelas untuk memproses pembayaran siswa satu per satu.',
            'steps' => ['Siswa', 'Jenis Pembayaran', 'Generate Tagihan', 'Bayar', 'Riwayat']
        ])

        <div class="page-header">
            <h2 class="page-title">
                <i class="fas fa-file-invoice-dollar"></i> 
                Manajemen Tagihan Siswa
            </h2>
            <div>
                <button type="button" class="btn-generate" data-bs-toggle="modal" data-bs-target="#generateModal">
                    <i class="fas fa-cogs"></i> Generate Otomatis
                </button>
            </div>
        </div>


        <!-- Generate Tagihan Modal -->
        <div class="modal fade" id="generateModal" tabindex="-1" aria-labelledby="generateModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="generateModalLabel">Generate Tagihan Otomatis</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Generate otomatis akan membuat tagihan untuk siswa yang sudah memiliki data sekolah, kelas, tahun ajaran, dan jenis pembayaran yang sesuai.</p>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> 
                            Periksa kembali data siswa dan jenis pembayaran sebelum menjalankan proses ini. Proses bisa memakan waktu beberapa menit.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('tagihan.generate.manual') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-cogs"></i> Generate Tagihan Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @forelse($sekolahData as $sekolah)
            <div class="school-section">
                <div class="school-header" onclick="toggleSchool({{ $sekolah->id }})">
                    <h3>
                        <span>
                            <i class="fas fa-school"></i>
                            {{ $sekolah->nama_sekolah }}
                        </span>
                        <i class="fas fa-chevron-down toggle-icon" id="school-icon-{{ $sekolah->id }}"></i>
                    </h3>
                </div>
                
                <div class="school-content" id="school-content-{{ $sekolah->id }}">
                    @forelse($sekolah->kelas as $kelas)
                        <div class="class-item">
                            <div class="class-header" onclick="toggleClass({{ $sekolah->id }}, {{ $kelas->id }})">
                                <h4>
                                    <i class="fas fa-users"></i>
                                    Kelas {{ $kelas->tingkat }} - {{ $kelas->nama_kelas }}
                                </h4>
                                <div class="class-stats">
                                    <div class="stat-item">
                                        <i class="fas fa-user-graduate"></i>
                                        <span>{{ $kelas->siswa_count }} siswa</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="fas fa-file-invoice"></i>
                                        <span>{{ $kelas->total_tagihan }} tagihan</span>
                                    </div>
                                    <i class="fas fa-chevron-down toggle-icon" id="class-icon-{{ $sekolah->id }}-{{ $kelas->id }}"></i>
                                </div>
                            </div>
                            
                            <div class="students-table-container" id="class-content-{{ $sekolah->id }}-{{ $kelas->id }}">
                                <table class="students-table">
                                    <thead>
                                        <tr>
                                            <th>Informasi Siswa</th>
                                            <th>Ringkasan Tagihan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="students-tbody-{{ $sekolah->id }}-{{ $kelas->id }}">
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                <i class="fas fa-spinner fa-spin"></i> Memuat data siswa...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @empty
                        @include('partials.admin-empty-state', [
                            'icon' => 'fas fa-users-slash',
                            'title' => 'Belum Ada Kelas di Sekolah Ini',
                            'message' => 'Tambahkan kelas terlebih dahulu, lalu masukkan siswa agar tagihan bisa dibuat.',
                            'actionRoute' => route('kelas.create'),
                            'actionText' => 'Tambah Kelas'
                        ])
                    @endforelse
                </div>
            </div>
        @empty
            @include('partials.admin-empty-state', [
                'icon' => 'fas fa-school',
                'title' => 'Belum Ada Data Sekolah',
                'message' => 'Tambahkan sekolah sebelum membuat kelas, siswa, dan tagihan pembayaran.',
                'actionRoute' => route('sekolah.create'),
                'actionText' => 'Tambah Sekolah'
            ])
        @endforelse
    </div>
</div>

<script>
// Tambahkan event listener untuk filter dengan Enter key
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const filterForm = document.getElementById('filterForm');
    
    if (searchInput && filterForm) {
        searchInput.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                filterForm.submit();
            }
        });
    }
});

function toggleSchool(sekolahId) {
    const content = document.getElementById(`school-content-${sekolahId}`);
    const icon = document.getElementById(`school-icon-${sekolahId}`);
    
    if (content.classList.contains('active')) {
        content.classList.remove('active');
        icon.classList.remove('rotated');
    } else {
        // Close all other schools
        document.querySelectorAll('.school-content').forEach(el => {
            el.classList.remove('active');
        });
        document.querySelectorAll('.school-header .toggle-icon').forEach(el => {
            el.classList.remove('rotated');
        });
        
        content.classList.add('active');
        icon.classList.add('rotated');
    }
}

function toggleClass(sekolahId, kelasId) {
    const content = document.getElementById(`class-content-${sekolahId}-${kelasId}`);
    const icon = document.getElementById(`class-icon-${sekolahId}-${kelasId}`);
    const tbody = document.getElementById(`students-tbody-${sekolahId}-${kelasId}`);
    
    if (content.classList.contains('active')) {
        content.classList.remove('active');
        icon.classList.remove('rotated');
    } else {
        // Close all other classes in this school
        document.querySelectorAll(`[id^="class-content-${sekolahId}-"]`).forEach(el => {
            el.classList.remove('active');
        });
        document.querySelectorAll(`[id^="class-icon-${sekolahId}-"]`).forEach(el => {
            el.classList.remove('rotated');
        });
        
        content.classList.add('active');
        icon.classList.add('rotated');
        
        // Load students data if not already loaded
        if (tbody.innerHTML.includes('Memuat data siswa')) {
            loadStudentsData(sekolahId, kelasId);
        }
    }
}

// Fungsi untuk memfilter siswa dalam kelas
function filterStudents(sekolahId, kelasId) {
    const searchInput = document.getElementById(`search-${sekolahId}-${kelasId}`);
    const searchTerm = searchInput.value.toLowerCase();
    
    const studentRows = document.querySelectorAll(`#students-tbody-${sekolahId}-${kelasId} tr`);
    let hasVisibleRow = false;
    
    studentRows.forEach(row => {
        const nameElement = row.querySelector('.student-name');
        const nisElement = row.querySelector('.student-nis');
        
        if (nameElement && nisElement) {
            const name = nameElement.textContent.toLowerCase();
            const nis = nisElement.textContent.toLowerCase();
            
            if (searchTerm === '' || name.includes(searchTerm) || nis.includes(searchTerm)) {
                row.style.display = '';
                hasVisibleRow = true;
            } else {
                row.style.display = 'none';
            }
        }
    });
    
    // Tampilkan pesan jika tidak ada hasil
    const noResultRow = document.getElementById(`no-result-${sekolahId}-${kelasId}`);
    if (noResultRow) {
        if (hasVisibleRow || searchTerm === '') {
            noResultRow.style.display = 'none';
        } else {
            noResultRow.style.display = '';
        }
    }
}

function loadStudentsData(sekolahId, kelasId) {
    const tbody = document.getElementById(`students-tbody-${sekolahId}-${kelasId}`);
    
    fetch(`/tagihan/get-students-summary/${sekolahId}/${kelasId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.students && data.students.length > 0) {
                // Bangun HTML untuk filter dan tabel
                let html = `
                    <tr>
                        <td colspan="3">
                            <div class="p-3">
                                <input type="text" 
                                       id="search-${sekolahId}-${kelasId}" 
                                       class="form-control" 
                                       placeholder="Cari nama atau NIS siswa..." 
                                       oninput="filterStudents(${sekolahId}, ${kelasId})"
                                       onkeydown="if(event.key === 'Enter'){ event.preventDefault(); }">
                            </div>
                        </td>
                    </tr>
                `;
                
                // Tambahkan baris untuk setiap siswa
                data.students.forEach(student => {
                    html += `
                        <tr>
                            <td>
                                <div class="student-info">
                                    <div class="student-name">${student.nama}</div>
                                    <div class="student-nis">NIS: ${student.nis}</div>
                                </div>
                            </td>
                            <td>
                                <div class="tagihan-summary">
                                    <div class="summary-item">
                                        <span class="summary-label">Total Tagihan:</span>
                                        <span class="summary-value">${student.total_tagihan}</span>
                                    </div>
                                    <div class="summary-item">
                                        <span class="summary-label">Total Nominal:</span>
                                        <span class="summary-value total">Rp ${student.total_nominal}</span>
                                    </div>
                                    <div class="summary-item">
                                        <span class="summary-label">Sudah Dibayar:</span>
                                        <span class="summary-value paid">Rp ${student.total_dibayar}</span>
                                    </div>
                                    <div class="summary-item">
                                        <span class="summary-label">Sisa:</span>
                                        <span class="summary-value remaining">Rp ${student.sisa_bayar}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="/tagihan/proses-siswa/${student.id}" class="btn-process">
                                    <i class="fas fa-credit-card"></i>
                                    Proses Tagihan
                                </a>
                            </td>
                        </tr>
                    `;
                });
                
                // Tambahkan baris "tidak ada hasil" yang tersembunyi secara default
                html += `
                    <tr id="no-result-${sekolahId}-${kelasId}" style="display: none;">
                        <td colspan="3" class="text-center">
                            <div class="empty-state">
                                <i class="fas fa-search"></i>
                                <p>Tidak ada siswa yang sesuai dengan filter pencarian</p>
                            </div>
                        </td>
                    </tr>
                `;
                
                tbody.innerHTML = html;
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center">
                            <div class="empty-state">
                                <i class="fas fa-user-slash"></i>
                                <p>Tidak ada siswa di kelas ini</p>
                            </div>
                        </td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading students:', error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="3" class="text-center text-red-500">
                        <i class="fas fa-exclamation-triangle"></i>
                        Gagal memuat data siswa
                    </td>
                </tr>
            `;
        });
}
</script>
@endsection
