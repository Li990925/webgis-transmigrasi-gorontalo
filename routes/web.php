<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataManagementController;
use App\Http\Controllers\ExecutiveDashboardController;
use App\Http\Controllers\KawasanTransmigrasiController;
use Illuminate\Support\Facades\Route;

// Public WebGIS & Tabular Data
Route::get('/', [KawasanTransmigrasiController::class, 'index'])->name('beranda');
Route::get('/api/transmigrasi/geojson', [KawasanTransmigrasiController::class, 'geojson'])->name('api.geojson');
Route::get('/kawasan/{kawasanTransmigrasi}', [KawasanTransmigrasiController::class, 'show'])->name('kawasan.show');

// Web Authentication & Session Persona Switcher
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'loginWeb'])->name('login.post');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logoutWeb'])->name('logout');
Route::get('/switch-persona', fn () => redirect()->route('admin.dashboard'));
Route::post('/switch-persona', [AuthController::class, 'switchRoleWeb'])->name('switch-persona.web');

// Operasional CRUD & Geometry Editing (JSON / API & Web Form fallback)
Route::post('/kawasan', [KawasanTransmigrasiController::class, 'store'])->name('kawasan.store');
Route::put('/kawasan/{kawasanTransmigrasi}', [KawasanTransmigrasiController::class, 'update'])->name('kawasan.update');
Route::patch('/api/kawasan/{kawasanTransmigrasi}/geometry', [KawasanTransmigrasiController::class, 'updateGeometry'])->name('kawasan.geometry');
Route::delete('/kawasan/{kawasanTransmigrasi}', [KawasanTransmigrasiController::class, 'destroy'])->name('kawasan.destroy');

// RBAC API Authentication & Persona Switcher (For fetch / SPA)
Route::get('/api/auth/status', [AuthController::class, 'status'])->name('auth.status');
Route::post('/api/auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/api/auth/switch-role', [AuthController::class, 'switchRole'])->name('auth.switch-role');
Route::post('/api/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Executive Dashboard & Advanced Analytics & Audit Logs API
Route::get('/api/analytics/dashboard', [ExecutiveDashboardController::class, 'analyticsData'])->name('analytics.dashboard');
Route::get('/api/audit-logs', [ExecutiveDashboardController::class, 'auditLogs'])->name('audit.logs');

// Data Management: Excel Export, Template, Mass Import, Official Print Report
Route::get('/data/export', [DataManagementController::class, 'export'])->name('data.export');
Route::get('/data/template', [DataManagementController::class, 'downloadTemplate'])->name('data.template');
Route::post('/data/import', [DataManagementController::class, 'import'])->name('data.import');
Route::get('/laporan/cetak', [DataManagementController::class, 'printReport'])->name('laporan.cetak');

// Dedicated Back-office Admin Panel (Protected by Auth)
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/kawasan', [AdminController::class, 'kawasanIndex'])->name('kawasan.index');
    Route::get('/kawasan/create', [AdminController::class, 'kawasanCreate'])->name('kawasan.create');
    Route::post('/kawasan', [KawasanTransmigrasiController::class, 'store'])->name('kawasan.store');
    Route::get('/kawasan/{kawasanTransmigrasi}/edit', [AdminController::class, 'kawasanEdit'])->name('kawasan.edit');
    Route::put('/kawasan/{kawasanTransmigrasi}', [KawasanTransmigrasiController::class, 'update'])->name('kawasan.update');
    Route::delete('/kawasan/{kawasanTransmigrasi}', [KawasanTransmigrasiController::class, 'destroy'])->name('kawasan.destroy');
    Route::get('/kawasan/{kawasanTransmigrasi}/spasial', [AdminController::class, 'kawasanSpasial'])->name('kawasan.spasial');
    Route::get('/klasterisasi', [AdminController::class, 'klasterisasi'])->name('klasterisasi');
    Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('audit-logs');
    Route::post('/audit-logs/mark-as-read', [AdminController::class, 'markAuditLogsAsRead'])->name('audit-logs.read');
    Route::resource('users', UserController::class)->except(['show']);
});
