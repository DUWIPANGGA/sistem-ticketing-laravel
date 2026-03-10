<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\KnowledgeBaseArticleController;
use App\Http\Controllers\CategoryController;
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

    // Report
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    // Users
    Route::resource('users', UserController::class);

    // Knowledge Base
    Route::resource('knowledge-base', KnowledgeBaseArticleController::class);

    // Categories
    Route::resource('categories', CategoryController::class);

    // Notifications
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::get('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
});

Route::get('/dashboard', function () {
    $user = Illuminate\Support\Facades\Auth::user();
    $query = App\Models\Ticket::query();

    if (!in_array($user->role, ['admin', 'technician'])) {
        $query->where('user_id', $user->id);
    }

    $totalTickets = $query->count();
    
    // Have to clone the query since count() executes it but doesn't necessarily consume it in a way that prevents cloning, 
    // actually it's better to recreate or clone prior to count.
    
    $openTickets = (clone $query)->where('status', 'open')->count();
    $resolvedTickets = (clone $query)->where('status', 'resolved')->count();
    $slaBreachedTickets = (clone $query)->whereNotNull('sla_due_at')
                                        ->where('sla_due_at', '<', now())
                                        ->whereNotIn('status', ['resolved', 'closed'])
                                        ->count();

    return view('dashboard', compact('totalTickets', 'openTickets', 'resolvedTickets', 'slaBreachedTickets'));
})->middleware(['auth'])->name('dashboard');


require __DIR__.'/auth.php';