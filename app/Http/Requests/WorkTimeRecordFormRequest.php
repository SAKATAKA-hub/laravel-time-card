<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class WorkTimeRecordFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return Bool
     */
    public function authorize()
    {
        return true;
    }




    /**
     * リクエストデータの加工
     * (比較できない文字列から、数値に変換した値を追加)
     *
     */
    protected function prepareForValidation()
    {
        # JSON形式データを配列形式に変換
        $work_time = json_decode($this->work_time ,true);
        $break_times = $work_time['break_times'];

        # 勤務時間のバリデーションデータ
        $w_in = (int) str_replace(':','',$work_time['input_in']);
        $w_out = $work_time['input_out']!=='00:00' ? (int) str_replace(':','',$work_time['input_out']) : 2400;
        $w_max_in = count($break_times) ? $break_times[0]['input_in'] : ( !empty($w_out)? $w_out : 2400 );
        $w_max_out = count($break_times) ?  $break_times[count($break_times)-1]['input_out'] : $w_in;

        //文字列(00:00)を数値に変換
        $this->merge([
            "valiWorkTime_in" => empty($work_time['input_in']) ? Null : (int) str_replace(':','',$w_in) ,
            "valiWorkTime_out" => empty($w_out) ? NULL : (int) str_replace(':','',$w_out) ,
            "valiWorkTime_maxIn" => empty($w_max_in) ? NULL : (int) str_replace(':','',$w_max_in) ,
            "valiWorkTime_minOut" => empty($w_max_out) ? NULL : (int) str_replace(':','',$w_max_out) ,
        ]);

        # 休憩時間のバリデーションデータ
        $vali_break_times = [];
        for ($i=0; $i < count($break_times); $i++)
        {
            $break_time = $break_times[$i];

            $b_in = (int) str_replace(':','',$break_time['input_in']);
            $b_out = $break_time['input_out']!=='00:00' ? (int) str_replace(':','',$break_time['input_out']) : 2400;
            $b_min_in = (int) str_replace( ':','',
                $i === 0 ?  $w_in : $break_times[$i -1]['input_out']
            );
            $b_max_out = (int) str_replace(':','',
                $i === count($break_times)-1 ?  $w_out : $break_times[$i +1]['input_in']
            );

            //文字列(00:00)を数値に変換
            $this->merge([
                'valiBreakTimes_'.$i.'_in' => empty($b_in) ? NULL : (int) str_replace(':','',$b_in) ,
                'valiBreakTimes_'.$i.'_out' => empty($b_out) ? NULL : (int) str_replace(':','',$b_out) ,
                'valiBreakTimes_'.$i.'_minIn'=> empty($b_min_in) ? NULL : (int) str_replace(':','',$b_min_in) ,
                'valiBreakTimes_'.$i.'_maxOut' => empty($b_max_out) ? NULL : (int) str_replace(':','',$b_max_out) ,
            ]);
        }
        $this->merge(['breakTimesCount' => count($break_times)]);

    }




    /**
     * バリデーションルール
     *
     * @return Arry
     */
    public function rules()
    {
        // dd($this->all());
        // dd($this['valiWorkTime_minOut']<$this['valiWorkTime_out']);



        # 出勤ルール
        $rules = [
            'valiWorkTime_in' => [
                'required',
                'lt:'.$this['valiWorkTime_maxIn'],
            ],
            'valiWorkTime_out' =>[
                'gt:'.$this['valiWorkTime_minOut'],
            ]
        ];

        // ルールの削除：退勤入力がまだのとき
        if( empty($this['valiWorkTime_out']) ){ $rules['valiWorkTime_out'] = [];}


        # 休憩ルールの追加
        for ($i=0; $i < $this['breakTimesCount']; $i++)
        {
            $rules['valiBreakTimes_'.$i.'_in'] = [
                'required',
                'gt:'.$this['valiBreakTimes_'.$i.'_minIn'],
                'lt:'.$this['valiBreakTimes_'.$i.'_out']
            ];
            $rules['valiBreakTimes_'.$i.'_out'] = [
                'required',
                'lt:'.$this['valiBreakTimes_'.$i.'_maxOut']
            ];

            // ルールの削除
            if( empty($this['valiBreakTimes_'.$i.'_maxOut']) ){
                $rules['valiBreakTimes_'.$i.'_out'] = [];
            }

        }

        return $rules;
    }




    /**
     * エラーメッセージの設定
     *
     * @return Array
     */
    public function messages()
    {
        # 基本メッセージ
        $messages = [
            'valiWorkTime_in.required' => '出勤時間の入力は必須です。',
            'valiWorkTime_in.lt' => '出勤時間は退勤時間より前の時間を入力してください。',
            'valiWorkTime_out.gt' => '退勤時間は出勤時間より後の時間を入力してください。',
        ];


        // 休憩があるときのメッセージを追加
        $b_count = $this['breakTimesCount'];
        if($b_count)
        {
            $messages['valiWorkTime_in.lt'] = '出勤時間は休憩開始時間1より前の時間を入力してください。';
            $messages['valiWorkTime_out.gt'] = '退勤時間は休憩終了時間'.$b_count.'より後の時間を入力してください。';
        }

        for ($i=0; $i < $this['breakTimesCount']; $i++)
        {
            $messages["valiBreakTimes_".$i."_in.required"] = '休憩開始時間'.$b_count.'の入力は必須です。';
            $messages["valiBreakTimes_".$i."_in.gt"] = $i === 0 ?
                '休憩開始時間1は、出勤時間より後の時間を入力してください。' :
                '休憩開始時間'.($i +1).'は、休憩終了時間時間'.$i.'より後の時間を入力してください。' ;
            $messages["valiBreakTimes_".$i."_in.lt"] = '休憩開始時間'.($i +1).'は、休憩終了時間'.($i +1).'より後の時間を入力して下さい。';
            $messages["valiBreakTimes_".$i."_out.required"] = '休憩終了時間'.$b_count.'の入力は必須です。';
            $messages["valiBreakTimes_".$i."_out.lt"] = $i === $b_count-1 ?
                '休憩終了時間'.($i +1).'は、退勤時間より前の時間を入力してください。' :
                '休憩終了時間'.($i +1).'は、休憩開始時間'.($i +2).'より前の時間を入力してください。' ;
        }


        return $messages;
    }




    /**
     * エラーがあったときの JSON レスポンス
     *
     * @return Json
     */
    protected function failedValidation(Validator $validator)
    {

        $response = response()->json([
            'errors' => $validator->errors(),
        ]);

        throw new HttpResponseException($response);
    }
}
