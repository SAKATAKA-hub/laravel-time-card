<?php

namespace App\Http\Controllers;

use App\Models\WorkTime;



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
     * @param Int $user_id
     * @param String $date
     * @return Array
    */
    // public function WorkTimesForJson($work_times)
    public function WorkTimesForJson($user_id,$date)
    {
        # (ユーザーに紐づく)従業員と日付を指定した、勤務データの取得
        $work_times =
        WorkTime::employees($user_id)->where('date',$date)->orderBy('in','asc')->get();

        # JSON送信用にデータを加工
        if($work_times->count()){
            foreach($work_times as $work_time)
            {
                $work_time->employee = $work_time->employee;
                $work_time->text = $work_time->text;
                $work_time->break_times = Method::jsonBreakTimes($work_time->break_times);
                $work_time->restrain_hour= $work_time->restrain_hour;
                $work_time->break_hour = $work_time->break_hour;
                $work_time->working_hour = $work_time->working_hour;
                $work_time->night_hour = $work_time->night_hour;

                $work_time->input_in = substr($work_time->in,0,5);
                $work_time->input_out = $work_time->out!=='24:00:00' ? substr($work_time->out,0,5) : '00:00';
            }
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
        foreach($break_times as $index => $break_time)
        {
            $break_time->text = $break_time->text;
            $break_time->input_in = substr($break_time->in,0,5);
            $break_time->input_out = $break_time->out!=='24:00:00' ? substr($break_time->out,0,5) : '00:00';

            # バリデーション用　休憩時間の最低値・最高値
            $break_time->min_in =  $index ?
                substr($break_times[$index -1]->input_out,0,5) : substr($break_time->work_time->in,0,5);

            $break_time->max_out =  $index !== $break_times->count() -1 ?
               substr($break_times[$index +1]->in,0,5) :
               ( !empty($break_time->work_time->out) ? substr($break_time->work_time->out,0,5) : NULL );
        }

        return $break_times;
    }


}
