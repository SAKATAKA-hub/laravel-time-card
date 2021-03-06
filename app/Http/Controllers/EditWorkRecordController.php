<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\WorkTimeRecordFormRequest;

use App\Models\WorkTime;
use App\Models\BreakTime;
use App\Models\Employee;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


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
        $user_id = Auth::user()->id;


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


        // JSON勤務データ一覧の取得
        $work_times =  Method::WorkTimesForJson($user_id,$date);


        // 集計時間
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
        $work_time = $request->work_time;

        return response()->json([
            'comment' => 'validation OK!',
        ]);
    }





    /**
     * 勤怠情報の更新(update)
     *
     * @param App\Http\Requests\WorkTimeRecordFormRequest $request
     * @return redirect
    */
    public function update(WorkTimeRecordFormRequest $request)
    {

        // dd($request->all());
        // dd($work_time);

        # データの更新処理
        //1. 休憩データの更新
        for ($i=0; $i < count($request->work_time["break_times"]); $i++)
        {
            $break_time = $request->work_time["break_times"][$i];
            BreakTime::find($break_time['id'])->update([
                'in' => $break_time['input_in'].':00',
                'out' => $break_time['input_out'].':00',
            ]);

        }

        //2. 休憩データの削除
        for ($i=0; $i < count($request->delete_break_times); $i++)
        {
            $break_time = $request->delete_break_times[$i];
            BreakTime::find($break_time['id'])->delete();
        }


        //3. 勤務データの更新
        $work_time = WorKTime::find($request->work_time['id']);
        $work_time->update([
            'in' => $request->work_time['input_in'].':00',
            'out' => !empty($request->work_time['input_out']) ?
                $request->work_time['input_out'].':00' :null,
        ]);


        # JSONデータの取得
        list($user_id, $date) =  [$work_time->employee->user_id,$work_time->date];

        //1. JSON勤務データ一覧の取得
        $work_times =  Method::WorkTimesForJson($user_id,$date);

        //2. 集計時間
        $total_times = [
            'restrain_hour' => Method::groupTotalTime($time_name='restrain_hour', $work_times), //総勤務時間(h)
            'break_hour' => Method::groupTotalTime($time_name='break_hour', $work_times), //総休憩時間(h)
            'working_hour' => Method::groupTotalTime($time_name='working_hour', $work_times), //総労働時間(h)
            'night_hour' => Method::groupTotalTime($time_name='night_hour', $work_times), //総深夜時間(h)
        ];

        //3. コメント
        $comment = 'update OK';

        return response()->json([
            compact('comment','work_times', 'total_times')
        ]);


    }




    /**
     * 勤怠情報の削除(destroy)
     *
     * @param App\Http\Requests\WorkTimeRecordFormRequest $request (JSON形式データをPHP配列に変換)
     * @return redirect
    */
    public function destroy(WorkTimeRecordFormRequest $request)
    {


        $work_time = WorKTime::find($request->work_time['id']);
        list($user_id, $date) =  [$work_time->employee->user_id,$work_time->date];

        # 勤務データの削除
        $work_time->delete();

        # JSONデータの取得
        //1. JSON勤務データ一覧の取得
        $work_times =  Method::WorkTimesForJson($user_id,$date);

        //2. 集計時間
        $total_times = [
            'restrain_hour' => Method::groupTotalTime($time_name='restrain_hour', $work_times), //総勤務時間(h)
            'break_hour' => Method::groupTotalTime($time_name='break_hour', $work_times), //総休憩時間(h)
            'working_hour' => Method::groupTotalTime($time_name='working_hour', $work_times), //総労働時間(h)
            'night_hour' => Method::groupTotalTime($time_name='night_hour', $work_times), //総深夜時間(h)
        ];

        //3. コメント
        $comment = 'update OK';

        return response()->json([
            compact('comment','work_times', 'total_times')
        ]);
    }


}
