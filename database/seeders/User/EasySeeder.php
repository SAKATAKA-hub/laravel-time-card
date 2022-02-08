<?php

namespace Database\Seeders\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;




class EasySeeder extends Seeder
{
    /**
     * 1.ユーザの新規作成(簡単ログインユーザー)
     *
     * @return void
     */
    public function run()
    {

        $now = Carbon::parse('now');

        $user = new User([
            'name' => 'ワンタイムユーザー',
            'email' => $now->format('YmdHis').'@mail.co.jp',
            'password'  =>  Hash::make('password'),
            'easy_user' => 1,
        ]);
        $user->save();

    }

}
