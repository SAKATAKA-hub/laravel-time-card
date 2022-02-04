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
->middleware(['delete_easy_user']) //簡単ログインユーザーの削除
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

# 簡単ログインユーザーの作成
Route::get('create_easy_user',[EasyUserController::class,'create_easy_user'])
->name('create_easy_user');




/*
|----------------------------------------
| 勤怠管理表ページの表示
|---------------------------------------- |
*/
# タイムカードページの表示(index)
Route::get('time_card', [InputWorkRecordController::class,'index'])
->middleware(['auth']) //ログイン中のみ表示
->middleware(['delete_easy_user']) //簡単ログインユーザーの削除
->name('time_card');


# タイムカードページのJSONデータ(employeees_json)
Route::post('time_card/employeees_json', [InputWorkRecordController::class,'employeees_json'])
->name('time_card.employeees_json');

# 勤務開始処理(work_in)
Route::post('time_card/work_in', [InputWorkRecordController::class,'work_in'])
->name('time_card.work_in');

# 休憩開始処理(break_in)
Route::post('time_card/break_in', [InputWorkRecordController::class,'break_in'])
->name('time_card.break_in');

# 休憩終了処理(break_out)
Route::patch('time_card/break_out', [InputWorkRecordController::class,'break_out'])
->name('time_card.break_out');

# 勤務終了処理(work_out)
Route::patch('time_card/work_out', [InputWorkRecordController::class,'work_out'])
->name('time_card.work_out');



/*
|----------------------------------------
| 勤怠管理表ページの表示
|---------------------------------------- |
*/
# ログイン中のみ表示(auth)
Route::middleware(['auth'])->group(function ()
{
    # 日別勤怠管理表ページの表示(date_list)
    Route::get('date_list', [WorkRecordListController::class,'date_list'])
    ->middleware(['delete_easy_user']) //簡単ログインユーザーの削除
    ->name('date_list');

    # 月別勤怠管理表ページの表示(month_list)
    Route::get('month_list', [WorkRecordListController::class,'month_list'])
    ->middleware(['delete_easy_user']) //簡単ログインユーザーの削除
    ->name('month_list');

    # 個人別勤怠管理表ページの表示(parsonal_list)
    Route::get('parsonal_list', [WorkRecordListController::class,'parsonal_list'])
    ->middleware(['delete_easy_user']) //簡単ログインユーザーの削除
    ->name('parsonal_list');
});




/*
|----------------------------------------
| 勤怠修正
|---------------------------------------- |
*/
# 勤怠修正ページの表示(edit_work_record)
Route::get('edit_work_record', [EditWorkRecordController::class,'index'])
->middleware(['auth']) //ログイン中のみ表示
->middleware(['delete_easy_user']) //簡単ログインユーザーの削除
->name('edit_work_record');


# 勤怠修正ページのJSONデータ(records_json)
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

