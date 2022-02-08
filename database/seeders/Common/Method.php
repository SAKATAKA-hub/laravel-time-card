<?php

namespace Database\Seeders\Common;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use App\Models\WorkTime;
use App\Models\BreakTime;
use Carbon\Carbon;
use Faker\Factory;



/*
====================================================
 クラス内で利用するメソッド
====================================================
*/
class Method
{
    /**
     * 各従業員の勤務スケジュールを呼び出すメソッド
     *
     * @return Array
     */
    public static function EmployeeCount()
    {
        return 7;
    }

    /**
     * 各従業員の勤務スケジュールを呼び出すメソッド
     *
     * @return Array
     */
    public static function GetSchedules()
    {
        // 出退勤時間
        $work_schedules = [
            // 出勤当日の勤務
            0 => ['08:00:00','17:00:00'],
            1 => ['10:00:00','19:00:00'],
            2 => ['14:00:00','23:00:00'],
            3 => ['16:00:00','24:00:00'],
            4 => ['18:00:00','24:00:00'],
            5 => ['20:00:00','24:00:00'],
            6 => ['22:00:00','24:00:00'],

            //日付を跨いだ勤務(0時以降)
            13 => ['00:00:00','01:00:00'],
            14 => ['00:00:00','04:00:00'],
            15 => ['00:00:00','06:00:00'],
            16 => ['00:00:00','08:00:00'],
        ];

        // 休憩時間
        $break_schedules = [
            // 出勤当日の勤務
            0 => [ ['10:00:00','10:30:00'], ['15:00:00','15:30:00'] ],
            1 => [ ['12:00:00','12:30:00'], ['17:00:00','17:30:00'] ],
            2 => [ ['16:00:00','16:30:00'], ['21:00:00','21:30:00'] ],
            3 => [ ['18:00:00','18:30:00'], ['23:00:00','23:30:00'] ],
            4 => [ ['21:00:00','22:00:00'], ],
            5 => [],
            6 => [],

            //日付を跨いだ勤務(0時以降)
            13 => [],
            14 => [ ['01:00:00','02:00:00'] ],
            15 => [ ['02:00:00','03:00:00'], ['04:00:00','05:00:00'] ],
            16 => [ ['03:00:00','04:00:00'], ['05:00:00','06:00:00'] ],
        ];

        return compact('work_schedules','break_schedules');
    }




    /**
     * 勤務記録を作成するメソッド
     *
     * @param Array $date
     * @param Int $wi
     * @param App\Models\User $user
     * @return void
     */
    public static function CreateWorkRecord($date,$wi,$user)
    {
        #0 明日の出勤データはスキップ
        if( $date->isToday() && $wi>10 ){ return; }

        #1. 変数の準備
        // 各従業員の勤務スケジュール
        $schedules = self::getSchedules();
        $work_schedule = $schedules['work_schedules'][$wi];
        $break_schedules = $schedules['break_schedules'][$wi];


        #2. 出退勤記録の作成
        // 出勤時間
        $in = $work_schedule[0] === '00:00:00' ? '00:00:00':
            Carbon::parse( $date->format('Y-m-d').' '.$work_schedule[0] )
            ->subSecond(mt_rand(1, 15*60-1)) //’出勤’0～15分’前’のランダムな時間
            ->format('H:i:s');

        // 退勤時間
        $out = $work_schedule[1] === '24:00:00' ? '24:00:00':
            Carbon::parse( $date->format('Y-m-d').' '.$work_schedule[1] )
            ->addSecond(mt_rand(0, 20*60-1)) //’退勤’0～20分’後’のランダムな時間
            ->format('H:i:s');


        //現在日時より先の時間はNULL
        $now = Carbon::parse('now');
        if($date->isToday()){
            $in= $in < $now->format('H:i:s') ? $in : NULL;
            $out= $out < $now->format('H:i:s') ? $out : NULL;
        }

        // 出退勤記録の保存
        if($in)
        {
            // $wi>10のとき、0時以降の勤務
            $work_time = new WorkTime([
                'employee_id' => $wi<10 ? $user->employees[$wi]->id :
                    $user->employees[$wi-10]->id,
                'date' =>  $wi<10 ? $date->format('Y-m-d') :
                    $date->copy()->addDay()->format('Y-m-d'),
                'in' => $in,
                'out' => $out,
            ]);
            $work_time->save();
        }


        #3. 休憩記録の作成
        foreach ($break_schedules as $break_schedule)
        {
            // 休憩開始時間
            $in = $break_schedule[0] === '00:00:00' ? '00:00:00':
                Carbon::parse( $date->format('Y-m-d').' '.$break_schedule[0] )
                ->addSecond(mt_rand(0, 5*60-1)) //’休憩開始’0～5分’後’のランダムな時間
                ->format('H:i:s');

            // 休憩終了時間
            $out = $break_schedule[1] === '24:00:00' ? '24:00:00':
                Carbon::parse( $date->format('Y-m-d').' '.$break_schedule[1] )
                ->subSecond(mt_rand(0, 5*60-1)) //’休憩終了’0～5分’前’のランダムな時間
                ->format('H:i:s');


            // 現在日時より先の時間はNULL
            $now = Carbon::parse('now');
            if($date->isToday()){
                $in= $in < $now->format('H:i:s') ? $in : NULL;
                $out= $out < $now->format('H:i:s') ? $out : NULL;
            }

            // 休憩時間の保存
            if($in)
            {
                $break_time = new BreakTime([
                    'work_time_id' => $work_time->id,
                    'in' => $in,
                    'out' => $out,
                ]);
                $break_time->save();
            }

        }

    }

}
