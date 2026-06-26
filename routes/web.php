<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\KnowledgeBaseArticleController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the Route::group and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// Ticket Routes
Route::middleware(['auth'])->group(function () {
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/categories/fields', [TicketController::class, 'getCategoryFields'])->name('category-fields');
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::get('/create', [TicketController::class, 'create'])->name('create');
        Route::post('/', [TicketController::class, 'store'])->name('store');
        Route::get('/{ticket}', [TicketController::class, 'show'])->name('show');
        Route::get('/{ticket}/edit', [TicketController::class, 'edit'])->name('edit');
        Route::put('/{ticket}', [TicketController::class, 'update'])->name('update');
        Route::delete('/{ticket}', [TicketController::class, 'destroy'])->name('destroy');

        // Ticket Tracking & History
        Route::get('/{ticket}/track', [TicketController::class, 'track'])->name('track');
        Route::post('/{ticket}/update', [TicketController::class, 'addUpdate'])->name('addUpdate');
        
        // Rating
        Route::post('/{ticket}/rate', [TicketController::class, 'rate'])->name('rate');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/data', [ReportController::class, 'chartData'])->name('data');
        Route::get('/export', [ReportController::class, 'export'])->name('export');
    });

    // Knowledge Base (public)
    Route::get('/knowledge-base', [KnowledgeBaseArticleController::class, 'index'])->name('knowledge-base.index');
    Route::get('/knowledge-base/{id}', [KnowledgeBaseArticleController::class, 'show'])->name('knowledge-base.show');

    // Notifications
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::get('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'chartData'])->name('dashboard.data');
});


require __DIR__.'/auth.php';
require __DIR__.'/master-data.php';