<?php

use Illuminate\Support\Facades\Route;

// Route untuk redirect ke admin/login
Route::get('/', function () {
    return redirect()->route('filament.admin.auth.login');
});
