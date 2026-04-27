<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated routes
Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Reports (all roles can view, but filtered by role in controller)
    Route::get('/reports', [TaskReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [TaskReportController::class, 'show'])->name('reports.show');

    // Shared Admin & Pimpinan routes
    Route::middleware('role:admin,pimpinan')->group(function () {
        Route::get('/export/reports/{report}', [ExportController::class, 'exportSingleReport'])->name('export.reports.single');
        Route::get('/export/reports/{report}/docx', [ExportController::class, 'exportWordPhotoDoc'])->name('export.reports.docx');
        Route::post('/reports/{report}/reject', [TaskReportController::class, 'reject'])->name('reports.reject');
    });

    // Pegawai report management
    Route::middleware('role:pegawai')->group(function () {
        Route::get('/reports-create', [TaskReportController::class, 'create'])->name('reports.create');
        Route::post('/reports', [TaskReportController::class, 'store'])->name('reports.store');
        Route::get('/reports/{report}/edit', [TaskReportController::class, 'edit'])->name('reports.edit');
        Route::put('/reports/{report}', [TaskReportController::class, 'update'])->name('reports.update');
        Route::post('/reports/{report}/submit', [TaskReportController::class, 'submit'])->name('reports.submit');
        Route::post('/reports/{report}/cancel', [TaskReportController::class, 'cancel'])->name('reports.cancel');
        Route::delete('/reports/{report}', [TaskReportController::class, 'destroy'])->name('reports.destroy');
        Route::post('/reports/{report}/upload-image', [TaskReportController::class, 'uploadImage'])->name('reports.upload-image');
        Route::delete('/report-images/{image}', [TaskReportController::class, 'deleteImage'])->name('reports.delete-image');
    });

    // Admin routes
    Route::middleware('role:admin')->group(function () {
        // User management
        Route::resource('users', UserController::class)->except(['show']);
        Route::post('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');

        // Configuration
        Route::get('/configuration', [ConfigurationController::class, 'index'])->name('configuration.index');
        Route::post('/configuration/type-signals', [ConfigurationController::class, 'storeTypeSignal'])->name('configuration.type-signals.store');
        Route::put('/configuration/type-signals/{typeSignal}', [ConfigurationController::class, 'updateTypeSignal'])->name('configuration.type-signals.update');
        Route::delete('/configuration/type-signals/{typeSignal}', [ConfigurationController::class, 'destroyTypeSignal'])->name('configuration.type-signals.destroy');
        Route::post('/configuration/points', [ConfigurationController::class, 'storePoint'])->name('configuration.points.store');
        Route::put('/configuration/points/{point}', [ConfigurationController::class, 'updatePoint'])->name('configuration.points.update');
        Route::delete('/configuration/points/{point}', [ConfigurationController::class, 'destroyPoint'])->name('configuration.points.destroy');
        Route::post('/configuration/gfd-items', [ConfigurationController::class, 'storeGfdItem'])->name('configuration.gfd-items.store');
        Route::put('/configuration/gfd-items/{gfdItem}', [ConfigurationController::class, 'updateGfdItem'])->name('configuration.gfd-items.update');
        Route::delete('/configuration/gfd-items/{gfdItem}', [ConfigurationController::class, 'destroyGfdItem'])->name('configuration.gfd-items.destroy');

        // Reordering
        Route::post('/configuration/reorder/type-signals', [ConfigurationController::class, 'reorderTypeSignals'])->name('configuration.type-signals.reorder');
        Route::post('/configuration/reorder/points', [ConfigurationController::class, 'reorderPoints'])->name('configuration.points.reorder');
        Route::post('/configuration/reorder/gfd-items', [ConfigurationController::class, 'reorderGfdItems'])->name('configuration.gfd-items.reorder');

        // Admin verify
        Route::post('/reports/{report}/verify', [TaskReportController::class, 'verify'])->name('reports.verify');
    });

    // Pimpinan review
    Route::middleware('role:pimpinan')->group(function () {
        Route::post('/reports/{report}/review', [TaskReportController::class, 'review'])->name('reports.review');
    });
});
