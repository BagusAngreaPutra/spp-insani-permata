<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Perbarui Kata Sandi') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Pastikan akun admin Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.') }}
        </p>
    </header>

   {{-- ✅ Notifikasi sukses --}}
    @if (session('status') === 'password-updated')
        <div class="mb-4 p-3 rounded-md bg-green-100 border border-green-300 text-green-700">
            ✅ Kata sandi berhasil diperbarui.
        </div>
    @endif

    {{-- ❌ Notifikasi gagal aturan --}}
    @if (session('status') === 'password-invalid')
        <div class="mb-4 p-3 rounded-md bg-red-100 border border-red-300 text-red-700">
            ❌ Kata sandi baru tidak memenuhi ketentuan. Silakan periksa kembali.
        </div>
    @endif



    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div>
            <x-input-label for="current_password" :value="__('Kata Sandi Saat Ini')" />
            <x-text-input id="current_password" name="current_password" type="password"
                class="mt-1 block w-full"
                autocomplete="current-password" />
            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
        </div>

        <!-- New Password -->
        <div>
            <x-input-label for="password" :value="__('Kata Sandi Baru')" />
            <x-text-input id="password" name="password" type="password"
                class="mt-1 block w-full"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi Baru')" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                class="mt-1 block w-full"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Simpan') }}</x-primary-button>
        </div>
    </form>
</section>
