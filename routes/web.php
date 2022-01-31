<?php

use Illuminate\Support\Facades\Route;

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
});

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
|----------------------------------------
| フォームバリデーションテスト
|---------------------------------------- |
*/
Route::get('test/form', [TestController::class,'form_index'])
->name('test/form');

Route::post('test/form_json', [TestController::class,'form_json'])
->name('test/form_json');


Route::post('test/form_post', [TestController::class,'form_post'])
->name('test/form_post');

Route::post('test/ajax_form_post', [TestController::class,'ajax_form_post'])
->name('test/ajax_form_post');





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




/*
|----------------------------------------
| 勤怠修正
|---------------------------------------- |
*/
# 勤怠修正ページの表示(edit_work_record)
Route::get('edit_work_record/', [EditWorkRecordController::class,'index'])
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
