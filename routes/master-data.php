<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterData\UserController;
use App\Http\Controllers\MasterData\CategoryController;
use App\Http\Controllers\MasterData\DivisionController;
use App\Http\Controllers\MasterData\KnowledgeBaseController;
use App\Http\Controllers\MasterData\PriorityController;

Route::middleware(['auth'])->prefix('master-data')->name('master-data.')->group(function () {
    Route::resource('users', UserController::class)->except(['create', 'store']);
    Route::resource('categories', CategoryController::class);
    Route::resource('divisions', DivisionController::class);
    Route::resource('priorities', PriorityController::class);
    Route::resource('knowledge-base', KnowledgeBaseController::class);
});
