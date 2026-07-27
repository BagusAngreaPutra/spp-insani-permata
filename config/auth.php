<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | Guard dan password reset default. Untuk aplikasi ini kita pakai
    | guard "web" untuk admin secara default.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'admins', // default reset pakai admins
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Daftar semua guard yang digunakan. Kita pakai dua:
    | - web   : untuk admin
    | - siswa : untuk siswa
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        'siswa' => [
            'driver' => 'session',
            'provider' => 'siswas',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Mapping guard ke model. Pastikan modelnya sudah benar:
    | - App\Models\Admin  untuk admin
    | - App\Models\Siswa  untuk siswa
    |
    */

    'providers' => [
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],

        'siswas' => [
            'driver' => 'eloquent',
            'model' => App\Models\Siswa::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Kita buatkan dua juga kalau nanti perlu reset password berbeda:
    |
    */

    'passwords' => [
        'admins' => [
            'provider' => 'admins',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'siswas' => [
            'provider' => 'siswas',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Timeout konfirmasi password dalam detik (3 jam = 10800 detik).
    |
    */

    'password_timeout' => 10800,

];
