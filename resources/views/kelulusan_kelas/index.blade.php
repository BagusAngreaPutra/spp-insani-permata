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

    .alert-success {
        background: linear-gradient(135deg, #d1fae5, #bbf7d0);
        border: 1px solid rgba(34, 197, 94, 0.2);
        color: #166534;
        padding: 1.25rem 1.5rem;
        border-radius: 16px;
        margin-bottom: 1.5rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    th, td {
        padding: 1rem 1.25rem;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }

    th {
        background: linear-gradient(135deg, #bbf7d0, #86efac);
        font-weight: 700;
        color: #065f46;
    }

    tr:last-child td {
        border-bottom: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: #fff;
        padding: 0.65rem 1.25rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
        box-shadow: 0 4px 12px rgba(34,197,94,0.3);
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(34,197,94,0.4);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: #fff;
        padding: 0.75rem 1.25rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
        box-shadow: 0 4px 12px rgba(59,130,246,0.3);
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59,130,246,0.4);
    }
</style>

<div class="main-content">
    @include('layouts.header')

    <div class="content-area">
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert-success" style="background: linear-gradient(135deg,#fee2e2,#fecaca);border:1px solid rgba(239,68,68,0.2);color:#991b1b;">
                {{ session('error') }}
            </div>
        @endif

        <div class="page-header">
            <h2 class="page-title">
                🎓 Siswa Siap Lulus
            </h2>
            <a href="{{ route('siswa.index') }}" class="btn-secondary">
                📋 Lihat Data Siswa
            </a>
        </div>

        {{-- FILTER FORM --}}
        <div class="table-container mb-6">
            <form method="GET" action="{{ route('kelulusan.index') }}" class="flex flex-wrap gap-4 p-6 bg-white bg-opacity-80 backdrop-blur-lg rounded-2xl shadow-md">

                {{-- Sekolah --}}
                <div class="flex flex-col">
                    <label for="sekolah_id" class="text-sm font-semibold text-green-700 mb-1">Sekolah</label>
                    <select name="sekolah_id" id="sekolah_id" onchange="this.form.submit()" class="rounded-xl px-4 py-2 border border-green-200 focus:ring focus:ring-green-400">
                        <option value="">-- Pilih Sekolah --</option>
                        @foreach ($sekolah as $s)
                            <option value="{{ $s->id }}" {{ $selectedSekolah == $s->id ? 'selected' : '' }}>{{ $s->nama_sekolah }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Kelas --}}
                <div class="flex flex-col">
                    <label for="kelas_id" class="text-sm font-semibold text-green-700 mb-1">Kelas</label>
                    <select name="kelas_id" id="kelas_id" onchange="this.form.submit()" class="rounded-xl px-4 py-2 border border-green-200 focus:ring focus:ring-green-400">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($kelas as $k)
                            <option value="{{ $k->id }}" {{ $selectedKelas == $k->id ? 'selected' : '' }}>Tingkat {{ $k->tingkat }} {{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tahun Ajaran --}}
                <div class="flex flex-col">
                    <label for="tahun_ajaran_id" class="text-sm font-semibold text-green-700 mb-1">Tahun Ajaran</label>
                    <select name="tahun_ajaran_id" id="tahun_ajaran_id" onchange="this.form.submit()" class="rounded-xl px-4 py-2 border border-green-200 focus:ring focus:ring-green-400">
                        <option value="">-- Pilih Tahun Ajaran --</option>
                        @foreach ($tahunAjaran as $ta)
                            <option value="{{ $ta->id }}" {{ $selectedTahunAjaran == $ta->id ? 'selected' : '' }}>
                                {{ $ta->nama_tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Pencarian --}}
                <div class="flex flex-col flex-grow">
                    <label for="search" class="text-sm font-semibold text-green-700 mb-1">Cari Nama / NIS</label>
                    <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Ketik nama atau NIS" onkeydown="if(event.key === 'Enter'){ this.form.submit(); }"
                        class="rounded-xl px-4 py-2 border border-green-200 focus:ring focus:ring-green-400 w-full">
                </div>

                {{-- Tombol --}}
                <div class="flex flex-col justify-end">
                    <label class="text-sm font-semibold text-transparent mb-1">Tombol</label>
                    <button type="submit" class="btn-primary">🔍 Filter</button>
                </div>
            </form>
        </div>


        <div class="form-container" style="padding:0; box-shadow:none; background:none;">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswaSiapLulus as $i => $siswa)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $siswa->nis }}</td>
                            <td>{{ $siswa->nama }}</td>
                            <td>
                                {{ $siswa->kelas?->nama_kelas }} 
                                (Tingkat {{ $siswa->kelas?->tingkat }})
                            </td>
                            <td>
                                @if($siswa->status === 'lulus')
                                    ✅ Lulus
                                @else
                                    🔄 Belum Lulus
                                @endif
                            </td>
                            <td>
                                @if($siswa->status !== 'lulus')
                                    <form action="{{ route('kelulusan.updateStatus', $siswa->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-primary">Luluskan</button>
                                    </form>
                                @else
                                    <span style="color:green;font-weight:600;">✔ Sudah Lulus</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:2rem;">
                                Tidak ada siswa yang siap lulus berdasarkan durasi pendidikan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
