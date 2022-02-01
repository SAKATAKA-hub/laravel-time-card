<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Employee;
use App\Models\WorkTime;
use App\Models\BreakTime;

use Carbon\Carbon;
use Faker\Factory;

use Database\Seeders\WorkRecordStatusSeeder;



class EasyUserController extends Controller
{
    public function create_easy_user()
    {
        # フェイクデータの作成
        WorkRecordStatusSeeder::run();

        # シーダーの利用
        $user = User::orderBy('id','desc')->first();

        dd($user->employees);






        # フェイクデータの作成
        WorkRecordStatusSeeder::run();

        # シーダーの利用
        $user = User::orderBy('id','desc')->first();

        # 作成データの加工
        $now = Carbon::parse('now');
        $email = $now->format('YmdHis').'@mail.co.jp';

        $user->update([
            'name' => '簡単ログイン登録',
            'email' => $email,
            'easy_user' => 1,
        ]);


        dd($user);
    }
}
