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

        //文字列を数値に変換
        $this->merge([
            "vali_work_time[in]" => empty($work_time['input_in']) ? Null : (int) str_replace(':','',$w_in) ,
            "vali_work_time[out]" => empty($w_out) ? NULL : (int) str_replace(':','',$w_out) ,
            "vali_work_time[max_in]" => empty($w_max_in) ? NULL : (int) str_replace(':','',$w_max_in) ,
            "vali_work_time[min_out]" => empty($w_max_out) ? NULL : (int) str_replace(':','',$w_max_out) ,
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

            //文字列を数値に変換
            $this->merge([
                "vali_break_times[$i][in]" => empty($b_in) ? NULL : (int) str_replace(':','',$b_in) ,
                "vali_break_times[$i][out]" => empty($b_out) ? NULL : (int) str_replace(':','',$b_out) ,
                "vali_break_times[$i][min_in]"=> empty($b_min_in) ? NULL : (int) str_replace(':','',$b_min_in) ,
                "vali_break_times[$i][max_out]" => empty($b_max_out) ? NULL : (int) str_replace(':','',$b_max_out) ,
            ]);
        }


    }




    /**
     * バリデーションルール
     *
     * @return Arry
     */
    public function rules()
    {
        // dd($this['vali_work_time[in]']);

        # バリデーションルール
        $rules = [
            "vali_work_time[in]" => ['required'],
            "vali_work_time[out]" => ['required'],
        ];


        // $rules = [
        //     // 'vali_in' => ['required','lt:'.$this->vali_out],
        //     // 'vali_out' => 'required',
        // ];


        return $rules;
    }




    /**
     * エラーメッセージの設定
     *
     * @return Array
     */
    public function messages()
    {
        return [
            'vali_in.required' => '出勤時間は入力必須です。',
            'vali_in.lt' => '出勤時間は退勤時間より後の時間では入力できません。',
            'vali_out.required' => '退勤時間は入力必須です。',
        ];
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
