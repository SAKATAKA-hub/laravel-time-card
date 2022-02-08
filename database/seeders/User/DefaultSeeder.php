<?php

namespace Database\Seeders\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;




class DefaultSeeder extends Seeder
{
    /**
     * 1.ユーザの新規作成(テストユーザー)
     *
     * @return void
     */
    public function run()
    {

        $now = Carbon::parse('now');

        $user = new User([
            'name' => 'SAKATAKA',
            'email' => 'sakataka@mail.co.jp',
            'password'  =>  Hash::make('password'),
            'app_dministrator' => 1, //アプリケーション管理者
        ]);
        $user->save();

    }

}
