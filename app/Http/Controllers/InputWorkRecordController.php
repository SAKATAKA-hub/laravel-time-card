<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkTime;
use App\Models\BreakTime;
use App\Models\Employee;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class InputWorkRecordController extends Controller
{

    public function middle()
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
    }

    /**
     * タイムカードページの表示(index)
     *
     * @return \Illuminate\View\View
    */
    public function index()
    {
        $this::middle();








        # ユーザーID
        $user_id = Auth::user()->id;

        return view('time_card.index',compact('user_id'));
    }




    /**
     * タイムカードページのJSONデータ(employeees_json)
     *
     * @param \Illuminate\Http\Request $request
     * @return Json
    */
    public function employeees_json(Request $request)
    {
        # 従業員情報の取得
        $employees = Employee::where('user_id',$request->user_id)->get();
        foreach ($employees as $employee)
        {
            $employee['work_status'] = $employee->last_work->status; //従業員の出勤状況
        }


        return response()->json([
            'comment' => 'employeees_json OK!',
            'employees' => $employees,
        ]);
    }




    /**
     * 勤務開始処理(work_in)
     *
     * @param \Illuminate\Http\Request $request
     * @return Json
    */
    public function work_in(Request $request)
    {
        # 従業員の現在の出勤状況が処理に対して正しいかチェック($request->work_status: 0 退勤中)
        $work_status = Employee::find($request->employee_id)->last_work->status;
        if( $work_status !== (int) $request->work_status )
        {
            return response()->json([],422); //正しくなければ、エラーを返す
        }


        # 勤務データの作成
        $work_time = new WorkTime([
            'employee_id' => $request->employee_id,
            'date' =>  Carbon::parse('today')->format('Y-m-d'),
            'in' => Carbon::parse('now')->format('H:i:s'),
            'out' => null,
        ]);
        $work_time->save();


        return response()->json([
            'comment' => 'work_in OK!',
            'request' => $request->all(),
        ]);
    }






    /**
     * 休憩開始処理(break_in)
     *
     * @param \Illuminate\Http\Request $request
     * @return Json
    */
    public function break_in(Request $request)
    {
        # 従業員の現在の出勤状況が処理に対して正しいかチェック($request->work_status: 1 出勤中)
        $work_status = Employee::find($request->employee_id)->last_work->status;
        if( $work_status !== (int) $request->work_status )
        {
            return response()->json([],422); //正しくなければ、エラーを返す
        }


        # 前回の休憩開始$m分以内に休憩を開始することはできない
        $m = \App\Models\method::getCutMin();
        $last_break = Employee::find($request->employee_id)->last_work->last_break;
        if(
            isset($last_break) &&
            ( Carbon::parse($last_break->in)->diffInMinutes( Carbon::parse('now') ) < $m )
        )
        {
            $diff = $m - Carbon::parse($last_break->in)->diffInMinutes( Carbon::parse('now') );
            return response()->json([
                'error' => '前回の休憩開始の'.$m.'分以内に休憩を開始することはできません。あと'.$diff.'分程お待ちください。',
            ]);
        }



        # 休憩データの作成
        $break_time = new BreakTime([
            'work_time_id' => Employee::find($request->employee_id)->last_work->id,
            'in' => Carbon::parse('now')->format('H:i:s'),
            'out' => null,
        ]);
        $break_time->save();



        return response()->json([
            'comment' => 'break_in OK!',
            'request' => $request->all(),
        ]);
    }






    /**
     * 休憩終了処理(break_out)
     *
     * @param \Illuminate\Http\Request $request
     * @return Json
    */
    public function break_out(Request $request)
    {
        # 従業員の現在の出勤状況が処理に対して正しいかチェック($request->work_status: 2 休憩中)
        $work_status = Employee::find($request->employee_id)->last_work->status;
        if( $work_status !== (int) $request->work_status )
        {
            return response()->json([],422); //正しくなければ、エラーを返す
        }


        # 休憩データの更新
        $break_time = Employee::find($request->employee_id)->last_work->last_break;
        $break_time->update([
            'out' => Carbon::parse('now')->format('H:i:s'),
        ]);


        return response()->json([
            'comment' => 'break_out OK!',
            'request' => $request->all(),
        ]);
    }




    /**
     * 勤務終了処理(work_out)
     *
     * @param \Illuminate\Http\Request $request
     * @return Json
    */
    public function work_out(Request $request)
    {
        # 従業員の現在の出勤状況が処理に対して正しいかチェック($request->work_status: 2 休憩中)
        $work_status = Employee::find($request->employee_id)->last_work->status;
        if( $work_status !== (int) $request->work_status )
        {
            return response()->json([],422); //正しくなければ、エラーを返す
        }


        # 勤務開始から$m分以内に勤務終了することはできない
        $m = \App\Models\method::getCutMin();
        $last_work = Employee::find($request->employee_id)->last_work;
        if( Carbon::parse($last_work->in)->diffInMinutes( Carbon::parse('now') ) < $m )
        {
            $diff = $m - Carbon::parse($last_work->in)->diffInMinutes( Carbon::parse('now') );
            return response()->json([
                'error' => '勤務開始から'.$m.'分以内に勤務終了することはできません。あと'.$diff.'分程お待ちください。',
            ]);
        }


        # 勤務データの更新
        $work_time = Employee::find($request->employee_id)->last_work;
        $work_time->update([
            'out' => Carbon::parse('now')->format('H:i:s'),
        ]);


        return response()->json([
            'comment' => 'work_out OK!',
            'request' => $request->all(),
        ]);
    }


}
