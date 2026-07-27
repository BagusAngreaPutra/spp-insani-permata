<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupDatabaseController extends Controller
{
    public function index()
    {
        // daftar semua file backup
        $backupPath = storage_path('app/Laravel');
        $files = collect(File::files($backupPath))->map(function ($file) {
            return [
                'name' => $file->getFilename(),
                'size' => round($file->getSize() / 1024 / 1024, 2) . ' MB',
                'created_at' => date('Y-m-d H:i:s', $file->getCTime()),
            ];
        })->sortByDesc('created_at');

        return view('backup.index', ['files' => $files]);
    }

    public function download($fileName)
    {
        $path = storage_path('app/Laravel/' . $fileName);
        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan');
        }
        return response()->download($path);
    }

    public function create()
    {
        // jalankan backup secara manual
        \Artisan::call('backup:run');
        return redirect()->route('backup.index')->with('success', 'Backup berhasil dibuat!');
    }

}
