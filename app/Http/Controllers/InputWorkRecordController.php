<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
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
            $employee->work_status = 0; //出勤状況
            $employee->active = false; //タイムカード表示時中か否か
        }

        // dd($employees);


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
        return response()->json([
            'comment' => 'work_in OK!',
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
        return response()->json([
            'comment' => 'break_in OK!',
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
        return response()->json([
            'comment' => 'break_out OK!',
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
        return response()->json([
            'comment' => 'work_out OK!',
        ]);
    }


}
