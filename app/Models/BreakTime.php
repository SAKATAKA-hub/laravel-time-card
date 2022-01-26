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
        public function work_time()
        {
            return $this->belongsTo(WorkTime::class);
        }

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

        return sprintf('%s - %s (%.2f)',$in,$out,$hour);
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
     * -- WorkTimeモデル getNightBreakHourAttributeメソッド内で利用 --
     * ($break_time->night_hour)
     *
     *
     * @return Int //(時)
     */
    public function getNightHourAttribute()
    {
        $time_hour = 0;
        if( isset($this->out) )
        {

            #A 出勤時間(in)が、05:00前のとき
            if( $this->in < '05:00:00')
            {
                //A-1 退勤時間(out)が、05:00前のとき
                if( $this->out <= '05:00:00')
                {
                    $in = $this->in; $out = $this->out;
                }
                //A-2 退勤時間(out)が、05:00以降のとき
                else
                {
                    $in = $this->in; $out = '05:00:00';
                }

                //深夜の休憩時間を加算
                // 休憩が日にちを跨ぐ場合、$m分区切りの端数を切り捨てる。
                $time_hour +=  $in === "00:00:00" ?
                    Method::restrainHour($in, $out) : //切り捨て計算
                    Method::breakHour($in, $out); //切り上げ計算

            }

            #B 退勤時間(out)が、22:00以降のとき
            if( $this->out > '22:00:00')
            {
                //B-1 出勤時間(in)が、22:00前のとき
                if($this->in < '22:00:00')
                {
                    $in = '22:00:00'; $out = $this->out;
                }
                //B-2 出勤時間(in)が、22:00以降のとき
                else {
                    $in = $this->in; $out = $this->out;
                }

                //深夜の休憩時間を加算
                $time_hour +=  Method::breakHour($in, $out);

            }

        }

        return $time_hour;
    }











    /*
    |--------------------------------------------------------------------------
    | ローカルスコープ
    |--------------------------------------------------------------------------
    */

}
