<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Cache;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});




// Show login form
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->middleware('guest')
    ->name('login');

// Web login handler
Route::post('/login', [AuthController::class, 'webLogin'])
    ->middleware('guest');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// // Dashboards by role
Route::get('/dashboard/superadmin', [DashboardController::class, 'superadminDashboard'])
    ->middleware(['auth', 'role:SuperAdmin'])
    ->name('dashboard.superadmin');


Route::get('/dashboard/admin', [DashboardController::class, 'adminDashboard'])
    ->middleware(['auth', 'role:Admin'])
    ->name('dashboard.admin');

Route::get('/dashboard/user', [DashboardController::class, 'userDashboard'])
    ->middleware(['auth', 'role:User'])
    ->name('dashboard.user');

Route::get('/home', function () {
    $user = auth()->user();
    if (!$user) {
        abort(403);
    }

    return match (true) {
        $user->hasRole('SuperAdmin') => redirect()->route('dashboard.superadmin'),
        $user->hasRole('Admin')      => redirect()->route('dashboard.admin'),
        default                      => redirect()->route('dashboard.user'),
    };
})->middleware('auth')->name('home');


Route::middleware(['auth', 'role:SuperAdmin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

Route::post('/admin/users/bulk-store', [UserController::class, 'bulkStore'])
    ->middleware(['auth', 'role:Admin'])
    ->name('admin.users.bulkStore');


Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin/users/{user}/edit', [UserController::class, 'admin_edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'admin_update'])->name('admin.users.update');
});
Route::delete('/admin/users/{user}', [UserController::class, 'user_destroy'])
    ->middleware(['auth', 'role:Admin'])
    ->name('admin.users.destroy');
Route::middleware(['auth', 'role:User'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/admin/refresh-user-cache', function () {
    Cache::forget('superadmin_users_list');
    return 'User cache cleared';
});
