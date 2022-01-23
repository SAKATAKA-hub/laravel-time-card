<?php

namespace App\Http\Controllers;


/**
 * ==============================================
 *
 * コントローラー内で共通利用するメソッド
 *
 * ==============================================
*/
class Method extends Controller
{
    /**
     * 勤怠リストの集計時間を計算するメソッド
     *
     * @param String $time_name //'restrain_hour'or'break_hour'or'working_hour'or'night_hour'
     * @param App\Models\WorkTime $work_times //勤怠記録
     * @return String
    */
    public function groupTotalTime($time_name, $work_times)
    {
        $time_hour = 0;

        foreach ($work_times as $work_time)
        {
            $time_hour += $work_time[$time_name];
        }


        return sprintf('%.2f', $time_hour);
    }
}
