<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /*
    |--------------------------------------------------------------------------
    | データ挿入設定
    |--------------------------------------------------------------------------
    */
    use HasFactory;

    public $timestamps = true;

    protected $fillable = ['user_id','name','color',];





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
     * 従業員の出勤状態
     * ($employee->work_status)
     *
     *
     * @return Int //[0=>'退勤中', 1=>'出勤中', 2=>'休憩中']
     */
    public function getWorkStatusAttribute()
    {
        // 直近の勤務記録
        $work_time = WorkTime::where('employee_id',$this->id)
        ->orderBy('date','desc')
        ->orderBy('in','desc')
        ->first();

        // 直近の勤務の最後の休憩記録
        $break_time = BreakTime::where('work_time_id',$work_time->id)
        ->orderBy('in','desc')
        ->first();

        // 従業員の出勤
        $work_status = 0;
        if( isset($break_time) && empty($break_time->out) ){
            $work_status = 2;
        }
        else if( isset($work_time) && empty($work_time->out) ){
            $work_status = 1;
        }

        return $work_status;
    }




    /*
    |--------------------------------------------------------------------------
    | ローカルスコープ
    |--------------------------------------------------------------------------
    */

}
