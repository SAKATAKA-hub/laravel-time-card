<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterFormRequest;
use App\Http\Requests\EditRegisterFormRequest;

use App\Models\User;


class AuthController extends Controller
{
    /**
     * ログイン処理(login)
     * @param \Illuminate\Http\Request $request
     * @return redirect
    */
    public function login(Request $request)
    {
        // ログイン成功の処理
        $credentials = $request->only('email','password');

        if (Auth::attempt($credentials)) { //ログイン成功のチェック

            $request->session()->regenerate(); //ユーザー情報をセッションに保存

            return redirect()->route('date_list');
        }

        // ログイン失敗の処理
        return back()->with('login_error','メールアドレスかパスワードが違います。');

    }




    /**
     * ログアウト処理(logout)
     * @param \Illuminate\Http\Request $request
     * @return redirect
    */
    public function logout(Request $request)
    {
        $user = Auth::user();

        Auth::logout(); //ユーザーセッションの削除

        $request->session()->invalidate(); //全セッションの削除

        $request->session()->regenerateToken(); //セッションの再作成(二重送信の防止)


        return redirect()->route('login_form');
    }





    /**
     * ユーザー登録処理(post_register)
     * @param \Illuminate\Http\RegisterFormRequest $request
     * @return redirect
    */
    public function post_register(RegisterFormRequest $request)
    {

        // ユーザー情報の保存
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),

        ]);
        $user->save();

        // ログイン処理
        $credentials = $request->only('email','password');

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            return redirect()->route('date_list');
        }

        return back()->with('error_alert','ユーザー登録に失敗しました。');
    }




    # ユーザー情報の更新(update_register)
    public function update_register(EditRegisterFormRequest $request){

        # 保存データ(パスワード変更処理の時は、空配列になる)
        $save_data = $request->only('name','email','comment');

        # パスワードの変更処理
        if($request->password)
        {
            $save_data['password'] = Hash::make($request->password);
        }

        # ユーザー情報の更新
        User::find($request->user_id)->update($save_data);


        return redirect()->route('top',);
    }




    # ユーザー情報の削除(destroy_register)
    public function destroy_register(Request $request)
    {

        # ログアウト処理
        Auth::logout(); //ユーザーセッションの削除
        $request->session()->invalidate(); //全セッションの削除
        $request->session()->regenerateToken(); //セッションの再作成(二重送信の防止)


        # 削除するユーザー
        User::find($request->user_id)->delete();


        return redirect()->route('top',);
    }




}
