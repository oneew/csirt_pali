<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Api\IncidentApiController;
// use App\Http\Controllers\Api\NewsApiController;
use App\Http\Controllers\Api\UserApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API Routes (no authentication required)
Route::prefix('public')->group(function () {
    // Route::get('/news', [NewsApiController::class, 'publicIndex']);
    // Route::get('/news/{news:slug}', [NewsApiController::class, 'publicShow']);
    Route::get('/services', [\App\Http\Controllers\Api\ServiceApiController::class, 'publicIndex']);
    Route::get('/gallery', [\App\Http\Controllers\Api\GalleryApiController::class, 'publicIndex']);
    Route::post('/contact', [\App\Http\Controllers\Api\ContactApiController::class, 'store']);
});

// Authenticated API Routes
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard APIs
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);
    Route::get('/dashboard/activities', [DashboardController::class, 'getRecentActivitiesApi']);
    Route::get('/dashboard/notifications-count', [DashboardController::class, 'getNotificationsCount']);
    
    // Notifications API
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'getNotifications']);
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount']);
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
        Route::delete('/{notification}', [NotificationController::class, 'destroy']);
    });
    
    // User Profile API
    Route::get('/profile', [UserApiController::class, 'getProfile']);
    Route::put('/profile', [UserApiController::class, 'updateProfile']);
    Route::post('/profile/avatar', [UserApiController::class, 'updateAvatar']);
    Route::delete('/profile/avatar', [UserApiController::class, 'removeAvatar']);
    
    // File Upload APIs
    Route::post('/upload/image', [\App\Http\Controllers\Api\FileUploadController::class, 'uploadImage']);
    Route::post('/upload/document', [\App\Http\Controllers\Api\FileUploadController::class, 'uploadDocument']);
    Route::delete('/files/{file}', [\App\Http\Controllers\Api\FileUploadController::class, 'deleteFile']);
});

// Admin API Routes
Route::middleware(['auth', 'verified', 'role:admin,operator'])->prefix('admin')->group(function () {
    
    // Incidents API
    Route::prefix('incidents')->group(function () {
        Route::get('/', [IncidentApiController::class, 'index']);
        Route::post('/', [IncidentApiController::class, 'store']);
        Route::get('/{incident}', [IncidentApiController::class, 'show']);
        Route::put('/{incident}', [IncidentApiController::class, 'update']);
        Route::delete('/{incident}', [IncidentApiController::class, 'destroy']);
        Route::post('/{incident}/assign', [IncidentApiController::class, 'assign']);
        Route::post('/{incident}/status', [IncidentApiController::class, 'updateStatus']);
        Route::post('/{incident}/comments', [IncidentApiController::class, 'addComment']);
        Route::get('/search/suggestions', [IncidentApiController::class, 'searchSuggestions']);
        Route::get('/export/csv', [IncidentApiController::class, 'exportCsv']);
    });
    
    // News API
    /*Route::prefix('news')->group(function () {
        Route::get('/', [NewsApiController::class, 'index']);
        Route::post('/', [NewsApiController::class, 'store']);
        Route::get('/{news}', [NewsApiController::class, 'show']);
        Route::put('/{news}', [NewsApiController::class, 'update']);
        Route::delete('/{news}', [NewsApiController::class, 'destroy']);
        Route::post('/{news}/publish', [NewsApiController::class, 'publish']);
        Route::post('/{news}/unpublish', [NewsApiController::class, 'unpublish']);
        Route::post('/{news}/archive', [NewsApiController::class, 'archive']);
        Route::post('/{news}/featured', [NewsApiController::class, 'toggleFeatured']);
        Route::post('/bulk-action', [NewsApiController::class, 'bulkAction']);
        Route::get('/search/suggestions', [NewsApiController::class, 'searchSuggestions']);
    });*/
    
    // Users API (Admin only)
    Route::middleware('role:admin')->prefix('users')->group(function () {
        Route::get('/', [UserApiController::class, 'index']);
        Route::post('/', [UserApiController::class, 'store']);
        Route::get('/{user}', [UserApiController::class, 'show']);
        Route::put('/{user}', [UserApiController::class, 'update']);
        Route::delete('/{user}', [UserApiController::class, 'destroy']);
        Route::post('/{user}/toggle-status', [UserApiController::class, 'toggleStatus']);
        Route::post('/{user}/role', [UserApiController::class, 'updateRole']);
        Route::post('/{user}/permissions', [UserApiController::class, 'updatePermissions']);
        Route::post('/bulk-action', [UserApiController::class, 'bulkAction']);
        Route::get('/search/suggestions', [UserApiController::class, 'searchSuggestions']);
    });
    
    // Gallery API
    Route::prefix('gallery')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\GalleryApiController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\GalleryApiController::class, 'store']);
        Route::get('/{gallery}', [\App\Http\Controllers\Api\GalleryApiController::class, 'show']);
        Route::put('/{gallery}', [\App\Http\Controllers\Api\GalleryApiController::class, 'update']);
        Route::delete('/{gallery}', [\App\Http\Controllers\Api\GalleryApiController::class, 'destroy']);
        Route::post('/{gallery}/featured', [\App\Http\Controllers\Api\GalleryApiController::class, 'toggleFeatured']);
        Route::post('/reorder', [\App\Http\Controllers\Api\GalleryApiController::class, 'reorder']);
    });
    
    // Contacts API
    Route::prefix('contacts')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\ContactApiController::class, 'index']);
        Route::get('/{contact}', [\App\Http\Controllers\Api\ContactApiController::class, 'show']);
        Route::put('/{contact}', [\App\Http\Controllers\Api\ContactApiController::class, 'update']);
        Route::delete('/{contact}', [\App\Http\Controllers\Api\ContactApiController::class, 'destroy']);
        Route::post('/{contact}/contacted', [\App\Http\Controllers\Api\ContactApiController::class, 'markContacted']);
        Route::post('/{contact}/resolved', [\App\Http\Controllers\Api\ContactApiController::class, 'markResolved']);
        Route::post('/{contact}/notes', [\App\Http\Controllers\Api\ContactApiController::class, 'addNotes']);
    });
    
    // Services API
    Route::prefix('services')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\ServiceApiController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\ServiceApiController::class, 'store']);
        Route::get('/{service}', [\App\Http\Controllers\Api\ServiceApiController::class, 'show']);
        Route::put('/{service}', [\App\Http\Controllers\Api\ServiceApiController::class, 'update']);
        Route::delete('/{service}', [\App\Http\Controllers\Api\ServiceApiController::class, 'destroy']);
        Route::post('/{service}/toggle-active', [\App\Http\Controllers\Api\ServiceApiController::class, 'toggleActive']);
        Route::post('/{service}/featured', [\App\Http\Controllers\Api\ServiceApiController::class, 'toggleFeatured']);
        Route::post('/reorder', [\App\Http\Controllers\Api\ServiceApiController::class, 'reorder']);
    });
    
    // Settings API
    Route::prefix('settings')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\SettingApiController::class, 'index']);
        Route::get('/group/{group}', [\App\Http\Controllers\Api\SettingApiController::class, 'getByGroup']);
        Route::put('/', [\App\Http\Controllers\Api\SettingApiController::class, 'update']);
        Route::put('/group/{group}', [\App\Http\Controllers\Api\SettingApiController::class, 'updateGroup']);
    });
    
    // Activity Logs API
    Route::prefix('activity-logs')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\ActivityLogApiController::class, 'index']);
        Route::get('/user/{user}', [\App\Http\Controllers\Api\ActivityLogApiController::class, 'getUserActivity']);
        Route::get('/model/{modelType}/{modelId}', [\App\Http\Controllers\Api\ActivityLogApiController::class, 'getModelActivity']);
        Route::delete('/{activityLog}', [\App\Http\Controllers\Api\ActivityLogApiController::class, 'destroy']);
        Route::post('/cleanup', [\App\Http\Controllers\Api\ActivityLogApiController::class, 'cleanup']);
    });
    
    // Analytics & Reports API
    Route::prefix('analytics')->group(function () {
        Route::get('/overview', [\App\Http\Controllers\Api\AnalyticsController::class, 'overview']);
        Route::get('/incidents/trend', [\App\Http\Controllers\Api\AnalyticsController::class, 'incidentsTrend']);
        Route::get('/incidents/by-severity', [\App\Http\Controllers\Api\AnalyticsController::class, 'incidentsBySeverity']);
        Route::get('/incidents/by-category', [\App\Http\Controllers\Api\AnalyticsController::class, 'incidentsByCategory']);
        Route::get('/users/activity', [\App\Http\Controllers\Api\AnalyticsController::class, 'userActivity']);
        Route::get('/news/performance', [\App\Http\Controllers\Api\AnalyticsController::class, 'newsPerformance']);
    });
    
    // Search API
    Route::prefix('search')->group(function () {
        Route::get('/global', [\App\Http\Controllers\Api\SearchController::class, 'global']);
        Route::get('/incidents', [\App\Http\Controllers\Api\SearchController::class, 'incidents']);
        Route::get('/news', [\App\Http\Controllers\Api\SearchController::class, 'news']);
        Route::get('/users', [\App\Http\Controllers\Api\SearchController::class, 'users']);
        Route::get('/suggestions', [\App\Http\Controllers\Api\SearchController::class, 'suggestions']);
    });
});

// Real-time APIs (using broadcasting)
Route::middleware(['auth', 'verified'])->prefix('realtime')->group(function () {
    Route::post('/ping', function () {
        return response()->json(['status' => 'ok', 'timestamp' => now()]);
    });
    
    Route::get('/notifications/stream', [NotificationController::class, 'stream']);
    Route::get('/activities/stream', [\App\Http\Controllers\Api\ActivityLogApiController::class, 'stream']);
});

// Health Check & System Status
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'version' => '1.0.0',
        'environment' => app()->environment()
    ]);
});

// API Documentation route
Route::get('/docs', function () {
    return response()->json([
        'message' => 'CSIRT PALI API Documentation',
        'version' => '1.0.0',
        'endpoints' => [
            'Authentication' => '/api/auth/*',
            'Dashboard' => '/api/dashboard/*',
            'Incidents' => '/api/admin/incidents/*',
            'News' => '/api/admin/news/*',
            'Users' => '/api/admin/users/*',
            'Notifications' => '/api/notifications/*',
            'Analytics' => '/api/admin/analytics/*',
        ]
    ]);
});