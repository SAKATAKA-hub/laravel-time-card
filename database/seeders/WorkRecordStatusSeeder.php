<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WorkRecordStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::parse('now');

        $email = $now->format('YmdHis').'@mail.co.jp';
        $user = new User([
            'name' => 'test user',
            'email' => $email,
            'password'  =>  Hash::make('password'),
            'easy_user' => 1,
        ]);
        $user->save();
    }
}
