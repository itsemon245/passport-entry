<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index()
    {

    }

    public function downloadLatest()
    {
        $files      = scandir(storage_path('app/public/backups'));
        $lastBackup = array_pop($files);
        return Storage::download('public/backups/' . $lastBackup);
    }
}
