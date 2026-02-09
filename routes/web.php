<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public welcome page
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [UserController::class, 'index'])
        ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get('/role-permissions', [UserController::class, 'showRolePermissions'])
        ->name('role.permissions');

    Route::middleware('custom_permission')->group(function () {
        Route::get('/permissions', [PermissionController::class, 'index'])
            ->name('permissions.index');
        Route::post('/permissions', [PermissionController::class, 'store'])
            ->name('permissions.store');

        Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])
            ->name('permissions.destroy');

        Route::get('/users', [UserController::class, 'index1'])
            ->name('users.index');

        Route::get('/users1', [UserController::class, 'index1'])
            ->name('users.index__view');

        Route::post('/users', [UserController::class, 'store'])
            ->name('users.store');

        Route::put('/users/{user}/role', [UserController::class, 'updateRole'])
            ->name('users.updateRole');

        Route::post('/users/{user}/assign-role', [UserController::class, 'assignRole'])
            ->name('users.assignRole');

        Route::get('/user/create', [UserController::class, 'index'])
            ->name('users.create');

        Route::get('/roles', [RoleController::class, 'index'])
            ->name('roles.index');

        Route::post('/roles', [RoleController::class, 'store'])
            ->name('roles.store');

        Route::put('/roles/{role}', [RoleController::class, 'update'])
            ->name('roles.update');

        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])
            ->name('roles.destroy');

        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])
            ->name('roles.edit');
    });
});


require __DIR__ . '/auth.php';
