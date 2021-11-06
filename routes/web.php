<?php

use App\Services\Settings\SettingService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/{path}', function () {
    $settingService = app()->make(SettingService::class);
    $title = $settingService->getSettingByName('title');

    return view('app', ['title' => $title ?  $title->value : config('app.name', 'Vuelerplate') ]);
})->where('path', '.*');
