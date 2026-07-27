<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- ✅ Notifikasi dengan tombol OK --}}
        @if (session('status') === 'password-updated')
            <div x-data="{ show: true }" x-show="show" x-transition
                class="flex items-start justify-between mb-4 p-4 rounded-lg bg-green-100 border border-green-400 text-green-700">
                <span>{{ __('Kata sandi berhasil diperbarui.') }}</span>
                <button @click="show = false"
                        class="ml-4 text-green-700 hover:text-green-900 font-bold">
                    OK
                </button>
            </div>
        @endif

        @if (session('status') === 'account-deleted')
            <div x-data="{ show: true }" x-show="show" x-transition
                class="flex items-start justify-between mb-4 p-4 rounded-lg bg-red-100 border border-red-400 text-red-700">
                <span>{{ __('Akun Anda berhasil dihapus.') }}</span>
                <button @click="show = false"
                        class="ml-4 text-red-700 hover:text-red-900 font-bold">
                    OK
                </button>
            </div>
        @endif

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <!-- Profile Information -->
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Profile Information') }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Update your account’s profile information.') }}
                        </p>
                    </header>

                    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                        @csrf
                        @method('patch')

                        <!-- Nama Admin -->
                        <div>
                            <x-input-label for="nama_admin" :value="__('Nama Admin')" />
                            <x-text-input id="nama_admin" name="nama_admin" type="text"
                                          class="mt-1 block w-full"
                                          :value="old('nama_admin', $admin->nama_admin)"
                                          required autofocus autocomplete="name" />
                            <x-input-error class="mt-2" :messages="$errors->get('nama_admin')" />
                        </div>

                        <!-- Username -->
                        <div class="mt-4">
                            <x-input-label for="username" :value="__('Username')" />
                            <x-text-input id="username" name="username" type="text"
                                          class="mt-1 block w-full"
                                          :value="old('username', $admin->username)"
                                          required autocomplete="username" />
                            <x-input-error class="mt-2" :messages="$errors->get('username')" />
                        </div>

                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>

                            @if (session('status') === 'profile-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition
                                   x-init="setTimeout(() => show = false, 2000)"
                                   class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('Saved.') }}
                                </p>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Update Password -->
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    @include('profile.partials.update-password-form')
                </div>

                <!-- Delete Account -->
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    @include('profile.partials.delete-user-form')
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
