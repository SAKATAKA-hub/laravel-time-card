<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EasyUserController;
use App\Http\Controllers\EditWorkRecordController;
use App\Http\Controllers\EmployeeListController;
use App\Http\Controllers\InputWorkRecordController;
use App\Http\Controllers\WorkRecordListController;

use App\Http\Controllers\TestController;

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
})
->name('top');

# 簡単ログインユーザーの作成
Route::get('create_easy_user',[EasyUserController::class,'create_easy_user'])
->name('create_easy_user');

# ベーステンプレートのみ表示
Route::get('base', function () {
    return view('layouts.base');
});


# Vue.js表示
Route::get('vuejs', function () {
    return view('test.vuejs');
});




/*
| --------------------------------------------------------
| ログイン認証
| --------------------------------------------------------
*/
# ログイン画面の表示(login_form)
Route::get('login_form',function(){
    return view('login.login_form');
})
->name('login_form');

# ログイン処理(login)
Route::post('login',[AuthController::class,'login'])
->name('login');

# ログアウト処理(logout)
Route::post('logout',[AuthController::class,'logout'])->middleware('auth')
->name('logout');




# ユーザー登録画面の表示(get_register)
Route::get('get_register',function(){
    return view('login.register_form');
})
->name('get_register');

# ユーザー登録処理(post_register)
Route::post('post_register',[AuthController::class,'post_register'])
->name('post_register');


# ログイン中のみ表示(auth)
Route::middleware(['auth'])->group(function ()
{

    # ユーザー情報変更ページの表示(edit_register)
    Route::get('edit_register',function(){
        return view('login.edit_register');
    })
    ->name('edit_register');

    # ユーザー情報の更新(update_register)
    Route::patch('update_register',[AuthController::class,'update_register'])
    ->name('update_register');

    # ユーザー情報の削除(destroy_register)
    Route::delete('destroy_register',[AuthController::class,'destroy_register'])
    ->name('destroy_register');

});


/*
|----------------------------------------
| 勤怠管理表ページの表示
|---------------------------------------- |
*/
# ログイン中のみ表示(auth)、24時間経過したワンタイムユーザーの削除
Route::middleware(['auth'])->middleware(['delete_easy_user'])->group(function ()
{

    # タイムカードページの表示(index)
    Route::get('time_card', [InputWorkRecordController::class,'index'])
    ->name('time_card');







    # 日別勤怠管理表ページの表示(date_list)
    Route::get('date_list', [WorkRecordListController::class,'date_list'])
    ->name('date_list');

    # 月別勤怠管理表ページの表示(month_list)
    Route::get('month_list', [WorkRecordListController::class,'month_list'])
    ->name('month_list');

    # 個人別勤怠管理表ページの表示(parsonal_list)
    Route::get('parsonal_list', [WorkRecordListController::class,'parsonal_list'])
    ->name('parsonal_list');

});




/*
|----------------------------------------
| 勤怠修正
|---------------------------------------- |
*/
# ログイン中のみ表示(auth)、24時間経過したワンタイムユーザーの削除
Route::middleware(['auth'])->middleware(['delete_easy_user'])->group(function ()
{

    # 勤怠修正ページの表示(edit_work_record)
    Route::get('edit_work_record', [EditWorkRecordController::class,'index'])
    ->name('edit_work_record');

    # 勤怠修正ページのJSONデータ(edit_work_record_json)
    Route::post('edit_work_record/records_json', [EditWorkRecordController::class,'records_json'])
    ->name('edit_work_record.records_json');


    # 入力した勤怠時間のバリデーションチェック(validate_input_time)
    Route::post('edit_work_record/validate_input_time', [EditWorkRecordController::class,'validate_input_time'])
    ->name('edit_work_record.validate_input_time');


    # 勤怠情報の更新(update)
    Route::patch('edit_work_record/update', [EditWorkRecordController::class,'update'])
    ->name('edit_work_record.update');

    # 勤怠情報の削除(destroy)
    Route::delete('edit_work_record/destroy', [EditWorkRecordController::class,'destroy'])
    ->name('edit_work_record.destroy');

});
