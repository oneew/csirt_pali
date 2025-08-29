<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\IncidentController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PublicNewsController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\GalleryController;
use App\Http\Controllers\Frontend\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
Route::get('/services', [ServiceController::class, 'index'])->name('services');
Route::get('/services/{service:slug}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/news', [PublicNewsController::class, 'index'])->name('news.index');
Route::get('/news/{news:slug}', [PublicNewsController::class, 'show'])->name('news.show');
Route::get('/news/category/{category}', [PublicNewsController::class, 'category'])->name('news.category');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Email Verification Routes
    Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
    Route::post('/email/verification-notification', [AuthController::class, 'resendVerification'])->name('verification.send');
});

// Protected User Dashboard Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
});

// Admin Routes
Route::middleware(['auth', 'verified', 'role:admin,operator'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Incidents Management
    Route::resource('incidents', IncidentController::class);
    Route::post('/incidents/{incident}/assign', [IncidentController::class, 'assign'])->name('incidents.assign');
    Route::post('/incidents/{incident}/status', [IncidentController::class, 'updateStatus'])->name('incidents.status');
    Route::get('/incidents-export', [IncidentController::class, 'export'])->name('incidents.export');
    
    // News Management
    Route::resource('news', NewsController::class);
    Route::post('/news/{news}/publish', [NewsController::class, 'publish'])->name('news.publish');
    Route::post('/news/{news}/unpublish', [NewsController::class, 'unpublish'])->name('news.unpublish');
    Route::post('/news/{news}/archive', [NewsController::class, 'archive'])->name('news.archive');
    Route::post('/news/{news}/featured', [NewsController::class, 'toggleFeatured'])->name('news.featured');
    Route::post('/news/{news}/duplicate', [NewsController::class, 'duplicate'])->name('news.duplicate');
    Route::post('/news/bulk-action', [NewsController::class, 'bulkAction'])->name('news.bulk');
    Route::get('/news-export', [NewsController::class, 'export'])->name('news.export');
    
    // Users Management (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::post('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.role');
        Route::post('/users/{user}/permissions', [UserController::class, 'updatePermissions'])->name('users.permissions');
        Route::post('/users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk');
        Route::get('/users-export', [UserController::class, 'export'])->name('users.export');
    });
    
    // Gallery Management
    Route::resource('gallery', \App\Http\Controllers\Admin\GalleryController::class);
    Route::post('/gallery/{gallery}/featured', [\App\Http\Controllers\Admin\GalleryController::class, 'toggleFeatured'])->name('gallery.featured');
    Route::post('/gallery/bulk-action', [\App\Http\Controllers\Admin\GalleryController::class, 'bulkAction'])->name('gallery.bulk');
    
    // Contacts Management
    Route::resource('contacts', \App\Http\Controllers\Admin\ContactController::class);
    Route::post('/contacts/{contact}/contacted', [\App\Http\Controllers\Admin\ContactController::class, 'markContacted'])->name('contacts.contacted');
    Route::post('/contacts/{contact}/resolved', [\App\Http\Controllers\Admin\ContactController::class, 'markResolved'])->name('contacts.resolved');
    Route::post('/contacts/{contact}/notes', [\App\Http\Controllers\Admin\ContactController::class, 'addNotes'])->name('contacts.notes');
    Route::post('/contacts/bulk-action', [\App\Http\Controllers\Admin\ContactController::class, 'bulkAction'])->name('contacts.bulk');
    Route::get('/contacts-export', [\App\Http\Controllers\Admin\ContactController::class, 'export'])->name('contacts.export');
    
    // Services Management
    Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class);
    Route::post('/services/{service}/toggle-active', [\App\Http\Controllers\Admin\ServiceController::class, 'toggleActive'])->name('services.toggle-active');
    Route::post('/services/{service}/featured', [\App\Http\Controllers\Admin\ServiceController::class, 'toggleFeatured'])->name('services.featured');
    Route::post('/services/bulk-action', [\App\Http\Controllers\Admin\ServiceController::class, 'bulkAction'])->name('services.bulk');
    Route::get('/services-export', [\App\Http\Controllers\Admin\ServiceController::class, 'export'])->name('services.export');
    
    // Settings Management (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/reset', [\App\Http\Controllers\Admin\SettingController::class, 'reset'])->name('settings.reset');
        Route::get('/settings/export', [\App\Http\Controllers\Admin\SettingController::class, 'export'])->name('settings.export');
    });
    
    // Notifications
    Route::resource('notifications', \App\Http\Controllers\Admin\NotificationController::class);
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::post('/notifications/bulk-action', [\App\Http\Controllers\Admin\NotificationController::class, 'bulkAction'])->name('notifications.bulk');
    Route::get('/notifications-export', [\App\Http\Controllers\Admin\NotificationController::class, 'export'])->name('notifications.export');
    
    // Profile Management
    Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    
    // Activity Logs
    Route::get('/activity-logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/{activityLog}', [\App\Http\Controllers\Admin\ActivityLogController::class, 'show'])->name('activity-logs.show');
    Route::delete('/activity-logs/{activityLog}', [\App\Http\Controllers\Admin\ActivityLogController::class, 'destroy'])->name('activity-logs.destroy');
    Route::post('/activity-logs/bulk-action', [\App\Http\Controllers\Admin\ActivityLogController::class, 'bulkAction'])->name('activity-logs.bulk');
    Route::post('/activity-logs/cleanup', [\App\Http\Controllers\Admin\ActivityLogController::class, 'cleanup'])->name('activity-logs.cleanup');
    Route::get('/activity-logs/export', [\App\Http\Controllers\Admin\ActivityLogController::class, 'export'])->name('activity-logs.export');
    Route::get('/activity-logs/statistics', [\App\Http\Controllers\Admin\ActivityLogController::class, 'statistics'])->name('activity-logs.statistics');
    
    // Reports
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/incidents', [\App\Http\Controllers\Admin\ReportController::class, 'incidents'])->name('reports.incidents');
    Route::get('/reports/users', [\App\Http\Controllers\Admin\ReportController::class, 'users'])->name('reports.users');
    Route::get('/reports/security', [\App\Http\Controllers\Admin\ReportController::class, 'security'])->name('reports.security');
    Route::get('/reports/performance', [\App\Http\Controllers\Admin\ReportController::class, 'performance'])->name('reports.performance');
});