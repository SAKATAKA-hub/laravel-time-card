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
        $in = substr($this->in, 0, 5);
        $out = substr($this->out, 0, 5);

        return sprintf('%s-%s',$in,$out);
    }




    /**
     * 勤務時間の表示
     * ($work_time->restrain_hour)
     *
     *
     * @return String //(時)
     */
    public function getRestrainHourAttribute()
    {
        $time_hour = Method::restrainHour($this->in, $this->out);


        return sprintf('%.2f', $time_hour);
    }




    /**
     * 休憩時間の表示
     * ($work_time->break_hour)
     *
     *
     * @return String //(時)
     */
    public function getBreakHourAttribute()
    {
        return'BreakHour';
    }




    /**
     * 労働時間の表示
     * ($work_time->working_hour)
     *
     *
     * @return String //(時)
     */
    public function getWorkingHourAttribute()
    {
        return 'Workinghour';
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
