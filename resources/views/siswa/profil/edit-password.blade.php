@extends('layouts.app')

@section('content')
<div class="main-content p-6">
    @include('layouts.sidebar-siswa')
    @include('layouts.header-siswa')

    <h1 class="text-2xl font-bold mb-4">Ubah Password</h1>

    <form action="{{ route('siswa.profil.updatePassword') }}" method="POST" class="bg-white p-6 rounded shadow max-w-lg">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-medium mb-1">Password Lama</label>
            <input type="password" name="current_password" class="w-full border rounded p-2" required>
            @error('current_password')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Password Baru</label>
            <input type="password" name="new_password" class="w-full border rounded p-2" required>
            @error('new_password')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Konfirmasi Password Baru</label>
            <input type="password" name="new_password_confirmation" class="w-full border rounded p-2" required>
        </div>

        <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800">
            Simpan Perubahan
        </button>
    </form>
</div>
@endsection
