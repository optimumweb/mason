<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\ConfigurationController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\EntryController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\TaxonomyController;
use App\Http\Controllers\Backend\MenuController;
use App\Http\Controllers\Backend\SettingsController;

Route::middleware(['auth'])->prefix('/backend')->name('backend.')->group(function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::prefix('/entries/{entryType:name}')->name('entries.')->group(function () {
        Route::get('/', [EntryController::class, 'index'])->name('index');
        Route::get('/create', [EntryController::class, 'create'])->name('create');
        Route::get('/{entry}', [EntryController::class, 'show'])->name('show');
        Route::get('/{entry}/edit', [EntryController::class, 'edit'])->name('edit');
        Route::patch('/{entry}', [EntryController::class, 'update'])->name('update');
        Route::get('/{entry}/destroy', [EntryController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/taxonomies/{taxonomyType:name}')->name('taxonomies.')->group(function () {
        Route::get('/', [TaxonomyController::class, 'index'])->name('index');
        Route::get('/create', [TaxonomyController::class, 'create'])->name('create');
        Route::get('/{taxonomy}', [TaxonomyController::class, 'show'])->name('show');
        Route::get('/{taxonomy}/edit', [TaxonomyController::class, 'edit'])->name('edit');
        Route::patch('/{taxonomy}', [TaxonomyController::class, 'update'])->name('update');
        Route::get('/{taxonomy}/destroy', [TaxonomyController::class, 'destroy'])->name('destroy');
    });

    Route::resource('menus', MenuController::class);

    Route::resource('users', UserController::class);

    Route::prefix('/configuration')->name('configuration.')->group(function () {
        Route::get('/', [ConfigurationController::class, 'general'])->name('general');

        Route::resource('setting', SettingsController::class);
    });
});