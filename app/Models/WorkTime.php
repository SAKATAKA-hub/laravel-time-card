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
     * テスト
     * ($work_time->test)
     *
     *
     * @return String
     */
    public function getTestAttribute()
    {
        $in = Method::ceilTime($His = $this->in);
        $out = Method::flloTime($His = $this->out);

        return $in;
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
