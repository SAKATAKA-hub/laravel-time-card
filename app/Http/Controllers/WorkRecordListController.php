<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkTime;
use App\Models\BreakTime;
use Carbon\Carbon;


class WorkRecordListController extends Controller
{

    /**
     * 月別勤怠管理表の表示(month_list)
     *
     *
    */
    public function month_list()
    {
        # ユーザーID
        $user_id = 1;

        # 日付の指定
        // $today = Carbon::parse('now')->format('Y-m-d');
        $today = '2022-01-19';

        # (ユーザーに紐づく)従業員と日付を指定した、勤務データの取得
        $work_times =
        WorkTime::employees($user_id)->where('date',$today)->get();







        # test
        $work_time = WorkTime::find(6);
        $break_time = BreakTime::find(7);

        dd($work_time->night_hour);



        return view('month_list');
    }




    /**
     * 日別勤怠管理表の表示(date_list)
     *
     *
    */
    public function date_list()
    {
        return view('date_list');
    }




    /**
     * 個人別勤怠管理表の表示(parsonal_list)
     *
     *
    */
    public function parsonal_list()
    {
        return view('parsonal_list');
    }

}
