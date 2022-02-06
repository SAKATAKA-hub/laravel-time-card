<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WorkTime;
use App\Models\BreakTime;
use App\Models\Employee;
use App\Models\User;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WorkRecordListController extends Controller
{
    /**
     * 日別勤怠管理表ページの表示(date_list)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
    */
    public function date_list(Request $request)
    {
        # ユーザーID
        $user_id = Auth::user()->id;

        # 日付の指定
        // 日付指定のリクエストが無ければ、今日の日付
        $date = empty( $request->date ) ? Carbon::parse('now')->format('Y-m-d') : $request->date;


        # 日付オブジェクト・曜日配列
        $date_ob = Carbon::parse($date);
        $weeks =['(日)','(月)','(火)','(水)','(木)','(金)','(土)',];


        # (ユーザーに紐づく)従業員と日付を指定した、勤務データの取得
        $work_times =
        WorkTime::employees($user_id)->where('date',$date)
        ->orderBy('in','asc')->get();


        # 集計時間
        $total_times = [
            'restrain_hour' => Method::groupTotalTime($time_name='restrain_hour', $work_times), //総勤務時間(h)
            'break_hour' => Method::groupTotalTime($time_name='break_hour', $work_times), //総休憩時間(h)
            'working_hour' => Method::groupTotalTime($time_name='working_hour', $work_times), //総労働時間(h)
            'night_hour' => Method::groupTotalTime($time_name='night_hour', $work_times), //総深夜時間(h)
        ];


        return view( 'date_list',compact('date_ob','weeks','work_times','total_times') );
    }







    /**
     * 月別勤怠管理表ページの表示(month_list)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
    */
    public function month_list(Request $request)
    {
        # ユーザーID
        $user_id = Auth::user()->id;

        # 日付の指定
        // 日付のリクエストが無ければ、今日の日付
        $date = empty( $request->month ) ? Carbon::parse('now')->format('Y-m-01') : $request->month.'-01';

        # 日付オブジェクト・曜日配列
        $date_ob = Carbon::parse($date);
        $weeks =['(日)','(月)','(火)','(水)','(木)','(金)','(土)',];


        # 全従業員情報
        $employees = Employee::where('user_id',$user_id)->get();


        # 各従業員ごとの月の集計データの取得
        for ($i=0; $i < $employees->count(); $i++)
        {

            $employee_id = $employees[$i]->id;

            // 勤務データ
            $work_times =
            WorkTime::where('employee_id',$employee_id)
            ->where('date','>=',$date)->where('date','<',$date_ob->copy()->addMonth()->format('Y-m-01'))
            ->orderBy('date','asc')->orderBy('in','asc')->get();

            // 集計データ
            $employee_total_times = [
                'restrain_hour' => Method::groupTotalTime($time_name='restrain_hour', $work_times),
                'break_hour' => Method::groupTotalTime($time_name='break_hour', $work_times),
                'working_hour' => Method::groupTotalTime($time_name='working_hour', $work_times),
                'night_hour' => Method::groupTotalTime($time_name='night_hour', $work_times),
            ];

            $employees[$i]->total_times = $employee_total_times;
        }


        # (ユーザーに紐づく)従業員と日付を指定した、勤務データの取得
        $work_times =
        WorkTime::employees($user_id)
        // WorkTime::where('employee_id',$employee_id)
        ->where('date','>=',$date)->where('date','<',$date_ob->copy()->addMonth()->format('Y-m-01'))
        ->orderBy('date','asc')->orderBy('in','asc')->get();


        # 集計時間
        $total_times = [
            'restrain_hour' => Method::groupTotalTime($time_name='restrain_hour', $work_times), //総勤務時間(h)
            'break_hour' => Method::groupTotalTime($time_name='break_hour', $work_times), //総休憩時間(h)
            'working_hour' => Method::groupTotalTime($time_name='working_hour', $work_times), //総労働時間(h)
            'night_hour' => Method::groupTotalTime($time_name='night_hour', $work_times), //総深夜時間(h)
        ];



        return view('month_list',
            compact('date_ob','weeks','employees','work_times','total_times')
        );
    }





    /**
     * 個人別勤怠管理表ページの表示(parsonal_list)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
    */
    public function parsonal_list(Request $request)
    {
        # ユーザーID
        $user_id = Auth::user()->id;

        # 日付の指定
        // 日付のリクエストが無ければ、今日の日付
        $date = empty( $request->month ) ? Carbon::parse('now')->format('Y-m-01') : $request->month.'-01';

        # 日付オブジェクト・曜日配列
        $date_ob = Carbon::parse($date);
        $weeks =['(日)','(月)','(火)','(水)','(木)','(金)','(土)',];


        # 全従業員情報
        $employees = Employee::where('user_id',$user_id)->get();

        # 表示中従業員
        // 従業員のリクエストが無ければ、先頭の従業員
        $employee_id = empty( $request->employee_id ) ? $employees[0]->id : (int)$request->employee_id ;
        $employee = Employee::find($employee_id);


        # (ユーザーに紐づく)従業員と日付を指定した、勤務データの取得
        $work_times =
        // WorkTime::employees($user_id)
        WorkTime::where('employee_id',$employee_id)
        ->where('date','>=',$date)->where('date','<',$date_ob->copy()->addMonth()->format('Y-m-01'))
        ->orderBy('date','asc')->orderBy('in','asc')->get();


        # 集計時間
        $total_times = [
            'restrain_hour' => Method::groupTotalTime($time_name='restrain_hour', $work_times), //総勤務時間(h)
            'break_hour' => Method::groupTotalTime($time_name='break_hour', $work_times), //総休憩時間(h)
            'working_hour' => Method::groupTotalTime($time_name='working_hour', $work_times), //総労働時間(h)
            'night_hour' => Method::groupTotalTime($time_name='night_hour', $work_times), //総深夜時間(h)
        ];



        return view('parsonal_list',
            compact('date_ob','weeks','employees','employee_id','employee','work_times','total_times')
        );
    }


}
