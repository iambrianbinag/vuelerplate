<?php

use App\Http\Controllers\Activities\ActivityController;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Roles\RoleController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Permissions\PermissionController;
use App\Http\Controllers\Settings\SettingController;
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
    Route::group(['prefix' => '/settings'], function(){
        Route::get('/', [SettingController::class, 'index'])->middleware('permission:setting_view');
        Route::post('/', [SettingController::class, 'store'])->middleware('permission:setting_create');
        Route::get('/{id}', [SettingController::class, 'show'])->middleware('permission:setting_view');
        Route::put('/{id}', [SettingController::class, 'update'])->middleware('permission:setting_update');
    });

    Route::group(['prefix' => '/activity-log'], function(){
        Route::get('/', [ActivityController::class, 'index'])->middleware('permission:log_view');
    });

    Route::group(['prefix' => '/users'], function(){
        Route::get('/', [UserController::class, 'index'])->middleware('permission:user_view');
        Route::post('/', [UserController::class, 'store'])->middleware('permission:user_create');
        Route::get('/total', [UserController::class, 'getTotalUser'])->middleware('permission:user_total_get');
        Route::get('/{id}', [UserController::class, 'show'])->middleware('permission:user_view');
        Route::put('/{id}', [UserController::class, 'update'])->middleware('permission:user_update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->middleware('permission:user_delete');
        Route::post('/logout', [UserAuthController::class, 'logout']);
        Route::post('/refresh', [UserAuthController::class, 'refresh']);
        Route::post('/me', [UserAuthController::class, 'me']);
    });

    Route::group(['prefix' => '/permissions'], function(){
        Route::get('/', [PermissionController::class, 'index'])->middleware('permission:permission_view');
        Route::post('/', [PermissionController::class, 'store'])->middleware('permission:permission_create');
        Route::get('/total', [PermissionController::class, 'getTotalPermission'])->middleware('permission:permission_total_get');
        Route::get('/{id}', [PermissionController::class, 'show'])->middleware('permission:permission_view');
        Route::put('/{id}', [PermissionController::class, 'update'])->middleware('permission:permission_update');
        Route::delete('/{id}', [PermissionController::class, 'destroy'])->middleware('permission:permission_delete');
    });

    Route::group(['prefix' => '/roles'], function(){
        Route::get('/', [RoleController::class, 'index'])->middleware('permission:role_view');
        Route::post('/', [RoleController::class, 'store'])->middleware('permission:role_create');
        Route::get('/total', [RoleController::class, 'getTotalRole'])->middleware('permission:role_total_get');
        Route::get('/{id}', [RoleController::class, 'show'])->middleware('permission:role_view');
        Route::put('/{id}', [RoleController::class, 'update'])->middleware('permission:role_update');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->middleware('permission:role_delete');
        Route::get('/{id}/permissions', [RoleController::class, 'getRolePermissions'])->middleware('permission:role_permissions_get');
        Route::post('/{id}/permissions', [RoleController::class, 'giveRolePermissions'])->middleware('permission:role_permissions_give');
        Route::put('/{id}/permissions', [RoleController::class, 'syncRolePermissions'])->middleware('permission:role_permissions_sync');
    });
});
