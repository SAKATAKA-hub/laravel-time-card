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
    /**
     * タイムカードページの表示(index)
     *
     * @return \Illuminate\View\View
    */
    public function index()
    {
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
            $employee['work_status'] = $employee->work_status; //従業員の出勤状況
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
        $work_status = Employee::find($request->employee_id)->work_status;
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
        $work_status = Employee::find($request->employee_id)->work_status;
        if( $work_status !== (int) $request->work_status )
        {
            return response()->json([],422); //正しくなければ、エラーを返す
        }


        # 休憩データの作成
        $work_time = WorkTime::where('employee_id',$request->employee_id)
        ->orderBy('date','desc')->orderBy('in','desc')->first();

        $break_time = new BreakTime([
            'work_time_id' => $work_time->id,
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
        $work_status = Employee::find($request->employee_id)->work_status;
        if( $work_status !== (int) $request->work_status )
        {
            return response()->json([],422); //正しくなければ、エラーを返す
        }


        # 休憩データの更新
        $work_time = WorkTime::where('employee_id',$request->employee_id)
        ->orderBy('date','desc')->orderBy('in','desc')->first();

        $break_time = BreakTime::where('work_time_id',$work_time->id)
        ->orderBy('in','desc')->first();

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
        $work_status = Employee::find($request->employee_id)->work_status;
        if( $work_status !== (int) $request->work_status )
        {
            return response()->json([],422); //正しくなければ、エラーを返す
        }


        # 勤務データの更新
        $work_time = WorkTime::where('employee_id',$request->employee_id)
        ->orderBy('date','desc')->orderBy('in','desc')->first();


        $work_time->update([
            'out' => Carbon::parse('now')->format('H:i:s'),
        ]);


        return response()->json([
            'comment' => 'work_out OK!',
            'request' => $request->all(),
        ]);
    }


}
