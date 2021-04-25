<?php

use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Roles\RoleController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Permissions\PermissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/users/login', [UserAuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function(){
    // USERS 
    Route::get('/users', [UserController::class, 'index'])->middleware('permission:view user');
    Route::post('/users', [UserController::class, 'store'])->middleware('permission:create user');
    Route::get('/users/{user}', [UserController::class, 'show'])->middleware('permission:view user');
    Route::put('/users/{user}', [UserController::class, 'update'])->middleware('permission:update user');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('permission:delete user');
    Route::post('/users/logout', [UserAuthController::class, 'logout']);
    Route::post('/users/refresh', [UserAuthController::class, 'refresh']);
    Route::post('/users/me', [UserAuthController::class, 'me']);

    // PERMISSIONS
    Route::get('/permissions', [PermissionController::class, 'index'])->middleware('permission:view permission');
    Route::post('/permissions', [PermissionController::class, 'store'])->middleware('permission:create permission');
    Route::get('/permissions/{permission}', [PermissionController::class, 'show'])->middleware('permission:view permission');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->middleware('permission:update permission');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->middleware('permission:delete permission');

    // ROLES
    Route::get('/roles', [RoleController::class, 'index'])->middleware('permission:view role');
    Route::post('/roles', [RoleController::class, 'store'])->middleware('permission:create role');
    Route::get('/roles/{role}', [RoleController::class, 'show'])->middleware('permission:view role');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->middleware('permission:update role');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->middleware('permission:delete role');
    Route::get('/roles/{role}/permissions', [RoleController::class, 'getRolePermissions'])->middleware('permission:get role permissions');
    Route::post('/roles/{role}/permissions', [RoleController::class, 'giveRolePermissions'])->middleware('permission:give role permissions');
    Route::put('/roles/{role}/permissions', [RoleController::class, 'syncRolePermissions'])->middleware('permission:sync role permissions');
});
