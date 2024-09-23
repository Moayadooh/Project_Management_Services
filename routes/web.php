<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Middleware\AddPermissionMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\DeletePermissionMiddleware;
use App\Http\Middleware\UpdatePermissionMiddleware;
use App\Http\Middleware\UserAdminMiddleware;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Route for admin and user roles
Route::middleware(['auth:sanctum', UserAdminMiddleware::class])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('projects')->group(function () {
        Route::get('/', [ProjectController::class, 'index']);
        Route::post('/add', [ProjectController::class, 'add'])->middleware(AddPermissionMiddleware::class);
        Route::get('/{id}', [ProjectController::class, 'getById']);
        Route::put('/{id}', [ProjectController::class, 'updateById'])->middleware(UpdatePermissionMiddleware::class);
        Route::delete('/{id}', [ProjectController::class, 'deleteById'])->middleware(DeletePermissionMiddleware::class);
    });

    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index']);
        Route::post('/add', [TaskController::class, 'add'])->middleware(AddPermissionMiddleware::class);
        Route::put('/assign/{id}', [TaskController::class, 'assign'])->middleware(UpdatePermissionMiddleware::class);
        Route::put('/{id}', [TaskController::class, 'updateById'])->middleware(UpdatePermissionMiddleware::class);
    });
});

// Route for admin role only
Route::middleware(['auth:sanctum', AdminMiddleware::class])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/', [AdminController::class, 'index']);
        Route::get('/getMessages', [AdminController::class, 'getMessages']);
        Route::post('/assign-role/{userId}', [AdminController::class, 'assignRole']);
        Route::post('/assign-permission/{userId}', [AdminController::class, 'assignPermission']);
    });

    Route::prefix('roles')->group(function () {
        Route::post('/', [RoleController::class, 'store']);
        Route::put('/{role}', [RoleController::class, 'update']);
        Route::delete('/{role}', [RoleController::class, 'destroy']);
        Route::get('/', [RoleController::class, 'index']);
    });

    Route::prefix('permissions')->group(function () {
        Route::post('/', [PermissionController::class, 'store']);
        Route::put('/{permission}', [PermissionController::class, 'update']);
        Route::delete('/{permission}', [PermissionController::class, 'destroy']);
        Route::get('/', [PermissionController::class, 'index']);
    });
});
