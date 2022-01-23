<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use App\Models\WorkTime;
use App\Models\BreakTime;
use Carbon\Carbon;
use Faker\Factory;



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
