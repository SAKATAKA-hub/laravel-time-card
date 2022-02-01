<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;


class EditRegisterFormRequest extends FormRequest
{
    public function authorize(){ return true;}


    /**
     * バリデーションルール
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => ['max:100'],
            'email' => ['email'],
            'password' => ['regex:/\A[a-z\d]{8,100}+\z/i','confirmed'], //'conf_password'の値と一致する
        ];

        # 他のユーザーが登録しているメールアドレスの重複登録不可
        $user = User::find($this->user_id);
        if( isset($request['email']) && ($user->email !== $request['email']) )
        {
            $rules['email'] = ['email','unique:users'];

        }
        return $rules;
    }


    /**
     * エラーメッセージの設定
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.max' => '255文字以内で入力してください。',

            'email.email' => 'メールアドレスは、メールの記述形式になるように入力してください。',
            'email.unique' => '別ユーザーが登録したメールアドレスは利用できません。',

            'password.regex' => 'パスワードは、8文字以上の半角英数字のみで入力してください。',
            'password.confirmed' => '入力したパスワードが確認用と異なります。',

            'image.max' => '100KBを超えるファイルは添付できません。',
            'image.mimes' => '添付画像のファイル形式は、jpeg、png、ipg以外では保存できません。',
        ];
    }
}
