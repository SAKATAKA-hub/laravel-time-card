<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * ==============================================
 *
 * モデル内で利用するメソッド
 *
 * ==============================================
*/
class Method  extends Model
{


    /**
     * [ 基本設定 ] 勤怠管理の時間集計は"m分区切り"で集計する
     *
     * @return Int
    */
    public function getCutMin()
    {
        $m = 15;


        return $m;
    }




    /**
     * 時間をm分区切りに計算(切り捨てる)
     *　-- 退勤時間の計算 --
     *
     * @param String $His (HH:ii:ss)
     * @return Array ['H','i','s']
    */
    public function flooTime($His)
    {
        # m分刻み
        $m = Method::getCutMin();

        # 時間を"分単位"に変換
        $times =explode(':',$His);
        $time_min = (int)$times[0]*60 + (int)$times[1];

        # $m分区切りで時間を"切り捨て"
        $time_min = floor($time_min/$m) * $m;

        # "分単位"の時間を、配列[H,i,s]に変換
        $time_hour = floor($time_min/60);
        $times = [
            sprintf('%02d', $time_hour),
            sprintf('%02d', $time_min - $time_hour*60),
            '00',
        ];

        return $times;
    }




    /**
     * 時間をm分区切りに計算(切り上げる)
     *　-- 出勤時間の計算 --
     *
     * @param String $His (HH:ii:ss)
     * @return Array ['H','i','s']
    */
    public function ceilTime($His)
    {
        # m分刻み
        $m = Method::getCutMin();

        # 時間を"分単位"に変換
        $times =explode(':',$His);
        $time_min = (int)$times[0]*60 + (int)$times[1];

        # $m分区切りで時間を"切り上げ"
        $time_min = ceil($time_min/$m) * $m;

        # "分単位"の時間を、配列[H,i,s]に変換
        $time_hour = floor($time_min/60);
        $times = [
            sprintf('%02d', $time_hour),
            sprintf('%02d', $time_min - $time_hour*60),
            '00',
        ];

        return $times;
    }




    /**
     * 勤務時間の計算
     * { out($m分切り下げ) - in($m分切り上げ) } / (時)
     *
     *
     * @return Int //(時)
     */
    public function restrainHour($in, $out)
    {
        $out_times = Method::flooTime($out);
        $in_times = Method::ceilTime($in);
        $time_min = ($out_times[0]*60 + $out_times[1]) - ($in_times[0]*60 + $in_times[1]);
        $time_hour = $time_min > 0 ? $time_min/60 : 0 ; //勤務が$m分以下の時は、0.00時間勤務

        return $time_hour;
    }




    /**
     * 休憩時間の計算
     * { out(秒) - in(秒) }($m分切り上げ) / (時)
     *
     *
     * @return Int //(時)
     */
    public function breakHour($in, $out)
    {

        # m分刻み
        $m = Method::getCutMin();

        # 出退勤時間を"H:I:s"形式から"秒単位"に変換して、その差分を算出
        $out_times = explode(':', $out);
        $out_sec = (int)$out_times[0]*60*60 + (int)$out_times[1]*60 + (int)$out_times[2];
        $in_times = explode(':', $in);
        $in_sec = (int)$in_times[0]*60*60 + (int)$in_times[1]*60 + (int)$in_times[2];

        $time_sec = $out_sec - $in_sec;

        # "秒単位"の時間を"分単位に変換"
        $time_min = $time_sec/60;

        # $m分区切りで時間を"切り上げ"
        $time_min = ceil($time_min/$m) * $m;

        # "分単位"の時間を"時単位に変換"
        $time_hour = $time_min/60;


        return $time_hour;
    }





}
