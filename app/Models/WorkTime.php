<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    /**
     * Employeeテーブルとのリレーション
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }


    /**
     * BreakTimeテーブルとのリレーション
     */

    public function break_times()
    {
        return $this->hasMany(BreakTime::class);
    }




    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    */


    /**
     * 直近の休憩記録を呼び出す
     * ($work_time->last_break)
     *
     *
     * @return String //(00:00-00:00)
     */
    public function getLastBreakAttribute()
    {
        return BreakTime::where('work_time_id',$this->id)
            ->orderBy('in','desc')->first();
    }




    /**
     * 出勤状態を表示
     * ($work_time->status)
     *
     *
     * @return Int //[0=>'退勤中', 1=>'出勤中', 2=>'休憩中']
     */
    public function getStatusAttribute()
    {
        $last_break =  $this::getLastBreakAttribute();

        if( isset($last_break) && empty($last_break->out) ){
            return 2 ; //休憩中
        }elseif( empty($this->out) ){
            return 1 ; //出勤中
        }else{
            return 0 ; //退勤中
        }
    }




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
     * 勤務日のテキスト表示
     * ($work_time->date_text)
     *
     *
     * @return String //(00:00-00:00)
     */
    public function getDateTextAttribute()
    {
        $weeks =['(日)','(月)','(火)','(水)','(木)','(金)','(土)',];

        $date = Carbon::parse($this->date);


        return $date->format('d日').$weeks[$date->format('w')];
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
            $time_hour += (float)$break_time->hour;
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
            (float)$this->restrain_hour - (float)$this->break_hour : 0
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

                // 深夜の勤務時間を加算
                $time_hour +=  Method::restrainHour($in, $out);
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

                // 深夜の勤務時間を加算
                $time_hour +=  Method::restrainHour($in, $out);
            }


            // 深夜の休憩時間を減算
            $time_hour -= $this->night_break_hour;


        }


        return sprintf('%.2f', $time_hour);


        /**
         * ----------------------------------------------------------
         * 深夜時間の算出解説
         * ----------------------------------------------------------
         *
         * 00:00     05:00               22:00     24:00
         *  |----------|------------------|---------|
         *     (深夜)                        (深夜)
         *
         * # in < 05:00, out <= 05:00のとき、                 処理A-1
         * # in < 05:00, 05:00 < out <= 22:00 のとき、        処理A-2
         * # in < 05:00, out < 22:00のとき、                  処理A-2, B-1
         * # 05:00 <= in < 22:00, 05:00 < out <= 22:00のとき、処理無し
         * #  05:00 <= in < 22:00, out > 22:00のとき、        処理B-1
         * # in >= 22:00, out > 22:00のとき、                 処理B-2
         *
         * ----------------------------------------------------------
         */
    }



    /**
     * [一勤務の合計]深夜休憩時間の表示
     * ($work_time->night_break_hour)
     * -- getNightHourAttributeメソッド内で利用 --
     *
     *
     * @return Int //(時)
     */
    public function getNightBreakHourAttribute()
    {
        # 出勤に紐づく休憩データの取得
        $break_times = BreakTime::where('work_time_id',$this->id)->get();

        # 休憩の合計時間を計算
        $time_hour = 0 ;
        foreach($break_times as $break_time)
        {
            $time_hour += (float)$break_time->night_hour;
        }


        return $time_hour;
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
