<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EasyUserController;


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

Route::get('/', function () {
    return view('welcome');
});

# 簡単ログインユーザーの作成
Route::get('create_easy_user',[EasyUserController::class,'create_easy_user'])
->name('create_easy_user');

# ベーステンプレートのみ表示
Route::get('base', function () {
    return view('layouts.base');
});

# タイムカードの表示
Route::get('time_card', function () {
    return view('time_card');
})
->name('time_card');


# 月別勤怠管理表の表示(month_list')
Route::get('month_list', function () {
    return view('month_list');
})
->name('month_list');

# 日別勤怠管理表の表示(date_list')
Route::get('date_list', function () {
    return view('date_list');
})
->name('date_list');

# 個人別勤怠管理表の表示(parsonal_list')
Route::get('parsonal_list', function () {
    return view('parsonal_list');
})
->name('parsonal_list');




