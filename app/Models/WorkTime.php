<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkTime extends Model
{
    /*
    |--------------------------------------------------------------------------
    | データ挿入設定
    |--------------------------------------------------------------------------
    */
    use HasFactory;

    public $timestamps = true;

    protected $fillable = ['employee_id','date','in','out',];





    /*
    |--------------------------------------------------------------------------
    | リレーション
    |--------------------------------------------------------------------------
    */








    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    */


    /**
     * 勤務時間のテキスト表示
     * ($work_time->text)
     *
     *
     * @return String //(00:00-00:00)
     */
    public function getTextAttribute()
    {
        // 退勤打刻がされていないときは、"--:--"を表示する

        $in = substr($this->in, 0, 5);
        $out = isset($this->out) ? substr($this->out, 0, 5) : '--:--';

        return sprintf('%s - %s',$in,$out);
    }




    /**
     * [一勤務の合計]勤務時間の表示
     * ($work_time->restrain_hour)
     *
     *
     * @return String //(時)
     */
    public function getRestrainHourAttribute()
    {
        // 退勤打刻がされていないときは、"0時間"を返す

        $time_hour = isset($this->out) ?
            Method::restrainHour($this->in, $this->out) : 0
        ;


        return sprintf('%.2f', $time_hour);
    }




    /**
     * [一勤務の合計]休憩時間の表示
     * ($work_time->break_hour)
     *
     *
     * @return String //(時)
     */
    public function getBreakHourAttribute()
    {
        # 出勤に紐づく休憩データの取得
        $break_times = BreakTime::where('work_time_id',$this->id)->get();

        # 休憩の合計時間を計算
        $time_hour = 0 ;
        foreach($break_times as $break_time)
        {
            $time_hour += (int)$break_time->hour;
        }


        return sprintf('%.2f', $time_hour);
    }




    /**
     * [一勤務の合計]労働時間の表示
     * ($work_time->working_hour)
     *
     *
     * @return String //(時)
     */
    public function getWorkingHourAttribute()
    {
        # [一勤務の合計]勤務時間 - [一勤務の合計]休憩時間
        // 退勤打刻がされていないときは、"0時間"を返す

        $time_hour = isset($this->out) ?
            (int)$this->restrain_hour - (int)$this->break_hour : 0
        ;


        return sprintf('%.2f', $time_hour);
    }



    /**
     * [一勤務の合計]深夜時間の表示
     * ($work_time->night_hour)
     *
     *
     * @return String //(時)
     */
    public function getNightHourAttribute()
    {
        return 'NightHour';
    }






    /*
    |--------------------------------------------------------------------------
    | ローカルスコープ
    |--------------------------------------------------------------------------
    */

    /**
     * ユーザーに紐づく,"従業員の勤務データ"を指定
     * ( employees($user_id) )
     *
     * @return Query $query
    */
    public function scopeEmployees($query, $user_id)
    {
        $employees = Employee::where('user_id',$user_id)->get();

        foreach($employees as $employee)
        {
            $query->orWhere('employee_id',$employee->id);
        }


        return $query;
    }

}
