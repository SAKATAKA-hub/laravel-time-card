<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\WorkTime;
use App\Models\BreakTime;
use App\Models\Employee;
use Carbon\Carbon;


class TakeOverRecord
{
    /**
     * 今日以前の出勤で、退勤処理が済んでいないデータの処理
     * (#1.出勤日の勤務記録の日締め処理, #2.出勤日以降の勤務記録の挿入)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $now = Carbon::parse('now');

        # 今日以前の出勤で、退勤処理が済んでいないデータの取得
        $work_times = WorkTime::where('date','<',$now->format('Y-m-d'))
        ->where('out',null)->get();


        for ($wi=0; $wi < count($work_times); $wi++) {
            $work_time = $work_times[$wi]; //処理対象の勤務記録
            $employee_id = $work_time->employee_id; //従業員ID
            $date = Carbon::parse($work_time->date); //退勤未入力の出勤日


            #1.出勤日の勤務記録の日締め処理
            //出退勤の締め処理
            $work_time->update(['out' => '24:00:00']);

            //休憩中のとき、休憩の締め処理
            if($work_time->status === 2 )
            {
                $work_time->last_break->update(['out' => '24:00:00']);
                // dd($work_time->last_break->id);
            }


            #2.出勤日以降の勤務記録の挿入
            $after_days = $date->diffInDays( Carbon::parse('today') );
            for ($di=0; $di < $after_days; $di++) {
                $date->addDay(); //間日の日付

                //出退勤記録の挿入
                $after_work_time = new WorkTime([
                    'employee_id' => $employee_id,
                    'date' =>  $date->format('Y-m-d'),
                    'in' => '00:00:00',
                    'out' => $di!==($after_days - 1) ? '24:00:00' : NULL,
                ]);
                $after_work_time->save();

                //休憩中の時、休憩記録の挿入
                if($work_time->status === 2 )
                {
                    $after_break_time = new BreakTime([
                        'work_time_id' => $after_work_time->id,
                        'in' => '00:00:00',
                        'out' => $di!==($after_days - 1) ? '24:00:00' : NULL,
                    ]);
                    $after_break_time->save();
                }
            }
        }

        return $next($request);
    }
}
