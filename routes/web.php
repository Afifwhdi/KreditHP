<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// Route untuk redirect ke admin/login
Route::get('/', function () {
    return redirect()->route('filament.admin.auth.login');
});
