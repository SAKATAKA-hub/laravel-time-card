<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EasyUserController;
use App\Http\Controllers\EditWorkRecordController;
use App\Http\Controllers\EmployeeListController;
use App\Http\Controllers\InputWorkRecordController;
use App\Http\Controllers\WorkRecordListController;


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





# タイムカードページの表示
Route::get('time_card', function () {
    return view('time_card');
})
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




# 勤怠修正ページの表示(edit_work_record)
Route::get('edit_work_record', [EditWorkRecordController::class,'edit_work_record'])
->name('edit_work_record');

# 勤怠情報の修正(update_work_record)
Route::patch('update_work_record', [EditWorkRecordController::class,'update_work_record'])
->name('update_work_record');

# 勤怠情報の削除(destroy_work_record)
Route::delete('destroy_work_record', [EditWorkRecordController::class,'destroy_work_record'])
->name('destroy_work_record');

# 休憩の削除(destroy_break_record)
Route::delete('destroy_break_record', [EditWorkRecordController::class,'destroy_break_record'])
->name('destroy_break_record');
