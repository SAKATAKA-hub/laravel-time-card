<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\WorkTimeRecordFormRequest;

use App\Models\WorkTime;
use App\Models\BreakTime;
use App\Models\Employee;
use Carbon\Carbon;


class EditWorkRecordController extends Controller
{
    /**
     * 勤怠修正ページの表示(index)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
    */
    public function index(Request $request)
    {
        # ユーザーID
        $user_id = 1;


        # 日付の指定
        // 日付指定のリクエストが無ければ、今日の日付
        $date = empty( $request->date ) ? Carbon::parse('now')->format('Y-m-d') : $request->date;


        # 日付オブジェクト・曜日配列
        $date_ob = Carbon::parse($date);
        $weeks =['(日)','(月)','(火)','(水)','(木)','(金)','(土)',];


        return view( 'edit_work_record.index',
            compact('user_id','date','date_ob','weeks',)
        );
    }




    /**
     * 勤怠修正ページのJSONデータ(records_json)
     *
     * @param \Illuminate\Http\Request $request
     * @return Json
    */
    public function records_json(Request $request)
    {

        list($user_id, $date) =  [$request->user_id,$request->date];


        # (ユーザーに紐づく)従業員と日付を指定した、勤務データの取得
        $work_times =
        WorkTime::employees($user_id)->where('date',$date)->orderBy('in','asc')->get();

        # JSON送信用にデータを加工
        $work_times = $work_times->count()? Method::WorkTimesForJson($work_times) : $work_times;


        # 集計時間
        $total_times = [
            'restrain_hour' => Method::groupTotalTime($time_name='restrain_hour', $work_times), //総勤務時間(h)
            'break_hour' => Method::groupTotalTime($time_name='break_hour', $work_times), //総休憩時間(h)
            'working_hour' => Method::groupTotalTime($time_name='working_hour', $work_times), //総労働時間(h)
            'night_hour' => Method::groupTotalTime($time_name='night_hour', $work_times), //総深夜時間(h)
        ];

        return response()->json(
            compact('work_times', 'total_times')
        );
    }




    /**
     * 入力した勤怠時間のバリデーションチェック(validate_input_time)
     *
     * @param App\Http\Requests\WorkTimeRecordFormRequest $request
     * @return Json
    */
    public function validate_input_time(WorkTimeRecordFormRequest $request)
    {
        // dd($request->work_time);
        $work_time = $request->work_time;

        return response()->json([
            'comment' => 'validation OK!',
            'work_time' => $work_time,
        ]);
    }





    /**
     * 勤怠情報の修正(update_work_record)
     *
     * @param \Illuminate\Http\Request $request
     * @return redirect
    */
    public function update_work_record(Request $request)
    {
        # 出退勤時間の更新
        $work_time = WorkTime::find($request->work_time_id)->update([
            'in' => $request->work_time_in,
            'out' => $request->work_time_out,
        ]);

        # 休憩時間の更新
        $break_times = BreakTime::where('work_time_id',$request->work_time_id)->get();
        for ($i=0; $i < $break_times ->count(); $i++)
        {
            $break_times[$i]->update([
                'in' => $request->break_time_in[$i],
                'out' => $request->break_time_out[$i],
            ]);
        }


        return redirect()->route('edit_work_record',['date'=>$request->date]);
    }




    /**
     * 勤怠情報の削除(destroy_work_record)
     *
     * @param \Illuminate\Http\Request $request
     * @return redirect
    */
    public function destroy_work_record(Request $request)
    {
        # 出退勤時間の削除
        $work_time = WorkTime::find($request->work_time_id)->delete();


        return redirect()->route('edit_work_record',['date'=>$request->date]);
    }




    /**
     * 休憩の削除(destroy_break_record)
     *
     * @param \Illuminate\Http\Request $request
     * @return redirect
    */
    public function destroy_break_record(Request $request)
    {
        # 休憩時間の削除
        $break_time = BreakTime::find($request->break_time_id)->delete();


        return redirect()->route('edit_work_record',['date'=>$request->date]);
    }



}
