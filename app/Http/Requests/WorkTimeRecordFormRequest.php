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
        $this->in = str_replace(':','',$this->in);
        $this->out = str_replace(':','',$this->out);
        $this->merge([
            'vali_in' => (int) str_replace(':','',$this->in),
            'vali_out' => (int) str_replace(':','',$this->out),
        ]);
    }


    /**
     * バリデーションルール
     *
     * @return Arry
     */
    public function rules()
    {

        # バリデーションルール
        $rules = [
            'vali_in' => ['required','lt:'.$this->vali_out],
            'vali_out' => 'required',

        ];


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
