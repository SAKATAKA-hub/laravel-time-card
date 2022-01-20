<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakTime extends Model
{
    /*
    |--------------------------------------------------------------------------
    | データ挿入設定
    |--------------------------------------------------------------------------
    */
    use HasFactory;

    public $timestamps = true;

    protected $fillable = ['work_time_id','in','out'];




    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    */
        // public function work_time()
        // {
        //     return $this->belongsTo(WorkTime::class);
        // }

    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    */


    /**
     * 休憩時間のテキスト表示
     * ($break_time->text)
     *
     *
     * @return String //( 00:00-00:00(0.00) )
     */
    public function getTextAttribute()
    {
        // 休憩終了の打刻がされていないときは、"--:--"を表示する

        $in = substr($this->in, 0, 5);
        $out = isset($this->out) ? substr($this->out, 0, 5) : '--:--';
        $hour = $this->hour;

        return sprintf('%s - %s(%.2f)',$in,$out,$hour);
    }





    /**
     * 休憩時間の表示
     * ($break_time->hour)
     *
     *
     * @return String //(時)
     */
    public function getHourAttribute()
    {
        // 休憩終了の打刻がされていないときは、"0時間"を返す

        $time_hour = isset($this->out) ?
            Method::breakHour($this->in, $this->out) : 0;
        ;


        return sprintf('%.2f', $time_hour);
    }



    /**
     * 深夜休憩時間の計算
     * ($break_time->night_hour)
     *
     *
     * @return String //(時)
     */
    public function getNightHourAttribute()
    {
        return 0;
    }












    /*
    |--------------------------------------------------------------------------
    | ローカルスコープ
    |--------------------------------------------------------------------------
    */

}
