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
        # DBデータの作成
        //1.ユーザの新規作成
        \Database\Seeders\User\EasySeeder::run();
        // \Database\Seeders\User\TestSeeder::run();
        // \Database\Seeders\User\DefaultSeeder::run();

        //2.フェイク従業員データの作成
        \Database\Seeders\EmployeesSeeder::run();

        //3.フェイク勤務記録の作成
        \Database\Seeders\WorkRecord\ThreeMonthsSeeder::run(); //(3ヶ月分)
        // \Database\Seeders\WorkRecord\ThreeDaysSeeder::run(); //(3日分)


        # セッションの削除
        Auth::logout(); //ユーザーセッションの削除
        $request->session()->invalidate(); //全セッションの削除
        $request->session()->regenerateToken(); //セッションの再作成(二重送信の防止)


        # ログイン処理
        $user = User::orderBy('id','desc')->first();
        $credentials = [
            'email' => $user->email,
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
