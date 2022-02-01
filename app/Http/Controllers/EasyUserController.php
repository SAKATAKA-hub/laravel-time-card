<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Employee;
use App\Models\WorkTime;
use App\Models\BreakTime;

use Carbon\Carbon;
use Faker\Factory;

use Database\Seeders\WorkRecordStatusSeeder;



class EasyUserController extends Controller
{
    public function create_easy_user(Request $request)
    {
        \Database\Seeders\WorkRecordForTestSeeder::run();



        # フェイクデータの作成
        // WorkRecordStatusSeeder::run();

        # 作成データの加工
        $user = User::orderBy('id','desc')->first();
        $now = Carbon::parse('now');
        $email = $now->format('Y/m/d/H:i:s').'@mail.co.jp';

        $user->update([
            'name' => 'ワンタイムユーザー',
            'email' => $email,
            'easy_user' => 1,
        ]);



        # セッションの削除
        Auth::logout(); //ユーザーセッションの削除
        $request->session()->invalidate(); //全セッションの削除
        $request->session()->regenerateToken(); //セッションの再作成(二重送信の防止)


        # ログイン処理
        $credentials = [
            'email' => $email,
            'password' => 'password',
        ];
        if (Auth::attempt($credentials))
        {
            $request->session()->regenerate();
            return redirect()->route('date_list');
        }

        // ログイン失敗の処理
        return back();


    }
}
