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
     * 休憩時間の算出
     * ($break_time->hour)
     *
     *
     * @return Int
     */
    public function getHourAttribute()
    {
        # m分刻み
        $m = Method::getCutMin();

        # 出退勤時間を"H:I:s"形式から"秒単位"に変換して、その差分を算出
        $out_times = explode(':', $this->out);
        $out_sec = (int)$out_times[0]*60*60 + (int)$out_times[1]*60 + (int)$out_times[2];
        $in_times = explode(':', $this->in);
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



    /**
     * 休憩時間のテキスト表示
     * ($break_time->text)
     *
     *
     * @return Int //(00:00-00:00(0.00))
     */
    public function getTextAttribute()
    {
        $in = substr($this->in, 0, 5);
        $out = substr($this->out, 0, 5);
        $hour = $this->hour;

        return sprintf('%s-%s(%.2f)',$in,$out,$hour);
    }










    /*
    |--------------------------------------------------------------------------
    | ローカルスコープ
    |--------------------------------------------------------------------------
    */

}
