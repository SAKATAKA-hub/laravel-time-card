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




    /**
     * JSON用の勤務データを返すメソッド
     *
     * @param Array $work_times //勤怠記録
     * @return String
    */
    public function jsonWorkTimes($work_times)
    {
        foreach($work_times as $work_time)
        {
            $work_time->in = substr($work_time->in,0,5);
            $work_time->out = substr($work_time->out,0,5);
            $work_time->date_text = $work_time->date_text;
            $work_time->break_times = Method::jsonBreakTimes($work_time->break_times);

            $work_time->restrain_hour= $work_time->restrain_hour;
            $work_time->break_hour = $work_time->break_hour;
            $work_time->working_hour = $work_time->working_hour;
            $work_time->night_hou = $work_time->night_hou;
        }

        return $work_times;
    }




    /**
     * JSON用の休憩データを返すメソッド
     *
     * @param Array $work_times //勤怠記録
     * @return String
    */
    public function jsonBreakTimes($break_times)
    {
        foreach($break_times as $break_time)
        {
            $break_time->in = substr($break_time->in,0,5);
            $break_time->out = substr($break_time->out,0,5);
            $break_time->date_text = $break_time->date_text;
        }

        return $break_times;
    }


}
