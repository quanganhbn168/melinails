<?php

use Illuminate\Support\Facades\Route;

// Worker routes - Redirected to admin routes after consolidation
Route::prefix('worker')->name('worker.')->middleware(['auth:admin'])->group(function () {
    // Redirect old routes to new consolidated admin routes
    Route::get('/my-jobs', fn() => redirect()->route('admin.work-orders.index'))->name('jobs');
    Route::get('/jobs/{id}', fn($id) => redirect()->route('admin.work-orders.show', $id))->name('jobs.detail');
    Route::get('/tasks/{id}', fn($id) => redirect()->route('admin.tasks.detail', $id))->name('tasks.detail');
    Route::get('/notifications', \App\Livewire\Worker\WorkerNotificationList::class)->name('notifications');
});
