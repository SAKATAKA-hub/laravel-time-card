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
    public function flloTime($His)
    {
        # m分刻み
        $m = self::getCutMin();

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
     *　-- 退勤時間の計算 --
     *
     * @param String $His (HH:ii:ss)
     * @return Array ['H','i','s']
    */
    public function ceilTime($His)
    {
        # m分刻み
        $m = self::getCutMin();

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

}
