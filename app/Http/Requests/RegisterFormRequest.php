<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterFormRequest extends FormRequest
{
    public function authorize(){ return true;}

    public function rules()
    {
        return [
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'regex:/\A[a-z\d]{8,100}+\z/i|confirmed',
            'password_confirmation' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'ユーザーネームは入力必須です。',
            'name.max' => '255文字以内で入力してください。',

            'email.required' => 'メールアドレスは入力必須です。',
            'email.email' => 'メールアドレスは、メールの記述形式になるように入力してください。',
            'email.unique' => '別ユーザーが登録したメールアドレスは利用できません。',

            'password.regex' => 'パスワードは、8文字以上の半角英数字のみで入力してください。',
            'password.confirmed' => '入力したパスワードが確認用と異なります。',

            'conf_password.required' => 'パスワード(確認用)は入力必須です。',
        ];
    }

}
