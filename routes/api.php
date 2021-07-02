<?php

use App\Http\Controllers\Activities\ActivityController;
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

Route::group(['prefix' => '/users'], function(){
    Route::post('/login', [UserAuthController::class, 'login']);
});

Route::group(['middleware' => 'auth:api'], function(){
    Route::group(['prefix' => '/activity-log'], function(){
        Route::get('/', [ActivityController::class, 'index'])->middleware('permission:view log');
    });

    Route::group(['prefix' => '/users'], function(){
        Route::get('/', [UserController::class, 'index'])->middleware('permission:view user');
        Route::post('/', [UserController::class, 'store'])->middleware('permission:create user');
        Route::get('/{user}', [UserController::class, 'show'])->middleware('permission:view user');
        Route::put('/{user}', [UserController::class, 'update'])->middleware('permission:update user');
        Route::delete('/{user}', [UserController::class, 'destroy'])->middleware('permission:delete user');
        Route::post('/logout', [UserAuthController::class, 'logout']);
        Route::post('/refresh', [UserAuthController::class, 'refresh']);
        Route::post('/me', [UserAuthController::class, 'me']);
    });

    Route::group(['prefix' => '/permissions'], function(){
        Route::get('/', [PermissionController::class, 'index'])->middleware('permission:view permission');
        Route::post('/', [PermissionController::class, 'store'])->middleware('permission:create permission');
        Route::get('/{permission}', [PermissionController::class, 'show'])->middleware('permission:view permission');
        Route::put('/{permission}', [PermissionController::class, 'update'])->middleware('permission:update permission');
        Route::delete('/{permission}', [PermissionController::class, 'destroy'])->middleware('permission:delete permission');
    });

    Route::group(['prefix' => '/roles'], function(){
        Route::get('/', [RoleController::class, 'index'])->middleware('permission:view role');
        Route::post('/', [RoleController::class, 'store'])->middleware('permission:create role');
        Route::get('/{role}', [RoleController::class, 'show'])->middleware('permission:view role');
        Route::put('/{role}', [RoleController::class, 'update'])->middleware('permission:update role');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->middleware('permission:delete role');
        Route::get('/{role}/permissions', [RoleController::class, 'getRolePermissions'])->middleware('permission:get role permissions');
        Route::post('/{role}/permissions', [RoleController::class, 'giveRolePermissions'])->middleware('permission:give role permissions');
        Route::put('/{role}/permissions', [RoleController::class, 'syncRolePermissions'])->middleware('permission:sync role permissions');
    });
});
